<?php

namespace app\modules\bichuv\models;

use app\components\OurCustomBehavior;
use app\models\Constants;
use app\models\Users;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\ModelVariationParts;
use app\modules\base\models\SizeCollections;
use app\modules\hr\models\HrEmployee;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\toquv\models\Musteri;


/**
 * This is the model class for table "bichuv_given_rolls".
 *
 * @property int $id
 * @property string $reg_date
 * @property string $doc_number
 * @property string $add_info
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $nastel_user_id
 *
 * @property BichuvGivenRollItems[] $bichuvGivenRollItems
 * @property Users $nastelUser
 * @property BichuvNastelDetails[] $bichuvNastelDetails
 * @property ModelRelProduction[] $modelRelProductions
 * @property BichuvDetailTypes $bichuvDetailType
 * @property Musteri $customer
 * @property Musteri $musteri
 * @property BichuvAcceptedMatoFromProduction[] $bichuvAcceptedMatoFromProduction
 * @property string $nastel_party [varchar(50)]
 * @property int $type [smallint(2)]
 * @property int $nastel_no [int(11)]
 * @property int $musteri_id [bigint(20)]
 * @property int $bichuv_detail_type_id [int(11)]
 * @property int $size_collection_id [int(11)]
 * @property array $modelListInfo
 * @property mixed $details
 * @property null|string $entityList
 * @property string $sizes
 * @property null|string $modelName
 * @property string $dIEntityIds
 * @property array $modelLists
 * @property mixed $reqCount
 * @property int $customer_id [bigint(20)]
 * @property int $updated_by [int(11)]
 */
class BichuvGivenRolls extends BaseModel
{
    public $updated_by;

    public $model_list_id;
    public $model_var_id;
    public $model_var_part_id;
    public $order_id;
    public $order_item_id;
    public $model_name;
    public $price;
    public $pb_id;


    private $_count = 0;
    private $_count_acs = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_given_rolls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reg_date','model_var_id','model_list_id','order_id','order_item_id'], 'safe'],
            [['add_info'], 'string'],
            [['nastel_party', 'add_info','customer_id','size_collection_id'], 'required'],
            ['nastel_party', 'unique'],
            [['created_by','customer_id','bichuv_detail_type_id', 'status', 'type', 'musteri_id', 'nastel_no', 'created_at', 'updated_at', 'size_collection_id', 'nastel_user_id'], 'integer'],
            [['doc_number', 'nastel_party'], 'string', 'max' => 50],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvMusteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvMusteri::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['nastel_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['nastel_user_id' => 'id']],
            [['nastel_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['nastel_employee_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'nastel_party' => Yii::t('app', 'Nastel Party'),
            'model_list_id' => Yii::t('app', 'Model List ID'),
            'order_id' => Yii::t('app', 'Model List ID'),
            'order_item_id' => Yii::t('app', 'Model Ranglari'),
            'model_var_id' => Yii::t('app', 'Model Ranglari'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'model_name' => Yii::t('app', 'Model nomi'),
            'customer_id' => Yii::t('app', 'Buyurtmachi'),
            'add_info' => Yii::t('app', 'Add Info'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'nastel_user_id' => Yii::t('app', 'Nastelchi'),
            'nastel_employee_id' => Yii::t('app', 'Nastelchi'),
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
            $this->reg_date = date('Y-m-d', strtotime($this->reg_date));
            return true;
        } else {
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = date('d.m.Y', strtotime($this->reg_date));

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRollItems()
    {
        return $this->hasMany(BichuvGivenRollItems::className(), ['bichuv_given_roll_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNastelUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'nastel_user_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNastelEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'nastel_employee_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelDetails()
    {
        return $this->hasMany(BichuvNastelDetails::className(), ['bichuv_given_roll_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelRelProductions()
    {
        return $this->hasMany(ModelRelProduction::className(), ['bichuv_given_roll_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvAcceptedMatoFromProduction()
    {
        return $this->hasMany(BichuvAcceptedMatoFromProduction::className(), ['bichuv_given_roll_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDetailType()
    {
        return $this->hasOne(BichuvDetailTypes::className(), ['id' => 'bichuv_doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }

    public function getSizes()
    {
        $size_collection = SizeCollections::findOne($this->size_collection_id);
        $size = '';
        if(!empty($size_collection)){
            $size = $size_collection->getSizeList(true);
        }
        return $size;
    }

    /**
     * @param $party
     * @param int $t
     * @param $data
     * @return array
     * @throws \yii\db\Exception
     */
    static function getRemains($party, $t = 1, $data = null)
    {
        $existsMusteriID = "";
        $conditionDept = "";
        $conditionExists = "";

        if (!empty($data) && !empty($data['musteri'])) {
            $existsMusteriID = $data['musteri'];
        }
        if (!empty($data) && !empty($data['party'])) {
            $entityIds = join("','", $data['party']);
            if (!empty($entityIds)) {
                $conditionExists = " AND brib.party_no NOT IN ('{$entityIds}') ";
            }
        }
        $userId = Yii::$app->user->id;
        $dept = UsersHrDepartments::find()->where(['user_id' => $userId])->asArray()->one();
        if (!empty($dept)) {
            $conditionDept = " AND brib2.hr_department_id = {$dept['hr_departments_id']} ";
        }

        $sql = "select wmi.id as entity_id,
                       m.name,
                       rmt.name as mato,
                       tn.name as ne,
                       tt.name as thread,
                       tpf.name as pus_fine,
                       c.color_id,
                       ct.name as ctone,
                       c.pantone,
                       p.name as model,
                       wmi.en,
                       wmi.gramaj,
                       brib.roll_inventory as rulon_count,
                       brib.inventory as rulon_kg,
                       brib.party_no,
                       brib.musteri_party_no,
                       brib.model_orders_items_id as moii,
                       p.id as model_id
                from bichuv_rm_item_balance brib
                         left join wms_mato_info wmi on brib.entity_id = wmi.id
                         left join toquv_raw_materials trm on wmi.toquv_raw_materials_id = trm.id
                         left join raw_material_type rmt on trm.raw_material_type_id = rmt.id
                         left join toquv_raw_material_ip trmi on trm.id = trmi.toquv_raw_material_id
                         left join toquv_ne tn on trmi.ne_id = tn.id
                         left join toquv_thread tt on trmi.thread_id = tt.id
                         left join toquv_pus_fine tpf on wmi.pus_fine_id = tpf.id
                         left join color c on wmi.wms_color_id = c.id
                         left join color_tone ct on c.color_tone = ct.id
                         left join musteri m on brib.from_musteri = m.id
                         left join product p on brib.model_id = p.id
                WHERE brib.from_musteri = %s AND brib.roll_inventory > 0 AND brib.inventory > 0 AND brib.id IN (
                    select MAX(brib2.id) from bichuv_rm_item_balance brib2
                    where brib2.party_no = '%s' AND brib2.from_musteri = %s %s GROUP BY brib2.entity_id) %s
                GROUP BY brib.entity_id ORDER BY brib.id ASC;";

        $sql = sprintf($sql, $existsMusteriID, $party, $existsMusteriID, $conditionDept, $conditionExists);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }
    /**
     * @param bool $keyValue
     * @param bool $isView
     * @return array
     * @throws \yii\db\Exception
     */
    public function getRollItems($keyValue = false, $isView = false)
    {
        $sql = "select bgri.entity_id,
                       m2.name,
                       rmt.name as mato,
                       tn.name as ne,
                       tt.name as thread,
                       tpf.name as pus_fine,
                       c.color_id,
                       ct.name as ctone,
                       c.pantone,
                       p.name as model,
                       wmi.en,
                       wmi.gramaj,
                       bgri.roll_count as rulon_count,
                       bgri.quantity as rulon_kg,
                       bgri.party_no,
                       bgri.musteri_party_no,
                       bdt.name as detail,
                       bgri.id id,
                       bgri.required_count 
                from bichuv_given_roll_items bgri   
                         left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id  
                         left join product p on bgri.model_id = p.id
                         left join wms_mato_info wmi on bgri.entity_id = wmi.id
                         left join toquv_raw_materials trm on wmi.toquv_raw_materials_id = trm.id
                         left join raw_material_type rmt on trm.raw_material_type_id = rmt.id
                         left join toquv_raw_material_ip trmi on trm.id = trmi.toquv_raw_material_id
                         left join toquv_ne tn on trmi.ne_id = tn.id
                         left join toquv_thread tt on trmi.thread_id = tt.id
                         left join toquv_pus_fine tpf on wmi.pus_fine_id = tpf.id
                         left join color c on wmi.wms_color_id = c.id
                         left join color_tone ct on c.color_tone = ct.id                  
                         left join musteri m on bgr.musteri_id = m.id
                         left join musteri m2 on bgr.customer_id = m2.id  
                WHERE bgr.id = %d AND bgr.musteri_id = %d AND bgri.entity_type = 1 GROUP BY wmi.id, bdt.id;";

        $sql = sprintf($sql, $this->id, $this->musteri_id);

        $res = Yii::$app->db->createCommand($sql)->queryAll();
        if ($keyValue && !$isView) {
            return ArrayHelper::map($res, 'entity_id', function ($d) {
                if ($d['is_accessory'] == 1) {
                    return "{$d['mato']}-{$d['thread']}";
                }
                return "{$d['mato']}-{$d['ne']}-{$d['thread']}|{$d['pus_fine']}-({$d['ctone']} {$d['color_id']} {$d['pantone']})";
            });
        }
        return $res;
    }
    public function getRollList($keyValue = false)
    {
        $musteriId = $this->musteri_id;
        if($musteriId == Constants::$NillGranitID){
            $sql = "select bgri.entity_id,
                           m.name,
                           rm.name as mato,
                           nename.name as ne,
                           thr.name as thread,
                           pf.name as pus_fine,
                           c.color_id,
                           ct.name as ctone,
                           c.pantone,
                           p.name as model,
                           bmi.en,
                           bmi.gramaj,
                           bgri.roll_count as rulon_count,
                           bgri.quantity as rulon_kg,
                           bgri.party_no,
                           bgri.musteri_party_no,
                           bdt.name as detail,
                           bgri.id id,
                           bgri.required_count 
                    from bichuv_given_roll_items bgri
                             left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                             left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                             left join bichuv_processes bp on bdt.bichuv_process_id = bp.id
                             left join product p on bgri.model_id = p.id
                             left join bichuv_mato_info bmi on bgri.entity_id = bmi.id
                             left join raw_material rm on bmi.rm_id = rm.id
                             left join ne nename on nename.id = bmi.ne_id
                             left join pus_fine pf on pf.id = bmi.pus_fine_id
                             left join thread thr on thr.id = bmi.thread_id
                             left join color c on bmi.color_id = c.id
                             left join color_tone ct on c.color_tone = ct.id
                             left join musteri m on bgr.musteri_id = m.id
                    WHERE bgr.id = %d AND bgr.musteri_id = %d GROUP BY bgri.id,bgri.entity_id;";
        }else{
            $sql = "select bgri.entity_id,
                           m.name,
                           rm.name as mato,
                           nename.name as ne,
                           thr.name as thread,
                           pf.name as pus_fine,
                           c.color_id,
                           ct.name as ctone,
                           c.pantone,
                           p.name as model,
                           bmi.en,
                           bmi.gramaj,
                           bgri.roll_count as rulon_count,
                           bgri.quantity as rulon_kg,
                           bgri.party_no,
                           bgri.musteri_party_no,
                           bdt.name as detail,
                           bgri.required_count 
                    from bichuv_given_roll_items bgri
                             left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                             left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id  
                             left join bichuv_mato_info bmi on bgri.entity_id = bmi.id
                             left join product p on bgri.model_id = p.id
                             left join musteri m on bgr.musteri_id = m.id
                             left join raw_material rm on bmi.rm_id = rm.id
                             left join ne nename on nename.id = bmi.ne_id
                             left join pus_fine pf on pf.id = bmi.pus_fine_id
                             left join thread thr on thr.id = bmi.thread_id
                             left join color c on bmi.color_id = c.id
                             left join color_tone ct on c.color_tone = ct.id
                    WHERE bgr.id = %d AND bgr.musteri_id = %d GROUP BY bmi.id;";
        }
        $sql = sprintf($sql, $this->id, $this->musteri_id);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        if($keyValue){
            return ArrayHelper::map($res,'id', function($d){
                if($d['is_accessory'] != 1){
                    return "{$d['mato']}-{$d['thread']}";
                }
                return "<b>{$d['detail']}</b> - {$d['mato']}-{$d['ne']}-{$d['thread']}|{$d['pus_fine']}-({$d['ctone']} {$d['color_id']} {$d['pantone']})";
            });
        }
        return $res;
    }
    public static function getRollOne($id)
    {
        $sql = "select
                       bgri.entity_id,
                       bgri.id bgri_id, 
                       bgr.nastel_party nastel_no,
                       m.name,
                       rmt.name         as mato,
                       tn.name     as ne,
                       tt.name        as thread,
                       tpf.name         as pus_fine,
                       cp.id as color_id,
                       CONCAT(ml.name,':',ml.article)  as model,
                       wmi.en,
                       wmi.gramaj,
                       bgri.roll_count as rulon_count,
                       bgri.quantity   as rulon_kg,
                       bgri.party_no,
                       bgri.musteri_party_no,
                       bdt.name        as detail,
                       bdt.id          as detail_type_id,
                       bp.id           as process_id 
                from bichuv_given_roll_items bgri
                         left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                         left join bichuv_processes bp on bdt.bichuv_process_id = bp.id
                         left join model_orders_items moi on bgri.model_orders_items_id = moi.id
                         left join models_list ml on moi.models_list_id = ml.id   
                         left join wms_mato_info wmi on bgri.entity_id = wmi.id
                         left join toquv_raw_materials trm on wmi.toquv_raw_materials_id = trm.id
                         left join raw_material_type rmt on trm.raw_material_type_id = rmt.id
                         left join toquv_raw_material_ip trmi on trm.id = trmi.toquv_raw_material_id
                         left join toquv_ne tn on trmi.ne_id = tn.id
                         left join toquv_thread tt on trmi.thread_id = tt.id
                         left join toquv_pus_fine tpf on wmi.pus_fine_id = tpf.id
                         left join wms_color wc on wmi.wms_color_id = wc.id
                         left join color_pantone cp on wc.color_pantone_id = cp.id
                         left join musteri m on bgr.musteri_id = m.id
                WHERE bgri.id = %d GROUP BY bgri.entity_id, bdt.id;";
        $sql = sprintf($sql, $id);

        $res = Yii::$app->db->createCommand($sql)->queryOne();
        return $res;
    }
    public static function getNastelDetalItems($id)
    {
        $sql = "select
                       bgri.id nastel_id, 
                       bgr.nastel_party,
                       s.name size,
                       bndi.required_count,
                       bndi.required_weight 
                from bichuv_nastel_detail_items bndi
                        left join bichuv_given_roll_items bgri on bndi.bichuv_given_roll_items_id = bgri.id
                        left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                        left join size s on bndi.size_id = s.id
                WHERE bgri.id = %d;";
        $sql = sprintf($sql, $id);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }
    public function getDetails()
    {
        $sql = "(select CONCAT_WS(' ',COALESCE(rm.name),COALESCE(nename.name),COALESCE(thr.name),COALESCE(pf.name), COALESCE(ct.name),COALESCE(c.color_id),COALESCE(c.pantone)) as name,
                    bdt.name as detail,
                    s.name as size_name,
                    bndi.required_count,
                    bdt.id as detail_id
             from bichuv_nastel_detail_items bndi
                      left join size s on s.id = bndi.size_id  
                      left join bichuv_given_roll_items bgri on bgri.id = bndi.bichuv_given_roll_items_id
                      left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                      left join bichuv_mato_info bmi on bgri.entity_id = bmi.id
                      left join raw_material rm on bmi.rm_id = rm.id
                      left join ne nename on nename.id = bmi.ne_id
                      left join pus_fine pf on pf.id = bmi.pus_fine_id
                      left join thread thr on thr.id = bmi.thread_id
                      left join color c on bmi.color_id = c.id
                      left join color_tone ct on c.color_tone = ct.id
             where bgri.entity_type = 1 AND bgri.bichuv_given_roll_id = %d)
            UNION
            (
                select CONCAT_WS(' ',COALESCE(acs.name),COALESCE(acs.sku),bap.name),
                       b.name as detail,
                       s2.name as size_name,
                       bndi2.required_count,
                       b.id as detail_id
                from bichuv_nastel_detail_items bndi2
                         left join size s2 on s2.id = bndi2.size_id
                         left join bichuv_given_roll_items bgri2 on bgri2.id = bndi2.bichuv_given_roll_items_id
                         left join bichuv_detail_types b on bgri2.bichuv_detail_type_id = b.id
                         left join bichuv_acs acs on bgri2.entity_id = acs.id
                         left join bichuv_acs_property bap on acs.property_id = bap.id
                where bgri2.entity_type = 2 AND bgri2.bichuv_given_roll_id = %d
            );";
        $sql = sprintf($sql, $this->id, $this->id);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function getNastelSearch($query='',$arr=null,$table_id=null,$token=null)
    {
        $q = '';
        if(!empty($query)){
            $q = " AND (bgr.nastel_party LIKE '%{$query}%' OR rm.name LIKE '%{$query}%' OR m.name LIKE '%{$query}%')";
        }
        $list = ($arr)?" AND bgri.id NOT IN ({$arr})":"";
        $table = ($table_id && $token)?" AND (bdt.token = '{$token}') AND bgri.id not in (select bnp.bichuv_given_roll_items_id from bichuv_nastel_processes bnp
                                                              where bnp.bichuv_nastel_stol_id = {$table_id} and bnp.bichuv_given_roll_items_id is not null)":"";
        $tk = ($token)?" AND (bdt.token = '{$token}')":"";
        $sql = "select
                       bgri.entity_id,
                       bgri.id bgri_id, 
                       m.name,
                       rm.name         as mato,
                       nename.name     as ne,
                       thr.name        as thread,
                       pf.name         as pus_fine,
                       c.color_id,
                       ct.name         as ctone,
                       c.pantone,
                       p.name          as model,
                       bmi.en,
                       bmi.gramaj,
                       bgri.roll_count as rulon_count,
                       bgri.quantity   as rulon_kg,
                       bgri.party_no,
                       bgri.musteri_party_no,
                       bgr.nastel_party,
                       bdt.name        as detail,
                       bdt.id          as detail_type_id,
                       bp.id           as process_id 
                from bichuv_given_roll_items bgri
                         left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                         left join bichuv_processes bp on bdt.bichuv_process_id = bp.id
                         left join product p on bgri.model_id = p.id
                         left join bichuv_mato_info bmi on bgri.entity_id = bmi.id
                         left join raw_material rm on bmi.rm_id = rm.id
                         left join ne nename on nename.id = bmi.ne_id
                         left join pus_fine pf on pf.id = bmi.pus_fine_id
                         left join thread thr on thr.id = bmi.thread_id
                         left join color c on bmi.color_id = c.id
                         left join color_tone ct on c.color_tone = ct.id
                         left join musteri m on bgr.musteri_id = m.id
                WHERE bgri.status > 0 %s %s %s GROUP BY bgri.id,bdt.id limit 50;";
        $sql = sprintf($sql,$q,$list,$table);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }

    public function getDIEntityIds()
    {
        $items = $this->bichuvGivenRollItems;
        $ids = "";
        if($items){
            $c = count($items);
            foreach ($items as $key => $item){
                $ids .= $item->entity_id;
                if(($key+1) != $c){
                    $ids .=",";
                }
            }
        }
        return $ids;
    }

    public function getLastNastelNo($type = 'year')
    {
        $results = [];
        if($type == 'year'){
            //Nastel nomeri har yili yangilanadi
            $currYearStartDate = date('Y-01-01');
            $currentDate = date('Y-m-d');
            $m = date('m');
            $y = date('y');
            if($currYearStartDate == $currentDate){
                $results['party'] = "{$m}{$y}-1";
                $results['number'] = 1;
            }else{
                $last = self::find()->where(['between', 'reg_date', date('Y-01-01'), date('Y-12-31')])->asArray()->orderBy(['id' => SORT_DESC])->one();
                if(!empty($last)){
                    $results['number'] = $last['nastel_no'] + 1;
                    $results['party'] = "{$m}{$y}-{$results['number']}";
                }else{
                    $results['party'] = "{$m}{$y}-1";
                    $results['number'] = 1;
                }
            }
        }elseif($type == 'month'){
            //Nastel nomeri har oyda yangilanadi
            $firstDayCurrentMonth = date('Y-m-01');
            $m = date('m');
            $y = date('y');
            $currentDate = date('Y-m-d');
            if ($firstDayCurrentMonth == $currentDate) {
                $results['party'] = "{$m}{$y}-1";
                $results['number'] = 1;
            } else {
                $last = self::find()->where(['between', 'reg_date', date('Y-m-01'), date('Y-m-31')])->asArray()->orderBy(['id' => SORT_DESC])->one();
                if (!empty($last)) {
                    $results['number'] = $last['nastel_no'] + 1;
                    $results['party'] = "{$m}{$y}-{$results['number']}";
                } else {
                    $results['party'] = "{$m}{$y}-1";
                    $results['number'] = 1;
                }
            }
        }

        return $results;
    }

    public function getMusteries($token = null, $except = null)
    {
        if ($token) {
            $list = Musteri::find()->select(['id', 'name'])->where(['token' => $token])->asArray()->orderBy(['name' => SORT_ASC])->all();
        } elseif ($except) {
            $list = Musteri::find()->select(['id', 'name'])
                ->where('token IS NULL OR token <> "SAMO"')->asArray()->orderBy(['name' => SORT_ASC])->all();
        } else {
            $list = Musteri::find()->select(['id', 'name'])->asArray()->orderBy(['name' => SORT_ASC])->all();
        }
        return ArrayHelper::map($list, 'id', 'name');
    }

    /**
     * @return string|null
     */
    public function getModelName()
    {
        $models = $this->getBichuvGivenRollItems()->with(['productModel'])->asArray()->all();
        $result = [];
        if (!empty($models)) {
            foreach ($models as $item) {
                if (!empty($item['productModel'])) {
                    $result[$item['productModel']['id']] = $item['productModel']['name'];
                }
            }
        }
        if (!empty($result)) {
            return join(', ', $result);
        }
        return null;
    }

    /**
     * @return array
     */
    public function getModelLists()
    {
        $models = Product::find()->asArray()->orderBy(['name' => SORT_ASC])->all();
        return ArrayHelper::map($models, 'id', 'name');
    }

    public function getPartyFields($fieldName = 'party_no')
    {
        $results = [];
        $items = $this->getBichuvGivenRollItems()->asArray()->all();
        if (!empty($items)) {
            foreach ($items as $item) {
                if (!empty($item[$fieldName])) {
                    $results[$item[$fieldName]] = $item[$fieldName];
                }
            }
        }
        if (!empty($results)) {
            return join(',', $results);
        }
        return null;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function checkRemainRm()
    {
        $items = $this->getBichuvGivenRollItems()->asArray()->all();
        $conditions = "";
        $parties = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                if (!empty($item['party_no'])) {
                    $parties[$item['party_no']] = $item['party_no'];
                }
            }
        }

        if (!empty($parties)) {
            $p = join(',', $parties);
            if ($p[strlen($p) - 1] === ',') {
                $p = substr_replace($p, '', -1, 1);
            }
            $conditions = "AND brib2.party_no IN ($p)";
        }
        $userId = Yii::$app->user->id;
        $dept = ToquvUserDepartment::find()->where(['user_id' => $userId])->asArray()->one();
        $conditionDept = "";
        if (!empty($dept)) {
            $conditionDept = " AND brib2.department_id = {$dept['department_id']} ";
        }
        $sql = "select brib.inventory
                from bichuv_rm_item_balance brib
                where brib.id IN (select max(brib2.id) from bichuv_rm_item_balance brib2 
                where brib2.from_musteri = %d  %s %s GROUP BY brib2.entity_id) AND brib.inventory > 2;";
        $sql = sprintf($sql, $this->musteri_id, $conditions, $conditionDept);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * @return string|null
     * @throws \yii\db\Exception
     */
    public function getEntityList()
    {
        $entityIds = $this->getBichuvGivenRollItems()->select(['entity_id'])->asArray()->all();
        if (!empty($entityIds)) {
            $entityIds = ArrayHelper::getColumn($entityIds, 'entity_id');
            $out = $this->getMatoName(null, true, $entityIds);
            if (!empty($out)) {
                return $out;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getSizeCollectionList($option = false)
    {
        $sc = SizeCollections::find()->select(['id', 'name'])->all();
        $sc_option = [];
        if ($option) {
            if ($sc) {
                foreach ($sc as $item) {
                    $sc_option[$item['id']] = [
                        'data-size-list' => $item->getSizeList(false,true)
                    ];
                }
            }
            return $sc_option;
        }
        return ArrayHelper::map($sc, 'id', 'name');
    }

    public function getCounter($acs = false)
    {
        if ($acs) {
            $count = $this->_count_acs;
            $this->_count_acs++;
            return $count;
        } else {
            $count = $this->_count;
            $this->_count++;
            return $count;
        }
    }

    /**
     * @param bool $keyVal
     * @param string $groupBy
     * @param null $orderId
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getOrderModelLists($keyVal = false, $groupBy = 'model_id', $orderId = null){
        $orderWhere = ($orderId)?" AND mo.id = {$orderId}":"";
        $sql = "select  mo.id as order_id,
                        m.name as musteri,
                        mo.doc_number,
                        mo.reg_date,
                        ml.id as model_id, 
                        ml.article,
                        m.id as musteri_id,
                        ml.name
                from models_list ml
                inner join model_orders_items mri on ml.id = mri.models_list_id
                inner join model_orders mo on mri.model_orders_id = mo.id 
                left join musteri m on mo.musteri_id = m.id
                where mo.status > 2 AND mo.status < 10 %s
                GROUP BY mo.id,ml.id;";
        $sql = sprintf($sql,$orderWhere);
        $models = Yii::$app->db->createCommand($sql)->queryAll();
        if($keyVal){
            return $models;
        }
        $out = [];
        foreach ($models as $model){
            $name = ($orderId)?"{$model['article']}-({$model['doc_number']}-{$model['musteri']})":"{$model['doc_number']}-{$model['musteri']}";
            $out['data'][$model[$groupBy]] = $name;
            $out['dataAttr'][$model[$groupBy]] = [
                'data-order-id' => $model['order_id'],
                'data-musteri-id' => $model['musteri_id'],
                'data-price' => $model['price'],
                'data-pb-id' => $model['pb_id'],
                'data-model-id' => $model['model_id']
            ];
        }
        return $out;
    }

    public static function getOrderItemList($modelId, $orderId, $keyVal = false, $withAttr = false){
        $sql = "select mv.id  as model_var_id,
                           moi.id as order_item_id,
                           mo.id as order_id,
                           IF(cp.code!=''&&cp.code IS NOT NULL,cp.code,c.color_id) code,
                           mv.name,
                           moi.price,
                           moi.pb_id,
                           1 as type,
                           null as model_var_part
                    from model_orders mo
                             left join model_orders_items moi on mo.id = moi.model_orders_id
                             left join models_list ml on moi.models_list_id = ml.id
                             left join models_variations mv on mv.id = moi.model_var_id
                             left join musteri m on mo.musteri_id = m.id
                             left join color_pantone cp on mv.color_pantone_id = cp.id
                             left join color c on mv.boyoqhona_color_id = c.id
                    where mo.id = %d
                      AND ml.id = %d
                    GROUP BY moi.id;";
        $sql = sprintf($sql,$orderId, $modelId);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        if($keyVal){
            if($withAttr){
                $out = [];
                $out['data'] = [];
                $out['dataAttr'] = [];
                foreach ($results as $result){
                    $out['data'][$result['model_var_id']] = $result['code'].' ('.$result['name'].')';
                    $out['dataAttr'][$result['model_var_id']] = [
                        'data-order-item-id' => $result['order_item_id'],
                        'data-price' => $result['price'],
                        'data-pb-id' => $result['pb_id'],
                        'data-order-id' => $result['order_id'],
                        'data-type' => $result['type'],
                        'data-model-var-part' => $result['model_var_part'],
                    ];
                }
                return $out;
            }else{
                return ArrayHelper::map($results,'model_var_id', function($m){
                    return $m['code']." (".$m['name'].")";
                });
            }

        }
        return $results;
    }

    /**
     * @param $params
     * @param bool $withAttr
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getModelVarParts($params, $withAttr = false){
        $ids = join(',', $params);
        $sql = "select mvp.name as colorName, 
                       cp.code, 
                       bpp.name as partName,
                       mvp.id,
                       mv.id as model_var_id
                from model_variation_parts mvp
                left join models_variations mv on mvp.model_var_id = mv.id
                left join color_pantone cp on mv.color_pantone_id = cp.id
                left join base_pattern_part bpp on mvp.base_pattern_part_id = bpp.id
                where mvp.model_var_id IN (%s);";
        $sql = sprintf($sql, $ids);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        if($withAttr){
            $out = [];
            if(!empty($results)){
                foreach ($results as $result){
                    $out['data'][$result['id']] = "{$result['partName']} {$result['code']} {$result['colorName']}";
                    $out['dataAttr'][$result['id']] = [
                        'data-model-var-id' => $result['model_var_id'],
                        'class' => "model-var-part-option_{$result['model_var_id']}"
                    ];
                }
            }
            return $out;
        }
        return $results;
    }

    public function getModelListInfo()
    {
        $sql = "select  mv.id as model_var_id, 
                        ml.id as model_id, 
                        ml.article, 
                        # mv.name, 
                        # cp.code,
                        ml.name as mname,
                        IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as name,
                        IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as code
                from bichuv_given_rolls bgr
                left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                left join models_list ml on mrp.models_list_id = ml.id
                left join models_variations mv on mv.id = mrp.model_variation_id
                left join wms_color wc on mv.wms_color_id = wc.id
                left join color_pantone cp on wc.color_pantone_id = cp.id
                where bgr.id = '%d' GROUP BY mv.id;";
        $sql = sprintf($sql, $this->id);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $out = [];
        $out['model_var'] = null;
        $out['model'] = null;
        $out['model_var_code'] = null;
        $out['model_id'] = null;
        $out['model_var_id'] = null;
        $out['model_name'] = null;
        if (!empty($results)) {
            foreach ($results as $item) {
                $out['model_id'] = $item['model_id'];
                $out['model_var_id'] = $item['model_var_id'];
                $out['model'] = $item['article'];
                $out['model_name'] = $item['mname'];
                $code = $item['code'];
                if (empty($out['model_var'])) {
                    $out['model_var'] = $code . " (" . $item['name'] . ")";
                    $out['model_var_code'] = "<p>" . $code . " (" . $item['name'] . ")" . "</p>";
                } else {
                    $out['model_var_code'] .= "<p>" . $code . " (" . $item['name'] . ")" . "</p>";
                    $out['model_var'] .= ", " . $code . " (" . $item['name'] . ")";
                }
            }
        }
        return $out;
    }

    public function getReqCount()
    {
        $detail_type = BichuvDetailTypes::findOne(['token'=>'MAIN']);
        $child = BichuvGivenRollItems::find()->where(['bichuv_given_roll_id'=>$this->id]);
        if($detail_type){
            $child = $child->andWhere(['bichuv_detail_type_id'=>$detail_type['id']]);
        }
        $child = $child->orderBy(['required_count'=>SORT_DESC])->limit(1)->one();
        return $child;
    }
}
