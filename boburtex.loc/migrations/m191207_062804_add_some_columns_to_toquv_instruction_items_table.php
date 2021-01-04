<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_instruction_items}}`.
 */
class m191207_062804_add_some_columns_to_toquv_instruction_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_instruction_items}}','toquv_instruction_rm_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_instruction_items}}','toquv_instruction_rm_id');
    }
}
