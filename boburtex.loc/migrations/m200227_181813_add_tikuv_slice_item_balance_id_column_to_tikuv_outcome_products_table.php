<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products}}`.
 */
class m200227_181813_add_tikuv_slice_item_balance_id_column_to_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_outcome_products','tikuv_slice_item_balance_id', $this->integer());

        // creates index for column `tikuv_slice_item_balance_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products-tikuv_slice_item_balance_id}}',
            '{{%tikuv_outcome_products}}',
            'tikuv_slice_item_balance_id'
        );
        // add foreign key for table `{{%tikuv_slice_item_balance}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products-tikuv_slice_item_balance_id}}',
            '{{%tikuv_outcome_products}}',
            'tikuv_slice_item_balance_id',
            '{{%tikuv_slice_item_balance}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drops foreign key for table `{{%tikuv_slice_item_balance_id}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products-tikuv_slice_item_balance_id}}',
            '{{%tikuv_outcome_products}}'
        );

        // drops index for column `tikuv_slice_item_balance_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products-tikuv_slice_item_balance_id}}',
            '{{%tikuv_outcome_products}}'
        );
        $this->dropColumn('tikuv_outcome_products','tikuv_slice_item_balance_id');
    }
}
