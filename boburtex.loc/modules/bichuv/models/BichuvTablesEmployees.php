<?php

namespace app\modules\bichuv\models;

use app\modules\hr\models\HrEmployee;
use phpDocumentor\Reflection\Types\Self_;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_tables_employees".
 *
 * @property int $id
 * @property int $bichuv_table_id
 * @property int $hr_employee_id
 * @property string $from_date
 * @property string $end_date
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BichuvTables $bichuvTable
 * @property HrEmployee $hrEmployee
 */
class BichuvTablesEmployees extends BaseModel
{

    const SCENARIO_UPDATE = "scenario-update";
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_tables_employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_employee_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['from_date', 'end_date'], 'safe'],
            [['add_info'], 'string'],
            [['bichuv_table_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvTables::className(), 'targetAttribute' => ['bichuv_table_id' => 'id']],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
            [['bichuv_table_id','hr_employee_id','from_date'],'required'],
            ['add_info', 'required' , 'on' => self::SCENARIO_UPDATE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bichuv_table_id' => Yii::t('app', 'Bichuv Tables'),
            'hr_employee_id' => Yii::t('app', 'Employee'),
            'from_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
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
    public function getBichuvTable()
    {
        return $this->hasOne(BichuvTables::className(), ['id' => 'bichuv_table_id']);

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'hr_employee_id']);
    }

    /**
     *
     */
    public function afterFind()
    {
        if (!empty($this->from_date)) {
            $this->from_date = date('d.m.Y', strtotime($this->from_date));
        }

        if (!empty($this->end_date)) {
            $this->end_date = date('d.m.Y', strtotime($this->end_date));
        }
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->from_date)) {
            $this->from_date = date('Y-m-d', strtotime($this->from_date));
        }

        if (!empty($this->end_date)) {
            $this->end_date = date('Y-m-d', strtotime($this->end_date));
        }

        return true;
    }

    public function allModelSave($model =null, $newAllModel = null){

        $saved = false;
        if(!empty($model['bichuv_table_id'])){
            foreach ($model['bichuv_table_id'] as $item){
                $isExists = self::find()->where([
                    'hr_employee_id' => $model['hr_employee_id'],
                    'bichuv_table_id' => $item,
                    'status' => self::STATUS_ACTIVE
                ])->asArray()->exists();
                if(!$isExists){
                    $newModel =  new BichuvTablesEmployees([
                        'bichuv_table_id' => $item,
                        'hr_employee_id' => $model['hr_employee_id'],
                        'from_date' => $model['from_date'],
                        'end_date' => $model['end_date'],
                    ]);
                    if($newAllModel !== null){
                        $newModel['from_date'] = date('Y-m-d');
                        if(is_numeric(array_search($newModel['bichuv_table_id'],$newAllModel))){
                            if($newModel->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }else{

                        if($newModel->save()){
                            $saved = true;
                        }else{
                            $saved = false;
                            break;
                        }

                    }
                }
            }
        }

        return $saved;
    }


    public function modelStatusChange($models = null, $add_info = null){

        $saved = false;
        if($models !== null && $add_info !== null){
            foreach ($models as $model){
                $model['end_date'] = date('Y-m-d');
                $model['add_info'] = $add_info;
                $model['status'] = self::STATUS_SAVED;

                if ($model->save()){
                    $saved = true;
                }else{
                    $saved = false;
                    break;
                }
            }
        }
        return $saved;
    }

    /**
     * @param $employee_id
     * @return string
     */
    public static function getTableListByEmployee($employee_id){
        $tables = self::find()->alias('bte')
            ->select(['bt.name','bp.name p_name'])
            ->leftJoin(['bt' => 'bichuv_tables'],'bte.bichuv_table_id = bt.id')
            ->leftJoin(['bp' => 'bichuv_processes'],'bt.bichuv_processes_id = bp.id')
            ->where(['bte.hr_employee_id' => $employee_id, 'bte.status' => self::STATUS_ACTIVE])
            ->asArray()
            ->all();

        if (!empty($tables)){
            $result = "";
            foreach ($tables as $table){
                $result .= "<code style='background:#F9F2F4 ; padding: 5px; display: inline-block; color:#C7254E; border-radius: 5px; margin: 2px 0'>{$table['name']} ({$table['p_name']})</code> ";
            }
            return $result;
        }
    }

    public static function getEmployeesByTables($id = null){

        $employees = self::find()->alias('bte')
            ->select(['he.id','CONCAT("- ",he.fish," -") fish'])
            ->leftJoin(['he' => 'hr_employee'],'bte.hr_employee_id = he.id')
            ->where(['bte.status' => self::STATUS_ACTIVE])
            ->groupBy(['hr_employee_id'])
            ->asArray();

        if(!empty($id)){
            $employees->andWhere(['bichuv_table_id' => $id]);
            return $employees->all();
        }

        if (!empty($employees)){
            return ArrayHelper::map($employees->all(), 'id','fish');
        }
        return [];
    }

}
