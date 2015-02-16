<?php

namespace almeyda\emcms\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;


class PageController extends Controller
{
	public $defaultAction = 'index';
	
	public function actionIndex($page = 'index')
    {
        try {
		    return $this->render('//page/'.$page); 
		} catch (InvalidParamException $e) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'), $e->getCode(), $e);
        }
		
    }
	
}
