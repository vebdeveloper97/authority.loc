<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders}}`.
 */
class m200716_154725_add_responsible_column_to_model_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders}}', 'responsible', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders}}', 'responsible');
    }
}
