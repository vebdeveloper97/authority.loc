<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%brend}}`.
 */
class m191104_091236_create_brend_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%brend}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'full_name' => $this->string(),
            'code' => $this->string(30),
            'image' => $this->string(),
            'token' => $this->string(30),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%brend}}');
    }
}
