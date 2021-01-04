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
class ChangeModelForm extends Model
{
    public $model_id;
    public $model_var_id;
    public $model_no;
    public $model_name;
    public $color_pantone_id;
    public $price;
    public $pb_id;
    public $order_id;
    public $order_item_id;
    public $color_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['model_var_id', 'model_id','model_no','model_name','price'], 'safe'],
            [['color_pantone_id','order_id','order_item_id','pb_id','color_id'],'integer'],
            [['model_id','model_var_id'],'required']
        ];
    }
    public function attributeLabels() {
        return [
            'model_id' => Yii::t('app','Yangi model'),
            'model_var_id' => Yii::t('app','Yangi rangi'),
            'color_pantone_id' => Yii::t('app','Panton rang kodi'),
        ];
    }

    public function getColorPantone(){
        $cp = ColorPantone::find()->where(['status' => 1])->asArray()->all();
        return ArrayHelper::map($cp,'id','code');
    }

}
