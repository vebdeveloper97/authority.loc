<?php


namespace app\modules\admin\models;
use app\components\CustomBehavior\CustomTimestampBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class BaseModel extends ActiveRecord
{
    /** /begin User table uchun */
    const ADMIN_TYPES = 1; /** admin uchun type*/
    const USER_TYPES = 2; /** oddiy foydalanuvchi uchun type*/
    /** /end User table uchun */

    /**
     * @return array
     */
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => CustomTimestampBehavior::class,
            ],
            [
                'class' => TimestampBehavior::class,
            ]
        ];
    }


}