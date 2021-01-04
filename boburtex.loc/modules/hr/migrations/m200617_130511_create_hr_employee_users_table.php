<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_employee_users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%hr_employee}}`
 */
class m200617_130511_create_hr_employee_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_employee_users}}', [
            'id' => $this->primaryKey(),
            'users_id' => $this->bigInteger(),
            'hr_employee_id' => $this->integer(),
            'status' => $this->smallInteger(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-hr_employee_users-users_id}}',
            '{{%hr_employee_users}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-hr_employee_users-users_id}}',
            '{{%hr_employee_users}}',
            'users_id',
            '{{%users}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-hr_employee_users-hr_employee_id}}',
            '{{%hr_employee_users}}',
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-hr_employee_users-hr_employee_id}}',
            '{{%hr_employee_users}}',
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
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-hr_employee_users-users_id}}',
            '{{%hr_employee_users}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-hr_employee_users-users_id}}',
            '{{%hr_employee_users}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-hr_employee_users-hr_employee_id}}',
            '{{%hr_employee_users}}'
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-hr_employee_users-hr_employee_id}}',
            '{{%hr_employee_users}}'
        );

        $this->dropTable('{{%hr_employee_users}}');
    }
}
