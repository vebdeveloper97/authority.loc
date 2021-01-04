<?php


namespace app\modules\tikuv\models;

use app\modules\admin\models\ToquvUserDepartment;
use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\OurCustomBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class BaseModel extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_SAVED = 3;
    const STATUS_ACCEPTED = 4;
    const STATUS_CENCALED = 5;

    const TYPE_ACCEPTED = 1;
    const TYPE_CENCALLED = 2;
    const TYPE_ACCEPTEDRESTART = 3;

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
                'module' => 'Tikuv',
                'table' => self::tableName() ?? '',
                'url' => \yii\helpers\Url::current([], true),
                'message' => $this->getErrors(),
                'data' => $this->toArray(),
            ];
            Yii::error($res, 'save');
        }
    }
    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null)
    {
        $result = [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app', 'Faol emas'),
            self::STATUS_SAVED => Yii::t('app', 'Saved'),
            self::STATUS_ACCEPTED => Yii::t('app', 'Qabul qilingan'),
        ];
        if (!empty($key)) {
            return $result[$key];
        }

        return $result;
    }


    /**
     * @param $user_id
     * @param null $type
     * @param bool $isMultiple
     * @return array|null
     */
    public function getUserDepartmentByUserId($user_id, $type = null, $isMultiple = false)
    {
        if ( is_null($type) ) {
            $type = ToquvUserDepartment::OWN_DEPARTMENT_TYPE;
        }
        if ($user_id) {
            $result = ToquvUserDepartment::find()
                ->select(['td.id', 'td.name'])
                ->from('toquv_user_department tud')
                ->innerJoin('toquv_departments td', '`td`.`id` = `tud`.`department_id`')
                ->where(['tud.user_id' => $user_id, 'tud.type' => $type])
                ->asArray()
                ->all();

            if (!empty($result)) {
                return ArrayHelper::map($result, 'id', 'name');
            }
        }
        return [];
    }
}