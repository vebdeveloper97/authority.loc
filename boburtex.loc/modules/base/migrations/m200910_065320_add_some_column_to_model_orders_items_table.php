<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 */
class m200910_065320_add_some_column_to_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'assorti_count', $this->integer());
        $this->addColumn('{{%model_orders_items_size}}', 'assorti_count', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items}}', 'assorti_count');
        $this->dropColumn('{{%model_orders_items_size}}', 'assorti_count');
    }
}
