<?php

namespace app\modules\bichuv\models;

use app\modules\wms\models\WmsDepartmentArea;
use Yii;

/**
 * This is the model class for table "spare_item_doc_items".
 *
 * @property int $id
 * @property int $spare_item_doc_id
 * @property int $entity_id
 * @property string $quantity
 * @property string $price_sum
 * @property string $price_usd
 * @property int $from_area
 * @property int $to_area
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property SpareItem $entity
 * @property WmsDepartmentArea $fromArea
 * @property SpareItemDoc $spareItemDoc
 * @property WmsDepartmentArea $toArea
 */
class SpareItemDocItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare_item_doc_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['spare_item_doc_id', 'entity_id', 'musteri_id', 'from_area', 'to_area', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['quantity', 'price_sum', 'price_usd'], 'number'],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItem::className(), 'targetAttribute' => ['entity_id' => 'id']],
            [['from_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['from_area' => 'id']],
            [['spare_item_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItemDoc::className(), 'targetAttribute' => ['spare_item_doc_id' => 'id']],
            [['to_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['to_area' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'spare_item_doc_id' => 'Spare Item Doc ID',
            'entity_id' => 'Entity ID',
            'quantity' => 'Quantity',
            'price_sum' => 'Price Sum',
            'price_usd' => 'Price Usd',
            'from_area' => 'From Area',
            'to_area' => 'To Area',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(SpareItem::className(), ['id' => 'entity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromArea()
    {
        return $this->hasOne(WmsDepartmentArea::className(), ['id' => 'from_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItemDoc()
    {
        return $this->hasOne(SpareItemDoc::className(), ['id' => 'spare_item_doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToArea()
    {
        return $this->hasOne(WmsDepartmentArea::className(), ['id' => 'to_area']);
    }
}
