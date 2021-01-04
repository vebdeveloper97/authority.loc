<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_variation_colors}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_detail_lists}}`
 * - `{{%toquv_raw_materials}}`
 */
class m200305_113853_add_some_fields_column_to_models_variation_colors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_variation_colors}}', 'base_detail_list_id', $this->integer());
        $this->addColumn('{{%models_variation_colors}}', 'toquv_raw_material_id', $this->integer());

        // creates index for column `base_detail_list_id`
        $this->createIndex(
            '{{%idx-models_variation_colors-base_detail_list_id}}',
            '{{%models_variation_colors}}',
            'base_detail_list_id'
        );

        // add foreign key for table `{{%base_detail_lists}}`
        $this->addForeignKey(
            '{{%fk-models_variation_colors-base_detail_list_id}}',
            '{{%models_variation_colors}}',
            'base_detail_list_id',
            '{{%base_detail_lists}}',
            'id'
        );

        // creates index for column `toquv_raw_material_id`
        $this->createIndex(
            '{{%idx-models_variation_colors-toquv_raw_material_id}}',
            '{{%models_variation_colors}}',
            'toquv_raw_material_id'
        );

        // add foreign key for table `{{%toquv_raw_materials}}`
        $this->addForeignKey(
            '{{%fk-models_variation_colors-toquv_raw_material_id}}',
            '{{%models_variation_colors}}',
            'toquv_raw_material_id',
            '{{%toquv_raw_materials}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_detail_lists}}`
        $this->dropForeignKey(
            '{{%fk-models_variation_colors-base_detail_list_id}}',
            '{{%models_variation_colors}}'
        );

        // drops index for column `base_detail_list_id`
        $this->dropIndex(
            '{{%idx-models_variation_colors-base_detail_list_id}}',
            '{{%models_variation_colors}}'
        );

        // drops foreign key for table `{{%toquv_raw_materials}}`
        $this->dropForeignKey(
            '{{%fk-models_variation_colors-toquv_raw_material_id}}',
            '{{%models_variation_colors}}'
        );

        // drops index for column `toquv_raw_material_id`
        $this->dropIndex(
            '{{%idx-models_variation_colors-toquv_raw_material_id}}',
            '{{%models_variation_colors}}'
        );

        $this->dropColumn('{{%models_variation_colors}}', 'base_detail_list_id');
        $this->dropColumn('{{%models_variation_colors}}', 'toquv_raw_material_id');
    }
}
