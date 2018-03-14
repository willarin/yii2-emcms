<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\BootstrapAsset;
use dosamigos\tinymce\TinyMce;

BootstrapAsset::register($this);

$this->title = Yii::t('user', 'Create page');
$this->params['breadcrumbs'][] = $this->title; ?>
<div class="wrap">
    <div class="container">
        <?php $form = ActiveForm::begin(['class' => 'create-form']); ?>
        <?= $form->field($model, 'route')->input('text',
            ['placeholder' => Yii::t('app', 'Enter route')])->label(Yii::t('app', 'Route')); ?>
        <?= $form->field($model,
            'title')->textInput(['placeholder' => Yii::t('app', 'Enter title')])->label(Yii::t('app', 'Title')); ?>
        <?= $form->field($model,
            'description')->textInput(['placeholder' => Yii::t('app', 'Enter description')])->label(Yii::t('app', 'Description')); ?>
        <?= $form->field($model, 'content')->widget(TinyMce::className(), [
            'options' => ['rows' => 6],
            'language' => 'en',
            'clientOptions' => [
                'plugins' => [
                    "advlist autolink lists link charmap anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                'menubar' => ''
            ]
        ]); ?>
        <?= Html::submitButton(Yii::t('app', 'Create page'),
            ['class' => 'btn btn-primary', 'name' => 'create-button']
        );
        ActiveForm::end();
        ?>
    </div>
</div>
