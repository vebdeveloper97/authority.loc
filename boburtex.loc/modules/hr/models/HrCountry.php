<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "hr_country".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property HrServices[] $hrServices
 */
class HrCountry extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
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
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrServices()
    {
        return $this->hasMany(HrServices::className(), ['hr_country_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getListMap()
    {
        $list = static::find()->where(['status'=>self::STATUS_ACTIVE])->asArray()->all();
        return ArrayHelper::map($list,'id', 'name');
    }

    public static function getListItem($key){
        return self::getListMap()[$key];
    }
}
