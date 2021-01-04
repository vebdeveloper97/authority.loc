<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_document_items}}`.
 */
class m190909_114246_add_price_item_id_column_to_toquv_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_document_items','price_item_id', $this->integer());

        //price_item_id
        $this->createIndex(
            'idx-toquv_document_items-price_item_id',
            'toquv_document_items',
            'price_item_id'
        );

        $this->addForeignKey(
            'fk-toquv_document_items-price_item_id',
            'toquv_document_items',
            'price_item_id',
            'toquv_pricing_item',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //model_list_id
        $this->dropForeignKey(
            'fk-toquv_document_items-price_item_id',
            'toquv_document_items'
        );
        $this->dropIndex(
            'idx-toquv_document_items-price_item_id',
            'toquv_document_items'
        );

        $this->dropColumn('toquv_document_items','price_item_id');
    }
}
