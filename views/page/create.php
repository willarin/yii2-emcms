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
use yii\web\JsExpression;
use dominus77\tinymce\components\MihaildevElFinder;
use kartik\select2\Select2;
use \yii\helpers\ArrayHelper;

BootstrapAsset::register($this);

$this->title = Yii::t('user', $model->scenario == 'create' ? 'Create page' : 'Update page'); ?>
<H1 class="text-center"><?= $model->scenario == 'create' ? 'Page creation' : 'Page updating' ?></H1>

<?php $form = ActiveForm::begin(['class' => 'create-form']); ?>

<?= $form->field($model, 'pageType')->widget(Select2::class, [
    'options' =>
        ['placeholder' => 'Select a page type ...', 'class' => 'form-group'],
    'data' => [
        'page' => Yii::t('app', 'Page'),
        'section' => Yii::t('app', 'Section')
    ],
    'pluginOptions' => [
        'allowClear' => true
    ],
    'toggleAllSettings' => [
        'options' => ['class' => 'form-group']
    ],
]); ?>

<?= (!(\Yii::$app->getRequest()->get('listingId'))) ?
    '<label class="control-label">' . Yii::t('app', 'Listing') . '</label>' . Select2::widget([
        'name' => 'listingId',
        'value' => isset($model->listing) ? $model->listing->id : '',
        'options' =>
            ['placeholder' => Yii::t('app', 'Select a listing') . ' ...', 'class' => 'form-group'],
        'data' => $selectData,
        'pluginOptions' => [
            'allowClear' => true
        ],
        'toggleAllSettings' => [
            'options' => ['class' => 'form-group']
        ],
    ]) : '';
?>

<?php
$provider = new \yii\data\ArrayDataProvider([
    'allModels' => ArrayHelper::merge([0 => Yii::t('app', 'No Parent')], ArrayHelper::map($model->getPages($model->id), 'id', 'title')),
    'sort' => [
        'attributes' => ['id', 'title'],
    ],
]);
?>
<?= $form->field($model, 'parentId')->widget(Select2::class, [
    'options' =>
        ['placeholder' => Yii::t('app', 'Select a parent') . ' ...', 'class' => 'form-group'],
    'data' => $provider->getModels(),
    'pluginOptions' => [
        'allowClear' => true
    ],
    'toggleAllSettings' => [
        'options' => ['class' => 'form-group']
    ],
])->label(Yii::t('app', 'Parent')); ?>

<?= $form->field($model, 'isMenu')->checkbox(['label' => Yii::t('app', 'Show in the menu')])->label(false); ?>

<?= $form->field($model, 'hidden')->checkbox(['label' => Yii::t('app', 'This is a hidden page')])->label(false); ?>

<?= $form->field($model, 'route')->input('text',
    ['placeholder' => Yii::t('app', 'Enter route')])->label(Yii::t('app', 'Route')); ?>
<?= $form->field($model,
    'title')->textInput(['placeholder' => Yii::t('app', 'Enter title')])->label(Yii::t('app', 'Title')); ?>
<?= $form->field($model,
    'description')->textInput(['placeholder' => Yii::t('app', 'Enter description')])->label(Yii::t('app', 'Description')); ?>

<?= $form->field($model, 'headerHtml')->textarea(['rows' => 5]) ?>

<?= $form->field($model, 'content')->widget(TinyMce::class, [
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
        'setup' => new JsExpression("function(editor) {
        editor.addButton('csstemplates', {
          type: 'menubutton',
          text: '" . Yii::t('app', 'Select template') . "',
          menu: [" . $templatesItems . "]
        });
      }")
    ],
    'fileManager' => [
        'class' => MihaildevElFinder::class,
        'controller' => 'emcms/elfinder',
        'title' => 'File manager',
        'width' => 900,
        'height' => 600,
        'resizable' => 'yes'
    ],
]); ?>

<?= $form->field($model, 'footerHtml')->textarea(['rows' => 5]) ?>

<?= Html::submitButton(Yii::t('app', $model->scenario == 'create' ? 'Create page' : 'Update page'),
    ['class' => 'btn btn-primary', 'name' => 'create-button', 'style' => 'margin-top:15px;']
);
ActiveForm::end();
?>
