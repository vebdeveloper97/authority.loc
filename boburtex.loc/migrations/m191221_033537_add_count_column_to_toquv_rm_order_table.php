<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_rm_order}}`.
 */
class m191221_033537_add_count_column_to_toquv_rm_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_rm_order}}', 'count', $this->double()->defaultValue(0));
        $this->addColumn('{{%toquv_item_balance}}', 'quantity', $this->double()->defaultValue(0));
        $this->addColumn('{{%toquv_item_balance_arxiv}}', 'quantity', $this->double()->defaultValue(0));
        $this->addColumn('{{%toquv_document_items}}', 'count', $this->double()->defaultValue(0));
        $this->addColumn('{{%roll_info}}', 'type', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_rm_order}}', 'count');
        $this->dropColumn('{{%toquv_item_balance}}', 'quantity');
        $this->dropColumn('{{%toquv_item_balance_arxiv}}', 'quantity');
        $this->dropColumn('{{%toquv_document_items}}', 'count');
        $this->dropColumn('{{%roll_info}}', 'type');
    }
}
