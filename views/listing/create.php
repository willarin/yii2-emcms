<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\BootstrapAsset;
use dominus77\tinymce\TinyMce;
use yii\grid\GridView;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\web\JsExpression;
use dominus77\tinymce\components\MihaildevElFinder;

BootstrapAsset::register($this);

$this->title = Yii::t('user', $model->scenario == 'create' ? 'Create listing' : 'Update listing'); ?>
<H1 class="text-center"><?= $model->scenario == 'create' ? 'Page creation' : 'Page updating' ?></H1>
<?php $form = ActiveForm::begin(['class' => 'create-form']); ?>
<?= $form->field($model,
    'name')->textInput(['placeholder' => Yii::t('app', 'Enter name of listing')])->label(Yii::t('app', 'Listing name')); ?>
<?= Html::submitButton(Yii::t('app', $model->scenario == 'create' ? 'Create listing' : 'Update listing'),
    ['class' => 'btn btn-primary', 'name' => 'create-button', 'style' => 'margin-bottom: 15px']
);
ActiveForm::end();
?>
<?= GridView::widget([
    'dataProvider' => $listDataProvider,
    'layout' => "{pager}\n{items}\n{summary}\n",
    'summary' => '',
    'columns' => [
        'id',
        [
            'attribute' => 'route',
            'format' => 'raw',
            'label' => Yii::t('app', 'Route')
        ],
        'title:ntext',
        'description:ntext',
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'width:70px;'],
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil" data-method="POST"></span>', ['update', 'id' => $model->id]);
                },
                'view' => function ($url, $model) {
                    if (\Yii::$app->user->identity->isAdmin) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['@web/' . $model->route], ['target' => '_blank'
                        ]);
                    }
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                        'class' => '',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you absolutely sure ? You will lose all the information about page "' . $model->title . '"'),
                            'method' => 'post',
                        ],
                    ]);
                }
            ]
        ],
    ],
]);
echo Html::a(Yii::t('app', 'Add page'), ['page/create', 'listingId' => $model->id], ['class' => 'btn btn-primary', 'data' => ['method' => 'post']]) ?>
