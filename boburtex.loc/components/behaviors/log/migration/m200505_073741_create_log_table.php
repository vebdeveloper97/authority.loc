<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%change_log}}`.
 */
class m200505_073741_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%change_log}}', [
            'id' => $this->primaryKey(),
            'old_attributes' => $this->text(),
            'new_attributes' => $this->text(),
            'user_id' => $this->integer(),
            'user_name' => $this->string(),
            'user_login' => $this->string(),
            'table' => $this->string(),
            'event' => $this->string(),
            'object' => $this->string(),
            'date' => $this->dateTime(),
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%change_log}}');
    }
}
