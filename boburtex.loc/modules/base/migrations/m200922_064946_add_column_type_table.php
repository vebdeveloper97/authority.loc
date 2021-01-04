<?php

use yii\db\Migration;

/**
 * Class m200922_064946_add_column_type_table
 */
class m200922_064946_add_column_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('model_orders_naqsh', 'width', $this->decimal(20,3));
        $this->alterColumn('model_orders_naqsh', 'height', $this->decimal(20,3));
        $this->alterColumn('model_orders_items_pechat', 'height', $this->decimal(20,3));
        $this->alterColumn('model_orders_items_pechat', 'width', $this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('model_orders_naqsh', 'width', $this->integer());
        $this->alterColumn('model_orders_naqsh', 'height', $this->integer());
        $this->alterColumn('model_orders_items_pechat', 'height', $this->integer());
        $this->alterColumn('model_orders_items_pechat', 'width', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200922_064946_add_column_type_table cannot be reverted.\n";

        return false;
    }
    */
}
