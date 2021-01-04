<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products_pack}}`.
 */
class m191015_081054_add_doc_id_column_to_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_outcome_products_pack','doc_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tikuv_outcome_products_pack','doc_id');
    }
}
