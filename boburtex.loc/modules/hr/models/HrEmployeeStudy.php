<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "{{%hr_employee_study}}".
 *
 * @property int $id
 * @property int $hr_employee_id
 * @property string $from
 * @property string $to
 * @property string $where_studied
 * @property string $level
 * @property int $degree
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrEmployee $hrEmployee
 */
class HrEmployeeStudy extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hr_employee_study}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['hr_employee_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['where_studied', 'level'], 'string', 'max' => 50],
            [['from', 'to'], 'date', 'format' => 'php: d.m.Y'],
            ['from', 'validateDates'],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
            [['degree'], 'exist', 'skipOnError' => true, 'targetClass' => HrStudyDegree::className(), 'targetAttribute' => ['degree' => 'id']],
        ];
    }

    public function validateDates(){
        if(strtotime($this->to) < strtotime($this->from)){
            $this->addError('from', Yii::t('app', 'Please give correct Start and End dates'));
            $this->addError('to', Yii::t('app','Please give correct Start and End dates'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hr_employee_id' => 'Hr Employee ID',
            'from' => 'From',
            'to' => 'To',
            'where_studied' => 'Where Studied',
            'level' => Yii::t('app', 'Level'),
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
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
    public function getStudyDegree()
    {
        return $this->hasOne(HrStudyDegree::class, ['id' => 'degree']);
    }

    public function getSaves($data, $id)
    {
        if(empty($id) && !$this->validate()){
            return false;
        }
        $model = new HrEmployeeStudy();
        $saved = false;

        foreach ($data as $item){
            if(!empty($item['where_studied'])){
                $model->status = self::STATUS_ACTIVE;
                $model->hr_employee_id = $id;
                $model->where_studied = $item['where_studied'];
                $model->from = $item['from'];
                $model->to = $item['to'];
                $model->level = $item['level'];
                if($model->save()){
                    $saved = true;
                    $model = new HrEmployeeStudy();
                }
                else{
                    return false;
                }
            }
            else{
                break;
            }
        }
            return true;

    }

    // update qilishda malumotlarni ochirish
    public static function getRemoveEmployeeId($id)
    {
        $model = HrEmployeeStudy::deleteAll([
            'hr_employee_id' => $id
        ]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->from)) {
                $this->from = date('Y-m-d', strtotime($this->from));
            }
            if (!empty($this->to)) {
                $this->to = date('Y-m-d', strtotime($this->to));
            }
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->from = date('d.m.Y', strtotime($this->from));
        $this->to = date('d.m.Y', strtotime($this->to));

    }
}
