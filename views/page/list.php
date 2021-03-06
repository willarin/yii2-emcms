<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;


BootstrapAsset::register($this);

$this->title = Yii::t('app', 'List of pages'); ?>
    <H1 class="text-center">List of pages</H1>
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
echo Html::a(Yii::t('app', 'Add page'), ['create'], ['class' => 'btn btn-primary']) ?>