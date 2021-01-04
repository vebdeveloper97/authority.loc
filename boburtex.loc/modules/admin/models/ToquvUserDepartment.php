<?php

namespace app\modules\admin\models;

use Yii;
use app\modules\toquv\models\ToquvDepartments;
use app\models\Users;
use app\modules\toquv\models\BaseModel;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_user_department".
 *
 * @property int $id
 * @property int $user_id
 * @property int $department_id
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvDepartments $department
 * @property Users $user
 */
class ToquvUserDepartment extends BaseModel
{
    /**
     * @var integer Ishlaydigan bo'limlar
     */
    const OWN_DEPARTMENT_TYPE = 0;

    /**
     * @var integer Ishlay oladigan bo'limalar
     */
    const FOREIGN_DEPARTMENT_TYPE = 1;

    /**
     * @var integer $departments_2 ishlay oladigan bo'limlar, [type=1]
     */
    public $departments_2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_user_department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user_id','required'],
            [['user_id', 'department_id', 'created_by', 'status', 'type', 'created_at', 'updated_at'], 'integer'],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'department_id' => Yii::t('app', 'Department ID'),
            'type' => Yii::t('app', 'Type'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return string|null
     */
    public function getUserName(){
        $user = Users::findOne($this->created_by);

        if($user !== null){
            return $user->user_fio;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getBelongToDepartments(){

        $depIds = self::find()
        ->select(['department_id'])
        ->where(['user_id' => $this->user_id, 'type' => self::OWN_DEPARTMENT_TYPE])
        ->asArray()
        ->all();
        
        if(!empty($depIds)){

            $allIds = ArrayHelper::getColumn($depIds,'department_id');
            $departmentNames = ToquvDepartments::find()
            ->select(['name'])
            ->where(['in','id', $allIds])
            ->asArray()
            ->all();
            $res = '';

            foreach ($departmentNames as $name){
                $res .= "<span class='each-department-tags'>{$name['name']}</span>";
            }

            return $res;
        }
        return null;
    }

    public function getBelongToDepartments2(){

        $depIds = self::find()
        ->select(['department_id'])
        ->where(['user_id' => $this->user_id, 'type' => self::FOREIGN_DEPARTMENT_TYPE])
        ->asArray()
        ->all();

        if(!empty($depIds)){

            $allIds = ArrayHelper::getColumn($depIds,'department_id');
            $departmentNames = ToquvDepartments::find()
            ->select(['name'])
            ->where(['in','id', $allIds])
            ->asArray()
            ->all();
            $res = '';

            foreach ($departmentNames as $name){
                $res .= "<span class='each-department-tags'>{$name['name']}</span>";
            }

            return $res;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getDepartments($isTransfer = 0){

        $res = ToquvDepartments::find()

        ->select(['id','name'])
        ->asArray()
        ->orderBy(['id' => SORT_ASC])
        ->all();
        
        if(!empty($res)){
            return ArrayHelper::map($res,'id','name');
        }

        return [];
    }

    /**
     * @return array
     */
    public function getUsers(){
        
        $users = Users::find()
        ->select(['id','user_fio','username', 'lavozimi'])
        ->asArray()
        ->orderBy(['user_fio' => SORT_ASC])
        ->all();

        if(!empty($users)){
            $result = [];

            foreach ($users as $user) {
                $result[$user['id']] = $user['username'] . " - " . $user['user_fio'] . " - " . $user['lavozimi'];
            }
            
            return $result;
        }

        return [];
    }

    public static function getUserDeps($id)
    {
        $deps = self::find()->select(['toquv_user_department.*','toquv_departments.id as dep_id','toquv_departments.name as dep_name'])
            ->leftJoin('toquv_departments','toquv_user_department.department_id=toquv_departments.id')
            ->where(['toquv_user_department.user_id' => $id])
            ->asArray()
            ->all();
            
        return ArrayHelper::map($deps,'dep_id','dep_name');
    }


}
