<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "employee_skills".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property EmployeeRelSkills[] $employeeRelSkills
 * @property HrEmployee[] $hrEmployees
 */
class HrEmployeeSkills extends \app\modules\hr\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_employee_skills';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
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
    public function getEmployeeRelSkills()
    {
        return $this->hasMany(EmployeeRelSkills::className(), ['employee_skills_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['id' => 'hr_employee_id'])
            ->viaTable('employee_rel_skills', ['employee_skills_id' => 'id']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Inactive')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    public static function getList($skillId = null, $asArray = true) {
        $query = static::find()
            ->select(['id', 'name'])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->andFilterWhere(['id' => $skillId])
            ->asArray($asArray);

        return $query->all();
    }

    public static function getListMap() {
        return ArrayHelper::map(self::getList(), 'id', 'name');
    }

    public static function getSkillById($skillId) {
        $skills = self::getList($skillId);
        return isset($skills[0]) ? $skills[0]['name'] : null;
    }
}
