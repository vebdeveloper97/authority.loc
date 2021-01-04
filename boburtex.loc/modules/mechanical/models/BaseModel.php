<?php


namespace app\modules\mechanical\models;

use app\components\OurCustomBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

class BaseModel extends ActiveRecord
{
    const STATUS_ACTIVE     = 1;
    const STATUS_INACTIVE   = 2;
    const STATUS_ENDED      = 3; // ish tugatilgandan keyin qaytib o'zgartirib bo'lmaydigan holat
    const STATUS_DELETED    = 4;

    const d = 1;
    const w = 2;
    const m = 3;
    const Y = 4;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::class,
                'updatedByAttribute' => 'updated_by',
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
            self::STATUS_INACTIVE => Yii::t('app','Inactive'),
            self::STATUS_ENDED => Yii::t('app','Ended'),
            self::STATUS_DELETED => Yii::t('app','Deleted')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getDateTypeList($key = null){
        $result = [
            self::d => Yii::t('app','Kun'),
            self::w => Yii::t('app','Hafta'),
            self::m => Yii::t('app','Oy'),
            self::Y => Yii::t('app','Yil')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }




}
