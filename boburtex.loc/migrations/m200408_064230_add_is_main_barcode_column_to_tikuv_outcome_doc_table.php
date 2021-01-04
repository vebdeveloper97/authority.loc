<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_doc}}`.
 */
class m200408_064230_add_is_main_barcode_column_to_tikuv_outcome_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_outcome_products','is_main_barcode',$this->integer());
        $this->addColumn('tikuv_package_item_balance','is_main_barcode',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tikuv_outcome_products','is_main_barcode');
        $this->dropColumn('tikuv_package_item_balance','is_main_barcode');
    }
}
