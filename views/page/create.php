<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\BootstrapAsset;
use dominus77\tinymce\TinyMce;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

BootstrapAsset::register($this);

$this->title = Yii::t('user', $model->scenario == 'create' ? 'Create page' : 'Update page'); ?>
<H1 class="text-center"><?= $model->scenario == 'create' ? 'Page creation' : 'Page updating' ?></H1>
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
            "advlist autolink lists link charmap anchor image imagetools",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image code | csstemplates",
        'image_advtab' => true,
        'menubar' => false,
        'setup' => new \yii\web\JsExpression("function(editor) {
        editor.addButton('csstemplates', {
          type: 'menubutton',
          text: 'Select template',
          menu: [" . $templatesItems . "]
        });
      }")
    ],
    'fileManager' => [
        'class' => \dominus77\tinymce\components\MihaildevElFinder::className(),
        'controller' => 'emcms/elfinder',
        'title' => 'File manager',
        'width' => 900,
        'height' => 600,
        'resizable' => 'yes'
    ],
]); ?>
<?= Html::submitButton(Yii::t('app', $model->scenario == 'create' ? 'Create page' : 'Update page'),
    ['class' => 'btn btn-primary', 'name' => 'create-button']
);
ActiveForm::end();
?>
