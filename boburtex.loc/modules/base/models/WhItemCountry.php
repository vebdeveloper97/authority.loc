<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wh_item_country".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @property WhItems[] $whItems
 */
class WhItemCountry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wh_item_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['name', 'code'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhItems()
    {
        return $this->hasMany(WhItems::className(), ['country_id' => 'id']);
    }

    /**
     * @param null $id
     * @param bool $array
     * @return array
     */
    public static function getList($id=null,$array=false)
    {
        $list = self::find();
        if($id){
            $list = $list->where(['type_id'=>$id]);
        }
        $list = $list->asArray()->all();
        if($array){
            $res = [];
            if(!empty($array)){
                foreach ($list as $item) {
                    $res[] = [
                        'id' => $item['id'],
                        'name' => $item['name']
                    ];
                }
            }
            return $res;
        }
        return ArrayHelper::map($list,'id','name');
    }
}
