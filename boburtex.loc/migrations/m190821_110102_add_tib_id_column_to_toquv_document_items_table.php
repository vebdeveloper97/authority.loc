<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_document_items}}`.
 */
class m190821_110102_add_tib_id_column_to_toquv_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_document_items}}', 'tib_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_document_items}}', 'tib_id');
    }
}
