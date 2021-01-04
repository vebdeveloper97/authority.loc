<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_item_doc_items}}`.
 */
class m200714_085442_add_summa_usd_column_to_spare_item_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_item_doc_items}}', 'summa_usd', $this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%spare_item_doc_items}}', 'summa_usd');
    }
}
