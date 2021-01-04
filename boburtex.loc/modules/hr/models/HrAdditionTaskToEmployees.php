<?php

namespace app\modules\hr\models;

use Yii;
use app\modules\hr\models\BaseModel;
use app\modules\hr\models\HrEmployee;
/**
 * This is the model class for table "hr_addition_task_to_employees".
 *
 * @property int $id
 * @property int $hr_employee_id
 * @property string $task
 * @property int $rate
 * @property int $status
 * @property string $expire_date
 * @property string $remember_date
 * @property int $type
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property HrAdditionTaskItems[] $hrAdditionTaskItems
 * @property HrEmployee $hrEmployee
 */
class HrAdditionTaskToEmployees extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_addition_task_to_employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_employee_id', 'rate', 'status', 'type', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['task'], 'string'],
            [['expire_date', 'remember_date','reg_date'], 'safe'],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
            [['hr_employee_id'],'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app','ID'),
            'hr_employee_id' => Yii::t('app','Employee'),
            'task' => Yii::t('app','Assigned tasks'),
            'rate' => Yii::t('app','Rate'),
            'status' => Yii::t('app','Status'),
            'expire_date' => Yii::t('app','Expire Date'),
            'reg_date' => Yii::t('app','Reg Date'),
            'remember_date' => Yii::t('app','Remember Date'),
            'type' => Yii::t('app','Type'),
            'created_by' => Yii::t('app','Created By'),
            'updated_by' => Yii::t('app','Updated By'),
            'created_at' => Yii::t('app','Created At'),
            'updated_at' => Yii::t('app','Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrAdditionTaskItems()
    {
        return $this->hasMany(HrAdditionTaskItems::class, ['hr_addition_task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployee()
    {
        return $this->hasOne(HrEmployee::class, ['id' => 'hr_employee_id']);
    }
}
