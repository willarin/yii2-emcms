<?php

namespace almeyda\emcms;

/**
 * The Emcms Module provides content rendering functionality
 *
 */
class Module extends \yii\base\Module
{
    public $cacheFiles = false;
    public $customLayout = 'main';
    public $defaultRoute = 'page';
    public $adminLayout = 'admin';
    public $imagePath = 'images';
    public $cssTemplates = [];

    public function init()
    {
        parent::init();
        $this->controllerMap = [
            'elfinder' => [
                'class' => 'mihaildev\elfinder\Controller',
                'access' => ['@'],
                'disabledCommands' => ['netmount'],
                'roots' => [
                    [
                        'baseUrl' => '@web',
                        'basePath' => '@webroot',
                        'path' => $this->imagePath,
                        'name' => 'images'
                    ],
                ],
            ]
        ];
    }
}
