<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_doc_items_rel_order}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_document_items}}`
 * - `{{%toquv_orders}}`
 * - `{{%toquv_rm_order}}`
 */
class m191105_110536_create_toquv_doc_items_rel_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_doc_items_rel_order}}', [
            'id' => $this->primaryKey(),
            'toquv_document_items_id' => $this->integer(),
            'toquv_orders_id' => $this->integer(),
            'toquv_rm_order_id' => $this->integer(),
        ]);

        // creates index for column `toquv_document_items_id`
        $this->createIndex(
            '{{%idx-toquv_doc_items_rel_order-toquv_document_items_id}}',
            '{{%toquv_doc_items_rel_order}}',
            'toquv_document_items_id'
        );

        // add foreign key for table `{{%toquv_document_items}}`
        $this->addForeignKey(
            '{{%fk-toquv_doc_items_rel_order-toquv_document_items_id}}',
            '{{%toquv_doc_items_rel_order}}',
            'toquv_document_items_id',
            '{{%toquv_document_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `toquv_orders_id`
        $this->createIndex(
            '{{%idx-toquv_doc_items_rel_order-toquv_orders_id}}',
            '{{%toquv_doc_items_rel_order}}',
            'toquv_orders_id'
        );

        // add foreign key for table `{{%toquv_orders}}`
        $this->addForeignKey(
            '{{%fk-toquv_doc_items_rel_order-toquv_orders_id}}',
            '{{%toquv_doc_items_rel_order}}',
            'toquv_orders_id',
            '{{%toquv_orders}}',
            'id',
            'CASCADE'
        );

        // creates index for column `toquv_rm_order_id`
        $this->createIndex(
            '{{%idx-toquv_doc_items_rel_order-toquv_rm_order_id}}',
            '{{%toquv_doc_items_rel_order}}',
            'toquv_rm_order_id'
        );

        // add foreign key for table `{{%toquv_rm_order}}`
        $this->addForeignKey(
            '{{%fk-toquv_doc_items_rel_order-toquv_rm_order_id}}',
            '{{%toquv_doc_items_rel_order}}',
            'toquv_rm_order_id',
            '{{%toquv_rm_order}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_document_items}}`
        $this->dropForeignKey(
            '{{%fk-toquv_doc_items_rel_order-toquv_document_items_id}}',
            '{{%toquv_doc_items_rel_order}}'
        );

        // drops index for column `toquv_document_items_id`
        $this->dropIndex(
            '{{%idx-toquv_doc_items_rel_order-toquv_document_items_id}}',
            '{{%toquv_doc_items_rel_order}}'
        );

        // drops foreign key for table `{{%toquv_orders}}`
        $this->dropForeignKey(
            '{{%fk-toquv_doc_items_rel_order-toquv_orders_id}}',
            '{{%toquv_doc_items_rel_order}}'
        );

        // drops index for column `toquv_orders_id`
        $this->dropIndex(
            '{{%idx-toquv_doc_items_rel_order-toquv_orders_id}}',
            '{{%toquv_doc_items_rel_order}}'
        );

        // drops foreign key for table `{{%toquv_rm_order}}`
        $this->dropForeignKey(
            '{{%fk-toquv_doc_items_rel_order-toquv_rm_order_id}}',
            '{{%toquv_doc_items_rel_order}}'
        );

        // drops index for column `toquv_rm_order_id`
        $this->dropIndex(
            '{{%idx-toquv_doc_items_rel_order-toquv_rm_order_id}}',
            '{{%toquv_doc_items_rel_order}}'
        );

        $this->dropTable('{{%toquv_doc_items_rel_order}}');
    }
}
