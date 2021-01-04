<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_inspection_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%spare_inspection}}`
 * - `{{%spare_control_list}}`
 */
class m200911_150111_create_spare_inspection_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_inspection_items}}', [
            'id' => $this->primaryKey(),
            'spare_inspection_id' => $this->integer(),
            'spare_control_list_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `spare_inspection_id`
        $this->createIndex(
            '{{%idx-spare_inspection_items-spare_inspection_id}}',
            '{{%spare_inspection_items}}',
            'spare_inspection_id'
        );

        // add foreign key for table `{{%spare_inspection}}`
        $this->addForeignKey(
            '{{%fk-spare_inspection_items-spare_inspection_id}}',
            '{{%spare_inspection_items}}',
            'spare_inspection_id',
            '{{%spare_inspection}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `spare_control_list_id`
        $this->createIndex(
            '{{%idx-spare_inspection_items-spare_control_list_id}}',
            '{{%spare_inspection_items}}',
            'spare_control_list_id'
        );

        // add foreign key for table `{{%spare_control_list}}`
        $this->addForeignKey(
            '{{%fk-spare_inspection_items-spare_control_list_id}}',
            '{{%spare_inspection_items}}',
            'spare_control_list_id',
            '{{%spare_control_list}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%spare_inspection}}`
        $this->dropForeignKey(
            '{{%fk-spare_inspection_items-spare_inspection_id}}',
            '{{%spare_inspection_items}}'
        );

        // drops index for column `spare_inspection_id`
        $this->dropIndex(
            '{{%idx-spare_inspection_items-spare_inspection_id}}',
            '{{%spare_inspection_items}}'
        );

        // drops foreign key for table `{{%spare_control_list}}`
        $this->dropForeignKey(
            '{{%fk-spare_inspection_items-spare_control_list_id}}',
            '{{%spare_inspection_items}}'
        );

        // drops index for column `spare_control_list_id`
        $this->dropIndex(
            '{{%idx-spare_inspection_items-spare_control_list_id}}',
            '{{%spare_inspection_items}}'
        );

        $this->dropTable('{{%spare_inspection_items}}');
    }
}
