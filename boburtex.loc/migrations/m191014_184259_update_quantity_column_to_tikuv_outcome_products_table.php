<?php

use yii\db\Migration;

/**
 * Class m191014_184259_update_quantity_column_to_tikuv_outcome_products_table
 */
class m191014_184259_update_quantity_column_to_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tikuv_outcome_products','quantity', $this->decimal(20,3));
        $this->addColumn('top_accepted','doc_number',$this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('tikuv_outcome_products','quantity', $this->integer());
        $this->dropColumn('top_accepted','doc_number');
    }
}
