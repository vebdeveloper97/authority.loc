<?php

use yii\db\Migration;

/**
 * Class m191118_123335_add_tir_id_to_toquv_makine_processes_table
 */
class m191118_123335_add_toquv_instruction_rm_id_to_toquv_makine_processes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_makine_processes}}', 'toquv_instruction_rm_id', $this->integer()->after('ti_id'));

        // creates index for column `toquv_instruction_rm_id`
        $this->createIndex(
            '{{%idx-toquv_makine_processes-toquv_instruction_rm_id}}',
            '{{%toquv_makine_processes}}',
            'toquv_instruction_rm_id'
        );

        // add foreign key for table `{{%toquv_instruction_rm_id}}`
        $this->addForeignKey(
            '{{%fk-toquv_makine_processes-toquv_instruction_rm_id}}',
            '{{%toquv_makine_processes}}',
            'toquv_instruction_rm_id',
            '{{%toquv_instruction_rm}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_instruction_rm_id}}`
        $this->dropForeignKey(
            '{{%fk-toquv_makine_processes-toquv_instruction_rm_id}}',
            '{{%toquv_makine_processes}}'
        );

        // drops index for column `toquv_instruction_rm_id`
        $this->dropIndex(
            '{{%idx-toquv_makine_processes-toquv_instruction_rm_id}}',
            '{{%toquv_makine_processes}}'
        );

        $this->dropColumn('{{%toquv_makine_processes}}', 'toquv_instruction_rm_id');
    }
}
