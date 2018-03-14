<?php

namespace almeyda\emcms\web;

use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use almeyda\emcms\models\Page;
use yii\helpers\StringHelper;

class LoadAction extends Action
{
    /**
     * @var integer the id of the emcms_page record in the database.
     */
    public $id = '';

    public function run()
    {
        try {
            $this->id = Yii::$app->request->get("id");
            $page = Page::findOne(['id' => $this->id]);
            $theme = StringHelper::dirname($page->getAttribute('route'));
            if ($theme !== '') {
                Yii::$app->request->setQueryParams(['theme' => $theme]);
            }
            $output = $this->controller->render('load', ['model' => $page]);
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
