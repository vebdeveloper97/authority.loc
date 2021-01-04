<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_departments}}`.
 */
class m200615_125827_create_hr_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_departments}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->bigInteger(),
            'type' => $this->smallInteger(),
        ]);

        $this->addForeignKey(
            'fk-hr_departments-created_by',
            '{{%hr_departments}}',
            'created_by',
            '{{%users}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%hr_departments}}');
    }
}
