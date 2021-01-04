<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%color_pantone}}`
 */
class m200311_104917_add_color_pantone_id_column_to_models_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_variations}}', 'color_pantone_id', $this->integer());

        // creates index for column `color_pantone_id`
        $this->createIndex(
            '{{%idx-models_variations-color_pantone_id}}',
            '{{%models_variations}}',
            'color_pantone_id'
        );

        // add foreign key for table `{{%color_pantone}}`
        $this->addForeignKey(
            '{{%fk-models_variations-color_pantone_id}}',
            '{{%models_variations}}',
            'color_pantone_id',
            '{{%color_pantone}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%color_pantone}}`
        $this->dropForeignKey(
            '{{%fk-models_variations-color_pantone_id}}',
            '{{%models_variations}}'
        );

        // drops index for column `color_pantone_id`
        $this->dropIndex(
            '{{%idx-models_variations-color_pantone_id}}',
            '{{%models_variations}}'
        );

        $this->dropColumn('{{%models_variations}}', 'color_pantone_id');
    }
}
