<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rabbit_connection}}`.
 */
class m200904_173237_create_rabbitmq_connection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rabbitmq_connection}}', [
            'id'                  => $this->primaryKey(),
            'host'                => $this->string()->notNull(),
            'port'                => $this->string(10)->notNull(),
            'user'                => $this->string()->notNull(),
            'password'            => $this->string()->notNull(),
            'vhost'               => $this->string()->defaultValue('/'),
            'insist'              => $this->boolean()->defaultValue(false),
            'login_method'        => $this->string()->defaultValue('AMQPLAIN'),
            'login_response'      => $this->string(),
            'locale'              => $this->string()->defaultValue('en_US'),
            'connection_timeout'  => $this->float()->defaultValue(3.0),
            'read_write_timeout'  => $this->float()->defaultValue(3.0),
            'context'             => $this->string(),
            'keepalive'           => $this->boolean()->defaultValue(false),
            'heartbeat'           => $this->integer()->defaultValue(0),
            'channel_rpc_timeout' => $this->float()->defaultValue(0.0),
            'ssl_protocol'        => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rabbitmq_connection}}');
    }
}
