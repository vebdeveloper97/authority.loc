<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_item_balance}}`.
 */
class m191226_173834_add_from_department_column_to_toquv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_item_balance}}', 'from_department', $this->integer()->after('to_department'));
        $this->addColumn('{{%toquv_item_balance_arxiv}}', 'from_department', $this->integer()->after('to_department'));
        //from_department
        $this->createIndex(
            'idx-toquv_item_balance-from_department',
            'toquv_item_balance',
            'from_department'
        );

        $this->addForeignKey(
            'fk-toquv_item_balance-from_department',
            'toquv_item_balance',
            'from_department',
            'toquv_departments',
            'id'
        );
        //from_department
        $this->createIndex(
            'idx-toquv_item_balance_arxiv-from_department',
            'toquv_item_balance_arxiv',
            'from_department'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //from_department
        $this->dropIndex(
            'idx-toquv_item_balance_arxiv-from_department',
            'toquv_item_balance_arxiv'
        );
        //from_department
        $this->dropForeignKey(
            'fk-toquv_item_balance-from_department',
            'toquv_item_balance'
        );

        $this->dropIndex(
            'idx-toquv_item_balance-from_department',
            'toquv_item_balance'
        );
        $this->dropColumn('{{%toquv_item_balance}}', 'from_department');
        $this->dropColumn('{{%toquv_item_balance_arxiv}}', 'from_department');
    }
}
