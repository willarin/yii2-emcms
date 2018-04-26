<?php

namespace almeyda\emcms\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;

class Page extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

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

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'timeCreated',
                'updatedAtAttribute' => 'timeUpdated',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['route', 'title', 'content', 'description'];
        $scenarios['update'] = ['route', 'title', 'content', 'description', 'id'];
        return $scenarios;
    }

    public static function LoadPagesByTheme($theme)
    {
        $result = Page::find()->Where(['LIKE', 'page.route', $theme])->orderBy(['timeUpdated' => SORT_DESC])->all();
        return $result;
    }

    /**
     * Method forms string of settings for tinyMce custom menu button(https://www.tinymce.com/docs/demo/custom-toolbar-menu-button/)
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
    public function FormMenuItems($cssTemplates)
    {
        $menuItems = "";
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
        return $menuItems;
    }
}