<?php

namespace app\modules\hr\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class HrEmployeeForm
 * @package app\modules\hr\models
 *
 * @property mixed $employeeList
 */
class HrEmployeeForm extends Model
{
    public $employee_id;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['employee_id'],'integer'],
        ];
    }


    public function search($params){

        $this->load($params);

        $employeeId = '';
        if(!empty($this->employee_id)){
            $employeeId = " AND e.id = {$this->employee_id}";
        }
        $sql = "select * from hr_employee e where 1=1 %s;";
        $sql = sprintf($sql, $employeeId);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public function getEmployeeList(){
        $employee = HrEmployee::find()->where(['status' => 1])->asArray()->all();
        return ArrayHelper::map($employee,'id', 'fish');
    }
}
