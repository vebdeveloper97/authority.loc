<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "hr_staff_counters".
 *
 * @property int $id
 * @property int $staff_id
 * @property int $status
 * @property int $quantity
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property HrStaff $staff
 */
class HrStaffCounters extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_staff_counters';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_id', 'status', 'quantity', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrStaff::className(), 'targetAttribute' => ['staff_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'staff_id' => Yii::t('app', 'Staff ID'),
            'status' => Yii::t('app', 'Status'),
            'quantity' => Yii::t('app', 'Quantity'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(HrStaff::className(), ['id' => 'staff_id']);
    }
}
