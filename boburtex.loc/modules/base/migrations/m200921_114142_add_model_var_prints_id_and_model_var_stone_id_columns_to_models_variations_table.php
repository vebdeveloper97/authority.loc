<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_var_stone}}`
 * - `{{%model_var_prints}}`
 */
class m200921_114142_add_model_var_prints_id_and_model_var_stone_id_columns_to_models_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_variations}}', 'model_var_stone_id', $this->integer());
        $this->addColumn('{{%models_variations}}', 'model_var_prints_id', $this->integer());

        // creates index for column `model_var_stone_id`
        $this->createIndex(
            '{{%idx-models_variations-model_var_stone_id}}',
            '{{%models_variations}}',
            'model_var_stone_id'
        );

        // add foreign key for table `{{%model_var_stone}}`
        $this->addForeignKey(
            '{{%fk-models_variations-model_var_stone_id}}',
            '{{%models_variations}}',
            'model_var_stone_id',
            '{{%model_var_stone}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_var_prints_id`
        $this->createIndex(
            '{{%idx-models_variations-model_var_prints_id}}',
            '{{%models_variations}}',
            'model_var_prints_id'
        );

        // add foreign key for table `{{%model_var_prints}}`
        $this->addForeignKey(
            '{{%fk-models_variations-model_var_prints_id}}',
            '{{%models_variations}}',
            'model_var_prints_id',
            '{{%model_var_prints}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_var_stone}}`
        $this->dropForeignKey(
            '{{%fk-models_variations-model_var_stone_id}}',
            '{{%models_variations}}'
        );

        // drops index for column `model_var_stone_id`
        $this->dropIndex(
            '{{%idx-models_variations-model_var_stone_id}}',
            '{{%models_variations}}'
        );

        // drops foreign key for table `{{%model_var_prints}}`
        $this->dropForeignKey(
            '{{%fk-models_variations-model_var_prints_id}}',
            '{{%models_variations}}'
        );

        // drops index for column `model_var_prints_id`
        $this->dropIndex(
            '{{%idx-models_variations-model_var_prints_id}}',
            '{{%models_variations}}'
        );

        $this->dropColumn('{{%models_variations}}', 'model_var_stone_id');
        $this->dropColumn('{{%models_variations}}', 'model_var_prints_id');
    }
}
