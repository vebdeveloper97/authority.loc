<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "{{%toquv_makine_info}}".
 *
 * @property int $id
 * @property int $toquv_makine_id
 * @property int $toquv_instruction_rm_id
 * @property string $musteri
 * @property string $doc_number
 * @property string $mato
 * @property string $info
 * @property string $order_quantity
 * @property string $quantity
 * @property double $difference
 * @property double $remain
 * @property double $roll
 * @property double $count
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvMakine $toquvMakine
 */
class ToquvMakineInfo extends \app\modules\toquv\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%toquv_makine_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_makine_id', 'toquv_instruction_rm_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['order_quantity', 'quantity', 'difference', 'remain', 'roll', 'count'], 'number'],
            [['add_info'], 'string'],
            [['musteri'], 'string', 'max' => 70],
            [['doc_number', 'info'], 'string', 'max' => 30],
            [['mato'], 'string', 'max' => 150],
            [['toquv_makine_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvMakine::className(), 'targetAttribute' => ['toquv_makine_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_makine_id' => Yii::t('app', 'Toquv Makine ID'),
            'toquv_instruction_rm_id' => Yii::t('app', 'Toquv Instruction Rm ID'),
            'musteri' => Yii::t('app', 'Musteri'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'mato' => Yii::t('app', 'Mato'),
            'info' => Yii::t('app', 'Info'),
            'order_quantity' => Yii::t('app', 'Order Quantity'),
            'quantity' => Yii::t('app', 'Quantity'),
            'difference' => Yii::t('app', 'Difference'),
            'remain' => Yii::t('app', 'Remain'),
            'roll' => Yii::t('app', 'Roll'),
            'count' => Yii::t('app', 'Count'),
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
    public function getToquvMakine()
    {
        return $this->hasOne(ToquvMakine::className(), ['id' => 'toquv_makine_id']);
    }
}
