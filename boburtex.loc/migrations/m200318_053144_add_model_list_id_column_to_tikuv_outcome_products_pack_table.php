<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products_pack}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_list}}`
 * - `{{%model_var}}`
 */
class m200318_053144_add_model_list_id_column_to_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'model_list_id', $this->integer());

        // creates index for column `nastel_no`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-nastel_no}}',
            '{{%tikuv_outcome_products_pack}}',
            'nastel_no'
        );

        // creates index for column `model_list_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-model_list_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'model_list_id'
        );

        // add foreign key for table `{{%model_list}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-model_list_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'model_list_id',
            '{{%models_list}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `nastel_no`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-nastel_no}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops foreign key for table `{{%model_list}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-model_list_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `model_list_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-model_list_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'model_list_id');
    }
}
