<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_country}}`.
 */
class m200802_083640_create_hr_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_country}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(50),
            'name' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(1),
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
        $this->dropTable('{{%hr_country}}');
    }
}
