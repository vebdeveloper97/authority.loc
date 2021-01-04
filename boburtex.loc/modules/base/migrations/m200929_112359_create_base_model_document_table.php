<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_model_document}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 */
class m200929_112359_create_base_model_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_model_document}}', [
            'id' => $this->primaryKey(),
            'doc_number' => $this->char(255),
            'date' => $this->date(),
            'model_id' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-base_model_document-model_id}}',
            '{{%base_model_document}}',
            'model_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-base_model_document-model_id}}',
            '{{%base_model_document}}',
            'model_id',
            '{{%models_list}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-base_model_document-model_id}}',
            '{{%base_model_document}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-base_model_document-model_id}}',
            '{{%base_model_document}}'
        );

        $this->dropTable('{{%base_model_document}}');
    }
}
