<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */
/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

BootstrapAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php
if (!Yii::$app->user->isGuest) {
    NavBar::begin([
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);
    $menuItems = [
        Yii::$app->user->isGuest ?
            ['label' => 'Sign in', 'url' => ['/user/security/login']] :
            ['label' => 'Sign out (' . Yii::$app->user->identity->username . ')', 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']],
        ['label' => 'Settings', 'url' => ['/user/settings/account']],
    ];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => Yii::$app->user->isGuest ? [] :
            [
                ['label' => Yii::t('app', 'Pages'), 'url' => ['/emcms']],
                ['label' => Yii::t('app', 'Listings of pages'), 'url' => ['/emcms/listing']]
            ]
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
}
?>

<div class="wrap">
    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => [
                'label' => Yii::t('yii', 'Authentication'),
                'url' => Url::to('/emcms'),
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
