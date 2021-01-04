<?php

use yii\db\Migration;

/**
 * Class m200108_100706_add_bss_id_column_bichuv_doc_items_table
 */
class m200108_100706_add_bss_id_column_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc_items','bss_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc_items','bss_id');
    }
}
