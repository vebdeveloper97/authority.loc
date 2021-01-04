<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%mobile_process_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 */
class m200825_110710_drop_started_employee_id_column_from_mobile_process_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-mobile_process_production-started_employee_id}}',
            '{{%mobile_process_production}}'
        );

        // drops index for column `started_employee_id`
        $this->dropIndex(
            '{{%idx-mobile_process_production-started_employee_id}}',
            '{{%mobile_process_production}}'
        );

        $this->dropColumn('{{%mobile_process_production}}', 'started_employee_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%mobile_process_production}}', 'started_employee_id', $this->integer());

        // creates index for column `started_employee_id`
        $this->createIndex(
            '{{%idx-mobile_process_production-started_employee_id}}',
            '{{%mobile_process_production}}',
            'started_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-mobile_process_production-started_employee_id}}',
            '{{%mobile_process_production}}',
            'started_employee_id',
            '{{%hr_employee}}',
            'id'
        );
    }
}
