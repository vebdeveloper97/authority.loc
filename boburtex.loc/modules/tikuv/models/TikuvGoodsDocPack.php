<?php

namespace app\modules\tikuv\models;

use app\models\Constants;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\base\models\BarcodeCustomers;
use app\modules\base\models\Brend;
use app\modules\bichuv\models\ModelRelProduction;
use app\modules\base\models\Goods;
use app\modules\base\models\GoodsItems;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\Musteri;
use app\modules\bichuv\models\BichuvMusteri;
use app\modules\toquv\models\SortName;
use app\modules\toquv\models\Unit;
use app\modules\wms\models\WmsDepartmentArea;
use app\modules\wms\models\WmsDocitemsRelTikuvPackage;
use app\modules\wms\models\WmsDocument;
use app\modules\wms\models\WmsDocumentItems;
use app\modules\wms\models\WmsGender;
use app\modules\wms\models\WmsItemBarcode;
use app\modules\wms\models\WmsItems;
use app\modules\wms\models\WmsItemWrappers;
use app\modules\wms\models\WmsModelCategory;
use app\modules\wms\models\WmsModels;
use app\modules\wms\models\WmsModelVariations;
use app\modules\wms\models\WmsPrice;
use Yii;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\httpclient\Client;

/**
 * This is the model class for table "tikuv_goods_doc_pack".
 *
 * @property int $id
 * @property string $doc_number
 * @property string $reg_date
 * @property int $department_id
 * @property int $order_id
 * @property int $order_item_id
 * @property int $created_by
 * @property int $status
 * @property int $is_incoming
 * @property int $from_department
 * @property string $to_department
 * @property int $from_musteri
 * @property int $to_musteri
 *
 * @property TikuvGoodsDoc[] $tikuvGoodsDocs
 * @property ToquvDepartments $department
 * @property ToquvDepartments $fromDepartment
 * @property ModelsVariations $modelVar
 * @property BarcodeCustomers $barcodeCustomer
 * @property ModelsList $modelList
 * @property Brend $brand
 * @property ModelOrders $order
 * @property ModelOrdersItems $orderItem
 * @property int $created_at [int(11)]
 * @property null|array $departments
 * @property ActiveQuery $toDepartment
 * @property array $orderItemList
 * @property int $updated_at [int(11)]
 * @property mixed $unitList
 * @property int $is_full [smallint(6)]
 * @property string $nastel_no [varchar(20)]
 * @property int $model_list_id [int(11)]
 * @property int $model_var_id [int(11)]
 * @property int $brand_type [smallint(1)]
 * @property array $sortTypeList
 * @property ActiveQuery $fromMusteri
 * @property ActiveQuery $toMusteri
 * @property int $brand_id [int(11)]
 * @property mixed $barcodeCustomerList
 * @property int $barcode_customer_id [int(11)]
 */
class TikuvGoodsDocPack extends BaseModel
{
    public $color;
    public $sabu;
    public $wmsModelVarList = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_goods_doc_pack';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reg_date','sabu'], 'safe'],
            [['model_var_id'],'required'],
            [['department_id','brand_type','barcode_customer_id','brand_id','model_var_id','model_list_id','is_full', 'is_incoming', 'order_id', 'order_item_id', 'created_at', 'updated_at', 'created_by', 'status', 'from_department', 'from_musteri', 'to_musteri'], 'integer'],
            [['doc_number','to_department','nastel_no'], 'string', 'max' => 255],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['order_item_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brend::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['barcode_customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => BarcodeCustomers::className(), 'targetAttribute' => ['barcode_customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'reg_date' => Yii::t('app', 'Sana'),
            'department_id' => Yii::t('app', 'Department ID'),
            'model_var_id' => Yii::t('app', 'Nastel raqami, model va model rangi'),
            'order_id' => Yii::t('app', 'Buyurtma'),
            'order_item_id' => Yii::t('app', 'Buyurtma haqida ma\'lumotlar'),
            'barcode_customer_id' => Yii::t('app', 'Brend'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'brand_type' => Yii::t('app', 'Brend'),
            'from_department' => Yii::t('app', 'From Department'),
            'to_department' => Yii::t('app', "Qaysi bo'limga"),
            'from_musteri' => Yii::t('app', 'Kimdan'),
            'to_musteri' => Yii::t('app', 'Kimga'),
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if(!empty($this->reg_date)){
                $date = date('Y-m-d', strtotime($this->reg_date));
                $hours = date('H:i:s');
                $this->reg_date = date('Y-m-d H:i:s', strtotime($date." ".$hours));
            }else{
                $this->reg_date = date('Y-m-d H:i:s');
            }
            if(!empty($this->doc_number)){
                $currentDate = date('Y');
                if($this->isNewRecord){
                    $docNumberExist = TikuvGoodsDocPack::find()->where(['doc_number' => $this->doc_number])->asArray()->one();
                    if(!empty($docNumberExist)){
                        $index = $docNumberExist['id'] + 1;
                        $this->doc_number = "TK{$index}/{$currentDate}";
                    }
                }else{
                    $this->doc_number = "TK{$this->id}/{$currentDate}";
                }
            }
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        $this->reg_date = date('d.m.Y H:i:s', strtotime($this->reg_date));
        $sql = "SELECT cp.code FROM color_pantone cp
                        LEFT JOIN model_rel_doc mrd on cp.id = mrd.color_id
                WHERE mrd.nastel_no = '{$this->nastel_no}'
                ";
        $this->color = Yii::$app->db->createCommand($sql)->queryOne()['code'];
        parent::afterFind();
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvGoodsDocs()
    {
        return $this->hasMany(TikuvGoodsDoc::className(), ['tgdp_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'department_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBarcodeCustomer()
    {
        return $this->hasOne(BarcodeCustomers::className(), ['id' => 'barcode_customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brend::className(), ['id' => 'brand_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_list_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_var_id']);
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
    public function getToDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'to_department']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelRelDoc()
    {
        return $this->hasOne(ModelRelDoc::className(), ['nastel_no' => 'nastel_no']);
    }
    public function getModelRelProduction()
    {
        return $this->hasOne(ModelRelProduction::className(), ['nastel_no' => 'nastel_no']);
    }
    public function getOrder()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'order_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrderItem()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'order_item_id']);
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
    public function getToMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'to_musteri']);
    }
    /**
     * @param $params
     * @return array
     * @throws Exception
     */
    public static function searchAjax($params){
        if(!empty($params['q'])){
            $words = explode(' ', $params['q']);
            $q = "";
            $wordCount = count($words);
            $nastelNo1 = "";
            $modelId1 = "";
            $modelVarId1 = "";
            $brandType = "";

            if(!empty($params['nastelNo'])){
                $nastelNo1 = "AND (topp.nastel_no = '{$params['nastelNo']}' OR t.nastel_no = '{$params['nastelNo']}')"; //topp ni 2-3 xil nastel_no li childlari bo'lganda topilme qolgani uchun t.nastel_no qo'shdim
            }
            if(!empty($params['modelId'])){
                $modelId1 = " AND topp.model_list_id = {$params['modelId']}";
            }
            if(!empty($params['modelVarId'])){
                $modelVarId1 = " AND topp.model_var_id = {$params['modelVarId']}";
            }
            if(!empty($params['brandTypeId'])){
                $brandType = " AND bc.id = {$params['brandTypeId']}";
            }

            if(preg_match('/\b(qop)\b/i', $params['q'], $matches)){
                if(!empty($params['nastelNo'])){
                    $nastelNo1 = " AND t.nastel_no = '{$params['nastelNo']}' "; //topp ni 2-3 xil nastel_no li childlari bo'lganda topilme qolgani uchun t.nastel_no qo'shdim
                }
                $q .= "g.name LIKE '%{$params['q']}%'";
                $sql = "select g.id, 
                               g.model_no, 
                               g.type, 
                               g.name,
                               s.name as sizeName, 
                               cp.code,
                               gb.barcode,
                               g.barcode as main, 
                               bc.code as barcodeCode,
                               bc.id as brandId,
                               s.name as sort_name 
                    from goods g
                             left join goods_items gi on gi.parent = g.id
                             left join goods g2 on gi.child = g2.id
                             left join goods_items gi2 on gi2.parent = g2.id
                             left join goods g3 on gi2.child = g3.id
                             left join goods_items gi3 on gi3.parent = g3.id
                             left join goods g4 on gi3.child = g4.id
                             left join size s on g4.size = s.id
                             inner join tikuv_package_item_balance t on g4.id = t.goods_id
                             left join sort_name sn on t.sort_type_id = sn.id  
                             left join barcode_customers bc on t.barcode_customer_id = bc.id
                             left join color_pantone cp on g4.color = cp.id
                             left join goods_barcode gb on g.id = gb.goods_id
                        WHERE %s %s
                        GROUP BY g.id
                    LIMIT 25;";
                $sql = sprintf($sql, $q, $nastelNo1, $brandType);
            }elseif (preg_match('/\b(blo.?)\b/i', $params['q'], $matches)){
                $q .= "g.name LIKE '%{$params['q']}%'";
                $sql = "select g.id, 
                               g.model_no, 
                               g.type, 
                               g.name,
                               s.name as sizeName, 
                               cp.code,
                               gb.barcode,
                               g.barcode as main, 
                               bc.code as barcodeCode,
                               bc.id as brandId
                    from goods g
                             left join goods_items gi on gi.parent = g.id
                             left join goods g2 on gi.child = g2.id
                             left join goods_items gi2 on gi2.parent = g2.id
                             left join goods g3 on gi2.child = g3.id
                             left join size s on g3.size = s.id
                             left join color_pantone cp on g3.color = cp.id
                             inner join tikuv_outcome_products t on g3.id = t.goods_id
                             left join tikuv_outcome_products_pack topp on t.pack_id = topp.id
                             left join barcode_customers bc on topp.barcode_customer_id = bc.id
                             left join goods_barcode gb on g.id = gb.goods_id   
                        WHERE %s %s %s
                        GROUP BY g.id
                    LIMIT 25;";
                $sql = sprintf($sql, $q, $nastelNo1, $brandType);
            }elseif (preg_match('/\b(pak.?.?)\b/i', $params['q'], $matches)){
                $q .= "AND g.name LIKE '%{$params['q']}%'";
                $sql = "select g.id, 
                               g.model_no, 
                               g.type, 
                               g.name,
                               s.name as sizeName, 
                               cp.code,
                               gb.barcode,
                               bc.code as barcodeCode,
                               g.barcode as main, 
                               bc.id as brandId
                    from goods g
                             left join goods_items gi on gi.parent = g.id
                             left join goods g2 on gi.child = g2.id
                             left join size s on g2.size = s.id
                             left join color_pantone cp on g2.color = cp.id
                             inner join tikuv_outcome_products t on g2.id = t.goods_id
                             left join tikuv_outcome_products_pack topp on t.pack_id = topp.id
                             left join barcode_customers bc on topp.barcode_customer_id = bc.id
                             left join goods_barcode gb on g.id = gb.goods_id
                        WHERE 1=1 %s %s %s
                        GROUP BY g.id
                    LIMIT 25;";
                $sql = sprintf($sql, $q, $nastelNo1, $brandType);
            }else{
                if(!empty($params['nastelNo'])){
                    $nastelNo1 = "AND (t.nastel_no = '{$params['nastelNo']}')"; //topp ni 2-3 xil nastel_no li childlari bo'lganda topilme qolgani uchun t.nastel_no qo'shdim
                }
                if(!empty($params['modelId'])){
                    $modelId1 = " AND g.model_id = {$params['modelId']}";
                }
                if(!empty($params['modelVarId'])){
                    $modelVarId1 = " AND g.model_var = {$params['modelVarId']}";
                }
                if(!empty($params['brandTypeId'])){
                    $brandType = " AND bc.id = {$params['brandTypeId']}";
                }
                if($wordCount == 1){
                    //$q = " (s.name LIKE '%{$words[0]}%' OR cp.code LIKE '%{$words[0]}%' OR g.model_no LIKE '%{$words[0]}%') ";
                    $q = " (s.name LIKE '%{$words[0]}%')";
                }elseif($wordCount == 2){
                    //$q = " (g.model_no LIKE '%{$words[0]}%' AND (cp.code LIKE '%{$words[1]}%' OR s.name LIKE '%{$words[1]}%')) ";
                    $q = " (s.name LIKE '%{$words[0]}%' AND (cp.code LIKE '%{$words[1]}%' OR g.model_no LIKE '%{$words[1]}%')) ";
                }elseif ($wordCount == 3){
                    $q = " (g.model_no LIKE '%{$words[0]}%' AND cp.code LIKE '%{$words[1]}%' AND s.name LIKE '%{$words[2]}%') ";
                }else{
                    $q = " ";
                    foreach ($words as $key=>$item){
                        $q .= " (g.model_no LIKE '%{$item}%' OR cp.code LIKE '%{$item}%' OR s.name LIKE '%{$item}%') ";
                        if($wordCount > ($key+1)){
                            $q .= " OR ";
                        }
                    }
                }
                /*$sql = "select g.id,
                               g.model_no,
                               g.type,
                               g.name,
                               g.type,
                               s.name as sizeName,
                               cp.code,
                               t.is_main_barcode as barcode,
                               g.barcode as main, 
                               bc.id as brandId,
                               bc.code as barcodeCode 
                        from goods g
                                 left join size s on g.size = s.id
                                 left join color_pantone cp on g.color = cp.id
                                 inner join tikuv_outcome_products t on g.id = t.goods_id
                                 left join tikuv_outcome_products_pack topp on t.pack_id = topp.id
                                 left join barcode_customers bc on topp.barcode_customer_id = bc.id
                        WHERE %s %s %s
                        GROUP BY g.id
                        LIMIT 25;";*/
                $sql = "select g.id,
                               g.model_no,
                               g.type,
                               g.name,
                               g.type,
                               s.name as sizeName,
                               t.inventory remain,
                               cp.code,
                               t.is_main_barcode as barcode,
                               g.barcode as main, 
                               bc.id as brandId,
                               bc.code as barcodeCode,
                               sn.id sort_id,
                               sn.name sort_name 
                        from goods g
                                 left join size s on g.size = s.id
                                 left join color_pantone cp on g.color = cp.id
                                 inner join tikuv_package_item_balance t on g.id = t.goods_id
                                 left join barcode_customers bc on t.barcode_customer_id = bc.id
                                 left join sort_name sn on t.sort_type_id = sn.id
                        WHERE %s %s %s AND t.id IN (SELECT max(t.id) id FROM tikuv_package_item_balance t LEFT JOIN goods g on t.goods_id = g.id 
                                        left join color_pantone cp on g.color = cp.id
                                        LEFT JOIN size s on g.size = s.id WHERE %s %s %s AND t.dept_type = 'P' GROUP BY g.id,t.sort_type_id,t.nastel_no
                                    ) AND t.inventory > 0
                        GROUP BY g.id,t.sort_type_id
                        LIMIT 25;";
                $sql = sprintf($sql, $q, $nastelNo1, $brandType, $q, $nastelNo1, $brandType);
            }
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            $result['results'] = [];
            foreach ($res as $item){
                $barcode = $item['barcode'];
                if($item['brandId'] == 1){
                    $barcode = $item['main'];
                }
                if($item['type'] == 1){
                    $code = $item['code'];
                    array_push($result['results'],[
                        'id' => $item['id'],
                        'type' => $item['type'],
                        'brand_type' => $item['brandId'],
                        'text' => "{$item['model_no']}-({$code})-{$item['sizeName']}-({$item['sort_name']}) - ({$item['remain']} dona)",
                        'barcode' => $barcode,
                        'remain' =>$item['remain'],
                        'sort_id' => $item['sort_id']
                    ]);
                }else{
                    array_push($result['results'],[
                        'id' => $item['id'],
                        'text' => $item['name']."-({$item['sort_name']})",
                        'type' => $item['type'],
                        'brand_type' => $item['brandId'],
                        'barcode' => $barcode,
                        'sort_id' => $item['sort_id'],
                        'remain' => $item['remain'],
                    ]);
                }
            }
            return $result;
        }else{
            return ['results' =>
                [
                    'id' => null,
                    'text' => null,
                    'type' => null,
                    'brand_type' => null,
                    'barcode' => null,
                    'remain' => null
                ]
            ];
        }
    }

    public static function searchGetValues($params){
        if(!empty($params['q_q'])){
            $words = explode(' ', $params['q']);
            $q = "";
            $wordCount = count($words);

            $nastelNo1 = "";
            $brandType = "";
            $deptartmentId = "";
            $sabu ="";

            if(!empty($params['nastelNo'])){
                $nastelNo1 = "AND (topp.nastel_no = '{$params['nastelNo']}' OR t.nastel_no = '{$params['nastelNo']}')"; //topp ni 2-3 xil nastel_no li childlari bo'lganda topilme qolgani uchun t.nastel_no qo'shdim
            }
            if(!empty($params['brandTypeId'])){
                $brandType = " AND bc.id = {$params['brandTypeId']}";
            }
            if(!empty($params['deptId'])){
                $deptartmentId = " AND t.department_id = {$params['deptId']}";
            }
            if(!empty($params['sabu'])){
                $sabu = " AND g.name LIKE '%{$params['sabu']}%'";
            }

            $check_type = $params['q_q'];
            if($check_type == 1){
                if(!empty($params['nastelNo'])){
                    $nastelNo1 = " AND t.nastel_no = '{$params['nastelNo']}' "; //topp ni 2-3 xil nastel_no li childlari bo'lganda topilme qolgani uchun t.nastel_no qo'shdim
                }
                $q .= "g.name LIKE '%(Qop)%'";

                $sql = "select g.id, 
                               g.model_no, 
                               g.type, 
                               g.name,
                               s.name as sizeName, 
                               cp.code,
                               gb.barcode,
                               g.barcode as main, 
                               bc.code as barcodeCode,
                               bc.id as brandId,
                               s.name as sort_name 
                    from goods g
                             left join goods_items gi on gi.parent = g.id
                             left join goods g2 on gi.child = g2.id
                             left join goods_items gi2 on gi2.parent = g2.id
                             left join goods g3 on gi2.child = g3.id
                             left join goods_items gi3 on gi3.parent = g3.id
                             left join goods g4 on gi3.child = g4.id
                             left join size s on g4.size = s.id
                             inner join tikuv_package_item_balance t on g4.id = t.goods_id
                             left join sort_name sn on t.sort_type_id = sn.id  
                             left join barcode_customers bc on t.barcode_customer_id = bc.id
                             left join color_pantone cp on g4.color = cp.id
                             left join goods_barcode gb on g.id = gb.goods_id
                        WHERE %s %s %s %s %s
                        GROUP BY g.id
                    LIMIT 50;";
                $sql = sprintf($sql, $q, $nastelNo1, $brandType, $deptartmentId, $sabu);
            }elseif ($check_type == 2){
                $q .= "g.name LIKE '%{$params['q']}%'";
                $sql = "select g.id, 
                               g.model_no, 
                               g.type, 
                               g.name,
                               s.name as sizeName, 
                               cp.code,
                               gb.barcode,
                               g.barcode as main, 
                               bc.code as barcodeCode,
                               bc.id as brandId
                    from goods g
                             left join goods_items gi on gi.parent = g.id
                             left join goods g2 on gi.child = g2.id
                             left join goods_items gi2 on gi2.parent = g2.id
                             left join goods g3 on gi2.child = g3.id
                             left join size s on g3.size = s.id
                             left join color_pantone cp on g3.color = cp.id
                             inner join tikuv_outcome_products t on g3.id = t.goods_id
                             left join tikuv_outcome_products_pack topp on t.pack_id = topp.id
                             left join barcode_customers bc on topp.barcode_customer_id = bc.id
                             left join goods_barcode gb on g.id = gb.goods_id   
                        WHERE %s %s %s %s
                        GROUP BY g.id
                    LIMIT 50;";
                $sql = sprintf($sql, $q, $nastelNo1, $brandType, $sabu);
            }elseif ($check_type == 3){
                $q .= "AND g.name LIKE '%{$params['q']}%'";
                $sql = "select g.id, 
                               g.model_no, 
                               g.type, 
                               g.name,
                               s.name as sizeName, 
                               cp.code,
                               gb.barcode,
                               bc.code as barcodeCode,
                               g.barcode as main, 
                               bc.id as brandId
                    from goods g
                             left join goods_items gi on gi.parent = g.id
                             left join goods g2 on gi.child = g2.id
                             left join size s on g2.size = s.id
                             left join color_pantone cp on g2.color = cp.id
                             inner join tikuv_outcome_products t on g2.id = t.goods_id
                             left join tikuv_outcome_products_pack topp on t.pack_id = topp.id
                             left join barcode_customers bc on topp.barcode_customer_id = bc.id
                             left join goods_barcode gb on g.id = gb.goods_id
                        WHERE 1=1 %s %s %s %s
                        GROUP BY g.id
                    LIMIT 50;";
                $sql = sprintf($sql, $q, $nastelNo1, $brandType, $sabu);
            }else{
                if(!empty($params['nastelNo'])){
                    $nastelNo1 = "AND (t.nastel_no = '{$params['nastelNo']}')"; //topp ni 2-3 xil nastel_no li childlari bo'lganda topilme qolgani uchun t.nastel_no qo'shdim
                }
                if(!empty($params['brandTypeId'])){
                    $brandType = " AND t.barcode_customer_id = {$params['brandTypeId']}";
                }
                if($wordCount == 1){
                    //$q = " (s.name LIKE '%{$words[0]}%' OR cp.code LIKE '%{$words[0]}%' OR g.model_no LIKE '%{$words[0]}%') ";
                    $q = " (s.name LIKE '%{$words[0]}%')";
                }elseif($wordCount == 2){
                    //$q = " (g.model_no LIKE '%{$words[0]}%' AND (cp.code LIKE '%{$words[1]}%' OR s.name LIKE '%{$words[1]}%')) ";
                    $q = " (s.name LIKE '%{$words[0]}%' AND (cp.code LIKE '%{$words[1]}%' OR g.model_no LIKE '%{$words[1]}%')) ";
                }elseif ($wordCount == 3){
                    $q = " (g.model_no LIKE '%{$words[0]}%' AND cp.code LIKE '%{$words[1]}%' AND s.name LIKE '%{$words[2]}%') ";
                }else{
                    $q = " ";
                    foreach ($words as $key=>$item){
                        $q .= " (g.model_no LIKE '%{$item}%' OR cp.code LIKE '%{$item}%' OR s.name LIKE '%{$item}%') ";
                        if($wordCount > ($key+1)){
                            $q .= " OR ";
                        }
                    }
                }
                /*$sql = "select g.id,
                               g.model_no,
                               g.type,
                               g.name,
                               g.type,
                               s.name as sizeName,
                               cp.code,
                               t.is_main_barcode as barcode,
                               g.barcode as main,
                               bc.id as brandId,
                               bc.code as barcodeCode
                        from goods g
                                 left join size s on g.size = s.id
                                 left join color_pantone cp on g.color = cp.id
                                 inner join tikuv_outcome_products t on g.id = t.goods_id
                                 left join tikuv_outcome_products_pack topp on t.pack_id = topp.id
                                 left join barcode_customers bc on topp.barcode_customer_id = bc.id
                        WHERE %s %s %s
                        GROUP BY g.id
                        LIMIT 25;";*/
                $sql = "select g.id,
                               g.model_no,
                               g.type,
                               g.name,
                               g.type,
                               s.name as sizeName,
                               t.inventory remain,
                               cp.code,
                               t.is_main_barcode as barcode,
                               g.barcode as main, 
                               bc.id as brandId,
                               bc.code as barcodeCode,
                               sn.id sort_id,
                               sn.name sort_name 
                        from goods g
                                 left join size s on g.size = s.id
                                 left join color_pantone cp on g.color = cp.id
                                 inner join tikuv_package_item_balance t on g.id = t.goods_id
                                 left join barcode_customers bc on t.barcode_customer_id = bc.id
                                 left join sort_name sn on t.sort_type_id = sn.id
                        WHERE %s %s %s %s AND t.id IN (SELECT max(t.id) id FROM tikuv_package_item_balance t LEFT JOIN goods g on t.goods_id = g.id 
                                        left join color_pantone cp on g.color = cp.id
                                        LEFT JOIN size s on g.size = s.id WHERE %s %s %s AND t.dept_type = 'P' GROUP BY g.id,t.sort_type_id,t.nastel_no
                                    ) AND t.inventory > 0
                        GROUP BY g.id,t.sort_type_id,t.is_main_barcode
                        LIMIT 50;";
                $sql = sprintf($sql, $q, $nastelNo1, $brandType, $deptartmentId, $q, $nastelNo1, $brandType, $deptartmentId);
            }
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            $result['results'] = [];
            foreach ($res as $item){
                $barcode = $item['barcode'];
                if($item['brandId'] == 1){
                    $barcode = $item['main'];
                }
                if($item['type'] == 1){
                    $code = $item['code'];
                    array_push($result['results'],[
                        'id' => $item['id'],
                        'type' => $item['type'],
                        'brand_type' => $item['brandId'],
                        'text' => "{$item['model_no']}-({$code})-{$item['sizeName']}-({$item['sort_name']}) - ({$item['remain']} dona)",
                        'barcode' => $barcode,
                        'remain'=>$item['remain'],
                        'sort_id' => $item['sort_id']
                    ]);
                }else{
                    array_push($result['results'],[
                        'id' => $item['id'],
                        'text' => $item['name']."-({$item['sort_name']})",
                        'type' => $item['type'],
                        'brand_type' => $item['brandId'],
                        'barcode' => $barcode,
                        'sort_id' => $item['sort_id'],
                        'remain'=>$item['remain'],
                    ]);
                }
            }
            return $result;
        }else{
            return ['results' =>
                [
                    'id' => null,
                    'text' => null,
                    'type' => null,
                    'brand_type' => null,
                    'barcode' => null,
                    'remain'=> null

                ]
            ];
        }
    }
    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public function getBelongToPack($id){

        $sql = "select g.id, g.name, g.type, g.model_no, cp.code, s.name as sizeName from goods g
                left join size s on g.size = s.id
                left join color_pantone cp on g.color = cp.id  
                    WHERE g.id IN (
                    select tgd.goods_id from tikuv_goods_doc_pack tgdp
                    left join tikuv_goods_doc tgd on tgdp.id = tgd.tgdp_id
                    WHERE tgdp.id = %d
                    ) GROUP BY g.id LIMIT 25;";
        $sql = sprintf($sql,$id);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($res)){
            $result = [];
            foreach ($res as $item){
                if($item['type'] == 1){
                    $result[$item['id']] = $item['model_no']."-".$item['code']."-".$item['sizeName'];
                }else{
                    $result[$item['id']] = $item['name'];
                }
            }
            return $result;
        }else{
            return [];
        }
    }

    /**
     * @param int $i
     * @return array
     * @throws Exception
     */
    public function getOrders($i){
        $sql = "select mo.id, mo.doc_number, m.name, mo.reg_date  from model_orders mo
                    left join musteri m on mo.musteri_id = m.id
                    ORDER BY mo.id DESC;";
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($results, 'id', function ($item){
            return $item['doc_number'] . " (". $item['name'] .") (".  date('d.m.Y', strtotime($item['reg_date'])).")";
        });
    }

    public function getDepartments(){
        $curUser = Yii::$app->user->id;
        $sql = "select  td.id, 
                        td.name 
                from toquv_user_department tud
                left join toquv_departments td on tud.department_id = td.id
                where tud.user_id = %d";
        $sql = sprintf($sql, $curUser);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($res, 'id','name');
    }

    public function getModelVarWithNastelList($dept_type = 'P'){

        $fromDeptCondition = "";
        if($dept_type == 'P'){
            $fromDeptCondition = " AND tpib2.from_department = {$this->from_department} ";
        }
        $sql = "select SUM(tpib.inventory) as sum,
                       tpib.nastel_no,
                       ml.article, 
                       mv.name as model_var,
                       ml.id as model_id,
                       mv.id as model_var_id,
                       cp.name as pantone,
                       cp.code,
                       tpib.order_id,
                       tpib.order_item_id,
                       b2.name as brand2,
                       b3.name as brand3,
                       tpib.brand_type 
                       from tikuv_package_item_balance tpib
                left join models_list ml on ml.id = (select ml.id from models_list ml where ml.id = tpib.model_list_id limit 1)
                left join models_variations mv on mv.id = (select mv.id from models_variations mv where mv.id = tpib.model_var_id limit 1)
                left join color_pantone cp on mv.color_pantone_id = cp.id
                left join goods g on tpib.goods_id = g.id
                left join brend b2 on g.brand2 = b2.id
                left join brend b3 on g.brand3 = b3.id 
                where tpib.id IN (select MAX(tpib2.id) from tikuv_package_item_balance tpib2 
                where tpib2.dept_type = '%s' %s and tpib2.nastel_no = '%s'
                GROUP BY tpib2.goods_id, tpib2.sort_type_id)
                AND tpib.inventory > 0 AND ml.id = %d AND tpib.brand_type = %d and mv.id = %d and tpib.department_id = %d
                GROUP BY tpib.model_var_id limit 100;";
        $sql = sprintf($sql, $dept_type, $fromDeptCondition, $this->nastel_no, $this->model_list_id, $this->brand_type, $this->model_var_id, $this->department_id);
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        if(!empty($res)) {
            $brand = Constants::$brandSAMO;
            if($res['brand_type'] == 2){
                $brand = $res['brand2'];
            }elseif ($res['brand_type'] == 3){
                $brand = $res['brand3'];
            }
            $out['data'] = [$res['nastel_no'] => "({$brand}) {$res['nastel_no']} ({$res['article']} {$res['code']})(Jami:{$res['sum']})"];
            $out['dataAttr'] = [
                'data-model-id' => $res['model_id'],
                'data-nastel-no' => $res['nastel_no'],
                'data-order-id' => $res['order_id'],
                'data-model-var-id' => $res['model_var_id'],
                'data-order-item-id' => $res['order_item_id']
            ];
            return $out;
        }
         return null;
    }

    public function getOrderItemList()
    {
        $sql = "SELECT
                    moi.id,
                    mo.doc_number,
                    cp.code,
                    m.name musteri,
                    ml.name model,
                    size_id,
                    st.name size_type,
                    load_date,
                    summa
                FROM model_orders_items moi 
                LEFT JOIN model_orders mo on moi.model_orders_id = mo.id 
                LEFT JOIN models_variations mv on moi.model_var_id = mv.id 
                LEFT JOIN models_variation_colors mvc on mv.id = mvc.model_var_id 
                LEFT JOIN color_pantone cp on mvc.color_pantone_id = cp.id 
                LEFT JOIN musteri m on mo.musteri_id = m.id 
                LEFT JOIN models_list ml on moi.models_list_id = ml.id 
                LEFT JOIN model_orders_items_size mois on moi.id = mois.model_orders_items_id 
                LEFT JOIN ( SELECT model_orders_items_id, SUM(count) summa FROM model_orders_items_size mois3 
                            LEFT JOIN size s2 on mois3.size_id = s2.id GROUP BY mois3.model_orders_items_id ) 
                    mois2 on moi.id = mois2.model_orders_items_id 
                LEFT JOIN size s on mois.size_id = s.id LEFT JOIN size_type st on s.size_type_id = st.id 
                WHERE 
                    ( mo.id = :order_id ) AND 
                    ( mvc.is_main = 1 ) AND 
                    ( mois.id = ( SELECT MAX(id) FROM model_orders_items_size mois WHERE mois.model_orders_items_id = moi.id )
            )";
        $model = Yii::$app->db->createCommand($sql)->bindValue(':order_id', $this->order_id)->queryAll();
        $response = [];
        foreach ($model as $key){
            $response[$key['id']] = $key['doc_number'] .' ('. $key['model'] .' - '. $key['code'] .')' .' ('. $key['size_type'] .')' .' ('. $key['summa'] .')' .' ('. date('d.m.Y',strtotime($key['load_date'])) .')';
        }
        return $response;
    }

    public function getMovingDepartmentList($key = null){
        $dataToDept = [
            'TMO' => Yii::t('app','Tayyor maxsulotlar ombori'),
            'SHOWROOM' => Yii::t('app','Showroom')
        ];
        if(!empty($key)){
            return $dataToDept[$key];
        }
        return $dataToDept;
    }

    public function getUnitList(){
        $units = Unit::find()->asArray()->all();
        return ArrayHelper::map($units,'id','name');
    }

    public static function getModelListWithNastel($q="", $fromDept="", $currentDeptId, $deptType = 'P', $list=false){

        $conditionQ = "";
        $conditionDept = "";
        if(!empty($q) and trim($q)){
            $conditionQ = " AND ((tpib.nastel_no LIKE '%{$q}%') OR (ml.article LIKE '%{$q}%') OR (cp.code LIKE '%{$q}%')) ";
        }
        if(!empty($fromDept)){
            $conditionDept = " AND tpib2.from_department = '{$fromDept}' ";
        }
        $sql= "select SUM(tpib.inventory) as sum,
                       tpib.nastel_no,
                       ml.article, 
                       mv.name as model_var,
                       ml.id as model_id,
                       mv.id as model_var_id,
                       cp.name as pantone,
                       cp.code,
                       tpib.order_id,
                       tpib.order_item_id,
                       bc.name as brand,
                       bc.id as brandId 
                       from tikuv_package_item_balance tpib
                left join models_list ml on ml.id = (select ml.id from models_list ml where ml.id = tpib.model_list_id limit 1)
                left join models_variations mv on mv.id = (select mv.id from models_variations mv where mv.id = tpib.model_var_id limit 1)
                left join color_pantone cp on mv.color_pantone_id = cp.id
                left join goods g on tpib.goods_id = g.id
                left join barcode_customers bc on tpib.barcode_customer_id = bc.id
                where tpib.id IN (
                            select MAX(tpib2.id) from tikuv_package_item_balance tpib2 
                            where tpib2.department_id = %d AND tpib2.dept_type = '%s' %s
                            GROUP BY tpib2.goods_id,tpib2.nastel_no, tpib2.model_var_id, tpib2.sort_type_id, tpib2.barcode_customer_id
                        )
                AND tpib.inventory > 0  %s GROUP BY tpib.nastel_no,tpib.model_var_id, tpib.barcode_customer_id LIMIT 100;";
        $sql = sprintf($sql, $currentDeptId, $deptType, $conditionDept, $conditionQ);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        if($list){
            $out = [];
            $modelVar = [];
            foreach ($results as $result) {
                $qty = number_format($result['sum'],0,'.',' ');
                array_push($modelVar, $result['model_var']);
                $out['data'][$result['nasel_no']] = "({$result['brand']}) {$result['nastel_no']} ({$result['article']} - {$result['code']} {$result['pantone']}) (Jami: {$qty} dona)";
                $out['dataAttr'][$result['nastel_no']] = [
                    'data-model-id'      => $result['model_id'],
                    'data-model-var-id'  => $result['model_var_id'],
                    'data-model'         => $result['article'],
                    'data-model-var'     => join(',', $modelVar),
                    'data-nastel-no'     => $result['nastel_no'],
                    'data-order-id'      => $result['order_id'],
                    'data-order-item-id' => $result['order_item_id']
                ];
            }
            return $out;
        }
        return $results;
    }

    public static function getModelListWithOthers($musteri, $nastel = '', bool $list = false)
    {
        if(!empty($nastel)){
            $sql= "select SUM(bsib.inventory) as sum,
                       bsib.nastel_no,
                       ml.article, 
                       mv.name as model_var,
                       ml.id as model_id,
                       mv.id as model_var_id,
                       cp.name as pantone,
                       cp.code, 
                       st.id size_type_id, 
                       s.name size, 
                       s.id size_id 
                       from bichuv_service_item_balance bsib
                left join models_list ml on ml.id = bsib.model_id
                left join models_variations mv on mv.id = bsib.model_var
                left join color_pantone cp on mv.color_pantone_id = cp.id
                left join size s on bsib.size_id = s.id
                left join size_type st on s.size_type_id = st.id
                where bsib.id in (select MAX(bsib2.id) from bichuv_service_item_balance bsib2 where bsib2.musteri_id = %d GROUP BY bsib2.nastel_no, bsib2.musteri_id, bsib2.size_id) AND bsib.musteri_id = %d
                AND bsib.inventory > 0 AND bsib.nastel_no = %s GROUP BY bsib.musteri_id,bsib.size_id LIMIT 100;";
            $sql = sprintf($sql, $musteri, $musteri, $nastel);
        }
        else{
            $sql = "select SUM(bsib.inventory) as sum,
                           bsib.nastel_no,
                           ml.article, 
                           mv.name as model_var,
                           ml.id as model_id,
                           mv.id as model_var_id,
                           cp.name_ru as pantone,
                           cp.code
                           from bichuv_service_item_balance bsib
                    left join models_list ml on ml.id = bsib.model_id
                    left join models_variations mv on mv.id = bsib.model_var
                    left join color_pantone cp on mv.color_pantone_id = cp.id
                    where bsib.id in (select MAX(bsib2.id) from bichuv_service_item_balance bsib2 where bsib2.musteri_id = %d GROUP BY bsib2.nastel_no, bsib2.musteri_id) AND bsib.musteri_id = %d
                    AND bsib.inventory > 0 GROUP BY bsib.musteri_id,bsib.nastel_no, ml.article, mv.name, ml.id, mv.id, cp.name_ru, cp.code LIMIT 100;";
            $sql = sprintf($sql, $musteri, $musteri);
        }
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        if($list){
            $out = [];
            $brand = Constants::$brandSAMO;
            $modelVar = [];
            foreach ($results as $result) {
                $qty = number_format($result['sum'],0,'.',' ');
                if($result['brand_type'] == 2){
                    $brand = $result['brand2'];
                }elseif ($result['brand_type'] == 3){
                    $brand = $result['brand3'];
                }
                array_push($modelVar, $result['model_var']);
                $out['data'][$result['nasel_no']] = "({$brand}) {$result['nastel_no']} ({$result['article']} - {$result['code']} {$result['pantone']}) (Jami: {$qty} dona)";
                $out['dataAttr'][$result['nastel_no']] = [
                    'data-model-id'      => $result['model_id'],
                    'data-model-var-id'  => $result['model_var_id'],
                    'data-model'         => $result['article'],
                    'data-model-var'     => join(',', $modelVar),
                    'data-nastel-no'     => $result['nastel_no'],
                    'data-order-id'      => $result['order_id'],
                    'data-order-item-id' => $result['order_item_id']
                ];
            }
            return $out;
        }
        return $results;
    }
    public function getDepartmentByToken($token, $isMultiple = false)
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

    public function getBarcodeCustomerList(){
        $bc = BarcodeCustomers::find()->asArray()->all();
        return ArrayHelper::map($bc,'id','name');
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
                $query->andFilterWhere(['OR', ['musteri_type_id' => $musteri_type],['id' => $id]]);
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
     * @return array
     */
    public function getSortTypeList(){
        $sl = SortName::find()->asArray()->all();
        return ArrayHelper::map($sl,'id','name');
    }

    private function getPackageVolume($gid, $type){
        $sum = 0;
        if($type > 1){
            $sql = "select (ROUND(IF(SUM(gi.quantity) IS NULL,1, SUM(gi.quantity))*
                                     IF(SUM(gi2.quantity) IS NULL,1, SUM(gi2.quantity))*
                                     IF(SUM(gi3.quantity) IS NULL,1, SUM(gi3.quantity))*
                                     IF(SUM(gi4.quantity) IS NULL,1, SUM(gi4.quantity)))) as volume 
                            from goods gin 
                            left join goods_items gi on gi.parent = gin.id
                            left join goods g2 on gi.child = g2.id
                            left join goods_items gi2 on gi2.parent = g2.id
                            left join goods g3 on gi2.child = g3.id
                            left join goods_items gi3 on gi3.parent = g3.id
                         left join goods g4 on gi3.child = g4.id
                         left join goods_items gi4 on gi4.parent = g4.id
                         where gin.id = %d GROUP BY gin.id, g2.id, g3.id, g4.id";
            $sql = sprintf($sql, $gid);
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($result as $item){
                $sum +=$item['volume'];
            }
        }
        if($sum > 0){
            return $sum;
        }
        return 1;
    }

    public function DocumentData($id)
    {
        $sql = "select tgdp.doc_number as docNumber,
                    tgdp.reg_date as regDate,
                    tgdp.id as packId, 
                    tgdp.nastel_no,
                    tgdp.to_department as to_department,
                    tgdp.department_id as from_department,
                    ml.name as gname,
                    ml.long_name as longName, 
                    tgd.quantity,
                    g.name as goodName,
                    g.goods_items as g_items,
                    0 as accepted,
                    g.size_collection as sizeCollection, 
                    g.color_collection as colorCollection, 
                    g.type,
                    g.id as gid,
                    mrd.price as price,
                    pb.code as currency,
                    pb.id as currency_id,
                    tgd.barcode,
                    g.barcode as mainBarcode, 
                    g.model_no,
                    s.name as sizeName,
                    s.code as sizeCode,
                    cp.code as colorCode,
                    cp.name as colorName,
                    tgd.weight,
                    u.name as unitName,
                    u.code as unitCode,
                    g.model_id as model_list_id,
                    tgdp.model_var_id as model_var_id,
                    ml.long_name,
                    b.token as manufacturerCode,
                    b.name as manufacturerName,
                    b.token as brandCode,
                    b.name as brandName,
                    ms.name as seasonName,
                    ms.code as seasonCode,
                    mv.name as genderName, 
                    mv.code as genderCode,
                    m.name  as customerName,
                    m.token as customerCode,
                    att.path,
                    sn.code as sortCode,
                    sn.name as sortName,
                    sn.id as sortId,
                    g.package_code as packageCode, 
                    mt.name as model_cat_name,
                    att.id as photoId from tikuv_goods_doc tgd
                        
                left join tikuv_goods_doc_pack tgdp on tgdp.id = tgd.tgdp_id
                left join sort_name sn on tgd.sort_type_id = sn.id          
                left join model_rel_doc mrd on mrd.model_list_id = tgdp.model_list_id
                left join model_orders mo on mrd.order_id = mo.id
                left join musteri m on mo.musteri_id = m.id          
                left join pul_birligi pb on mrd.pb_id = pb.id
                left join goods g on tgd.goods_id = g.id
                left join models_list ml on g.model_id = ml.id
                left join brend b on ml.brend_id = b.id
                left join model_view mv on ml.view_id = mv.id
                left join model_season ms on ml.model_season = ms.id
                left join model_rel_attach mra on ml.id = mra.model_list_id
                left join attachments att on mra.attachment_id = att.id
                left join size s on g.size = s.id
                left join color_pantone cp on g.color = cp.id
                left join unit u on tgd.unit_id = u.id
                left join model_types mt on ml.type_id = mt.id
                where tgdp.id = :id AND
                      mrd.model_var_id = tgdp.model_var_id AND
                      tgdp.order_id = mrd.order_id AND
                      tgdp.order_item_id = mrd.order_item_id
                GROUP BY g.id, tgd.sort_type_id;";
        $results = Yii::$app->db->createCommand($sql)->bindValue('id', $id)->queryAll();

        return $results;
    }

    public function sendToAPI($id){
        $results = self::DocumentData($id);
        $child = [];
        $model = [];
        $samo = Constants::$brandSAMO;
        $unitList = [
            1 => [
                'code' => 'DONA',
                'name' => 'dona'
            ],
            2 => [
                'code' => 'PAKET',
                'name' => 'paket'
            ],
            3 => [
                'code' => 'BLOK',
                'name' => 'blok'
            ],
            4 => [
                'code' => 'QOP',
                'name' => 'qop'
            ],
        ];
        foreach ($results as $result){
            $colorName = $result['colorName'];
            $colorCode = $result['colorCode'];
            if($result['type'] > 1){
                if(!empty($result['colorCollection'])){
                    $cc = trim($result['colorCollection']);
                    $explodeCC = preg_split('/\s+/', $cc);
                    if(isset($explodeCC) && !empty($explodeCC[0])){
                        $colorCode = $explodeCC[0];
                    }
                    if(isset($explodeCC) && !empty($explodeCC[1])){
                        $colorName = $explodeCC[1];
                    }
                    if(isset($explodeCC) && !empty($explodeCC[2])){
                        $colorName .= " ".$explodeCC[2];
                    }
                }
            }
            $model = [
                'docNumber' => $result['docNumber'],
                'regDate' => $result['regDate'],
                'department' => $result['to_department'],
                'comment' => '',
                'currency' => $result['currency'],
                'packId' => $result['packId'], //Document id by send document
                'customerName' => $result['customerName'],
                'customerCode' => $result['customerCode'],
            ];
            $child[] = [
                'gid' => $result['gid'],
                'model_no' => $result['model_no'],
                'gname' => $result['gname'],
                'quantity' => $result['quantity'],
                'volume' => $this->getPackageVolume($result['gid'], $result['type']),
                'accepted' => $result['accepted'],
                'type' => $result['type'],
                'sizeCollection' => $result['sizeCollection'],
                'colorCollection' => $result['colorCollection'],
                'price' => $result['price']*$this->getPackageVolume($result['gid'], $result['type']),
                'barcode' => !empty($result['barcode'])?$result['barcode']:$result['mainBarcode'],
                'packageCode' => $result['packageCode'],
                'weight' => $result['weight'],
                'sizeName' => $result['sizeName'],
                'sizeCode' => $result['sizeCode'],
                'colorName' => $colorName,
                'colorCode' => $colorCode,
                'sortName' => $result['sortName'],
                'sortCode' => $result['sortCode'],
                'unitCode' => $unitList[$result['type']]['code'],
                'unitName' => $unitList[$result['type']]['name'],
                'model_list_id' => $result['model_list_id'],
                'model_var_id' => $result['model_var_id'],
                'modelInfo' => [
                    'longName' => $result['longName'],
                    'brandCode' => $result['brandCode'],
                    'brandName' => $result['brandName'],
                    'manufacturerCode' => $samo,
                    'manufacturerName' => $samo,
                    'seasonName' => $result['seasonName'],
                    'seasonCode' => $result['seasonCode'],
                    'genderName' => $result['genderName'],
                    'genderCode' => $result['genderCode'],
                    'mainPhotoId' => $result['photoId'],
                    'photos' => 'http://'.$_SERVER['HTTP_HOST'].'/'.$result['path']
                ]
            ];
        }
        $model['documentItems'] = $child;
        $out = [
            'user' => Constants::$API_USER,
            'password' => Constants::$API_PASSWORD,
            'document' => $model
        ];

        $client = new Client();
        $url = Constants::PROD_POST_API_LOCAL;
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl("{$url}document-by-api/create")
            ->addHeaders(['content-type' => 'application/json'])
            ->setContent(json_encode($out))
            ->send();

        if ($response->isOk) {
            return true;
        }
        return false;
    }


    /**
     * Yuborilayotgan document yaratib berish tayyor mahsulotlar omboruchuni
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function makeWmsDocumentForTmo($id)
    {

        $results = self::DocumentData($id);
        $wmsDocument = [];
        if(!empty($results)){
            $newWmsDocument = new WmsDocument([
                'document_type' => WmsDocument::DOC_TYPE_PENDING,
                'doc_number' => $results[0]['docNumber'],
                'reg_date' => $results[0]['regDate'],
                'from_department' => $results[0]['from_department'],
                'to_department' => array_key_first(self::getDepartmentByToken($results[0]['to_department'])),
                'add_info' => 'Ko\'chirish qilindi',
                'tikuv_goods_doc_pack_id' => $id,
            ]);

            return $newWmsDocument->save();
           /* if($newWmsDocument->save()){
                return  self::makeWmsDocumentItemsForTmo($results,$newWmsDocument);
            }else{
                return false;
            }*/
        }
    }

    /**
     * @param $id
     * @return bool
     * Reject View da Mahsulotlarning barchasi qabul qilinganmi qaytaradi true or false
     */
    public static function isAllAccepted($id){
        return TikuvGoodsDocAcceptedByTmo::find()->where(['tgdp_id' => $id, 'type' => self::TYPE_CENCALLED,'status' => self::STATUS_ACTIVE])->exists();
    }

    /**
     * @param $tgdp_id
     * @param $tgd_id
     * @return bool
     * Bitta Tikuv Godd Doc ni item to'liq qabul qilinganmi shuni tekshiradi qaytaradi true or false
     */
    public static function isAccepted($tgdp_id,$tgd_id){

        return TikuvGoodsDocAcceptedByTmo::find()
            ->where([
            'tgdp_id' => $tgdp_id,
            'tgd_id' => $tgd_id,
            'type' => self::TYPE_CENCALLED,
            'status' => self::STATUS_ACTIVE])
            ->exists();
    }

    public function acceptItemFromTmo($getData){
        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try{
                $sql="
                SELECT 
                       tgdp.nastel_no,
                       tgd.goods_id,
                       tgd.sort_type_id,
                       tgd.package_type,
                       tgdabt.quantity
                    FROM tikuv_goods_doc_accepted_by_tmo tgdabt
                    LEFT JOIN tikuv_goods_doc_pack tgdp on tgdabt.tgdp_id = tgdp.id
                    LEFT JOIN tikuv_goods_doc tgd on tgdabt.tgd_id = tgd.id
                WHERE tgdabt.tgdp_id = %d AND tgdabt.tgd_id = %d AND tgdabt.type = %d
             ";
                $sql = sprintf($sql, $getData['tgdp_id'],$getData['tgd_id'], self::TYPE_CENCALLED);

            $query = Yii::$app->db->createCommand($sql)->queryOne();

            $findTikuvPackageItemBalance = TikuvPackageItemBalance::find()
                    ->where([
                        'goods_id' => $query['goods_id'],
                        'nastel_no' => $query['nastel_no'],
                        'sort_type_id' => $query['sort_type_id'],
                        'package_type' => $query['package_type'],
                        'dept_type' => 'TW'
                    ])->orderBy(['id' => SORT_DESC])->one();
                if(!empty($findTikuvPackageItemBalance)){
                    $newTikuvPackageItemBalance = new TikuvPackageItemBalance();
                    $newTikuvPackageItemBalance->attributes = $findTikuvPackageItemBalance->getAttributes();
                    $newTikuvPackageItemBalance['count'] = intval($query['quantity']);
                    $newTikuvPackageItemBalance['inventory'] = $newTikuvPackageItemBalance['inventory'] + $query['quantity'];
                    $newTikuvPackageItemBalance['doc_type'] = 1;


                    if($newTikuvPackageItemBalance->save()){
                        $saved = true;
                    }else{
                        $saved = false;
                    }
                }

            if($saved){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        }catch(\Exception $e){
            Yii::info('Not saved'.$e,'save');
            $transaction->rollBack();
        }
        return $saved;
    }



    public function getWorkCount()
    {
        $addQuery = "";
        $sql = "select
                       format(SUM(tgd.quantity), '0') count,
                       if( tgd.package_type=4,
                       g.name,
                       GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ')
                       ) size 
                from tikuv_goods_doc tgd
                left join goods g on tgd.goods_id = g.id
                left join size s on g.size=s.id
                WHERE 1=1 %s";
        $addQuery .= "AND tgd.tgdp_id = $this->id";
        $sql = sprintf($sql, $addQuery);
        $result = Yii::$app->db->createCommand($sql)->queryOne();
        return $result;
    }

}
