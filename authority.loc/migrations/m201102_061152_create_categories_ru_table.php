<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%categories_ru}}`.
 */
class m201102_061152_create_categories_ru_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%categories_ru}}', [
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
        $this->dropTable('{{%categories_ru}}');
    }
}
