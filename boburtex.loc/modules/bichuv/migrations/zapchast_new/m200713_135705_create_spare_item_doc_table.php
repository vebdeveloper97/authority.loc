<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_item_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 * - `{{%hr_departments}}`
 * - `{{%hr_departments}}`
 * - `{{%hr_employee}}`
 * - `{{%hr_employee}}`
 * - `{{%wms_department_area}}`
 * - `{{%wms_department_area}}`
 */
class m200713_135705_create_spare_item_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_item_doc}}', [
            'id' => $this->primaryKey(),
            'document_type' => $this->smallInteger(6),
            'doc_number' => $this->char(25),
            'reg_date' => $this->datetime(),
            'musteri_id' => $this->integer(),
            'from_department' => $this->integer(),
            'to_department' => $this->integer(),
            'from_employee' => $this->integer(),
            'to_employee' => $this->integer(),
            'from_area' => $this->integer(),
            'to_area' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-spare_item_doc-musteri_id}}',
            '{{%spare_item_doc}}',
            'musteri_id'
        );

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-spare_item_doc-from_department}}',
            '{{%spare_item_doc}}',
            'from_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc-from_department}}',
            '{{%spare_item_doc}}',
            'from_department',
            '{{%hr_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_department`
        $this->createIndex(
            '{{%idx-spare_item_doc-to_department}}',
            '{{%spare_item_doc}}',
            'to_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc-to_department}}',
            '{{%spare_item_doc}}',
            'to_department',
            '{{%hr_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `from_employee`
        $this->createIndex(
            '{{%idx-spare_item_doc-from_employee}}',
            '{{%spare_item_doc}}',
            'from_employee'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc-from_employee}}',
            '{{%spare_item_doc}}',
            'from_employee',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_employee`
        $this->createIndex(
            '{{%idx-spare_item_doc-to_employee}}',
            '{{%spare_item_doc}}',
            'to_employee'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc-to_employee}}',
            '{{%spare_item_doc}}',
            'to_employee',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );

        // creates index for column `from_area`
        $this->createIndex(
            '{{%idx-spare_item_doc-from_area}}',
            '{{%spare_item_doc}}',
            'from_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc-from_area}}',
            '{{%spare_item_doc}}',
            'from_area',
            '{{%wms_department_area}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_area`
        $this->createIndex(
            '{{%idx-spare_item_doc-to_area}}',
            '{{%spare_item_doc}}',
            'to_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc-to_area}}',
            '{{%spare_item_doc}}',
            'to_area',
            '{{%wms_department_area}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-spare_item_doc-musteri_id}}',
            '{{%spare_item_doc}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc-from_department}}',
            '{{%spare_item_doc}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-spare_item_doc-from_department}}',
            '{{%spare_item_doc}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc-to_department}}',
            '{{%spare_item_doc}}'
        );

        // drops index for column `to_department`
        $this->dropIndex(
            '{{%idx-spare_item_doc-to_department}}',
            '{{%spare_item_doc}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc-from_employee}}',
            '{{%spare_item_doc}}'
        );

        // drops index for column `from_employee`
        $this->dropIndex(
            '{{%idx-spare_item_doc-from_employee}}',
            '{{%spare_item_doc}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc-to_employee}}',
            '{{%spare_item_doc}}'
        );

        // drops index for column `to_employee`
        $this->dropIndex(
            '{{%idx-spare_item_doc-to_employee}}',
            '{{%spare_item_doc}}'
        );

        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc-from_area}}',
            '{{%spare_item_doc}}'
        );

        // drops index for column `from_area`
        $this->dropIndex(
            '{{%idx-spare_item_doc-from_area}}',
            '{{%spare_item_doc}}'
        );

        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc-to_area}}',
            '{{%spare_item_doc}}'
        );

        // drops index for column `to_area`
        $this->dropIndex(
            '{{%idx-spare_item_doc-to_area}}',
            '{{%spare_item_doc}}'
        );

        $this->dropTable('{{%spare_item_doc}}');
    }
}
