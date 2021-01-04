<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "employee_rel_skills".
 *
 * @property int $hr_employee_id
 * @property int $employee_skills_id
 * @property double $rate
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrEmployeeSkills $employeeSkills
 * @property HrEmployee $hrEmployee
 */
class EmployeeRelSkills extends \app\modules\hr\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_rel_skills';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['hr_employee_id', 'employee_skills_id', 'rate'], 'required'],
            [['hr_employee_id', 'employee_skills_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['rate'], 'number'],
            [['add_info'], 'string'],
            [['hr_employee_id', 'employee_skills_id'], 'unique', 'targetAttribute' => ['hr_employee_id', 'employee_skills_id']],
            [['employee_skills_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployeeSkills::className(), 'targetAttribute' => ['employee_skills_id' => 'id']],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hr_employee_id' => Yii::t('app', 'Hr Employee ID'),
            'employee_skills_id' => Yii::t('app', 'Employee Skills ID'),
            'rate' => Yii::t('app', 'Rate'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeSkills()
    {
        return $this->hasOne(HrEmployeeSkills::className(), ['id' => 'employee_skills_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'hr_employee_id']);
    }
}
