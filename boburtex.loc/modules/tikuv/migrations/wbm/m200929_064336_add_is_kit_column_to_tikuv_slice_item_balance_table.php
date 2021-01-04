<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_slice_item_balance}}`.
 */
class m200929_064336_add_is_kit_column_to_tikuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_slice_item_balance}}', 'is_kit', $this->smallInteger()->defaultValue(1));
        // creates index for column `is_kit`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-is_kit}}',
            '{{%tikuv_slice_item_balance}}',
            'is_kit'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `is_kit`
//        $this->dropIndex(
//            '{{%idx-tikuv_slice_item_balance-is_kit}}',
//            '{{%tikuv_slice_item_balance}}'
//        );
        $this->dropColumn('{{%tikuv_slice_item_balance}}', 'is_kit');
    }
}
