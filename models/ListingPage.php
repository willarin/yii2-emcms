<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

namespace almeyda\emcms\models;

use yii\db\ActiveRecord;

class ListingPage extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%listing_page}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['pageId', 'listingId', 'sort'];
        $scenarios['update'] = ['pageId', 'listingId', 'sort', 'id'];
        return $scenarios;
    }
}