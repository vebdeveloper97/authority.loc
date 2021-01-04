<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders}}`.
 */
class m200927_220327_add_confirm_supply_column_to_model_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders}}', 'confirm_supply', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders}}', 'confirm_supply');
    }
}
