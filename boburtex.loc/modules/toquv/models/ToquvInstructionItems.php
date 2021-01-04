<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "{{%toquv_instruction_items}}".
 *
 * @property int $id
 * @property int $toquv_instruction_id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $quantity
 * @property string $fact
 * @property string $add_info
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $thread_name
 * @property int $is_own
 * @property int $rm_item_id
 * @property int $musteri_id
 * @property string $lot
 * @property int $toquv_instruction_rm_id
 * @property double $percentage
 * @property string $toquv_ne
 * @property string $toquv_thread
 * @property string $toquv_ip_color
 *
 * @property ToquvRmOrderItems $rmItem
 * @property ToquvInstructions $toquvInstruction
 */
class ToquvInstructionItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%toquv_instruction_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_instruction_id', 'entity_id', 'entity_type', 'created_by', 'status', 'created_at', 'updated_at', 'is_own', 'rm_item_id', 'musteri_id', 'toquv_instruction_rm_id'], 'integer'],
            [['quantity', 'fact', 'percentage'], 'number'],
            [['add_info'], 'string'],
            [['thread_name'], 'string', 'max' => 255],
            [['lot'], 'string', 'max' => 30],
            [['toquv_ne', 'toquv_thread', 'toquv_ip_color'], 'string', 'max' => 40],
            [['rm_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRmOrderItems::className(), 'targetAttribute' => ['rm_item_id' => 'id']],
            [['toquv_instruction_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvInstructions::className(), 'targetAttribute' => ['toquv_instruction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_instruction_id' => Yii::t('app', 'Toquv Instruction ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'fact' => Yii::t('app', 'Fact'),
            'add_info' => Yii::t('app', 'Add Info'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'thread_name' => Yii::t('app', 'Thread Name'),
            'is_own' => Yii::t('app', 'Is Own'),
            'rm_item_id' => Yii::t('app', 'Rm Item ID'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'lot' => Yii::t('app', 'Lot'),
            'toquv_instruction_rm_id' => Yii::t('app', 'Toquv Instruction Rm ID'),
            'percentage' => Yii::t('app', 'Percentage'),
            'toquv_ne' => Yii::t('app', 'Toquv Ne'),
            'toquv_thread' => Yii::t('app', 'Toquv Thread'),
            'toquv_ip_color' => Yii::t('app', 'Toquv Ip Color'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRmItem()
    {
        return $this->hasOne(ToquvRmOrderItems::className(), ['id' => 'rm_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvInstruction()
    {
        return $this->hasOne(ToquvInstructions::className(), ['id' => 'toquv_instruction_id']);
    }
}
