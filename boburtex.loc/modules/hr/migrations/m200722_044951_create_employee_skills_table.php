<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_skills}}`.
 */
class m200722_044951_create_employee_skills_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_skills}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->unique(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee_skills}}');
    }
}
