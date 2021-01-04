<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_doc_sub_items".
 *
 * @property int $id
 * @property int $doc_item_id
 * @property int $entity_id
 * @property int $level
 * @property int $entity_type
 * @property int $quantity
 * @property string $price_sum
 * @property string $price_usd
 * @property string $current_usd
 * @property int $is_own
 * @property int $package_type
 * @property int $package_qty
 * @property string $lot
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvDocumentItems $docItem
 */
class ToquvDocSubItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_doc_sub_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_item_id', 'entity_id', 'level', 'entity_type', 'quantity', 'is_own', 'package_type', 'package_qty', 'status', 'created_at', 'updated_at'], 'integer'],
            [['price_sum', 'price_usd', 'current_usd'], 'number'],
            [['lot'], 'string', 'max' => 25],
            [['doc_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDocumentItems::className(), 'targetAttribute' => ['doc_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_item_id' => Yii::t('app', 'Doc Item ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'level' => Yii::t('app', 'Level'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'price_sum' => Yii::t('app', 'Price Sum'),
            'price_usd' => Yii::t('app', 'Price Usd'),
            'current_usd' => Yii::t('app', 'Current Usd'),
            'is_own' => Yii::t('app', 'Is Own'),
            'package_type' => Yii::t('app', 'Package Type'),
            'package_qty' => Yii::t('app', 'Package Qty'),
            'lot' => Yii::t('app', 'Lot'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocItem()
    {
        return $this->hasOne(ToquvDocumentItems::className(), ['id' => 'doc_item_id']);
    }
}
