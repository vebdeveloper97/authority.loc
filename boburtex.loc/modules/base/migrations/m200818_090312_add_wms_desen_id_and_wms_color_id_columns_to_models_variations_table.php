<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wms_desen}}`
 * - `{{%wms_color}}`
 */
class m200818_090312_add_wms_desen_id_and_wms_color_id_columns_to_models_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_variations}}', 'wms_desen_id', $this->integer());
        $this->addColumn('{{%models_variations}}', 'wms_color_id', $this->integer());

        // creates index for column `wms_desen_id`
        $this->createIndex(
            '{{%idx-models_variations-wms_desen_id}}',
            '{{%models_variations}}',
            'wms_desen_id'
        );

        // add foreign key for table `{{%wms_desen}}`
        $this->addForeignKey(
            '{{%fk-models_variations-wms_desen_id}}',
            '{{%models_variations}}',
            'wms_desen_id',
            '{{%wms_desen}}',
            'id',
            'CASCADE'
        );

        // creates index for column `wms_color_id`
        $this->createIndex(
            '{{%idx-models_variations-wms_color_id}}',
            '{{%models_variations}}',
            'wms_color_id'
        );

        // add foreign key for table `{{%wms_color}}`
        $this->addForeignKey(
            '{{%fk-models_variations-wms_color_id}}',
            '{{%models_variations}}',
            'wms_color_id',
            '{{%wms_color}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wms_desen}}`
        $this->dropForeignKey(
            '{{%fk-models_variations-wms_desen_id}}',
            '{{%models_variations}}'
        );

        // drops index for column `wms_desen_id`
        $this->dropIndex(
            '{{%idx-models_variations-wms_desen_id}}',
            '{{%models_variations}}'
        );

        // drops foreign key for table `{{%wms_color}}`
        $this->dropForeignKey(
            '{{%fk-models_variations-wms_color_id}}',
            '{{%models_variations}}'
        );

        // drops index for column `wms_color_id`
        $this->dropIndex(
            '{{%idx-models_variations-wms_color_id}}',
            '{{%models_variations}}'
        );

        $this->dropColumn('{{%models_variations}}', 'wms_desen_id');
        $this->dropColumn('{{%models_variations}}', 'wms_color_id');
    }
}
