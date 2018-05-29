<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;


BootstrapAsset::register($this);

$this->title = Yii::t('app', 'Page listings'); ?>
    <H1 class="text-center">Page listings</H1>
<?= GridView::widget([
    'dataProvider' => $listDataProvider,
    'layout' => "{pager}\n{items}\n{summary}\n",
    'summary' => '',
    'columns' => [
        'id',
        [
            'attribute' => 'name',
            'format' => 'raw',
            'label' => Yii::t('app', 'Name')
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'width:49px;'],
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil" data-method="POST"></span>', ['update', 'id' => $model->id]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                        'class' => '',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you absolutely sure ? You will lose all the information about listing "' . $model->name . '"and its pages'),
                            'method' => 'post',
                        ],
                    ]);
                }
            ]
        ],
    ],
]);
echo Html::a(Yii::t('app', 'Add listing'), ['create'], ['class' => 'btn btn-primary']) ?>