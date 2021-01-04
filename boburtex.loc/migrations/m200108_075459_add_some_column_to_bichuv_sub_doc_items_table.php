<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_sub_doc_items}}`.
 */
class m200108_075459_add_some_column_to_bichuv_sub_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_sub_doc_items','rm_id', $this->integer());
        $this->addColumn('bichuv_sub_doc_items','ne_id', $this->integer());
        $this->addColumn('bichuv_sub_doc_items','thread_id', $this->integer());
        $this->addColumn('bichuv_sub_doc_items','pus_fine_id', $this->integer());
        $this->addColumn('bichuv_sub_doc_items','c_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_sub_doc_items','rm_id');
        $this->dropColumn('bichuv_sub_doc_items','ne_id');
        $this->dropColumn('bichuv_sub_doc_items','thread_id');
        $this->dropColumn('bichuv_sub_doc_items','pus_fine_id');
        $this->dropColumn('bichuv_sub_doc_items','c_id');
    }
}
