<?php

namespace almeyda\emcms;

use Yii;
use yii\base\BootstrapInterface;
use dektrium\user\Bootstrap as UserBootstrap;
use yii\base\InvalidConfigException;
use almeyda\emcms\models\Page;
use yii\web\Application as WebApplication;

/**
 * Bootstrap class registers module user for usage by extension.
 *
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     * */
    public function bootstrap($app)
    {
        if ($app->hasModule('emcms/user')) {
            if ($app instanceof WebApplication) {
                $app->setHomeUrl('/emcms');
            }
            $components = $app->getComponents();
            if (isset($components['urlManager'])) {
                if (in_array('page', Yii::$app->db->schema->tableNames)) {
                    $rulesToAdd = [
                        'emcms-list' => [
                            'pattern' => 'emcms/page/list',
                            'route' => 'emcms/page/list'
                        ],
                        'emcms-add' => [
                            'pattern' => 'emcms/page/create',
                            'route' => 'emcms/page/create'
                        ],
                    ];
                    $pages = Page::find()->all();
                    foreach ($pages as $page) {
                        $rulesToAdd[str_replace('/', '', $page['route'])] = array('pattern' => $page['route'], 'route' => 'emcms/page/load', 'defaults' => ['id' => $page['id']]);
                    }
                    $app->getUrlManager()->addRules(
                        $rulesToAdd
                        , false);
                }
            }
            if (isset($components['view'])) {
                $app->getView()->theme->pathMap['@dektrium/user/views'] = '@almeyda/emcms/views';
            }
            if (!$app->hasModule('user')) {
                $user_config = $app->getModule('emcms/user');
                $app->setModules(['user' => $user_config]);
                $user = new UserBootstrap();
                $user->bootstrap($app);
                \yii\base\Event::on(
                    \dektrium\user\controllers\SecurityController::className(),
                    \dektrium\user\controllers\SecurityController::EVENT_AFTER_LOGIN,
                    function () {
                        Yii::$app->response->redirect(array('/emcms'))->send();
                        Yii::$app->end();
                    }
                );
                \yii\base\Event::on(
                    \dektrium\user\controllers\SecurityController::className(),
                    \dektrium\user\controllers\SecurityController::EVENT_AFTER_LOGOUT,
                    function () {
                        Yii::$app->response->redirect(array('/user/login'))->send();
                        Yii::$app->end();
                    }
                );

            } else {
                throw new InvalidConfigException('You already have module with id="user" in configuration');
            }
        }
    }
}
