<?php

namespace common\modules\request_log\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class RequestLog
 *
 * @package common\modules\request_log\models
 *
 * @property string      id
 * @property string|null session_id
 * @property string|null pair_id
 * @property string|null service
 * @property string|null date
 * @property string|null type
 * @property string|null body
 */
class RequestLog extends ActiveRecord
{
    public const TYPE_REQUEST = 'request';
    public const TYPE_RESPONSE = 'response';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%request_log}}';
    }

    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['date'],
                ],
                'value'      => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [[
                'id',
                'session_id',
                'pair_id',
                'service',
                'date',
                'type',
                'body',], 'safe'],
        ];
    }

    public static function getAllPairRequests(int $id): array
    {
        $request = self::findOne(['id' => $id]);

        if ($request === null) {
            return [];
        }

        return self::find()
            ->where(['pair_id' => $request->pair_id])
            ->orderBy(['id' => $id])
            ->all();
    }
}
