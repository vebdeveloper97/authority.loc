<?php

namespace app\modules\admin\models;

use app\models\Users;
use app\modules\hr\models\HrDepartments;
use Mpdf\Tag\Hr;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_hr_departments".
 *
 * @property int $user_id
 * @property int $hr_departments_id
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Users $user
 * @property HrDepartments $hrDepartments
 * @property-read array $users
 * @property-read null|string $userName
 * @property-read null|string $belongToDepartments2
 * @property-read null|string $belongToDepartments
 * @property int $id [int]
 */
class UsersHrDepartments extends \app\models\BaseModel
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
        return 'users_hr_departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user_id','required'],
            [['user_id', 'hr_departments_id', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['hr_departments_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_departments_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'hr_departments_id' => Yii::t('app', "Relevant sections"),
            'departments_2' => Yii::t('app', "Sections that can work"),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartments()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'hr_departments_id']);
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
            ->select(['hr_departments_id'])
            ->where(['user_id' => $this->user_id, 'type' => self::OWN_DEPARTMENT_TYPE])
            ->asArray()
            ->all();

        if(!empty($depIds)){

            $allIds = ArrayHelper::getColumn($depIds,'hr_departments_id');
            $departmentNames = HrDepartments::find()
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
            ->select(['hr_departments_id'])
            ->where(['user_id' => $this->user_id, 'type' => self::FOREIGN_DEPARTMENT_TYPE])
            ->asArray()
            ->all();

        if(!empty($depIds)){

            $allIds = ArrayHelper::getColumn($depIds,'hr_departments_id');
            $departmentNames = HrDepartments::find()
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

        $res = HrDepartments::find()

//            ->select(['id','name'])
            ->andWhere(['lft' => 1])
            ->asArray()
            ->addOrderBy(['lft' => SORT_ASC, 'root' => SORT_ASC])
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
        $deps = self::find()
            ->select(['users_hr_departments.*','hr_departments.id as dep_id','hr_departments.name as dep_name'])
            ->leftJoin('hr_departments','users_hr_departments.hr_departments_id=hr_departments.id')
            ->where(['users_hr_departments.user_id' => $id])
            ->asArray()
            ->all();

        return ArrayHelper::map($deps,'dep_id','dep_name');
    }
}
