<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 */
class m200709_141850_add_created_at_and_updated_at_and_created_by_and_updated_by_and_columns_to_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'created_at', $this->integer());
        $this->addColumn('{{%model_orders_items}}', 'updated_at', $this->integer());
        $this->addColumn('{{%model_orders_items}}', 'created_by', $this->integer());
        $this->addColumn('{{%model_orders_items}}', 'updated_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items}}', 'created_at');
        $this->dropColumn('{{%model_orders_items}}', 'updated_at');
        $this->dropColumn('{{%model_orders_items}}', 'created_by');
        $this->dropColumn('{{%model_orders_items}}', 'updated_by');
    }
}
