<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_instruction_rm}}`.
 */
class m190927_105952_add_column_toquv_instruction_id_to_toquv_instruction_rm_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_instruction_rm','toquv_instruction_id', $this->integer());

        // creates index for column `toquv_instruction_id`
        $this->createIndex(
            '{{%idx-toquv_instruction_rm-toquv_instruction_id}}',
            '{{%toquv_instruction_rm}}',
            'toquv_instruction_id'
        );

        // add foreign key for table `{{%toquv_instruction_rm}}`
        $this->addForeignKey(
            '{{%fk-toquv_instruction_rm-toquv_instruction_id}}',
            '{{%toquv_instruction_rm}}',
            'toquv_instruction_id',
            '{{%toquv_instructions}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_instruction_rm}}`
        $this->dropForeignKey(
            '{{%fk-toquv_instruction_rm-toquv_instruction_id}}',
            '{{%toquv_instruction_rm}}'
        );

        // drops index for column `toquv_instruction_id`
        $this->dropIndex(
            '{{%idx-toquv_instruction_rm-toquv_instruction_id}}',
            '{{%toquv_instruction_rm}}'
        );

        $this->dropColumn('toquv_instruction_rm','toquv_instruction_id');
    }
}
