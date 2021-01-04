<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_var_baski_colors}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_var_baski}}`
 * - `{{%color_pantone}}`
 */
class m200325_101211_create_model_var_baski_colors extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_var_baski_colors}}', [
            'id' => $this->primaryKey(),
            'model_var_baski_id' => $this->integer(),
            'color_pantone_id' => $this->integer(),
            'is_main' => $this->boolean(),
            'add_info' => $this->string(),
        ]);

        // creates index for column `model_var_baski_id`
        $this->createIndex(
            '{{%idx-model_var_baski_colors-model_var_baski_id}}',
            '{{%model_var_baski_colors}}',
            'model_var_baski_id'
        );

        // add foreign key for table `{{%model_var_baski}}`
        $this->addForeignKey(
            '{{%fk-model_var_baski_colors-model_var_baski_id}}',
            '{{%model_var_baski_colors}}',
            'model_var_baski_id',
            '{{%model_var_baski}}',
            'id',
            'CASCADE'
        );

        // creates index for column `color_pantone_id`
        $this->createIndex(
            '{{%idx-model_var_baski_colors-color_pantone_id}}',
            '{{%model_var_baski_colors}}',
            'color_pantone_id'
        );

        // add foreign key for table `{{%color_pantone}}`
        $this->addForeignKey(
            '{{%fk-model_var_baski_colors-color_pantone_id}}',
            '{{%model_var_baski_colors}}',
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
        // drops foreign key for table `{{%model_var_baski}}`
        $this->dropForeignKey(
            '{{%fk-model_var_baski_colors-model_var_baski_id}}',
            '{{%model_var_baski_colors}}'
        );

        // drops index for column `model_var_baski_id`
        $this->dropIndex(
            '{{%idx-model_var_baski_colors-model_var_baski_id}}',
            '{{%model_var_baski_colors}}'
        );

        // drops foreign key for table `{{%color_pantone}}`
        $this->dropForeignKey(
            '{{%fk-model_var_baski_colors-color_pantone_id}}',
            '{{%model_var_baski_colors}}'
        );

        // drops index for column `color_pantone_id`
        $this->dropIndex(
            '{{%idx-model_var_baski_colors-color_pantone_id}}',
            '{{%model_var_baski_colors}}'
        );

        $this->dropTable('{{%model_var_baski_colors}}');
    }
}
