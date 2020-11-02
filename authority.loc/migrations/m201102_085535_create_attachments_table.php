<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attachments}}`.
 */
class m201102_085535_create_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%attachments}}', [
            'id' => $this->primaryKey(),
            'name' => $this->char(100)->notnull(),
            'extension' => $this->char(50),
            'size' => $this->integer(),
            'path' => $this->char(100),
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
        $this->dropTable('{{%attachments}}');
    }
}
