<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items_size}}`.
 */
class m200709_144155_add_created_at_and_updated_at_and_created_by_and_updated_by_and_columns_to_model_orders_items_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items_size}}', 'created_at', $this->integer());
        $this->addColumn('{{%model_orders_items_size}}', 'updated_at', $this->integer());
        $this->addColumn('{{%model_orders_items_size}}', 'created_by', $this->integer());
        $this->addColumn('{{%model_orders_items_size}}', 'updated_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items_size}}', 'created_at');
        $this->dropColumn('{{%model_orders_items_size}}', 'updated_at');
        $this->dropColumn('{{%model_orders_items_size}}', 'created_by');
        $this->dropColumn('{{%model_orders_items_size}}', 'updated_by');
    }
}
