<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_goods_doc_accepted}}`.
 */
class m200402_095245_add_barcode_column_to_tikuv_goods_doc_accepted_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_goods_doc_accepted','barcode',$this->integer());
        $this->addColumn('model_rel_production','order_id',$this->integer());
        $this->addColumn('model_rel_production','order_item_id',$this->integer());

        // creates index for column `barcode`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_accepted-barcode}}',
            '{{%tikuv_goods_doc_accepted}}',
            'barcode'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `barcode`
        $this->dropIndex(
            '{{%idx-base_patterns-barcode}}',
            '{{%base_patterns}}'
        );

        $this->dropColumn('tikuv_goods_doc_accepted','barcode');
        $this->dropColumn('model_rel_production','order_id');
        $this->dropColumn('model_rel_production','order_item_id');
    }
}
