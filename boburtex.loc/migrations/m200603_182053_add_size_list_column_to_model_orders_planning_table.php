<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_planning}}`.
 */
class m200603_182053_add_size_list_column_to_model_orders_planning_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_planning}}', 'size_list', $this->string(200));
        $this->addColumn('{{%model_orders_planning}}', 'size_list_name', $this->string(200));
        $this->addColumn('{{%model_orders_planning}}', 'size_count', $this->integer());
        $this->addColumn('{{%toquv_rm_order}}', 'add_info', $this->text());
        $this->addColumn('{{%toquv_rm_order}}', 'size_list_name', $this->string(200));
        $this->addColumn('{{%toquv_rm_order_moi}}', 'add_info', $this->text());
        $this->addColumn('{{%toquv_rm_order_moi}}', 'size_list_name', $this->string(200));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_planning}}', 'size_list');
        $this->dropColumn('{{%model_orders_planning}}', 'size_list_name');
        $this->dropColumn('{{%model_orders_planning}}', 'size_count');
        $this->dropColumn('{{%toquv_rm_order}}', 'add_info');
        $this->dropColumn('{{%toquv_rm_order_moi}}', 'add_info');
        $this->dropColumn('{{%toquv_rm_order}}', 'size_list_name');
        $this->dropColumn('{{%toquv_rm_order_moi}}', 'size_list_name');
    }
}
