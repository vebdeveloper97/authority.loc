<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "bichuv_nastel_process_brak".
 *
 * @property int $id
 * @property string $quantity
 * @property int $bichuv_nastel_processes_id
 * @property int $bichuv_nastel_detail_items_id
 * @property int $bichuv_given_roll_items_id
 * @property int $users_id
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvGivenRollItems $bichuvGivenRollItems
 * @property BichuvNastelDetailItems $bichuvNastelDetailItems
 * @property BichuvNastelProcesses $bichuvNastelProcesses
 * @property Users $users
 */
class BichuvNastelProcessBrak extends \app\modules\bichuv\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_nastel_process_brak';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity'], 'number'],
            [['bichuv_nastel_processes_id', 'bichuv_nastel_detail_items_id', 'bichuv_given_roll_items_id', 'users_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info'], 'string'],
            [['bichuv_given_roll_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRollItems::className(), 'targetAttribute' => ['bichuv_given_roll_items_id' => 'id']],
            [['bichuv_nastel_detail_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvNastelDetailItems::className(), 'targetAttribute' => ['bichuv_nastel_detail_items_id' => 'id']],
            [['bichuv_nastel_processes_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvNastelProcesses::className(), 'targetAttribute' => ['bichuv_nastel_processes_id' => 'id']],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'bichuv_nastel_processes_id' => Yii::t('app', 'Bichuv Nastel Processes ID'),
            'bichuv_nastel_detail_items_id' => Yii::t('app', 'Bichuv Nastel Detail Items ID'),
            'bichuv_given_roll_items_id' => Yii::t('app', 'Bichuv Given Roll Items ID'),
            'users_id' => Yii::t('app', 'Users ID'),
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
    public function getBichuvNastelDetailItems()
    {
        return $this->hasOne(BichuvNastelDetailItems::className(), ['id' => 'bichuv_nastel_detail_items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelProcesses()
    {
        return $this->hasOne(BichuvNastelProcesses::className(), ['id' => 'bichuv_nastel_processes_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }
}
