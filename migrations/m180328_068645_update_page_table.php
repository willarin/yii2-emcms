<?php

use yii\db\Migration;

/**
 * Handles the creation of table `page`.
 */
class m180328_068645_update_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%page}}', 'timeCreated', $this->dateTime());
        $this->addColumn('{{%page}}', 'timeUpdated', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%page}}', 'timeCreated');
        $this->dropColumn('{{%page}}', 'timeUpdated');
    }
}
