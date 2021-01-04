<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_model_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_model_document}}`
 */
class m200929_112642_create_base_model_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_model_document_items}}', [
            'id' => $this->primaryKey(),
            'doc_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `doc_id`
        $this->createIndex(
            '{{%idx-base_model_document_items-doc_id}}',
            '{{%base_model_document_items}}',
            'doc_id'
        );

        // add foreign key for table `{{%base_model_document}}`
        $this->addForeignKey(
            '{{%fk-base_model_document_items-doc_id}}',
            '{{%base_model_document_items}}',
            'doc_id',
            '{{%base_model_document}}',
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
            '{{%fk-base_model_document_items-doc_id}}',
            '{{%base_model_document_items}}'
        );

        // drops index for column `doc_id`
        $this->dropIndex(
            '{{%idx-base_model_document_items-doc_id}}',
            '{{%base_model_document_items}}'
        );

        $this->dropTable('{{%base_model_document_items}}');
    }
}
