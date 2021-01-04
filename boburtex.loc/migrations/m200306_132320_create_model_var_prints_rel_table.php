<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_var_prints_rel}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_variations}}`
 * - `{{%model_var_prints}}`
 * - `{{%color_pantone}}`
 */
class m200306_132320_create_model_var_prints_rel_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_var_prints_rel}}', [
            'id' => $this->primaryKey(),
            'models_variations_id' => $this->integer(),
            'model_var_prints_id' => $this->integer(),
            'add_info' => $this->text()
        ]);

        // creates index for column `models_variations_id`
        $this->createIndex(
            '{{%idx-model_var_prints_rel-models_variations_id}}',
            '{{%model_var_prints_rel}}',
            'models_variations_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-model_var_prints_rel-models_variations_id}}',
            '{{%model_var_prints_rel}}',
            'models_variations_id',
            '{{%models_variations}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_var_prints_id`
        $this->createIndex(
            '{{%idx-model_var_prints_rel-model_var_prints_id}}',
            '{{%model_var_prints_rel}}',
            'model_var_prints_id'
        );

        // add foreign key for table `{{%model_var_prints}}`
        $this->addForeignKey(
            '{{%fk-model_var_prints_rel-model_var_prints_id}}',
            '{{%model_var_prints_rel}}',
            'model_var_prints_id',
            '{{%model_var_prints}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-model_var_prints_rel-models_variations_id}}',
            '{{%model_var_prints_rel}}'
        );

        // drops index for column `models_variations_id`
        $this->dropIndex(
            '{{%idx-model_var_prints_rel-models_variations_id}}',
            '{{%model_var_prints_rel}}'
        );

        // drops foreign key for table `{{%model_var_prints}}`
        $this->dropForeignKey(
            '{{%fk-model_var_prints_rel-model_var_prints_id}}',
            '{{%model_var_prints_rel}}'
        );

        // drops index for column `model_var_prints_id`
        $this->dropIndex(
            '{{%idx-model_var_prints_rel-model_var_prints_id}}',
            '{{%model_var_prints_rel}}'
        );
        $this->dropTable('{{%model_var_prints_rel}}');
    }
}
