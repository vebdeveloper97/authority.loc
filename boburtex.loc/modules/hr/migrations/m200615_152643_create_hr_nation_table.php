<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_nation}}`.
 */
class m200615_152643_create_hr_nation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_nation}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'status' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%hr_nation}}');
    }
}
