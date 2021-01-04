<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 */
class m200115_095736_add_slice_weight_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc','slice_weight', $this->decimal(20,3)->defaultValue(0));
        $this->addColumn('bichuv_doc','total_weight', $this->decimal(20,3)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc','slice_weight');
        $this->dropColumn('bichuv_doc','total_weight');
    }
}
