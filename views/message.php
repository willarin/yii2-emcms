<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

/**
 * @var yii\web\View $this
 * @var dektrium\user\Module $module
 */

$this->title = $title;
$this->params['breadcrumbs'][] = $title;
?>

<?= $this->render('/_alert', ['module' => $module]);
