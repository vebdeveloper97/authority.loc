<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_slice_items}}`.
 */
class m200828_074841_add_fact_quantity_column_to_bichuv_slice_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_slice_items}}', 'fact_quantity', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bichuv_slice_items}}', 'fact_quantity');
    }
}
