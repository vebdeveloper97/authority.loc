<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_rel_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 * - `{{%bichuv_given_rolls}}`
 */
class m200307_042810_create_model_rel_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_rel_production}}', [
            'id' => $this->primaryKey(),
            'models_list_id' => $this->integer(),
            'model_variation_id' => $this->integer(),
            'bichuv_given_roll_id' => $this->integer(),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-model_rel_production-models_list_id}}',
            '{{%model_rel_production}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-model_rel_production-models_list_id}}',
            '{{%model_rel_production}}',
            'models_list_id',
            '{{%models_list}}',
            'id'
        );

        // creates index for column `model_variation_id`
        $this->createIndex(
            '{{%idx-model_rel_production-model_variation_id}}',
            '{{%model_rel_production}}',
            'model_variation_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-model_rel_production-model_variation_id}}',
            '{{%model_rel_production}}',
            'model_variation_id',
            '{{%models_variations}}',
            'id'
        );

        // creates index for column `bichuv_given_roll_id`
        $this->createIndex(
            '{{%idx-model_rel_production-bichuv_given_roll_id}}',
            '{{%model_rel_production}}',
            'bichuv_given_roll_id'
        );

        // add foreign key for table `{{%bichuv_given_roll}}`
        $this->addForeignKey(
            '{{%fk-model_rel_production-bichuv_given_roll_id}}',
            '{{%model_rel_production}}',
            'bichuv_given_roll_id',
            '{{%bichuv_given_rolls}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_production-models_list_id}}',
            '{{%model_rel_production}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-model_rel_production-models_list_id}}',
            '{{%model_rel_production}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_production-model_variation_id}}',
            '{{%model_rel_production}}'
        );

        // drops index for column `model_variation_id`
        $this->dropIndex(
            '{{%idx-model_rel_production-model_variation_id}}',
            '{{%model_rel_production}}'
        );

        // drops foreign key for table `{{%bichuv_given_rolls}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_production-bichuv_given_roll_id}}',
            '{{%model_rel_production}}'
        );

        // drops index for column `bichuv_given_roll_id`
        $this->dropIndex(
            '{{%idx-model_rel_production-bichuv_given_roll_id}}',
            '{{%model_rel_production}}'
        );

        $this->dropTable('{{%model_rel_production}}');
    }
}
