<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "color".
 *
 * @property int $id
 * @property string $name
 * @property string $pantone
 * @property string $color_id
 * @property int $color_tone
 * @property int $color_group
 * @property int $color_type
 * @property string $color
 * @property int $musteri_id
 * @property string $reg_date
 * @property int $user_id
 *
 * @property ColorPantone[] $colorPantones
 */
class Color extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'color';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'color_id', 'color_tone', 'color_group', 'color_type', 'musteri_id', 'user_id'], 'required'],
            [['color_tone', 'color_group', 'color_type', 'musteri_id', 'user_id'], 'integer'],
            [['reg_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['pantone'], 'string', 'max' => 100],
            [['color_id'], 'string', 'max' => 150],
            [['color'], 'string', 'max' => 15],
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
            'pantone' => Yii::t('app', 'Pantone'),
            'color_id' => Yii::t('app', 'Color ID'),
            'color_tone' => Yii::t('app', 'Color Tone'),
            'color_group' => Yii::t('app', 'Color Group'),
            'color_type' => Yii::t('app', 'Color Type'),
            'color' => Yii::t('app', 'Color'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorPantones()
    {
        return $this->hasMany(ColorPantone::className(), ['color_id' => 'id']);
    }
}
