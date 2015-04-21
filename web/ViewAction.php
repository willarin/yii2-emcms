<?php

namespace almeyda\emcms\web;

use Yii;
use yii\web\ViewAction as Action;
use yii\web\NotFoundHttpException;

class ViewAction extends Action
{
    public function run()
    {
        try {
            $output = parent::run();
        } catch (NotFoundHttpException $e) {
            if (YII_DEBUG) {
                throw new NotFoundHttpException($e->getMessage());
            } else {
                throw new NotFoundHttpException(
                    Yii::t('yii', 'Page not found.')
                );
            }
        }

        return $output;
    }

}
