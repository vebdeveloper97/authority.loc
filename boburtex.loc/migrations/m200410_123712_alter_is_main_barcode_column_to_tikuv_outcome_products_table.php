<?php

use yii\db\Migration;

/**
 * Class m200410_123712_alter_is_main_barcode_column_to_tikuv_outcome_products_table
 */
class m200410_123712_alter_is_main_barcode_column_to_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tikuv_outcome_products','is_main_barcode', $this->string(50));
        $this->alterColumn('tikuv_package_item_balance','is_main_barcode', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
