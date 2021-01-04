<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "goods_barcode".
 *
 * @property int $id
 * @property int $goods_id
 * @property int $brand_id
 * @property int $number
 *
 * @property Brend $brand
 * @property Goods $goods
 * @property BarcodeCustomers $barcodeCustomer
 * @property string $barcode [varchar(40)]
 * @property int $bc_id [int(11)]
 */
class GoodsBarcode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods_barcode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id','bc_id','brand_id', 'number'], 'integer'],
            ['barcode','string'],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brend::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
            [['bc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BarcodeCustomers::className(), 'targetAttribute' => ['bc_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', 'Goods ID'),
            'brand_id' => Yii::t('app', 'Brand ID'),
            'barcode' => Yii::t('app', 'Barcode'),
            'number' => Yii::t('app', 'Number'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brend::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarcodeCustomer()
    {
        return $this->hasOne(BarcodeCustomers::className(), ['id' => 'bc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
}
