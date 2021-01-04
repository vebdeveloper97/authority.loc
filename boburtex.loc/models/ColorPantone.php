<?php

namespace app\models;

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
 *
 * @property Color $color
 * @property ColorPantonType $colorPantonType
 * @property ModelsVariationColors[] $modelsVariationColors
 * @property string $name_ru [varchar(255)]
 * @property string $name_uz [varchar(255)]
 * @property string $name_ml [varchar(255)]
 * @property int $status [smallint(6)]
 * @property int $created_by [int(11)]
 * @property int $created_at [int(11)]
 * @property int $updated_by [int(11)]
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
            [['r', 'g', 'b','status','created_by','created_at','updated_by','color_panton_type_id', 'color_id'], 'integer'],
            [['name','name_ru','name_uz','name_ml'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 25],
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
    public function getModelsVariationColors()
    {
        return $this->hasMany(ModelsVariationColors::className(), ['color_pantone_id' => 'id']);
    }

    /**
     * @param int $status
     * @return array
     */
    public static function getColorPantoneList($status = 1) {
        $pantones = self::find()
            ->where(['status' => $status])
            ->asArray()
            ->all();
        return ArrayHelper::map($pantones, 'id', 'name');
    }
}
