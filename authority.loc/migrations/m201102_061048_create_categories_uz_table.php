<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%categories_uz}}`.
 */
class m201102_061048_create_categories_uz_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%categories_uz}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
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
        $this->dropTable('{{%categories_uz}}');
    }
}
