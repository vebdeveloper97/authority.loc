<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_doc_items_rel_order".
 *
 * @property int $id
 * @property int $toquv_document_items_id
 * @property int $toquv_orders_id
 * @property int $toquv_rm_order_id
 *
 * @property ToquvDocumentItems $toquvDocumentItems
 * @property ToquvOrders $toquvOrders
 * @property ToquvRmOrder $toquvRmOrder
 */
class ToquvDocItemsRelOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_doc_items_rel_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_document_items_id', 'toquv_orders_id', 'toquv_rm_order_id'], 'integer'],
            [['toquv_document_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDocumentItems::className(), 'targetAttribute' => ['toquv_document_items_id' => 'id']],
            [['toquv_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvOrders::className(), 'targetAttribute' => ['toquv_orders_id' => 'id']],
            [['toquv_rm_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRmOrder::className(), 'targetAttribute' => ['toquv_rm_order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_document_items_id' => Yii::t('app', 'Toquv Document Items ID'),
            'toquv_orders_id' => Yii::t('app', 'Toquv Orders ID'),
            'toquv_rm_order_id' => Yii::t('app', 'Toquv Rm Order ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocumentItems()
    {
        return $this->hasOne(ToquvDocumentItems::className(), ['id' => 'toquv_document_items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvOrders()
    {
        return $this->hasOne(ToquvOrders::className(), ['id' => 'toquv_orders_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvRmOrder()
    {
        return $this->hasOne(ToquvRmOrder::className(), ['id' => 'toquv_rm_order_id']);
    }
}
