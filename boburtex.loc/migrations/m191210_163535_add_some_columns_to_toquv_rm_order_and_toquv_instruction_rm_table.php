<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_rm_order}}` and `{{%toquv_instruction_rm}}`.
 */
class m191210_163535_add_some_columns_to_toquv_rm_order_and_toquv_instruction_rm_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_rm_order}}', 'type_weaving', $this->smallInteger(6)->defaultValue(1));
        $this->addColumn('{{%toquv_instruction_rm}}', 'type_weaving', $this->smallInteger(6)->defaultValue(1));

        $this->alterColumn('{{%toquv_rm_order}}','thread_length', $this->string(30));
        $this->alterColumn('{{%toquv_rm_order}}','finish_en', $this->string(30));
        $this->alterColumn('{{%toquv_rm_order}}','finish_gramaj', $this->string(30));
        $this->alterColumn('{{%toquv_instruction_rm}}','thread_length', $this->string(30));
        $this->alterColumn('{{%toquv_instruction_rm}}','finish_en', $this->string(30));
        $this->alterColumn('{{%toquv_instruction_rm}}','finish_gramaj', $this->string(30));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_rm_order}}', 'type_weaving');
        $this->dropColumn('{{%toquv_instruction_rm}}', 'type_weaving');

        $this->alterColumn('{{%toquv_rm_order}}','thread_length', $this->integer());
        $this->alterColumn('{{%toquv_rm_order}}','finish_en', $this->integer());
        $this->alterColumn('{{%toquv_rm_order}}','finish_gramaj', $this->integer());
        $this->alterColumn('{{%toquv_instruction_rm}}','thread_length', $this->integer());
        $this->alterColumn('{{%toquv_instruction_rm}}','finish_en', $this->integer());
        $this->alterColumn('{{%toquv_instruction_rm}}','finish_gramaj', $this->integer());
    }
}
