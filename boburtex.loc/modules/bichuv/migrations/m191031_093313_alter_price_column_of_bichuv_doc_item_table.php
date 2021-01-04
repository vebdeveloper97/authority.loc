<?php

use yii\db\Migration;

/**
 * Class m191031_093313_alter_price_column_of_bichuv_doc_item_table
 */
class m191031_093313_alter_price_column_of_bichuv_doc_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('bichuv_doc_items', 'price_sum', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_doc_items', 'price_usd', $this->decimal(20,3)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('bichuv_doc_items', 'price_sum', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_doc_items', 'price_usd', $this->decimal(20,2)->defaultValue(0));
    }

}
