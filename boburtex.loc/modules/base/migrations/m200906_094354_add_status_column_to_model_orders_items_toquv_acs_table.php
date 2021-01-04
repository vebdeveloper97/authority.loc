<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items_toquv_acs}}`.
 */
class m200906_094354_add_status_column_to_model_orders_items_toquv_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items_toquv_acs}}', 'status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items_toquv_acs}}', 'status');
    }
}
