<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_model_table_file}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_model_document}}`
 * - `{{%base_model_document_items}}`
 * - `{{%attachments}}`
 */
class m200929_114145_create_base_model_table_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_model_table_file}}', [
            'id' => $this->primaryKey(),
            'doc_id' => $this->integer(),
            'doc_items_id' => $this->integer(),
            'attachment_id' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `doc_id`
        $this->createIndex(
            '{{%idx-base_model_table_file-doc_id}}',
            '{{%base_model_table_file}}',
            'doc_id'
        );

        // add foreign key for table `{{%base_model_document}}`
        $this->addForeignKey(
            '{{%fk-base_model_table_file-doc_id}}',
            '{{%base_model_table_file}}',
            'doc_id',
            '{{%base_model_document}}',
            'id',
            'CASCADE'
        );

        // creates index for column `doc_items_id`
        $this->createIndex(
            '{{%idx-base_model_table_file-doc_items_id}}',
            '{{%base_model_table_file}}',
            'doc_items_id'
        );

        // add foreign key for table `{{%base_model_document_items}}`
        $this->addForeignKey(
            '{{%fk-base_model_table_file-doc_items_id}}',
            '{{%base_model_table_file}}',
            'doc_items_id',
            '{{%base_model_document_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `attachment_id`
        $this->createIndex(
            '{{%idx-base_model_table_file-attachment_id}}',
            '{{%base_model_table_file}}',
            'attachment_id'
        );

        // add foreign key for table `{{%attachments}}`
        $this->addForeignKey(
            '{{%fk-base_model_table_file-attachment_id}}',
            '{{%base_model_table_file}}',
            'attachment_id',
            '{{%attachments}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_model_document}}`
        $this->dropForeignKey(
            '{{%fk-base_model_table_file-doc_id}}',
            '{{%base_model_table_file}}'
        );

        // drops index for column `doc_id`
        $this->dropIndex(
            '{{%idx-base_model_table_file-doc_id}}',
            '{{%base_model_table_file}}'
        );

        // drops foreign key for table `{{%base_model_document_items}}`
        $this->dropForeignKey(
            '{{%fk-base_model_table_file-doc_items_id}}',
            '{{%base_model_table_file}}'
        );

        // drops index for column `doc_items_id`
        $this->dropIndex(
            '{{%idx-base_model_table_file-doc_items_id}}',
            '{{%base_model_table_file}}'
        );

        // drops foreign key for table `{{%attachments}}`
        $this->dropForeignKey(
            '{{%fk-base_model_table_file-attachment_id}}',
            '{{%base_model_table_file}}'
        );

        // drops index for column `attachment_id`
        $this->dropIndex(
            '{{%idx-base_model_table_file-attachment_id}}',
            '{{%base_model_table_file}}'
        );

        $this->dropTable('{{%base_model_table_file}}');
    }
}
