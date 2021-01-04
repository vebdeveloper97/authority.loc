<?php

namespace app\modules\settings\models;

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
