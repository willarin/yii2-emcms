<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */
namespace almeyda\emcms\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;

class Listing extends ActiveRecord
{
    
    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            'nameUnique' => [
                'name',
                'unique',
                'message' => \Yii::t('app', 'Name is already taken'),
                'on' => ['create', 'update']
            ],
            'fieldsRequired' => [['name'], 'required', 'on' => ['create', 'update']],
            'nameValid' => [['name'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/'],
            'idRequired' => [['id'], 'required', 'except' => 'create'],
            'nameMax' => ['route', 'string', 'min' => 1, 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'timeCreated',
                'updatedAtAttribute' => 'timeUpdated',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Deletes Pages and ListingPage records associated with Listing when deleted
     */
    public function afterDelete()
    {
        Page::deleteAll(['id' => $this->getPageIds($this->id)]);
        ListingPage::deleteAll(['pageId' => $this->getPageIds($this->id)]);
        parent::afterDelete();
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['name'];
        $scenarios['update'] = ['name', 'id'];
        return $scenarios;
    }

    /**
     * @param $listingId integer - id of listing
     * @return array - zero-based array of Page id's from the listing
     */
    public function getPageIds($listingId)
    {
        $pages = ListingPage::find()->select('pageId')->where(['listingId' => $listingId])->all();
        $pageIds = [];
        foreach ($pages as $page) {
            $pageIds[] = $page->pageId;
        }
        return $pageIds;
    }
}
