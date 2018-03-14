<?php

namespace almeyda\emcms\web;

use Yii;
use yii\web\ViewAction as Action;
use yii\web\ForbiddenHttpException;
use almeyda\emcms\models\Page;
use yii\data\ActiveDataProvider;

class ListAction extends Action
{
    public function run()
    {
        if (Yii::$app->user->getIsGuest()) {
            throw new ForbiddenHttpException();
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => Page::find(),
                'pagination' => [
                    'defaultPageSize' => 10,
                    'pageSize' => 20,
                    'pageSizeLimit' => [1, 50],
                ],
            ]);
            $output = $this->controller->render('list', ['listDataProvider' => $dataProvider]);
        }
        return $output;
    }
}
