<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_variation_colors}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wms_color}}`
 * - `{{%wms_desen}}`
 */
class m200818_112856_add_wms_color_id_and_wms_desen_id_columns_to_models_variation_colors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_variation_colors}}', 'wms_color_id', $this->integer());
        $this->addColumn('{{%models_variation_colors}}', 'wms_desen_id', $this->integer());

        // creates index for column `wms_color_id`
        $this->createIndex(
            '{{%idx-models_variation_colors-wms_color_id}}',
            '{{%models_variation_colors}}',
            'wms_color_id'
        );

        // add foreign key for table `{{%wms_color}}`
        $this->addForeignKey(
            '{{%fk-models_variation_colors-wms_color_id}}',
            '{{%models_variation_colors}}',
            'wms_color_id',
            '{{%wms_color}}',
            'id',
            'CASCADE'
        );

        // creates index for column `wms_desen_id`
        $this->createIndex(
            '{{%idx-models_variation_colors-wms_desen_id}}',
            '{{%models_variation_colors}}',
            'wms_desen_id'
        );

        // add foreign key for table `{{%wms_desen}}`
        $this->addForeignKey(
            '{{%fk-models_variation_colors-wms_desen_id}}',
            '{{%models_variation_colors}}',
            'wms_desen_id',
            '{{%wms_desen}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wms_color}}`
        $this->dropForeignKey(
            '{{%fk-models_variation_colors-wms_color_id}}',
            '{{%models_variation_colors}}'
        );

        // drops index for column `wms_color_id`
        $this->dropIndex(
            '{{%idx-models_variation_colors-wms_color_id}}',
            '{{%models_variation_colors}}'
        );

        // drops foreign key for table `{{%wms_desen}}`
        $this->dropForeignKey(
            '{{%fk-models_variation_colors-wms_desen_id}}',
            '{{%models_variation_colors}}'
        );

        // drops index for column `wms_desen_id`
        $this->dropIndex(
            '{{%idx-models_variation_colors-wms_desen_id}}',
            '{{%models_variation_colors}}'
        );

        $this->dropColumn('{{%models_variation_colors}}', 'wms_color_id');
        $this->dropColumn('{{%models_variation_colors}}', 'wms_desen_id');
    }
}
