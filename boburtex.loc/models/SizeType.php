<?php

namespace app\models;

use app\modules\tikuv\models\TikuvOutcomeProducts;
use Yii;

/**
 * This is the model class for table "size_type".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 *
 * @property Size[] $sizes
 * @property TikuvOutcomeProducts[] $tikuvOutcomeProducts
 */
class SizeType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'size_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 50],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSizes()
    {
        return $this->hasMany(Size::className(), ['size_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvOutcomeProducts()
    {
        return $this->hasMany(TikuvOutcomeProducts::className(), ['size_type_id' => 'id']);
    }
}
