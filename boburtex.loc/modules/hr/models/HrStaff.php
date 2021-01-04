<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "hr_staff".
 *
 * @property int $id
 * @property int $department_id
 * @property int $position_id
 * @property int $position_type_id
 * @property int $quantity
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrHiringEmployees[] $hrHiringEmployees
 * @property HrDepartments $department
 * @property HrPosition $position
 * @property HrPositionType $positionType
 * @property HrStaffCounters[] $hrStaffCounters
 * @property string $name
 */
class HrStaff extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['department_id', 'position_type_id', 'position_id', 'quantity', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['position_type_id', 'position_id', 'position_type_id'], 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrPosition::className(), 'targetAttribute' => ['position_id' => 'id']],
            [['position_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrPositionType::className(), 'targetAttribute' => ['position_type_id' => 'id']],
            [['department_id', 'position_id', 'position_type_id'], 'unique', 'targetAttribute' => ['department_id', 'position_id', 'position_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'department_id' => Yii::t('app', 'Department'),
            'position_id' => Yii::t('app', 'Position name'),
            'position_type_id' => Yii::t('app', 'Position type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'department.name' => Yii::t('app', 'Department'),

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrHiringEmployees()
    {
        return $this->hasMany(HrHiringEmployees::className(), ['staff_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPositionType()
    {
        return $this->hasOne(HrPositionType::className(), ['id' => 'position_type_id']);
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
    public function getHrStaffCounters()
    {
        return $this->hasMany(HrStaffCounters::className(), ['staff_id' => 'id']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Inactive'),
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    public static function countActiveStaffById(int $staffId) {
        $currentStaffQuantity = static::find()
            ->select('quantity')
            ->andWhere(['id' => $staffId])
            ->scalar();
        Yii::debug($currentStaffQuantity, 'curr staff qty');

        $inactiveStaffCount = static::find()
            ->select(['COUNT(hrsc.staff_id) AS count_busy_staff'])
            ->alias('hrs')
            ->leftJoin(['hrsc' => 'hr_staff_counters'], 'hrs.id = hrsc.staff_id')
            ->andWhere([
                'hrs.id' => $staffId,
                'hrs.status' => self::STATUS_ACTIVE,
                'hrsc.status' => self::STATUS_ACTIVE,
            ])
            ->groupBy('hrs.id')
            ->scalar();

        Yii::debug($inactiveStaffCount, 'inactive staff qty');

        return $currentStaffQuantity - $inactiveStaffCount;
    }

    public function getPositionTypeMap() {
        $result = HrPositionType::find()
            ->select(['id', 'name'])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->asArray()
            ->all();

        return ArrayHelper::map($result, 'id', 'name');
    }

    public function getName() {
        return "{$this->department->name} - {$this->position->name} - {$this->positionType->name}";
    }
}
