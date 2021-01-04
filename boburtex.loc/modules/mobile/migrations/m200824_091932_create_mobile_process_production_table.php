<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mobile_process_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_process}}`
 * - `{{%hr_employee}}`
 * - `{{%hr_employee}}`
 */
class m200824_091932_create_mobile_process_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mobile_process_production}}', [
            'id' => $this->primaryKey(),
            'mobile_process_id' => $this->integer(),
            'nastel_no' => $this->string(),
            'started_date' => $this->dateTime(),
            'ended_date' => $this->dateTime(),
            'started_employee_id' => $this->integer(),
            'ended_employee_id' => $this->integer(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `mobile_process_id`
        $this->createIndex(
            '{{%idx-mobile_process_production-mobile_process_id}}',
            '{{%mobile_process_production}}',
            'mobile_process_id'
        );

        // add foreign key for table `{{%mobile_process}}`
        $this->addForeignKey(
            '{{%fk-mobile_process_production-mobile_process_id}}',
            '{{%mobile_process_production}}',
            'mobile_process_id',
            '{{%mobile_process}}',
            'id'
        );

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

        // creates index for column `ended_employee_id`
        $this->createIndex(
            '{{%idx-mobile_process_production-ended_employee_id}}',
            '{{%mobile_process_production}}',
            'ended_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-mobile_process_production-ended_employee_id}}',
            '{{%mobile_process_production}}',
            'ended_employee_id',
            '{{%hr_employee}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_process}}`
        $this->dropForeignKey(
            '{{%fk-mobile_process_production-mobile_process_id}}',
            '{{%mobile_process_production}}'
        );

        // drops index for column `mobile_process_id`
        $this->dropIndex(
            '{{%idx-mobile_process_production-mobile_process_id}}',
            '{{%mobile_process_production}}'
        );

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

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-mobile_process_production-ended_employee_id}}',
            '{{%mobile_process_production}}'
        );

        // drops index for column `ended_employee_id`
        $this->dropIndex(
            '{{%idx-mobile_process_production-ended_employee_id}}',
            '{{%mobile_process_production}}'
        );

        $this->dropTable('{{%mobile_process_production}}');
    }
}
