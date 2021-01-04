<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%hr_employee}}".
 *
 * @property int $id
 * @property string $fish
 * @property string $address
 * @property string $phone
 * @property string $birth_date
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrEmployeeRelAttachment[] $hrEmployeeRelAttachments
 * @property HrEmployeeStudy[] $hrEmployeeStudies
 * @property bool $dataSaves
 * @property HrEmployeeWorkPlace[] $hrEmployeeWorkPlaces
 * @property-read HrEmployeeUsers $hrEmployeeUser
 * @property-read HrNation $hrNation
 * @property EmployeeRelSkills[] $employeeRelSkills
 */
class HrEmployee extends BaseModel
{
    public $path;
    public $attachmentList;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hr_employee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['fish', 'address', 'birth_date', 'gender','table_number','by_whom','hr_nation_id'], 'required'],
            [
                'pasport_series',
                'required',
                /*'when' => function($model, $attribute) {
                    $birthDate = \DateTime::createFromFormat('d.m.Y', $model->birth_date);
                    $now = new \DateTime();
                    $diff = $now->diff($birthDate);
                    return $diff->y >= 17;
                },*/
                /*'whenClient' => "function(attribute, value) {
                    function _calculateAge(birthday) { // birthday is a date
                        var ageDifMs = Date.now() - birthday.getTime();
                        var ageDate = new Date(ageDifMs); // miliseconds from epoch
                        return Math.abs(ageDate.getUTCFullYear() - 1970);
                    }
                    
                    console.log(attribute);
                    console.log(_calculateAge(new Date(value)));
                    
                    return _calculateAge(new Date(value)) >= 17;
                }",*/
            ],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            ['birth_date', 'safe'],
            [['fish', 'address'], 'string', 'max' => 50],
            [['add_info'], 'string'],
            [['phone'], 'string', 'max' => 25],
            ['pasport_series', 'unique'],
            ['path','safe'],
            [['hr_nation_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrNation::className(), 'targetAttribute' => ['hr_nation_id' => 'id']],
            [['military_rank','serviceability','special_account_num','military_register_num','card_number','inn','inps'],'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => Yii::t('app','Image'),
            'fish' => Yii::t('app', 'Surname and first name'),
            'address' => Yii::t('app', 'Address'),
            'phone' => Yii::t('app', 'Phone number'),
            'gender' => Yii::t('app', 'Sex'),
            'table_number' => Yii::t('app', 'Tabel №'),
            'birth_date' => Yii::t('app', 'Date of Birth'),
            'status' => Yii::t('app', 'Status'),
            'by_whom' => Yii::t('app', 'By Whom'),
            'pasport_series' => Yii::t('app', 'Passport number'),
            'military_rank' => Yii::t('app', 'Military Rank'),
            'serviceability' => Yii::t('app', 'Serviceability'),
            'special_account_num' => Yii::t('app', 'Special Account №'),
            'military_register_num' => Yii::t('app', 'Military Register №'),
            'card_number' => Yii::t('app', 'Card №'),
            'inn' => Yii::t('app', 'INN'),
            'inps' => Yii::t('app', 'INPS'),
            'hr_nation_id' => Yii::t('app', 'Nationality'),
            'created_at' => Yii::t('app','Created At'),
            'updated_at' => Yii::t('app','Updated At'),
            'created_by' => Yii::t('app','Created By'),
            'updated_by' => Yii::t('app','Updated By'),
            'add_info' => Yii::t('app', 'Add Info')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeRelAttachments()
    {
        return $this->hasMany(HrEmployeeRelAttachment::className(), ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeUser()
    {
        return $this->hasOne(HrEmployeeUsers::class, ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeStudies()
    {
        return $this->hasMany(HrEmployeeStudy::className(), ['hr_employee_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrNation()
    {
        return $this->hasOne(HrNation::className(), ['id' => 'hr_nation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeWorkPlaces()
    {
        return $this->hasMany(HrEmployeeWorkPlace::className(), ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeRelSkills()
    {
        return $this->hasMany(EmployeeRelSkills::class, ['hr_employee_id' => 'id']);
    }

    // malumotlarni saqlash
    public function getDataSaves()
    {
        $this->status = self::STATUS_ACTIVE;
        if($this->save())
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function getEmployeeAvatar($id){
        $employeeAvatar = HrEmployeeRelAttachment::find()
            ->where(['hr_employee_id' => $id,'type' => self::EMPLOYEE_AVATAR_TYPE])->one();

        return $employeeAvatar;
    }

    public static function tableNumberGenerator(){
        $lastTableNumber = self::find()->orderBy(['id' => SORT_DESC])->asArray()->one();
        if(empty($lastTableNumber)){
           return 1;
        }
        else{
            return ++$lastTableNumber['table_number'];
        }
    }

    // excelni tekshirish
    public function getIsExcel($data)
    {
        $array = [];
        if(!empty($data)){
            for($i = 2; $i < count($data); $i++){
                $key = trim($data[$i]['H']).trim($data[$i]['I']);
                if(isset($array[$key])){
                    return false;
                }
                $array[$key] = 1;
            }
        }
        return true;
    }

    // db bilan tekshirish
    public function getIsDb($data)
    {
        $array = [];
        for($i = 2; $i < count($data); $i++){
            $array[] = trim($data[$i]['H']).trim($data[$i]['I']);
        }

        $result = HrEmployee::find()
            ->where(['in', 'pasport_series', $array])
            ->all();

        if(empty($result))
            return true;
        return false;
    }

    // date formatlab chiqarish
    public function getDateFormat($date){
        $date = new \DateTime($date);
        return $date->format('Y-m-d');
    }

    // gender ni tanlash
    public function getGender($gender)
    {
        $result = 0;
        if($gender === 'Мужской'){
            $result = 1;
        }
        elseif($gender === 'Женский'){
            $result = 2;
        }
        else{
            $result = 0;
        }
        return $result;
    }
    // data excel import
    public function getExcelImport($data)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $saved = false;
            for($i = 2; $i < count($data); $i++){
                if(!empty($data[$i]['C'])) {
                    $pasport = rtrim($data[$i]['H']).rtrim($data[$i]['I']);
                    $model = new HrEmployee();
                    $model->fish = $data[$i]['C'];
                    $model->phone = $data[$i]['M'];
                    $model->birth_date = $this->getDateFormat($data[$i]['E']);
                    $model->pasport_series = $pasport;
                    $model->inn = $data[$i]['K'];
                    $model->inps = $data[$i]['Q'];
                    $model->gender = $this->getGender($data[$i]['O']);
                    $model->status = HrEmployee::STATUS_ACTIVE;
                    if($model->save(false)){
                        $saved = true;
                        unset($model);
                    }
                    else{
                        $saved = false;
                    }
                }
            }
            if($saved)
            {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                $transaction->commit();
                return true;
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                $transaction->rollBack();
                return false;
            }
        }
        catch(\Exception $e){
            Yii::info('Error data '.$e->getMessage(),'save');
        }
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->birth_date)) {
                $this->birth_date = date('Y-m-d', strtotime($this->birth_date));
            }
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->birth_date = date('d.m.Y', strtotime($this->birth_date));

    }

    public static function getList($employeeId = null, $asArray = true) {
        $query = static::find()
            ->select(['id', 'fish'])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->andFilterWhere(['id' => $employeeId])
            ->asArray($asArray);
        return $query->all();
    }

    public static function getListMap($employeeId = null) {
        return ArrayHelper::map(self::getList($employeeId), 'id', 'fish');
    }

}
