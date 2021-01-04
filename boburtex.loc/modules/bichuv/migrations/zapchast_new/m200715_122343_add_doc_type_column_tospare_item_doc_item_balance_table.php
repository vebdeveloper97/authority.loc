<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_item_doc_item_balance}}`.
 */
class m200715_122343_add_doc_type_column_tospare_item_doc_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_item_doc_item_balance}}', 'doc_type', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%spare_item_doc_item_balance}}', 'doc_type');
    }
}
