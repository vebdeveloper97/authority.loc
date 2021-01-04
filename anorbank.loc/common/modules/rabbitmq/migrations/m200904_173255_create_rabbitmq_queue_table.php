<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rabbit_queue}}`.
 */
class m200904_173255_create_rabbitmq_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rabbitmq_queue}}', [
            'id'   => $this->primaryKey(),
            'name' => $this->string()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rabbitmq_queue}}');
    }
}
