<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_documents}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 * - `{{%toquv_departments}}`
 * - `{{%users}}`
 * - `{{%toquv_departments}}`
 * - `{{%users}}`
 */
class m191106_115319_create_tikuv_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_documents}}', [
            'id' => $this->primaryKey(),
            'document_type' => $this->smallInteger(6),
            'action' => $this->smallInteger(6),
            'doc_number' => $this->string(25),
            'reg_date' => $this->dateTime(),
            'musteri_id' => $this->bigInteger(),
            'musteri_responsible' => $this->string(),
            'from_department' => $this->integer(),
            'from_employee' => $this->bigInteger(),
            'to_department' => $this->integer(),
            'to_employee' => $this->bigInteger(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-tikuv_documents-musteri_id}}',
            '{{%tikuv_documents}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-tikuv_documents-musteri_id}}',
            '{{%tikuv_documents}}',
            'musteri_id',
            '{{%musteri}}',
            'id',
            'CASCADE'
        );

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-tikuv_documents-from_department}}',
            '{{%tikuv_documents}}',
            'from_department'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-tikuv_documents-from_department}}',
            '{{%tikuv_documents}}',
            'from_department',
            '{{%toquv_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `from_employee`
        $this->createIndex(
            '{{%idx-tikuv_documents-from_employee}}',
            '{{%tikuv_documents}}',
            'from_employee'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-tikuv_documents-from_employee}}',
            '{{%tikuv_documents}}',
            'from_employee',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_department`
        $this->createIndex(
            '{{%idx-tikuv_documents-to_department}}',
            '{{%tikuv_documents}}',
            'to_department'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-tikuv_documents-to_department}}',
            '{{%tikuv_documents}}',
            'to_department',
            '{{%toquv_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_employee`
        $this->createIndex(
            '{{%idx-tikuv_documents-to_employee}}',
            '{{%tikuv_documents}}',
            'to_employee'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-tikuv_documents-to_employee}}',
            '{{%tikuv_documents}}',
            'to_employee',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_documents-musteri_id}}',
            '{{%tikuv_documents}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-tikuv_documents-musteri_id}}',
            '{{%tikuv_documents}}'
        );

        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_documents-from_department}}',
            '{{%tikuv_documents}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-tikuv_documents-from_department}}',
            '{{%tikuv_documents}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_documents-from_employee}}',
            '{{%tikuv_documents}}'
        );

        // drops index for column `from_employee`
        $this->dropIndex(
            '{{%idx-tikuv_documents-from_employee}}',
            '{{%tikuv_documents}}'
        );

        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_documents-to_department}}',
            '{{%tikuv_documents}}'
        );

        // drops index for column `to_department`
        $this->dropIndex(
            '{{%idx-tikuv_documents-to_department}}',
            '{{%tikuv_documents}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_documents-to_employee}}',
            '{{%tikuv_documents}}'
        );

        // drops index for column `to_employee`
        $this->dropIndex(
            '{{%idx-tikuv_documents-to_employee}}',
            '{{%tikuv_documents}}'
        );

        $this->dropTable('{{%tikuv_documents}}');
    }
}
