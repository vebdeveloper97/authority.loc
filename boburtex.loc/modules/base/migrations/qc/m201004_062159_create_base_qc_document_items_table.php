<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_qc_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_qc_document}}`
 * - `{{%base_error_list}}`
 */
class m201004_062159_create_base_qc_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_qc_document_items}}', [
            'id' => $this->primaryKey(),
            'qc_document_id' => $this->integer(),
            'error_list_id' => $this->integer(),
            'quantity' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `qc_document_id`
        $this->createIndex(
            '{{%idx-base_qc_document_items-qc_document_id}}',
            '{{%base_qc_document_items}}',
            'qc_document_id'
        );

        // add foreign key for table `{{%base_qc_document}}`
        $this->addForeignKey(
            '{{%fk-base_qc_document_items-qc_document_id}}',
            '{{%base_qc_document_items}}',
            'qc_document_id',
            '{{%base_qc_document}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `error_list_id`
        $this->createIndex(
            '{{%idx-base_qc_document_items-error_list_id}}',
            '{{%base_qc_document_items}}',
            'error_list_id'
        );

        // add foreign key for table `{{%base_error_list}}`
        $this->addForeignKey(
            '{{%fk-base_qc_document_items-error_list_id}}',
            '{{%base_qc_document_items}}',
            'error_list_id',
            '{{%base_error_list}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_qc_document}}`
        $this->dropForeignKey(
            '{{%fk-base_qc_document_items-qc_document_id}}',
            '{{%base_qc_document_items}}'
        );

        // drops index for column `qc_document_id`
        $this->dropIndex(
            '{{%idx-base_qc_document_items-qc_document_id}}',
            '{{%base_qc_document_items}}'
        );

        // drops foreign key for table `{{%base_error_list}}`
        $this->dropForeignKey(
            '{{%fk-base_qc_document_items-error_list_id}}',
            '{{%base_qc_document_items}}'
        );

        // drops index for column `error_list_id`
        $this->dropIndex(
            '{{%idx-base_qc_document_items-error_list_id}}',
            '{{%base_qc_document_items}}'
        );

        $this->dropTable('{{%base_qc_document_items}}');
    }
}
