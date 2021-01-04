<?php

namespace app\modules\boyoq\models;

use app\modules\base\models\Goods;
use app\modules\base\models\ModelsVariationColors;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\ModelVarPrintsColors;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "color_pantone".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $r
 * @property int $g
 * @property int $b
 * @property int $color_panton_type_id
 * @property int $color_id
 * @property int $status
 * @property string $name_ru
 * @property string $name_uz
 * @property string $name_ml
 *
 * @property Color $color
 * @property ColorPantonType $colorPantonType
 * @property Goods[] $goods
 * @property ModelVarPrintsColors[] $modelVarPrintsColors
 * @property ModelsVariationColors[] $modelsVariationColors
 * @property ModelsVariations[] $modelsVariations
 */
class ColorPantone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'color_pantone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['r', 'g', 'b', 'color_panton_type_id', 'color_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 25],
            [['name_ru', 'name_uz', 'name_ml'], 'string', 'max' => 255],
            [['color_id'], 'exist', 'skipOnError' => true, 'targetClass' => Color::className(), 'targetAttribute' => ['color_id' => 'id']],
            [['color_panton_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ColorPantonType::className(), 'targetAttribute' => ['color_panton_type_id' => 'id']],
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
            'code' => Yii::t('app', 'Code'),
            'r' => Yii::t('app', 'R'),
            'g' => Yii::t('app', 'G'),
            'b' => Yii::t('app', 'B'),
            'color_panton_type_id' => Yii::t('app', 'Color Panton Type ID'),
            'color_id' => Yii::t('app', 'Color ID'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'name_uz' => Yii::t('app', 'Name Uz'),
            'name_ml' => Yii::t('app', 'Name (ml)'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorPantonType()
    {
        return $this->hasOne(ColorPantonType::className(), ['id' => 'color_panton_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::className(), ['color' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPrintsColors()
    {
        return $this->hasMany(ModelVarPrintsColors::className(), ['color_pantone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsVariationColors()
    {
        return $this->hasMany(ModelsVariationColors::className(), ['color_pantone_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsVariations()
    {
        return $this->hasMany(ModelsVariations::className(), ['color_pantone_id' => 'id']);
    }

    public static function getMapList() {
        $result = static::find()
            ->select(['id', 'name', 'code'])
            ->andWhere(['status' => 1])
            ->asArray()
            ->all();

        return ArrayHelper::map($result, 'id', function($item) {
            return $item['code'] . ' | ' . $item['name'];
        });
    }
}
