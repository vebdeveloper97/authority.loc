<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_pack_rel_model_var}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_variations}}`
 * - `{{%tikuv_outcome_products_pack}}`
 */
class m200318_094134_create_junction_table_for_models_variations_and_tikuv_outcome_products_pack_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_pack_rel_model_var}}', [
            'models_variations_id' => $this->integer(),
            'tikuv_outcome_products_pack_id' => $this->integer(),
            'PRIMARY KEY(models_variations_id, tikuv_outcome_products_pack_id)',
        ]);

        // creates index for column `models_variations_id`
        $this->createIndex(
            '{{%idx-tikuv_pack_rel_model_var-models_variations_id}}',
            '{{%tikuv_pack_rel_model_var}}',
            'models_variations_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-tikuv_pack_rel_model_var-models_variations_id}}',
            '{{%tikuv_pack_rel_model_var}}',
            'models_variations_id',
            '{{%models_variations}}',
            'id',
            'CASCADE'
        );

        // creates index for column `tikuv_outcome_products_pack_id`
        $this->createIndex(
            '{{%idx-tikuv_pack_rel_model_var-tikuv_outcome_products_pack_id}}',
            '{{%tikuv_pack_rel_model_var}}',
            'tikuv_outcome_products_pack_id'
        );

        // add foreign key for table `{{%tikuv_outcome_products_pack}}`
        $this->addForeignKey(
            '{{%fk-tikuv_pack_rel_model_var-tikuv_outcome_products_pack_id}}',
            '{{%tikuv_pack_rel_model_var}}',
            'tikuv_outcome_products_pack_id',
            '{{%tikuv_outcome_products_pack}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_pack_rel_model_var-models_variations_id}}',
            '{{%tikuv_pack_rel_model_var}}'
        );

        // drops index for column `models_variations_id`
        $this->dropIndex(
            '{{%idx-tikuv_pack_rel_model_var-models_variations_id}}',
            '{{%tikuv_pack_rel_model_var}}'
        );

        // drops foreign key for table `{{%tikuv_outcome_products_pack}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_pack_rel_model_var-tikuv_outcome_products_pack_id}}',
            '{{%tikuv_pack_rel_model_var}}'
        );

        // drops index for column `tikuv_outcome_products_pack_id`
        $this->dropIndex(
            '{{%idx-tikuv_pack_rel_model_var-tikuv_outcome_products_pack_id}}',
            '{{%tikuv_pack_rel_model_var}}'
        );

        $this->dropTable('{{%tikuv_pack_rel_model_var}}');
    }
}
