<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_item_doc_items}}`.
 */
class m200714_085306_add_summa_column_to_spare_item_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_item_doc_items}}', 'summa', $this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%spare_item_doc_items}}', 'summa');
    }
}
