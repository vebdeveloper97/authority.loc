<?php

use yii\db\Migration;

/**
 * Class m191211_152658_add_thread_consist_to_bichuv_sub_doc_items_table
 */
class m191211_152658_add_thread_consist_to_bichuv_sub_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_sub_doc_items','thread_consist', $this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_sub_doc_items','thread_consist');
    }


}
