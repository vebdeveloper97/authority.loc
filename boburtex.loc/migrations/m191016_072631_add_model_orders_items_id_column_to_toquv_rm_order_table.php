<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_rm_order}}`.
 */
class m191016_072631_add_model_orders_items_id_column_to_toquv_rm_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_rm_order}}', 'moi_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_rm_order}}', 'moi_id');
    }
}
