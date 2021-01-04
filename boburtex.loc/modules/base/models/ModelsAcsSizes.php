<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%models_acs_sizes}}".
 *
 * @property int $id
 * @property int $models_acs_id
 * @property int $size_id
 *
 * @property ModelsAcs $modelsAcs
 * @property Size $size
 */
class ModelsAcsSizes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%models_acs_sizes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_acs_id', 'size_id'], 'integer'],
            [['models_acs_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsAcs::className(), 'targetAttribute' => ['models_acs_id' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'models_acs_id' => Yii::t('app', 'Models Acs ID'),
            'size_id' => Yii::t('app', 'Size ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsAcs()
    {
        return $this->hasOne(ModelsAcs::className(), ['id' => 'models_acs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }
}
