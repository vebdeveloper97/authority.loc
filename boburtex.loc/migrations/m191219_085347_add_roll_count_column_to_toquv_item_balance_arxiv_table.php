<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_item_balance_arxiv}}`.
 */
class m191219_085347_add_roll_count_column_to_toquv_item_balance_arxiv_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_item_balance_arxiv}}','roll_inventory', $this->decimal(20,3)->defaultValue(0));
        $this->addColumn('{{%toquv_item_balance_arxiv}}','roll_count', $this->decimal(20,3)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%toquv_item_balance_arxiv}}','roll_inventory');
        $this->addColumn('{{%toquv_item_balance_arxiv}}','roll_count');
    }
}
