<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items_material}}`.
 */
class m200827_111929_add_models_list_id_and_model_var_id_columns_to_model_orders_items_material_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items_material}}', 'models_list_id', $this->integer());
        $this->addColumn('{{%model_orders_items_material}}', 'model_var_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items_material}}', 'models_list_id');
        $this->dropColumn('{{%model_orders_items_material}}', 'model_var_id');
    }
}
