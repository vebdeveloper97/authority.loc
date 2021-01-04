<?php

namespace app\modules\settings\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "unit".
 *
 * @property int $id
 * @property string $name
 * @property string $add_info
 * @property string $code
 *
 * @property BichuvAcs[] $bichuvAcs
 * @property TikuvDocumentItems[] $tikuvDocumentItems
 * @property TikuvGoodsDoc[] $tikuvGoodsDocs
 * @property TikuvOutcomeProducts[] $tikuvOutcomeProducts
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
            [['add_info'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 20],
        ];
    }
    public static function getUnitList($key = null){
        $result = static::find()->asArray()->all();
        $result = ArrayHelper::map($result,'id','name');
        if(!empty($key)){
            return $result[$key];
        }

        return $result;
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
            'code' => Yii::t('app', 'Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvAcs()
    {
        return $this->hasMany(BichuvAcs::className(), ['unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvDocumentItems()
    {
        return $this->hasMany(TikuvDocumentItems::className(), ['unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvGoodsDocs()
    {
        return $this->hasMany(TikuvGoodsDoc::className(), ['unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvOutcomeProducts()
    {
        return $this->hasMany(TikuvOutcomeProducts::className(), ['unit_id' => 'id']);
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
