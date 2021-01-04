<?php


namespace app\models;

use app\components\CustomBehaviors;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    const STATUS_ACTIVE   = 1;
    const STATUS_NOACTIVE = 2;
    const STATUS_SAVED    = 3;

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            CustomBehaviors::class,
        ];
    }

    public function beforeSave($insert)
    {
        $load_year = date('Y');
        $load_month = date('m');
        $load_date = date('date');
        $path = $load_year.'/'.$load_month.'/'.$load_date.'/error.log';

        if(parent::beforeSave($insert)){
            if($this->isNewRecord){
                if($this->hasAttribute('status')){
                    $this->status = self::STATUS_ACTIVE;
                }
                if($this->hasAttribute('password')){
                    $this->password = Yii::$app->security->generatePasswordHash($this->password);
                }
                if($this->hasAttribute('access_token')){
                    $this->access_token = Yii::$app->security->generateRandomString(100);
                }
            }
            return true;
        }else{
            return false;
        }
    }
}