<?php

namespace app\modules\tikuv\models;

use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use Yii;
use app\modules\base\models\Goods;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;

/**
 * This is the model class for table "tikuv_goods_doc_moving".
 *
 * @property int $id
 * @property int $goods_id
 * @property int $order_id
 * @property int $order_item_id
 * @property string $quantity
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Goods $goods
 * @property ModelOrders $order
 * @property ModelOrdersItems $orderItem
 * @property int $model_list_id [int(11)]
 * @property int $model_var_id [int(11)]
 */
class TikuvGoodsDocMoving extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_goods_doc_moving';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'model_list_id','model_var_id','order_id', 'order_item_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['quantity'], 'number'],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['order_item_id' => 'id']],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
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
            'order_id' => Yii::t('app', 'Order ID'),
            'order_item_id' => Yii::t('app', 'Order Item ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_list_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_var_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'order_item_id']);
    }
}
