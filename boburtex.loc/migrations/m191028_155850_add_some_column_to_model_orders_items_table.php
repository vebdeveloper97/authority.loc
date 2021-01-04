<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 */
class m191028_155850_add_some_column_to_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'baski_id', $this->integer());
        $this->addColumn('{{%model_orders_items}}', 'prints_id', $this->integer());
        $this->addColumn('{{%model_orders_items}}', 'stone_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items}}', 'baski_id');
        $this->dropColumn('{{%model_orders_items}}', 'prints_id');
        $this->dropColumn('{{%model_orders_items}}', 'stone_id');
    }
}
