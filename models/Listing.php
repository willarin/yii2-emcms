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

/**
 * Class Listing
 * @package almeyda\emcms\models
 */
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
     * Unlink Pages and delete associated ListingPage records
     */
    public function beforeDelete()
    {
        $this->unlinkAll('pages', true);
        return parent::beforeDelete();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::class, ['id' => 'pageId'])->viaTable('listing_page', ['listingId' => 'id'])
            ->leftJoin('listing_page lp', 'lp.pageId = page.id')->orderBy(['lp.sort' => SORT_ASC]);
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
     * @return array - zero-based array of Page id's from the listing
     */
    public function getPageIds()
    {
        $pages = $this->getPages()->all();
        $pageIds = [];
        foreach ($pages as $page) {
            $pageIds[] = $page->id;
        }
        return $pageIds;
    }
    
    /**
     * Method that returns indexed array of listings to display in select
     * @return array
     */
    public static function selectListings()
    {
        $result = [];
        $listings = Listing::find()->all();
        if (is_array($listings)) {
            foreach ($listings as $listing) {
                $result[$listing->id] = $listing->name;
            }
        }
        return $result;
    }
}
