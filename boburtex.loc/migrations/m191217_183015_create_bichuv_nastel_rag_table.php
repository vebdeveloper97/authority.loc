<?php

use yii\db\Migration;

/**
 * Class m191217_183015_create_bichuv_nastel_rag_table
 */
class m191217_183015_create_bichuv_nastel_rag_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_nastel_rag}}', [
            'id' => $this->primaryKey(),
            'nastel_party' => $this->string(50),
            'bichuv_doc_id' => $this->integer(),
            'quantity' => $this->decimal(20,3),
            'type' => $this->smallInteger(2)->defaultValue(1),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `bichuv_doc_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_rag-bichuv_doc_id}}',
            '{{%bichuv_nastel_rag}}',
            'bichuv_doc_id'
        );

        // add foreign key for table `{{%bichuv_doc_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_rag-bichuv_doc_id}}',
            '{{%bichuv_nastel_rag}}',
            'bichuv_doc_id',
            '{{%bichuv_doc}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_doc_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_rag-bichuv_doc_id}}',
            '{{%bichuv_nastel_rag}}'
        );

        // drops index for column `bichuv_doc_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_rag-bichuv_doc_id}}',
            '{{%bichuv_nastel_rag}}'
        );

        $this->dropTable('{{%bichuv_nastel_rag}}');
    }


}
