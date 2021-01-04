<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_inspection}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%spare_passport_items}}`
 */
class m200911_112306_create_spare_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_inspection}}', [
            'id' => $this->primaryKey(),
            'sirhe_id' => $this->integer(),
            'spare_passport_item_id' => $this->integer(),
            'control_type' => $this->integer(),
            'reg_date' => $this->dateTime()->defaultValue(date('Y-m-d H:i:s')),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `spare_passport_item_id`
        $this->createIndex(
            '{{%idx-spare_inspection-spare_passport_item_id}}',
            '{{%spare_inspection}}',
            'spare_passport_item_id'
        );

        // add foreign key for table `{{%spare_passport_items}}`
        $this->addForeignKey(
            '{{%fk-spare_inspection-spare_passport_item_id}}',
            '{{%spare_inspection}}',
            'spare_passport_item_id',
            '{{%spare_passport_items}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `sirhe_id`
        $this->createIndex(
            '{{%idx-spare_inspection-sirhe_id}}',
            '{{%spare_inspection}}',
            'sirhe_id'
        );

        // add foreign key for table `{{%spare_item_rel_hr_employee}}`
        $this->addForeignKey(
            '{{%fk-spare_inspection-sirhe_id}}',
            '{{%spare_inspection}}',
            'sirhe_id',
            '{{%spare_item_rel_hr_employee}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%spare_passport_items}}`
        $this->dropForeignKey(
            '{{%fk-spare_inspection-spare_passport_item_id}}',
            '{{%spare_inspection}}'
        );

        // drops index for column `spare_passport_item_id`
        $this->dropIndex(
            '{{%idx-spare_inspection-spare_passport_item_id}}',
            '{{%spare_inspection}}'
        );

        $this->dropTable('{{%spare_inspection}}');
    }
}
