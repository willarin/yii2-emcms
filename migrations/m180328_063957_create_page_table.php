<?php

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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('page');
    }
}
