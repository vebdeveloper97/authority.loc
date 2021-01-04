<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "bichuv_given_roll_items_sub".
 *
 * @property int $id
 * @property string $remain
 * @property int $roll_remain
 * @property string $otxod
 * @property int $bichuv_nastel_processes_id
 * @property int $bichuv_given_roll_items_id
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvGivenRollItems $bichuvGivenRollItems
 * @property BichuvNastelProcesses $bichuvNastelProcesses
 */
class BichuvGivenRollItemsSub extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_given_roll_items_sub';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['remain', 'otxod'], 'number'],
            [['roll_remain', 'bichuv_nastel_processes_id', 'bichuv_given_roll_items_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info'], 'string'],
            [['bichuv_given_roll_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRollItems::className(), 'targetAttribute' => ['bichuv_given_roll_items_id' => 'id']],
            [['bichuv_nastel_processes_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvNastelProcesses::className(), 'targetAttribute' => ['bichuv_nastel_processes_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'remain' => Yii::t('app', 'Remain'),
            'roll_remain' => Yii::t('app', 'Roll Remain'),
            'otxod' => Yii::t('app', 'Otxod'),
            'bichuv_nastel_processes_id' => Yii::t('app', 'Bichuv Nastel Processes ID'),
            'bichuv_given_roll_items_id' => Yii::t('app', 'Bichuv Given Roll Items ID'),
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
    public function getBichuvGivenRollItems()
    {
        return $this->hasOne(BichuvGivenRollItems::className(), ['id' => 'bichuv_given_roll_items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelProcesses()
    {
        return $this->hasOne(BichuvNastelProcesses::className(), ['id' => 'bichuv_nastel_processes_id']);
    }
}
