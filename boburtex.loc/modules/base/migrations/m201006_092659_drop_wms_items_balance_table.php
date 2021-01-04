<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%wms_items_balance}}`.
 */
class m201006_092659_drop_wms_items_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            'fk-wms_item_balance-model_orders_items_id',
            'wms_item_balance'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey(
            'fk-wms_item_balance-model_orders_items_id',
            'wms_item_balance',
            'model_orders_items_id',
            'model_orders_items',
            'id',
            'RESTRICT'
        );
    }
}
