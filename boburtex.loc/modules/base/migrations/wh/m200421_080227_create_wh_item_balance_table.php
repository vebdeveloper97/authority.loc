<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wh_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_departments}}`
 * - `{{%toquv_departments}}`
 * - `{{%musteri}}`
 * - `{{%toquv_departments}}`
 * - `{{%musteri}}`
 * - `{{%pb}}`
 * - `{{%wh_document}}`
 */
class m200421_080227_create_wh_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wh_item_balance}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger(6),
            'lot' => $this->string(50),
            'quantity' => $this->decimal(20,3),
            'inventory' => $this->decimal(20,3),
            'reg_date' => $this->dateTime(),
            'department_id' => $this->integer(),
            'dep_section' => $this->integer(),
            'dep_area' => $this->integer(),
            'wh_document_id' => $this->integer(),
            'incoming_price' => $this->decimal(20,2),
            'incoming_pb_id' => $this->integer(),
            'wh_price' => $this->decimal(20,2),
            'wh_pb_id' => $this->integer(),
            'package_type' => $this->integer(),
            'package_qty' => $this->decimal(20,3),
            'package_inventory' => $this->decimal(20,3),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-wh_item_balance-department_id}}',
            '{{%wh_item_balance}}',
            'department_id'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-wh_item_balance-department_id}}',
            '{{%wh_item_balance}}',
            'department_id',
            '{{%toquv_departments}}',
            'id'
        );

        // creates index for column `dep_section`
        $this->createIndex(
            '{{%idx-wh_item_balance-dep_section}}',
            '{{%wh_item_balance}}',
            'dep_section'
        );

        // add foreign key for table `{{%wh_department_area}}`
        $this->addForeignKey(
            '{{%fk-wh_item_balance-dep_section}}',
            '{{%wh_item_balance}}',
            'dep_section',
            '{{%wh_department_area}}',
            'id'
        );

        // creates index for column `dep_area`
        $this->createIndex(
            '{{%idx-wh_item_balance-dep_area}}',
            '{{%wh_item_balance}}',
            'dep_area'
        );

        // add foreign key for table `{{%wh_department_area}}`
        $this->addForeignKey(
            '{{%fk-wh_item_balance-dep_area}}',
            '{{%wh_item_balance}}',
            'dep_area',
            '{{%wh_department_area}}',
            'id'
        );

        // creates index for column `incoming_pb_id`
        $this->createIndex(
            '{{%idx-wh_item_balance-incoming_pb_id}}',
            '{{%wh_item_balance}}',
            'incoming_pb_id'
        );

        // add foreign key for table `{{%pb}}`
        $this->addForeignKey(
            '{{%fk-wh_item_balance-incoming_pb_id}}',
            '{{%wh_item_balance}}',
            'incoming_pb_id',
            '{{%pul_birligi}}',
            'id'
        );

        // creates index for column `wh_pb_id`
        $this->createIndex(
            '{{%idx-wh_item_balance-wh_pb_id}}',
            '{{%wh_item_balance}}',
            'wh_pb_id'
        );

        // add foreign key for table `{{%pb}}`
        $this->addForeignKey(
            '{{%fk-wh_item_balance-wh_pb_id}}',
            '{{%wh_item_balance}}',
            'wh_pb_id',
            '{{%pul_birligi}}',
            'id'
        );

        // creates index for column `wh_document_id`
        $this->createIndex(
            '{{%idx-wh_item_balance-wh_document_id}}',
            '{{%wh_item_balance}}',
            'wh_document_id'
        );

        // add foreign key for table `{{%wh_document}}`
        $this->addForeignKey(
            '{{%fk-wh_item_balance-wh_document_id}}',
            '{{%wh_item_balance}}',
            'wh_document_id',
            '{{%wh_document}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-wh_item_balance-department_id}}',
            '{{%wh_item_balance}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-wh_item_balance-department_id}}',
            '{{%wh_item_balance}}'
        );

        // drops foreign key for table `{{%wh_document}}`
        $this->dropForeignKey(
            '{{%fk-wh_item_balance-wh_document_id}}',
            '{{%wh_item_balance}}'
        );

        // drops index for column `wh_document_id`
        $this->dropIndex(
            '{{%idx-wh_item_balance-wh_document_id}}',
            '{{%wh_item_balance}}'
        );

        // drops foreign key for table `{{%wh_department_area}}`
        $this->dropForeignKey(
            '{{%fk-wh_document_items-dep_section}}',
            '{{%wh_document_items}}'
        );

        // drops index for column `dep_section`
        $this->dropIndex(
            '{{%idx-wh_document_items-dep_section}}',
            '{{%wh_document_items}}'
        );

        // drops foreign key for table `{{%wh_department_area}}`
        $this->dropForeignKey(
            '{{%fk-wh_document_items-dep_area}}',
            '{{%wh_document_items}}'
        );

        // drops index for column `dep_area`
        $this->dropIndex(
            '{{%idx-wh_document_items-dep_area}}',
            '{{%wh_document_items}}'
        );

        // drops foreign key for table `{{%pb}}`
        $this->dropForeignKey(
            '{{%fk-wh_document_items-incoming_pb_id}}',
            '{{%wh_document_items}}'
        );

        // drops index for column `incoming_pb_id`
        $this->dropIndex(
            '{{%idx-wh_document_items-incoming_pb_id}}',
            '{{%wh_document_items}}'
        );


        // drops foreign key for table `{{%pb}}`
        $this->dropForeignKey(
            '{{%fk-wh_document_items-wh_pb_id}}',
            '{{%wh_document_items}}'
        );

        // drops index for column `wh_pb_id`
        $this->dropIndex(
            '{{%idx-wh_document_items-wh_pb_id}}',
            '{{%wh_document_items}}'
        );

        $this->dropTable('{{%wh_item_balance}}');
    }
}
