<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_documents}}`.
 */
class m200109_101018_add_parent_doc_id_column_to_toquv_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_documents}}','parent_doc_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_documents}}','parent_doc_id');
    }
}
