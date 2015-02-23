<?php

namespace almeyda\emcms\controllers;

use Yii;
use yii\web\Controller;


class PageController extends Controller
{
	
	public function actions()
    {
        return [
            'page' => [
                'class' => 'yii\web\ViewAction',
            ],
        ];
    }

	
}
