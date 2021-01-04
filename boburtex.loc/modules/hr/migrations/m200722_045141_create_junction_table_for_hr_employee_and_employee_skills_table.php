<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_rel_skills}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 * - `{{%employee_skills}}`
 */
class m200722_045141_create_junction_table_for_hr_employee_and_employee_skills_table extends Migration
{
    const TABLE_NAME = '{{%employee_rel_skills}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'hr_employee_id' => $this->integer(),
            'employee_skills_id' => $this->integer(),
            'rate' => $this->float(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
            'PRIMARY KEY(hr_employee_id, employee_skills_id)',
        ]);

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-employee_rel_skills-hr_employee_id}}',
            self::TABLE_NAME,
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-employee_rel_skills-hr_employee_id}}',
            self::TABLE_NAME,
            'hr_employee_id',
            '{{%hr_employee}}',
            'id'
        );

        // creates index for column `employee_skills_id`
        $this->createIndex(
            '{{%idx-employee_rel_skills-employee_skills_id}}',
            self::TABLE_NAME,
            'employee_skills_id'
        );

        // add foreign key for table `{{%employee_skills}}`
        $this->addForeignKey(
            '{{%fk-employee_rel_skills-employee_skills_id}}',
            self::TABLE_NAME,
            'employee_skills_id',
            '{{%employee_skills}}',
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
            '{{%fk-employee_rel_skills-hr_employee_id}}',
            self::TABLE_NAME
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-employee_rel_skills-hr_employee_id}}',
            self::TABLE_NAME
        );

        // drops foreign key for table `{{%employee_skills}}`
        $this->dropForeignKey(
            '{{%fk-employee_rel_skills-employee_skills_id}}',
            self::TABLE_NAME
        );

        // drops index for column `employee_skills_id`
        $this->dropIndex(
            '{{%idx-employee_rel_skills-employee_skills_id}}',
            self::TABLE_NAME
        );

        $this->dropTable(self::TABLE_NAME);
    }
}
