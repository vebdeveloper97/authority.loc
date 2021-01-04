<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_document_expense}}`.
 */
class m190808_103531_create_toquv_document_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pul_birligi}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('{{%toquv_document_expense}}', [
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
            'idx-toquv_document_expense-document_id',
            'toquv_document_expense',
            'document_id'
        );

        $this->addForeignKey(
            'fk-toquv_document_expense-document_id',
            'toquv_document_expense',
            'document_id',
            'toquv_documents',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-toquv_document_expense-pb_id',
            'toquv_document_expense',
            'pb_id'
        );

        $this->addForeignKey(
            'fk-toquv_document_expense-pb_id',
            'toquv_document_expense',
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
            'fk-toquv_document_expense-pb_id',
            'toquv_document_expense'
        );

        $this->dropIndex(
            'idx-toquv_document_expense-pb_id',
            'toquv_document_expense'
        );

        $this->dropForeignKey(
            'fk-toquv_document_expense-document_id',
            'toquv_document_expense'
        );

        $this->dropIndex(
            'idx-toquv_document_expense-document_id',
            'toquv_document_expense'
        );

        $this->dropTable('{{%toquv_document_expense}}');
        $this->dropTable('{{%pul_birligi}}');
    }
}
