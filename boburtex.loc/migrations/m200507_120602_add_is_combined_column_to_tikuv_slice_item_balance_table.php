<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_slice_item_balance}}`.
 */
class m200507_120602_add_is_combined_column_to_tikuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_slice_item_balance','is_combined', $this->smallInteger(1)->defaultValue(1));
        $this->addColumn('tikuv_doc_items','is_combined', $this->smallInteger(1)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tikuv_slice_item_balance','is_combined');
        $this->dropColumn('tikuv_doc_items','is_combined');
    }
}
