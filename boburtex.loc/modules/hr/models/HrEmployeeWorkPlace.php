<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%hr_employee_work_place}}".
 *
 * @property int $id
 * @property int $hr_employee_id
 * @property string $from
 * @property string $to
 * @property string $organization
 * @property string $position
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrEmployee $hrEmployee
 */
class HrEmployeeWorkPlace extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hr_employee_work_place}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_employee_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['organization', 'position'], 'string', 'max' => 50],
            [['from', 'to'], 'date', 'format' => 'php: d.m.Y'],
            ['from', 'validateDates'],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
        ];
    }

    public function validateDates(){
        if(strtotime($this->to) < strtotime($this->from)){
            $this->addError('from', Yii::t('app', 'Please give correct Start and End dates'));
            $this->addError('to', Yii::t('app','Please give correct Start and End dates'));
        }
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
            'organization' => 'Organization',
            'position' => 'Position',
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

    // employee_id bilan saqlash
    public function getSaves($data, $id)
    {
        if(empty($id)){
            return false;
        }

        $saved = false;
        $model = new HrEmployeeWorkPlace();

        foreach ($data as $item){
            if(!empty($item['organization'])){
                $model->status = self::STATUS_ACTIVE;
                $model->hr_employee_id = $id;
                $model->organization = $item['organization'];
                $model->from = $item['from'];
                $model->to = $item['to'];
                $model->position = $item['position'];
                if($model->save()){
                    $saved = true;
                    $model = new HrEmployeeWorkPlace();
                }
            }
            else{
                break;
            }
        }
        return true;
    }

    // yangilashda ochirish
    public static function getRemoveEmployeeId($id)
    {
        $model = HrEmployeeWorkPlace::deleteAll([
            'hr_employee_id' => $id,
        ]);

    }
}
