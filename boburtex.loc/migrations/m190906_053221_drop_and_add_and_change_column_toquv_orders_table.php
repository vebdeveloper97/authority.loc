<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%and_add_and_change_column_toquv_orders}}`.
 */
class m190906_053221_drop_and_add_and_change_column_toquv_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('toquv_orders', 'order_number');
        $this->addColumn('toquv_orders','priority',$this->smallInteger(2)->defaultValue(1));
        $this->addColumn('toquv_orders','entity_type',$this->smallInteger()->defaultValue(1));
        $this->addColumn('toquv_orders','done_date', 'datetime DEFAULT NOW()');
        $this->alterColumn('toquv_orders','document_number', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('toquv_orders','order_number',$this->integer());$this->dropColumn('toquv_orders', 'priority');
        $this->dropColumn('toquv_orders', 'entity_type');
        $this->dropColumn('toquv_orders', 'done_date');
        $this->alterColumn('toquv_orders','document_number', $this->integer());
    }
}
