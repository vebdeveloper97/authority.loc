<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_variation_colors}}`.
 */
class m190905_155453_create_models_variation_colors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%models_variation_colors}}', [
            'id' => $this->primaryKey(),
            'model_var_id' => $this->integer(),
            'color_pantone_id' => $this->integer(),
            'is_main' => $this->boolean(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ],$tableOptions);

        //model_var_id
        $this->createIndex(
            'idx-models_variation_colors-model_var_id',
            'models_variation_colors',
            'model_var_id'
        );

        $this->addForeignKey(
            'fk-models_variation_colors-model_var_id',
            'models_variation_colors',
            'model_var_id',
            'models_variations',
            'id'
        );

        //color_pantone_id
        $this->createIndex(
            'idx-models_variation_colors-color_pantone_id',
            'models_variation_colors',
            'color_pantone_id'
        );

        $this->addForeignKey(
            'fk-models_variation_colors-color_pantone_id',
            'models_variation_colors',
            'color_pantone_id',
            'color_pantone',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //model_var_id
        $this->dropForeignKey(
            'fk-models_variation_colors-model_var_id',
            'models_variation_colors'
        );

        $this->dropIndex(
            'idx-models_variation_colors-model_var_id',
            'models_variation_colors'
        );

        //model_list_id
        $this->dropForeignKey(
            'fk-models_variation_colors-color_pantone_id',
            'models_variation_colors'
        );

        $this->dropIndex(
            'idx-models_variation_colors-color_pantone_id',
            'models_variation_colors'
        );
        $this->dropTable('{{%models_variation_colors}}');
    }
}
