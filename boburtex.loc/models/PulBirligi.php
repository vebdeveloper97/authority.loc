<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pul_birligi".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $code
 *
 * @property BichuvDocExpense[] $bichuvDocExpenses
 * @property BichuvSaldo[] $bichuvSaldos
 * @property ToquvDocumentExpense[] $toquvDocumentExpenses
 * @property ToquvPriceIpItem[] $toquvPriceIpItems
 * @property ToquvPricingItem[] $toquvPricingItems
 * @property ToquvSaldo[] $toquvSaldos
 */
class PulBirligi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pul_birligi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 10],
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
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'code' => Yii::t('app', 'Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDocExpenses()
    {
        return $this->hasMany(BichuvDocExpense::className(), ['pb_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvSaldos()
    {
        return $this->hasMany(BichuvSaldo::className(), ['pb_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocumentExpenses()
    {
        return $this->hasMany(ToquvDocumentExpense::className(), ['pb_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvPriceIpItems()
    {
        return $this->hasMany(ToquvPriceIpItem::className(), ['pb_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvPricingItems()
    {
        return $this->hasMany(ToquvPricingItem::className(), ['pb_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvSaldos()
    {
        return $this->hasMany(ToquvSaldo::className(), ['pb_id' => 'id']);
    }
    public static function getPbList()
    {
        $pb = PulBirligi::find()->all();
        return ArrayHelper::map($pb, 'id', 'name');
    }
}
