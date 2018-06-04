<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

use yii\db\Migration;

/**
 * Handles the creation indexes and additional tables
 * NOTE: temporary migration
 */
class m180328_063957_create_listing_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //create index for column 'title'
        $this->createIndex('idx-title', '{{%page}}', 'title');
        $this->createTable('listing', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'timeCreated' => $this->dateTime(),
            'timeUpdated' => $this->dateTime(),
        ]);
        //create index for column 'title'
        $this->createIndex('idx-name', '{{%listing}}', 'name');
        $this->createTable('listing_page', [
            'id' => $this->primaryKey()->unsigned(),
            'pageId' => $this->integer()->unsigned(),
            'listingId' => $this->integer()->unsigned(),
            'sort' => $this->integer()->unsigned(),
        ]);
        
        $this->addForeignKey('fk-page-id', '{{%listing_page}}', 'pageId', '{{%page}}', 'id', 'CASCADE', "CASCADE");
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-page-id', '{{%listing_page}}');
        $this->dropIndex('idx-name', '{{%listing}}');
        $this->dropIndex('idx-title', '{{%page}}');
        $this->dropTable('listing');
        $this->dropTable('listing_page');
    }
}
