<?php

namespace app\models;

use app\modules\admin\models\ToquvUserDepartment;
use app\modules\base\models\ModelOrdersResponsible;
use app\modules\base\models\ModelsList;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvProcessesUsers;
use app\modules\bichuv\models\BichuvTables;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvMakineProcesses;
use app\modules\toquv\models\ToquvMakineUsers;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $uid
 * @property string $user_fio
 * @property string $lavozimi
 * @property int $user_role
 * @property string $add_info
 * @property string $session_id
 * @property string $session_time
 * @property int $created_user
 * @property string $created_time
 * @property string $code
 * @property int $status
 * @property string $deleted_time
 *
 * @property BichuvDoc[] $bichuvDocs
 * @property BichuvDoc[] $bichuvDocs0
 * @property ModelOrdersResponsible[] $modelOrdersResponsibles
 * @property ModelsList[] $modelsLists
 * @property TikuvDocuments[] $tikuvDocuments
 * @property TikuvDocuments[] $tikuvDocuments0
 * @property ToquvDocuments[] $toquvDocuments
 * @property ToquvDocuments[] $toquvDocuments0
 * @property ToquvMakineProcesses[] $toquvMakineProcesses
 * @property ToquvMakineUserAction[] $toquvMakineUserActions
 * @property ToquvUserDepartment[] $toquvUserDepartments
 * @property UsersInfo $usersInfo
 * @property mixed authKey
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $confirm_password;
    public $roles = [];
    public $rfidKey;
    const SCENARIO_CREATE  = 'create';
    const SCENARIO_UPDATE  = 'update';
    const SCENARIO_DELETE  = 'delete';
    const ACTIVE = 1;
    const DELETED = 2;
    const INACTIVE = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username','user_fio'], 'required'],
            [['user_role', 'status'], 'integer'],
            [['password','confirm_password'], 'required','on' =>self::SCENARIO_CREATE],
            [['add_info'], 'required','on' =>self::SCENARIO_DELETE],
            ['confirm_password', 'required', 'when' => function ($model) {
                return !empty($model->password);
            }, 'whenClient' => "function (attribute, value) {
                return $('#users-password').val() !== '';
            }", 'on' => self::SCENARIO_UPDATE],
            ['confirm_password', 'compare', 'compareAttribute'=> 'password', 'message'=>Yii::t('app', "Passwords don't match")],
            [['add_info'], 'string'],
            [['roles', 'deleted_time'], 'safe'],
            [['username', 'lavozimi', 'session_id'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 50],
            [['user_fio'], 'string', 'max' => 255],
            [['session_id'], 'default', 'value' => 'unnecessary'],
            [['created_user'], 'default', 'value' => '1'],
            [['code'], 'string', 'max' => 30],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'confirm_password' => Yii::t('app', 'Parolni tasdiqlash'),
            'uid' => Yii::t('app', 'Uid'),
            'user_fio' => Yii::t('app', 'FIO'),
            'lavozimi' => Yii::t('app', 'Lavozimi'),
            'user_role' => Yii::t('app', 'User Role'),
            'add_info' => Yii::t('app', 'Add Info'),
            'session_id' => Yii::t('app', 'Session ID'),
            'session_time' => Yii::t('app', 'Session Time'),
            'created_user' => Yii::t('app', 'Created User'),
            'created_time' => Yii::t('app', 'Created Time'),
            'code' => Yii::t('app', 'Code'),
            'rfidKey' => Yii::t('app','Rfid Key'),
            'status' => Yii::t('app', 'Status'),
            'deleted_time' => Yii::t('app', 'Deleted Time'),
        ];
    }

    public function beforeSave($insert)
    {
        if(!empty($this->password)) {
            $this->setPassword($this->password);
        }else{
            $this->password = $this->oldAttributes['password'];
        }
        if($this->scenario==$this::SCENARIO_CREATE){
            $this->uid = $this->getMaxId();
            $this->created_user = Yii::$app->user->identity->id;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    public function getUserRole(){
        return $this->hasOne(UserRoles::className(),['id'=>'user_role']);
    }

    public function getUserName()
    {
        return $this->username;
    }
    public function getRfidkey()
    {
        return $this->hasMany(UserRfidKey::className(), ['user_id' => 'id']);
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /*foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }*/
        if(static::findOne(['accessToken'=>$token])){
            return static::findOne(['accessToken'=>$token]);
        }
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return  $this->password === md5($password);
    }

    public function setPassword($password)
    {
        $this->password = md5($password);
    }

    public static function getPermissions($id)
    {
        $permissions =  \app\modules\admin\models\AuthAssignment::find()->select('item_name')->where(['user_id' => $id])->asArray()->all();
        if(!empty($permissions)){
            $perms = [];

            foreach ($permissions as $perm){
                array_push($perms,$perm['item_name']);
            }

            return $perms;
        }

        return null;
    }

    public static function getUserNameById($id)
    {
        $model = self::find()->where(['id' => $id])->one();

        return $model->username;
    }


    public function getBelongToDepartments(){

    }

    public static function getMyDepartments($id)
    {

    }
    public static function getUserRoleList(){
        $role = UserRoles::find()->all();
        return ArrayHelper::map($role,'id','role_name');
    }
    public static function getToquvUserRoleList($department='toquv'){
        $role = UserRoles::find()->where(['department' => $department])->all();
        return ArrayHelper::map($role,'id','role_name');
    }
    private function getMaxId(){
        $max = Yii::$app->db->createCommand("
            SELECT `AUTO_INCREMENT`
            FROM  INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = DATABASE()
            AND   TABLE_NAME   = 'users'
        ")->queryScalar();
        return $max;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDocs()
    {
        return $this->hasMany(BichuvDoc::className(), ['from_employee' => 'id']);
    }
    public function getBichuvDocs0()
    {
        return $this->hasMany(BichuvDoc::className(), ['to_employee' => 'id']);
    }
    public function getModelOrdersResponsibles()
    {
        return $this->hasMany(ModelOrdersResponsible::className(), ['users_id' => 'id']);
    }
    public function getModelsLists()
    {
        return $this->hasMany(ModelsList::className(), ['users_id' => 'id']);
    }
    public function getToquvDocuments()
    {
        return $this->hasMany(ToquvDocuments::className(), ['from_employee' => 'id']);
    }
    public function getToquvDocuments0()
    {
        return $this->hasMany(ToquvDocuments::className(), ['to_employee' => 'id']);
    }
    public function getToquvMakineProcesses()
    {
        return $this->hasMany(ToquvMakineProcesses::className(), ['ended_by' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvMakineUsers()
    {
        return $this->hasMany(ToquvMakineUsers::className(), ['users_id' => 'id']);
    }
    /*public function getToquvMakineUserActions()
    {
        return $this->hasMany(ToquvMakineUserAction::className(), ['users_id' => 'id']);
    }*/
    public function getToquvUserDepartments()
    {
        return $this->hasMany(ToquvUserDepartment::className(), ['user_id' => 'id']);
    }
    public function getUsersInfo()
    {
        return $this->hasOne(UsersInfo::className(), ['users_id' => 'id']);
    }

    public static function getStatusList($key=null)
    {
        $list = [
            Users::ACTIVE => Yii::t('app', 'Faol'),
            Users::DELETED => Yii::t('app', "O'chirilgan"),
        ];
        if($key){
            return $list[$key];
        }
        return $list;
    }
    public static function getUserList($array=null,$code=null,$fromDepartment=false){
        $users = Users::find()->with('usersInfo')->where(['users.status'=>1]);
        if($code) {
            if($fromDepartment){
                if(is_array($code)){
                    $user_department = ToquvUserDepartment::find()->joinWith('department')->select('user_id')->where(['in','toquv_departments.token',$code]);
                }else {
                    $user_department = ToquvUserDepartment::find()->joinWith('department')->select('user_id')->where(['toquv_departments.token' => $code]);
                }
                if(!$user_department){
                    return [];
                }
            }else{
                if(is_array($code)){
                    $user_role = UserRoles::find()->select('id')->where(['in','code',$code]);
                }else {
                    $user_role = UserRoles::find()->select('id')->where(['code' => $code])->asArray()->one();
                }
                if(!$user_role){
                    return [];
                }
            }
            if ($user_role) {
                $users = $users->andWhere(['user_role' => $user_role]);
            }
            if ($user_department) {
                $users = $users->andWhere(['users.id' => $user_department]);
            }
        }
        $users = $users->andWhere(['!=','id',1])->asArray()->all();
        if($array){
            $user = [];
            $user['list'] = [];
            if(!empty($users)) {
                foreach ($users as $key) {
                    $user['list'][$key['id']] = [
                        'data-id' => $key['id'],
                        'data-name' => $key['code'] . ' - ' . $key['user_fio'] . ' - ' . $key['usersInfo']['tabel'],
                        'data-table' => $key['usersInfo']['tabel'],
                    ];
                }
            }
            return $user['list'];
        }
        if(!empty($users)) {
            return ArrayHelper::map($users, 'id', function ($m) {
                return $m['code'] . ' - ' . $m['user_fio'] . ' - ' . $m['usersInfo']['tabel'];
            });
        }else{
            return [];
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvTables()
    {
        return $this->hasMany(BichuvTables::className(), ['id' => 'bichuv_tables_id'])->viaTable('bichuv_tables_users', ['users_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvProcessesUsers()
    {
        return $this->hasMany(BichuvProcessesUsers::className(), ['users_id' => 'id']);
    }
}
