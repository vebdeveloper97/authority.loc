<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items_acs}}`.
 */
class m200709_145922_add_updated_by_and_columns_to_model_orders_items_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items_acs}}', 'updated_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items_acs}}', 'updated_by');
    }
}
