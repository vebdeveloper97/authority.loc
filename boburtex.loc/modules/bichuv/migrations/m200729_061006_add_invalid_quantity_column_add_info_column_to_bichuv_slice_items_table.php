<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_slice_items}}`.
 */
class m200729_061006_add_invalid_quantity_column_add_info_column_to_bichuv_slice_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_slice_items}}', 'invalid_quantity', $this->decimal(20,3));
        $this->addColumn('{{%bichuv_slice_items}}', 'add_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bichuv_slice_items}}', 'invalid_quantity');
        $this->dropColumn('{{%bichuv_slice_items}}', 'add_info');
    }
}
