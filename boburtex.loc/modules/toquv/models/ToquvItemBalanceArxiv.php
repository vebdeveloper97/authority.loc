<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "{{%toquv_item_balance_arxiv}}".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $count
 * @property string $inventory
 * @property string $reg_date
 * @property int $department_id
 * @property int $is_own
 * @property string $price_uzs
 * @property string $price_usd
 * @property string $sold_price_uzs
 * @property string $sold_price_usd
 * @property string $sum_uzs
 * @property string $sum_usd
 * @property int $document_id
 * @property int $document_type
 * @property int $version
 * @property string $comment
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $price_rub
 * @property string $sold_price_rub
 * @property string $price_eur
 * @property string $sold_price_eur
 * @property string $lot
 * @property int $to_department
 * @property int $from_department
 * @property int $from_musteri
 * @property int $to_musteri
 * @property int $musteri_id
 * @property string $roll_inventory
 * @property string $roll_count
 * @property double $quantity
 * @property double $quantity_inventory
 * @property int $parent_id
 */
class ToquvItemBalanceArxiv extends \app\modules\toquv\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%toquv_item_balance_arxiv}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_type', 'department_id', 'is_own', 'document_id', 'document_type', 'version', 'created_by', 'status', 'created_at', 'updated_at', 'to_department', 'from_department', 'from_musteri', 'to_musteri', 'musteri_id', 'parent_id'], 'integer'],
            [['count', 'inventory'], 'required'],
            [['count', 'inventory', 'price_uzs', 'price_usd', 'sold_price_uzs', 'sold_price_usd', 'sum_uzs', 'sum_usd', 'price_rub', 'sold_price_rub', 'price_eur', 'sold_price_eur', 'roll_inventory', 'roll_count', 'quantity', 'quantity_inventory'], 'number'],
            [['reg_date'], 'safe'],
            [['comment'], 'string'],
            [['lot'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'count' => Yii::t('app', 'Count'),
            'inventory' => Yii::t('app', 'Inventory'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'department_id' => Yii::t('app', 'Department ID'),
            'is_own' => Yii::t('app', 'Is Own'),
            'price_uzs' => Yii::t('app', 'Price Uzs'),
            'price_usd' => Yii::t('app', 'Price Usd'),
            'sold_price_uzs' => Yii::t('app', 'Sold Price Uzs'),
            'sold_price_usd' => Yii::t('app', 'Sold Price Usd'),
            'sum_uzs' => Yii::t('app', 'Sum Uzs'),
            'sum_usd' => Yii::t('app', 'Sum Usd'),
            'document_id' => Yii::t('app', 'Document ID'),
            'document_type' => Yii::t('app', 'Document Type'),
            'version' => Yii::t('app', 'Version'),
            'comment' => Yii::t('app', 'Comment'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'price_rub' => Yii::t('app', 'Price Rub'),
            'sold_price_rub' => Yii::t('app', 'Sold Price Rub'),
            'price_eur' => Yii::t('app', 'Price Eur'),
            'sold_price_eur' => Yii::t('app', 'Sold Price Eur'),
            'lot' => Yii::t('app', 'Lot'),
            'to_department' => Yii::t('app', 'To Department'),
            'from_department' => Yii::t('app', 'From Department'),
            'from_musteri' => Yii::t('app', 'From Musteri'),
            'to_musteri' => Yii::t('app', 'To Musteri'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'roll_inventory' => Yii::t('app', 'Roll Inventory'),
            'roll_count' => Yii::t('app', 'Roll Count'),
            'quantity' => Yii::t('app', 'Quantity'),
            'quantity_inventory' => Yii::t('app', 'Quantity Inventory'),
            'parent_id' => Yii::t('app', 'Parent ID'),
        ];
    }
}
