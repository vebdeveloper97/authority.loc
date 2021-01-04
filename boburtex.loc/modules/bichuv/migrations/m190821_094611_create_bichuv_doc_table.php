<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_doc}}`.
 */
class m190821_094611_create_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_doc}}', [
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
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11)
        ]);

        //musteri_id
        $this->createIndex(
            'idx-bichuv_doc-musteri_id',
            'bichuv_doc',
            'musteri_id'
        );

        $this->addForeignKey(
            'fk-bichuv_doc-musteri_id',
            'bichuv_doc',
            'musteri_id',
            'musteri',
            'id'
        );

        //to_department
        $this->createIndex(
            'idx-bichuv_doc-to_department',
            'bichuv_doc',
            'to_department'
        );

        $this->addForeignKey(
            'fk-bichuv_doc-to_department',
            'bichuv_doc',
            'to_department',
            'toquv_departments',
            'id'
        );
        //from_department
        $this->createIndex(
            'idx-bichuv_doc-from_department',
            'bichuv_doc',
            'from_department'
        );

        $this->addForeignKey(
            'fk-bichuv_doc-from_department',
            'bichuv_doc',
            'from_department',
            'toquv_departments',
            'id'
        );

        //from_employee
        $this->createIndex(
            'idx-bichuv_doc-from_employee',
            'bichuv_doc',
            'from_employee'
        );

        $this->addForeignKey(
            'fk-bichuv_doc-from_employee',
            'bichuv_doc',
            'from_employee',
            'users',
            'id'
        );

        //to_employee
        $this->createIndex(
            'idx-bichuv_doc-to_employee',
            'bichuv_doc',
            'to_employee'
        );

        $this->addForeignKey(
            'fk-bichuv_doc-to_employee',
            'bichuv_doc',
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
            'fk-bichuv_doc-musteri_id',
            'bichuv_doc'
        );

        $this->dropIndex(
            'idx-bichuv_doc-musteri_id',
            'bichuv_doc'
        );

        //to
        $this->dropForeignKey(
            'fk-bichuv_doc-to',
            'bichuv_doc'
        );

        $this->dropIndex(
            'idx-bichuv_doc-to',
            'bichuv_doc'
        );

        $this->dropTable('{{%bichuv_doc}}');
    }
}
