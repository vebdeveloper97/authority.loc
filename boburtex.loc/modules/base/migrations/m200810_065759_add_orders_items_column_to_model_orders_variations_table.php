<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_variations}}`.
 */
class m200810_065759_add_orders_items_column_to_model_orders_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_variations}}', 'orders_items', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_variations}}', 'orders_items');
    }
}
