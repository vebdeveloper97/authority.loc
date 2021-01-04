<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rabbit_exchange}}`.
 */
class m200904_173256_create_rabbitmq_exchange_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rabbitmq_exchange}}', [
            'id'   => $this->primaryKey(),
            'name' => $this->string()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rabbitmq_exchange}}');
    }
}
