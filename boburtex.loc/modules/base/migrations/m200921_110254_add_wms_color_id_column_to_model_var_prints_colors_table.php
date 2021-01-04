<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_var_prints_colors}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wms_color}}`
 */
class m200921_110254_add_wms_color_id_column_to_model_var_prints_colors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_var_prints_colors}}', 'wms_color_id', $this->integer());

        // creates index for column `wms_color_id`
        $this->createIndex(
            '{{%idx-model_var_prints_colors-wms_color_id}}',
            '{{%model_var_prints_colors}}',
            'wms_color_id'
        );

        // add foreign key for table `{{%wms_color}}`
        $this->addForeignKey(
            '{{%fk-model_var_prints_colors-wms_color_id}}',
            '{{%model_var_prints_colors}}',
            'wms_color_id',
            '{{%wms_color}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wms_color}}`
        $this->dropForeignKey(
            '{{%fk-model_var_prints_colors-wms_color_id}}',
            '{{%model_var_prints_colors}}'
        );

        // drops index for column `wms_color_id`
        $this->dropIndex(
            '{{%idx-model_var_prints_colors-wms_color_id}}',
            '{{%model_var_prints_colors}}'
        );

        $this->dropColumn('{{%model_var_prints_colors}}', 'wms_color_id');
    }
}
