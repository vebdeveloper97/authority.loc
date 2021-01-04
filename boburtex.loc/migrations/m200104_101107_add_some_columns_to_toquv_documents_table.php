<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_documents}}`.
 */
class m200104_101107_add_some_columns_to_toquv_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_document_items}}', 'add_info', $this->text());
        $this->addColumn('{{%toquv_documents}}', 'to_musteri', $this->integer()->after('to_employee'));
        $this->addColumn('{{%toquv_documents}}', 'from_musteri', $this->integer()->after('to_employee'));
        $this->addColumn('{{%toquv_item_balance}}', 'to_musteri', $this->integer()->after('from_department'));
        $this->addColumn('{{%toquv_item_balance_arxiv}}', 'to_musteri', $this->integer()->after('from_department'));
        $this->addColumn('{{%toquv_item_balance}}', 'from_musteri', $this->integer()->after('from_department'));
        $this->addColumn('{{%toquv_item_balance_arxiv}}', 'from_musteri', $this->integer()->after('from_department'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_document_items}}', 'add_info');
        $this->dropColumn('{{%toquv_documents}}', 'to_musteri');
        $this->dropColumn('{{%toquv_documents}}', 'from_musteri');
        $this->dropColumn('{{%toquv_item_balance}}', 'to_musteri');
        $this->dropColumn('{{%toquv_item_balance_arxiv}}', 'to_musteri');
        $this->dropColumn('{{%toquv_item_balance}}', 'from_musteri');
        $this->dropColumn('{{%toquv_item_balance_arxiv}}', 'from_musteri');
    }
}
