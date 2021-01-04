<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders}}`.
 */
class m200709_193228_add_token_column_to_model_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders}}', 'token', $this->string(50)->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders}}', 'token');
    }
}
