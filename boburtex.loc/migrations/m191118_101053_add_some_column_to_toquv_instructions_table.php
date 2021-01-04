<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_instructions}}`.
 */
class m191118_101053_add_some_column_to_toquv_instructions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_instructions', 'is_service', $this->smallInteger()->defaultValue(1));
        $this->addColumn('toquv_instruction_rm','quantity', $this->decimal(20,3)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_instructions','is_service');
        $this->dropColumn('toquv_instruction_rm','quantity');
    }
}
