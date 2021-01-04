<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_tables_users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 */
class m200815_115635_add_hr_employee_id_column_to_bichuv_tables_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_tables_users}}', 'hr_employee_id', $this->integer());

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-bichuv_tables_users-hr_employee_id}}',
            '{{%bichuv_tables_users}}',
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-bichuv_tables_users-hr_employee_id}}',
            '{{%bichuv_tables_users}}',
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
        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_tables_users-hr_employee_id}}',
            '{{%bichuv_tables_users}}'
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-bichuv_tables_users-hr_employee_id}}',
            '{{%bichuv_tables_users}}'
        );

        $this->dropColumn('{{%bichuv_tables_users}}', 'hr_employee_id');
    }
}
