<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_addition_task_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_addition_task}}`
 */
class m200723_063135_create_hr_addition_task_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_addition_task_items}}', [
            'id' => $this->primaryKey(),
            'hr_addition_task_id' => $this->integer(),
            'task' => $this->text(),
            'rate' => $this->integer(3)->defaultValue(0),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'expire_date' => $this->dateTime(),
            'remember_date' => $this->dateTime(),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `hr_addition_task_id`
        $this->createIndex(
            '{{%idx-hr_addition_task_items-hr_addition_task_id}}',
            '{{%hr_addition_task_items}}',
            'hr_addition_task_id'
        );

        // add foreign key for table `{{%hr_addition_task}}`
        $this->addForeignKey(
            '{{%fk-hr_addition_task_items-hr_addition_task_id}}',
            '{{%hr_addition_task_items}}',
            'hr_addition_task_id',
            '{{%hr_addition_task_to_employees}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_addition_task}}`
        $this->dropForeignKey(
            '{{%fk-hr_addition_task_items-hr_addition_task_id}}',
            '{{%hr_addition_task_items}}'
        );

        // drops index for column `hr_addition_task_id`
        $this->dropIndex(
            '{{%idx-hr_addition_task_items-hr_addition_task_id}}',
            '{{%hr_addition_task_items}}'
        );

        $this->dropTable('{{%hr_addition_task_items}}');
    }
}
