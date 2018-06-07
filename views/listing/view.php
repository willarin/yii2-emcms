<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

use yii\helpers\Html;
use almeyda\emcms\widgets\PageWidget;

$this->title = $model->name; ?>
<?php
$provider = new \yii\data\ArrayDataProvider([
    'allModels' => $pagesList,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => ['title', 'description'],
    ],
]);
?>
<?php if ($pagesList) : ?>
    <?php foreach ($provider->getModels() as $page) : ?>
        <?= PageWidget::widget(['model' => $page]); ?>
    <?php endforeach; ?>
<?php endif; ?>
<div class="text-center">
    <?= \yii\widgets\LinkPager::widget([
        'pagination' => $provider->getPagination(),
    ]);
    ?>
</div>