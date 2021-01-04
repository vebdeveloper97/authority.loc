<?php

use yii\db\Migration;

/**
 * Class m191214_073939_add_first_weight_column_bichuv_sub_doc_items_table
 */
class m191214_073939_add_first_weight_column_bichuv_sub_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_sub_doc_items','first_weight', $this->decimal(10,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_sub_doc_items','first_weight');
    }
}
