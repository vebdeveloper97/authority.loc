<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_makine_processes}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%ended_by}}`
 */
class m191002_093717_add_some_column_to_toquv_makine_processes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_makine_processes}}', 'ended_by', $this->bigInteger()->after('created_by'));

        // creates index for column `ended_by`
        $this->createIndex(
            '{{%idx-toquv_makine_processes-ended_by}}',
            '{{%toquv_makine_processes}}',
            'ended_by'
        );

        // add foreign key for table `{{%ended_by}}`
        $this->addForeignKey(
            '{{%fk-toquv_makine_processes-ended_by}}',
            '{{%toquv_makine_processes}}',
            'ended_by',
            '{{%users}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%ended_by}}`
        $this->dropForeignKey(
            '{{%fk-toquv_makine_processes-ended_by}}',
            '{{%toquv_makine_processes}}'
        );

        // drops index for column `ended_by`
        $this->dropIndex(
            '{{%idx-toquv_makine_processes-ended_by}}',
            '{{%toquv_makine_processes}}'
        );

        $this->dropColumn('{{%toquv_makine_processes}}', 'ended_by');
    }
}
