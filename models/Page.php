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
                'message' => \Yii::t('app', 'Route has been already taken'), 'on' => ['create', 'update']
            ],
            'titleUnique' => [
                'title',
                'unique',
                'message' => \Yii::t('app', 'Title must be unique'), 'on' => ['create', 'update']
            ],
            'fieldsRequired' => [['route', 'title', 'content', 'description'], 'required', 'on' => ['create', 'update']],
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
        $listing = Listing::findOne(\Yii::$app->getRequest()->get('listingId'));
        $listing->link('pages', $this);
        parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['route', 'title', 'content', 'description'];
        $scenarios['update'] = ['route', 'title', 'content', 'description', 'id'];
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
}
