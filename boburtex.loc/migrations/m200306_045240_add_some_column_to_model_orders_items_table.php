<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 */
class m200306_045240_add_some_column_to_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `model_orders_items` ADD `prepayment` FLOAT(5,2) NULL DEFAULT '0' AFTER `status`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items}}', 'prepayment');
    }
}
