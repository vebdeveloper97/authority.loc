<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items_acs}}`.
 */
class m200912_100928_add_application_part_column_to_model_orders_items_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items_acs}}', 'application_part', $this->char(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items_acs}}', 'application_part');
    }
}
