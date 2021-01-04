<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wh_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wh_document}}`
 * - `{{%pb}}`
 * - `{{%unit}}`
 */
class m200421_080205_create_wh_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wh_document_items}}', [
            'id' => $this->primaryKey(),
            'wh_document_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger(6),
            'lot' => $this->string(50),
            'document_qty' => $this->decimal(20,3),
            'quantity' => $this->decimal(20,3),
            'dep_section' => $this->integer(),
            'dep_area' => $this->integer(),
            'incoming_price' => $this->decimal(20,2),
            'incoming_pb_id' => $this->integer(),
            'wh_price' => $this->decimal(20,2),
            'wh_pb_id' => $this->integer(),
            'package_type' => $this->integer(),
            'package_qty' => $this->decimal(20,3),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
        ]);

        // creates index for column `wh_document_id`
        $this->createIndex(
            '{{%idx-wh_document_items-wh_document_id}}',
            '{{%wh_document_items}}',
            'wh_document_id'
        );

        // add foreign key for table `{{%wh_document}}`
        $this->addForeignKey(
            '{{%fk-wh_document_items-wh_document_id}}',
            '{{%wh_document_items}}',
            'wh_document_id',
            '{{%wh_document}}',
            'id',
            'CASCADE'
        );

        // creates index for column `dep_section`
        $this->createIndex(
            '{{%idx-wh_document_items-dep_section}}',
            '{{%wh_document_items}}',
            'dep_section'
        );

        // add foreign key for table `{{%wh_department_area}}`
        $this->addForeignKey(
            '{{%fk-wh_document_items-dep_section}}',
            '{{%wh_document_items}}',
            'dep_section',
            '{{%wh_department_area}}',
            'id'
        );


        // creates index for column `dep_area`
        $this->createIndex(
            '{{%idx-wh_document_items-dep_area}}',
            '{{%wh_document_items}}',
            'dep_area'
        );

        // add foreign key for table `{{%wh_department_area}}`
        $this->addForeignKey(
            '{{%fk-wh_document_items-dep_area}}',
            '{{%wh_document_items}}',
            'dep_area',
            '{{%wh_department_area}}',
            'id'
        );

        // creates index for column `incoming_pb_id`
        $this->createIndex(
            '{{%idx-wh_document_items-incoming_pb_id}}',
            '{{%wh_document_items}}',
            'incoming_pb_id'
        );

        // add foreign key for table `{{%pb}}`
        $this->addForeignKey(
            '{{%fk-wh_document_items-incoming_pb_id}}',
            '{{%wh_document_items}}',
            'incoming_pb_id',
            '{{%pul_birligi}}',
            'id'
        );

        // creates index for column `wh_pb_id`
        $this->createIndex(
            '{{%idx-wh_document_items-wh_pb_id}}',
            '{{%wh_document_items}}',
            'wh_pb_id'
        );

        // add foreign key for table `{{%pb}}`
        $this->addForeignKey(
            '{{%fk-wh_document_items-wh_pb_id}}',
            '{{%wh_document_items}}',
            'wh_pb_id',
            '{{%pul_birligi}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wh_document}}`
        $this->dropForeignKey(
            '{{%fk-wh_document_items-wh_document_id}}',
            '{{%wh_document_items}}'
        );

        // drops index for column `wh_document_id`
        $this->dropIndex(
            '{{%idx-wh_document_items-wh_document_id}}',
            '{{%wh_document_items}}'
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

        $this->dropTable('{{%wh_document_items}}');
    }
}
