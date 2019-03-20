<?php

use yii\db\Migration;

/**
 * Class m190319_064313_create_ismenu_ispage_fields
 */
class m190319_064313_create_ismenu_ispage_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('page', 'parentId', $this->integer()->defaultValue(0)->notNull());
        $this->addColumn('page', 'isMenu', $this->boolean()->defaultValue(false)->notNull());
        $this->addColumn('page', 'pageType', $this->string(10)->defaultValue('page')->notNull());
        $this->addColumn('page', 'hidden', $this->boolean()->defaultValue(false)->notNull());
        $this->addColumn('page', 'headerHtml', $this->text()->null());
        $this->addColumn('page', 'footerHtml', $this->text()->null());
        $this->addColumn('page', 'sort', $this->int()->null());

        $this->createIndex('idx_page_parentId', 'page', ['parentId']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190319_064313_create_ismenu_ispage_fields cannot be reverted.\n";

        $this->dropColumn('page', 'isMenu');
        $this->dropColumn('page', 'isPage');
        $this->dropColumn('page', 'hidden');
        $this->dropColumn('page', 'headerHtml');
        $this->dropColumn('page', 'footerHtml');
        $this->dropIndex('idx_page_parentId', 'page');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190319_064313_create_ismenu_ispage_fields cannot be reverted.\n";

        return false;
    }
    */
}
