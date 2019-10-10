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
use almeyda\emcms\models\Listing;
use yii\data\ActiveDataProvider;
use yii\helpers\StringHelper;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

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
                'class' => AccessControl::class,
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
    public function beforeAction($action)
    {
        if (in_array($action->id, ['create', 'update', 'delete', 'list'])) {
            $this->layout = $this->module->adminLayout;
        }
        return parent::beforeAction($action);
    }
    
    /**
     * Creates new Page
     * @return string|\yii\web\Response
     */
    public function actionCreate($id = 0)
    {
        if ($id) {
            $model = Page::findOne($id);
            $model->setScenario('update');
        } else {
            $model = new Page();
            $model->setScenario('create');
        }

        $result = true;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //upload image
            $uploadedFile = UploadedFile::getInstance($model, 'image');
            if ($uploadedFile) {
                $model->image = $uploadedFile;
                $fileName = md5(uniqid()) . '_' . $model->image->baseName . '.' . $model->image->extension;
                $model->image->saveAs($this->module->getUploadPath() . DIRECTORY_SEPARATOR . $fileName);
                $model->image->name = $fileName;
            }
            if ($model->save()) {
                $result = true;
                if (\Yii::$app->getRequest()->get('listingId')) {
                    $output = $this->redirect('../listing/update?id=' . \Yii::$app->getRequest()->get('listingId'));
                } else {
                    $output = $this->redirect('list');
                }
            } else {
                $result = false;
            }
        }
        if ($result) {
            $output = $this->render('create', [
                'model' => $model,
                'templatesItems' => $model->FormMenuItems($this->module->cssTemplates),
                'selectData' => Listing::selectListings()
            ]);
        }
        return $output;
    }

    /**
     * Updates data for the Page
     * @param $id - id of Page record
     * @return mixed
     */
    public function actionUpdate($id)
    {
        return $this->runAction('create', ['id' => $id]);
    }

    /**
     * Displays list of pages
     * @return string
     */
    public function actionList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Page::find()->joinWith('listing'),
            'pagination' => $this->module->config['pagination'],
        ]);
        $dataProvider->sort->attributes['listing_name'] = [
            'asc' => ['listing.name' => SORT_ASC],
            'desc' => ['listing.name' => SORT_DESC],
        ];
        $output = $this->render('list', ['listDataProvider' => $dataProvider]);
        return $output;
    }

    /**
     * Displays page by the given id
     * @param $id - id of page to load from database
     * @return mixed
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
     * Deletes Page by given id
     * @param $id - id of Page
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $model = Page::findOne($id);
        $model->delete();
        if (\Yii::$app->getRequest()->get('listingId')) {
            $output = $this->redirect('../listing/update?id=' . \Yii::$app->getRequest()->get('listingId'));
        } else {
            $output = $this->redirect('list');
        }
        return $output;
    }
}
