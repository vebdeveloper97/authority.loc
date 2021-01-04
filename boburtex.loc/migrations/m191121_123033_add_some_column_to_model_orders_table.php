<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders}}`.
 */
class m191121_123033_add_some_column_to_model_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders}}', 'sum_item_qty', $this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders}}', 'sum_item_qty');
    }
}
