<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "base_standart".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $notes
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseNormStandart[] $baseNormStandarts
 */
class BaseStandart extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_standart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notes'], 'string'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
            [['code'],'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'notes' => Yii::t('app', 'Notes'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseNormStandarts()
    {
        return $this->hasMany(BaseNormStandart::className(), ['base_standart_id' => 'id']);
    }

    public static function getStandartList(){
        $standarts = self::find()->asArray()->all();
        if (!empty($standarts)){
            return $standarts;
        }else{
            return false;
        }
    }

    public static function getStandartListMap(){
        $standarts = self::getStandartList();
        if (!empty($standarts)){
            return ArrayHelper::map($standarts,'id',function ($m){
               return $m['name']." - ".$m['code'];
            });
        }else{
            return [];
        }
    }

}
