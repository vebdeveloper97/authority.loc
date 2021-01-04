<?php

namespace app\modules\toquv\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "toquv_document_items".
 *
 * @property int $id
 * @property int $toquv_document_id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $quantity
 * @property string $price_sum
 * @property string $price_usd
 * @property string $current_usd
 * @property int $is_own
 * @property int $package_type
 * @property int $package_qty
 * @property string $lot
 * @property string $party
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $unit_id
 * @property string $document_qty
 * @property int $tib_id
 * @property int $price_item_id
 * @property string $roll_count
 * @property double $count
 * @property string $add_info
 *
 * @property ToquvDocItemsRelOrder[] $toquvDocItemsRelOrders
 * @property ToquvDocSubItems[] $toquvDocSubItems
 * @property ToquvPricingItem $priceItem
 * @property ToquvDocuments $toquvDocument
 * @property Unit $unit
 * @property int $bss_id [int(11)]
 */
class ToquvDocumentItems extends BaseModel
{
    public $fact;
    public $toquv_orders_id;
    public $toquv_rm_order_id;
    public $order_id;
    public $order_item_id;
    public $remain_count;
    public $remain_roll;
    public $remain;
    public $documentQty;
    public $toquv_instruction_items_id;
    public $color;
    public $b_color;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_document_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity','entity_id'],'required','when' => function($model){
                return $model->toquvDocument->document_type == 2;
            } ],
            [['quantity','document_qty','price_sum','price_usd','package_qty','entity_id','lot'],'required','when' => function($model){
                return $model->toquvDocument->document_type == 1  && $model->toquvDocument->entity_type == 1;
            } ],
            [['price_usd'], 'number', 'min' => 0.01, 'when' => function($model) {
                $slug = Yii::$app->request->get('slug');
                return $model->price_sum <= 0.01 && $model->toquvDocument->document_type == 1 && $slug !== 'kirim_mato' && $model->entity_type != ToquvDocuments::ENTITY_TYPE_MATO && $model->entity_type != ToquvDocuments::ENTITY_TYPE_ACS;
            }],
            [['price_sum'], 'number', 'min' => 0.01, 'when' => function($model) {
                $slug = Yii::$app->request->get('slug');
                return $model->price_usd <= 0.01 && $model->toquvDocument->document_type == 1 && $slug !== 'kirim_mato' && $slug !== 'chiqim_aksessuar' && $model->entity_type != ToquvDocuments::ENTITY_TYPE_MATO && $model->entity_type != ToquvDocuments::ENTITY_TYPE_ACS && $model->entity_type == self::ENTITY_TYPE_IP;
            }],
            [['toquv_document_id','bss_id', 'entity_id', 'entity_type', 'is_own', 'package_type', 'package_qty', 'status', 'created_at', 'updated_at', 'created_by', 'unit_id', 'tib_id', 'price_item_id'], 'integer'],
            [['quantity', 'price_sum', 'price_usd', 'current_usd', 'document_qty', 'fact', 'roll_count', 'count'], 'number'],
            [['add_info'], 'string'],
            [['quantity','remain'], 'number', 'min' => 0],
            [['lot'], 'string', 'max' => 25],
            [['party'], 'string', 'max' => 100],
            [['price_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvPricingItem::className(), 'targetAttribute' => ['price_item_id' => 'id']],
            [['toquv_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDocuments::className(), 'targetAttribute' => ['toquv_document_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_document_id' => Yii::t('app', 'Toquv Document ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'price_sum' => Yii::t('app', 'Price Sum'),
            'price_usd' => Yii::t('app', 'Price Usd'),
            'current_usd' => Yii::t('app', 'Current Usd'),
            'is_own' => Yii::t('app', 'Is Own'),
            'package_type' => Yii::t('app', 'Package Type'),
            'package_qty' => Yii::t('app', 'Package Qty'),
            'lot' => Yii::t('app', 'Lot'),
            'party' => Yii::t('app', 'Party'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'document_qty' => Yii::t('app', 'Document Qty'),
            'tib_id' => Yii::t('app', 'Tib ID'),
            'price_item_id' => Yii::t('app', 'Price Item ID'),
            'roll_count' => Yii::t('app', 'Roll Count'),
            'count' => Yii::t('app', 'Count'),
            'add_info' => Yii::t('app', 'Add Info'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocItemsRelOrders()
    {
        return $this->hasMany(ToquvDocItemsRelOrder::className(), ['toquv_document_items_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocSubItems()
    {
        return $this->hasMany(ToquvDocSubItems::className(), ['doc_item_id' => 'id']);
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public static function getMusteri($id)
    {
        $model = ToquvDocumentItems::findOne($id);
        $musteri = $model->toquvDocItemsRelOrders[0]->toquvOrders['musteri_id'];
        if($musteri){
            return $musteri;
        }
        return false;
    }

    public function getTib()
    {
        $data = ($this->entity_type == ToquvDocuments::ENTITY_TYPE_IP)?ToquvItemBalance::findOne($this->tib_id):ToquvMatoItemBalance::findOne($this->tib_id);
        $lastEntity = [
            'entity_id' => $data['entity_id'],
            'entity_type' => $data['entity_type'],
            'lot' => $data['lot'],
            'department_id' => $data['department_id'],
            'is_own' => $data['is_own'],
            'musteri_id' => $data['musteri_id']
        ];
        $tib = ($this->entity_type == ToquvDocuments::ENTITY_TYPE_IP)?ToquvItemBalance::find():ToquvMatoItemBalance::find();
        $tib = $tib->where($lastEntity)->orderBy(['id'=>SORT_DESC])->one();
        if($tib){
            return $tib;
        }
        return false;
    }
    public function getTibMato()
    {
        $data = ToquvMatoItemBalance::findOne($this->tib_id);
        $lastEntity = [
            'entity_id' => $data['entity_id'],
            'entity_type' => $data['entity_type'],
            'lot' => $data['lot'],
            'department_id' => $data['department_id'],
            'is_own' => $data['is_own'],
            'musteri_id' => $data['musteri_id']
        ];
        $tib = ToquvMatoItemBalance::find()->where($lastEntity)->orderBy(['id'=>SORT_DESC])->one();
        if($tib){
            return $tib;
        }
        return false;
    }
    public function getTir(){
        return $this->hasOne(MatoInfo::className(), ['id' => 'entity_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMato()
    {
        return $this->tir->entity;
    }
    public function getMatoInfo()
    {
        $thread_length = Yii::t('app', 'Thread Length');
        $finish_en = Yii::t('app', 'Finish En');
        $finish_gramaj = Yii::t('app', 'Finish Gramaj');
        return "{$this->mato->name} ({$this->tir->musteri->name} - {$this->tir->toquvRmOrder->quantity} kg) ({$thread_length} - {$this->tir['thread_length']}, {$finish_en} - {$this->tir['finish_en']}, {$finish_gramaj} - {$this->tir['finish_gramaj']}";
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceItem()
    {
        return $this->hasOne(ToquvPricingItem::className(), ['id' => 'price_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocument()
    {
        return $this->hasOne(ToquvDocuments::className(), ['id' => 'toquv_document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
       if(parent::beforeSave($insert)){
           $this->price_usd = (!empty($this->price_usd)?$this->price_usd:0.00);
           $this->price_sum = (!empty($this->price_sum)?$this->price_sum:0.00);
           return true;
       }else{
           return false;
       }
    }

    /**
     * @return string
     */
    public static function getModelName(){
        return StringHelper::basename(get_class(new self()));
    }

    /**
     * @return mixed|null
     */
    public function getThreadName($isFull = false){
       $thread = ToquvIp::find()->where(['id'=> $this->entity_id, 'status' => self::STATUS_ACTIVE])->one();
       if(!empty($thread)){
           if($isFull){
               return "{$thread->name}-{$thread->ne->name}-{$thread->thread->name}-{$thread->color->name}";
           }
           return $thread['name'];
       }
       return null;
    }
    /**
     * @return array
     */
    public function getCurrentPrice(){
        $result = [
            'value'    => 0,
            'currency' => '$'
        ];
        if(!empty($this->price_sum) && (int)$this->price_sum > 0){
            $result = [
                'value' => $this->price_sum,
                'currency' => 'UZS'
            ];
        }else{
            $result['value'] = $this->price_usd;
        }
        return $result;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getPackageTypes($key = null){
        $result = [
            1 => Yii::t('app','Qop'),
            2 => Yii::t('app','Karopka'),
            3 => Yii::t('app','Polet')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param null $id
     * @return array|string|null
     */
    public function getUnits($id = null){
        if(!empty($key)){
            $unit = Unit::findOne($id);
            if($unit !== null){
                return $unit->name;
            }
        }else{
            $units = Unit::find()->asArray()->all();
            if(!empty($units)){
                return ArrayHelper::map($units,'id','name');
            }
            return null;
        }

    }

    /**
     * @param null $id
     * @return array|null
     */
    public function getIplar($id = null){
        if(!empty($id)){
            $ip = ToquvIp::find()->with(['ne','thread','color'])->where(['id' => $id, 'status' => ToquvIp::STATUS_ACTIVE])->asArray()->one();
            if(!empty($ip)){
                return $ip['name'].' - '.$ip['ne']['name'].' - '.$ip['thread']['name'].' - '.$ip['color']['name'];
            }
        }else{
            $sql = sprintf("SELECT ip.id, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname  from toquv_ip  as ip
                    LEFT JOIN toquv_ne as ne ON ip.ne_id = ne.id
                    LEFT JOIN toquv_thread as thr ON ip.thread_id = thr.id
                    LEFT JOIN toquv_ip_color as cl ON ip.color_id = cl.id
                    WHERE ip.status = 1");
            $iplar = Yii::$app->db->createCommand($sql)->queryAll();
            if(!empty($iplar)){
                $result = [];
                foreach ($iplar as $ip){
                    $result[$ip['id']] = $ip['ipname'].' - '.$ip['nename'].' - '.$ip['thrname'].' - '.$ip['clname'];
                }
                return $result;
            }
            return null;
        }
    }

    /**
     * @return float|int
     */
    public function getSum(){
        $price = $this->getCurrentPrice();
        return $price['value']*$this->quantity;
    }



    /**
     * @param $provider
     * @param array $fields
     * @param bool $calcSeparately
     * @return int
     */
    public static function getTotal($provider, $fields = [], $calcSeparately = false){
        $total = 0;
        if(!empty($fields)){
            foreach ($fields as $fieldName){
                foreach ($provider as $item){
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
    public static function getTotalPrice($provider, $fields = [], $calcSeparately = false){
        $total = 0;
        if(!empty($fields)){
            foreach ($provider as $item){
                $priceSUM = $item[$fields[0]];
                if(!empty($priceSUM) && $priceSUM > 0){
                    $total += $priceSUM;
                }else{
                    $priceUSD = $item[$fields[1]];
                    $total += $priceUSD;
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
    public static function getTotalSum($provider, $fields = []){
       $total = 0;
        foreach ($provider as $item){
             $priceSUM = $item[$fields[0]];
             if(!empty($priceSUM) && $priceSUM > 0){
                 $total += $priceSUM*$item[$fields[2]];
             }else{
                 $priceUSD = $item[$fields[1]]*$item[$fields[2]];
                 $total += $priceUSD;
             }
        }
        return $total;
    }

    /**
     * @param $entityId
     * @param $fromDepartment
     * @return array|false
     * @throws \yii\db\Exception
     */
    public function getIplarFromItemBalance($entityId, $fromDepartment){

        $sql = "SELECT  t1.lot, t1.inventory, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.ne_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
                    WHERE (reg_date BETWEEN '%s' AND '%s') AND (entity_type=1) AND (department_id=%d) AND (t1.id=%d) 
                    LIMIT 1";
        $sql = sprintf($sql,
            date('Y-m-d', strtotime('2019-01-01')),
            date('Y-m-d', strtotime('tomorrow')),
            $fromDepartment,
            $entityId );
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function getColor($type='color')
    {
        $sql = "SELECT cp.code c_pantone,cp.name c_name,cp.r,cp.g,cp.b,c.pantone b_pantone,c.color_id,c.name b_name,c.color b_color
                FROM mato_info mi
                LEFT JOIN toquv_rm_order tro on mi.toquv_rm_order_id = tro.id
                LEFT JOIN color c on tro.color_id = c.id
                LEFT JOIN color_pantone cp on tro.color_pantone_id = cp.id
                WHERE mi.id = %d
        ";
        $sql = sprintf($sql,$this->entity_id);
        $item = Yii::$app->db->createCommand($sql)->queryOne();
        $res = [];
        $res['color'] = "<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> ".$item['c_pantone'];
        $res['b_color'] = " <span style='background:{$item['b_color']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> {$item['color_id']}";
        return $res[$type];
    }
}
