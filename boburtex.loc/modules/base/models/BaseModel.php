<?php

namespace app\modules\base\models;

use app\modules\bichuv\models\BichuvAcsAttachment;
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
    const STATUS_ACTIVE             = 1;
    const STATUS_INACTIVE           = 2;
    const STATUS_SAVED              = 3;
    const STATUS_PLANNED            = 4;
    const STATUS_PLANNED_TOQUV      = 5;
    const STATUS_PLANNED_TOQUV_AKS  = 6;
    const STATUS_MODEL_ROOM         = 8;
    const STATUS_MARKETING          = 9;

    /** Bo'limlar uchun Token */
    const TOKEN_PLAN      = "PLAN";
    const TOKEN_KONSTRUKT = "KONSTRUKTOR";
    const TOKEN_MODELXONA = "MODEL_XONA";
    const TOKEN_MARKETING = "MARKETING";

    /** Typelar uchun notification*/
    const TYPE_MARKETING = 1;
    const TYPE_MODELXONA = 2;
    const TYPE_PLAN      = 3;
    const TYPE_KONSTRUKT = 4;


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
                'table' => self::tableName() ?? '',
                'url' => \yii\helpers\Url::current([], true),
                'data' => $this->toArray(),
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
            self::STATUS_PLANNED => Yii::t('app','Planned'),
        ];
        if(!empty($key)){
            return $result[$key];
        }

        return $result;
    }

    public function uploadBase64($folder, $imageFile)
    {
        if ($imageFile) {
            $img = $imageFile;
            $img = explode(',', $img);
            $data = base64_decode($img[1]);
            $ini = substr($img[0], 11);
            $type = explode(';', $ini)[0];
            switch ($type){
                case 'jpeg':
                case 'gif':
                case 'jpg':
                case 'png':
                case 'bmp':
                case 'jfif':
                    break;
                default:
                    return false;
            }
            $directory = 'uploads/' . $folder . '/' . $type;
            if (!is_dir($directory)) {
                \yii\helpers\FileHelper::createDirectory($directory);
            }
            $uid = uniqid(date('d.m.Y-H.i.s-'));
            $fileName = $uid . '.' . $type;
            $filePath = $directory . '/' . $fileName;
            if ($success = file_put_contents($filePath, $data)) {
                if ($success) {
                    $path = '/web/uploads/' . $folder . '/' . $type . '/' . $fileName;
                    return $path;
                }
            }
        }
        return false;
    }

}
