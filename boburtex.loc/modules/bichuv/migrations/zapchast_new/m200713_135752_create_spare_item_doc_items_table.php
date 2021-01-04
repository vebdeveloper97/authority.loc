<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_item_doc_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%spare_item_doc}}`
 * - `{{%spare_item}}`
 * - `{{%wms_department_area}}`
 * - `{{%wms_department_area}}`
 */
class m200713_135752_create_spare_item_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_item_doc_items}}', [
            'id' => $this->primaryKey(),
            'spare_item_doc_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'quantity' => $this->decimal(20,3),
            'price_sum' => $this->decimal(20,3),
            'price_usd' => $this->decimal(20,3),
            'from_area' => $this->integer(),
            'to_area' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `spare_item_doc_id`
        $this->createIndex(
            '{{%idx-spare_item_doc_items-spare_item_doc_id}}',
            '{{%spare_item_doc_items}}',
            'spare_item_doc_id'
        );

        // add foreign key for table `{{%spare_item_doc}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_items-spare_item_doc_id}}',
            '{{%spare_item_doc_items}}',
            'spare_item_doc_id',
            '{{%spare_item_doc}}',
            'id',
            'CASCADE'
        );

        // creates index for column `entity_id`
        $this->createIndex(
            '{{%idx-spare_item_doc_items-entity_id}}',
            '{{%spare_item_doc_items}}',
            'entity_id'
        );

        // add foreign key for table `{{%spare_item}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_items-entity_id}}',
            '{{%spare_item_doc_items}}',
            'entity_id',
            '{{%spare_item}}',
            'id',
            'CASCADE'
        );

        // creates index for column `from_area`
        $this->createIndex(
            '{{%idx-spare_item_doc_items-from_area}}',
            '{{%spare_item_doc_items}}',
            'from_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_items-from_area}}',
            '{{%spare_item_doc_items}}',
            'from_area',
            '{{%wms_department_area}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_area`
        $this->createIndex(
            '{{%idx-spare_item_doc_items-to_area}}',
            '{{%spare_item_doc_items}}',
            'to_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_items-to_area}}',
            '{{%spare_item_doc_items}}',
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
        // drops foreign key for table `{{%spare_item_doc}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_items-spare_item_doc_id}}',
            '{{%spare_item_doc_items}}'
        );

        // drops index for column `spare_item_doc_id`
        $this->dropIndex(
            '{{%idx-spare_item_doc_items-spare_item_doc_id}}',
            '{{%spare_item_doc_items}}'
        );

        // drops foreign key for table `{{%spare_item}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_items-entity_id}}',
            '{{%spare_item_doc_items}}'
        );

        // drops index for column `entity_id`
        $this->dropIndex(
            '{{%idx-spare_item_doc_items-entity_id}}',
            '{{%spare_item_doc_items}}'
        );

        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_items-from_area}}',
            '{{%spare_item_doc_items}}'
        );

        // drops index for column `from_area`
        $this->dropIndex(
            '{{%idx-spare_item_doc_items-from_area}}',
            '{{%spare_item_doc_items}}'
        );

        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_items-to_area}}',
            '{{%spare_item_doc_items}}'
        );

        // drops index for column `to_area`
        $this->dropIndex(
            '{{%idx-spare_item_doc_items-to_area}}',
            '{{%spare_item_doc_items}}'
        );

        $this->dropTable('{{%spare_item_doc_items}}');
    }
}
