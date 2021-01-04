<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 */
class m200529_122923_add_some_column_to_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products}}', 'models_list_id', $this->integer());
        $this->addColumn('{{%tikuv_outcome_products}}', 'model_var_id', $this->integer());
        $this->addColumn('{{%tikuv_outcome_products}}', 'order_id', $this->integer());
        $this->addColumn('{{%tikuv_outcome_products}}', 'order_item_id', $this->integer());
        $this->addColumn('{{%tikuv_outcome_products}}', 'nastel_no', $this->string(30));

        $this->addColumn('{{%model_rel_doc}}', 'nastel_no', $this->string(30));

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products-models_list_id}}',
            '{{%tikuv_outcome_products}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products-models_list_id}}',
            '{{%tikuv_outcome_products}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products-model_var_id}}',
            '{{%tikuv_outcome_products}}',
            'model_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products-model_var_id}}',
            '{{%tikuv_outcome_products}}',
            'model_var_id',
            '{{%models_variations}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products-models_list_id}}',
            '{{%tikuv_outcome_products}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products-models_list_id}}',
            '{{%tikuv_outcome_products}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products-model_var_id}}',
            '{{%tikuv_outcome_products}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products-model_var_id}}',
            '{{%tikuv_outcome_products}}'
        );

        $this->dropColumn('{{%tikuv_outcome_products}}', 'models_list_id');
        $this->dropColumn('{{%tikuv_outcome_products}}', 'model_var_id');
        $this->dropColumn('{{%tikuv_outcome_products}}', 'order_id');
        $this->dropColumn('{{%tikuv_outcome_products}}', 'order_item_id');
        $this->dropColumn('{{%tikuv_outcome_products}}', 'nastel_no');

        $this->dropColumn('{{%model_rel_doc}}', 'nastel_no');
    }
}
