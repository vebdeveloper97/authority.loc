<?php

namespace app\modules\boyoq\models;

use Yii;

/**
 * This is the model class for table "color_panton_type".
 *
 * @property int $id
 * @property string $name
 *
 * @property ColorPantone[] $colorPantones
 */
class ColorPantonType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'color_panton_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorPantones()
    {
        return $this->hasMany(ColorPantone::className(), ['color_panton_type_id' => 'id']);
    }
}
