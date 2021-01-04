<?php

namespace app\modules\bichuv\models;

use app\models\Constants;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\Musteri;
use app\modules\hr\models\HrDepartments;
use app\modules\mobile\models\MobileDocDiffItems;
use app\modules\settings\models\Currency;
use app\modules\toquv\models\ToquvIp;
use app\modules\toquv\models\Unit;
use Faker\Provider\Base;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "bichuv_doc_items".
 *
 * @property int $id
 * @property int $bichuv_doc_id
 * @property int $entity_id
 * @property int $entity_type
 * @property int $quantity
 * @property int $document_quantity
 * @property string $price_sum
 * @property string $price_usd
 * @property string $current_usd
 * @property int $is_own
 * @property int $package_type
 * @property int $package_qty
 * @property string $lot
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $bichuv_mato_order_items_id
 *
 * @property null|mixed $threadName
 * @property array $currentPrice
 * @property BichuvDoc $bichuvDoc
 * @property BichuvMatoOrderItems $bichuvMatoOrderItems
 * @property Product $productModel
 * @property BichuvSubDocItems[] $bichuvSubDocItems
 * @property BichuvRollRecords[] $bichuvRollRecords
 * @property string $add_info [varchar(100)]
 * @property string $roll_count [decimal(5,2)]
 * @property int $is_accessory [smallint(2)]
 * @property int $work_weight [int(5)]
 * @property string $party_no [varchar(50)]
 * @property string $musteri_party_no [varchar(50)]
 * @property int $model_id [smallint(6)]
 * @property bool $is_remain [tinyint(3)]
 * @property int $bss_id [int(11)]
 * @property int $is_fixed [smallint(6)]
 * @property string $nastel_no [varchar(25)]
 * @property int $rm_model_id [smallint(6)]
 * @property int $brib_id [int(11)]
 * @property mixed $productModels
 * @property ActiveQuery $productModelRm
 * @property int $remainRoll
 * @property null $rMPockets
 * @property ActiveQuery $musteri
 * @property null|string $subItem
 * @property mixed $given
 * @property int $musteri_id [bigint(20)]
 * @property int $from_area [int]
 * @property int $to_area [int]
 * @property int $model_orders_items_id [int]
 */
class BichuvDocItems extends BaseModel
{
    public $remain;
    public $new_model_id;
    public $model_name;
    public $document_qty;
    public $remain_roll;
    public $name;
    public $given_qty;

    const SCENARIO_UPDATE_MOVING_NASTEL = 'update_kochirish_acs_with_nastel';
    const SCENARIO_ACCEPT_MATO = 'accept_mato';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_doc_items';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE_MOVING_NASTEL] = ['add_info', 'quantity'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_doc_id','rm_model_id','new_model_id', 'bss_id', 'is_fixed', 'is_remain', 'model_id', 'is_accessory', 'entity_id', 'work_weight', 'entity_type', 'is_own', 'package_type', 'package_qty', 'status', 'created_at', 'updated_at', 'created_by', 'bichuv_mato_order_items_id', 'brib_id', 'musteri_id'], 'integer'],
            [['quantity', 'entity_id'], 'required'],
            //[ 'price_usd', 'validatePrice', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['price_sum', 'price_usd'], 'required'],
            [['price_sum'], 'number', 'min' => 0.001, 'when' => function ($model) {
                return $model->price_usd < 0.001 && $model->bichuvDoc->document_type == 1;
            }],
            [['price_usd'], 'number', 'min' => 0.001, 'when' => function ($model) {
                return $model->price_sum < 0.001 && $model->bichuvDoc->document_type == 1;
            }],
            [['add_info'], 'required','on' => self::SCENARIO_ACCEPT_MATO, 'when' => function ($model) {
                return $model->quantity != $model->fact_quantity;
            }],
            [['current_usd', 'quantity','fact_quantity','document_quantity', 'remain', 'roll_count'], 'number'],
            [['lot', 'nastel_no'], 'string', 'max' => 25],
            [['add_info', 'party_no', 'musteri_party_no'], 'string', 'max' => 100],
            [['bichuv_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::className(), 'targetAttribute' => ['bichuv_doc_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['rm_model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['rm_model_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bichuv_doc_id' => 'Bichuv Doc ID',
            'entity_id' => 'Entity ID',
            'entity_type' => 'Entity Type',
            'work_weight' => Yii::t('app', 'Work Weight'),
            'quantity' => Yii::t('app', 'Quantity'),
            'document_quantity' => 'Document Quantity',
            'price_sum' => 'Price Sum',
            'price_usd' => 'Price Usd',
            'current_usd' => 'Current Usd',
            'is_own' => 'Is Own',
            'package_type' => 'Package Type',
            'package_qty' => 'Package Qty',
            'fact_quantity' => 'Qabul(mato)',
            'lot' => 'Lot',
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'bichuv_mato_order_items_id' => Yii::t('app', 'Bichuv Mato Order Items ID'),
            'brib_id' => Yii::t('app', 'Mato'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvDoc()
    {
        return $this->hasOne(BichuvDoc::className(), ['id' => 'bichuv_doc_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductModel()
    {
        return $this->hasOne(Product::className(), ['id' => 'model_id']);
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
    public function getProductModelRm()
    {
        return $this->hasOne(Product::className(), ['id' => 'rm_model_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getBichuvMatoOrderItems()
    {
        return $this->hasOne(BichuvMatoOrderItems::className(), ['id' => 'bichuv_mato_order_items_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvSubDocItems()
    {
        return $this->hasMany(BichuvSubDocItems::className(), ['doc_item_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBichuvRollRecords()
    {
        return $this->hasMany(BichuvRollRecords::className(), ['doc_item_id' => 'id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->price_usd = (!empty($this->price_usd) ? $this->price_usd : 0.000);
            $this->price_sum = (!empty($this->price_sum) ? $this->price_sum : 0.000);
            $this->current_usd = Currency::getCurrency();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public static function getModelName()
    {
        return StringHelper::basename(get_class(new self()));
    }

    /**
     * @return mixed|null
     */
    public function getThreadName()
    {
        $thread = ToquvIp::find()->where(['id' => $this->entity_id, 'status' => self::STATUS_ACTIVE])->asArray()->one();
        if (!empty($thread)) {
            return $thread['name'];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getCurrentPrice()
    {
        $result = [
            'value' => 0,
            'symbol' => '$'
        ];
        if (!empty($this->price_sum) && (int)$this->price_sum > 0) {
            $result = [
                'value' => $this->price_sum,
                'symbol' => 'UZS'
            ];
        } else {
            $result['value'] = $this->price_usd;
        }
        return $result;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getPackageTypes($key = null)
    {
        $result = [
            1 => Yii::t('app', 'Qop'),
            2 => Yii::t('app', 'Karopka'),
            3 => Yii::t('app', 'Polet')
        ];
        if (!empty($key)) {
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param null $id
     * @return array|string|null
     */
    public function getUnits($id = null)
    {
        if (!empty($key)) {
            $unit = Unit::findOne($id);
            if ($unit !== null) {
                return $unit->name;
            }
        } else {
            $units = Unit::find()->asArray()->all();
            if (!empty($units)) {
                return ArrayHelper::map($units, 'id', 'name');
            }
            return null;
        }

    }

    /**
     * @param null $id
     * @param bool $all
     * @return array|string|null
     * @throws Exception
     */
    public function getAccessories($id = null, $all = false)
    {
        if (!empty($id)) {
            $sql = "select accs.sku, accs.name, bap.name as property 
                    from bichuv_acs accs left join bichuv_acs_property bap on accs.property_id = bap.id 
                    where accs.id = :id limit 1";
            $accs = Yii::$app->db->createCommand($sql)->bindValues(['id' => $id])->queryOne();
            if ($accs) {
                return $accs['sku'] . ' - ' . $accs['name'] . ' - ' . $accs['property'];
            }
        } else {
            if ($all) {
                $sql = "select accs.id, accs.sku, accs.name, bap.name as property from bichuv_acs accs 
                        left join bichuv_acs_property bap on accs.property_id = bap.id ORDER BY accs.sku ASC LIMIT 10000;";
            } else {
                $sql = "select accs.id, accs.sku, accs.name, bap.name as property from bichuv_acs accs 
                    left join bichuv_acs_property bap on accs.property_id = bap.id
                    left join bichuv_item_balance bib on bib.entity_id = accs.id
                    where accs.status = 1 AND bib.inventory > 0 AND bib.id IN (select MAX(bib2.id) from bichuv_item_balance bib2 where bib2.entity_id = accs.id) limit 1000";
            }
            $iplar = Yii::$app->db->createCommand($sql)->queryAll();
            if (!empty($iplar)) {
                $result = [];
                foreach ($iplar as $ip) {
                    $result['data'][$ip['id']] = $ip['sku'] . ' - ' . $ip['name'] . ' - ' . $ip['property'];
                    $result['barcodeAttr'][$ip['id']] = ['data-barcode' => $ip['barcode']];
                }
                return $result;
            }
            return null;
        }
    }

    /**
     * @return array
     * @throws Exception
     */


    public function getRollInfo($id)
    {
        $sql = "select bmi.id,
                       bdi.is_accessory,
                       bdi.quantity as rulon_kg,
                       rm.name as mato,
                       nename.name as ne,
                       thr.name as ip,
                       pf.name as pus_fine,
                       c.color_id,
                       ct.name as ctone,
                       c.pantone,
                       bmi.en as mato_en,
                       bmi.gramaj,
                       bdi.id as roll_order
                from bichuv_doc_items bdi
                            left join bichuv_mato_info bmi on bmi.id = bdi.entity_id
                            left join raw_material rm on bmi.rm_id = rm.id
                            left join ne nename on bmi.ne_id = nename.id
                            left join pus_fine pf on bmi.pus_fine_id = pf.id
                            left join thread thr on bmi.thread_id = thr.id
                            left join color c on bmi.color_id = c.id
                            left join color_tone ct on c.color_tone = ct.id
                            left join bichuv_doc bd on bdi.bichuv_doc_id = bd.id
                WHERE bdi.entity_id = :id AND bd.status = 3 AND bd.document_type = 1;";

        $res = Yii::$app->db->createCommand($sql)->bindValues(['id' => $id])->queryOne();
        if ($res) {
            $color = "{$res['ctone']} {$res['color_id']} {$res['pantone']}";
            $gr = "<b>{$res['mato_en']}</b> sm | <b>{$res['gramaj']}</b> gr/m <sup>2</sup>";
            return "<b>{$res['mato']}</b>-{$res['ne']}-{$res['ip']}|{$res['pus_fine']} -{$color} - {$gr}";
        }
        return null;
    }

    /**
     * @param $entity_id
     * @return array|bool|false
     * @throws Exception
     */
    public function getMatoInfoByEntityId($entity_id)
    {
        $sql = "select bsdi.*
                from bichuv_sub_doc_items bsdi
                            left join bichuv_doc_items bdi on bdi.id = bsdi.doc_item_id
                            left join bichuv_doc bd on bdi.bichuv_doc_id = bd.id
                WHERE bdi.entity_id = :id AND bd.status = 3 AND bd.document_type = 1;";
        $res = Yii::$app->db->createCommand($sql)->bindValue('id', $entity_id)->queryOne();
        if (!empty($res)) {
            return $res;
        }
        return false;
    }

    public function getRMPockets()
    {
        return null;
    }

    /**
     * @param int $type
     * @return float|int
     */
    public function getSum($type = 1)
    {

        if ($type == 2) {
            $this->price_usd = $this->price_usd ? $this->price_usd : 0;
            return $this->price_usd * $this->quantity;
        } else {
            $this->price_sum = $this->price_sum ? $this->price_sum : 0;
            return $this->price_sum * $this->quantity;
        }

    }

    /**
     * @param $provider
     * @param array $fields
     * @param bool $calcSeparately
     * @return int
     */
    public static function getTotal($provider, $fields = [], $calcSeparately = false)
    {
        $total = 0;
        if (!empty($fields)) {
            foreach ($fields as $fieldName) {
                foreach ($provider as $item) {
                    $total += $item[$fieldName];
                }
            }
        }
        return $total;
    }

    /**
     * @param $provider
     * @param array $fields
     * @param bool $calcSeparately
     * @return int
     */
    public static function getTotalPrice($provider, $fields = [], $calcSeparately = false)
    {
        $total = 0;

        if ($calcSeparately) {
            foreach ($provider as $item) {
                $priceSUM = $item[$fields[0]];
                if (!empty($priceSUM) && $priceSUM > 0) {
                    $total += $priceSUM;
                }
            }

        } else {
            if (!empty($fields)) {
                foreach ($provider as $item) {
                    $priceSUM = $item[$fields[0]];
                    if (!empty($priceSUM) && $priceSUM > 0) {
                        $total += $priceSUM;
                    } else {
                        $priceUSD = $item[$fields[1]];
                        $total += $priceUSD;
                    }
                }
            }
        }
        return $total;
    }

    /**
     * @param $provider
     * @param array $fields
     * @return float|int
     */
    public static function getTotalSum($provider, $fields = [])
    {
        $total = 0;
        foreach ($provider as $item) {
            $priceSUM = $item[$fields[0]];
            if (!empty($priceSUM) && $priceSUM > 0) {
                $total += $priceSUM * $item[$fields[1]];
            }

        }
        return $total;
    }

    /**
     * @return string|null
     */
    public function getSubItem()
    {
        $sub = BichuvSubDocItems::find()->select(['en', 'gramaj', 'party_no'])->where(['id' => $this->entity_id])->asArray()->one();
        if ($sub) {
            return $sub;
        }
        return null;
    }

    public function getRemainRoll()
    {
        $sql = "select (select SUM(brr.quantity)
                            from bichuv_roll_records brr
                            left join bichuv_doc_items bdi on brr.doc_item_id = bdi.id
                            left join bichuv_doc bd on bdi.bichuv_doc_id = bd.id
                            where bd.status = 3 AND brr.bichuv_sub_doc_id = :id) 
                    as rasxod,
                    bsdi.roll_weight
                    from bichuv_sub_doc_items bsdi
                    where bsdi.id = :id;";
        $res = Yii::$app->db->createCommand($sql)->bindValue('id', $this->entity_id)->queryOne();
        if ($res) {
            $diff = $res['roll_weight'];
            if (!empty($res['rasxod'])) {
                $diff = $res['roll_weight'] - $res['rasxod'];
            }
            return $diff;
        }
        return 0;
    }

    public function getRemainFromItemBalance($type = 'roll')
    {
        $ib = BichuvRmItemBalance::find()->where(['entity_id' => $this->entity_id,'department_id'=>$this->bichuvDoc->from_department])->asArray()->orderBy(['id' => SORT_DESC])->one();
        if (!empty($ib)) {
            $result = 0;
            switch ($type) {
                case 'roll':
                    $result = $ib['roll_inventory'];
                    break;
                case 'quantity':
                    $result = $ib['inventory'];
                    break;
            }
            return $result;
        }
        return null;
    }
    public function getRemainFromMusteriItemBalance($type = 'roll')
    {
        $musteri_id = $this->musteri_id ?? $this->bichuvDoc->musteri_id;
        $ib = BichuvRmItemBalance::find()->where(['entity_id' => $this->entity_id,'department_id'=>$this->bichuvDoc->from_department,'from_musteri'=>$musteri_id])->asArray()->orderBy(['id' => SORT_DESC])->one();
        if (!empty($ib)) {
            $result = 0;
            switch ($type) {
                case 'roll':
                    $result = $ib['roll_inventory'];
                    break;
                case 'quantity':
                    $result = $ib['inventory'];
                    break;
            }
            return $result;
        }
        return null;
    }
    public function getMatoEnGramaj($entityId)
    {
        $m = BichuvMatoInfo::find()->where(['id' => $entityId])->asArray()->one();
        if (!empty($m)) {
            return $m;
        }
        return false;
    }

    public function getProductModels()
    {
        $m = Product::find()->asArray()->orderBy(['name' => SORT_ASC])->all();
        return ArrayHelper::map($m, 'id', 'name');
    }

    /**
     * @param $musteriId
     * @param $musteriPartyNo
     * @param $docId
     * @return bool
     */
    public static function getMusteriPartyNo($musteriId, $musteriPartyNo, $docId){
        $result = BichuvDocItems::find()
            ->leftJoin('bichuv_doc','bichuv_doc.id = bichuv_doc_items.bichuv_doc_id')
            ->andFilterWhere([
                'bichuv_doc.musteri_id' => $musteriId,
                'bichuv_doc_items.musteri_party_no' => $musteriPartyNo,
                ])
            ->andFilterWhere(['<>','bichuv_doc.id', $docId])
            ->asArray()
            ->exists();
        return $result;
    }

    public function getGiven()
    {
        $given = BichuvDocItems::find()->joinWith('bichuvDoc bd')->where(['bichuv_mato_order_items_id'=>$this->bichuv_mato_order_items_id,'bd.document_type'=>BichuvDoc::DOC_TYPE_MOVING,'bd.from_department'=>$this->bichuvDoc->from_department,'bd.to_department'=>$this->bichuvDoc->to_department])->andFilterWhere(['>','bd.status',BichuvDoc::STATUS_INACTIVE])->sum('quantity');
        return $given;
    }


    /**
     * @param null $id
     * @return array
     * Model order item id berilsa unga tegishli malumotlarni qaytaradi
     *
     */
    public static function getOrderDataByModelOrdersItemsId($id = null){
        $result =  [];
        if(!empty($id)){
            $data = ModelOrdersItems::find()
                ->alias('moi')
                ->select([
                    'mo.id as model_orders_id',
                    'ml.id as models_list_id',
                    'ml.article',
                    'ml.name as model_name',
                    'mv.id model_var_id',
                    'moi.price',
                    'moi.pb_id'
                    ])
                ->leftJoin(['mo' => 'model_orders'], 'moi.model_orders_id = mo.id')
                ->leftJoin(['ml' => 'models_list'],'moi.models_list_id = ml.id')
                ->leftJoin(['mv' => 'models_variations'],'moi.model_var_id = mv.id')
                ->where(['moi.id' => $id])
                ->asArray()
                ->one();
            if (!empty($data)){
                $result['data'] = $data;
            }
        }
        return $result;
    }

    public static function saveBichuvDocItemsDiff($model,$data){

        $saved = true;
        $models = $model->bichuvDocItems;
        if (!empty($models)){
            foreach ($models as $key => $item){
                $diff_quantity = $data[$key]['fact_quantity'] - $item['quantity'];
                $unit_id = Unit::findOne(['code' => 'KG'])->id;
                if ($diff_quantity != 0 && $item['id'] == $data[$key]['id']){
                    $params = [
                        'doc_items_id' => $item['id'],
                        'table_name' => self::getTableSchema()->name,
                        'diff_qty' => $diff_quantity,
                        'add_info' => $data[$key]['add_info'],
                        'unit_id' => $unit_id,
                        'department_id' => $model['to_hr_department']
                    ];
                    $saved = MobileDocDiffItems::saveMobileDocDiffItems($params);
                    if (!$saved){
                        return $saved;
                    }
                }
            }
        }else{
            return false;
        }
       return $saved;
    }  



}
