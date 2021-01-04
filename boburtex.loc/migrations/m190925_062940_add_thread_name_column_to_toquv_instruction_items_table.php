<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_instruction_items}}`.
 */
class m190925_062940_add_thread_name_column_to_toquv_instruction_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_instruction_items','thread_name',$this->string());
        $this->addColumn('toquv_instruction_items','is_own',$this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_instruction_items','thread_name');
        $this->dropColumn('toquv_instruction_items','is_own');
    }
}
