<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "unit".
 *
 * @property int $id
 * @property string $name
 * @property string $add_info
 *
 * @property ToquvDocumentItems[] $toquvDocumentItems
 * @property ToquvServicePricing[] $toquvServicePricings
 */
class Unit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            ['code','string','max' => 20],
            ['code','unique'],
            [['add_info'], 'string', 'max' => 255],
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
            'add_info' => Yii::t('app', 'Add Info'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocumentItems()
    {
        return $this->hasMany(ToquvDocumentItems::className(), ['unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvServicePricings()
    {
        return $this->hasMany(ToquvServicePricing::className(), ['unit_id' => 'id']);
    }
}
