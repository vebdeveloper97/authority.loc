<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_item_property}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%spare_item}}`
 * - `{{%spare_item_property_list}}`
 */
class m200713_135542_create_spare_item_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_item_property}}', [
            'id' => $this->primaryKey(),
            'spare_item_id' => $this->integer(),
            'spare_item_property_list_id' => $this->integer(),
            'value' => $this->char(255),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `spare_item_id`
        $this->createIndex(
            '{{%idx-spare_item_property-spare_item_id}}',
            '{{%spare_item_property}}',
            'spare_item_id'
        );

        // add foreign key for table `{{%spare_item}}`
        $this->addForeignKey(
            '{{%fk-spare_item_property-spare_item_id}}',
            '{{%spare_item_property}}',
            'spare_item_id',
            '{{%spare_item}}',
            'id',
            'CASCADE'
        );

        // creates index for column `spare_item_property_list_id`
        $this->createIndex(
            '{{%idx-spare_item_property-spare_item_property_list_id}}',
            '{{%spare_item_property}}',
            'spare_item_property_list_id'
        );

        // add foreign key for table `{{%spare_item_property_list}}`
        $this->addForeignKey(
            '{{%fk-spare_item_property-spare_item_property_list_id}}',
            '{{%spare_item_property}}',
            'spare_item_property_list_id',
            '{{%spare_item_property_list}}',
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
            '{{%fk-spare_item_property-spare_item_id}}',
            '{{%spare_item_property}}'
        );

        // drops index for column `spare_item_id`
        $this->dropIndex(
            '{{%idx-spare_item_property-spare_item_id}}',
            '{{%spare_item_property}}'
        );

        // drops foreign key for table `{{%spare_item_property_list}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_property-spare_item_property_list_id}}',
            '{{%spare_item_property}}'
        );

        // drops index for column `spare_item_property_list_id`
        $this->dropIndex(
            '{{%idx-spare_item_property-spare_item_property_list_id}}',
            '{{%spare_item_property}}'
        );

        $this->dropTable('{{%spare_item_property}}');
    }
}
