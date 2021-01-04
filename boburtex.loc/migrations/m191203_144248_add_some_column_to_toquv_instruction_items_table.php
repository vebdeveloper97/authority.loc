<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_instruction_items}}`.
 */
class m191203_144248_add_some_column_to_toquv_instruction_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_instruction_items','musteri_id', $this->integer());
        $this->addColumn('toquv_instruction_items', 'lot', $this->string(30));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_instruction_items','musteri_id');
        $this->dropColumn('toquv_instruction_items', 'lot');
    }
}
