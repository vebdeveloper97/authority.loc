<?php

namespace app\modules\tikuv\models;

use app\models\Constants;
use app\models\Users;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\Musteri;
use app\modules\bichuv\models\ModelRelProduction;
use app\modules\bichuv\models\Product;
use app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use app\modules\mobile\models\MobileProcess;
use app\modules\mobile\models\MobileProcessProduction;
use app\modules\mobile\models\MobileTables;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\wms\models\WmsDepartmentArea;
use app\modules\wms\models\WmsDocument;
use app\modules\wms\models\WmsDocumentItems;
use app\modules\wms\models\WmsItemCategory;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Request;

/**
 * This is the model class for table "tikuv_doc".
 *
 * @property int $id
 * @property int $document_type
 * @property string $doc_number
 * @property string $party_no
 * @property int $party_count
 * @property string $reg_date
 * @property int $musteri_id
 * @property string $musteri_responsible
 * @property int $from_department
 * @property int $from_employee
 * @property int $to_department
 * @property int $to_employee
 * @property int $from_hr_department
 * @property int $from_hr_employee
 * @property int $to_hr_department
 * @property int $to_hr_employee
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $work_weight
 * @property int $mobile_table_id
 * @property int $mobile_process_id
 * @property int $parent_id
 *
 * @property ToquvDepartments $fromDepartment
 * @property Users $fromEmployee
 * @property Musteri $musteri
 * @property ToquvDepartments $toDepartment
 * @property Users $toEmployee
 * @property TikuvDocItems[] $tikuvDocItems
 * @property TikuvRmItems[] $tikuvRmItems
 * @property ModelRelDoc[] $modelRelDoc
 * @property MobileTables $mobileTable
 * @property int $type [smallint(1)]
 * @property int $is_change_model [smallint(1)]
 * @property string $change_note
 * @property string $combined_nastel [varchar(255)]
 * @property mixed $productModels
 * @property array $modelListInfo
 * @property array|mixed $slugLabel
 * @property array $sliceItems
 * @property mixed $modelVarList
 * @property array $modelListPartInfo
 * @property array $slicePartItems
 * @property array $belongToModelList
 * @property mixed $modelList
 * @property mixed $orderItemModelList
 * @property MobileProcess $mobileProcess
 * @property int $is_combined [smallint(1)]
 * @property int $is_service [smallint(6)]
 * @property int $from_musteri [bigint(20)]
 * @property int $to_musteri [bigint(20)]
 * @property ActiveQuery $fromMusteri
 * @property ActiveQuery $toMusteri
 * @property int $usluga_doc_id [int(11)]
 * @property TikuvDoc $parent
 * @property TikuvDoc[] $children
 */
class TikuvDoc extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    const DOC_TYPE_INCOMING = 1;
    const DOC_TYPE_MOVING = 2;
    const DOC_TYPE_SELLING = 3;
    const DOC_TYPE_RETURN = 4;
    const DOC_TYPE_OUTGOING = 5;
    //qabul acs uchun 7
    const DOC_TYPE_ACCEPTED = 7;
    const DOC_TYPE_INSIDE = 8;
    const DOC_TYPE_REPAIR = 9;


    const DOC_TYPE_INCOMING_LABEL = 'kirim_acs';
    const DOC_TYPE_MOVING_LABEL = 'kochirish_acs';
    const DOC_TYPE_SELLING_LABEL = 'sotish_acs';
    const DOC_TYPE_RETURN_LABEL = 'qaytarish_acs';
    const DOC_TYPE_OUTGOING_LABEL = 'chiqim_acs';

    //Mato
    const DOC_TYPE_INCOMING_MATO_LABEL = 'kirim_mato';
    const DOC_TYPE_MOVING_MATO_LABEL = 'kochirish_mato';
    const DOC_TYPE_REPAIR_MATO_LABEL = 'tamir_mato';

    //Kesilgan(Nastel size bilan birga)

    const DOC_TYPE_MOVING_SLICE_LABEL = "kochirish_kesim";
    const DOC_TYPE_ACCEPTED_SLICE_LABEL = "qabul_kesim";
    //qabul acs uchun
    const DOC_TYPE_ACCEPTED_LABEL = 'qabul_acs';
    const DOC_TYPE_ACCEPTED_MATO_LABEL = 'qabul_mato';

    //model types
    const MODEL_TYPE_SLICE = 1;
    const MODEL_TYPE_ACCESSORY = 2;
    const MODEL_TYPE_RM = 3;

    public $model_list_id;
    public $model_var_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_type','type','is_combined', 'party_count','is_change_model', 'musteri_id', 'from_department', 'from_employee', 'to_department', 'to_employee', 'status', 'created_by', 'created_at', 'updated_at', 'work_weight', 'is_service', 'from_musteri', 'to_musteri', 'usluga_doc_id'], 'integer'],
            [['reg_date'], 'safe'],
            [['add_info','change_note','combined_nastel'], 'string'],
            [['doc_number', 'party_no'], 'string', 'max' => 25],
            [['musteri_responsible'], 'string', 'max' => 255],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['from_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['from_employee' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['to_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['to_employee' => 'id']],
            [['from_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['from_hr_department' => 'id']],
            [['from_hr_employee'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::class, 'targetAttribute' => ['from_hr_employee' => 'id']],
            [['to_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['to_hr_department' => 'id']],
            [['to_hr_employee'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::class, 'targetAttribute' => ['to_hr_employee' => 'id']],
            [['mobile_table_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileTables::class, 'targetAttribute' => ['mobile_table_id' => 'id']],
            [['mobile_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileProcess::class, 'targetAttribute' => ['mobile_process_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvDoc::class, 'targetAttribute' => ['parent_id' => 'id']],
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
            'doc_number' => Yii::t('app', 'Doc Number'),
            'party_no' => Yii::t('app', 'Party No'),
            'party_count' => Yii::t('app', 'Party Count'),
            'is_change_model' => Yii::t('app', 'Is Change Model'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'musteri_responsible' => Yii::t('app', 'Musteri Responsible'),
            'from_department' => Yii::t('app', 'From Department'),
            'from_employee' => Yii::t('app', 'From Employee'),
            'to_department' => Yii::t('app', 'To Department'),
            'to_employee' => Yii::t('app', 'To Employee'),
            'from_hr_department' => Yii::t('app', 'From department'),
            'from_hr_employee' => Yii::t('app', 'From Employee'),
            'to_hr_department' => Yii::t('app', 'To department'),
            'to_hr_employee' => Yii::t('app', 'To Employee'),
            'add_info' => Yii::t('app', 'Add Info'),
            'change_note' => Yii::t('app', "O'zgarish sababi"),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'work_weight' => Yii::t('app', 'Work Weight'),
            'mobile_table_id' => Yii::t('app', 'Table'),
            'mobile_process_id' => Yii::t('app', 'Process'),
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
            self::DOC_TYPE_ACCEPTED_SLICE_LABEL => Yii::t('app', "Qabul Kesim"),
            self::DOC_TYPE_REPAIR_MATO_LABEL => Yii::t('app', "Ta'mir Mato"),
        ];
        if ($key)
            return $result[$key];
        return $result;
    }

    /**
     * @return array|mixed
     */
    public function getSlugLabel()
    {
        $slug = Yii::$app->request->get('slug');
        if (!empty($slug)) {
            return self::getDocTypeBySlug($slug);
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getFromHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'from_hr_department']);
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
    public function getFromHrEmployee()
    {
        return $this->hasOne(HrEmployee::class, ['id' => 'from_hr_employee']);
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
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'to_musteri']);
    }
    /**
     * @return ActiveQuery
     */
    public function getFromMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'from_musteri']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'to_department']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'to_hr_department']);
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
    public function getTikuvDocItems()
    {
        return $this->hasMany(TikuvDocItems::className(), ['tikuv_doc_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelRelDoc()
    {
        return $this->hasMany(ModelRelDoc::className(), ['tikuv_doc_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvRmItems()
    {
        return $this->hasMany(TikuvRmItems::className(), ['tikuv_doc_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMobileTable()
    {
        return $this->hasOne(MobileTables::class, ['id' => 'mobile_table_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMobileProcess()
    {
        return $this->hasOne(MobileProcess::class, ['id' => 'mobile_process_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(TikuvDoc::class, ['id' => 'parent_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(TikuvDoc::class, ['parent_id' => 'id']);
    }

    public function getProductModels()
    {
        $m = Product::find()->asArray()->all();
        return ArrayHelper::map($m, 'id', 'name');
    }

    public function getModelList(){
        $m = ModelsList::find()->asArray()->all();
        return ArrayHelper::map($m,'id', function($m){
            return $m['article']."-".$m['name'];
        });
    }

    /**
     * @param string $type
     * @return string|null
     */
    public function getProductModelList($type = 'item')
    {
        if ($type == 'slice') {
            $pm = $this->getTikuvDocItems()->with(['productModel'])->asArray()->all();
        } else {
            $pm = $this->getTikuvRmItems()->with(['productModel'])->asArray()->all();
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

    public function getModelVarList()
    {
        $mv = ModelsVariations::find()
            ->select(['models_variations.id','color_pantone.code', 'models_variations.name'])
            ->leftJoin('color_pantone','color_pantone.id = models_variations.color_pantone_id')
            ->where('color_pantone.code IS NOT NULL')->asArray()->all();
        return ArrayHelper::map($mv, 'id', function($m){
            return $m['code']." (".$m['name'].")";
        });
    }

    /***
     * @param string $type
     * @return string|null
     */
    public function getNastelParty($type = 'slice')
    {
        $result = null;
        switch ($type){
            case 'slice':
                $items = $this->getTikuvDocItems()->asArray()->all();
                if (!empty($items)) {
                    $temp = [];
                    foreach ($items as $item){
                        $temp[$item['nastel_party_no']] = $item['nastel_party_no'];
                    }
                    if(!empty($temp)){
                        $result = join(', ', $temp);
                    }
                }
                break;
            case 'item':
                $items = $this->getBichuvDocItems()->asArray()->all();
                if (!empty($items)) {
                    $temp = [];
                    foreach ($items as $item){
                        $temp[$item['nastel_no']] = $item['nastel_no'];
                    }
                    if(!empty($temp)){
                        $result = join(', ', $temp);
                    }
                }
                break;
        }
        return $result;
    }

    /**
     * @param string $type
     * @return int|string
     */
    public function getWorkCount($type = 'slice')
    {
        $result = 0;
        switch ($type){
            case 'slice':
                $items = $this->getTikuvDocItems()->asArray()->all();
                if (!empty($items)) {
                    foreach ($items as $item) {
                        $result += $item['quantity'];
                    }
                }
                $result = number_format($result, 0, '.', ' ');
                break;
            case 'acs':
                $items = $this->getTikuvRmItems()->where(['is_accessory' => 2])->asArray()->all();
                if (!empty($items)) {
                    foreach ($items as $item) {
                        $result += $item['quantity'];
                    }
                }
                $result = number_format($result, 0, '.', ' ');
                break;
            case 'rm':
                $items = $this->getTikuvRmItems()->where(['is_accessory' => 1])->asArray()->all();
                if (!empty($items)) {
                    foreach ($items as $item) {
                        $result += $item['quantity'];
                    }
                }
                $result = number_format($result, 3, '.', ' ');
                break;

        }

        return $result;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getSliceItems(){
        $sql = "select tdi.id,
                       s.name as size,
                       tdi.quantity,
                       p.name as model,
                       tdi.nastel_party_no,
                       tdi.work_weight 
                       from tikuv_doc_items tdi
                left join size s on tdi.size_id = s.id
                left join product p on tdi.model_id = p.id
                where tdi.tikuv_doc_id = :docId AND tdi.is_combined = 1;";
        return Yii::$app->db->createCommand($sql)->bindValue('docId',$this->id)->queryAll();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getSlicePartItems(){
        $sql = "select tdi.id,
                       tdi.nastel_party_no,
                       s.name as size, 
                       ml.article,
                       tdi.work_weight,
                       tdi.quantity, 
                       CONCAT(bpp.name,' ',cp.code,' ', cp.name_ru) as model_var
                from tikuv_doc td
                left join tikuv_doc_items tdi on td.id = tdi.tikuv_doc_id
                left join size s on tdi.size_id = s.id       
                left join bichuv_given_rolls bgr on bgr.nastel_party = tdi.nastel_party_no
                left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                left join models_list ml on mrp.models_list_id = ml.id
                left join models_variations mv on mv.id = mrp.model_variation_id
                left join model_variation_parts mvp on mrp.model_var_part_id = mvp.id
                left join color_pantone cp on mvp.color_pantone_id = cp.id
                left join base_pattern_part bpp on mvp.base_pattern_part_id = bpp.id
                where bgr.id IN (
                    select mrp2.bichuv_given_roll_id from tikuv_doc_items tdi
                    left join tikuv_doc td on tdi.tikuv_doc_id = td.id
                    left join bichuv_given_rolls bgr on bgr.nastel_party = tdi.nastel_party_no
                    left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                    left join model_rel_production mrp2 on mrp2.model_variation_id = mrp.model_variation_id
                    left join models_list ml on mrp.models_list_id = ml.id
                    where td.id = 17 AND mrp2.type = 2 AND  mrp2.models_list_id = mrp.models_list_id GROUP BY mrp2.id
                    );";
        return Yii::$app->db->createCommand($sql)->bindValue('docId', $this->id)->queryAll();
    }

    /**
     * @param int $type
     * @return array
     * @throws Exception
     */
    public function getAdditionItems($type = 1){

        if($type == 1){
            $sql = "select rm.name as mato,
                        nename.name as ne,
                        pf.name as pus_fine,
                        thr.name as thread,
                        c.color_id,
                        c.pantone,
                        ct.name as ctone,
                        bmi.gramaj,
                        bmi.en,
                        tri.nastel_no,
                        tri.quantity,
                        tri.roll_count
            from tikuv_rm_items tri
                        left join bichuv_mato_info bmi on bmi.id = tri.entity_id
                        left join raw_material rm on bmi.rm_id = rm.id
                        left join ne nename on nename.id = bmi.ne_id
                        left join pus_fine pf on pf.id = bmi.pus_fine_id
                        left join thread thr on thr.id = bmi.thread_id
                        left join color c on bmi.color_id = c.id
                        left join color_tone ct on c.color_tone = ct.id
            where tri.is_accessory = :type AND tri.nastel_no IN (select tdi.nastel_party_no from tikuv_doc_items tdi where tdi.tikuv_doc_id = :docId);";
        }else{
            $sql = "select acs.sku,
                       acs.name as name,
                       bap.name as property,
                       tri.quantity,
                       tri.nastel_no
            from tikuv_rm_items tri
                     left join bichuv_acs acs on acs.id = tri.entity_id
                     left join bichuv_acs_property bap on acs.property_id = bap.id
            where tri.is_accessory = :type AND tri.nastel_no IN (select tdi.nastel_party_no from tikuv_doc_items tdi where tdi.tikuv_doc_id = :docId);";
        }

        return Yii::$app->db->createCommand($sql)->bindValues(['type' => $type, 'docId' => $this->id])->queryAll();

    }

    /**
     * @return array
     * @throws Exception
     */
    public function getBelongToModelList()
    {
        $sql = "select ml.id as model_id, 
                       ml.name, 
                       ml.article, 
                       mv.name as color,
                       mv.id as model_var_id, 
                       mv.code, 
                       tk.name as konveyer,
                       mv.color_pantone_id,
                       cp.name as pantone,
                       cp.code,
                       mrp.price,
                       mrp.pb_id,
                       mrp.order_id,
                       mo.doc_number,
                       mo.sum_item_qty qty,
                       m.name musteri,
                       tdi.nastel_party_no nastel_no, 
                       mrp.order_item_id 
            from tikuv_doc_items tdi
         inner join bichuv_given_rolls bgr on bgr.nastel_party = tdi.nastel_party_no
         left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
         left join tikuv_konveyer tk on tkbgr.tikuv_konveyer_id = tk.id
         left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
         left join model_orders mo ON mrp.order_id = mo.id
         left join musteri m ON mo.musteri_id = m.id
         left join models_list ml on mrp.models_list_id = ml.id
         inner join models_variations mv on mrp.model_variation_id = mv.id
         left join color_pantone cp on mv.color_pantone_id = cp.id
         where tdi.tikuv_doc_id = :id  AND mrp.is_accepted = 1 GROUP BY mrp.model_variation_id, ml.id, tdi.tikuv_doc_id,tdi.nastel_party_no;";

        $res = Yii::$app->db->createCommand($sql)->bindValue('id',$this->id)->queryAll();
        return $res;
    }

    public static function getOrderItemModelList($order=null,$order_id=null,$list=false,$option=false){
        $sql = "select %s
                       from models_list ml
                inner join model_orders_items moi on ml.id = moi.models_list_id
                inner join model_orders mo on moi.model_orders_id = mo.id 
                %s
                where mo.status > 2 AND moi.id IS NOT NULL %s GROUP BY %s LIMIT 10000;";
        if($order){
            $select = 'mo.id,mo.doc_number,m.name,mo.sum_item_qty qty';
            $leftJoin = 'left join musteri m ON m.id = mo.musteri_id';
            $where = '';
            $groupBy = "mo.id";
        }else{
            $select = "ml.article,
                       mv.id as model_var_id,
                       moi.id moi_id, 
                       ml.name model_name,
                       cp.code,
                       cp.id as color_pantone_id, 
                       cp.name as pantone,
                       mv.name as model_var,
                       mo.id as order_id,
                       moi.price,
                       moi.pb_id,
                       ml.article as model_no,
                       ml.id as model_id";
            $leftJoin = 'left join models_variations mv on mv.id = moi.model_var_id
                        left join color_pantone cp on mv.color_pantone_id = cp.id';
            $where = "AND mo.id = {$order_id}";
            $groupBy = "moi.id";
        }
        $sql = sprintf($sql,$select,$leftJoin,$where,$groupBy);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        if($list){
            return $res;
        }
        if($order){
            return ArrayHelper::map($res,'id',function($m){
                return $m['name']."-<b>".$m['doc_number'].'</b>('.$m['qty'].')';
            });
        }
        if($option){
            return ArrayHelper::map($res,'moi_id',function($m){
                return [
                    'data' => [
                        'pantone-id' => $m['color_pantone_id'],
                        'price' => $m['price'],
                        'pb-id' => $m['pb_id'],
                        'model-no' => $m['model_no'],
                        'model-name' => $m['model_name'],
                        'model-id' => $m['model_id']
                    ]
                ];
            });
        }
        return ArrayHelper::map($res,'moi_id',function($m){
           return $m['article']."-".$m['model_name']. " (".$m['code'].")";
        });

    }

    public function getModelListInfo()
    {
        $mrd = ModelRelDoc::find()->where(['tikuv_doc_id' => $this->id])->exists();
        if($mrd){
            $sql = "select  mv.id as model_var_id, 
                            ml.id as model_id, 
                            ml.article, 
                            mv.name, 
                            cp.code,
                            mo.doc_number,
                            NULL as part,
                            NULL as type
                from model_rel_doc mrp
                left join tikuv_doc td on td.id = mrp.tikuv_doc_id
                left join tikuv_doc_items tdi on td.id = tdi.tikuv_doc_id   
                left join models_list ml on mrp.model_list_id = ml.id
                left join models_variations mv on mv.id = mrp.model_var_id
                left join color_pantone cp on mv.color_pantone_id = cp.id
                left join model_orders as mo on mrp.order_id = mo.id
                where tdi.nastel_party_no = '%s' GROUP BY mv.id, tdi.tikuv_doc_id;";
        }else{
            $sql = "select  mv.id as model_var_id, 
                        ml.id as model_id, 
                        ml.article, 
                        # mv.name, 
                        # cp.code,
                        mo.doc_number,
                        bpp.name as part,
                        mrp.type,
                        IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as code,
                        IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as name,
                        mrp.order_item_id,
                        mo.musteri_id
                from bichuv_given_rolls bgr
                left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                 left join model_orders as mo on mrp.order_id = mo.id   
                left join models_list ml on mrp.models_list_id = ml.id
                left join models_variations mv on mv.id = mrp.model_variation_id
                left join model_variation_parts mvp on mrp.model_var_part_id = mvp.id
                left join base_pattern_part bpp on mvp.base_pattern_part_id = bpp.id
                left join wms_color wc on mv.wms_color_id = wc.id                
                left join color_pantone cp on wc.color_pantone_id = cp.id
                where bgr.nastel_party = '%s' GROUP BY mv.id;";
        }
        $sql = sprintf($sql, $this->tikuvDocItems[0]->nastel_party_no);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $out = [];
        $out['model_var'] = null;
        $out['model'] = null;
        $out['doc'] = null;
        $out['model_var_code'] = null;
        $out['model_id'] = null;
        $out['model_var_id'] = null;
        $out['part'] = null;
        if (!empty($results)) {
            foreach ($results as $item) {
                $out['model_id'] = $item['model_id'];
                $out['model_var_id'] = $item['model_var_id'];
                $out['model'] = $item['article'];
                $out['doc'] = $item['doc_number'];
                $out['type'] = $item['type'];
                $out['order_item_id'] = $item['order_item_id'];
                $out['musteri_id'] = $item['musteri_id'];
                $code = $item['code'];
                if($out['part']){
                    $out['part'] = "<p>" . $item['part']." ".$code . " (" . $item['name'] . ")" . "</p>";
                } else {
                    $out['part'] .= "<p>". $item['part']." ".$code . " (" . $item['name'] . ")" . "</p>";
                }
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

    public function getModelListPartInfo()
    {
        $sql = "select  mv.id as model_var_id,
                        ml.id as model_id, 
                        ml.article,
                        mv.name, 
                        cp.code,
                        bpp.name as part
                from bichuv_given_rolls bgr
                left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                left join models_list ml on mrp.models_list_id = ml.id
                left join models_variations mv on mv.id = mrp.model_variation_id
                left join model_variation_parts mvp on mrp.model_var_part_id = mvp.id
                left join base_pattern_part bpp on mvp.base_pattern_part_id = bpp.id
                left join color_pantone cp on mv.color_pantone_id = cp.id
                where bgr.nastel_party = '%s' AND mrp.type = 2 AND mrp.is_combine = 1 GROUP BY mvp.id;";
        $sql = sprintf($sql, $this->tikuvDocItems[0]->nastel_party_no);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $out = [];
        $out['part'] = null;
        $out['model'] = null;
        if (!empty($results)) {
            foreach ($results as $item) {
                $out['model'] = $item['article'];
                $code = $item['code'];
                if (empty($out['part'])) {
                    $out['part'] = $item['part']." ".$code . " (" . $item['name'] . ")";
                } else {
                    $out['part'] .= ", ".$item['part']." ".$code." (" . $item['name'] . ")";
                }
            }
        }
        return $out;
    }

    public function deleteOne()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try {
            TikuvDocItems::deleteAll(['tikuv_doc_id'=>$this->id]);
            if($this->delete()){
                $saved = true;
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

    public static function getNextId() {
        $lastId = static::find()->select('id')->orderBy(['id' => SORT_DESC])->limit(1)->scalar();
        return $lastId ? intval($lastId) + 1 : 1;
    }

    public function TIKUV_ACCEPT_SLICE(Request $request, $model, $models) {
        $saved = false;
        $model->status = TikuvDoc::STATUS_SAVED;
        $saved = $model->save();

        // fact sonlarini saqlash
        if ($saved && \is_iterable($models)) {
            foreach ($models as $tikuvDocItem) {
                $saved = $tikuvDocItem->save();
                if (!$saved) {
                    break;
                }
            }

            // item balance ga qo'shish
            if ($saved) {
                foreach ($models as $tikuvDocItem) {
                    $addItemBalance = new TikuvSliceItemBalance();
                    $addItemBalance->setAttributes([
                        'entity_type' => $tikuvDocItem['entity_type'],
                        'size_id' => $tikuvDocItem['size_id'],
                        'nastel_no' => $tikuvDocItem['nastel_party_no'],
                        'count' => $tikuvDocItem['fact_quantity'],
                        'doc_id' => $model['id'],
                        'doc_type' => $model['document_type'],
                        'hr_department_id' => $model['to_hr_department'],
                        'from_hr_department' => $model['from_hr_department'],
                        'to_hr_department' => $model['to_hr_department'],
                        'musteri_id' => $model['musteri_id'],
                        'mobile_process_id' => $model['mobile_process_id'],
                        'mobile_tables_id' => $model['mobile_table_id'],
                    ]);

                    $saved = TikuvSliceItemBalance::increaseItem($addItemBalance);

                    if (!$saved) {
                        break;
                    }
                }
            }

            // jarayonni ma'lumotlari
            $params = [
                'nastel_no' => $model['party_no'],
                'started_date' => date('d.m.Y H:i:s'),
                'ended_date' => date('d.m.Y H:i:s'),
                'table_name' => TikuvDoc::getTableSchema()->name,
                'model_orders_items_id' => self::getModelOrdersItemsIdByNastelNo($model['party_no']),
                'doc_id' => $model->id,
                'mobile_tables_id' => $model->mobile_table_id,
            ];

            if ($model['mobile_process_id'] == MobileProcess::getProcessIdByToken(Constants::TOKEN_TIKUV_KONVEYER)) {
                // konveyer statusini accepted ( 2 ) qilish
                $saved = $saved && TikuvKonveyerBichuvGivenRolls::findOne([
                        'mobile_tables_id' => $model['mobile_table_id'],
                    ])
                        ->changeStatus(TikuvKonveyerBichuvGivenRolls::STATUS_ACCEPTED);

                $params['status'] = MobileProcessProduction::STATUS_WAITING;
            }

            // jarayonni qayd etish
            $saved = $saved && MobileProcessProduction::saveMobileProcess($params);
        }

        return $saved;
    }

    public function TIKUV_CONVEYOR_OUT(Request $request, $model, $models) {
        $saved = false;
        $model->status = TikuvDoc::STATUS_SAVED;
        $saved = $model->save();

        // fact sonlarini saqlash
        if ($saved && \is_iterable($models)) {
            foreach ($models as $tikuvDocItem) {
                $tikuvDocItem->setAttributes([
                    'tikuv_doc_id' => $model['id'],
                    'fact_quantity' => $tikuvDocItem['quantity'],
                ]);
                $saved = $tikuvDocItem->save();
                if (!$saved) {
                    break;
                }
            }

            // item balance da ayirish
            if ($saved) {
                foreach ($models as $tikuvDocItem) {
                    $minusItemBalance = new TikuvSliceItemBalance();
                    $minusItemBalance->setAttributes([
                        'entity_type' => $tikuvDocItem['entity_type'],
                        'size_id' => $tikuvDocItem['size_id'],
                        'nastel_no' => $tikuvDocItem['nastel_party_no'],
                        'count' => $tikuvDocItem['quantity'],
                        'doc_id' => $model['id'],
                        'doc_type' => $model['document_type'],
                        'hr_department_id' => $model['from_hr_department'],
                        'from_hr_department' => $model['from_hr_department'],
                        'to_hr_department' => $model['to_hr_department'],
                        'musteri_id' => $model['musteri_id'],
                        'mobile_tables_id' => $model['mobile_table_id'],
                        'mobile_process_id' => $model['mobile_process_id'],
                    ]);

                    $saved = TikuvSliceItemBalance::decreaseItem($minusItemBalance);

                    if (!$saved) {
                        break;
                    }
                }
            }

            $nextProcess = MobileProcess::getNextProcessInstanceByTableId($this->mobileTable['id']);

            $nextTable = null;
            if ($nextProcess === null) {
                $saved = false;
                Yii::error('Keyingi jarayon topilmadi');
                Yii::$app->session->setFlash('error', Yii::t('app', 'The next process was not found'));
            } else {
                $nextTable = MobileTables::getNextTableInstanceByProcessId($nextProcess['id']);
            }

            // keyingi jarayon uchun document yaratish (TIKUV yoki TMO)
            if ($saved) {
                $nextDepartment = HrDepartments::getDepartmentInstanceById($nextProcess['department_id']);
                if ($nextDepartment === null) {
                    $saved = false;
                    Yii::error('Departament mavjud emas', 'save');
                } else {
                    switch ($nextDepartment['token']) {
                        case HrDepartments::TOKEN_TIKUV:
                            $nextTikuvDoc = new TikuvDoc();
                            $nextTikuvDoc->setAttributes([
                                'party_no' => $model['party_no'],
                                'parent_id' => $model['id'],
                                'doc_number' => 'T'.self::getNextId(),
                                'document_type' => self::DOC_TYPE_ACCEPTED,
                                'musteri_id' => $model['musteri_id'],
                                'reg_date' => date('Y-m-d'),
                                'from_hr_department' => $model['from_hr_department'],
                                'to_hr_department' => $model['to_hr_department'],
                                'from_hr_employee' => $model['from_hr_employee'],
                                'to_hr_employee' => $model['to_hr_employee'],
                                'mobile_table_id' => $nextTable['id'],
                                'mobile_process_id' => $nextProcess['id'],
                            ]);
                            $saved = $nextTikuvDoc->save();

                            if ($saved) {
                                foreach ($models as $item) {
                                    $nextTikuvDocItem = new TikuvDocItems();
                                    $nextTikuvDocItem->setAttributes([
                                        'tikuv_doc_id' => $nextTikuvDoc['id'],
                                        'size_id' => $item['size_id'],
                                        'entity_id' => $item['entity_id'],
                                        'entity_type' => $item['entity_type'],
                                        'quantity' => $item['fact_quantity'],
                                        'work_weight' => $item['work_weight'],
                                        'nastel_party_no' => $item['nastel_party_no'],
                                        'fact_quantity' => $item['fact_quantity'],
                                    ]);

                                    $saved = $saved && $nextTikuvDocItem->save();
                                    if (!$saved) {
                                        Yii::error($nextTikuvDocItem->getErrors(), 'save');
                                        break;
                                    }
                                }

                            }
                            break;
                        case HrDepartments::TOKEN_TMO:
                            // model_orders_items_id ni nastel no bo'yicha olib kelish
                            $modelInfo = $model->getModelListInfo();

                            $nextDoc = new WmsDocument();
                            $nextDoc->setAttributes([
                                'doc_number' => 'WT'.WmsDocument::getLastId(),
                                'document_type' => WmsDocument::DOCUMENT_TYPE_ACCEPT_TMO,
                                'department_id' => $nextDepartment['id'],
                                'to_musteri' => $model['musteri_id'],
                                'musteri_id' => $modelInfo['musteri_id'],//$model['musteri_id'],
                                'reg_date' => date('Y-m-d H:i:s'),
                                'from_department' => $model['from_hr_department'],
                                'to_department' => $model['to_hr_department'],
                                'from_employee' => $model['from_hr_employee'],
                                'to_employee' => $model['to_hr_employee'],
//                                'mobile_table_id' => $nextTable['id'],
//                                'mobile_process_id' => $nextProcess['id'],
                                'nastel_no' => $model['party_no'],
                            ]);
                            $saved = $nextDoc->save();

                            if ($saved) {
                                $tmoEntityType = WmsItemCategory::getIdByToken(WmsItemCategory::ENTITY_TOKEN_TMO);
                                if ($tmoEntityType === null) {
                                    $saved = false;
                                    Yii::error('Tayyor mahsulot categoryiyasi mavjud emas', 'save');
                                    Yii::$app->session->addFlash('warning', Yii::t('app', 'This {token} category does not exist', ['token' => WmsItemCategory::ENTITY_TOKEN_TMO]));
                                }

                                // TMO UCHUN DEFAULT AREASINI OLISH
                                $TMODepAreaId = WmsDepartmentArea::getAreaIdByToken(WmsDepartmentArea::TOKEN_TMO_GREEN_ZONE);
                                if (!$TMODepAreaId) {
                                    Yii::$app->session->addFlash('warning', Yii::t('app', 'Create an TMO storage sector first'));
                                    $tokenArea = WmsDepartmentArea::TOKEN_TMO_GREEN_ZONE;
                                    Yii::error("TMO uchun '{$tokenArea}' tokenli sektor yaratilmagan", 'save');
                                    $saved = false;
                                }

                                if ($saved) {
                                    foreach ($models as $item) {
                                        $nextDocItem = new WmsDocumentItems();
                                        $nextDocItem->setAttributes([
                                            'wms_document_id' => $nextDoc['id'],
                                            'party_no' => $item['nastel_party_no'],
                                            'size_id' => $item['size_id'],
                                            'entity_id' => $item['entity_id'],
                                            'entity_type' => $tmoEntityType,
                                            'quantity' => $item['fact_quantity'],
                                            'fact_quantity' => $item['quantity'],
                                            'model_orders_items_id' => $modelInfo['order_item_id'],
                                            'musteri_id' => $modelInfo['musteri_id'],
                                            'to_musteri' => $modelInfo['musteri_id'],
                                            'dep_area' => $TMODepAreaId,
                                        ]);

                                        $saved = $saved && $nextDocItem->save();
                                        if (!$saved) {
                                            Yii::error($nextDocItem->getErrors(), 'save');
                                            break;
                                        }
                                    }
                                }

                            }
                            break;
                        default:
                            $saved = false;
                            Yii::error('Keyingi jarayon department id si mavjud emas', 'save');
                    }
                }

            }


            // jarayonni ma'lumotlari
            $params = [
                'nastel_no' => $model['party_no'],
                'started_date' => date('d.m.Y H:i:s'),
                'ended_date' => date('d.m.Y H:i:s'),
                'table_name' => TikuvDoc::getTableSchema()->name,
                'model_orders_items_id' => self::getModelOrdersItemsIdByNastelNo($model['party_no']),
                'doc_id' => $model->id,
                'mobile_tables_id' => $model->mobile_table_id,
            ];

            if ($saved && $model['mobile_process_id'] == MobileProcess::getProcessIdByToken(Constants::TOKEN_TIKUV_KONVEYER)) {
                // konveyer statusini finished ( 5 ) qilish
                $saved = $saved && TikuvKonveyerBichuvGivenRolls::findOne([
                        'mobile_tables_id' => $model['mobile_table_id'],
                    ])
                        ->changeStatus(TikuvKonveyerBichuvGivenRolls::STATUS_FINISHED);

                $params['status'] = MobileProcessProduction::STATUS_ENDED;
            }

            // jarayonni qayd etish
            $saved = $saved && MobileProcessProduction::saveMobileProcess($params);
        }

        return $saved;
    }

    public function isSavedDocument() {
        return $this->status > 2;
    }

    public static function getModelOrdersItemsIdByNastelNo(string $nastelNo) {
        return ModelRelProduction::find()
            ->select('order_item_id')
            ->andWhere(['nastel_no' => $nastelNo])
            ->scalar();
    }
}
