<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wh_document}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 * - `{{%toquv_departments}}`
 * - `{{%users}}`
 * - `{{%musteri}}`
 * - `{{%toquv_departments}}`
 * - `{{%users}}`
 * - `{{%musteri}}`
 */
class m200421_080123_create_wh_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wh_document}}', [
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
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-wh_document-musteri_id}}',
            '{{%wh_document}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-wh_document-musteri_id}}',
            '{{%wh_document}}',
            'musteri_id',
            '{{%musteri}}',
            'id'
        );

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-wh_document-from_department}}',
            '{{%wh_document}}',
            'from_department'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-wh_document-from_department}}',
            '{{%wh_document}}',
            'from_department',
            '{{%toquv_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `from_employee`
        $this->createIndex(
            '{{%idx-wh_document-from_employee}}',
            '{{%wh_document}}',
            'from_employee'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-wh_document-from_employee}}',
            '{{%wh_document}}',
            'from_employee',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_department`
        $this->createIndex(
            '{{%idx-wh_document-to_department}}',
            '{{%wh_document}}',
            'to_department'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-wh_document-to_department}}',
            '{{%wh_document}}',
            'to_department',
            '{{%toquv_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_employee`
        $this->createIndex(
            '{{%idx-wh_document-to_employee}}',
            '{{%wh_document}}',
            'to_employee'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-wh_document-to_employee}}',
            '{{%wh_document}}',
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
            '{{%fk-wh_document-musteri_id}}',
            '{{%wh_document}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-wh_document-musteri_id}}',
            '{{%wh_document}}'
        );

        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-wh_document-from_department}}',
            '{{%wh_document}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-wh_document-from_department}}',
            '{{%wh_document}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-wh_document-from_employee}}',
            '{{%wh_document}}'
        );

        // drops index for column `from_employee`
        $this->dropIndex(
            '{{%idx-wh_document-from_employee}}',
            '{{%wh_document}}'
        );

        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-wh_document-to_department}}',
            '{{%wh_document}}'
        );

        // drops index for column `to_department`
        $this->dropIndex(
            '{{%idx-wh_document-to_department}}',
            '{{%wh_document}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-wh_document-to_employee}}',
            '{{%wh_document}}'
        );

        // drops index for column `to_employee`
        $this->dropIndex(
            '{{%idx-wh_document-to_employee}}',
            '{{%wh_document}}'
        );

        $this->dropTable('{{%wh_document}}');
    }
}
