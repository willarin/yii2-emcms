<?php

namespace almeyda\emcms\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;


class PageController extends Controller
{
	public $defaultAction = 'page';
	
	public function actions()
    {
        return [
            'page' => [
                'class' => 'yii\web\ViewAction',
				'viewPrefix' => $this->module->appThemePath.$this->module->viewsFolder,
				'layout' => $this->module->appThemePath.$this->module->layoutPath,
            ],
        ];
    }
	
	/**
     * setup theme map used for the application
     * @param Action $action the action to be executed.
     * @return boolean whether the action should continue to be executed.
     */
    public function beforeAction($action)
    {
        /*
		if ($this->module->appThemePath) {
			$this->getView()->theme = Yii::createObject([
				'class' => '\yii\base\Theme',
				'pathMap' => ['@app/views' => $this->module->appThemePath],
				'baseUrl' => $this->module->webThemePath,
			]);
		}
		 * */
		return true;
    }
	
	public function actionIlndex($page = 'index')
    {
        try {
		    return $this->render('//page/'.$page); 
		} catch (InvalidParamException $e) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'), $e->getCode(), $e);
        }
		
    }
	
}
