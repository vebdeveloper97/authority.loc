<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%wms_document_items}}`.
 */
class m201006_091741_drop_wms_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            'fk-wms_document_items-model_orders_items_id',
            'wms_document_items'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey(
            'fk-wms_document_items-model_orders_items_id',
            'wms_document_items',
            'model_orders_items_id',
            'model_orders_items',
            'id',
            'RESTRICT'
        );
    }
}
