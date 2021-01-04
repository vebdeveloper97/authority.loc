<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_passport_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%spare_item_rel_hr_employee}}`
 * - `{{%spare_control_list}}`
 */
class m200805_065530_create_spare_passport_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_passport_items}}', [
            'id' => $this->primaryKey(),
            'sirhe_id' => $this->integer(),
            'spare_control_id' => $this->integer(),
            'interval_control_date' => $this->decimal(20,3),
            'control_date_type' => $this->integer(1),
            'start_control_date' => $this->timestamp(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `sirhe_id`
        $this->createIndex(
            '{{%idx-spare_passport_items-sirhe_id}}',
            '{{%spare_passport_items}}',
            'sirhe_id'
        );

        // add foreign key for table `{{%spare_item_rel_hr_employee}}`
        $this->addForeignKey(
            '{{%fk-spare_passport_items-sirhe_id}}',
            '{{%spare_passport_items}}',
            'sirhe_id',
            '{{%spare_item_rel_hr_employee}}',
            'id'
        );

        // creates index for column `spare_control_id`
        $this->createIndex(
            '{{%idx-spare_passport_items-spare_control_id}}',
            '{{%spare_passport_items}}',
            'spare_control_id'
        );

        // add foreign key for table `{{%spare_control_list}}`
        $this->addForeignKey(
            '{{%fk-spare_passport_items-spare_control_id}}',
            '{{%spare_passport_items}}',
            'spare_control_id',
            '{{%spare_control_list}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%spare_item_rel_hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-spare_passport_items-sirhe_id}}',
            '{{%spare_passport_items}}'
        );

        // drops index for column `sirhe_id`
        $this->dropIndex(
            '{{%idx-spare_passport_items-sirhe_id}}',
            '{{%spare_passport_items}}'
        );

        // drops foreign key for table `{{%spare_control_list}}`
        $this->dropForeignKey(
            '{{%fk-spare_passport_items-spare_control_id}}',
            '{{%spare_passport_items}}'
        );

        // drops index for column `spare_control_id`
        $this->dropIndex(
            '{{%idx-spare_passport_items-spare_control_id}}',
            '{{%spare_passport_items}}'
        );

        $this->dropTable('{{%spare_passport_items}}');
    }
}
