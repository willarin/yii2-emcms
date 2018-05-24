<?php
/**
 * @link https://almeyda.repositoryhosting.com/git_public/almeyda/yii2-emcms.git
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */
use yii\db\Migration;

/**
 * Handles the creation of table `page`.
 */
class m180328_063957_create_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('page', [
            'id' => $this->primaryKey()->unsigned(),
            'route' => $this->string()->notNull(),
            'title' => $this->string(300)->notNull(),
            'description' => $this->string(2048)->notNull(),
            'content' => $this->text(),
            'timeCreated' => $this->dateTime(),
            'timeUpdated' => $this->dateTime(),
        ]);
        $this->createTable('listing', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'timeCreated' => $this->dateTime(),
            'timeUpdated' => $this->dateTime(),
        ]);
        $this->createTable('listingPage', [
            'id' => $this->primaryKey()->unsigned(),
            'pageId' => $this->integer()->unsigned(),
            'listingId' => $this->integer()->unsigned(),
            'sort' => $this->integer()->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('page');
        $this->dropTable('listing');
        $this->dropTable('listingPage');
    }
}
