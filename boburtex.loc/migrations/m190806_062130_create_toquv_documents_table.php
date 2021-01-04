<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_documents}}`.
 */
class m190806_062130_create_toquv_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%toquv_documents}}', [
            'id' => $this->primaryKey(),
            'document_type' => $this->smallInteger()->defaultValue(1),
            'action' => $this->smallInteger()->defaultValue(1),
            'doc_number' => $this->string(25),
            'reg_date' => $this->dateTime()->defaultValue(date('Y-m-d H:i:s')),
            'musteri_id' => $this->bigInteger(),
            'musteri_responsible' => $this->string(),
            'from_department' => $this->integer(),
            'from_employee' => $this->bigInteger(),
            'to_department' => $this->integer(),
            'to_employee' => $this->bigInteger(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        //musteri_id
        $this->createIndex(
            'idx-toquv_documents-musteri_id',
            'toquv_documents',
            'musteri_id'
        );

        $this->addForeignKey(
            'fk-toquv_documents-musteri_id',
            'toquv_documents',
            'musteri_id',
            'musteri',
            'id'
        );

        //to_department
        $this->createIndex(
            'idx-toquv_documents-to_department',
            'toquv_documents',
            'to_department'
        );

        $this->addForeignKey(
            'fk-toquv_documents-to_department',
            'toquv_documents',
            'to_department',
            'toquv_departments',
            'id'
        );
        //from_department
        $this->createIndex(
            'idx-toquv_documents-from_department',
            'toquv_documents',
            'from_department'
        );

        $this->addForeignKey(
            'fk-toquv_documents-from_department',
            'toquv_documents',
            'from_department',
            'toquv_departments',
            'id'
        );

        //from_employee
        $this->createIndex(
            'idx-toquv_documents-from_employee',
            'toquv_documents',
            'from_employee'
        );

        $this->addForeignKey(
            'fk-toquv_documents-from_employee',
            'toquv_documents',
            'from_employee',
            'users',
            'id'
        );

        //to_employee
        $this->createIndex(
            'idx-toquv_documents-to_employee',
            'toquv_documents',
            'to_employee'
        );

        $this->addForeignKey(
            'fk-toquv_documents-to_employee',
            'toquv_documents',
            'to_employee',
            'users',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //from
        $this->dropForeignKey(
            'fk-toquv_documents-musteri_id',
            'toquv_documents'
        );

        $this->dropIndex(
            'idx-toquv_documents-musteri_id',
            'toquv_documents'
        );

        //to
        $this->dropForeignKey(
            'fk-toquv_documents-to',
            'toquv_documents'
        );

        $this->dropIndex(
            'idx-toquv_documents-to',
            'toquv_documents'
        );
        $this->dropTable('{{%toquv_documents}}');
    }
}
