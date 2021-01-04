<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_item_balance}}`.
 */
class m190817_055722_add_lot_column_to_toquv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_item_balance}}', 'lot', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_item_balance}}', 'lot');
    }
}
