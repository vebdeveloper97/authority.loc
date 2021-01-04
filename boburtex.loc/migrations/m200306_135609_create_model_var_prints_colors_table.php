<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_var_prints_colors}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_var_prints}}`
 * - `{{%color_pantone}}`
 */
class m200306_135609_create_model_var_prints_colors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_var_prints_colors}}', [
            'id' => $this->primaryKey(),
            'model_var_prints_id' => $this->integer(),
            'color_pantone_id' => $this->integer(),
            'is_main' => $this->boolean(),
            'add_info' => $this->string(),
        ]);

        // creates index for column `model_var_prints_id`
        $this->createIndex(
            '{{%idx-model_var_prints_colors-model_var_prints_id}}',
            '{{%model_var_prints_colors}}',
            'model_var_prints_id'
        );

        // add foreign key for table `{{%model_var_prints}}`
        $this->addForeignKey(
            '{{%fk-model_var_prints_colors-model_var_prints_id}}',
            '{{%model_var_prints_colors}}',
            'model_var_prints_id',
            '{{%model_var_prints}}',
            'id',
            'CASCADE'
        );

        // creates index for column `color_pantone_id`
        $this->createIndex(
            '{{%idx-model_var_prints_colors-color_pantone_id}}',
            '{{%model_var_prints_colors}}',
            'color_pantone_id'
        );

        // add foreign key for table `{{%color_pantone}}`
        $this->addForeignKey(
            '{{%fk-model_var_prints_colors-color_pantone_id}}',
            '{{%model_var_prints_colors}}',
            'color_pantone_id',
            '{{%color_pantone}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_var_prints}}`
        $this->dropForeignKey(
            '{{%fk-model_var_prints_colors-model_var_prints_id}}',
            '{{%model_var_prints_colors}}'
        );

        // drops index for column `model_var_prints_id`
        $this->dropIndex(
            '{{%idx-model_var_prints_colors-model_var_prints_id}}',
            '{{%model_var_prints_colors}}'
        );

        // drops foreign key for table `{{%color_pantone}}`
        $this->dropForeignKey(
            '{{%fk-model_var_prints_colors-color_pantone_id}}',
            '{{%model_var_prints_colors}}'
        );

        // drops index for column `color_pantone_id`
        $this->dropIndex(
            '{{%idx-model_var_prints_colors-color_pantone_id}}',
            '{{%model_var_prints_colors}}'
        );

        $this->dropTable('{{%model_var_prints_colors}}');
    }
}
