<?php

namespace app\modules\base\models;

use app\modules\boyoq\models\Color;
use app\modules\boyoq\models\ColorPantone;
use app\modules\toquv\models\ToquvRawMaterials;
use Yii;

/**
 * This is the model class for table "model_variation_parts".
 *
 * @property int $id
 * @property int $model_list_id
 * @property int $model_var_id
 * @property int $color_pantone_id
 * @property int $raw_material_id
 * @property int $boyoqhona_color_id
 * @property int $base_pattern_part_id
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BasePatternPart $basePatternPart
 * @property Color $boyoqhonaColor
 * @property ColorPantone $colorPantone
 * @property ModelsList $modelList
 * @property ModelsVariations $modelVar
 * @property ToquvRawMaterials $rawMaterial
 */
class ModelVariationParts extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_variation_parts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_list_id', 'model_var_id', 'color_pantone_id', 'raw_material_id', 'boyoqhona_color_id', 'base_pattern_part_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['base_pattern_part_id'], 'exist', 'skipOnError' => true, 'targetClass' => BasePatternPart::className(), 'targetAttribute' => ['base_pattern_part_id' => 'id']],
            [['boyoqhona_color_id'], 'exist', 'skipOnError' => true, 'targetClass' => Color::className(), 'targetAttribute' => ['boyoqhona_color_id' => 'id']],
            [['color_pantone_id'], 'exist', 'skipOnError' => true, 'targetClass' => ColorPantone::className(), 'targetAttribute' => ['color_pantone_id' => 'id']],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
            [['raw_material_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['raw_material_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_list_id' => Yii::t('app', 'Model List ID'),
            'model_var_id' => Yii::t('app', 'Model Var ID'),
            'color_pantone_id' => Yii::t('app', 'Color Pantone ID'),
            'raw_material_id' => Yii::t('app', 'Raw Material ID'),
            'boyoqhona_color_id' => Yii::t('app', 'Boyoqhona Color ID'),
            'base_pattern_part_id' => Yii::t('app', 'Base Pattern Part ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatternPart()
    {
        return $this->hasOne(BasePatternPart::className(), ['id' => 'base_pattern_part_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBoyoqhonaColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'boyoqhona_color_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorPantone()
    {
        return $this->hasOne(ColorPantone::className(), ['id' => 'color_pantone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_var_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRawMaterial()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'raw_material_id']);
    }
}
