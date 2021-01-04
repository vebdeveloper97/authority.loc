<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_documents}}`.
 */
class m190817_055850_add_add_info_column_to_toquv_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_documents}}', 'add_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_documents}}', 'add_info');
    }
}
