<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_sketch}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 */
class m190920_135155_create_model_sketch_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_sketch}}', [
            'id' => $this->primaryKey(),
            'models_list_id' => $this->integer(),
            'name' => $this->string(),
            'size' => $this->integer(),
            'extension' => $this->string(10),
            'path' => $this->string(),
            'is_main' => $this->boolean(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-model_sketch-models_list_id}}',
            '{{%model_sketch}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-model_sketch-models_list_id}}',
            '{{%model_sketch}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-model_sketch-models_list_id}}',
            '{{%model_sketch}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-model_sketch-models_list_id}}',
            '{{%model_sketch}}'
        );

        $this->dropTable('{{%model_sketch}}');
    }
}
