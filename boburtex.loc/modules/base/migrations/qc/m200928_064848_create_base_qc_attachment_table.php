<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_qc_attachment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_qc_document}}`
 */
class m200928_064848_create_base_qc_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_qc_attachment}}', [
            'id' => $this->primaryKey(),
            'qc_document_id' => $this->integer(),
            'name' => $this->string(),
            'path' => $this->text(),
        ]);

        // creates index for column `qc_document_id`
        $this->createIndex(
            '{{%idx-base_qc_attachment-qc_document_id}}',
            '{{%base_qc_attachment}}',
            'qc_document_id'
        );

        // add foreign key for table `{{%base_qc_document}}`
        $this->addForeignKey(
            '{{%fk-base_qc_attachment-qc_document_id}}',
            '{{%base_qc_attachment}}',
            'qc_document_id',
            '{{%base_qc_document}}',
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
            '{{%fk-base_qc_attachment-qc_document_id}}',
            '{{%base_qc_attachment}}'
        );

        // drops index for column `qc_document_id`
        $this->dropIndex(
            '{{%idx-base_qc_attachment-qc_document_id}}',
            '{{%base_qc_attachment}}'
        );

        $this->dropTable('{{%base_qc_attachment}}');
    }
}
