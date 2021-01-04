<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_instruction_rm}}`.
 */
class m191119_061214_add_is_service_column_to_toquv_instruction_rm_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_instruction_rm','is_service', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_instruction_rm','is_service');
    }
}
