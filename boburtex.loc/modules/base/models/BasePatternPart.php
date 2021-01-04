<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "base_pattern_part".
 *
 * @property int $id
 * @property string $name
 * @property string $token
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BasePatternItems[] $basePatternItems
 */
class BasePatternPart extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_pattern_part';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'token'], 'string', 'max' => 255],
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
            'token' => Yii::t('app', 'Token'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatternItems()
    {
        return $this->hasMany(BasePatternItems::className(), ['base_pattern_part_id' => 'id']);
    }
}
