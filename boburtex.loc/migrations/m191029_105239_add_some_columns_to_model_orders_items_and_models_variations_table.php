<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}} and {{%models_variations}}`.
 */
class m191029_105239_add_some_columns_to_model_orders_items_and_models_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'percentage', $this->smallInteger(3));
        $this->addColumn('{{%models_variations}}', 'add_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items}}', 'percentage');
        $this->dropColumn('{{%models_variations}}', 'add_info');
    }
}