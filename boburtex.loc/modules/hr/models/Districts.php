<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "districts".
 *
 * @property int $id
 * @property int $region_id
 * @property string $name
 *
 * @property HrServices[] $hrServices
 */
class Districts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'districts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region_id'], 'required'],
            [['region_id'], 'integer'],
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
            'region_id' => Yii::t('app', 'Region ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrServices()
    {
        return $this->hasMany(HrServices::className(), ['district_id' => 'id']);
    }
    /**
     * @return array
     */
    public static function getListMap()
    {
        $list = static::find()->asArray()->all();
        return ArrayHelper::map($list,'id', 'name');
    }
    public static function getListItem($key){
        return self::getListMap()[$key];
    }
}
