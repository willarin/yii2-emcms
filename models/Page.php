<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

namespace almeyda\emcms\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Page
 * @package almeyda\emcms\models
 */
class Page extends ActiveRecord
{
    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            'routeUnique' => [
                'route',
                'unique',
                'message' => \Yii::t('app', 'Route has been already taken'),
                'on' => ['create', 'update']
            ],
            'titleUnique' => [
                'title',
                'unique',
                'message' => \Yii::t('app', 'Title must be unique'),
                'on' => ['create', 'update']
            ],
            'fieldsRequired' => [['pageType', 'route', 'title', 'content'], 'required', 'on' => ['create', 'update']],
            'urlPathValid' => [['route'], 'match', 'pattern' => '/^([\w-])([\/\w \.-]*)*\/?$/'],
            'idRequired' => [['id'], 'required', 'except' => 'create'],
            'routeMax' => ['route', 'string', 'min' => 1, 'max' => 256],
            'titleMax' => ['title', 'string', 'min' => 1, 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'timeCreated',
                'updatedAtAttribute' => 'timeUpdated',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Creates new or updates existing ListingPage record when Page is saved
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->unlinkAll('listing', true);
        if (\Yii::$app->getRequest()->get('listingId')) {
            $listing = Listing::findOne(\Yii::$app->getRequest()->get('listingId'));
        } elseif (\Yii::$app->getRequest()->getBodyParam('listingId')) {
            $listing = Listing::findOne(\Yii::$app->getRequest()->getBodyParam('listingId'));
        } else {
            $listing = null;
        }
        if ($listing) {
            $this->link('listing', $listing);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['route', 'title', 'content', 'description', 'isMenu', 'pageType', 'hidden', 'headerHtml', 'footerHtml', 'parentId', 'sort'];
        $scenarios['update'] = ArrayHelper::merge($scenarios['create'], ['id']);
        return $scenarios;
    }

    /**
     * Method forms list of Pages to display for users by the theme name from the route
     * @param $theme
     * @return mixed
     */
    public static function loadPagesByTheme($theme)
    {
        $result = Page::find()->where(['LIKE', 'page.route', $theme])->orderBy(['timeUpdated' => SORT_DESC])->all();
        return $result;
    }

    /**
     * Method forms list of Pages to display for users by the list name
     * @param $listName string name of list to be displayed
     * @return mixed
     */
    public static function loadPagesByListName($listName)
    {
        $result = Page::find()->leftJoin('listingPage', 'page.id = listingPage.pageId')->
        leftJoin('listing', 'listing_page.listingId = listing.id')->orderBy(['listing_page.sort' => SORT_ASC])->
        where(['listing.name' => $listName])->all();
        return $result;
    }

    /**
     * Method forms string of settings for tinyMce custom menu button
     * @link https://www.tinymce.com/docs/demo/custom-toolbar-menu-button
     * @param $cssTemplates module property $cssTemplates e.g.
     * cssTemplates => [
     *      template_name1 => [
     *          '/path/to/css/file1.css',
     *          '/path/to/css/file2.css',
     *      ]
     *      template_name2 => [
     *          '/path/to/css/file3.css',
     *          '/path/to/css/file4.css',
     *      ]
     * ]
     * @return string formatted string for tinyMce setup section
     */
    public function formMenuItems($cssTemplates)
    {
        $menuItems = '';
        if (is_array($cssTemplates)) {
            foreach ($cssTemplates as $templateName => $templateCssValues) {
                foreach ($templateCssValues as $index => $pathToCss) {
                    $templateCssValues[$index] = "'" . $pathToCss . "'";
                }
                $menuItems .= "{
                text: '" . $templateName . "',
                onclick: function() {
                    editor.contentCSS = [" . implode(',', $templateCssValues) . "];
                    editor.render();
                    }
                },";
            }
        }
        return $menuItems;
    }

    /**
     * @param bool $exclidedParentId
     * @return array
     */
    public function getPages($exclidedParentId = false)
    {
        $query = self::find()->where(['pageType' => 'page']);
        if ($exclidedParentId) {
            $query->andWhere(['NOT IN', 'id', is_array($exclidedParentId) ? $exclidedParentId : [$exclidedParentId]]);
        }
        $query->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC]);
        return $query->asArray()->all();
    }

    /**
     * @param $route
     * @return array|ActiveRecord[]
     */
    public function getPageByRoute($route)
    {
        $query = self::find()->where(['route' => $route]);
        return $query->one();
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function getChildren()
    {
        $query = self::find()->where(['parentId' => $this->id]);
        $query->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC]);
        return $query->all();
    }

    /**
     * @return $this
     * @throws \yii\base\InvalidConfigException
     */
    public function getListing()
    {
        return $this->hasOne(Listing::class, ['id' => 'listingId'])->viaTable('listing_page', ['pageId' => 'id']);
    }

    /**
     * @return mixed
     */
    public function renderContent()
    {
        $result = $this->content;
        preg_match_all(
            '/\#\#(.+?)\#\#/i',
            $result,
            $matches,
            PREG_PATTERN_ORDER
        );

        if (isset($matches[0])) {
            foreach ($matches[0] as $key => $match) {
                $section = $this->getPageByRoute(str_replace('#', '', $match));
                $content = ($section) ? $section->content : '';
                $result = str_replace($match, $content, $result);

                //replace trash <p> tags at the beginning and at the end
                $expressions = ['/^(\<p\>)/i', '/(\<\/p\>)$/i'];
                foreach($expressions as $expression) {
                    while (preg_match($expression, $result)) {
                        $result = preg_replace($expressions, "", $result);
                    }
                }


            }
        }
        return $result;
    }

}
