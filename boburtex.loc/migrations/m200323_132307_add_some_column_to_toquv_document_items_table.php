<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_document_items}}`.
 */
class m200323_132307_add_some_column_to_toquv_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_document_items}}', 'party', $this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_document_items}}', 'party');
    }
}
