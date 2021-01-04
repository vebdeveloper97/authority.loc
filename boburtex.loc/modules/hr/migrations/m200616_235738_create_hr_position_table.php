<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_position}}`.
 */
class m200616_235738_create_hr_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_position}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'status' => $this->smallInteger(),
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
        $this->dropTable('{{%hr_position}}');
    }
}
