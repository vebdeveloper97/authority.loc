<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "hr_position_type".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property HrStaff[] $hrStaff
 */
class HrPositionType extends \app\modules\hr\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_position_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['created_at', 'updated_at','updated_by', 'created_by'], 'integer'],
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
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrStaff()
    {
        return $this->hasMany(HrStaff::className(), ['position_type_id' => 'id']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Inactive'),
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }
}
