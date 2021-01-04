<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%models_raw_materials_sizes}}".
 *
 * @property int $id
 * @property int $models_raw_materials_id
 * @property int $size_id
 *
 * @property ModelsRawMaterials $modelsRawMaterials
 * @property Size $size
 */
class ModelsRawMaterialsSizes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%models_raw_materials_sizes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_raw_materials_id', 'size_id'], 'integer'],
            [['models_raw_materials_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsRawMaterials::className(), 'targetAttribute' => ['models_raw_materials_id' => 'id']],
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
            'models_raw_materials_id' => Yii::t('app', 'Models Raw Materials ID'),
            'size_id' => Yii::t('app', 'Size ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsRawMaterials()
    {
        return $this->hasOne(ModelsRawMaterials::className(), ['id' => 'models_raw_materials_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }
}
