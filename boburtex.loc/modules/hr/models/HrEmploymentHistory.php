<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "hr_employment_history".
 *
 * @property int $id
 * @property int $employee_id
 * @property int $position_id
 * @property int $from_department
 * @property int $to_department
 * @property string $reg_date
 * @property string $end_date
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property HrEmployee $employee
 * @property HrDepartments $fromDepartment
 * @property HrPosition $position
 * @property HrDepartments $toDepartment
 */
class HrEmploymentHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_employment_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id', 'position_id', 'from_department', 'to_department', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['reg_date', 'end_date'], 'safe'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['employee_id' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrPosition::className(), 'targetAttribute' => ['position_id' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'employee_id' => Yii::t('app', 'Staff'),
            'position_id' => Yii::t('app', 'Positions'),
            'from_department' => Yii::t('app', "From department"),
            'to_department' => Yii::t('app', "To department"),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'from_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(HrPosition::className(), ['id' => 'position_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'to_department']);
    }
}
