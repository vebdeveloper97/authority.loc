<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%boyahane_mixing_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wh_item}}`
 * - `{{%wh_document}}`
 */
class m200607_173442_create_boyahane_mixing_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%boyahane_mixing_items}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger(6),
            'lot' => $this->string(50),
            'wh_document_id' => $this->integer(),
            'department_id' => $this->integer(),
            'dep_section' => $this->integer(),
            'dep_area' => $this->integer(),
            'wh_price' => $this->decimal(20,2),
            'wh_pb_id' => $this->integer(),
            'package_type' => $this->integer(),
            'quantity' => $this->decimal(20,3),
            'unit_id' => $this->integer(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `wh_item_id`
        $this->createIndex(
            '{{%idx-boyahane_mixing_items-entity_id}}',
            '{{%boyahane_mixing_items}}',
            'entity_id'
        );

        // add foreign key for table `{{%wh_item}}`
        $this->addForeignKey(
            '{{%fk-boyahane_mixing_items-entity_id}}',
            '{{%boyahane_mixing_items}}',
            'entity_id',
            '{{%wh_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `wh_document_id`
        $this->createIndex(
            '{{%idx-boyahane_mixing_items-wh_document_id}}',
            '{{%boyahane_mixing_items}}',
            'wh_document_id'
        );

        // add foreign key for table `{{%wh_document}}`
        $this->addForeignKey(
            '{{%fk-boyahane_mixing_items-wh_document_id}}',
            '{{%boyahane_mixing_items}}',
            'wh_document_id',
            '{{%wh_document}}',
            'id',
            'CASCADE'
        );

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-boyahane_mixing_items-unit_id}}',
            '{{%boyahane_mixing_items}}',
            'unit_id'
        );

        // add foreign key for table `{{%wh_document}}`
        $this->addForeignKey(
            '{{%fk-boyahane_mixing_items-unit_id}}',
            '{{%boyahane_mixing_items}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wh_item}}`
        $this->dropForeignKey(
            '{{%fk-boyahane_mixing_items-entity_id}}',
            '{{%boyahane_mixing_items}}'
        );

        // drops index for column `wh_item_id`
        $this->dropIndex(
            '{{%idx-boyahane_mixing_items-entity_id}}',
            '{{%boyahane_mixing_items}}'
        );

        // drops foreign key for table `{{%wh_document}}`
        $this->dropForeignKey(
            '{{%fk-boyahane_mixing_items-wh_document_id}}',
            '{{%boyahane_mixing_items}}'
        );

        // drops index for column `wh_document_id`
        $this->dropIndex(
            '{{%idx-boyahane_mixing_items-wh_document_id}}',
            '{{%boyahane_mixing_items}}'
        );

        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-boyahane_mixing_items-unit_id}}',
            '{{%boyahane_mixing_items}}'
        );

        // drops index for column `uunit_id`
        $this->dropIndex(
            '{{%idx-boyahane_mixing_items-unit_id}}',
            '{{%boyahane_mixing_items}}'
        );

        $this->dropTable('{{%boyahane_mixing_items}}');
    }
}
