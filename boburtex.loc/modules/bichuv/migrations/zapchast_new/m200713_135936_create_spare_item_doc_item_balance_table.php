<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_item_doc_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%spare_item}}`
 * - `{{%hr_departments}}`
 * - `{{%spare_item_doc}}`
 * - `{{%hr_departments}}`
 * - `{{%hr_departments}}`
 * - `{{%wms_department_area}}`
 * - `{{%wms_department_area}}`
 * - `{{%wms_department_area}}`
 */
class m200713_135936_create_spare_item_doc_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_item_doc_item_balance}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            'quantity' => $this->decimal(20,3),
            'inventory' => $this->decimal(20,3),
            'reg_date' => $this->datetime(),
            'department_id' => $this->integer(),
            'price_uzs' => $this->decimal(20,3),
            'price_usd' => $this->decimal(20,3),
            'document_id' => $this->integer(),
            'add_info' => $this->text(),
            'document_type' => $this->smallInteger(),
            'from_department' => $this->integer(),
            'to_department' => $this->integer(),
            'dep_area' => $this->integer(),
            'from_area' => $this->integer(),
            'to_area' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `entity_id`
        $this->createIndex(
            '{{%idx-spare_item_doc_item_balance-entity_id}}',
            '{{%spare_item_doc_item_balance}}',
            'entity_id'
        );

        // add foreign key for table `{{%spare_item}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_item_balance-entity_id}}',
            '{{%spare_item_doc_item_balance}}',
            'entity_id',
            '{{%spare_item}}',
            'id',
            'CASCADE'
        );

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-spare_item_doc_item_balance-department_id}}',
            '{{%spare_item_doc_item_balance}}',
            'department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_item_balance-department_id}}',
            '{{%spare_item_doc_item_balance}}',
            'department_id',
            '{{%hr_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `document_id`
        $this->createIndex(
            '{{%idx-spare_item_doc_item_balance-document_id}}',
            '{{%spare_item_doc_item_balance}}',
            'document_id'
        );

        // add foreign key for table `{{%spare_item_doc}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_item_balance-document_id}}',
            '{{%spare_item_doc_item_balance}}',
            'document_id',
            '{{%spare_item_doc}}',
            'id',
            'CASCADE'
        );

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-spare_item_doc_item_balance-from_department}}',
            '{{%spare_item_doc_item_balance}}',
            'from_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_item_balance-from_department}}',
            '{{%spare_item_doc_item_balance}}',
            'from_department',
            '{{%hr_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_department`
        $this->createIndex(
            '{{%idx-spare_item_doc_item_balance-to_department}}',
            '{{%spare_item_doc_item_balance}}',
            'to_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_item_balance-to_department}}',
            '{{%spare_item_doc_item_balance}}',
            'to_department',
            '{{%hr_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `dep_area`
        $this->createIndex(
            '{{%idx-spare_item_doc_item_balance-dep_area}}',
            '{{%spare_item_doc_item_balance}}',
            'dep_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_item_balance-dep_area}}',
            '{{%spare_item_doc_item_balance}}',
            'dep_area',
            '{{%wms_department_area}}',
            'id',
            'CASCADE'
        );

        // creates index for column `from_area`
        $this->createIndex(
            '{{%idx-spare_item_doc_item_balance-from_area}}',
            '{{%spare_item_doc_item_balance}}',
            'from_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_item_balance-from_area}}',
            '{{%spare_item_doc_item_balance}}',
            'from_area',
            '{{%wms_department_area}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_area`
        $this->createIndex(
            '{{%idx-spare_item_doc_item_balance-to_area}}',
            '{{%spare_item_doc_item_balance}}',
            'to_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_item_balance-to_area}}',
            '{{%spare_item_doc_item_balance}}',
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
        // drops foreign key for table `{{%spare_item}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_item_balance-entity_id}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops index for column `entity_id`
        $this->dropIndex(
            '{{%idx-spare_item_doc_item_balance-entity_id}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_item_balance-department_id}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-spare_item_doc_item_balance-department_id}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops foreign key for table `{{%spare_item_doc}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_item_balance-document_id}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops index for column `document_id`
        $this->dropIndex(
            '{{%idx-spare_item_doc_item_balance-document_id}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_item_balance-from_department}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-spare_item_doc_item_balance-from_department}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_item_balance-to_department}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops index for column `to_department`
        $this->dropIndex(
            '{{%idx-spare_item_doc_item_balance-to_department}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_item_balance-dep_area}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops index for column `dep_area`
        $this->dropIndex(
            '{{%idx-spare_item_doc_item_balance-dep_area}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_item_balance-from_area}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops index for column `from_area`
        $this->dropIndex(
            '{{%idx-spare_item_doc_item_balance-from_area}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_item_balance-to_area}}',
            '{{%spare_item_doc_item_balance}}'
        );

        // drops index for column `to_area`
        $this->dropIndex(
            '{{%idx-spare_item_doc_item_balance-to_area}}',
            '{{%spare_item_doc_item_balance}}'
        );

        $this->dropTable('{{%spare_item_doc_item_balance}}');
    }
}
