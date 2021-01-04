<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products_pack}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_var}}`
 */
class m200319_043458_add_model_var_id_column_to_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'model_var_id', $this->integer());

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-model_var_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'model_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-model_var_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'model_var_id',
            '{{%models_variations}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-model_var_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-model_var_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'model_var_id');
    }
}
