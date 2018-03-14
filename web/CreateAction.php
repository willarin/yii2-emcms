<?php

namespace almeyda\emcms\web;

use Yii;
use yii\web\ViewAction as Action;
use yii\web\ForbiddenHttpException;
use almeyda\emcms\models\Page;

class CreateAction extends Action
{
    public function run()
    {
        if (Yii::$app->user->getIsGuest()) {
            throw new ForbiddenHttpException();
        } else {
            $model = new Page();
            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                $output = $this->controller->redirect('list');
            } else {
                $output = $this->controller->render('create', ['model' => $model]);
            }
        }
        return $output;
    }
}
