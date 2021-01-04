<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mopdel_orders_items}}`.
 */
class m191127_105527_add_status_column_to_mopdel_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'status', $this->smallInteger(6)->defaultValue(1));
        $this->addColumn('{{%model_orders_planning}}', 'status', $this->smallInteger(6)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items}}', 'status');
        $this->dropColumn('{{%model_orders_planning}}', 'status');
    }
}
