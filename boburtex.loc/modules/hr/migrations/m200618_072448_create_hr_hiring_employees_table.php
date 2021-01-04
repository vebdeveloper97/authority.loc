<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_hiring_employees}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 * - `{{%hr_staff}}`
 */
class m200618_072448_create_hr_hiring_employees_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_hiring_employees}}', [
            'id' => $this->primaryKey(),
            'employee_id' => $this->integer(),
            'staff_id' => $this->integer(),
            'reg_date' => $this->datetime(),
            'end_date' => $this->datetime(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `employee_id`
        $this->createIndex(
            '{{%idx-hr_hiring_employees-employee_id}}',
            '{{%hr_hiring_employees}}',
            'employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-hr_hiring_employees-employee_id}}',
            '{{%hr_hiring_employees}}',
            'employee_id',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );

        // creates index for column `staff_id`
        $this->createIndex(
            '{{%idx-hr_hiring_employees-staff_id}}',
            '{{%hr_hiring_employees}}',
            'staff_id'
        );

        // add foreign key for table `{{%hr_staff}}`
        $this->addForeignKey(
            '{{%fk-hr_hiring_employees-staff_id}}',
            '{{%hr_hiring_employees}}',
            'staff_id',
            '{{%hr_staff}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-hr_hiring_employees-employee_id}}',
            '{{%hr_hiring_employees}}'
        );

        // drops index for column `employee_id`
        $this->dropIndex(
            '{{%idx-hr_hiring_employees-employee_id}}',
            '{{%hr_hiring_employees}}'
        );

        // drops foreign key for table `{{%hr_staff}}`
        $this->dropForeignKey(
            '{{%fk-hr_hiring_employees-staff_id}}',
            '{{%hr_hiring_employees}}'
        );

        // drops index for column `staff_id`
        $this->dropIndex(
            '{{%idx-hr_hiring_employees-staff_id}}',
            '{{%hr_hiring_employees}}'
        );

        $this->dropTable('{{%hr_hiring_employees}}');
    }
}
