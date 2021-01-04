<?php

namespace common\modules\rabbitmq\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%rabbitmq_connection}}".
 *
 * @property int               $id
 * @property string            $host
 * @property string            $port
 * @property string            $user
 * @property string            $password
 * @property string|null       $vhost
 * @property bool|null         $insist
 * @property string|null       $login_method
 * @property string|null       $login_response
 * @property string|null       $locale
 * @property float|null        $connection_timeout
 * @property float|null        $read_write_timeout
 * @property string|null       $context
 * @property bool|null         $keepalive
 * @property int|null          $heartbeat
 * @property float|null        $channel_rpc_timeout
 * @property string|null       $ssl_protocol
 *
 * @property RabbitmqConsume[] $rabbitmqConsumes
 */
class RabbitMQConnection extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%rabbitmq_connection}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['host', 'port', 'user', 'password'], 'required'],
            [['insist', 'keepalive'], 'boolean'],
            [['connection_timeout', 'read_write_timeout', 'channel_rpc_timeout'], 'number'],
            [['heartbeat'], 'default', 'value' => null],
            [['heartbeat'], 'integer'],
            [['host', 'user', 'password', 'vhost', 'login_method', 'login_response', 'locale', 'context', 'ssl_protocol'], 'string', 'max' => 255],
            [['port'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'                  => 'ID',
            'host'                => 'Host',
            'port'                => 'Port',
            'user'                => 'User',
            'password'            => 'Password',
            'vhost'               => 'Vhost',
            'insist'              => 'Insist',
            'login_method'        => 'Login Method',
            'login_response'      => 'Login Response',
            'locale'              => 'Locale',
            'connection_timeout'  => 'Connection Timeout',
            'read_write_timeout'  => 'Read Write Timeout',
            'context'             => 'Context',
            'keepalive'           => 'Keepalive',
            'heartbeat'           => 'Heartbeat',
            'channel_rpc_timeout' => 'Channel Rpc Timeout',
            'ssl_protocol'        => 'Ssl Protocol',
        ];
    }

    /**
     * Gets query for [[RabbitmqConsumes]].
     *
     * @return ActiveQuery
     */
    public function getRabbitmqConsumes(): ActiveQuery
    {
        return $this->hasMany(RabbitmqConsume::class, ['connection_id' => 'id']);
    }

    public static function list(): array
    {
        return ArrayHelper::map(self::find()->select(['id', 'host'])->asArray()->all(), 'id', 'host');
    }
}
