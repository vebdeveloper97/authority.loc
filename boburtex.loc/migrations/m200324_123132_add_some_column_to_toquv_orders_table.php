<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_orders}}`.
 */
class m200324_123132_add_some_column_to_toquv_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_orders}}', 'order_type', $this->smallInteger(6)->defaultValue(1));
        $this->addColumn('{{%toquv_rm_order}}', 'order_type', $this->smallInteger(6)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_orders}}', 'order_type');
        $this->dropColumn('{{%toquv_rm_order}}', 'order_type');
    }
}
