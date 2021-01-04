<?php

use yii\db\Migration;

/**
 * Class m191118_132527_add_toquv_instruction_rm_id_to_toquv_kalite_table
 */
class m191118_132527_add_toquv_instruction_rm_id_to_toquv_kalite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_kalite}}', 'toquv_instruction_rm_id', $this->integer()->after('toquv_instructions_id'));

        // creates index for column `toquv_instruction_rm_id`
        $this->createIndex(
            '{{%idx-toquv_kalite-toquv_instruction_rm_id}}',
            '{{%toquv_kalite}}',
            'toquv_instruction_rm_id'
        );

        // add foreign key for table `{{%toquv_instruction_rm_id}}`
        $this->addForeignKey(
            '{{%fk-toquv_kalite-toquv_instruction_rm_id}}',
            '{{%toquv_kalite}}',
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
            '{{%fk-toquv_kalite-toquv_instruction_rm_id}}',
            '{{%toquv_kalite}}'
        );

        // drops index for column `toquv_instruction_rm_id`
        $this->dropIndex(
            '{{%idx-toquv_kalite-toquv_instruction_rm_id}}',
            '{{%toquv_kalite}}'
        );

        $this->dropColumn('{{%toquv_kalite}}', 'toquv_instruction_rm_id');
    }
}
