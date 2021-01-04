<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%quv_instructions}}`.
 */
class m191126_120656_add_is_close_column_toquv_instructions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_instructions','is_closed', $this->smallInteger(2)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_instructions','is_closed');
    }
}
