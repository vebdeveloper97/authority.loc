<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tikuv_documents}}`
 * - `{{%unit}}`
 */
class m191106_115457_create_tikuv_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_document_items}}', [
            'id' => $this->primaryKey(),
            'tikuv_documents_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger(6),
            'quantity' => $this->decimal(20,3),
            'price_sum' => $this->decimal(20,2),
            'price_usd' => $this->decimal(20,2),
            'current_usd' => $this->decimal(20,2),
            'is_own' => $this->smallInteger(6),
            'package_type' => $this->integer(),
            'package_qty' => $this->integer(),
            'lot' => $this->string(25),
            'unit_id' => $this->integer(),
            'document_qty' => $this->decimal(20,3),
            'tib_id' => $this->integer(),
            'price_item_id' => $this->integer(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `tikuv_documents_id`
        $this->createIndex(
            '{{%idx-tikuv_document_items-tikuv_documents_id}}',
            '{{%tikuv_document_items}}',
            'tikuv_documents_id'
        );

        // add foreign key for table `{{%tikuv_documents}}`
        $this->addForeignKey(
            '{{%fk-tikuv_document_items-tikuv_documents_id}}',
            '{{%tikuv_document_items}}',
            'tikuv_documents_id',
            '{{%tikuv_documents}}',
            'id',
            'CASCADE'
        );

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-tikuv_document_items-unit_id}}',
            '{{%tikuv_document_items}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-tikuv_document_items-unit_id}}',
            '{{%tikuv_document_items}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tikuv_documents}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_document_items-tikuv_documents_id}}',
            '{{%tikuv_document_items}}'
        );

        // drops index for column `tikuv_documents_id`
        $this->dropIndex(
            '{{%idx-tikuv_document_items-tikuv_documents_id}}',
            '{{%tikuv_document_items}}'
        );

        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_document_items-unit_id}}',
            '{{%tikuv_document_items}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-tikuv_document_items-unit_id}}',
            '{{%tikuv_document_items}}'
        );

        $this->dropTable('{{%tikuv_document_items}}');
    }
}
