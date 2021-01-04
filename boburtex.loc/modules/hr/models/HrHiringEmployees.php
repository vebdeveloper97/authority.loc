<?php

namespace app\modules\hr\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "hr_hiring_employees".
 *
 * @property int $id
 * @property int $employee_id
 * @property int $staff_id
 * @property string $reg_date
 * @property string $end_date
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrEmployee $employee
 * @property HrStaff $staff
 */
class HrHiringEmployees extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_hiring_employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reg_date', 'staff_id', 'employee_id'], 'required'],
            ['status', 'default', 'value' => 1],
            [['employee_id', 'staff_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['reg_date', 'end_date'], 'string'],
            ['end_date', 'default'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['employee_id' => 'id']],
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
            'employee_id' => Yii::t('app', 'Employee'),
            'staff_id' => Yii::t('app', 'Staff'),
            'reg_date' => Yii::t('app', 'Employment date'),
            'end_date' => Yii::t('app', 'Termination date'),
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
    public function getEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(HrStaff::className(), ['id' => 'staff_id']);
    }

    public static function getMapList($column) {
        $name = '';
        $query = '';
        switch ($column) {
            case 'employee': // TODO: bu joyida faqat bo'sh xodimlarni tanlash lozim
                $query = HrEmployee::find()
                    ->select(['id', 'fish'])
                    ->where(['status' => BaseModel::STATUS_ACTIVE]);
                $name = 'fish';
                break;
            case 'staff':
                $query = self::getStaffInfo();
                $name = 'staff_info';
                break;
        }

        if ($query instanceof Query && $result = $query->asArray()->all()) {
            return ArrayHelper::map($result, 'id', $name);
        }

        return '';
    }

    public static function getStaffInfo() {
        return HrStaff::find()
            ->select(['hrs.id', "CONCAT(hrd.name, ' - ', hrp.name, ' (', hrpt.name, ')') AS staff_info"])
            ->alias('hrs')
            ->leftJoin(['hrd' => 'hr_departments'], 'hrs.department_id = hrd.id')
            ->leftJoin(['hrp' => 'hr_position'], 'hrs.position_id = hrp.id')
            ->leftJoin(['hrpt' => 'hr_position_type'], 'hrs.position_type_id = hrpt.id')
            ->where(['hrs.status' => BaseModel::STATUS_ACTIVE]);
    }



    public static function getStaffsMapByDepartmentId($departmentId)
    {
        $staffs = HrStaff::find()
            ->select(['hrs.id', "CONCAT(hrd.name, ' - ', hrp.name, ' (', hrpt.name, ')') AS name"])
            ->alias('hrs')
            ->leftJoin(['hrd' => 'hr_departments'], 'hrs.department_id = hrd.id')
            ->leftJoin(['hrp' => 'hr_position'], 'hrs.position_id = hrp.id')
            ->leftJoin(['hrpt' => 'hr_position_type'], 'hrs.position_type_id = hrpt.id')
            ->andWhere(['hrs.status' => BaseModel::STATUS_ACTIVE])
            ->andWhere(['hrs.department_id' => $departmentId])
            ->asArray()
            ->all();

        return $staffs;
    }

    public function getStaffInfoById($staffId) {
        return HrStaff::find()
            ->select(['hrs.id', "CONCAT(hrd.name, ' - ', hrp.name, ' (', hrpt.name, ')') AS staff_info"])
            ->alias('hrs')
            ->leftJoin(['hrd' => 'hr_departments'], 'hrs.department_id = hrd.id')
            ->leftJoin(['hrp' => 'hr_position'], 'hrs.position_id = hrp.id')
            ->leftJoin(['hrpt' => 'hr_position_type'], 'hrs.position_type_id = hrpt.id')
            ->andWhere(['hrs.status' => BaseModel::STATUS_ACTIVE])
            ->andWhere(['hrs.id' => $staffId])
            ->asArray()
            ->one();
    }

    public static function getEmployeeInfo($hiringEmployeeId = null) {
        return static::find()
            ->select(['hre.*'])
            ->alias('hrhe')
            ->leftJoin(['hre' => 'hr_employee'], 'hrhe.employee_id = hre.id')
            ->andFilterWhere(['hrhe.id' => $hiringEmployeeId])
            ->asArray()
            ->all();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!$this->status && $this->isNewRecord) {
            $this->status = self::STATUS_ACTIVE;
        }

        return true;
    }

    public function saveAndAddCounter()
    {
        $transaction = Yii::$app->db->beginTransaction();

        $isSaved = false;
        try {
            $isSaved = $this->save();

            /**
             * hr_staff_counters jadvaliga yangi row qo'shib qo'yamiz
             */
            if ($isSaved) {
                $oldStaffCounter = HrStaffCounters::find()
                    ->andWhere([
                        'staff_id' => $this->staff_id,
                        'status' => self::STATUS_ACTIVE
                    ])
                    ->one();

                /** oldingi shtat o'rni bo'lsa uni bo'shatamiz qilamiz */
                if ($oldStaffCounter) {
                    $oldStaffCounter->status = self::STATUS_INACTIVE;
                    $isSaved = $isSaved && $oldStaffCounter->save();
                }

                $oldHiringEmployee = static::find()
                    ->andWhere([
                        'employee_id' => $this->employee_id,
                        'status' => self::STATUS_ACTIVE
                    ])
                    ->andWhere(['!=', 'id', $this->id])
                    ->one();

                /**
                 * agar hodim oldingi lavozimi bor bo'lsa uni inactive qilamiz
                 */
                if ($oldHiringEmployee) {
                    $oldHiringEmployee->end_date = $this->reg_date;
                    $oldHiringEmployee->status = self::STATUS_INACTIVE;
                    $isSaved = $isSaved && $oldHiringEmployee->save();
                }

                $newStaffCounter = new HrStaffCounters();
                $newStaffCounter->setAttributes([
                    'staff_id' => $this->staff_id,
                    'status' => 1,
                    'quantity' => 1,
                ]);
                $isSaved = $isSaved && $newStaffCounter->save();
            }

            if (!$isSaved) {
                $transaction->rollBack();
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            Yii::debug($e, 'hiring exception'); // TODO: bu qatorni o'chirish kerak
            $transaction->rollBack();
        }

        return $isSaved;
    }

    public function getStatusLabel($status) {
        $statusLabel = '';
        switch($status) {
            case self::STATUS_ACTIVE:
                $statusLabel = Yii::t('app', 'Active');
                break;
            case self::STATUS_INACTIVE:
                $statusLabel = Yii::t('app', 'Inactive');
                break;
        }

        return $statusLabel;
    }
}
