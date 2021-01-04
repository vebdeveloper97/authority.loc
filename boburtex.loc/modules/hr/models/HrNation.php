<?php

namespace app\modules\hr\models;

use Yii;
use app\modules\hr\models\BaseModel;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "hr_nation".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class HrNation extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_nation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app','ID'),
            'name' => Yii::t('app','Name'),
            'status' => Yii::t('app','Status'),
            'created_by' => Yii::t('app','Created By'),
            'updated_by' => Yii::t('app','Updated By'),
            'created_at' => Yii::t('app','Created At'),
            'updated_at' => Yii::t('app','Updated At'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['hr_nation_id' => 'id']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getNationList($key = null){
        if(!is_null($key)){
            $nation = self::find()->where(['id' => $key])->asArray()->one();
            return $nation['name'];
        }else{
            $nations = self::find()->asArray()->all();
            return ArrayHelper::map($nations,'id','name');
        }
    }
}
