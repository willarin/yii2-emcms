<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

namespace almeyda\emcms\widgets;

use yii\base\Widget;

/**
 * Widget that display page item at listing page
 */
class PageWidget extends Widget
{
    
    public $model;
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('page', [
            'model' => $this->model,
        ]);
    }
}
