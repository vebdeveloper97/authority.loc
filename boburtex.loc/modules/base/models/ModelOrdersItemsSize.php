<?php

namespace app\modules\base\models;

use app\models\Size;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "model_orders_items_size".
 *
 * @property int $id
 * @property int $model_orders_items_id
 * @property int $count
 * @property int $size_id
 * @property int $parent_id
 * @property int $model_orders_items_changes_id
 *
 * @property ModelOrdersItemsChanges $modelOrdersItemsChanges
 * @property ModelOrdersItems $modelOrdersItems
 * @property Size $size
 * @property int $created_at [int]
 * @property int $updated_at [int]
 * @property int $created_by [int]
 * @property int $updated_by [int]
 * @property int $model_orders_id [int]
 * @property string $add_info
 * @property-read array $goods
 * @property int $assorti_count [int]
 */
class ModelOrdersItemsSize extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_orders_items_size';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_items_id', 'count', 'size_id', 'parent_id', 'model_orders_items_changes_id', 'assorti_count', 'model_orders_id'], 'integer'],
            [['add_info'], 'string'],
            [['model_orders_items_changes_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItemsChanges::className(), 'targetAttribute' => ['model_orders_items_changes_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
            [['model_orders_id'],  'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::class, 'targetAttribute' => ['model_orders_id' => 'id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_orders_items_id' => Yii::t('app', 'Model Orders Items ID'),
            'count' => Yii::t('app', 'Count'),
            'size_id' => Yii::t('app', 'Size ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'model_orders_items_changes_id' => Yii::t('app', 'Model Orders Items Changes ID'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItemsChanges()
    {
        return $this->hasOne(ModelOrdersItemsChanges::className(), ['id' => 'model_orders_items_changes_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'model_orders_items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }
    public function getGoods()
    {
        $order = $this->modelOrdersItems;
        $model = $order->modelsList;
        $color = $order->modelVar->colorId;
        $goods = Goods::findOne([
            'model_no' => $model->article,
            'size_type' => $this->size->size_type_id,
            'size' => $this->size_id,
            'color' => $color
        ]);
        if ($goods){
            return $goods;
        }
        return [];
    }
}
