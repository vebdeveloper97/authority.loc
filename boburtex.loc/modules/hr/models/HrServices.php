<?php

namespace app\modules\hr\models;

use Yii;
use app\models\PulBirligi;

/**
 * This is the model class for table "hr_services".
 *
 * @property int $id
 * @property int $hr_employee_id
 * @property int $type
 * @property string $start_date
 * @property string $end_date
 * @property string $reg_date
 * @property string $reason
 * @property string $initiator
 * @property string $count
 * @property int $pb_id
 * @property string $other
 * @property int $hr_country_id
 * @property int $district_id
 * @property int $region_type
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Districts $district
 * @property HrCountry $hrCountry
 * @property HrEmployee $hrEmployee
 * @property PulBirligi $pb
 */

class HrServices extends BaseModel
{
    const SERVICE_TYPE_RAGBAT = 1;
    const SERVICE_TYPE_JARIMA = 2;
    const SERVICE_TYPE_OGOHLANTIRISH = 3;
    const SERVICE_TYPE_XIZMAT_SAFARI = 4;
    const SERVICE_TYPE_MALAKA_OSHIRISH = 5;

    const  SERVICE_TYPE_RAGBAT_LABEL = "encourage";
    const  SERVICE_TYPE_JARIMA_LABEL = "punishment";
    const  SERVICE_TYPE_OGOHLANTIRISH_LABEL = "caution";
    const  SERVICE_TYPE_XIZMAT_SAFARI_LABEL = "expedition";
    const  SERVICE_TYPE_MALAKA_OSHIRISH_LABEL = "development";
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_employee_id', 'type', 'pb_id', 'hr_country_id', 'district_id', 'region_type', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['start_date', 'end_date', 'reg_date'], 'safe'],
            [['other','add_info'], 'string'],
            [['reason'], 'string','message' => 'To\'ldirilishi shart'],
            [['count'], 'number'],
            [['initiator'], 'string', 'max' => 255],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::className(), 'targetAttribute' => ['region_id' => 'id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => Districts::className(), 'targetAttribute' => ['district_id' => 'id']],
            [['hr_country_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrCountry::className(), 'targetAttribute' => ['hr_country_id' => 'id']],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
            [['pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['pb_id' => 'id']],
            [['hr_employee_id','reason','reg_date'],'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hr_employee_id' => Yii::t('app', 'Employee'),
            'type' => Yii::t('app', 'Type'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'reason' => Yii::t('app', 'Asos/Sabab'),
            'add_info' => Yii::t('app', 'Add Info'),
            'initiator' => Yii::t('app', 'Initiator'),
            'count' => Yii::t('app', 'Summa'),
            'pb_id' => Yii::t('app', 'Pb ID'),
            'other' => Yii::t('app', 'Boshqa turdagi'),
            'hr_country_id' => Yii::t('app', 'Country'),
            'region_id' => Yii::t('app', 'Region'),
            'district_id' => Yii::t('app', 'District'),
            'region_type' => Yii::t('app', 'Turi'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getServiceTypeBySlug($key = null)
    {
        $result = [
            self::SERVICE_TYPE_RAGBAT_LABEL => Yii::t('app', 'Ragbatlantirish'),
            self::SERVICE_TYPE_JARIMA_LABEL => Yii::t('app', 'Jarima'),
            self::SERVICE_TYPE_OGOHLANTIRISH_LABEL => Yii::t('app', 'Ogohlantirish'),
            self::SERVICE_TYPE_XIZMAT_SAFARI_LABEL => Yii::t('app', 'Xizmat safari'),
            self::SERVICE_TYPE_MALAKA_OSHIRISH_LABEL => Yii::t('app', 'Malaka oshirish'),
        ];
        if ($key)
            return $result[$key];
        return $result;
    }
    /**
 * @return \yii\db\ActiveQuery
 */
    public function getDistrict()
    {
        return $this->hasOne(Districts::className(), ['id' => 'district_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Regions::className(), ['id' => 'region_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrCountry()
    {
        return $this->hasOne(HrCountry::className(), ['id' => 'hr_country_id']);
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
    public function getPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'pb_id']);
    }
}
