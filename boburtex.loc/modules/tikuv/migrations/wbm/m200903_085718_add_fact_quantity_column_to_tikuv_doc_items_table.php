<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc_items}}`.
 */
class m200903_085718_add_fact_quantity_column_to_tikuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_doc_items}}', 'fact_quantity', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tikuv_doc_items}}', 'fact_quantity');
    }
}
