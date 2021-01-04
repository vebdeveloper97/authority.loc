<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "models_toquv_acs_sizes".
 *
 * @property int $id
 * @property int $models_toquv_acs_id
 * @property string $size_id
 */
class ModelsToquvAcsSizes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'models_toquv_acs_sizes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_toquv_acs_id'], 'integer'],
            [['size_id'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'models_toquv_acs_id' => Yii::t('app', 'Models Toquv Acs ID'),
            'size_id' => Yii::t('app', 'Size ID'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsAcs()
    {
        return $this->hasOne(ModelsToquvAcs::className(), ['id' => 'models_toquv_acs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }

}
