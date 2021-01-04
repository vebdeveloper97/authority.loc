<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_raw_materials}}`
 */
class m200312_112308_add_toquv_raw_material_id_column_to_models_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_variations}}', 'toquv_raw_material_id', $this->integer());
        $this->addColumn('{{%models_variations}}', 'boyoqhona_color_id', $this->integer());

        // creates index for column `toquv_raw_material_id`
        $this->createIndex(
            '{{%idx-models_variations-toquv_raw_material_id}}',
            '{{%models_variations}}',
            'toquv_raw_material_id'
        );

        // add foreign key for table `{{%toquv_raw_materials}}`
        $this->addForeignKey(
            '{{%fk-models_variations-toquv_raw_material_id}}',
            '{{%models_variations}}',
            'toquv_raw_material_id',
            '{{%toquv_raw_materials}}',
            'id'
        );

        // creates index for column `boyoqhona_color_id`
        $this->createIndex(
            '{{%idx-models_variations-boyoqhona_color_id}}',
            '{{%models_variations}}',
            'boyoqhona_color_id'
        );

        // add foreign key for table `{{%color}}`
        $this->addForeignKey(
            '{{%fk-models_variations-boyoqhona_color_id}}',
            '{{%models_variations}}',
            'boyoqhona_color_id',
            '{{%color}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_raw_materials}}`
        $this->dropForeignKey(
            '{{%fk-models_variations-toquv_raw_material_id}}',
            '{{%models_variations}}'
        );

        // drops index for column `toquv_raw_material_id`
        $this->dropIndex(
            '{{%idx-models_variations-toquv_raw_material_id}}',
            '{{%models_variations}}'
        );

        // drops foreign key for table `{{%color}}`
        $this->dropForeignKey(
            '{{%fk-models_variations-boyoqhona_color_id}}',
            '{{%models_variations}}'
        );

        // drops index for column `boyoqhona_color_id`
        $this->dropIndex(
            '{{%idx-models_variations-boyoqhona_color_id}}',
            '{{%models_variations}}'
        );

        $this->dropColumn('{{%models_variations}}', 'toquv_raw_material_id');
        $this->dropColumn('{{%models_variations}}', 'boyoqhona_color_id');
    }
}
