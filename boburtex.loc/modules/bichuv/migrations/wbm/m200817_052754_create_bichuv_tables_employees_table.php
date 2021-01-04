<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_tables_employees}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_tables}}`
 * - `{{%hr_employee}}`
 */
class m200817_052754_create_bichuv_tables_employees_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_tables_employees}}', [
            'id' => $this->primaryKey(),
            'bichuv_table_id' => $this->integer(),
            'hr_employee_id' => $this->integer(),
            'from_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11),
            'updated_by' => $this->integer(11)
        ]);

        // creates index for column `bichuv_table_id`
        $this->createIndex(
            '{{%idx-bichuv_tables_employees-bichuv_table_id}}',
            '{{%bichuv_tables_employees}}',
            'bichuv_table_id'
        );

        // add foreign key for table `{{%bichuv_tables}}`
        $this->addForeignKey(
            '{{%fk-bichuv_tables_employees-bichuv_table_id}}',
            '{{%bichuv_tables_employees}}',
            'bichuv_table_id',
            '{{%bichuv_tables}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-bichuv_tables_employees-hr_employee_id}}',
            '{{%bichuv_tables_employees}}',
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-bichuv_tables_employees-hr_employee_id}}',
            '{{%bichuv_tables_employees}}',
            'hr_employee_id',
            '{{%hr_employee}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_tables}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_tables_employees-bichuv_table_id}}',
            '{{%bichuv_tables_employees}}'
        );

        // drops index for column `bichuv_table_id`
        $this->dropIndex(
            '{{%idx-bichuv_tables_employees-bichuv_table_id}}',
            '{{%bichuv_tables_employees}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_tables_employees-hr_employee_id}}',
            '{{%bichuv_tables_employees}}'
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-bichuv_tables_employees-hr_employee_id}}',
            '{{%bichuv_tables_employees}}'
        );

        $this->dropTable('{{%bichuv_tables_employees}}');
    }
}
