<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%about_uz}}`.
 */
class m201110_171612_create_about_uz_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%about_uz}}', [
            'id' => $this->primaryKey(),
            'address' => $this->char(100)->notNull(),
            'phone' => $this->char(25)->notNull(),
            'work_hous' => $this->char(100),
            'email' => $this->char(50),
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
        $this->dropTable('{{%about_uz}}');
    }
}
