<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_kalite}}`.
 */
class m191223_083159_add_count_column_to_toquv_kalite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_kalite}}', 'count', $this->double()->defaultValue(0));
        $this->addColumn('{{%toquv_kalite}}', 'roll', $this->double()->defaultValue(0));
        $this->addColumn('{{%toquv_item_balance}}', 'quantity_inventory', $this->double()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_kalite}}', 'count');
        $this->dropColumn('{{%toquv_kalite}}', 'roll');
        $this->dropColumn('{{%toquv_item_balance}}', 'quantity_inventory');
    }
}
