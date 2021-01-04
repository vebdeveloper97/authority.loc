<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_variation_colors}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%color}}`
 */
class m200310_094318_add_color_boyoqhona_id_column_to_models_variation_colors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_variation_colors}}', 'color_boyoqhona_id', $this->integer());

        // creates index for column `color_boyoqhona_id`
        $this->createIndex(
            '{{%idx-models_variation_colors-color_boyoqhona_id}}',
            '{{%models_variation_colors}}',
            'color_boyoqhona_id'
        );

        // add foreign key for table `{{%color}}`
        $this->addForeignKey(
            '{{%fk-models_variation_colors-color_boyoqhona_id}}',
            '{{%models_variation_colors}}',
            'color_boyoqhona_id',
            '{{%color}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%color}}`
        $this->dropForeignKey(
            '{{%fk-models_variation_colors-color_boyoqhona_id}}',
            '{{%models_variation_colors}}'
        );

        // drops index for column `color_boyoqhona_id`
        $this->dropIndex(
            '{{%idx-models_variation_colors-color_boyoqhona_id}}',
            '{{%models_variation_colors}}'
        );

        $this->dropColumn('{{%models_variation_colors}}', 'color_boyoqhona_id');
    }
}
