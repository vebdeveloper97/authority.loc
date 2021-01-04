<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_doc_expense}}`.
 */
class m190821_103006_create_bichuv_doc_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_doc_expense}}', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer(),
            'price' => $this->decimal(20, 2)->defaultValue(0),
            'pb_id' => $this->integer()->defaultValue(1), //    default sum
            'add_info' =>$this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-bichuv_doc_expense-document_id',
            'bichuv_doc_expense',
            'document_id'
        );

        $this->addForeignKey(
            'fk-bichuv_doc_expense-document_id',
            'bichuv_doc_expense',
            'document_id',
            'bichuv_doc',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-bichuv_doc_expense-pb_id',
            'bichuv_doc_expense',
            'pb_id'
        );

        $this->addForeignKey(
            'fk-bichuv_doc_expense-pb_id',
            'bichuv_doc_expense',
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
        $this->dropForeignKey(
            'fk-bichuv_doc_expense-pb_id',
            'bichuv_doc_expense'
        );

        $this->dropIndex(
            'idx-bichuv_doc_expense-pb_id',
            'bichuv_doc_expense'
        );

        $this->dropForeignKey(
            'fk-bichuv_doc_expense-document_id',
            'bichuv_doc_expense'
        );

        $this->dropIndex(
            'idx-bichuv_doc_expense-document_id',
            'bichuv_doc_expense'
        );

        $this->dropTable('{{%bichuv_doc_expense}}');
    }
}
