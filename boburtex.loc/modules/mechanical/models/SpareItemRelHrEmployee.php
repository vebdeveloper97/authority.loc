<?php

namespace app\modules\mechanical\models;

use app\modules\hr\models\HrCountry;
use Yii;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use app\modules\bichuv\models\SpareItem;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "spare_item_rel_hr_employee".
 *
 * @property int $id
 * @property int $spare_item_id
 * @property int $hr_employee_id
 * @property int $hr_department_id
 * @property string $add_info
 * @property string $interval_control_date
 * @property string $start_control_date
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property HrDepartments $hrDepartment
 * @property HrEmployee $hrEmployee
 * @property SpareItem $spareItem
 */
class SpareItemRelHrEmployee extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare_item_rel_hr_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['spare_item_id', 'hr_employee_id', 'hr_department_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info','company_name'], 'string'],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['hr_department_id' => 'id']],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::class, 'targetAttribute' => ['hr_employee_id' => 'id']],
            [['spare_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItem::class, 'targetAttribute' => ['spare_item_id' => 'id']],
            [['hr_country_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrCountry::class, 'targetAttribute' => ['spare_item_id' => 'id']],
            [['hr_employee_id','spare_item_id','hr_department_id','inv_number','installed_date'],'required'],
            [['manufacture_date','installed_date'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'spare_item_id' => Yii::t('app', 'Machine'),
            'hr_employee_id' => Yii::t('app', 'Javobgar shaxs'),
            'hr_department_id' => Yii::t('app', 'Department'),
            'hr_country_id' => Yii::t('app', 'Country'),
            'add_info' => Yii::t('app', 'Add Info'),
            'inv_number' => Yii::t('app', 'Inv Number'),
            'status' => Yii::t('app', 'Status'),
            'manufacture_date' => Yii::t('app', 'Manufacture Date'),
            'installed_date' => Yii::t('app', 'Installed Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public function afterFind()
    {
        if (!empty($this->manufacture_date)) {
            $this->manufacture_date = date('d.m.yy', strtotime($this->manufacture_date));
        }

        if (!empty($this->installed_date)) {
            $this->installed_date = date('d.m.yy', strtotime($this->installed_date));
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->manufacture_date)) {
            $this->manufacture_date = date('Y-m-d H:i:s', strtotime($this->manufacture_date));
        }

        if (!empty($this->installed_date)) {
            $this->installed_date = date('Y-m-d H:i:s', strtotime($this->installed_date));

        }
        return true;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployee()
    {
        return $this->hasOne(HrEmployee::class, ['id' => 'hr_employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItem()
    {
        return $this->hasOne(SpareItem::class, ['id' => 'spare_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrCountry()
    {
        return $this->hasOne(HrCountry::class, ['id' => 'hr_country_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSparePassportItems()
    {
        return $this->hasMany(SparePassportItems::class, ['sirhe_id' => 'id']);
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     *
     * Dastgoh va mashinalarni majburiy koriklar tartibini saqlash
     *
     */
    public function saveSparePassportItems($id, $data){
        $saved = false;
        foreach ($data as $item) {
            $newItem = new SparePassportItems([
                'sirhe_id' => $id,
                'spare_control_id' => $item['spare_control_id'],
                'interval_control_date' => $item['interval_control_date'],
                'control_date_type' => $item['control_date_type'],
            ]);
            if ($newItem->save()){
                $saved = true;
            }else{
                $saved =  false;
                break;
            }
        }
        return $saved;
    }

    public static function getSpareList($data = null){
        $spares = self::find()
            ->alias('sirhe')
            ->select(['sirhe.id','CONCAT(si.name," (", sirhe.inv_number,")") name'])
            ->leftJoin(['si' => 'spare_item'],'sirhe.spare_item_id = si.id')
            ->where(['sirhe.status' => self::STATUS_ACTIVE]);
        if (!is_null($data)){
            $spares = $spares->andFilterWhere([
                'like','name',$data['name']
            ]);
        }
        $spares = $spares
            ->asArray()
            ->all();

        if (!empty($spares))
            return $spares;
        else
            return false;
    }

    public static function getSpareListMap(){
        $spares = self::getSpareList();
        if($spares)
            return ArrayHelper::map($spares, 'id','name');
        return $spares;
    }


    public  static  function getSpareListTimeToCheck(){

        $sql = "SELECT 
                    * 
                FROM spare_item_rel_hr_employee sirhe
                ";

    }


}
