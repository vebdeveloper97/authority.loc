<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_staff}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%hr_position}}`
 */
class m200618_071359_create_hr_staff_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_staff}}', [
            'id' => $this->primaryKey(),
            'department_id' => $this->integer(),
            'position_id' => $this->integer(),
            'quantity' => $this->integer(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-hr_staff-department_id}}',
            '{{%hr_staff}}',
            'department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_staff-department_id}}',
            '{{%hr_staff}}',
            'department_id',
            '{{%hr_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `position_id`
        $this->createIndex(
            '{{%idx-hr_staff-position_id}}',
            '{{%hr_staff}}',
            'position_id'
        );

        // add foreign key for table `{{%hr_position}}`
        $this->addForeignKey(
            '{{%fk-hr_staff-position_id}}',
            '{{%hr_staff}}',
            'position_id',
            '{{%hr_position}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-hr_staff-department_id}}',
            '{{%hr_staff}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-hr_staff-department_id}}',
            '{{%hr_staff}}'
        );

        // drops foreign key for table `{{%hr_position}}`
        $this->dropForeignKey(
            '{{%fk-hr_staff-position_id}}',
            '{{%hr_staff}}'
        );

        // drops index for column `position_id`
        $this->dropIndex(
            '{{%idx-hr_staff-position_id}}',
            '{{%hr_staff}}'
        );

        $this->dropTable('{{%hr_staff}}');
    }
}
