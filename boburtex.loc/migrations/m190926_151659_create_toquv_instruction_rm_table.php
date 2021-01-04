<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_instruction_rm}}`.
 */
class m190926_151659_create_toquv_instruction_rm_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_instruction_rm}}', [
            'id' => $this->primaryKey(),
            'toquv_rm_order_id' => $this->integer(),
            'toquv_pus_fine_id' => $this->integer(),
            'thread_length' => $this->integer(),
            'finish_en' => $this->integer(),
            'finish_gramaj' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        /*  toquv_rm_order_id */
        $this->createIndex(
            '{{%idx-toquv_instruction_rm-toquv_rm_order_id}}',
            '{{%toquv_instruction_rm}}',
            'toquv_rm_order_id'
        );

        $this->addForeignKey(
            '{{%fk-toquv_instruction_rm-toquv_rm_order_id}}',
            '{{%toquv_instruction_rm}}',
            'toquv_rm_order_id',
            '{{%toquv_rm_order}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        /*   pus_fine_id  */
        $this->createIndex(
            '{{%idx-toquv_instruction_rm-toquv_pus_fine_id}}',
            '{{%toquv_instruction_rm}}',
            'toquv_pus_fine_id'
        );

        $this->addForeignKey(
            '{{%fk-toquv_instruction_rm-toquv_pus_fine_id}}',
            '{{%toquv_instruction_rm}}',
            'toquv_pus_fine_id',
            '{{%toquv_pus_fine}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-toquv_instruction_rm-toquv_rm_order_id}}',
            '{{%toquv_instruction_rm}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-toquv_instruction_rm-toquv_rm_order_id}}',
            '{{%toquv_instruction_rm}}'
        );

        $this->dropForeignKey(
            '{{%fk-toquv_instruction_rm-toquv_rm_order_id}}',
            '{{%toquv_instruction_rm}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-toquv_instruction_rm-toquv_rm_order_id}}',
            '{{%toquv_instruction_rm}}'
        );

        $this->dropTable('{{%toquv_instruction_rm}}');
    }
}
