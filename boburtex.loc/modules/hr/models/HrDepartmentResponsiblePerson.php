<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "hr_department_responsible_person".
 *
 * @property int $id
 * @property int $hr_department_id
 * @property int $hr_employee_id
 * @property string $start_date
 * @property string $end_date
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrDepartments $hrDepartment
 * @property HrEmployee $hrEmployee
 */
class HrDepartmentResponsiblePerson extends \app\modules\hr\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_department_responsible_person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_department_id', 'hr_employee_id', 'start_date'], 'required'],
            [['hr_department_id', 'hr_employee_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['start_date', 'end_date'], 'date', 'format' => 'php: d.m.Y'],
            [['end_date'], 'compare', 'compareAttribute' => 'start_date', 'operator' => '>='],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['hr_department_id' => 'id']],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::class, 'targetAttribute' => ['hr_employee_id' => 'id']],
        ];
    }

    public function afterFind()
    {
        if (!empty($this->start_date)) {
            $this->start_date = date('d.m.Y', strtotime($this->start_date));
        }

        if (!empty($this->end_date)) {
            $this->end_date = date('d.m.Y', strtotime($this->end_date));
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->start_date)) {
            $this->start_date = date('Y-m-d', strtotime($this->start_date));
        }

        if (!empty($this->end_date)) {
            $this->end_date = date('Y-m-d', strtotime($this->end_date));

            /** agar ma'sul shaxs ma'suliyatidan ozod etilsa, status => 3 qilamiz */
            $this->status = self::STATUS_SAVED;
        }

        return true;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Deleted'),
            self::STATUS_SAVED => Yii::t('app','Saved')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hr_department_id' => Yii::t('app', 'Department'),
            'hr_employee_id' => Yii::t('app', 'Responsible person (Department)'),
            'start_date' => Yii::t('app', 'Date of appointment'),
            'end_date' => Yii::t('app', 'End date'),
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
    public function getHrDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'hr_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'hr_employee_id']);
    }

    public static function getResponsiblePersonByDepartmentId(int $departmentId) {
        if (empty($departmentId)) {
            return null;
        }

        $query = static::find()
            ->alias('hdrp')
            ->select([
                'he.id as id',
                'he.fish as title',
            ])
            ->leftJoin(['he' => 'hr_employee'], 'hdrp.hr_employee_id = he.id')
            ->andWhere(['hr_department_id' => $departmentId])
            ->andWhere('end_date IS NULL') // TODO: hozircha tugallangan sanasi qo'yilmagam hodimni oladi
            ->asArray();

        return $query->one();
    }

    public static function getResponsiblePersonMapList(int $departmentId = null)
    {
        $responsiblePerson = self::getResponsiblePersonByDepartmentId($departmentId);

        $result[$responsiblePerson['id']] = $responsiblePerson['title'];
        return $result;
    }
}
