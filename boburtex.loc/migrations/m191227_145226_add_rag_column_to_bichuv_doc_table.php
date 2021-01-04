<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 */
class m191227_145226_add_rag_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc','rag',$this->decimal(20,3)->defaultValue(0));
        $this->addColumn('bichuv_doc','work_weight',$this->integer(5)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc','rag');
        $this->dropColumn('bichuv_doc','work_weight');
    }
}
