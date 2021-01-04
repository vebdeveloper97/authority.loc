<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 */
class m200724_115135_add_models_list_info_and_model_var_info_columns_to_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'models_list_info', $this->text());
        $this->addColumn('{{%model_orders_items}}', 'model_var_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items}}', 'models_list_info');
        $this->dropColumn('{{%model_orders_items}}', 'model_var_info');
    }
}
