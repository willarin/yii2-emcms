<?php

use yii\db\Migration;

/**
 * Class m191007_071245_create_image_and_category_fields
 */
class m191007_071245_create_image_and_category_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('page_category', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'timeCreated' => $this->dateTime(),
            'timeUpdated' => $this->dateTime(),
        ]);
        
        $this->addColumn('page', 'categoryId', $this->integer()->null());
        $this->addColumn('page', 'image', $this->string(255)->null());

        $this->createIndex('idx_page_categoryId', 'page', ['categoryId']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191007_071245_create_image_and_category_fields cannot be reverted.\n";

        $this->dropTable('page_category');
        $this->dropColumn('page', 'categoryId');
        $this->dropColumn('page', 'image');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191007_071245_create_image_and_category_fields cannot be reverted.\n";

        return false;
    }
    */
}
