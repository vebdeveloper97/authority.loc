<?php

namespace app\modules\tikuv\models;

use Yii;
use yii\base\Model;

/**
 */
class CombineNatelForm extends Model
{
    public $main_nastel_no;
    public $nastel;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['main_nastel_no','nastel'],'safe'],
            [['main_nastel_no','nastel'],'required']
        ];
    }
    public function attributeLabels() {
        return [
            'main_nastel_no' => Yii::t('app','Asosiy nastel raqami'),
            'nastel_no' => Yii::t('app','Nastel No'),
        ];
    }

}
