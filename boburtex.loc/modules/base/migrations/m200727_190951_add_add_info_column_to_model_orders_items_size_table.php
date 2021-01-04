<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items_size}}`.
 */
class m200727_190951_add_add_info_column_to_model_orders_items_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items_size}}', 'add_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items_size}}', 'add_info');
    }
}
