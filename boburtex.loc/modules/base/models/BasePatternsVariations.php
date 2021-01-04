<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "base_patterns_variations".
 *
 * @property int $id
 * @property int $base_patterns_id
 * @property int $variant_no
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BasePatternItems[] $basePatternItems
 * @property BasePatterns $basePatterns
 */
class BasePatternsVariations extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_patterns_variations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['base_patterns_id', 'variant_no', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['base_patterns_id'], 'exist', 'skipOnError' => true, 'targetClass' => BasePatterns::className(), 'targetAttribute' => ['base_patterns_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'base_patterns_id' => Yii::t('app', 'Base Patterns ID'),
            'variant_no' => Yii::t('app', 'Variant No'),
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
    public function getBasePatternItems()
    {
        return $this->hasMany(BasePatternItems::className(), ['base_patterns_variant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatterns()
    {
        return $this->hasOne(BasePatterns::className(), ['id' => 'base_patterns_id']);
    }
}
