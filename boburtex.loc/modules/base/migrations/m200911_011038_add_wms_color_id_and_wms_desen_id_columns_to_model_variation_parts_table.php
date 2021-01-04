<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_variation_parts}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wms_color}}`
 * - `{{%wms_desen}}`
 */
class m200911_011038_add_wms_color_id_and_wms_desen_id_columns_to_model_variation_parts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_variation_parts}}', 'wms_color_id', $this->integer());
        $this->addColumn('{{%model_variation_parts}}', 'wms_desen_id', $this->integer());

        // creates index for column `wms_color_id`
        $this->createIndex(
            '{{%idx-model_variation_parts-wms_color_id}}',
            '{{%model_variation_parts}}',
            'wms_color_id'
        );

        // add foreign key for table `{{%wms_color}}`
        $this->addForeignKey(
            '{{%fk-model_variation_parts-wms_color_id}}',
            '{{%model_variation_parts}}',
            'wms_color_id',
            '{{%wms_color}}',
            'id',
            'CASCADE'
        );

        // creates index for column `wms_desen_id`
        $this->createIndex(
            '{{%idx-model_variation_parts-wms_desen_id}}',
            '{{%model_variation_parts}}',
            'wms_desen_id'
        );

        // add foreign key for table `{{%wms_desen}}`
        $this->addForeignKey(
            '{{%fk-model_variation_parts-wms_desen_id}}',
            '{{%model_variation_parts}}',
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
            '{{%fk-model_variation_parts-wms_color_id}}',
            '{{%model_variation_parts}}'
        );

        // drops index for column `wms_color_id`
        $this->dropIndex(
            '{{%idx-model_variation_parts-wms_color_id}}',
            '{{%model_variation_parts}}'
        );

        // drops foreign key for table `{{%wms_desen}}`
        $this->dropForeignKey(
            '{{%fk-model_variation_parts-wms_desen_id}}',
            '{{%model_variation_parts}}'
        );

        // drops index for column `wms_desen_id`
        $this->dropIndex(
            '{{%idx-model_variation_parts-wms_desen_id}}',
            '{{%model_variation_parts}}'
        );

        $this->dropColumn('{{%model_variation_parts}}', 'wms_color_id');
        $this->dropColumn('{{%model_variation_parts}}', 'wms_desen_id');
    }
}
