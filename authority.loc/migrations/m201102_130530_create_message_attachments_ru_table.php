<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message_attachments_ru}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%attachments}}`
 * - `{{%message}}`
 */
class m201102_130530_create_message_attachments_ru_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message_attachments_ru}}', [
            'id' => $this->primaryKey(),
            'attachments_id' => $this->integer(),
            'message_id' => $this->integer(),
        ]);

        // creates index for column `attachments_id`
        $this->createIndex(
            '{{%idx-message_attachments_ru-attachments_id}}',
            '{{%message_attachments_ru}}',
            'attachments_id'
        );

        // add foreign key for table `{{%attachments}}`
        $this->addForeignKey(
            '{{%fk-message_attachments_ru-attachments_id}}',
            '{{%message_attachments_ru}}',
            'attachments_id',
            '{{%attachments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `message_id`
        $this->createIndex(
            '{{%idx-message_attachments_ru-message_id}}',
            '{{%message_attachments_ru}}',
            'message_id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%attachments}}`
        $this->dropForeignKey(
            '{{%fk-message_attachments_ru-attachments_id}}',
            '{{%message_attachments_ru}}'
        );

        // drops index for column `attachments_id`
        $this->dropIndex(
            '{{%idx-message_attachments_ru-attachments_id}}',
            '{{%message_attachments_ru}}'
        );


        // drops index for column `message_id`
        $this->dropIndex(
            '{{%idx-message_attachments_ru-message_id}}',
            '{{%message_attachments_ru}}'
        );

        $this->dropTable('{{%message_attachments_ru}}');
    }
}
