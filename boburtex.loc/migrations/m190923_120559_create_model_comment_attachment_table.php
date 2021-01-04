<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_comment_attachment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 */
class m190923_120559_create_model_comment_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_comment_attachment}}', [
            'id' => $this->primaryKey(),
            'models_list_id' => $this->integer(),
            'name' => $this->string(),
            'size' => $this->integer(),
            'extension' => $this->string(10),
            'type' => $this->string(120),
            'path' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-model_comment_attachment-models_list_id}}',
            '{{%model_comment_attachment}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-model_comment_attachment-models_list_id}}',
            '{{%model_comment_attachment}}',
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
            '{{%fk-model_comment_attachment-models_list_id}}',
            '{{%model_comment_attachment}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-model_comment_attachment-models_list_id}}',
            '{{%model_comment_attachment}}'
        );

        $this->dropTable('{{%model_comment_attachment}}');
    }
}
