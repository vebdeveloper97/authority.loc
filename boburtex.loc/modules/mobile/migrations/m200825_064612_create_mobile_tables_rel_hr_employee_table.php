<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mobile_tables_rel_hr_employee}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_tables}}`
 * - `{{%hr_employee}}`
 */
class m200825_064612_create_mobile_tables_rel_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mobile_tables_rel_hr_employee}}', [
            'id' => $this->primaryKey(),
            'mobile_tables_id' => $this->integer(),
            'hr_employee_id' => $this->integer(),
            'start_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `mobile_tables_id`
        $this->createIndex(
            '{{%idx-mobile_tables_rel_hr_employee-mobile_tables_id}}',
            '{{%mobile_tables_rel_hr_employee}}',
            'mobile_tables_id'
        );

        // add foreign key for table `{{%mobile_tables}}`
        $this->addForeignKey(
            '{{%fk-mobile_tables_rel_hr_employee-mobile_tables_id}}',
            '{{%mobile_tables_rel_hr_employee}}',
            'mobile_tables_id',
            '{{%mobile_tables}}',
            'id'
        );

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-mobile_tables_rel_hr_employee-hr_employee_id}}',
            '{{%mobile_tables_rel_hr_employee}}',
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-mobile_tables_rel_hr_employee-hr_employee_id}}',
            '{{%mobile_tables_rel_hr_employee}}',
            'hr_employee_id',
            '{{%hr_employee}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_tables}}`
        $this->dropForeignKey(
            '{{%fk-mobile_tables_rel_hr_employee-mobile_tables_id}}',
            '{{%mobile_tables_rel_hr_employee}}'
        );

        // drops index for column `mobile_tables_id`
        $this->dropIndex(
            '{{%idx-mobile_tables_rel_hr_employee-mobile_tables_id}}',
            '{{%mobile_tables_rel_hr_employee}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-mobile_tables_rel_hr_employee-hr_employee_id}}',
            '{{%mobile_tables_rel_hr_employee}}'
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-mobile_tables_rel_hr_employee-hr_employee_id}}',
            '{{%mobile_tables_rel_hr_employee}}'
        );

        $this->dropTable('{{%mobile_tables_rel_hr_employee}}');
    }
}
