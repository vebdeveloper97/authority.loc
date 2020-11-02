<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message_ru}}`.
 */
class m201102_065017_create_message_ru_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message_ru}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'content' => $this->text()->notNull(),
            'author' => $this->string(100)->notNull(),
            'images' => $this->char(100),
            'date' => $this->date(),
            'type' => $this->integer()->notNull(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%message_ru}}');
    }
}
