<?php


namespace app\models;
use app\components\CustomTimestampBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

class BaseModel extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NOACTIVE = 2;
    const STATUS_SAVED = 3;

    public function behaviors()
    {
        return [
            [
                'class' => CustomTimestampBehavior::class,
                'updatedByAtAttribute' => 'updated_by'
            ],
            [
                'class' => TimestampBehavior::class,
            ]
        ];
    }

    public function afterValidate()
    {
        if($this->hasErrors()){
            $res = [
                'status' => 'error',
                'table' => self::tableName() ?? '',
                'url' => \yii\helpers\Url::current([], true),
                'data' => $this->toArray(),
                'message' => $this->getErrors(),
            ];
            \Yii::error($res, 'save');
        }
    }

}