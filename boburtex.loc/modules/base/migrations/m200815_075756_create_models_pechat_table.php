<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_pechat}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 * - `{{%attachments}}`
 */
class m200815_075756_create_models_pechat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%models_pechat}}', [
            'id' => $this->primaryKey(),
            'models_list_id' => $this->integer(),
            'models_var_id' => $this->integer(),
            'title' => $this->char(255),
            'content' => $this->text(),
            'attachments_id' => $this->integer(),
        ]);

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-models_pechat-models_list_id}}',
            '{{%models_pechat}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-models_pechat-models_list_id}}',
            '{{%models_pechat}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'CASCADE'
        );

        // creates index for column `models_var_id`
        $this->createIndex(
            '{{%idx-models_pechat-models_var_id}}',
            '{{%models_pechat}}',
            'models_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-models_pechat-models_var_id}}',
            '{{%models_pechat}}',
            'models_var_id',
            '{{%models_variations}}',
            'id',
            'CASCADE'
        );

        // creates index for column `attachments_id`
        $this->createIndex(
            '{{%idx-models_pechat-attachments_id}}',
            '{{%models_pechat}}',
            'attachments_id'
        );

        // add foreign key for table `{{%attachments}}`
        $this->addForeignKey(
            '{{%fk-models_pechat-attachments_id}}',
            '{{%models_pechat}}',
            'attachments_id',
            '{{%attachments}}',
            'id',
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
            '{{%fk-models_pechat-models_list_id}}',
            '{{%models_pechat}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-models_pechat-models_list_id}}',
            '{{%models_pechat}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-models_pechat-models_var_id}}',
            '{{%models_pechat}}'
        );

        // drops index for column `models_var_id`
        $this->dropIndex(
            '{{%idx-models_pechat-models_var_id}}',
            '{{%models_pechat}}'
        );

        // drops foreign key for table `{{%attachments}}`
        $this->dropForeignKey(
            '{{%fk-models_pechat-attachments_id}}',
            '{{%models_pechat}}'
        );

        // drops index for column `attachments_id`
        $this->dropIndex(
            '{{%idx-models_pechat-attachments_id}}',
            '{{%models_pechat}}'
        );

        $this->dropTable('{{%models_pechat}}');
    }
}
