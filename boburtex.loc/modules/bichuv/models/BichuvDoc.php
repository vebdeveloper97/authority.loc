<?php

namespace app\modules\bichuv\models;

use app\components\behaviors\log\Log;
use app\components\OurCustomBehavior;
use app\models\Constants;
use app\models\UserRoles;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\hr\models\HrDepartmentResponsiblePerson;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use app\modules\hr\models\HrEmployeeUsers;
use app\modules\mobile\models\MobileProcessProduction;
use app\modules\mobile\models\MobileTablesRelHrEmployee;
use Yii;
use app\models\Users;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use app\modules\toquv\models\PulBirligi;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\base\models\SizeCollections;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\admin\models\ToquvUserDepartment;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "bichuv_doc".
 *
 * @property int $id
 * @property int $document_type
 * @property int $action
 * @property string $doc_number
 * @property string $reg_date
 * @property int $musteri_id
 * @property string $musteri_responsible
 * @property int $from_department
 * @property int $from_employee
 * @property int $to_department
 * @property int $to_employee
 * @property string $add_info
 * @property string $paid_amount
 * @property string $pb_id
 * @property string $payment_method
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $bichuv_mato_orders_id
 * @property int $model_orders_items_id
 *
 * @property ToquvDepartments $fromDepartment
 * @property Users $fromEmployee
 * @property SizeCollections $sizeCollection
 * @property ToquvDocuments $toquvDoc
 * @property BichuvMusteri $musteri
 * @property ToquvDepartments $toDepartment
 * @property ModelsList $modelList
 * @property ModelsVariations $modelVar
 * @property Users $toEmployee
 * @property BichuvDocExpense[] $bichuvDocExpenses
 * @property BichuvNastelDetails[] $bichuvNastelDetails
 * @property BichuvNastelRag[] $bichuvNastelRag
 * @property BichuvSliceItems[] $bichuvSliceItems
 * @property BichuvDocItems[] $bichuvDocItems
 * * @property BichuvMatoOrders $bichuvMatoOrders
 * @property BichuvBeka[] $bichuvBeka
 * @property BichuvSaldo[] $bichuvSaldos
 * @property int $parent_id [int(11)]
 * @property int $type [smallint(2)]
 * @property int $size_collection_id [int(11)]
 * @property string $rag [decimal(20,3)]
 * @property int $work_weight [int(5)]
 * @property int $toquv_doc_id [int(11)]
 * @property string $slice_weight [decimal(20,3)]
 * @property string $total_weight [decimal(20,3)]
 * @property bool $is_returned [tinyint(1)]
 * @property int $nastel_table_no [smallint(2)]
 * @property int $nastel_table_worker [int(11)]
 * @property int $service_musteri_id [bigint(20)]
 * @property string $deadline [date]
 * @property string $nastel_no
 * @property bool $is_service [tinyint(1)]
 * @property int $models_list_id [int(11)]
 * @property array $modelListInfo
 * @property array $accessoriesFromIB
 * @property array $matoListFromIB
 * @property mixed $accessoriesView
 * @property mixed $acceptedItems
 * @property null $allPulBirligi
 * @property string $dIEntityIds
 * @property mixed $productModels
 * @property mixed $bichuvBekaView
 * @property null $detailTypes
 * @property ActiveQuery $pbId
 * @property null $nastelWorkerName
 * @property null $partyNoNames
 * @property ActiveQuery $serviceMusteri
 * @property mixed $aks
 * @property bool $headerInfo
 * @property mixed $childDoc
 * @property ActiveQuery $parentDoc
 * @property int $model_var_id [int(11)]
 * @property int $updated_by [int(11)]
 */
class BichuvDoc extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    const DOC_TYPE_INCOMING = 1;
    const DOC_TYPE_MOVING = 2;
    const DOC_TYPE_SELLING = 3;
    const DOC_TYPE_RETURN = 4;
    const DOC_TYPE_OUTGOING = 5;
    const DOC_TYPE_QUERY = 6;

    const DOC_TYPE_ACCEPTED = 7;

    const DOC_TYPE_INSIDE = 8;
    const DOC_TYPE_REPAIR = 9;
    const DOC_TYPE_PLAN_NASTEL = 10;
    const DOC_TYPE_ADJUSTMENT = 11;
    const DOC_TYPE_ADJUSTMENT_SERVICE = 12;

    /** aksessuar o'tkazish nastel bo'yicha  */
    const DOC_TYPE_MOVING_ACS_WITH_NASTEL = 13;
    // qabul bichuvdan
    const DOC_TYPE_ACCEPTED_FROM_BICHUV = 14;
    // bichuvga ko'chiruv
    const DOC_TYPE_TRANSFER_SLICE_TO_BICHUV = 15;

    // Aksessuar tarqatuvchi ombor uchun, AKS (SAMO) dan qabul
    const DOC_TYPE_ACCEPTED_ACS_FROM_WAREHOUSE = 16;


    const DOC_TYPE_ACCEPTED_MATO_LABEL = 'qabul_mato';

    const DOC_TYPE_INCOMING_LABEL = 'kirim_acs';
    const DOC_TYPE_MOVING_LABEL = 'kochirish_acs';
    const DOC_TYPE_SELLING_LABEL = 'sotish_acs';
    const DOC_TYPE_SELLING_MATO_LABEL = 'sotish_mato';
    const DOC_TYPE_RETURN_LABEL = 'qaytarish_acs';
    const DOC_TYPE_OUTGOING_LABEL = 'chiqim_acs';

    //Mato
    const DOC_TYPE_INCOMING_MATO_LABEL = 'kirim_mato';
    const DOC_TYPE_MOVING_MATO_LABEL = 'kochirish_mato';
    const DOC_TYPE_REPAIR_MATO_LABEL = 'tamir_mato';

    //Kesilgan(Nastel size bilan birga)

    const DOC_TYPE_MOVING_SLICE_LABEL = "kochirish_kesim";
    const DOC_TYPE_MOVING_SLICE_TAY_LABEL = "kochirish_kesim_tay";
    const DOC_TYPE_TRANSFER_SLICE_TO_BICHUV_LABEL = "transfer_slice";
    const DOC_TYPE_MOVING_SERVICE_LABEL = "usluga";
    const DOC_TYPE_ACCEPTED_SLICE_LABEL = "qabul_kesim";
    const DOC_TYPE_INCOMING_SLICE_LABEL = "kirim_kesim";
    //qabul acs uchun
    const DOC_TYPE_ACCEPTED_LABEL = 'qabul_acs';


    const DOC_TYPE_ACCEPTED_ACS_FROM_WAREHOUSE_LABEL = 'qabul_acs_from_warehouse';

    const DOC_TYPE_ACCEPTED_SlICE_FROM_BICHUV_LABEL = 'qabul_kesim_bichuv';

    // so'rov acs uchun
    const DOC_TYPE_QUERY_ACS_LABEL = 'query_acs';

    //nastel detal va prosesslar uchun plan

    const DOC_TYPE_NASTEL_PLAN_LABEL = 'nastel_plan';
    const DOC_TYPE_MOVING_ACS_WITH_NASTEL_LABEL = 'kochirish_acs_with_nastel';

    const DOC_TYPE_ADJUSTMENT_LABEL = 'togrilash';
    const DOC_TYPE_ADJUSTMENT_SERVICE_LABEL = 'usluga_qoldiq';

    const SCENARIO_UPDATE_MOVING_NASTEL = 'update_kochirish_acs_with_nastel';

    // Kesimlarni print yoki patternga kochirish
    const DOC_TYPE_MOVING_SLICE_TO_PRINT_OR_PATTERN_LABEL = "kochirish_print_or_pattern";

    public $updated_by;
    /**
     * Barcha slice itemslar uchun pechat
     * @var $print_all
     *
     * Barcha slice itemslar uchun stone
     * @var $stone_all
     */
    public $print_all;
    public $stone_all;

    public static function tableName()
    {
        return 'bichuv_doc';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE_MOVING_NASTEL] = ['add_info'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_type', 'service_musteri_id', 'is_service', 'nastel_table_no', 'nastel_table_worker', 'is_returned', 'toquv_doc_id', 'type', 'action', 'musteri_id', 'from_department',
                'from_employee', 'to_department', 'to_employee',
                'status', 'created_at', 'updated_at', 'created_by', 'updated_by',
                'pb_id', 'payment_method', 'size_collection_id', 'work_weight', 'bichuv_mato_orders_id', 'models_list_id', 'model_var_id'],
                'integer'],
            [['paid_amount', 'rag', 'slice_weight', 'total_weight'], 'number'],
            [['reg_date', 'doc_number'], 'required'],
            [['add_info'], 'required', 'when' => function ($model) {
                return $model->document_type == $model::DOC_TYPE_REPAIR;
            }],
            [['model_orders_id'], 'required', 'when' => function ($model) {
                return $model->document_type == self::DOC_TYPE_INCOMING_LABEL;
            }],
            ['add_info', 'required', 'on' => [self::SCENARIO_UPDATE_MOVING_NASTEL]],
            [['from_hr_department', 'to_hr_department'], 'required', 'when' => function ($model) {
                return $model->document_type == $model::DOC_TYPE_MOVING;
            }],
            [['from_department', 'musteri_id'], 'required', 'when' => function ($model) {
                return $model->document_type == $model::DOC_TYPE_OUTGOING;
            }],
            [['from_department', 'musteri_id', 'parent_id'], 'required', 'when' => function ($model) {
                return $model->document_type == $model::DOC_TYPE_RETURN;
            }],
            [['models_list_id', 'model_var_id'], 'required', 'when' => function ($model) {
                return $model->document_type == $model::DOC_TYPE_ADJUSTMENT_SERVICE;
            }],
            [['reg_date', 'deadline'], 'safe'],
            [['add_info','nastel_no'], 'string'],
            [['doc_number'], 'string', 'max' => 25],
            [['musteri_responsible'], 'string', 'max' => 255],
            [['from_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['from_hr_department' => 'id']],
            [['to_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['to_hr_department' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['from_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['from_employee' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvMusteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['service_musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvMusteri::className(), 'targetAttribute' => ['service_musteri_id' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['to_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['to_employee' => 'id']],
            [['size_collection_id'], 'exist', 'skipOnError' => true, 'targetClass' => SizeCollections::className(), 'targetAttribute' => ['size_collection_id' => 'id']],
            [['toquv_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDocuments::className(), 'targetAttribute' => ['toquv_doc_id' => 'id']],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['from_hr_employee'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['from_hr_employee' => 'id']],
            [['to_hr_employee'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['to_hr_employee' => 'id']],
            [['bichuv_nastel_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvNastelLists::className(), 'targetAttribute' => ['bichuv_nastel_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'document_type' => Yii::t('app', 'Document Type'),
            'action' => Yii::t('app', 'Action'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'deadline' => Yii::t('app', "Tayyor bo'lish muddati"),
            'rag' => Yii::t('app', 'Chiqindi mato qoldig\'i (kg)'),
            'slice_weight' => Yii::t('app', "Kesilgan ish og'irligi (kg)"),
            'total_weight' => Yii::t('app', "Sof ish og'irligi (kg)"),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'nastel_table_no' => Yii::t('app', 'Nastel stol raqami'),
            'nastel_table_worker' => Yii::t('app', 'Nastel Kesuvchi'),
            'size_collection_id' => Yii::t('app', 'Size Collection'),
            'musteri_responsible' => Yii::t('app', 'Musteri Responsible'),
            'model_orders_id' => Yii::t('app', 'Model Orders ID'),
            'from_department' => Yii::t('app', 'From Department'),
            'from_hr_department' => Yii::t('app', 'From department'),
            'from_employee' => Yii::t('app', 'From Employee'),
            'to_department' => Yii::t('app', 'To Department'),
            'to_hr_department' => Yii::t('app', 'To department'),
            'to_employee' => Yii::t('app', 'To Employee'),
            'paid_amount' => Yii::t('app', 'Summa'),
            'pb_id' => Yii::t('app', 'Pb ID'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'payment_method' => Yii::t('app', 'Payment Method'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'bichuv_mato_orders_id' => Yii::t('app', 'Bichuv Mato Orders ID'),
            'models_list_id' => Yii::t('app', 'Model'),
            'model_var_id' => Yii::t('app', 'Variant'),
            'to_hr_employee' => Yii::t('app', 'Responsible person'),
            'from_hr_employee' => Yii::t('app', 'Responsible person'),
            'bichuv_nastel_list_id' => Yii::t('app', 'Bichuv Nastel Nomer'),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->reg_date = date('Y-m-d H:i:s', strtotime($this->reg_date));
            if (!empty($this->deadline)) {
                $this->deadline = date('Y-m-d', strtotime($this->deadline));
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = date('d.m.Y', strtotime($this->reg_date));
        if ($this->deadline) {
            $this->deadline = date('d.m.Y', strtotime($this->deadline));
        }

    }
    /**
     * @return ActiveQuery
     */
    public function getBichuvNastelList()
    {
        return $this->hasOne(BichuvNastelLists::className(), ['id' => 'bichuv_nastel_list_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getFromDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'from_department']);
    }
    /**
     * @return ActiveQuery
     */
    public function getFromHrDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'from_hr_department']);
    } /**
 * @return ActiveQuery
 */
    public function getToHrDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'to_hr_department']);
    }
    /**
     * @return ActiveQuery
     */
    public function getFromEmployee()
    {
        return $this->hasOne(Users::className(), ['id' => 'from_employee']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(BichuvMusteri::className(), ['id' => 'musteri_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getServiceMusteri()
    {
        return $this->hasOne(BichuvMusteri::className(), ['id' => 'service_musteri_id']);
    }

    /**
     * @return ActiveQuery
     *
     */
    public function getToDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'to_department']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToEmployee()
    {
        return $this->hasOne(Users::className(), ['id' => 'to_employee']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToHrEmployee()
    {
        return $this->hasOne(HrEmployee::class, ['id' => 'to_hr_employee']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFromHrEmployee()
    {
        return $this->hasOne(HrEmployee::class, ['id' => 'from_hr_employee']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSizeCollection()
    {
        return $this->hasOne(SizeCollections::className(), ['id' => 'size_collection_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToquvDoc()
    {
        return $this->hasOne(ToquvDocuments::className(), ['id' => 'toquv_doc_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvNastelDetails()
    {
        return $this->hasMany(BichuvNastelDetails::className(), ['bichuv_doc_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvMatoOrders()
    {
        return $this->hasOne(BichuvMatoOrders::className(), ['id' => 'bichuv_mato_orders_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getPbId()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'pb_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvDocExpenses()
    {
        return $this->hasMany(BichuvDocExpense::className(), ['document_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvBeka()
    {
        return $this->hasMany(BichuvBeka::className(), ['bichuv_doc_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvNastelRag()
    {
        return $this->hasMany(BichuvNastelRag::className(), ['bichuv_doc_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvSliceItems()
    {
        return $this->hasMany(BichuvSliceItems::className(), ['bichuv_doc_id' => 'id'])->joinWith('size');
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvDocItems()
    {
        return $this->hasMany(BichuvDocItems::className(), ['bichuv_doc_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvSaldos()
    {
        return $this->hasMany(BichuvSaldo::className(), ['bd_id' => 'id']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getDocTypes($key = null)
    {
        $result = [
            self::DOC_TYPE_INCOMING => Yii::t('app', 'Qabul qilish'),
            self::DOC_TYPE_MOVING => Yii::t('app', "O'tkazish"),
            self::DOC_TYPE_SELLING => Yii::t('app', "Sotish"),
            self::DOC_TYPE_RETURN => Yii::t('app', "Qaytarish"),
            self::DOC_TYPE_OUTGOING => Yii::t('app', "Chiqim"),
            self::DOC_TYPE_ACCEPTED => Yii::t('app', "Qabul qilish"),
            self::DOC_TYPE_PLAN_NASTEL => Yii::t('app', "Nastel Plan"),
            self::DOC_TYPE_ACCEPTED_FROM_BICHUV => Yii::t('app', "Qabul qilish bichuvdan"),
            self::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV => Yii::t('app', "Ko'chirish bichuvga")
        ];
        if (!empty($key)) {
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getDocTypeBySlug($key = null)
    {
        $result = [
            self::DOC_TYPE_INCOMING_LABEL => Yii::t('app', 'Kirim Aksesuar'),
            self::DOC_TYPE_INCOMING_MATO_LABEL => Yii::t('app', 'Kirim Mato'),
            self::DOC_TYPE_MOVING_MATO_LABEL => Yii::t('app', "Ko'chirish Mato"),
            self::DOC_TYPE_ACCEPTED_MATO_LABEL => Yii::t('app', "Qabul Mato"),
            self::DOC_TYPE_MOVING_LABEL => Yii::t('app', "O'tkazish Aksesuar"),
            self::DOC_TYPE_OUTGOING_LABEL => Yii::t('app', "Chiqim Aksesuar"),
            self::DOC_TYPE_RETURN_LABEL => Yii::t('app', "Qaytarish Aksesuar"),
            self::DOC_TYPE_ACCEPTED_LABEL => Yii::t('app', "Qabul Aksesuar"),
            self::DOC_TYPE_MOVING_SLICE_LABEL => Yii::t('app', "Ko'chirish Kesim"),
            self::DOC_TYPE_MOVING_SLICE_TAY_LABEL => Yii::t('app', "Ko'chirish Kesim"),
            self::DOC_TYPE_MOVING_SLICE_TO_PRINT_OR_PATTERN_LABEL => Yii::t('app', "Ko'chirish Kesim Print Or Pattern"),
            self::DOC_TYPE_MOVING_SERVICE_LABEL => Yii::t('app', "Xizmat uchun yuborish"),
            self::DOC_TYPE_ACCEPTED_SLICE_LABEL => Yii::t('app', "Qabul Kesim"),
            self::DOC_TYPE_INCOMING_SLICE_LABEL => Yii::t('app', "Kirim Kesim"),
            self::DOC_TYPE_REPAIR_MATO_LABEL => Yii::t('app', "Ta'mir Mato"),
            self::DOC_TYPE_SELLING_MATO_LABEL => Yii::t('app', "Sotish Mato"),
            self::DOC_TYPE_NASTEL_PLAN_LABEL => Yii::t('app', "Nastel Plan"),
            self::DOC_TYPE_MOVING_ACS_WITH_NASTEL_LABEL => Yii::t('app', "O'tkazish Aksesuar(nastel bo'yicha)"),
            self::DOC_TYPE_ADJUSTMENT_LABEL => Yii::t('app', "To'grilash"),
            self::DOC_TYPE_ADJUSTMENT_SERVICE_LABEL => Yii::t('app', "Usluga qoldiq kiritish"),
            self::DOC_TYPE_QUERY_ACS_LABEL => Yii::t('app', "So'rov aksessuar"),
            self::DOC_TYPE_ACCEPTED_SlICE_FROM_BICHUV_LABEL => Yii::t('app', "Qabul kesim bichuvdan"),
            self::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV_LABEL => Yii::t('app', "Kochirish bichuvga"),
            self::DOC_TYPE_ACCEPTED_ACS_FROM_WAREHOUSE_LABEL => Yii::t('app', "Accept accessory"),
        ];
        if ($key)
            return $result[$key];
        return $result;
    }

    /**
     * @return array|mixed
     */
    public function getSlugLabel($default_slug=null)
    {
        if($default_slug){
            return self::getDocTypeBySlug($default_slug);
        }
        $slug = Yii::$app->request->get('slug');
        if (!empty($slug)) {
            return self::getDocTypeBySlug($slug);
        }
    }

    /**
     * @param bool $isAll
     * @return array|null
     */
    public function getEmployees($isAll = false,$token=false)
    {
        if($token){
            $dept = ToquvDepartments::findOne(['token'=>$token]);
            if($dept){
                $user = Users::find()->joinWith('toquvUserDepartments tud')->where(['tud.department_id'=>$dept['id']])->andWhere(['!=','tud.user_id',1])->asArray()->all();
            }
        }
        else {
            if ($isAll) {
                $user = Users::find()->select(['id', 'user_fio'])->asArray()->all();
            } else {
                /*$user = Users::find()->select(['id', 'user_fio'])->where(['id' => Yii::$app->user->id])->asArray()->all();*/
                $hr = HrEmployeeUsers::find()->where(['users_id' => Yii::$app->user->id])->one();
                $user = $hr ? $hr->getHrEmployee()->asArray()->all() : HrEmployee::find()->asArray()->all();
            }
        }
        if (!empty($user)) {
            return ArrayHelper::map($user, 'id', 'fish');
        }
        return [];
    }

    public function getHrEmployee()
    {
        /*$hr = HrEmployeeUsers::findOne(['users_id' => Yii::$app->user->id]);
        if(!empty($hr)){
            $result = HrEmployee::findOne($hr->hr_employee_id);
            return ArrayHelper::map($result, 'id', 'fish');
        }
        else{*/
            $result = HrEmployee::find()->asArray()->all();
            return ArrayHelper::map($result,'id', 'fish');
//        }
    }

    /**
     * @param array $token
     * @return array|null
     */
    public static function getEmployeesByRole($token = [], $dept = null)
    {
        if ($dept) {
            $subQuery = UserRoles::find()->select(['id'])->where(['department' => $dept]);
            $res = Users::find()->select(['id', 'user_fio'])->where(['user_role' => $subQuery])->asArray()->all();
            return ArrayHelper::map($res, 'id', 'user_fio');
        }
        if (!empty($token)) {
            $subQuery = UserRoles::find()->select(['id'])->where(['code' => $token]);
            $res = Users::find()->select(['id', 'user_fio'])->where(['user_role' => $subQuery])->asArray()->all();
            return ArrayHelper::map($res, 'id', 'user_fio');
        }
        return null;
    }

    /**
     * @param null $token
     * @param null $musteri_type
     * @return array|null
     */
    public function getMusteries($token = null, $musteri_type = null)
    {
        if ($token) {
            $result = BichuvMusteri::find()->select(['id', 'name'])->where([
                'status' => self::STATUS_ACTIVE,
                'token' => $token
            ])->asArray()->one();
            return [$result['id'] => $result['name']];
        } else {
            $query = BichuvMusteri::find();
            if (!empty($musteri_type)) {
                $id = Constants::$NillGranitID;
                $query->andFilterWhere(['OR', ['musteri_type_id' => $musteri_type], ['id' => $id]]);
            }
            $query->andFilterWhere(['status' => self::STATUS_ACTIVE])->select(['id', 'name']);
            $results = $query->asArray()->orderBy(['name' => SORT_ASC])->all();
            if (!empty($results)) {
                return ArrayHelper::map($results, 'id', 'name');
            }
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getDepartments($isGetAll = false)
    {
        if ($isGetAll) {
            $depts = ToquvDepartments::find()->where(['status' => ToquvDepartments::STATUS_ACTIVE])->asArray()->all();
            return ArrayHelper::map($depts, 'id', 'name');
        }
        else {
            $availIds = ToquvUserDepartment::find()->select(['department_id'])
                ->where(['status' => self::STATUS_ACTIVE, 'user_id' => Yii::$app->user->id])
                ->asArray()->all();
            if (!empty($availIds)) {
                $ids = ArrayHelper::getColumn($availIds, 'department_id');
                $result = ToquvDepartments::find()->select(['id', 'name'])
                    ->andFilterWhere(['status' => self::STATUS_ACTIVE])
                    ->andFilterWhere(['in', 'id', $ids])->asArray()->all();
            } else {
                return null;
            }
            if (!empty($result)) {
                return ArrayHelper::map($result, 'id', 'name');
            }
        }
        return null;
    }

    public function getHrDepartments()
    {
        $id = Yii::$app->user->id;
        $depId = UsersHrDepartments::find()
            ->select('hr_departments_id')
            ->where(['user_id' => 4])
            ->column();

        $hrDep = HrDepartments::find()
            ->where(['in', 'id', $depId])
            ->asArray()
            ->all();
        $column = ArrayHelper::map($hrDep,'id', 'name');
        return $column;
    }

    public function getEntityType($key = null)
    {
        $res = [
            1 => Yii::t('app', 'Kesim'),
            2 => Yii::t('app', 'Aksessuar'),
            3 => Yii::t('app', 'Mato'),
        ];
        if ($key) {
            return $res[$key];
        }
        return $res;
    }

    /**
     * @param $token
     * @param bool $isMultiple
     * @return array|null
     */
    public static function getDepartmentByToken($token, $isMultiple = false)
    {
        if ($token) {
            if ($isMultiple) {
                $result = ToquvDepartments::find()->select(['id', 'name'])
                    ->andFilterWhere(['status' => self::STATUS_ACTIVE])
                    ->andFilterWhere(['in', 'token', $token])->asArray()->all();
            } else {
                $result = ToquvDepartments::find()->select(['id', 'name'])
                    ->andFilterWhere(['status' => self::STATUS_ACTIVE])
                    ->andFilterWhere(['token' => $token])->asArray()->all();
            }


            if (!empty($result)) {
                return ArrayHelper::map($result, 'id', 'name');
            } else return null;
        }
        return null;
    }

    /**
     * @param bool $withoutKeyValue
     * @return array
     * @throws Exception
     */
    public static function getDepartmentsBelongTo($withoutKeyValue = false)
    {

        $currentID = Yii::$app->user->id;
        $sql = "select 
                    hd.id,
                    hd.name,
                    hd.token from hr_departments hd
                    where hd.id IN 
                        (SELECT  hud.hr_departments_id from users_hr_departments hud 
                                    WHERE hud.user_id = %d AND hud.status = %d AND hud.type = 0);";
        $sql = sprintf($sql, $currentID, self::STATUS_ACTIVE);
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        if (!empty($query)) {
            if ($withoutKeyValue) {
                return $query;
            }
            return ArrayHelper::map($query, 'id', 'name');
        }

        return [];
    }

    public function getAllPulBirligi()
    {
        $results = PulBirligi::find()->select(['id', 'name'])->where(['status' => self::STATUS_ACTIVE])->asArray()->all();
        if (!empty($results)) {
            return ArrayHelper::map($results, 'id', 'name');
        }
        return null;
    }

    /**
     * @param $params
     * @param bool $isAll
     * @return array|mixed|null
     * @throws Exception
     */
    public function getRemain($params, $isAll = false)
    {
        if ($isAll) {
            $sql = "select entity_id, inventory from bichuv_item_balance where entity_id IN (%s) AND entity_type = %d AND department_id = %d;";
            $sql = sprintf($sql, $params['id'], $params['type'], $params['depId']);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            return $res;
        } else {
            $results = BichuvItemBalance::find()->select(['inventory'])
                ->where(['entity_type' => $params['type'], 'entity_id' => $params['id'], 'department_id' => $params['depId']])
                ->orderBy(['id' => SORT_DESC])->asArray()->one();
            if (!empty($results)) {
                return $results['inventory'];
            }
        }
        return null;
    }

    /**
     * @param $params
     * @return array
     * @throws Exception
     */
    public function searchEntities($params)
    {
        $q = '';
        if (!empty($params['query'])) {
            $q = " AND ((acs.name LIKE '%{$params['query']}%') "
                . "OR (ne.name LIKE '%{$params['query']}%') "
                . "OR (thr.name LIKE '%{$params['query']}%') "
                . "OR (cl.name LIKE '%{$params['query']}%') "
                . "OR (t1.lot LIKE '%{$params['query']}%'))";
        }
        $sql = "SELECT t1.id, t1.entity_id, t1.inventory AS summa, acs.sku,
                        acs.name as acsname, pr.name as prname
                FROM bichuv_item_balance t1
                    LEFT JOIN bichuv_acs acs ON t1.entity_id = acs.id
                    LEFT JOIN bichuv_acs_property pr ON acs.property_id = pr.id 
                    JOIN (SELECT MAX(id) as id, entity_id 
                          from bichuv_item_balance 
                          WHERE department_id=%d 
                          GROUP BY entity_id ORDER BY id ASC) as t2 ON t1.id = t2.id
                WHERE (entity_type=%d) AND (department_id=%d) %s 
                    GROUP BY t1.entity_id LIMIT 500";

        $sql = sprintf($sql,
            $params['department_id'],
            $params['entity_type'],
            $params['department_id'],
            $q);

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * @param null $doc_id
     * @param int $t
     * @return array
     * @throws Exception
     */
    public function getMatoView($doc_id = null, $t = 1)
    {
        if ($t == 1) {
            $sql = "select bss.id,
                       m.name              as mname,
                       bsm.musteri_partiya as mijoz_part,
                       bsp.partiya_no,
                       bss.finish_gr       as gramaj,
                       bss.material_width  as mato_en,
                       thr.name            as ip,
                       ne.name             as ne,
                       pf.name             as pus_fine,
                       rm.name             as mato,
                       mt.name             as mato_turi,
                       bdi.quantity        as rulon_kg,
                       bdi.roll_count      as count_rulon,
                       pr.name             as model,
                       u.user_fio,
                       c.color_id,
                       c.pantone,
                       c_ton.name          as ctone,
                       c_tip.name          as ctip,
                       bdi.is_accessory 
                    from bichuv_doc_items bdi
                    LEFT JOIN bichuv_doc bd on bdi.bichuv_doc_id = bd.id  
                    LEFT JOIN boyahane_siparis_subpart bss on bss.id = bdi.bss_id
                    LEFT JOIN paketlar p on p.subpart_id = bss.id
                    LEFT JOIN thread thr on bss.thread_id = thr.id
                    LEFT JOIN boyahane_siparis_part bsp on bss.part_id = bsp.id
                    LEFT JOIN boyahane_siparis_musteri bsm on bsp.siparis_id = bsm.id
                    LEFT JOIN musteri m on bsm.musteri = m.id
                    LEFT JOIN color c on bsp.color_id = c.id
                    LEFT JOIN color c2 ON bsp.color_id2 = c2.id
                    LEFT JOIN color_tone c_ton ON bsp.color_tone = c_ton.id
                    LEFT JOIN color_type c_tip ON bsp.color_type = c_tip.id
                    left join ne on bss.ne_id = ne.id
                    LEFT JOIN raw_material rm ON bss.raw_material_id = rm.id
                    LEFT JOIN material_type mt ON bss.material_type_id = mt.id
                    LEFT JOIN product pr ON bdi.model_id = pr.id
                    LEFT JOIN pus_fine pf ON bss.pus_fine_id = pf.id
                    LEFT JOIN users u on p.user_uid = u.id
                    WHERE bdi.bichuv_doc_id = :docId AND bd.type = :t GROUP BY bss.id ORDER BY  bss.id;";
        } elseif ($t == 2) {
            $sql = "select bdi.id,
                           bdi.is_accessory,
                           bdi.quantity        as rulon_kg,
                           bdi.roll_count      as count_rulon,
                           rm.name as mato,
                           nename.name as ne,
                           thr.name as ip,
                           pf.name as pus_fine,
                           c.color_id,
                           c.pantone,
                           ct.name as ctone,
                           p.name as model,
                           bdi.party_no as partiya_no,
                           bdi.musteri_party_no as mijoz_part,
                           bmi.en as mato_en,
                           bmi.gramaj
                    from bichuv_doc_items bdi
                            left join product p ON bdi.model_id = p.id
                            left join bichuv_doc bd on bdi.bichuv_doc_id = bd.id
                            left join bichuv_mato_info bmi on bdi.entity_id = bmi.id
                            left join musteri m on bd.musteri_id = m.id
                            left join raw_material rm on bmi.rm_id = rm.id
                            left join ne nename on bmi.ne_id = nename.id
                            left join thread thr on bmi.thread_id = thr.id
                            left join pus_fine pf on bmi.pus_fine_id = pf.id
                            left join color c on bmi.color_id = c.id
                            left join color_tone ct on c.color_tone = ct.id
                    WHERE bd.id = :docId AND bdi.entity_type = 2 AND bd.type = :t GROUP BY bdi.id;";
        } elseif ($t == 3) {
            $sql = "select bdi.id,
                           bdi.quantity        as rulon_kg,
                           bdi.roll_count      as count_rulon,
                           rm.name as mato,
                           nename.name as ne,
                           thr.name as ip,
                           pf.name as pus_fine,
                           c.color_id,
                           c.pantone,
                           ct.name as ctone,
                           p.name as model,
                           bdi.party_no as partiya_no,
                           bdi.musteri_party_no as mijoz_part,
                           bmi.en as mato_en,
                           bmi.gramaj,
                           bdi.nastel_no 
                    from bichuv_doc_items bdi
                            left join product p ON bdi.model_id = p.id
                            left join bichuv_doc bd on bdi.bichuv_doc_id = bd.id
                            left join bichuv_mato_info bmi on bdi.entity_id = bmi.id
                            left join musteri m on bd.musteri_id = m.id
                            left join raw_material rm on bmi.rm_id = rm.id
                            left join ne nename on bmi.ne_id = nename.id
                            left join thread thr on bmi.thread_id = thr.id
                            left join pus_fine pf on bmi.pus_fine_id = pf.id
                            left join color c on bmi.color_id = c.id
                            left join color_tone ct on c.color_tone = ct.id
                    WHERE bd.id = :docId AND bd.type = :t GROUP BY bdi.id;";
        }
        $res = Yii::$app->db->createCommand($sql)->bindValues([
            'docId' => $doc_id,
            't' => $t
        ])->queryAll();
        return $res;
    }

    /**
     * @param array $boyahane_siparis_subpart_ids
     * @return array
     * @throws Exception
     */
    public static function getRMInfo($boyahane_siparis_subpart_ids = [])
    {

        if (!empty($boyahane_siparis_subpart_ids)) {
            $ids = join(',', $boyahane_siparis_subpart_ids);
            $condition = " AND bss.id IN ({$ids})";
            $sql = "select bss.id,
                       p.id as pid,
                       m.name              as mname,
                       m.id                as mid,  
                       bsm.musteri_partiya as mijoz_part,
                       bss.thread_consist, 
                       bsp.partiya_no,
                       bss.finish_gr       as gramaj,
                       bss.material_width  as mato_en,
                       thr.name            as ip,
                       thr.id              as thr_id,  
                       ne.name             as ne,
                       ne.id               as ne_id,  
                       pf.name             as pus_fine,
                       pf.id               as pf_id,  
                       rm.name             as mato,
                       rm.id               as rmid, 
                       mt.name             as mato_turi,
                       mt.id               as mt_id,  
                       p.kg                as rulon_kg,
                       (select COUNT(p2.id) from paketlar p2 where p2.subpart_id = bss.id)  as count_rulon,
                       pr.name             as model,
                       u.user_fio,
                       c.color_id,
                       c.id                as c_id, 
                       c.pantone,
                       c_ton.name          as ctone,
                       c_tip.name          as ctip,
                       c_ton.id            as cton_id,
                       c_tip.id            as ctip_id  
                from boyahane_siparis_subpart bss
                         left join paketlar p on p.subpart_id = bss.id
                         left join thread thr on bss.thread_id = thr.id
                         left join boyahane_siparis_part bsp on bss.part_id = bsp.id
                         left join boyahane_siparis_musteri bsm on bsp.siparis_id = bsm.id
                         left join musteri m on bsm.musteri = m.id
                         left join color c on bsp.color_id = c.id
                         LEFT JOIN color c2 ON bsp.color_id2 = c2.id
                         LEFT JOIN color_tone c_ton ON bsp.color_tone = c_ton.id
                         LEFT JOIN color_type c_tip ON bsp.color_type = c_tip.id
                         left join ne on bss.ne_id = ne.id
                         LEFT JOIN raw_material rm ON bss.raw_material_id = rm.id
                         LEFT JOIN material_type mt ON bss.material_type_id = mt.id
                         LEFT JOIN product pr ON bsp.product_id = pr.id
                         LEFT JOIN pus_fine pf ON bss.pus_fine_id = pf.id
                         LEFT JOIN users u on p.user_uid = u.id
                where 1=1 %s  GROUP BY p.id ORDER BY p.id ASC;";
            $sql = sprintf($sql, $condition);
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            return $result;
        }
        return false;
    }

    public function getItems($isMoving = false)
    {
        if ($isMoving) {
            $sql = "select bsdi.*, bdi.is_accessory from bichuv_sub_doc_items bsdi
                left join bichuv_doc_items bdi on bdi.entity_id = bsdi.id
                WHERE  bdi.bichuv_doc_id = :di;";
            $res = Yii::$app->db->createCommand($sql)->bindValue('di', $this->id)->queryAll();

            return ArrayHelper::map($res, 'id', function ($m) {
                if ($m['is_accessory'] == 2) {
                    return "{$m['mato']}-{$m['thread']}-({$m['ctone']} {$m['color_id']} {$m['pantone']}) - ({$m['model']})";
                }
                return "{$m['mato']}-{$m['ne']}-{$m['thread']}|{$m['pus_fine']}-({$m['ctone']} {$m['color_id']} {$m['pantone']}) - ({$m['model']})";
            });
        } else {
            $sql = "select bsdi.*, bdi.is_accessory, bdi.entity_id from bichuv_doc_items bdi
                left join bichuv_sub_doc_items bsdi on bdi.id = bsdi.doc_item_id
                WHERE  bdi.bichuv_doc_id = :di GROUP BY bsdi.bss_id;";
            $res = Yii::$app->db->createCommand($sql)->bindValue('di', $this->id)->queryAll();
            return ArrayHelper::map($res, 'entity_id', function ($m) {
                if ($m['is_accessory'] == 2) {
                    return "{$m['mato']}-{$m['thread']}-({$m['ctone']} {$m['color_id']} {$m['pantone']})";
                }
                return "{$m['mato']}-{$m['ne']}-{$m['thread']}|{$m['pus_fine']}-({$m['ctone']} {$m['color_id']} {$m['pantone']})";
            });
        }

    }


    /***
     * @param $id
     * @return array
     * @throws Exception
     */
    public function getMatoListFromIB()
    {
        $sql = "select bdi.entity_id,
                       rm.name as mato,
                       n.name as nename,
                       thr.name as thread,
                       pf.name as pus_fine,
                       ct.name as ctone,
                       c.color_id,
                       c.pantone,
                       m.name,
                       p.name as model
                from bichuv_mato_info bmi
                left join pus_fine pf on bmi.pus_fine_id = pf.id
                left join raw_material rm on bmi.rm_id = rm.id
                left join ne n on bmi.ne_id = n.id
                left join color c on bmi.color_id = c.id
                left join color_tone ct on c.color_tone = ct.id   
                left join thread thr on bmi.thread_id = thr.id
                left join bichuv_doc_items bdi on bdi.entity_id = bmi.id
                left join product p on bdi.model_id = p.id   
                left join bichuv_doc bd on bdi.bichuv_doc_id = bd.id
                left join musteri m on bd.musteri_id = m.id
                WHERE bd.id = :id;";
        $res = Yii::$app->db->createCommand($sql)->bindValue('id', $this->id)->queryAll();

        return ArrayHelper::map($res, 'entity_id', function ($m) {
            return "{$m['mato']}-{$m['nename']}-{$m['thread']}|{$m['pus_fine']}-({$m['ctone']} {$m['color_id']} {$m['pantone']})-({$m['name']})-({$m['model']})";
        });
    }

    public function getDIEntityIds()
    {
        $items = $this->bichuvDocItems;
        $ids = "";
        if ($items) {
            $c = count($items);
            foreach ($items as $key => $item) {
                $ids .= $item->entity_id;
                if (($key + 1) != $c) {
                    $ids .= ",";
                }
            }
        }
        return $ids;
    }

    /**
     * @param bool $is_musteri
     * @return string
     */
    public function getParties($is_musteri = false)
    {
        $results = [];
        $items = $this->bichuvDocItems;
        if (!empty($items)) {
            foreach ($items as $item) {
                $subs = $item->bichuvSubDocItems;
                if (!empty($subs)) {
                    foreach ($subs as $sub) {
                        if ($is_musteri) {
                            $results[$sub->musteri_party_no] = $sub->musteri_party_no;
                        } else {
                            $results[$sub->party_no] = $sub->party_no;
                        }
                    }
                }
            }
        }
        return join(', ', $results);
    }

    public function getMovingParties($isMusteri = false)
    {
        if ($isMusteri) {
            $sql = "select bdi.musteri_party_no as party from bichuv_doc_items bdi where bdi.bichuv_doc_id = :id GROUP BY bdi.musteri_party_no ORDER BY bdi.musteri_party_no;";
        } else {
            $sql = "select bdi.party_no as party from bichuv_doc_items bdi where bdi.bichuv_doc_id = :id GROUP BY bdi.party_no ORDER BY bdi.party_no;";
        }
        $res = Yii::$app->db->createCommand($sql)->bindValue('id', $this->id)->queryAll();
        $party = ArrayHelper::getColumn($res, 'party');
        return join(', ', $party);
    }

    /**
     * @param string $type
     * @return array
     * @throws Exception
     */
    public function getRMList($type = 'mato')
    {
        switch ($type) {
            case 'mato':
                $sql = "select id, name from raw_material;";
                break;
            case 'ne':
                $sql = "select id, name from ne;";
                break;
            case 'pf':
                $sql = "select id, name from pus_fine;";
                break;
            case 'thread':
                $sql = "select id, name from thread";
                break;
            case 'model':
                $sql = "select id, name from product";
                break;
            case 'color':
                $sql = "select c.id, CONCAT(ct.name, ' | ',c.color_id, ' | ', c.pantone) name from color c left join color_tone ct on c.color_tone = ct.id";
                break;
        }
        $res = Yii::$app->db->createCommand($sql)->queryAll();

        return ArrayHelper::map($res, 'id', 'name');
    }

    /**
     * @param $id
     * @param $t
     * @return array
     * @throws Exception
     */
    public static function getLoadRolls($id, $t)
    {
        if ($t == 1) {
            $sql = "select bss.id,
                       p.id                as pid,
                       m.name              as mname,
                       bsm.musteri_partiya as mijoz_part,
                       bsp.partiya_no,
                       bss.finish_gr       as gramaj,
                       bss.material_width  as mato_en,
                       thr.name            as ip,
                       ne.name             as ne,
                       pf.name             as pus_fine,
                       rm.name             as mato,
                       mt.name             as mato_turi,
                       p.kg                as rulon_kg,
                       pr.name             as model,
                       u.user_fio,
                       c.color_id,
                       c.pantone,
                       c_ton.name          as ctone,
                       c_tip.name          as ctip
                from boyahane_siparis_subpart bss
                         left join paketlar p on p.subpart_id = bss.id
                         left join thread thr on bss.thread_id = thr.id
                         left join boyahane_siparis_part bsp on bss.part_id = bsp.id
                         left join boyahane_siparis_musteri bsm on bsp.siparis_id = bsm.id
                         left join musteri m on bsm.musteri = m.id
                         left join color c on bsp.color_id = c.id
                         LEFT JOIN color c2 ON bsp.color_id2 = c2.id
                         LEFT JOIN color_tone c_ton ON bsp.color_tone = c_ton.id
                         LEFT JOIN color_type c_tip ON bsp.color_type = c_tip.id
                         left join ne on bss.ne_id = ne.id
                         LEFT JOIN raw_material rm ON bss.raw_material_id = rm.id
                         LEFT JOIN material_type mt ON bss.material_type_id = mt.id
                         LEFT JOIN product pr ON bsp.product_id = pr.id
                         LEFT JOIN pus_fine pf ON bss.pus_fine_id = pf.id
                         LEFT JOIN users u on p.user_uid = u.id
                where bss.id = :id
                ORDER BY p.id ASC;";
        } else {
            $sql = "select bdi.id,
                           m.name as mname,
                           bdi.is_accessory,
                           bdi.quantity as rulon_kg,
                           rm.name as mato,
                           nename.name as ne,
                           thr.name as ip,
                           pf.name as pus_fine,
                           c.color_id,
                           p.name as model,
                           bdi.party_no as partiya_no,
                           bdi.musteri_party_no as mijoz_part,
                           bmi.en as mato_en,
                           bmi.gramaj,
                           bdi.roll_count, 
                           bdi.id as roll_order
                    from bichuv_doc_items bdi
                    left join bichuv_doc bd on bdi.bichuv_doc_id = bd.id
                    left join product p on bdi.model_id = p.id
                    left join bichuv_mato_info bmi on bdi.entity_id = bmi.id
                    left join raw_material rm on bmi.rm_id = rm.id
                    left join ne nename on nename.id = bmi.ne_id
                    left join pus_fine pf on pf.id = bmi.pus_fine_id
                    left join thread thr on thr.id = bmi.thread_id
                    left join color c on bmi.color_id = c.id
                    left join color_tone ct on c.color_tone = ct.id    
                    left join musteri m on bd.musteri_id = m.id
                    WHERE bdi.entity_type = 2 AND bdi.id = :id GROUP BY bdi.id ORDER BY bdi.id ASC;";
        }
        $items = Yii::$app->db->createCommand($sql)->bindValue('id', $id)->queryAll();

        return $items;
    }

    /**
     * @param $packetId
     * @param $existsIdsConditions
     * @param $existsRolls
     * @return array
     * @throws Exception
     */
    public static function getRMInfoAjax($packetId, $existsIdsConditions, $existsRolls)
    {
        $sql = "select bss.id,
                       p.id                as pid,
                       m.name              as mname,
                       bsm.musteri_partiya as mijoz_part,
                       bsp.partiya_no,
                       bss.finish_gr       as gramaj,
                       bss.material_width  as mato_en,
                       thr.name            as ip,
                       thr.id              as thr_id,  
                       ne.name             as ne,
                       ne.id               as ne_id,
                       pf.name             as pus_fine,
                       pf.id               as pf_id,  
                       rm.name             as mato,
                       rm.id               as rm_id,  
                       mt.name             as mato_turi,
                       SUM(p.kg)           as rulon_kg,
                       COUNT(p.id)         as count_rulon,
                       pr.name             as model,
                       pr.id               as model_id,  
                       u.user_fio,
                       c.id                as c_id,  
                       c.color_id,
                       c.pantone,
                       c_ton.name          as ctone,
                       c_tip.name          as ctip
                from boyahane_siparis_subpart bss
                         left join paketlar p on p.subpart_id = bss.id
                         left join thread thr on bss.thread_id = thr.id
                         left join boyahane_siparis_part bsp on bss.part_id = bsp.id
                         left join boyahane_siparis_musteri bsm on bsp.siparis_id = bsm.id
                         left join musteri m on bsm.musteri = m.id
                         left join color c on bsp.color_id = c.id
                         LEFT JOIN color c2 ON bsp.color_id2 = c2.id
                         LEFT JOIN color_tone c_ton ON bsp.color_tone = c_ton.id
                         LEFT JOIN color_type c_tip ON bsp.color_type = c_tip.id
                         left join ne on bss.ne_id = ne.id
                         LEFT JOIN raw_material rm ON bss.raw_material_id = rm.id
                         LEFT JOIN material_type mt ON bss.material_type_id = mt.id
                         LEFT JOIN product pr ON bsp.product_id = pr.id
                         LEFT JOIN pus_fine pf ON bss.pus_fine_id = pf.id
                         LEFT JOIN users u on p.user_uid = u.id
                where bsp.partiya_no = '%s' %s %s
                GROUP BY bss.child_partiya_no, rm.id
                ORDER BY bss.id;";

        $sql = sprintf($sql, $packetId, $existsIdsConditions, $existsRolls);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }

    /**
     * @param $packetId
     * @return array
     * @throws Exception
     */
    public static function getRMInfoAjaxForRemain($packetId)
    {

        $sql = "select bdi.bss_id as id,
                       bdi.quantity as rulon_kg,
                       bdi.roll_count as count_rulon, 
                       bdi.model_id as model_id,
                       bsdi.model,
                       bdi.is_accessory,
                       bdi.party_no as partiya_no,
                       bdi.musteri_party_no as mijoz_part,
                       bsdi.gramaj,
                       bsdi.en as mato_en,
                       bsdi.thread as ip,
                       bsdi.ne,
                       bsdi.ne_id, 
                       bsdi.pus_fine,
                       bsdi.pus_fine_id as pf_id,
                       bsdi.mato,
                       bsdi.rm_id,
                       bsdi.thread_id as thr_id,
                       bsdi.c_id, 
                       bsdi.thread_consist,
                       bsdi.ctone,
                       bsdi.color_id,
                       bsdi.pantone
                from bichuv_doc_items bdi
                left join bichuv_doc bd on bdi.bichuv_doc_id = bd.id
                left join bichuv_sub_doc_items bsdi on bdi.id = bsdi.doc_item_id
                where bdi.party_no = '%s' AND bd.document_type = 1 AND bd.status = 3 GROUP BY bdi.entity_id;";
        $sql = sprintf($sql, $packetId);

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * @param $partyId
     * @param $rollIds
     * @param $musteriId
     * @param $adjustment
     * @return array
     * @throws Exception
     */
    public static function loadRollsByParty($partyId, $rollIds, $musteriId, $adjustment = false)
    {
        $conditionRollIds = "";
        $conditionMusteri = "";
        $conditionDept = "";
        if (!empty($rollIds)) {
            $ids = join(',', $rollIds);
            $conditionRollIds = " AND bdi.id NOT IN ({$ids}) ";
        }
        $isEmptyMusteri = true;
        if (!empty($musteriId)) {
            if ($musteriId != Constants::$NillGranitID) {
                $isEmptyMusteri = false;
            }
            $conditionMusteri = " AND m.id = {$musteriId}";
        } else {
            $musteriId = Constants::$NillGranitID;
        }
        $userId = Yii::$app->user->id;
        $conditionDept = " AND brib2.department_id IN (select tud.department_id from toquv_user_department tud where tud.user_id = {$userId}) ";
        $zeroCondition = " AND brib.inventory > 0";
        if($adjustment){
            $zeroCondition = "";
        }
        if ($isEmptyMusteri) {
            $sql = "select bdi.id,
                       brib.entity_id,
                       bdi.bss_id,
                       m.name as mname,
                       bdi.is_accessory,
                       rm.name as mato,
                       nename.name  as ne,
                       thr.name as ip,
                       pf.name as pus_fine,
                       c.color_id,
                       ct.name as ctone,
                       c.pantone,
                       p.name as model,
                       brib.party_no as partiya_no,
                       brib.musteri_party_no as mijoz_part,
                       bmi.en as mato_en,
                       bmi.gramaj,
                       brib.roll_inventory as rulon_count,
                       brib.inventory as rulon_kg,
                       brib.party_no,
                       brib.musteri_party_no,
                       brib.from_musteri, 
                       p.id as model_id
                from bichuv_rm_item_balance brib
                 left join bichuv_mato_info bmi on brib.entity_id = bmi.id
                 inner join bichuv_doc_items bdi on brib.entity_id = bdi.entity_id
                 left join raw_material rm on bmi.rm_id = rm.id
                 left join ne nename on bmi.ne_id = nename.id
                 left join pus_fine pf on bmi.pus_fine_id = pf.id
                 left join thread thr on bmi.thread_id = thr.id
                 left join color c on bmi.color_id = c.id
                 left join color_tone ct on c.color_tone = ct.id
                 left join musteri m on brib.from_musteri = m.id
                 left join product p on brib.model_id = p.id 
                WHERE brib.id IN (
                            select MAX(brib2.id) from bichuv_rm_item_balance brib2 
                            where brib2.party_no = '%s' AND brib2.from_musteri = %s %s  GROUP BY brib2.entity_id, brib2.party_no) %s %s %s
                GROUP BY brib.entity_id, brib.party_no ORDER BY brib.id ASC ;";
        } else {
            $sql = "select bdi.id,
                       brib.entity_id,
                       bdi.bss_id,
                       m.name as mname,
                       bdi.is_accessory,
                       rm.name as mato,
                       nename.name  as ne,
                       thr.name as ip,
                       pf.name as pus_fine,
                       c.color_id,
                       ct.name as ctone,
                       c.pantone,
                       p.name as model,
                       brib.party_no as partiya_no,
                       brib.musteri_party_no as mijoz_part,
                       bmi.en as mato_en,
                       bmi.gramaj,
                       brib.roll_inventory as rulon_count,
                       brib.inventory as rulon_kg,
                       brib.party_no,
                       brib.from_musteri, 
                       brib.musteri_party_no,
                       p.id as model_id
                from bichuv_rm_item_balance brib
                 inner join bichuv_doc_items bdi on brib.entity_id = bdi.entity_id
                 left join bichuv_mato_info bmi on brib.entity_id = bmi.id  
                 left join raw_material rm on bmi.rm_id = rm.id
                 left join ne nename on bmi.ne_id = nename.id
                 left join pus_fine pf on bmi.pus_fine_id = pf.id
                 left join thread thr on bmi.thread_id = thr.id
                 left join color c on bmi.color_id = c.id
                 left join color_tone ct on c.color_tone = ct.id
                 left join musteri m on brib.from_musteri = m.id
                 left join product p on brib.model_id = p.id 
                WHERE brib.id IN (
                            select MAX(brib2.id) from bichuv_rm_item_balance brib2 
                            where brib2.musteri_party_no = '%s' AND brib2.from_musteri = %s %s  GROUP BY brib2.entity_id, brib2.party_no) %s %s %s
                GROUP BY brib.entity_id, brib.party_no ORDER BY brib.id ASC ;";
        }

        $sql = sprintf($sql, $partyId, $musteriId, $conditionDept, $conditionMusteri, $conditionRollIds, $zeroCondition);
        $items = Yii::$app->db->createCommand($sql)->queryAll();
//        echo $sql;
//        die;
        return $items;
    }

    /**
     * @param null $key
     * @param bool $option
     * @return array|mixed|null
     */
    public function getSizeCollectionList($key = null,$option=false)
    {
        if (!empty($key)) {
            $list = SizeCollections::find()->where(['id' => $key])->select(['id', 'name'])->asArray()->one();
            if (!empty($list)) {
                return $list['name'];
            }
            return null;
        } else {
            $list = SizeCollections::find()->select(['id', 'name']);
            $sc_option = [];
            if ($option) {
                $list = $list->all();
                if ($list) {
                    foreach ($list as $item) {
                        $sc_option[$item['id']] = [
                            'data-size-list' => $item->getSizeList(false,true)
                        ];
                    }
                }
                return $sc_option;
            }
            $list = $list->asArray()->all();
            return ArrayHelper::map($list, 'id', 'name');
        }
    }

    /***
     * @param string $type
     * @return string|null
     */
    public function getNastelParty($type = 'slice')
    {
        $result = null;
        switch ($type) {
            case 'slice':
                $items = $this->getBichuvSliceItems()->asArray()->all();
                if (!empty($items)) {
                    $temp = [];
                    foreach ($items as $item) {
                        $temp[$item['nastel_party']] = $item['nastel_party'];
                    }
                    if (!empty($temp)) {
                        $result = join(', ', $temp);
                    }
                }
                break;
            case 'item':
                $items = $this->getBichuvDocItems()->asArray()->all();
                if (!empty($items)) {
                    $temp = [];
                    foreach ($items as $item) {
                        $temp[$item['nastel_no']] = $item['nastel_no'];
                    }
                    if (!empty($temp)) {
                        $result = join(', ', $temp);
                    }
                }
                break;

            case 'plan':
                $items = $this->getBichuvNastelDetails()->asArray()->all();
                if (!empty($items)) {
                    $temp = [];
                    foreach ($items as $item) {
                        $temp[$item['nastel_no']] = $item['nastel_no'];
                    }
                    if (!empty($temp)) {
                        $result = join(', ', $temp);
                    }
                }
                break;
        }

        return $result;
    }

    public function getWorkCount($type = 'slice',$list=false)
    {
        $result = 0;
        switch ($type) {
            case 'slice':
                if($list){
                    $result = [];
                    $result['count'] = 0;
                    $result['size'] = '';
                    $items = BichuvSliceItems::find()->where(['bichuv_doc_id'=>$this->id]);
                    if ($items->count()>0) {
                        $result['count'] = $items->sum('quantity');
                        foreach ($items->groupBy(['size_id'])->all() as $key => $item) {
                            $result['size'] .= ($key==0)?$item->size->name:",".$item->size->name;
                        }
                        $result['count'] = number_format($result['count'], 0, '.', '');
                    }
                    return $result;
                }else{
                    if (!empty($this->bichuvSliceItems)) {
                        foreach ($this->bichuvSliceItems as $item) {
                            $result += $item->quantity;
                        }
                    }
                }
                $result = number_format($result, 0, '.', ' ');
                break;
            case 'item':
                if (!empty($this->bichuvDocItems)) {
                    foreach ($this->bichuvDocItems as $item) {
                        $result += $item->quantity;
                    }
                }
                $result = number_format($result, 0, '.', ' ');
                break;
            case 'rm':
                if (!empty($this->bichuvDocItems)) {
                    foreach ($this->bichuvDocItems as $item) {
                        $result += $item->quantity;
                    }
                }
                $result = number_format($result, 3, '.', ' ');
                break;

        }

        return $result;
    }

    public function getSliceMovingViewOld($docId)
    {
        $sql = "select bsi.id,  
                       bsi.nastel_party,
                       s.name,
                       bsi.quantity,
                       bsi.fact_quantity,
                       bsi.invalid_quantity,
                       bsi.add_info,
                       bsi.work_weight,
                       mvp.code as print_code,
                       mvp.id as print_id,
                       mvs.code as stone_code,
                       mvs.id as stone_id,
                       CONCAT(ml.name,' (',ml.article,')') as model,                         
                       CONCAT(bgr.color_code,' (',bgr.color_name,')') as variation
                from bichuv_slice_items bsi
                         JOIN (select 
                                      mv.id as model_var_id, 
                                      ml.id as model_id, 
                                      ml.article, 
                                      mv.name, 
                                      cp.code, 
                                      bgr.id bgr_id, 
                                      mrp.order_id, 
                                      mrp.order_item_id, 
                                      CONCAT(mo.doc_number,' (',m.name,') ',(mo.sum_item_qty)) as model_order,
                                      m.id musteri,
                                      bgr.nastel_party,
                                      IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as color_name,
                                      IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as color_code
                            from bichuv_given_rolls bgr
                                left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                                left join models_list ml on mrp.models_list_id = ml.id
                                left join models_variations mv on mv.id = mrp.model_variation_id    
                                left join wms_color wc on mv.wms_color_id = wc.id
                                left join color_pantone cp on wc.color_pantone_id = cp.id
                                left join model_orders mo on mrp.order_id = mo.id
                                left join musteri m on mo.musteri_id = m.id
                         ) bgr ON bgr.nastel_party = bsi.nastel_party
                         left join model_var_prints mvp on mvp.id = bsi.model_var_print_id
                         left join model_var_stone mvs on mvs.id = bsi.model_var_stone_id
                         left join size s on bsi.size_id = s.id
                         left join model_rel_production mrp2 on mrp2.nastel_no = bsi.nastel_party
                         left join models_list ml on mrp2.models_list_id = ml.id
                where bsi.bichuv_doc_id = :docId GROUP BY bsi.id ORDER BY s.`order` ASC;
            ";
        return Yii::$app->db->createCommand($sql)->bindValue('docId', $docId)->queryAll();
    }

    public function getSliceMovingView($docId)
    {
        $sql = "select bsi.id,  
                       bsi.nastel_party,
                       s.name,
                       CONCAT(bdt.name, ' (', bdl.name, ')') as detail_name,
                       bsi.quantity,
                       bsi.fact_quantity,
                       bsi.invalid_quantity,
                       bsi.add_info,
                       bsi.work_weight,
                       mvp.code as print_code,
                       mvp.id as print_id,
                       mvs.code as stone_code,
                       mvs.id as stone_id,
                       ml.name as model,
                       ml.article                         
                from bichuv_slice_items bsi
                         left join bichuv_given_roll_items bgri ON bsi.bgri_id = bgri.id
                         left join mobile_process_production mpp on bsi.nastel_party = mpp.nastel_no
                         left join mobile_process_production mpp2 on mpp.parent_id = mpp2.id
                         left join bichuv_detail_types bdt ON mpp.bichuv_detail_type_id = bdt.id 
                         left join base_detail_lists bdl ON mpp.base_detail_list_id = bdl.id 
                         left join model_var_prints mvp on mvp.id = bsi.model_var_print_id
                         left join model_var_stone mvs on mvs.id = bsi.model_var_stone_id
                         left join size s on bsi.size_id = s.id
                         left join model_rel_production mrp2 on mrp2.nastel_no = mpp2.nastel_no
                         left join models_list ml on mrp2.models_list_id = ml.id
                where bsi.bichuv_doc_id = :docId  GROUP BY bsi.id ORDER BY s.`order` ASC ;
            ";

        return Yii::$app->db->createCommand($sql)->bindValue('docId', $docId)->queryAll();
    }

    public function getAccessoriesView()
    {
        $sql = "select acs.sku,
                       acs.name as acs,
                       bap.name as property,
                       bdi.quantity,
                       bdi.nastel_no,
                       p.name as model
                       from bichuv_doc bd
                left join bichuv_doc_items bdi on bd.id = bdi.bichuv_doc_id
                left join product p on bdi.model_id = p.id
                left join bichuv_acs acs on acs.id = bdi.entity_id
                left join bichuv_acs_property bap on acs.property_id = bap.id
                where bd.id = :docId;";
        return Yii::$app->db->createCommand($sql)->bindValues(['docId' => $this->id])->queryAll();
    }

    /**
     * @param $modelId
     * @return array
     * @throws Exception
     */
    public function getItemsWithSub($modelId)
    {
        $sql = "select bdi.entity_id,
                       bdi.entity_type,
                       bdi.quantity,
                       bdi.document_quantity,
                       bdi.current_usd,
                       bdi.roll_count,
                       bdi.is_accessory,
                       bdi.party_no,
                       bdi.musteri_party_no,
                       bdi.model_id,
                       bsdi.en,
                       bsdi.gramaj,
                       bsdi.ne,
                       bsdi.thread,
                       bsdi.pus_fine,
                       bsdi.ctone,
                       bsdi.color_id,
                       bsdi.pantone,
                       bsdi.mato,
                       bsdi.model,
                       bsdi.thread_consist 
               from bichuv_doc_items bdi
               left join bichuv_doc_items bdi2 on bdi.entity_id = bdi2.id
               left join bichuv_sub_doc_items bsdi on bdi2.id = bsdi.doc_item_id
               where bdi.bichuv_doc_id = :docId GROUP BY bsdi.doc_item_id;";
        $items = Yii::$app->db->createCommand($sql)->bindValue('docId', $modelId)->queryAll();
        return $items;
    }

    public function getHeaderInfo()
    {
        $bichuvId = HrDepartments::findOne(['token' => 'BICHUV']);
        $result = HrDepartmentResponsiblePerson::find()
            ->where(['hr_department_id' => $bichuvId])
            ->andWhere(['status' => BaseModel::STATUS_ACTIVE])
            ->asArray()
            ->one();
        if (!empty($result)) {
            return $result;
        }
        return false;

    }

    public function getAcceptedItems()
    {
        $sql = "select bdi.quantity,
                       acs.name,
                       acs.sku,
                       bap.name as property
                from bichuv_doc_items bdi
                left join bichuv_acs acs on bdi.entity_id = acs.id
                left join bichuv_acs_property bap on acs.property_id = bap.id
                where bdi.bichuv_doc_id = :id";
        $result = Yii::$app->db->createCommand($sql)->bindValues(['id' => $this->id])->queryAll();
        return $result;
    }

    public function getProductModels()
    {
        $m = Product::find()->asArray()->all();
        return ArrayHelper::map($m, 'id', 'name');
    }

    /**
     * @param string $type
     * @return string|null
     */
    public function getProductModelList($type = 'item')
    {
        if ($type == 'slice') {
            $pm = $this->getBichuvSliceItems()->with(['productModel'])->asArray()->all();
        } elseif ($type == 'plan') {
            $pm = $this->getBichuvNastelDetails()->with(['productModel'])->asArray()->all();
        } else {
            $pm = $this->getBichuvDocItems()->with(['productModel'])->asArray()->all();
        }
        $model = [];
        if (!empty($pm)) {
            foreach ($pm as $item) {
                if (!empty($item['productModel'])) {
                    $model[$item['productModel']['id']] = $item['productModel']['name'];
                }
            }
        }
        if (!empty($model)) {
            return join(', ', $model);
        }
        return null;
    }



    public function getPartyNoNames()
    {

        $sql = "select bgri.party_no from bichuv_slice_items bsi
                    left join bichuv_given_rolls bgr on bsi.bichuv_given_roll_id = bgr.id
                    left join bichuv_given_roll_items bgri on bgr.id = bgri.bichuv_given_roll_id
                    where bsi.bichuv_doc_id = :id GROUP BY bgri.party_no;";
        $items = Yii::$app->db->createCommand($sql)->bindValue('id', $this->id)->queryAll();
        $res = [];
        foreach ($items as $item) {
            $res[$item['party_no']] = $item['party_no'];
        }
        if (!empty($res)) {
            return join(', ', $res);
        }
        return null;
    }

    /**
     * @param bool $otherDept
     * @param string $token
     * @param bool $fromProduction
     * @return array|null
     * @throws Exception
     */
    public function getNastelNumbers($otherDept = false, $token = '', $fromProduction = false)
    {
        if ($otherDept) {
            if (!empty($token)) {
                $condition = "AND bd.from_department IN (select td.id from toquv_departments td where td.token = '{$token}')";
            }
        } else {
            $currentUserId = Yii::$app->user->id;
            $condition = " AND bd.from_department IN (select tud.department_id from toquv_user_department tud where tud.user_id = {$currentUserId}) ";
        }
        $result = null;
        if (!empty($condition)) {
            if ($fromProduction) {
                $sql = "select bgr.nastel_party as party_no,
                               SUM(bgri.quantity) as inventory,
                               bgr.musteri_id,
                               p.name as model,
                               p.id as model_id  
                    from bichuv_given_rolls bgr 
                    left join bichuv_given_roll_items bgri on bgr.id = bgri.bichuv_given_roll_id
                    left join product p on bgri.model_id = p.id
                    where bgr.status = 3 GROUP BY bgr.id ORDER BY bgr.id DESC LIMIT 100;";
            } else {
                $sql = "select  bsi.nastel_party as party_no,
                            SUM(bsi.quantity) as inventory,
                            bd.musteri_id,
                            p.name as model,
                            p.id as model_id 
                    from bichuv_doc bd
                    left join bichuv_slice_items bsi on bd.id = bsi.bichuv_doc_id
                    left join product p on bsi.model_id = p.id
                    where bd.document_type = '%d' %s GROUP BY bsi.nastel_party ORDER BY bd.id DESC;";
            }
            $sql = sprintf($sql, self::DOC_TYPE_INSIDE, $condition);
            $m = Yii::$app->db->createCommand($sql)->queryAll();
            $result = [];
            if (!empty($m)) {
                foreach ($m as $item) {
                    $result['nastelAttr'][$item['party_no']] = [
                        'data-nastel' => $item['inventory'],
                        'data-model' => $item['model'],
                        'data-musteri' => $item['musteri_id'],
                        'data-model-id' => $item['model_id']
                    ];
                    if ($otherDept) {
                        $result['data'][$item['party_no']] = $item['party_no']
                            . " (" . number_format($item['inventory'], 0, '.', ' ') . ")";
                    } else {
                        $result['data'][$item['party_no']] = $item['party_no'];
                    }
                }
            }
        }
        return $result;
    }

    public function getRmWithNastel($nastel = null, $key = false, $isAjax = false, $fromIB = false)
    {
        if ($fromIB) {
            $userId = Yii::$app->user->id;
            $sql = "select brib.entity_id,
                           brib.inventory,
                           brib.roll_inventory,
                           rm.name as mato,
                           nename.name as ne,
                           pf.name as pus_fine,
                           thr.name as thread,
                           c.color_id,
                           c.pantone,
                           ct.name as ctone,
                           bmi.gramaj,
                           bmi.en,
                           p.name as model,
                           p.id as  model_id,
                           brib.party_no,
                           brib.musteri_party_no,
                           brib.from_musteri 
                        from bichuv_rm_item_balance brib
                        left join bichuv_mato_info bmi on brib.entity_id = bmi.id
                        left join product p on brib.model_id = p.id
                        left join raw_material rm on bmi.rm_id = rm.id
                        left join ne nename on nename.id = bmi.ne_id
                        left join pus_fine pf on pf.id = bmi.pus_fine_id
                        left join thread thr on thr.id = bmi.thread_id
                        left join color c on bmi.color_id = c.id
                        left join color_tone ct on c.color_tone = ct.id
                    where brib.id IN (select MAX(brib2.id) from bichuv_rm_item_balance brib2 
                                        where brib2.department_id IN 
                                              (select tud.department_id from toquv_user_department tud where tud.user_id = :userId) 
                    GROUP BY brib2.entity_id)
                    AND brib.inventory > 0 GROUP BY brib.entity_id;";
            $res = Yii::$app->db->createCommand($sql)->bindValue('userId', $userId)->queryAll();
            $out = [];
            if (!empty($res)) {
                foreach ($res as $item) {
                    $out['data'][$item['entity_id']] = '(' . $item['party_no'] . '/' . $item['musteri_party_no'] . ')-'
                        . $item['mato'] . '-' . $item['ne'] . '-' . $item['thread'] . '|' . $item['pus_fine'] . ' (' . $item['model'] . ')';
                    $out['dataAttr'][$item['entity_id']] = [
                        'data-remain' => $item['inventory'],
                        'data-remain-roll' => $item['roll_inventory'],
                        'data-model-id' => $item['model_id'],
                        'data-musteri' => $item['from_musteri'],
                        'data-party-no' => $item['party_no'],
                        'data-musteri-party-no' => $item['musteri_party_no']
                    ];
                }
            }
            return $out;
        }
        $conditions = "";
        if ($nastel) {
            $conditions = " AND bgr.nastel_party LIKE '{$nastel}%' ";
        }
        $sql = "select bgri.entity_id,
                       rm.name as mato,
                       nename.name as ne,
                       pf.name as pus_fine,
                       thr.name as thread,
                       c.color_id,
                       c.pantone,
                       ct.name as ctone,
                       bmi.gramaj,
                       bmi.en,
                       bgr.nastel_party 
                from bichuv_given_rolls bgr
                left join bichuv_given_roll_items bgri on bgr.id = bgri.bichuv_given_roll_id
                left join bichuv_mato_info bmi on bgri.entity_id = bmi.id
                left join product p on bgri.model_id = p.id
                left join raw_material rm on bmi.rm_id = rm.id
                left join ne nename on nename.id = bmi.ne_id
                left join pus_fine pf on pf.id = bmi.pus_fine_id
                left join thread thr on thr.id = bmi.thread_id
                left join color c on bmi.color_id = c.id
                left join color_tone ct on c.color_tone = ct.id WHERE bgr.status = 3 %s ORDER BY bgr.nastel_party DESC;";

        $sql = sprintf($sql, $conditions);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        if ($isAjax) {
            $out = [];
            $out['results'] = [];
            if (!empty($res)) {
                foreach ($res as $key => $item) {
                    array_push($out['results'], [
                        'id' => $item['entity_id'],
                        'text' => "(" . $item['nastel_party'] . ") " . $item['mato'] . "-" . $item['ne'] . "-" . $item['thread'] . "|"
                            . $item['pus_fine'],
                        'nastel' => $item['nastel_party']
                    ]);
                }
            } else {
                $out['results'] = ['id' => null, 'text' => null, 'nastel' => null];
            }
            return $out;
        }
        if ($key) {
            return ArrayHelper::map($res, 'entity_id', function ($m) {
                return "(" . $m['nastel_party'] . ") " . $m['mato'] . "-" . $m['ne'] . "-" . $m['thread'] . "|" . $m['pus_fine'];
            });
        }
        return $res;

    }

    public function getBichuvBekaView()
    {
        $sql = "select rm.name     as mato,
                       nename.name as ne,
                       thr.name    as thread,
                       pf.name     as pus_fine,
                       c.color_id,
                       c.pantone,
                       ct.name     as ctone,
                       bb.weight,
                       bb.roll_count,
                       bb.party_no,
                       bb.musteri_party_no,
                       p.name as model 
                from bichuv_beka bb
                left join product p on bb.model_id = p.id
                left join bichuv_mato_info bmi on bmi.id = bb.entity_id
                left join raw_material rm on bmi.rm_id = rm.id
                left join ne nename on nename.id = bmi.ne_id
                left join pus_fine pf on pf.id = bmi.pus_fine_id
                left join thread thr on thr.id = bmi.thread_id
                left join color c on bmi.color_id = c.id
                left join color_tone ct on c.color_tone = ct.id where bb.weight > 0 AND bb.bichuv_doc_id = :docId;";
        return Yii::$app->db->createCommand($sql)->bindValue('docId', $this->id)->queryAll();
    }

    /**
     * @param $nastelNo
     * @return array|bool
     * @throws Exception
     */
    public function getBekaDataViaNastelNo($nastelNo)
    {

        $sql = "select bgr.id,
                       bgri.entity_id,
                       bgri.party_no,
                       bgri.musteri_party_no,
                       bgr.nastel_party as nastel_no,
                       bgri.model_id 
       from bichuv_given_rolls bgr 
                left join bichuv_given_roll_items bgri on bgr.id = bgri.bichuv_given_roll_id
                where bgr.nastel_party in ({$nastelNo}) ORDER BY bgr.id ;";
        $res = Yii::$app->db->createCommand($sql)->queryAll();

        if (!empty($res)) {
            return $res;
        }
        return false;
    }

    public function getNastelWorkerName()
    {
        $user = HrEmployee::find()->where(['id' => $this->nastel_table_worker])->asArray()->one();
        if (!empty($user)) {
            return $user['fish'];
        }
        return null;
    }

    public function getAccessoriesFromIB()
    {
        $userId = Yii::$app->user->id;
        $sql = "select bib.entity_id,
                       p.name as model,
                       acs.sku, 
                       acs.name, 
                       bap.name as property, 
                       bib.inventory
                from bichuv_item_balance bib
                         left join bichuv_doc bd on bib.document_id = bd.id
                         left join bichuv_doc_items bdi on bd.id = bdi.bichuv_doc_id
                         left join product p on bdi.model_id = p.id
                         left join bichuv_acs acs on bib.entity_id = acs.id
                         left join bichuv_acs_property bap on acs.property_id = bap.id
                where bib.id IN (select MAX(bib2.id)
                                 from bichuv_item_balance bib2
                                 WHERE bib2.department_id IN
                                       (select tud.department_id from toquv_user_department tud where tud.user_id = %d)
                                 GROUP BY bib2.entity_id)
                  AND bib.inventory > 0
                GROUP BY bib.entity_id
                ORDER BY acs.sku, acs.name DESC;";
        $sql = sprintf($sql, $userId);
        $queries = Yii::$app->db->createCommand($sql)->queryAll();
        $results = [];
        foreach ($queries as $item) {
            $results['data'][$item['entity_id']] = $item['sku'] . "-" . $item['name'] . "-" . $item['property'];
            $results['dataAttr'][$item['entity_id']] = [
                'data-remain' => $item['inventory'],
                'data-model' => $item['model']
            ];
        }
        return $results;
    }

    /**
     * @param null $id
     * @param bool $all
     * @param bool $keyVal
     * @return array|string|null
     * @throws Exception
     */
    public static function getAccessories($id = null, $all = false, $keyVal = false)
    {
        if (!empty($id)) {
            $sql = "SELECT
                        bichuv_acs.id, 
                        bichuv_acs.barcode, 
                        bichuv_acs.sku, 
                        bichuv_acs.name,
                        bichuv_acs_properties.value
                        FROM bichuv_acs
                        LEFT JOIN bichuv_acs_properties
                        ON bichuv_acs.id = bichuv_acs_properties.bichuv_acs_id 
                        WHERE bichuv_acs.id = :id
                       ";
            $accs = Yii::$app->db->createCommand($sql)->bindParam(':id', $id)->queryAll();
            if (!empty($accs)) {
                $str = '';
                $result = [];

                if ($keyVal) {
                    return ArrayHelper::map($accs, 'id', function ($m) {
                        return $m['sku'] . ' ' . $m['name'] . ' ' . $m['value'];
                    });
                } else {

                    foreach ($accs as $ip) {
                        if(isset($result['data'][$ip['id']])){
                            $str .= '  '.$ip['value'];
                        }
                        else{
                            $str = '';
                            $str .= $ip['sku'] . ' ' . $ip['name'] . ' ' . $ip['value'];
                            $result['data'][$ip['id']] = $str;
                        }

                    }
                }
                return $result['data'];
            }
            if ($accs) {
                return $accs['sku'] . ' - ' . $accs['name'] . ' ';
            }
        }
        else {
            $inactive = BichuvAcs::STATUS_ACTIVE;
            if ($all) {
                $sql = "SELECT
                        bichuv_acs.id, bichuv_acs.barcode, bichuv_acs.sku, bichuv_acs.name,
                        bichuv_acs_properties.value
                        FROM bichuv_acs
                        LEFT JOIN bichuv_acs_properties
                        ON bichuv_acs.id = bichuv_acs_properties.bichuv_acs_id 
                        WHERE bichuv_acs.status = '{$inactive}'";
            } else {
                $sql = "select accs.id, accs.sku, accs.name, bap.name as property from bichuv_acs accs 
                    left join bichuv_acs_property bap on accs.property_id = bap.id
                    left join bichuv_item_balance bib on bib.entity_id = accs.id
                    where accs.status = {$inactive} AND bib.inventory > 0 AND bib.id IN (select MAX(bib2.id) from bichuv_item_balance bib2 
                    where bib2.entity_id = accs.id) limit 1000";
            }
            $accs = Yii::$app->db->createCommand($sql)->queryAll();

            if (!empty($accs)) {
                $str = '';
                $result = [];
                if ($keyVal) {
                    return ArrayHelper::map($accs, 'id', function ($m) {
                        return $m['name'] . ' ' . $m['value'];
                    });
                } else {
                    foreach ($accs as $ip) {
                        if(isset($result['data'][$ip['id']])){
                            $str .= '  '.$ip['value'];
                        }
                        else{
                            $str = '';
                            $str .= $ip['name'] . ' ' . $ip['value'];
                        }
                        $result['data'][$ip['id']] = $str;
                        $result['barcodeAttr'][$ip['id']] = ['data-barcode' => $ip['barcode']];
                    }
                }
                return $result;
            }
            return null;
        }
    }

    public function getProductionNastelNumber($limit = 60, $withAttr = true)
    {
        $sql = "select bgr.nastel_party,
                       p.name as model,
                       p.id as model_id,
                       bgr.musteri_id 
                from bichuv_given_rolls bgr
                left join bichuv_given_roll_items bgri on bgr.id = bgri.bichuv_given_roll_id
                left join product p on bgri.model_id = p.id WHERE bgr.status = 3 GROUP BY bgr.id ORDER BY bgr.id DESC LIMIT :limitCount;";
        $out = [];
        $results = Yii::$app->db->createCommand($sql)->bindValue('limitCount', $limit)->queryAll();
        if ($withAttr) {
            foreach ($results as $result) {
                $out['data'][$result['nastel_party']] = $result['nastel_party'];
                $out['dataAttr'][$result['nastel_party']] = [
                    'data-model-id' => $result['model_id'],
                    'data-model' => $result['model'],
                    'data-musteri' => $result['musteri_id']
                ];
            }
            return $out;
        }
        return $results;
    }

    public function getDetailTypes()
    {
        $out = [];
        $details = $this->bichuvNastelDetails;
        if (!empty($details)) {
            foreach ($details as $detail) {
                $out[$detail->detailType->id] = $detail->detailType->name;
            }
        }
        if (!empty($out)) {
            return join(', ', $out);
        }
        return null;
    }

    public function getModelListInfoOld()
    {
        $sql = "select 
                mv.id as model_var_id, 
                ml.id as model_id, 
                ml.article, 
                # mv.name, 
                # cp.code, 
                bgr.id bgr_id, 
                CONCAT(mo.doc_number,' (',m.name,') ',(mo.sum_item_qty)) as model_order,
                IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as code,
                IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as name
            from bichuv_given_rolls bgr
                left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                left join models_list ml on mrp.models_list_id = ml.id
                left join models_variations mv on mv.id = mrp.model_variation_id
                left join wms_color wc on mv.wms_color_id = wc.id                
                left join color_pantone cp on wc.color_pantone_id = cp.id    
                left join model_orders mo on mrp.order_id = mo.id
                left join musteri m on mo.musteri_id = m.id
                where bgr.nastel_party = '%s' GROUP BY mv.id;";
        $sql = sprintf($sql, $this->bichuvSliceItems[0]->nastel_party);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $out = [];
        $out['model_var'] = null;
        $out['model'] = null;
        $out['model_var_code'] = null;
        $out['model_id'] = null;
        $out['model_var_id'] = null;
        $out['bgr_id'] = null;
        $out['model_order'] = null;
        if (!empty($results)) {
            foreach ($results as $item) {
                $out['model_id'] = $item['model_id'];
                $out['model_var_id'] = $item['model_var_id'];
                $out['model'] = $item['article'];
                $out['bgr_id'] = $item['bgr_id'];
                $out['model_order'] = $item['model_order'];
                $code = $item['code'];
                if (empty($out['model_var'])) {
                    $out['model_var'] = $code . " (" . $item['name'] . ")";
                    $out['model_var_code'] = "<span>" . $code . " (" . $item['name'] . ")" . "</span>";
                } else {
                    $out['model_var_code'] .= "<span>" . $code . " (" . $item['name'] . ")" . "</span>";
                    $out['model_var'] .= ", " . $code . " (" . $item['name'] . ")";
                }
            }
        }
        return $out;
    }

    public function getModelListInfo()
    {
        $sql = "select 
            mv.id as model_var_id,
            ml.id as model_id, 
            ml.article, 
            # mv.name, 
            # cp.code, 
            bgr.id bgr_id,
            CONCAT(mo.doc_number,' (',m.name,') ',(mo.sum_item_qty)) as model_order,
            IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as code,
            IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as name
       from mobile_process_production mpp
                left join mobile_process_production mpp2 on mpp.parent_id = mpp2.id
                left join bichuv_given_rolls bgr on bgr.nastel_party = mpp2.nastel_no
                left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                left join models_list ml on mrp.models_list_id = ml.id
                left join models_variations mv on mv.id = mrp.model_variation_id
                left join wms_color wc on mv.wms_color_id = wc.id                
                left join color_pantone cp on wc.color_pantone_id = cp.id 
                left join model_orders mo on mrp.order_id = mo.id
                left join musteri m on mo.musteri_id = m.id
                where mpp.nastel_no = '%s' AND mpp.parent_id IS NOT NULL GROUP BY mv.id";
        $sql = sprintf($sql, $this->bichuvSliceItems[0]->nastel_party);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $out = [];
        $out['model_var'] = null;
        $out['model'] = null;
        $out['model_var_code'] = null;
        $out['model_id'] = null;
        $out['model_var_id'] = null;
        $out['bgr_id'] = null;
        $out['model_order'] = null;
        if (!empty($results)) {
            foreach ($results as $item) {
                $out['model_id'] = $item['model_id'];
                $out['model_var_id'] = $item['model_var_id'];
                $out['model'] = $item['article'];
                $out['bgr_id'] = $item['bgr_id'];
                $out['model_order'] = $item['model_order'];
                $code = $item['code'];
                if (empty($out['model_var'])) {
                    $out['model_var'] = $code . " (" . $item['name'] . ")";
                    $out['model_var_code'] = "<span>" . $code . " (" . $item['name'] . ")" . "</span>";
                } else {
                    $out['model_var_code'] .= "<span>" . $code . " (" . $item['name'] . ")" . "</span>";
                    $out['model_var'] .= ", " . $code . " (" . $item['name'] . ")";
                }
            }
        }
        return $out;
    }

    public function checkBgr($count=null){
        $sql = "SELECT bgr.id id, bsi.nastel_party, bd.to_department
        FROM bichuv_slice_items bsi
        LEFT JOIN bichuv_doc bd on bsi.bichuv_doc_id = bd.id
        LEFT JOIN bichuv_given_rolls bgr ON bgr.nastel_party = bsi.nastel_party
        WHERE bsi.bichuv_doc_id = {$this->id} GROUP BY bsi.nastel_party";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $btn = '';
        $i = 0;
        if(!empty($res)){
            foreach ($res as $item) {
                $tkbgr = TikuvKonveyerBichuvGivenRolls::findOne(['bichuv_given_rolls_id'=>$item['id']]);
                if (empty($tkbgr)){
                    $btn .= "<a href='".Yii::$app->urlManager->createUrl(['bichuv/doc/add-konveyer','id'=>$item['id'],'dept'=>$item['to_department'],'slug'=>'kirim_mato'])."' class='btn btn-success add-konveyer'>".Yii::t('app', 'Konveyer biriktirish')."(<b>{$item['nastel_party']}</b>)</a>&nbsp;";
                    $i++;
                }
            }
        }
        if($count){
            return $i;
        }
        return $btn;
    }
    /**
     * @return ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'models_list_id']);
    }

    public function getChildDoc()
    {
        return $this->hasOne(self::className(),['parent_id'=>'id']);
    }

    public function getParentDoc()
    {
        return $this->hasMany(self::className(),['id'=>'parent_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_var_id']);
    }

    public function getAks(){
        $sql = "SELECT 
                   ba.id,
                   bgr.nastel_party,
                   CONCAT(ba.sku,' <b>',ba.name,'</b> ', GROUP_CONCAT(DISTINCT bapl.name, ': ', bap.value SEPARATOR ', ')) aks,
                   moia.qty, 
                   moia.unit_id,
                   u.name as unit_name
                FROM bichuv_slice_items bsi
                LEFT JOIN bichuv_doc bd on bsi.bichuv_doc_id = bd.id
                LEFT JOIN bichuv_given_rolls bgr ON bgr.nastel_party = bsi.nastel_party
                LEFT JOIN model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                LEFT JOIN model_orders_items moi on mrp.order_item_id = moi.id
                LEFT JOIN model_orders_items_acs moia on moi.id = moia.model_orders_items_id
                LEFT JOIN bichuv_acs ba on moia.bichuv_acs_id = ba.id
                LEFT JOIN bichuv_acs_properties bap on ba.id = bap.bichuv_acs_id
                LEFT JOIN bichuv_acs_property_list bapl on bap.bichuv_acs_property_list_id = bapl.id
                LEFT JOIN unit as u on moia.unit_id = u.id
                WHERE bsi.bichuv_doc_id = :id 
                GROUP BY bsi.nastel_party,moi.id,ba.id";
        $res = Yii::$app->db->createCommand($sql)
            ->bindParam(':id', $this->id)
            ->queryAll();
        return $res;
    }

    public function deleteOne()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try {
            $child = BichuvSliceItems::find()->where(['bichuv_doc_id'=>$this->id])->all();
            $user = Yii::$app->user->identity;
            $doc_type = $this->document_type;
            if(!empty($child)){
                foreach ($child as $item) {
                    if($item->delete()){
                        $saved = true;
                        if($doc_type==self::DOC_TYPE_INSIDE){
                            Log::saveLog(
                                $item->oldAttributes,
                                $item->attributes,
                                'delete_slice',
                                $item::className(),
                                $user->id ?? null,
                                $user->user_fio ?? null,
                                $user->username ?? null,
                                $item::tableName()
                            );
                        }
                    }else{
                        $saved = false;
                    }
                }
            }else{
                $saved = true;
            }
            if($saved){
                $bichuv_beka = BichuvBeka::find()->where(['bichuv_doc_id'=>$this->id])->all();
                if($bichuv_beka){
                    foreach ($bichuv_beka as $item) {
                        if($item->delete()){
                            $saved = true;
                        }else{
                            $saved = false;
                        }
                    }
                }
            }
            if($this->delete()&&$saved){
                $saved = true;
                if($doc_type==self::DOC_TYPE_INSIDE) {
                    Log::saveLog(
                        $this->oldAttributes,
                        $this->attributes,
                        'delete_slice_doc',
                        $this::className(),
                        $user->id ?? null,
                        $user->user_fio ?? null,
                        $user->username ?? null,
                        $this::tableName()
                    );
                }
            }
            if($saved) {
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            Yii::info('Not saved' . $e, 'save');
            $transaction->rollBack();
        }
        return $saved;
    }

    public static function getLastId() {
        $lastId = static::find()
            ->select('id')
            ->orderBy(['id' => SORT_DESC])
            ->scalar();

        return $lastId ? intval($lastId) + 1 : 1;
    }

    public static function getModelByNastelNo($nastelNo) {
        if (empty($nastelNo)) {
            return null;
        }

        $query = BichuvDocItems::find()
            ->alias('bdi')
            ->select([
                'ml.article as model_article',
            ])
            ->leftJoin(['bgr' => 'bichuv_given_rolls'], 'bdi.nastel_no = bgr.nastel_party')
            ->leftJoin(['mrp' => 'model_rel_production'], 'bgr.id = mrp.bichuv_given_roll_id')
            ->leftJoin(['ml' => 'models_list'], 'mrp.models_list_id = ml.id')
            ->leftJoin(['mv' => 'models_variations'], 'mrp.model_variation_id = mv.id')
            ->andWhere(['bdi.nastel_no' => $nastelNo]);

        return $query->asArray()->scalar();
    }

    public static function getRelDocByNastelNo($nastelNo)
    {
        $tayyorlovDepId = ToquvDepartments::find()->select('id')->andWhere(['token' => 'TAYYORLOV'])->scalar();
        $query = static::find()
            ->alias('bd')
            ->leftJoin(['bdi' => 'bichuv_doc_items'], 'bd.id = bdi.bichuv_doc_id')
            ->andWhere([
                'bd.status' => 3,
                'bd.document_type' => 7,
                'bd.to_department' => $tayyorlovDepId,
                'bdi.nastel_no' => $nastelNo,
                'bdi.entity_type' => 1,
            ]);

        return $query->all();
    }

    public function getHrEmployees()
    {
        $result = ArrayHelper::map(HrEmployee::find()->asArray()->all(), 'id', 'fish');
        return $result;
    }

    public function getUsersData($id = null)
    {
        if($id === null){
            $model = HrEmployee::find()->asArray()->all();
            $result = ArrayHelper::map($model, 'id', 'fish');
        }
        else{
            $model = Users::findOne($id);
            $result = $model;
        }
        return $result;
    }

    public function getEmployee($id)
    {
        $user = HrEmployeeUsers::findOne(['users_id' => $id]);
        $employee = HrEmployee::findOne($user->hr_employee_id);
        return $employee->fish;
    }

    public function getNastelLists()
    {
        return ArrayHelper::map(BichuvNastelLists::find()->asArray()->all(), 'id','name');
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\base\InvalidConfigException
     * Kesilgan mato ya'ni tayyor kesimlar nastel stol va nastelchi haqida malumotlar
     */
    public function getMobileTableInfoBySlice($id){

        $__nastelList = BichuvSliceItems::find()
            ->select(['nastel_party'])
            ->where(['bichuv_doc_id' => $id])
            ->groupBy(['nastel_party'])
            ->asArray()
            ->all();
        $__nastelList = ArrayHelper::getColumn($__nastelList, 'nastel_party');
        $__resultArray = [
            'tables' => '',
            'employess' => '',
        ];

        if (!empty($__nastelList)){

            $__processProduction = MobileProcessProduction::find()
                ->alias('mpp')
                ->select(['mt.name table_name','he.fish employee_name'])
                ->leftJoin(['mt' => 'mobile_tables'],'mpp.mobile_tables_id = mt.id')
                ->leftJoin(['mtrhe' => 'mobile_tables_rel_hr_employee'],'mt.id = mtrhe.mobile_tables_id')
                ->leftJoin(['he' => 'hr_employee'],'mtrhe.hr_employee_id = he.id')
                ->where(['in','mpp.nastel_no', $__nastelList])
                ->andFilterWhere(['mpp.table_name' => BichuvGivenRollItems::getTableSchema()->name])
                ->andFilterWhere(['mtrhe.status' => MobileTablesRelHrEmployee::STATUS_ACTIVE])
                ->asArray()
                ->all();
            if(!empty($__processProduction)){

                $__resultArray['tables'] = "<code>".join('<br>', array_unique(ArrayHelper::getColumn($__processProduction,'table_name')))."</code>";
                $__resultArray['employess'] = "<code>".join('<br>', array_unique(ArrayHelper::getColumn($__processProduction,'employee_name')))."</code>";
            }
        }

        return $__resultArray;
    }

    /** Buyurtmalarini olib berish */
    public static function getModelOrdersMapList()
    {
        $model = ModelOrders::find()->where([
                'status' => ModelOrders::STATUS_PLANNED,
                'orders_status' => ModelOrders::STATUS_PLANNED,
            ])->all();
        if(!empty($model)){
            return ArrayHelper::map($model, 'id', 'doc_number');
        }
        return false;
    }

    public function getModelOrders()
    {
        return $this->hasOne(ModelOrders::class, ['id' => 'model_orders_id']);
    }

    /** Buyurtmani acc larini olib kelish */
    public static function getModelOrdersAcs($id)
    {
        $planned = ModelOrders::STATUS_PLANNED;
        $data = "
            SELECT ba.id as baid, mo.doc_number, moia.qty, ba.sku, ba.name as baname, ba.barcode, bap.value FROM
model_orders mo 
            LEFT JOIN model_orders_items moi ON mo.id = moi.model_orders_id
            LEFT JOIN model_orders_items_acs moia ON moia.model_orders_items_id = moi.id
            LEFT JOIN bichuv_acs ba ON ba.id = moia.bichuv_acs_id
            LEFT JOIN bichuv_acs_properties bap ON bap.bichuv_acs_id = ba.id
            LEFT JOIN unit u ON u.id = ba.unit_id
            WHERE mo.id = {$id} AND moi.status = {$planned}
        ";
        $query = Yii::$app->db->createCommand($data)->queryAll();
        $name = [];
        if(!empty($query)){
            $isArray = [];
            foreach ($query as $item) {
                $isArray[$item['baid']]['name'] = $item['baname'];
                $isArray[$item['baid']]['qty'] = $item['qty'];
                $isArray[$item['baid']]['id'] = $item['baid'];
                $isArray[$item['baid']]['value'][] = $item['value'];
            }

            foreach ($isArray as $key => $item) {
                $name[] = $item;
            }
            return $name;
        }
        else{
            return false;
        }
    }
}
