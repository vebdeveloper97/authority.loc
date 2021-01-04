<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_variation_parts}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_list}}`
 * - `{{%model_var}}`
 * - `{{%color_pantone}}`
 * - `{{%raw_material}}`
 * - `{{%boyoqhona_color}}`
 * - `{{%base_pattern_part}}`
 */
class m200417_181716_create_model_variation_parts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_variation_parts}}', [
            'id' => $this->primaryKey(),
            'model_list_id' => $this->integer(),
            'model_var_id' => $this->integer(),
            'color_pantone_id' => $this->integer(),
            'raw_material_id' => $this->integer(),
            'boyoqhona_color_id' => $this->integer(),
            'base_pattern_part_id' => $this->integer(),
            'name' => $this->string(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `model_list_id`
        $this->createIndex(
            '{{%idx-model_variation_parts-model_list_id}}',
            '{{%model_variation_parts}}',
            'model_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-model_variation_parts-model_list_id}}',
            '{{%model_variation_parts}}',
            'model_list_id',
            '{{%models_list}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-model_variation_parts-model_var_id}}',
            '{{%model_variation_parts}}',
            'model_var_id'
        );

        // add foreign key for table `{{%model_var}}`
        $this->addForeignKey(
            '{{%fk-model_variation_parts-model_var_id}}',
            '{{%model_variation_parts}}',
            'model_var_id',
            '{{%models_variations}}',
            'id',
            'CASCADE'
        );

        // creates index for column `color_pantone_id`
        $this->createIndex(
            '{{%idx-model_variation_parts-color_pantone_id}}',
            '{{%model_variation_parts}}',
            'color_pantone_id'
        );

        // add foreign key for table `{{%color_pantone}}`
        $this->addForeignKey(
            '{{%fk-model_variation_parts-color_pantone_id}}',
            '{{%model_variation_parts}}',
            'color_pantone_id',
            '{{%color_pantone}}',
            'id'
        );

        // creates index for column `raw_material_id`
        $this->createIndex(
            '{{%idx-model_variation_parts-raw_material_id}}',
            '{{%model_variation_parts}}',
            'raw_material_id'
        );

        // add foreign key for table `{{%raw_material}}`
        $this->addForeignKey(
            '{{%fk-model_variation_parts-raw_material_id}}',
            '{{%model_variation_parts}}',
            'raw_material_id',
            '{{%toquv_raw_materials}}',
            'id'
        );

        // creates index for column `boyoqhona_color_id`
        $this->createIndex(
            '{{%idx-model_variation_parts-boyoqhona_color_id}}',
            '{{%model_variation_parts}}',
            'boyoqhona_color_id'
        );

        // add foreign key for table `{{%boyoqhona_color}}`
        $this->addForeignKey(
            '{{%fk-model_variation_parts-boyoqhona_color_id}}',
            '{{%model_variation_parts}}',
            'boyoqhona_color_id',
            '{{%color}}',
            'id'
        );

        // creates index for column `base_pattern_part_id`
        $this->createIndex(
            '{{%idx-model_variation_parts-base_pattern_part_id}}',
            '{{%model_variation_parts}}',
            'base_pattern_part_id'
        );

        // add foreign key for table `{{%base_pattern_part}}`
        $this->addForeignKey(
            '{{%fk-model_variation_parts-base_pattern_part_id}}',
            '{{%model_variation_parts}}',
            'base_pattern_part_id',
            '{{%base_pattern_part}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_list}}`
        $this->dropForeignKey(
            '{{%fk-model_variation_parts-model_list_id}}',
            '{{%model_variation_parts}}'
        );

        // drops index for column `model_list_id`
        $this->dropIndex(
            '{{%idx-model_variation_parts-model_list_id}}',
            '{{%model_variation_parts}}'
        );

        // drops foreign key for table `{{%model_var}}`
        $this->dropForeignKey(
            '{{%fk-model_variation_parts-model_var_id}}',
            '{{%model_variation_parts}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-model_variation_parts-model_var_id}}',
            '{{%model_variation_parts}}'
        );

        // drops foreign key for table `{{%color_pantone}}`
        $this->dropForeignKey(
            '{{%fk-model_variation_parts-color_pantone_id}}',
            '{{%model_variation_parts}}'
        );

        // drops index for column `color_pantone_id`
        $this->dropIndex(
            '{{%idx-model_variation_parts-color_pantone_id}}',
            '{{%model_variation_parts}}'
        );

        // drops foreign key for table `{{%raw_material}}`
        $this->dropForeignKey(
            '{{%fk-model_variation_parts-raw_material_id}}',
            '{{%model_variation_parts}}'
        );

        // drops index for column `raw_material_id`
        $this->dropIndex(
            '{{%idx-model_variation_parts-raw_material_id}}',
            '{{%model_variation_parts}}'
        );

        // drops foreign key for table `{{%boyoqhona_color}}`
        $this->dropForeignKey(
            '{{%fk-model_variation_parts-boyoqhona_color_id}}',
            '{{%model_variation_parts}}'
        );

        // drops index for column `boyoqhona_color_id`
        $this->dropIndex(
            '{{%idx-model_variation_parts-boyoqhona_color_id}}',
            '{{%model_variation_parts}}'
        );

        // drops foreign key for table `{{%base_pattern_part}}`
        $this->dropForeignKey(
            '{{%fk-model_variation_parts-base_pattern_part_id}}',
            '{{%model_variation_parts}}'
        );

        // drops index for column `base_pattern_part_id`
        $this->dropIndex(
            '{{%idx-model_variation_parts-base_pattern_part_id}}',
            '{{%model_variation_parts}}'
        );

        $this->dropTable('{{%model_variation_parts}}');
    }
}
