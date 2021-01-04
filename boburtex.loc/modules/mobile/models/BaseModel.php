<?php
/**
 * Created By PhpStorm
 * User Omadbek Onorov
 * Time: 12.05.2020, 21:42
 */

namespace app\modules\mobile\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\OurCustomBehavior;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;

/**
 * Class BaseModel
 * @package app\modules\toquv\models
 */
class BaseModel extends ActiveRecord
{

    const STATUS_ACTIVE     = 1;
    const STATUS_INACTIVE   = 2;
    const STATUS_SAVED      = 3;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by'
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
                'module' => 'mobile',
                'table' => self::tableName() ?? '',
                'url' => \yii\helpers\Url::current([], true),
                'message' => $this->getErrors(),
            ];
            Yii::error($res, 'save');
            $list = [];
            foreach ($this->getErrors() as $key => $error) {
                Yii::$app->session->setFlash('error '.$key,$error[0]);
                $list[$key]['error'] = $error[0];
                $list[$key]['model'] = $this->formName();
            }
            Yii::$app->controller->view->registerJsVar('list_errors',$list);
        }
    }
    public static function getModelName()
    {
        return StringHelper::basename(get_class(new self()));
    }
    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Inactive'),
            self::STATUS_SAVED => Yii::t('app','Saved'),
        ];
        if(!empty($key)){
            return $result[$key];
        }

        return $result;
    }

    /**
     * @param $provider
     * @param $fieldName
     * @return int
     */

    public static function getTotal($provider, $fieldName)
    {
        $total = 0;
        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }
        return $total;
    }
}
