<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_department_responsible_person}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%hr_employee}}`
 */
class m200813_174900_create_hr_department_responsible_person_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_department_responsible_person}}', [
            'id' => $this->primaryKey(),
            'hr_department_id' => $this->integer(),
            'hr_employee_id' => $this->integer(),
            'start_date' => $this->datetime(),
            'end_date' => $this->datetime(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-hr_department_responsible_person-hr_department_id}}',
            '{{%hr_department_responsible_person}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_department_responsible_person-hr_department_id}}',
            '{{%hr_department_responsible_person}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id'
        );

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-hr_department_responsible_person-hr_employee_id}}',
            '{{%hr_department_responsible_person}}',
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-hr_department_responsible_person-hr_employee_id}}',
            '{{%hr_department_responsible_person}}',
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
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-hr_department_responsible_person-hr_department_id}}',
            '{{%hr_department_responsible_person}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-hr_department_responsible_person-hr_department_id}}',
            '{{%hr_department_responsible_person}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-hr_department_responsible_person-hr_employee_id}}',
            '{{%hr_department_responsible_person}}'
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-hr_department_responsible_person-hr_employee_id}}',
            '{{%hr_department_responsible_person}}'
        );

        $this->dropTable('{{%hr_department_responsible_person}}');
    }
}
