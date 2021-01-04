<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_instruction_rm}}`.
 */
class m191123_084041_add_is_closed_column_to_toquv_instruction_rm_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_instruction_rm','is_closed', $this->smallInteger(2)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_instruction_rm','is_closed');
    }
}
