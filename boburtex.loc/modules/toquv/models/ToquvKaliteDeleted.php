<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "{{%toquv_kalite_deleted}}".
 *
 * @property int $id
 * @property int $toquv_instructions_id
 * @property int $toquv_instruction_rm_id
 * @property int $toquv_rm_order_id
 * @property int $toquv_makine_id
 * @property int $user_id
 * @property string $quantity
 * @property int $sort_name_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $type
 * @property int $toquv_raw_materials_id
 * @property int $order
 * @property string $code
 * @property string $smena
 * @property double $count
 * @property double $roll
 * @property int $user_kalite_id
 * @property string $send_date
 * @property int $send_user_id
 * @property string $add_info
 * @property int $toquv_kalite_id
 * @property string $info
 */
class ToquvKaliteDeleted extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%toquv_kalite_deleted}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_instructions_id', 'toquv_instruction_rm_id', 'toquv_rm_order_id', 'toquv_makine_id', 'user_id', 'sort_name_id', 'status', 'created_by', 'created_at', 'updated_at', 'type', 'toquv_raw_materials_id', 'order', 'user_kalite_id', 'send_user_id', 'toquv_kalite_id'], 'integer'],
            [['quantity', 'count', 'roll'], 'number'],
            [['send_date'], 'safe'],
            [['add_info'], 'string'],
            [['code'], 'string', 'max' => 60],
            [['smena'], 'string', 'max' => 3],
            [['info'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_instructions_id' => Yii::t('app', 'Toquv Instructions ID'),
            'toquv_instruction_rm_id' => Yii::t('app', 'Toquv Instruction Rm ID'),
            'toquv_rm_order_id' => Yii::t('app', 'Toquv Rm Order ID'),
            'toquv_makine_id' => Yii::t('app', 'Toquv Makine ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'sort_name_id' => Yii::t('app', 'Sort Name ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'type' => Yii::t('app', 'Type'),
            'toquv_raw_materials_id' => Yii::t('app', 'Toquv Raw Materials ID'),
            'order' => Yii::t('app', 'Order'),
            'code' => Yii::t('app', 'Code'),
            'smena' => Yii::t('app', 'Smena'),
            'count' => Yii::t('app', 'Count'),
            'roll' => Yii::t('app', 'Roll'),
            'user_kalite_id' => Yii::t('app', 'User Kalite ID'),
            'send_date' => Yii::t('app', 'Send Date'),
            'send_user_id' => Yii::t('app', 'Send User ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'toquv_kalite_id' => Yii::t('app', 'Toquv Kalite ID'),
            'info' => Yii::t('app', 'Info'),
        ];
    }
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->send_date) {
                $this->send_date = date('Y-m-d H:i:s', strtotime($this->send_date));
            }
            return true;
        }else{
            return false;
        }
    }
    public function afterFind()
    {
        parent::afterFind();
        if($this->send_date) {
            $this->send_date = date('d.m.Y H:i', strtotime($this->send_date));
        }
    }
}
