<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

namespace almeyda\emcms;

/**
 * The Emcms Module provides content rendering functionality
 *
 */
class Module extends \yii\base\Module
{
    public $cacheFiles = false;

    /**
     * @var string layout file used for the client side rendering
     */
    public $customLayout = 'main';
    public $defaultRoute = 'page';
    public $adminLayout = 'admin';
    public $imagePath = 'images';
    public $uploadPath = false;
    public $cssTemplates = [];
    public $config = [
        'pagination' => [
            'defaultPageSize' => 10,
            'pageSize' => 10,
            'pageSizeLimit' => [1, 50],
        ],
    ];

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
                        'path' => \Yii::getAlias($this->imagePath),
                        'name' => 'images'
                    ],
                ],
            ]
        ];
    }
    
    public function getUploadPath()
    {
        return \Yii::getAlias('@webroot') . ($this->uploadPath ? $this->uploadPath : '/uploads');
    }
}
