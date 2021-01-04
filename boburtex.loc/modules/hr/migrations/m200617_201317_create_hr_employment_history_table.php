<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_employment_history}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 * - `{{%hr_position}}`
 * - `{{%hr_departments}}`
 * - `{{%hr_departments}}`
 */
class m200617_201317_create_hr_employment_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_employment_history}}', [
            'id' => $this->primaryKey(),
            'employee_id' => $this->integer(),
            'position_id' => $this->integer(),
            'from_department' => $this->integer(),
            'to_department' => $this->integer(),
            'reg_date' => $this->datetime(),
            'end_date' => $this->datetime(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->bigInteger(),
        ]);

        // creates index for column `employee_id`
        $this->createIndex(
            '{{%idx-hr_employment_history-employee_id}}',
            '{{%hr_employment_history}}',
            'employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-hr_employment_history-employee_id}}',
            '{{%hr_employment_history}}',
            'employee_id',
            '{{%hr_employee}}',
            'id'
        );

        // creates index for column `position_id`
        $this->createIndex(
            '{{%idx-hr_employment_history-position_id}}',
            '{{%hr_employment_history}}',
            'position_id'
        );

        // add foreign key for table `{{%hr_position}}`
        $this->addForeignKey(
            '{{%fk-hr_employment_history-position_id}}',
            '{{%hr_employment_history}}',
            'position_id',
            '{{%hr_position}}',
            'id'
        );

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-hr_employment_history-from_department}}',
            '{{%hr_employment_history}}',
            'from_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_employment_history-from_department}}',
            '{{%hr_employment_history}}',
            'from_department',
            '{{%hr_departments}}',
            'id'
        );

        // creates index for column `to_department`
        $this->createIndex(
            '{{%idx-hr_employment_history-to_department}}',
            '{{%hr_employment_history}}',
            'to_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_employment_history-to_department}}',
            '{{%hr_employment_history}}',
            'to_department',
            '{{%hr_departments}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-hr_employment_history-employee_id}}',
            '{{%hr_employment_history}}'
        );

        // drops index for column `employee_id`
        $this->dropIndex(
            '{{%idx-hr_employment_history-employee_id}}',
            '{{%hr_employment_history}}'
        );

        // drops foreign key for table `{{%hr_position}}`
        $this->dropForeignKey(
            '{{%fk-hr_employment_history-position_id}}',
            '{{%hr_employment_history}}'
        );

        // drops index for column `position_id`
        $this->dropIndex(
            '{{%idx-hr_employment_history-position_id}}',
            '{{%hr_employment_history}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-hr_employment_history-from_department}}',
            '{{%hr_employment_history}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-hr_employment_history-from_department}}',
            '{{%hr_employment_history}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-hr_employment_history-to_department}}',
            '{{%hr_employment_history}}'
        );

        // drops index for column `to_department`
        $this->dropIndex(
            '{{%idx-hr_employment_history-to_department}}',
            '{{%hr_employment_history}}'
        );

        $this->dropTable('{{%hr_employment_history}}');
    }
}
