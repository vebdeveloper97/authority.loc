<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_orders}} and {{%toquv_instructions}}`.
 */
class m191031_181150_add_some_columns_to_toquv_orders_and_toquv_instructions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_orders}}', 'model_orders_id', $this->integer());
        $this->addColumn('{{%toquv_instructions}}', 'model_orders_id', $this->integer());
        $this->addColumn('{{%toquv_instruction_rm}}', 'moi_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_orders}}', 'model_orders_id');
        $this->dropColumn('{{%toquv_instructions}}', 'model_orders_id');
        $this->dropColumn('{{%toquv_instruction_rm}}', 'moi_id');
    }
}
