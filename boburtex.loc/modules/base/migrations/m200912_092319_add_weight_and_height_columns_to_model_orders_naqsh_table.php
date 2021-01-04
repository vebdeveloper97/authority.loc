<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_naqsh}}`.
 */
class m200912_092319_add_weight_and_height_columns_to_model_orders_naqsh_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_naqsh}}', 'width', $this->integer());
        $this->addColumn('{{%model_orders_naqsh}}', 'height', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_naqsh}}', 'width');
        $this->dropColumn('{{%model_orders_naqsh}}', 'height');
    }
}
