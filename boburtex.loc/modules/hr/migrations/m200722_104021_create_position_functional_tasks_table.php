<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%position_functional_tasks}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_position}}`
 */
class m200722_104021_create_position_functional_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%position_functional_tasks}}', [
            'id' => $this->primaryKey(),
            'position_id' => $this->integer(),
            'tasks' => $this->text(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `position_id`
        $this->createIndex(
            '{{%idx-position_functional_tasks-position_id}}',
            '{{%position_functional_tasks}}',
            'position_id'
        );

        // add foreign key for table `{{%hr_position}}`
        $this->addForeignKey(
            '{{%fk-position_functional_tasks-position_id}}',
            '{{%position_functional_tasks}}',
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
        // drops foreign key for table `{{%hr_position}}`
        $this->dropForeignKey(
            '{{%fk-position_functional_tasks-position_id}}',
            '{{%position_functional_tasks}}'
        );

        // drops index for column `position_id`
        $this->dropIndex(
            '{{%idx-position_functional_tasks-position_id}}',
            '{{%position_functional_tasks}}'
        );

        $this->dropTable('{{%position_functional_tasks}}');
    }
}
