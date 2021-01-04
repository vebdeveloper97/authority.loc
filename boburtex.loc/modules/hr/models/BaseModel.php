<?php


namespace app\modules\hr\models;

use app\components\OurCustomBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

class BaseModel extends ActiveRecord
{
    const STATUS_ACTIVE     = 1;
    const STATUS_INACTIVE   = 2;
    const STATUS_SAVED      = 3;
    const STATUS_DELETED    = 4;

    const EMPLOYEE_AVATAR_TYPE = 2;
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
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
                'message' => $this->getErrors(),
            ];
            Yii::error($res, 'save');
        }
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Deleted'),
            self::STATUS_SAVED => Yii::t('app','Saved')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    public static function getRegionTypeList($key = null)
    {
        $result = [
            1   => Yii::t('app','Xorij'),
            2 => Yii::t('app','Respublika ichida'),

        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

}