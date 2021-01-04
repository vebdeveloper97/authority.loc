<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attachments}}`.
 */
class m190905_155300_create_attachments_table extends Migration
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
        $this->createTable('{{%attachments}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            //bayt da hisoblanadi
            'size' => $this->integer(),
            'extension' => $this->string(10),
            'path' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%attachments}}');
    }
}
