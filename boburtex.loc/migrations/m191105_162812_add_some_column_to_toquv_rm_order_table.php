<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_rm_order}}`.
 */
class m191105_162812_add_some_column_to_toquv_rm_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_rm_order}}', 'planed_date', $this->dateTime());
        $this->addColumn('{{%toquv_rm_order}}', 'finished_date', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_rm_order}}', 'planed_date');
        $this->dropColumn('{{%toquv_rm_order}}', 'finished_date');
    }
}
