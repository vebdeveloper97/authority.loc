<?php

namespace app\modules\base\models;

use app\modules\boyoq\models\ColorPantone;
use Yii;

/**
 * This is the model class for table "{{%model_var_baski_colors}}".
 *
 * @property int $id
 * @property int $model_var_baski_id
 * @property int $color_pantone_id
 * @property int $is_main
 * @property string $add_info
 *
 * @property ColorPantone $colorPantone
 * @property ModelVarBaski $modelVarBaski
 */
class ModelVarBaskiColors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_var_baski_colors}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_var_baski_id', 'color_pantone_id', 'is_main'], 'integer'],
            [['add_info'], 'string', 'max' => 255],
            [['color_pantone_id'], 'exist', 'skipOnError' => true, 'targetClass' => ColorPantone::className(), 'targetAttribute' => ['color_pantone_id' => 'id']],
            [['model_var_baski_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelVarBaski::className(), 'targetAttribute' => ['model_var_baski_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_var_baski_id' => Yii::t('app', 'Model Var Baski ID'),
            'color_pantone_id' => Yii::t('app', 'Color Pantone ID'),
            'is_main' => Yii::t('app', 'Is Main'),
            'add_info' => Yii::t('app', 'Add Info'),
        ];
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
    public function getModelVarBaski()
    {
        return $this->hasOne(ModelVarBaski::className(), ['id' => 'model_var_baski_id']);
    }
}
