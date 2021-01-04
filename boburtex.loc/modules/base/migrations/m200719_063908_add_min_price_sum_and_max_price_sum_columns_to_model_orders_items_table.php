<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 */
class m200719_063908_add_min_price_sum_and_max_price_sum_columns_to_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'min_price_sum', $this->decimal(20,3));
        $this->addColumn('{{%model_orders_items}}', 'max_price_sum', $this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items}}', 'min_price_sum');
        $this->dropColumn('{{%model_orders_items}}', 'max_price_sum');
    }
}
