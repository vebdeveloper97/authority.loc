<?php

namespace app\modules\tikuv\models;

use app\modules\boyoq\models\ColorPantone;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ClearNastelForm extends Model
{
    public $nastel_no;
    public $topp_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['nastel_no'], 'safe'],
            [['topp_id'], 'integer'],
        ];
    }
    public function attributeLabels() {
        return [
            'nastel_no' => Yii::t('app','Nastel №'),
            'topp_id' => Yii::t('app','Hujjat ID raqami'),
        ];
    }
}
