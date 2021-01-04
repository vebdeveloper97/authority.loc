<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders}}`.
 */
class m200915_175016_add_orders_status_column_to_model_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders}}', 'orders_status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders}}', 'orders_status');
    }
}
