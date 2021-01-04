<?php

namespace app\modules\base\models;

use Yii;
use app\modules\toquv\models\ToquvNe;
use app\modules\toquv\models\ToquvThread;

/**
 * This is the model class for table "model_orders_planning_thread".
 *
 * @property int $id
 * @property int $toquv_ne_id
 * @property int $toquv_thread_id
 * @property string $xom_mato
 * @property string $quantity
 * @property string $load_date
 * @property int $model_orders_id
 * @property int $model_orders_planning_id
 * @property int $model_orders_items_id
 * @property string $reg_date
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property ModelOrders $modelOrders
 * @property ModelOrdersItems $modelOrdersItems
 * @property ModelOrdersPlanning $modelOrdersPlanning
 * @property ToquvNe $toquvNe
 * @property ToquvThread $toquvThread
 */
class ModelOrdersPlanningThread extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_orders_planning_thread';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_ne_id', 'toquv_thread_id', 'model_orders_id', 'model_orders_planning_id', 'model_orders_items_id', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['xom_mato', 'quantity'], 'number'],
            [['load_date', 'reg_date'], 'safe'],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['model_orders_planning_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersPlanning::className(), 'targetAttribute' => ['model_orders_planning_id' => 'id']],
            [['toquv_ne_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvNe::className(), 'targetAttribute' => ['toquv_ne_id' => 'id']],
            [['toquv_thread_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvThread::className(), 'targetAttribute' => ['toquv_thread_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'toquv_ne_id' => 'Toquv Ne ID',
            'toquv_thread_id' => 'Toquv Thread ID',
            'xom_mato' => 'Xom Mato',
            'quantity' => 'Quantity',
            'load_date' => 'Load Date',
            'model_orders_id' => 'Model Orders ID',
            'model_orders_planning_id' => 'Model Orders Planning ID',
            'model_orders_items_id' => 'Model Orders Items ID',
            'reg_date' => 'Reg Date',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersPlanning()
    {
        return $this->hasOne(ModelOrdersPlanning::className(), ['id' => 'model_orders_planning_id']);
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
    public function getToquvThread()
    {
        return $this->hasOne(ToquvThread::className(), ['id' => 'toquv_thread_id']);
    }
}
