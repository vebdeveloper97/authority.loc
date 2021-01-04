<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_document_items}}`.
 */
class m200111_183406_add_bss_id_column_to_toquv_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_document_items','bss_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_document_items','bss_id');
    }
}
