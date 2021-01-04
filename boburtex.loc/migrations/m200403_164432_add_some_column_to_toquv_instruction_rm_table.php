<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_instruction_rm}}`.
 */
class m200403_164432_add_some_column_to_toquv_instruction_rm_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_instruction_rm}}', 'planed_date', $this->dateTime());
        $this->addColumn('{{%toquv_instruction_rm}}', 'finished_date', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_instruction_rm}}', 'planed_date');
        $this->dropColumn('{{%toquv_instruction_rm}}', 'finished_date');
    }
}
