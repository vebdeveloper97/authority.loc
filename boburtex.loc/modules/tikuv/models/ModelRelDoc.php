<?php

namespace app\modules\tikuv\models;

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\boyoq\models\ColorPantone;
use app\modules\toquv\models\PulBirligi;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "model_rel_doc".
 *
 * @property int $id
 * @property int $model_list_id
 * @property int $model_var_id
 * @property int $tikuv_doc_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ModelsList $modelList
 * @property ModelsVariations $modelVar
 * @property TikuvDoc $tikuvDoc
 * @property ModelOrders $order
 * @property ModelOrdersItems $orderItem
 * @property PulBirligi $pb
 * @property int $order_id [int(11)]
 * @property int $order_item_id [int(11)]
 * @property string $price [decimal(20,3)]
 * @property int $pb_id [int(11)]
 * @property string $nastel_no [varchar(30)]
 * @property ActiveQuery $colorPantone
 * @property int $color_id [int(11)]
 */
class ModelRelDoc extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_rel_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_list_id', 'order_id', 'order_item_id','pb_id','model_var_id', 'tikuv_doc_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            ['price','number'],
            [['nastel_no'], 'string', 'max' => 30],
            [['color_id'], 'required'],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
            [['tikuv_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvDoc::className(), 'targetAttribute' => ['tikuv_doc_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['order_item_id' => 'id']],
            [['pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['pb_id' => 'id']],
            [['color_id'], 'exist', 'skipOnError' => true, 'targetClass' => ColorPantone::className(), 'targetAttribute' => ['color_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_list_id' => Yii::t('app', 'Model List ID'),
            'model_var_id' => Yii::t('app', 'Model Var ID'),
            'tikuv_doc_id' => Yii::t('app', 'Tikuv Doc ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'order_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrderItem()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'order_item_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getColorPantone()
    {
        return $this->hasOne(ColorPantone::className(), ['id' => 'color_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'pb_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_list_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_var_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvDoc()
    {
        return $this->hasOne(TikuvDoc::className(), ['id' => 'tikuv_doc_id']);
    }
    public function getModelOrder()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'order_id']);
    }
}
