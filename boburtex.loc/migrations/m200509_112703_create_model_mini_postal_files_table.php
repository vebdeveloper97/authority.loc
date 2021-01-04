<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_mini_postal_files}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_mini_postal}}`
 */
class m200509_112703_create_model_mini_postal_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_mini_postal_files}}', [
            'id' => $this->primaryKey(),
            'model_mini_postal_id' => $this->integer(),
            'name' => $this->string(),
            'size' => $this->integer(),
            'extension' => $this->string(10),
            'type' => $this->string(120),
            'path' => $this->string(),
            'isMain' => $this->boolean(),
        ]);

        // creates index for column `model_mini_postal_id`
        $this->createIndex(
            '{{%idx-model_mini_postal_files-model_mini_postal_id}}',
            '{{%model_mini_postal_files}}',
            'model_mini_postal_id'
        );

        // add foreign key for table `{{%model_mini_postal}}`
        $this->addForeignKey(
            '{{%fk-model_mini_postal_files-model_mini_postal_id}}',
            '{{%model_mini_postal_files}}',
            'model_mini_postal_id',
            '{{%model_mini_postal}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_mini_postal}}`
        $this->dropForeignKey(
            '{{%fk-model_mini_postal_files-model_mini_postal_id}}',
            '{{%model_mini_postal_files}}'
        );

        // drops index for column `model_mini_postal_id`
        $this->dropIndex(
            '{{%idx-model_mini_postal_files-model_mini_postal_id}}',
            '{{%model_mini_postal_files}}'
        );

        $this->dropTable('{{%model_mini_postal_files}}');
    }
}
