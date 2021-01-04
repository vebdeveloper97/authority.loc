<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}} and {{%pul_birligi}}`.
 */
class m191031_112908_add_some_columns_to_model_orders_items_and_pul_birligi_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'price', $this->decimal(20,3));
        $this->addColumn('{{%model_orders_items}}', 'pb_id', $this->integer()->defaultValue(2));
        $this->addColumn('{{%pul_birligi}}', 'code', $this->string(10));
        $this->upsert('{{%pul_birligi}}', ['id'=>1,'code'=>'UZS'],true);
        $this->upsert('{{%pul_birligi}}', ['id'=>2,'code'=>'USD'],true);
        $this->upsert('{{%pul_birligi}}', ['id'=>3,'code'=>'RUB'],true);
        $this->upsert('{{%pul_birligi}}', ['id'=>4,'code'=>'EUR'],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items}}', 'price');
        $this->dropColumn('{{%model_orders_items}}', 'pb_id');
        $this->dropColumn('{{%pul_birligi}}', 'code');
    }
}
