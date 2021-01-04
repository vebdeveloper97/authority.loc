<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_item_balance}}`.
 */
class m191219_062022_add_roll_inventory_and_roll_count_column_to_toquv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_item_balance','roll_inventory', $this->decimal(20,3)->defaultValue(0));
        $this->addColumn('toquv_item_balance','roll_count', $this->decimal(20,3)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('toquv_item_balance','roll_inventory');
        $this->addColumn('toquv_item_balance','roll_count');
    }
}
