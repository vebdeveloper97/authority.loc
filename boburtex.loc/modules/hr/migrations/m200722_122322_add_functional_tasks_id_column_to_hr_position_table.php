<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_position}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%position_functional_tasks}}`
 */
class m200722_122322_add_functional_tasks_id_column_to_hr_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_position}}', 'functional_tasks_id', $this->integer());

        // creates index for column `functional_tasks_id`
        $this->createIndex(
            '{{%idx-hr_position-functional_tasks_id}}',
            '{{%hr_position}}',
            'functional_tasks_id'
        );

        // add foreign key for table `{{%position_functional_tasks}}`
        $this->addForeignKey(
            '{{%fk-hr_position-functional_tasks_id}}',
            '{{%hr_position}}',
            'functional_tasks_id',
            '{{%position_functional_tasks}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%position_functional_tasks}}`
        $this->dropForeignKey(
            '{{%fk-hr_position-functional_tasks_id}}',
            '{{%hr_position}}'
        );

        // drops index for column `functional_tasks_id`
        $this->dropIndex(
            '{{%idx-hr_position-functional_tasks_id}}',
            '{{%hr_position}}'
        );

        $this->dropColumn('{{%hr_position}}', 'functional_tasks_id');
    }
}
