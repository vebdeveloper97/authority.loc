<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_slice_item_balance}}`.
 */
class m200104_162559_add_created_by_column_to_bichuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_slice_item_balance','created_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_slice_item_balance','created_by');
    }
}
