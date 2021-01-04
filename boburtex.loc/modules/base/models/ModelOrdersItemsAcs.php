<?php

namespace app\modules\base\models;

use app\modules\bichuv\models\BichuvAcs;
use Yii;

/**
 * This is the model class for table "{{%model_orders_items_acs}}".
 *
 * @property int $id
 * @property int $models_orders_id
 * @property int $model_orders_items_id
 * @property int $bichuv_acs_id
 * @property string $qty
 * @property int $unit_id
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvAcs $bichuvAcs
 * @property ModelOrdersItems $modelOrdersItems
 */
class ModelOrdersItemsAcs extends \app\modules\base\models\BaseModel
{
    public $quantity;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_items_acs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_orders_id', 'model_orders_items_id', 'bichuv_acs_id', 'unit_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['qty', 'quantity'], 'number'],
            [['add_info'], 'string'],
            [['application_part'], 'string', 'max' => 255],
            //[['bichuv_acs_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvAcs::className(), 'targetAttribute' => ['bichuv_acs_id' => 'id']],
            [['models_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['models_orders_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'models_orders_id' => Yii::t('app', 'Models Orders ID'),
            'model_orders_items_id' => Yii::t('app', 'Model Orders Items ID'),
            'bichuv_acs_id' => Yii::t('app', 'Bichuv Acs ID'),
            'qty' => Yii::t('app', 'Qty'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvAcs()
    {
        return $this->hasOne(BichuvAcs::className(), ['id' => 'bichuv_acs_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrders()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'model_orders_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'model_orders_items_id']);
    }

    public static function getModelAcs($item,$key,$json=false)
    {
        $result = [];
        foreach ($item->modelsList->modelsAcs as $row => $item_acs) {
            $result[] = [
                'sku' => str_replace("'","`",$item_acs->bichuvAcs['sku']),
                'name' => str_replace("'","`",$item_acs->bichuvAcs['name']),
                'barcode' => $item_acs->bichuvAcs['barcode'],
                'id' => $item_acs->bichuvAcs['id'],
                'property' => str_replace("'","`",$item_acs->bichuvAcs->property['name']),
                'unit_id' => $item_acs->bichuvAcs['unit_id'],
                'qty' => $item_acs["qty"]*$item->getAllCountPercentage($item->percentage),
                'unit_name' => $item_acs->bichuvAcs->unit['name'],
                'image' => $item_acs->bichuvAcs->imageOne,
                'add_info' => str_replace("'","`",$item_acs->add_info),
                'key' => $key
            ];
        }
        if($json){
            return json_encode($result);
        }
        return $result;
    }
}
