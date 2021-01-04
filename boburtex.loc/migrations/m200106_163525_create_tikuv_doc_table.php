<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_doc}}`.
 */
class m200106_163525_create_tikuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_doc}}', [
            'id' => $this->primaryKey(),
            'document_type' => $this->smallInteger()->defaultValue(1),
            'doc_number' => $this->string(25),
            'party_no' => $this->string(25),
            'party_count'=> $this->integer()->defaultValue(1),
            'reg_date' => $this->dateTime(),
            'musteri_id' => $this->bigInteger(20),
            'musteri_responsible' => $this->string(),
            'from_department' => $this->integer(),
            'from_employee' => $this->bigInteger(),
            'to_department' => $this->integer(),
            'to_employee' => $this->bigInteger(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);

        //musteri_id
        $this->createIndex(
            'idx-tikuv_doc-musteri_id',
            'tikuv_doc',
            'musteri_id'
        );

        $this->addForeignKey(
            'fk-tikuv_doc-musteri_id',
            'tikuv_doc',
            'musteri_id',
            'musteri',
            'id'
        );

        //to_department
        $this->createIndex(
            'idx-tikuv_doc-to_department',
            'tikuv_doc',
            'to_department'
        );

        $this->addForeignKey(
            'fk-tikuv_doc-to_department',
            'tikuv_doc',
            'to_department',
            'toquv_departments',
            'id'
        );
        //from_department
        $this->createIndex(
            'idx-tikuv_doc-from_department',
            'tikuv_doc',
            'from_department'
        );

        $this->addForeignKey(
            'fk-tikuv_doc-from_department',
            'tikuv_doc',
            'from_department',
            'toquv_departments',
            'id'
        );

        //from_employee
        $this->createIndex(
            'idx-tikuv_doc-from_employee',
            'tikuv_doc',
            'from_employee'
        );

        $this->addForeignKey(
            'fk-tikuv_doc-from_employee',
            'tikuv_doc',
            'from_employee',
            'users',
            'id'
        );

        //to_employee
        $this->createIndex(
            'idx-tikuv_doc-to_employee',
            'tikuv_doc',
            'to_employee'
        );

        $this->addForeignKey(
            'fk-tikuv_doc-to_employee',
            'tikuv_doc',
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
        //musteri_id
        $this->dropForeignKey(
            'fk-tikuv_doc-musteri_id',
            'tikuv_doc'
        );

        $this->dropIndex(
            'idx-tikuv_doc-musteri_id',
            'tikuv_doc'
        );

        //from_department
        $this->dropForeignKey(
            'fk-tikuv_doc-from_department',
            'tikuv_doc'
        );

        $this->dropIndex(
            'idx-tikuv_doc-from_department',
            'tikuv_doc'
        );
        //to_department
        $this->dropForeignKey(
            'fk-tikuv_doc-to_department',
            'tikuv_doc'
        );

        $this->dropIndex(
            'idx-tikuv_doc-to_department',
            'tikuv_doc'
        );
        //from_employee
        $this->dropForeignKey(
            'fk-tikuv_doc-from_employee',
            'tikuv_doc'
        );

        $this->dropIndex(
            'idx-tikuv_doc-from_employee',
            'tikuv_doc'
        );
        //to_employee
        $this->dropForeignKey(
            'fk-tikuv_doc-to_employee',
            'tikuv_doc'
        );

        $this->dropIndex(
            'idx-tikuv_doc-to_employee',
            'tikuv_doc'
        );
        $this->dropTable('{{%tikuv_doc}}');
    }
}
