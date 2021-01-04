<?php

use yii\db\Migration;

/**
 * Class m200128_185157_remove_tikuv_doc_and_doc_items_table
 */
class m200128_185157_remove_tikuv_doc_and_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('tikuv_document_items');
        $this->dropTable('tikuv_documents');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }


}
