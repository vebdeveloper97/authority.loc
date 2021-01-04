<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_instruction_items}}`.
 */
class m200402_074421_add_some_column_to_toquv_instruction_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_instruction_items}}', 'percentage', $this->double());
        $this->addColumn('{{%toquv_instruction_items}}', 'toquv_ne', $this->string(40));
        $this->addColumn('{{%toquv_instruction_items}}', 'toquv_thread', $this->string(40));
        $this->addColumn('{{%toquv_instruction_items}}', 'toquv_ip_color', $this->string(40));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_instruction_items}}', 'percentage');
        $this->dropColumn('{{%toquv_instruction_items}}', 'toquv_ne');
        $this->dropColumn('{{%toquv_instruction_items}}', 'toquv_thread');
        $this->dropColumn('{{%toquv_instruction_items}}', 'toquv_ip_color');
    }
}
