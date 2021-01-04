<?php

namespace common\modules\rabbitmq\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\ArrayExpression;
use PhpAmqpLib\Exchange\AMQPExchangeType;

/**
 * This is the model class for table "{{%rabbitmq_consume}}".
 *
 * @property int                $id
 * @property string             $tag
 * @property int                $connection_id
 * @property int                $queue_id
 * @property int                $exchange_id
 * @property string|null        $queue_declare
 * @property string|null        $exchange_declare
 * @property bool|null          $no_local
 * @property bool|null          $no_ack
 * @property bool|null          $exclusive
 * @property bool|null          $nowait
 * @property string|null        $callback
 * @property int|null           $ticket
 * @property string|null        $arguments
 *
 * @property RabbitmqConnection $connection
 * @property RabbitmqExchange   $exchange
 * @property RabbitmqQueue      $queue
 */
class RabbitMQConsume extends ActiveRecord
{
    public bool $qd_passive = false;
    public bool $qd_durable = false;
    public bool $qd_exclusive = false;
    public bool $qd_auto_delete = true;
    public bool $qd_nowait = false;
    public array $qd_arguments = [];
    public ?int $qd_ticket = null;

    public string $ed_type = AMQPExchangeType::DIRECT;
    public bool $ed_passive = false;
    public bool $ed_durable = false;
    public bool $ed_auto_delete = true;
    public bool $ed_internal = false;
    public bool $ed_nowait = false;
    public array $ed_arguments = [];
    public ?int $ed_ticket = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%rabbitmq_consume}}';
    }

    public function beforeSave($insert)
    {
        $this->queue_declare = [
            'qd_passive'     => $this->qd_passive,
            'qd_durable'     => $this->qd_durable,
            'qd_exclusive'   => $this->qd_exclusive,
            'qd_auto_delete' => $this->qd_auto_delete,
            'qd_nowait'      => $this->qd_nowait,
            'qd_arguments'   => $this->qd_arguments,
            'qd_ticket'      => $this->qd_ticket,
        ];

        $this->exchange_declare = [
            'ed_passive'     => $this->ed_passive,
            'ed_durable'     => $this->ed_durable,
            'ed_auto_delete' => $this->ed_auto_delete,
            'ed_internal'    => $this->ed_internal,
            'ed_nowait'      => $this->ed_nowait,
            'ed_arguments'   => $this->ed_arguments,
            'ed_ticket'      => $this->ed_ticket,
        ];

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['tag', 'connection_id', 'queue_id', 'exchange_id'], 'required'],
            [['connection_id', 'queue_id', 'exchange_id', 'ticket'], 'default', 'value' => null],
            [['connection_id', 'queue_id', 'exchange_id', 'ticket', 'qd_ticket', 'ed_ticket'], 'integer'],
            [['queue_declare', 'exchange_declare'], 'safe'],
            [
                [
                    'no_local',
                    'no_ack',
                    'exclusive',
                    'nowait',
                    'qd_passive',
                    'qd_durable',
                    'qd_exclusive',
                    'qd_auto_delete',
                    'qd_nowait',
                    'ed_passive',
                    'ed_durable',
                    'ed_auto_delete',
                    'ed_internal',
                    'ed_nowait',
                ],
                'boolean',
            ],
            [['arguments', 'ed_type'], 'string'],
            [['tag', 'callback'], 'string', 'max' => 255],
            [['connection_id'], 'exist', 'skipOnError' => true, 'targetClass' => RabbitMQConnection::class, 'targetAttribute' => ['connection_id' => 'id']],
            [['exchange_id'], 'exist', 'skipOnError' => true, 'targetClass' => RabbitMQExchange::class, 'targetAttribute' => ['exchange_id' => 'id']],
            [['queue_id'], 'exist', 'skipOnError' => true, 'targetClass' => RabbitMQQueue::class, 'targetAttribute' => ['queue_id' => 'id']],
            [['qd_arguments', 'ed_arguments'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'               => 'ID',
            'tag'              => 'Tag',
            'connection_id'    => 'Connection ID',
            'queue_id'         => 'Queue ID',
            'exchange_id'      => 'Exchange ID',
            'queue_declare'    => 'Queue Declare',
            'exchange_declare' => 'Exchange Declare',
            'no_local'         => 'No Local',
            'no_ack'           => 'No Ack',
            'exclusive'        => 'Exclusive',
            'nowait'           => 'Nowait',
            'callback'         => 'Callback',
            'ticket'           => 'Ticket',
            'arguments'        => 'Arguments',
        ];
    }

    /**
     * Gets query for [[Connection]].
     *
     * @return ActiveQuery
     */
    public function getConnection(): ActiveQuery
    {
        return $this->hasOne(RabbitMQConnection::class, ['id' => 'connection_id']);
    }

    /**
     * Gets query for [[Exchange]].
     *
     * @return ActiveQuery
     */
    public function getExchange(): ActiveQuery
    {
        return $this->hasOne(RabbitMQExchange::class, ['id' => 'exchange_id']);
    }

    /**
     * Gets query for [[Queue]].
     *
     * @return ActiveQuery
     */
    public function getQueue(): ActiveQuery
    {
        return $this->hasOne(RabbitMQQueue::class, ['id' => 'queue_id']);
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->arguments = json_encode($this->arguments->getValue());
    }
}
