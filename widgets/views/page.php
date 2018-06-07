<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var almeyda\emcms\models\Page $model
 * @var string $action
 */

?>

<?php ?>
    <div class="row">
        <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
            <div>
                <div class="html_content">
                    <h3>
                        <span>
                            <?= Html::a($model->title, ['@web/' . $model->route], ['class' => 'post-heading']) ?>
                        </span>
                    </h3>
                    <p style="margin:0.14em 0em 0em 0.57em;"><?= $model->description ?></p>
                    <p>
                        <span>
                            <?= Html::a(Yii::t('app', 'Read More'), ['@web/' . $model->route], [
                                'class' => 'btn btn-md btn-default',
                                'target' => '_self'
                            ]) ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="clearfix visible-lg-block visible-xs-block">
        </div>
        <div style="width:100%;text-align:center; margin:0 auto;">
            <hr style="width:80%;border:1px solid #ccc;"/>
        </div>
    </div>
<?php ?>