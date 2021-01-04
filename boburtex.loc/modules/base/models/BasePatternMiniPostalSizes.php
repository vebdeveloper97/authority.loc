<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%base_pattern_mini_postal_sizes}}".
 *
 * @property int $id
 * @property int $base_pattern_mini_postal_id
 * @property int $size_id
 *
 * @property BasePatternMiniPostal $basePatternMiniPostal
 */
class BasePatternMiniPostalSizes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_pattern_mini_postal_sizes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['base_pattern_mini_postal_id', 'size_id'], 'integer'],
            [['base_pattern_mini_postal_id'], 'exist', 'skipOnError' => true, 'targetClass' => BasePatternMiniPostal::className(), 'targetAttribute' => ['base_pattern_mini_postal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'base_pattern_mini_postal_id' => Yii::t('app', 'Base Pattern Mini Postal ID'),
            'size_id' => Yii::t('app', 'Size ID'),
        ];
    }

    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatternMiniPostal()
    {
        return $this->hasOne(BasePatternMiniPostal::className(), ['id' => 'base_pattern_mini_postal_id']);
    }
}
