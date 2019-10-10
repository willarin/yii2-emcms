<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;


BootstrapAsset::register($this);

$this->title = Yii::t('app', 'List of pages'); ?>
    <H1 class="text-center">List of pages</H1>
<?= Html::a(Yii::t('app', 'Add page'), ['create'], ['class' => 'btn btn-primary']) ?>
<?= GridView::widget([
    'dataProvider' => $listDataProvider,
    'layout' => "{pager}\n{items}\n{summary}\n",
    'summary' => '',
    'columns' => [
        'id',
        'pageType:ntext',
        [
            'attribute' => 'route',
            'format' => 'raw',
            'label' => Yii::t('app', 'Route')
        ],
        'title:ntext',
        'description:ntext',
        [
            'attribute' => 'listing_name',
            'label' => Yii::t('app', 'Listing'),
            'format' => 'raw',
            'content' => function ($data) {
                $result = '';
                if (isset($data->listing)) {
                    $result = Html::a($data->listing->name, ['listing/update', 'id' => $data->listing->id]);
                }
                return $result;
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'width:70px;'],
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('Edit', ['update', 'id' => $model->id]);
                },
                'view' => function ($url, $model) {
                    if (\Yii::$app->user->identity->isAdmin) {
                        return Html::a('Open', ['@web/' . $model->route], [
                            'target' => '_blank'
                        ]);
                    }
                },
                'delete' => function ($url, $model) {
                    return Html::a('Delete', ['delete', 'id' => $model->id], [
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