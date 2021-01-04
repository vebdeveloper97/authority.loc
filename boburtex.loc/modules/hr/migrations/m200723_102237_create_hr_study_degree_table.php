<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_study_degree}}`.
 */
class m200723_102237_create_hr_study_degree_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_study_degree}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
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
        $this->dropTable('{{%hr_study_degree}}');
    }
}
