<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc}}`.
 */
class m200114_174801_add_work_weight_column_to_tikuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_doc','work_weight', $this->integer(5)->defaultValue(0));
        $this->addColumn('tikuv_doc_items','work_weight', $this->integer(5)->defaultValue(0));
        $this->addColumn('tikuv_doc_items','nastel_party_no', $this->string(25));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tikuv_doc','work_weight');
        $this->dropColumn('tikuv_doc_items','work_weight');
        $this->dropColumn('tikuv_doc_items','nastel_party_no');
    }
}
