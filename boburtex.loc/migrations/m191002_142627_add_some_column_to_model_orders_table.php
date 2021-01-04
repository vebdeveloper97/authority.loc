<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 */
class m191002_142627_add_some_column_to_model_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders}}', 'planning_id', $this->bigInteger());
        $this->addColumn('{{%model_orders}}', 'planning_date', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders}}', 'planning_id');
        $this->dropColumn('{{%model_orders}}', 'planning_date');
    }
}
