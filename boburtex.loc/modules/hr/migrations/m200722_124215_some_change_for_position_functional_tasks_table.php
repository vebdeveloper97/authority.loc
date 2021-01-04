<?php

use yii\db\Migration;

/**
 * Class m200722_124215_some_change_for_position_functional_tasks_table
 */
class m200722_124215_some_change_for_position_functional_tasks_table extends Migration
{
    const TABLE_NAME = '{{%position_functional_tasks}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
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

        $this->dropColumn(self::TABLE_NAME, 'position_id');

        $this->addColumn(self::TABLE_NAME, 'name', $this->string()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(self::TABLE_NAME, 'position_id', $this->string()->unique());

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
            'id'
        );

        $this->dropColumn(self::TABLE_NAME, 'name');
    }
}
