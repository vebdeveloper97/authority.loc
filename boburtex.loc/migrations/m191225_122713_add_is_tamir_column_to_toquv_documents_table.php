<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_documents}}`.
 */
class m191225_122713_add_is_tamir_column_to_toquv_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_documents}}','is_tamir',$this->smallInteger()->defaultValue(0));
        $this->addColumn('{{%toquv_item_balance_arxiv}}', 'quantity_inventory', $this->double()->defaultValue(0));
        $this->addColumn('{{%toquv_item_balance_arxiv}}', 'parent_id', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_documents}}','is_tamir');
        $this->dropColumn('{{%toquv_item_balance_arxiv}}', 'quantity_inventory');
        $this->dropColumn('{{%toquv_item_balance_arxiv}}', 'parent_id');
    }
}
