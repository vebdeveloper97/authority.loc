<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%model_mini_postal_sizes}}".
 *
 * @property int $id
 * @property int $model_mini_postal_id
 * @property int $size_id
 * @property int $count
 * @property int $count_detail Detallar soni
 *
 * @property ModelMiniPostal $modelMiniPostal
 */
class ModelMiniPostalSizes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_mini_postal_sizes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_mini_postal_id', 'size_id', 'count', 'count_detail'], 'integer'],
            [['model_mini_postal_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelMiniPostal::className(), 'targetAttribute' => ['model_mini_postal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_mini_postal_id' => Yii::t('app', 'Model Mini Postal ID'),
            'size_id' => Yii::t('app', 'Size ID'),
            'count' => Yii::t('app', 'Count'),
            'count_detail' => Yii::t('app', 'Detallar soni'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelMiniPostal()
    {
        return $this->hasOne(ModelMiniPostal::className(), ['id' => 'model_mini_postal_id']);
    }
}
