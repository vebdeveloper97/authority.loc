<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_documents}}`.
 */
class m191130_051909_add_party_column_to_toquv_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_documents','party', $this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_documents','party');
    }
}
