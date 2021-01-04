<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_variations}}`.
 */
class m200811_042039_add_models_list_id_column_to_model_orders_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_variations}}', 'models_list_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_variations}}', 'models_list_id');
    }
}
