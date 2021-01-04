<?php

namespace app\modules\toquv\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\OurCustomBehavior;
use yii\db\ActiveRecord;

/**
 * Class BaseModel
 * @package app\modules\toquv\models
 */
class BaseModel extends ActiveRecord
{
    const STATUS_ACTIVE     = 1;
    const STATUS_INACTIVE   = 2;
    const STATUS_SAVED      = 3;
    const STATUS_ACCEPTED   = 4;
    const STATUS_FINISHED   = 5;

    const VIRTUAL_SKLAD = 999;

    const ENTITY_TYPE_IP   = 1;
    const ENTITY_TYPE_MATO = 2;
    const ENTITY_TYPE_ACS  = 3;

    public $cp = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
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
                'module' => 'Toquv',
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
            self::STATUS_SAVED => Yii::t('app','Saved'),
        ];
        if(!empty($key)){
            return $result[$key];
        }

        return $result;
    }

    public static function getTotal($provider, $fieldName)
    {
        $total = 0;

        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }

        return $total;
    }
}
