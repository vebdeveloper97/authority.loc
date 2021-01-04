<?php

namespace common\modules\rabbitmq\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%rabbitmq_queue}}".
 *
 * @property int               $id
 * @property string|null       $name
 *
 * @property RabbitmqConsume[] $rabbitmqConsumes
 */
class RabbitMQQueue extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%rabbitmq_queue}}';
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
        return $this->hasMany(RabbitmqConsume::class, ['queue_id' => 'id']);
    }

    public static function list(): array
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }
}
