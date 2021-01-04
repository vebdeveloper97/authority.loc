<?php

namespace app\modules\mobile\models;

use app\modules\hr\models\HrEmployee;
use app\modules\hr\models\HrEmployeeUsers;
use Yii;

/**
 * This is the model class for table "mobile_tables_rel_hr_employee".
 *
 * @property int $id
 * @property int $mobile_tables_id
 * @property int $hr_employee_id
 * @property string $start_date
 * @property string $end_date
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrEmployee $hrEmployee
 * @property MobileTables $mobileTables
 */
class MobileTablesRelHrEmployee extends \app\modules\mobile\models\BaseModel
{
    const SCENARIO_CREATE = 'scenario-create';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mobile_tables_rel_hr_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_employee_id', 'start_date'], 'required'],
            ['mobile_tables_id', 'required', 'on' => [self::SCENARIO_CREATE]],
            [['mobile_tables_id', 'hr_employee_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'id'], 'integer'],
            [['start_date', 'end_date'], 'date', 'format' => 'php: d.m.Y'],
            [['end_date'], 'compare', 'compareAttribute' => 'start_date', 'operator' => '>='],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
            [['mobile_tables_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileTables::className(), 'targetAttribute' => ['mobile_tables_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mobile_tables_id' => Yii::t('app', 'Mobile Tables ID'),
            'hr_employee_id' => Yii::t('app', 'Hr Employee ID'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
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
    public function getHrEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'hr_employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMobileTables()
    {
        return $this->hasOne(MobileTables::className(), ['id' => 'mobile_tables_id']);
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

    public static function getResponsiblePersonByTableId(int $tableId) {
        if (empty($tableId)) {
            return null;
        }

        $query = static::find()
            ->alias('mtrhe')
            ->select([
                'he.id as id',
                'he.fish as title',
            ])
            ->leftJoin(['he' => 'hr_employee'], 'mtrhe.hr_employee_id = he.id')
            ->andWhere(['mobile_tables_id' => $tableId])
            ->andWhere('end_date IS NULL') // TODO: hozircha tugallangan sanasi qo'yilmagam hodimni oladi
            ->asArray();

        return $query->one();
    }

    public static function getResponsiblePersonMapList(int $tableId = null)
    {
        $responsiblePerson = self::getResponsiblePersonByTableId($tableId);

        $result[$responsiblePerson['id']] = $responsiblePerson['title'];
        return $result;
    }

    public static function getResponsiblePersonIdByUserId(int $userId)
    {
        return HrEmployeeUsers::find()
            ->alias('heu')
            ->select('he.id')
            ->innerJoin(['he' => 'hr_employee'], 'heu.hr_employee_id = he.id')
            ->innerJoin(['mtrhe' => 'mobile_tables_rel_hr_employee'], 'he.id = mtrhe.hr_employee_id and mtrhe.status = 1')
            ->andWhere(['heu.users_id' => $userId])
            ->scalar();
    }
}
