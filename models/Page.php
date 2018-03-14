<?php

namespace almeyda\emcms\models;

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
                'message' => \Yii::t('app', 'Route has been already taken')
            ],
            'titleUnique' => [
                'title',
                'unique',
                'message' => \Yii::t('app', 'Title must be unique')
            ],
            'contentString' => [['route', 'title', 'content', 'description'], 'required'],
            'routeMax' => ['route', 'string', 'min' => 1, 'max' => 2048],
            'titleMax' => ['title', 'string', 'min' => 1, 'max' => 300],
        ];
    }

    public static function LoadPagesByTheme($theme)
    {
        $result = Page::find()->Where(['LIKE', 'page.route', $theme])->orderBy(['id' => SORT_DESC])->all();
        return $result;
    }

}