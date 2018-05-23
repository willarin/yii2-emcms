<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

namespace almeyda\emcms\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use almeyda\emcms\models\Page;
use yii\data\ActiveDataProvider;
use yii\helpers\StringHelper;
use yii\filters\AccessControl;

/**
 * Controller contains all actions to manipulate pages at the admin side and render at the client side.
 */
class PageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $defaultAction = 'list';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [];
        if ($this->module->cacheFiles) {
            $behaviors = [
                [
                    'class' => 'yii\filters\PageCache',
                    'duration' => 0,
                    'variations' => [
                        Yii::$app->language,
                    ],

                ],
            ];
        }
        $behaviors['access'] =
            [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'list', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'page' => [
                'class' => 'almeyda\emcms\web\ViewAction',
            ],
        ];
    }

    /**
     * Creates new Page
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $this->layout = $this->module->adminLayout;
        $model = new Page();
        $model->setScenario('create');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $output = $this->redirect('../listing/update?id=' . \Yii::$app->getRequest()->get('listingId'));
        } else {
            $output = $this->render('create', ['model' => $model, 'templatesItems' => $model->FormMenuItems($this->module->cssTemplates)]);
        }
        return $output;
    }

    /**
     * Displays list of pages
     * @return string
     */
    public function actionList()
    {
        $this->layout = $this->module->adminLayout;
        $dataProvider = new ActiveDataProvider([
            'query' => Page::find(),
            'pagination' => [
                'defaultPageSize' => 10,
                'pageSize' => 10,
                'pageSizeLimit' => [1, 50],
            ],
        ]);
        $output = $this->render('list', ['listDataProvider' => $dataProvider]);
        return $output;
    }

    /**
     * Displays page by the given id
     * @param $id - id of page to load from database
     */
    public function actionLoad($id)
    {
        try {
            $page = Page::findOne(['id' => $id]);
            if ($page) {
                $this->layout = $this->module->customLayout;
            }
            $output = $this->render('load', ['model' => $page]);
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

    /**
     * Updates data for the Page
     * @param $id - id of Page record
     */
    public function actionUpdate($id)
    {
        $this->layout = $this->module->adminLayout;
        $model = Page::findOne($id);
        $model->setScenario('update');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $output = $this->redirect('../listing/update?id=' . \Yii::$app->getRequest()->get('listingId'));
        } else {
            $output = $this->render('create', ['model' => $model, 'templatesItems' => $model->FormMenuItems($this->module->cssTemplates)]);
        }
        return $output;
    }

    /**
     * Deletes Page by given id
     * @param $id - id of Page
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->layout = $this->module->adminLayout;
        $model = Page::findOne($id);
        $model->delete();
        $output = $this->redirect('../listing/update?id=' . \Yii::$app->getRequest()->get('listingId'));
        return $output;
    }
}
