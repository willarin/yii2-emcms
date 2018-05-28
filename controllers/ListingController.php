<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

namespace almeyda\emcms\controllers;

use almeyda\emcms\models\ListingPage;
use almeyda\emcms\models\Page;
use Yii;
use yii\web\Controller;
use almeyda\emcms\models\Listing;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

/**
 * Controller contains CRUD actions for work with listings of pages in the admin side.
 * It also provides rendering of listing at the client side.
 */
class ListingController extends Controller
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
        $behaviors = parent::behaviors();
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
     * Creates new Listing
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $this->layout = $this->module->adminLayout;
        $model = new Listing();
        $model->setScenario('create');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $output = $this->redirect('list');
        } else {
            $output = $this->render('create', ['model' => $model]);
        }
        return $output;
    }

    /**
     * Displays list of listings
     * @return string
     */
    public function actionList()
    {
        $this->layout = $this->module->adminLayout;
        $dataProvider = new ActiveDataProvider([
            'query' => Listing::find(),
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
     * Updates data for the Listing
     * @param $id - id of Listing record
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $this->layout = $this->module->adminLayout;
        $model = Listing::findOne($id);
        $model->setScenario('update');
        $dataProvider = new ActiveDataProvider([
            'query' => Page::find()->where(['page.id' => $model->getPageIds($id)])->
            join('INNER JOIN', 'listing_page lp', 'page.id = lp.pageId')->orderBy('lp.sort'),
            'pagination' => [
                'defaultPageSize' => 10,
                'pageSize' => 10,
                'pageSizeLimit' => [1, 50],
            ],
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $output = $this->redirect('list');
        } else {
            $output = $this->render('create', ['model' => $model, 'listDataProvider' => $dataProvider]);
        }
        return $output;
    }

    /**
     * Deletes Listing by given id
     * @param $id - id of Listing
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->layout = $this->module->adminLayout;
        $model = Listing::findOne($id);
        $model->delete();
        $output = $this->redirect('list');
        return $output;
    }

    /**
     * Fills the field 'sort' of ListingPage records with values of array sent by ajax request from the GridView
     */
    public function actionSort()
    {
        if (!\Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }

        if (isset($_POST['items']) && is_array($_POST['items'])) {
            foreach ($_POST['items'] as $i => $item) {
                $page = ListingPage::findOne(['pageId' => $item]);
                $page->updateAttributes([
                    'sort' => $i,
                ]);
            }
        }
    }
}
