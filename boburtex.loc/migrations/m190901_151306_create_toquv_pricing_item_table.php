<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_pricing_item}}`.
 */
class m190901_151306_create_toquv_pricing_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_pricing_item}}', [
            'id' => $this->primaryKey(),
            'doc_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'price' => $this->decimal(20, 2),
            'pb_id' => $this->integer(),
            'status' => $this->integer()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
        //doc_id
        $this->createIndex(
            'idx-toquv_pricing_item-doc_id',
            'toquv_pricing_item',
            'doc_id'
        );

        $this->addForeignKey(
            'fk-toquv_pricing_item-doc_id',
            'toquv_pricing_item',
            'doc_id',
            'toquv_pricing_doc',
            'id'
        );
        $this->createIndex(
            'idx-toquv_pricing_item-entity_id',
            'toquv_pricing_item',
            'entity_id'
        );
        //pb_id
        $this->createIndex(
            'idx-toquv_pricing_item-pb_id',
            'toquv_pricing_item',
            'pb_id'
        );

        $this->addForeignKey(
            'fk-toquv_pricing_item-pb_id',
            'toquv_pricing_item',
            'pb_id',
            'pul_birligi',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //doc_id
        $this->dropForeignKey(
            'fk-toquv_pricing_item-doc_id',
            'toquv_pricing_item'
        );

        $this->dropIndex(
            'idx-toquv_pricing_item-doc_id',
            'toquv_pricing_item'
        );
        // entity_id
        $this->dropIndex(
            'idx-toquv_pricing_item-entity_id',
            'toquv_pricing_item'
        );
        //pb_id
        $this->dropForeignKey(
            'idx-toquv_pricing_item-pb_id',
            'toquv_pricing_item'
        );

        $this->dropIndex(
            'idx-toquv_pricing_item-pb_id',
            'toquv_pricing_item'
        );
        $this->dropTable('{{%toquv_pricing_item}}');
    }
}
