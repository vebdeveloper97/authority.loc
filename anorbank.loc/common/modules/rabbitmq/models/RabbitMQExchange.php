<?php

namespace common\modules\rabbitmq\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%rabbitmq_exchange}}".
 *
 * @property int               $id
 * @property string|null       $name
 *
 * @property RabbitmqConsume[] $rabbitmqConsumes
 */
class RabbitMQExchange extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%rabbitmq_exchange}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'   => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[RabbitmqConsumes]].
     *
     * @return ActiveQuery
     */
    public function getRabbitmqConsumes(): ActiveQuery
    {
        return $this->hasMany(RabbitmqConsume::class, ['exchange_id' => 'id']);
    }

    public static function list(): array
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }
}
