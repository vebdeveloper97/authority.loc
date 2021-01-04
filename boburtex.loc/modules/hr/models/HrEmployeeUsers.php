<?php

namespace app\modules\hr\models;

use app\models\Users;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%hr_employee_users}}".
 *
 * @property int $id
 * @property int $users_id
 * @property int $hr_employee_id
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property HrEmployee $hrEmployee
 * @property Users $users
 */
class HrEmployeeUsers extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public $cp = [];
    public static function tableName()
    {
        return '{{%hr_employee_users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['users_id', 'hr_employee_id'], 'required'],
            [['users_id', 'hr_employee_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'users_id' => Yii::t('app', 'Users ID'),
            'hr_employee_id' => Yii::t('app', 'Employee'),
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }

    public function getArrayHelperUser()
    {
        /**
         * users['id'] lar dan hr_employee_users['users_id'] lar ayirmasi
         */
        $model = Users::find()
            ->alias('u')
            ->andWhere(['NOT IN', 'u.id', HrEmployeeUsers::find()->alias('heu')->select(['heu.users_id'])])
            ->andWhere(['u.status' => 1])
            ->asArray()
            ->all();

        if(!empty($model)){
            $models = ArrayHelper::map($model, 'id', function ($m){
                return $m['username'].' '.$m['user_fio'].' '.$m['lavozimi'];
            });
            return $models;
        }
        return [];
    }

    public function getUsersUpdate($id){
        $employee = HrEmployeeUsers::find()
            ->where(['not in', 'hr_employee_id', $id])
            ->all();
        $array = [];
        foreach ($employee as $item){
            array_push($array, $item['users_id']);
        }
        $model = Users::find()->where(['not in', 'id', $array])->asArray()->all();
        if(!empty($model)){
            $models = ArrayHelper::map($model, 'id', 'username');
            return $models;
        }
        return [];
    }

    public function getStatusActive($array)
    {
        if(!empty($array)) {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $save = false;
                foreach ($array as $item){
                    $employee = HrEmployeeUsers::findOne(['users_id' => $item]);
                    if($employee->delete()){
                        $save = true;
                        $model = new Users();
                        $employee = new HrEmployeeUsers();
                    }
                    if(!$save)
                    {
                        break;
                    }
                }
                if($save)
                    $transaction->commit();
                else
                    $transaction->rollBack();
            }
            catch (\Exception $e){

            }
        }
    }

    public function getStatusInActive($array, $id)
    {
        if(!empty($array)) {
            $transaction = Yii::$app->db->beginTransaction();
            $m = new HrEmployeeUsers();
            try{
                $save = false;
                foreach ($array as $item){
                    $m->users_id = $item;
                    $m->hr_employee_id = $id;
                    $m->status = $this::STATUS_ACTIVE;
                    if($m->save()){
                        $save = true;
                        $m = new HrEmployeeUsers();
                    }
                    if(!$save)
                    {
                        break;
                    }
                }
                if($save)
                    $transaction->commit();
                else
                    $transaction->rollBack();
            }
            catch (\Exception $e){

            }
        }
    }

    public function getArrayHelperEmployee()
    {
        $model = ArrayHelper::map(HrEmployee::find()->asArray()->where(['status' => 1])->all(), 'id', 'fish');
        return $model;
    }

    public function getSave($data)
    {
        if(!$this->validate() && empty($data)){
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        $model = new HrEmployeeUsers();
        $saved = false;

        try{
            foreach ($data['users_id'] as $item){
                $model->status = $this::STATUS_ACTIVE;
                $model->users_id = $item;
                $model->hr_employee_id = $data['hr_employee_id'];

                if($model->save()){
                    $saved = true;
                    $model = new HrEmployeeUsers();
                }
                else{
                    $saved = false;
                    break;
                }
            }
            if($saved)
                $transaction->commit();
            else
                $transaction->rollBack();
        }
        catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }

    }

    public static function getEmployeeByUserId($userId)
    {
        return static::find()
            ->alias('uhd')
            ->select([
                'uhd.*',
                'he.fish as employee_name'
            ])
            ->leftJoin(['he' => 'hr_employee'], 'uhd.hr_employee_id = he.id')
            ->andWhere(['uhd.users_id' => $userId])
            ->asArray()
            ->limit(1)
            ->one();
    }
}
