<?php

use yii\db\Migration;

/**
 * Class m201004_100316_after_base_model_table_file_table
 */
class m201004_100316_after_base_model_table_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-base_model_table_file-doc_items_id', 'base_model_table_file');
        $this->dropForeignKey('fk-base_model_tikuv_files-doc_items_id', 'base_model_tikuv_files');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey('fk-base_model_table_file-doc_items_id', 'base_model_table_file', 'doc_items_id', 'base_model_document_items', 'id');
        $this->addForeignKey('fk-base_model_tikuv_files-doc_items_id', 'base_model_tikuv_files', 'doc_items_id', 'base_model_document_items', 'id');
    }

}
