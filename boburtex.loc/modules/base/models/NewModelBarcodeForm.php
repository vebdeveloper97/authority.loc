<?php

namespace app\modules\base\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * LoginForm is the model behind the login form.
 *
 *
 * @property mixed $modelList
 * @property mixed $sizeCollection
 */
class NewModelBarcodeForm extends Model
{
    public $model;
    public $model_var;
    public $size;
    public $color;
    public $article;
    public $name;
    public $code;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
           [['model','model_var','color'],'integer'],
           [['size','article','name','code'],'safe'],
           [['model','model_var','size'],'required']
        ];
    }
    public function attributeLabels() {
        return [
            'model' => Yii::t('app','Model'),
            'model_var' => Yii::t('app','Model rangi'),
            'size' => Yii::t('app','Size Collection'),
        ];
    }

    public function getModelList(){
        $models = ModelsList::find()->where(['<>','id', 2])->asArray()->all();
        return ArrayHelper::map($models,'id','article');
    }

    public function getSizeCollection(){
        $sizeCollection = SizeCollections::find()->asArray()->all();
        return ArrayHelper::map($sizeCollection,'id','name');
    }

}
