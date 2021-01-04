<?php

namespace app\modules\hr\models;

use app\components\OurCustomBehavior;
use app\models\Users;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\base\models\Attachments;
use app\modules\wms\models\WmsDepartmentArea;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "hr_departments".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $type
 * @property string $token
 *
 * @property Users $createdBy
 * @property HrDepartmentsAttachments[] $hrDepartmentsAttachments
 * @property Attachments[] $attachments
 * @property HrDepartmentsInfo $hrDepartmentsInfo
 */
class HrDepartments extends \kartik\tree\models\Tree
{
    const TYPE_ORGANIZATION = 1;
    const TYPE_DEPARTMENT = 2;

    const STATUS_ACTIVE     = 1;
    const STATUS_INACTIVE   = 2;
    const STATUS_SAVED      = 3;

    const TOKEN_BICHUV = 'BICHUV';
    const TOKEN_TMO = 'TMO';
    const TOKEN_MODEL_ROOM = 'MODEL_XONA';
    const TOKEN_MATERIAL_WAREHOUSE = 'MATOOMBOR';
    const TOKEN_TIKUV = 'TIKUV';
    const TOKEN_TAYYORLOV = 'TAYYORLOV';
    const TOKEN_ACS_WAREHOUSE = 'ACS_WAREHOUSE';
    const TOKEN_KONSTRUKTOR = 'KONSTRUKTOR';
    const TOKEN_PLAN = 'PLAN';
    const TOKEN_MARKETING = 'MARKETING';
    const TOKEN_TAMINOT = 'TAMINOT';

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours[] = [ 'class' => OurCustomBehavior::className()];
        $behaviours[] = [ 'class' => TimestampBehavior::className()];

        return $behaviours;
    }

    public function afterValidate()
    {
        if($this->hasErrors()){
            $res = [
                'status' => 'error',
                'table' => self::tableName() ?? '',
                'url' => \yii\helpers\Url::current([], true),
                'message' => $this->getErrors(),
            ];
            Yii::error($res, 'save');
        }
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Deleted'),
            self::STATUS_SAVED => Yii::t('app','Saved')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'token'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'type'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['token'], 'string', 'max' => 50],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartmentsAttachments()
    {
        return $this->hasMany(HrDepartmentsAttachments::className(), ['hr_departments_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(Attachments::className(), ['id' => 'attachments_id'])
            ->viaTable('hr_departments_attachments', ['hr_departments_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartmentsInfo()
    {
        return $this->hasOne(HrDepartmentsInfo::className(), ['department_id' => 'id']);
    }

    public static function getDepartmentsByToken($token = null) {
        $currUser = Yii::$app->user->id;
        $depIds = UsersHrDepartments::find()->select(['hr_departments_id'])->where(['user_id' => $currUser])->asArray()->column();
        $all = $depIds; //ArrayHelper::getColumn($depIds,'hr_departments_id');
        if(!empty($token)){
            return static::find()
                ->where(['in','token',$token])
                ->addOrderBy('root','lft');
        }else if(!empty($all)){
            return static::find()
                ->where(['id' => $all])
                ->addOrderBy('root, lft');
        }
        return static::find()
            ->where(['id' => null])
            ->addOrderBy('root, lft');
    }

    public static function getDepartmentIdByToken($token)
    {
        return static::find()
            ->select('id')
            ->andWhere(['token' => $token])
            ->scalar();
    }

    public static function getDepartmentInstanceByToken(string $token)
    {
        return static::find()
            ->andWhere(['token' => $token])
            ->one();
    }

    public static function getDepartmentInstanceById($department_id)
    {
        return static::find()
            ->andWhere(['id' => $department_id])
            ->one();
    }

    /**
     * type 0 bo'lsa tegishli departmentlar qaytaradi
     * type 1 bo'lsa ishlay oladigan departmentlar qaytaradi
     * @param null $type
     * @return \kartik\tree\models\TreeQuery|mixed
     */
    public static function getDepartmentsForCurrentUser($type = null) {
        $currUser = Yii::$app->user->id;
        $depIds = UsersHrDepartments::find()
            ->select(['hr_departments_id'])
            ->where(['user_id' => $currUser])
            ->andFilterWhere(['type' => $type])
            ->asArray()
            ->column();
        $all = $depIds; //ArrayHelper::getColumn($depIds,'hr_departments_id');
        if(!empty($all)){
            return static::find()
                ->where(['id' => $all])
                ->addOrderBy('root, lft');
        }
        return static::find()
//            ->where(['id' => null])
            ->addOrderBy('root, lft');
    }
}
