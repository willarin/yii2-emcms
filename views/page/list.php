<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\BootstrapAsset;
use almeyda\emcms\models\Page;

BootstrapAsset::register($this);

$this->title = Yii::t('app', 'List of pages');
$this->params['breadcrumbs'][] = $this->title; ?>
<div class="wrap">
    <div class="container">
        <?= GridView::widget([
            'dataProvider' => $listDataProvider,
            'layout' => "{pager}\n{items}\n{summary}\n",
            'summary' => '',
            'columns' => [
                'id',
                [
                    'attribute' => 'route',
                    'value' => function (Page $data) {
                        return Html::a(Html::encode($data->route), Url::to(['../' . $data->route]), ['target' => '_blank']);
                    },
                    'format' => 'raw',
                    'label' => Yii::t('app', 'Route (click to open in new page)')
                ],
                'title:ntext',
                'description:ntext',
                [
                    'attribute' => 'content',
                    'format' => 'ntext',
                ],
            ],
        ]);
        echo Html::a(Yii::t('app', 'Add page'), ['create'], ['class' => 'btn btn-primary']) ?>
    </div>
</div>
