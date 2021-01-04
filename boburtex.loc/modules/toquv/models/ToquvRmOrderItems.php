<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_rm_order_items".
 *
 * @property int $id
 * @property int $percentage
 * @property string $own_quantity
 * @property string $their_quantity
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $toquv_rm_order_id
 * @property int $toquv_ne_id
 * @property int $toquv_thread_id
 *
 * @property ToquvInstructionItems[] $toquvInstructionItems
 * @property ToquvNe $toquvNe
 * @property ToquvRmOrder $toquvRmOrder
 * @property ToquvThread $toquvThread
 */
class ToquvRmOrderItems extends BaseModel
{
    public $name;
    public $quantity;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_rm_order_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['percentage', 'status', 'created_by', 'created_at', 'updated_at', 'toquv_rm_order_id', 'toquv_ne_id', 'toquv_thread_id'], 'integer'],
            [['own_quantity', 'their_quantity'], 'number'],
            [['toquv_ne_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvNe::className(), 'targetAttribute' => ['toquv_ne_id' => 'id']],
            [['toquv_rm_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRmOrder::className(), 'targetAttribute' => ['toquv_rm_order_id' => 'id']],
            [['toquv_thread_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvThread::className(), 'targetAttribute' => ['toquv_thread_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'percentage' => Yii::t('app', 'Percentage'),
            'own_quantity' => Yii::t('app', 'Own Quantity'),
            'their_quantity' => Yii::t('app', 'Their Quantity'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'toquv_rm_order_id' => Yii::t('app', 'Toquv Rm Order ID'),
            'toquv_ne_id' => Yii::t('app', 'Toquv Ne ID'),
            'toquv_thread_id' => Yii::t('app', 'Toquv Thread ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvInstructionItems()
    {
        return $this->hasMany(ToquvInstructionItems::className(), ['rm_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvNe()
    {
        return $this->hasOne(ToquvNe::className(), ['id' => 'toquv_ne_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvRmOrder()
    {
        return $this->hasOne(ToquvRmOrder::className(), ['id' => 'toquv_rm_order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvThread()
    {
        return $this->hasOne(ToquvThread::className(), ['id' => 'toquv_thread_id']);
    }
    public function getThreadNeName(){
        $name = $this->toquvNe->name . " - " . $this->toquvThread->name;
        return $name;
    }
    public function getRmIp(){
        $rmId = $this->toquvRmOrder->toquv_raw_materials_id;
        $rmIp = ToquvRawMaterialIp::find()->select(['id'])->where(['ne_id' => $this->toquv_ne_id,'thread_id' => $this->toquv_thread_id,'toquv_raw_material_id'=>$rmId])->asArray()->one();
        return $rmIp;
    }
    public function getAksRmIp(){
        $rmId = $this->toquvRmOrder->toquvAks->trm_id;
        $rmIp = ToquvRawMaterialIp::find()->select(['id'])->where(['ne_id' => $this->toquv_ne_id,'thread_id' => $this->toquv_thread_id,'toquv_raw_material_id'=>$rmId])->asArray()->one();
        return $rmIp;
    }
    public function getPrice(){
        $rmId = $this->toquvRmOrder->toquv_raw_materials_id;
        
        $rmIp = ToquvRawMaterialIp::find()->select(['id','ne_id','thread_id'])->where(['ne_id' => $this->toquv_ne_id,'thread_id' => $this->toquv_thread_id,'toquv_raw_material_id'=>$rmId])->asArray()->one();
        
        $price = ToquvPriceIpItem::find()->where(['toquv_ne_id'=>$rmIp['ne_id'],'toquv_thread_id'=>$rmIp['thread_id']])->orderBy(["id"=>SORT_DESC])->one();
        return ($price)?$price['price']:0;
    }
}
