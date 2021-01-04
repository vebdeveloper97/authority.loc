<?php

use yii\db\Migration;

/**
 * Class m191203_072124_add_toquv_instructions_id_to_toquv_documents_table
 */
class m191203_072124_add_toquv_instructions_id_to_toquv_documents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_documents','toquv_instructions_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_documents','toquv_instructions_id');
    }
}
