<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_employee}}`.
 */
class m200615_153037_create_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_employee}}', [
            'id' => $this->primaryKey(),
            'fish' => $this->string(50),
            'address' => $this->string(50),
            'phone' => $this->char(25),
            'birth_date' => $this->date(),
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
        $this->dropTable('{{%hr_employee}}');
    }
}
