<?php

namespace app\modules\toquv\models;

use app\modules\base\models\ModelOrdersItems;
use Yii;

/**
 * This is the model class for table "{{%toquv_rm_order_moi}}".
 *
 * @property int $id
 * @property int $toquv_rm_order_id
 * @property int $model_orders_id
 * @property int $model_orders_items_id
 * @property string $quantity
 * @property int $moi_rel_dept_id
 * @property string $start_date
 * @property string $end_date
 * @property int $status
 *
 * @property ModelOrdersItems $modelOrdersItems
 * @property ToquvRmOrder $toquvRmOrder
 * @property int $count [int(11)]
 * @property string $add_info
 * @property string $size_list_name [varchar(200)]
 */
class ToquvRmOrderMoi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%toquv_rm_order_moi}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_rm_order_id', 'model_orders_id', 'model_orders_items_id', 'moi_rel_dept_id', 'status', 'count'], 'integer'],
            [['quantity'], 'number'],
            ['size_list_name','string','max' => 200],
            ['add_info','string'],
            [['start_date', 'end_date'], 'safe'],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
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
            'toquv_rm_order_id' => Yii::t('app', 'Toquv Rm Order ID'),
            'model_orders_id' => Yii::t('app', 'Model Orders ID'),
            'model_orders_items_id' => Yii::t('app', 'Model Orders Items ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'moi_rel_dept_id' => Yii::t('app', 'Moi Rel Dept ID'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'status' => Yii::t('app', 'Status'),
            'count' => Yii::t('app', 'Count'),
        ];
    }
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $date = date('Y-m-d');
            if(!empty($this->start_date)){
                $date =  date('Y-m-d', strtotime($this->start_date));
            }
            if(!empty($this->end_date)){
                $end_date =  date('Y-m-d', strtotime($this->end_date));
            }
            $currentTime = date('H:i:s');
            $this->start_date = date('Y-m-d H:i:s', strtotime($date.' '.$currentTime));
            $this->end_date = date('Y-m-d H:i:s', strtotime($end_date.' '.$currentTime));
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->start_date = date('d.m.Y', strtotime($this->start_date));
        $this->end_date = date('d.m.Y', strtotime($this->end_date));

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
    public function getToquvRmOrder()
    {
        return $this->hasOne(ToquvRmOrder::className(), ['id' => 'toquv_rm_order_id']);
    }
}
