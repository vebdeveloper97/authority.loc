<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rabbitmq_consume}}`.
 */
class m200904_185457_create_rabbitmq_consume_table extends Migration
{
    public const TABLE_NAME = '{{%rabbitmq_consume}}';

    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id'               => $this->primaryKey(),
            'tag'              => $this->string()->notNull(),
            'connection_id'    => $this->integer()->notNull(),
            'queue_id'         => $this->integer()->notNull(),
            'exchange_id'      => $this->integer()->notNull(),
            'queue_declare'    => $this->json(),
            'exchange_declare' => $this->json(),
            'no_local'         => $this->boolean()->defaultValue(false),
            'no_ack'           => $this->boolean()->defaultValue(false),
            'exclusive'        => $this->boolean()->defaultValue(false),
            'nowait'           => $this->boolean()->defaultValue(false),
            'callback'         => $this->string(),
            'ticket'           => $this->integer(),
            'arguments'        => 'text[]',
        ]);

        $this->addForeignKey(
            'fk-rabbitmq_consume-connection_id',
            self::TABLE_NAME,
            'connection_id',
            '{{%rabbitmq_connection}}',
            'id'
        );

        $this->addForeignKey(
            'fk-rabbitmq_consume-queue_id',
            self::TABLE_NAME,
            'queue_id',
            '{{%rabbitmq_queue}}',
            'id'
        );

        $this->addForeignKey(
            'fk-rabbitmq_consume-exchange_id',
            self::TABLE_NAME,
            'exchange_id',
            '{{%rabbitmq_exchange}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-rabbitmq_consume-connection_id', self::TABLE_NAME);
        $this->dropForeignKey('fk-rabbitmq_consume-queue_id', self::TABLE_NAME);
        $this->dropForeignKey('fk-rabbitmq_consume-exchange_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
