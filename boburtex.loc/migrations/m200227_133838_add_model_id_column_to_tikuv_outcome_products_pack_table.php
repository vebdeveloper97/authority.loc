<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products_pack}}`.
 */
class m200227_133838_add_model_id_column_to_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('tikuv_outcome_products_pack','model_id',$this->smallInteger(6));
        $this->addColumn('tikuv_outcome_products_pack','tikuv_slice_item_balance_id', $this->integer());

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-model_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'model_id'
        );
        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-model_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'model_id',
            '{{%product}}',
            'id'
        );
        // creates index for column `tikuv_slice_item_balance_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-tikuv_slice_item_balance_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'tikuv_slice_item_balance_id'
        );
        // add foreign key for table `{{%tikuv_slice_item_balance}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-tikuv_slice_item_balance_id}}',
            '{{%tikuv_outcome_products_pack}}',
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
        // drops foreign key for table `{{%model_id}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-model_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-model_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops foreign key for table `{{%tikuv_slice_item_balance_id}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-tikuv_slice_item_balance_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `tikuv_slice_item_balance_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-tikuv_slice_item_balance_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );
        $this->dropColumn('tikuv_outcome_products_pack','model_id');
        $this->dropColumn('tikuv_outcome_products_pack','tikuv_slice_item_balance_id');
    }
}
