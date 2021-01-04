<?php

namespace app\modules\tikuv\models;

use app\models\Constants;
use app\modules\base\models\BarcodeCustomers;
use app\modules\base\models\GoodsBarcode;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\bichuv\models\BichuvMusteri;
use app\modules\usluga\models\UslugaDoc;
use Yii;
use app\models\Users;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use app\modules\base\models\Musteri;
use app\modules\bichuv\models\Product;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\boyoq\models\BoyahaneSiparisPart;

/**
 * This is the model class for table "tikuv_outcome_products_pack".
 *
 * @property int $id
 * @property string $order_no
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $department_id
 * @property string $toquv_partiya
 * @property string $boyoq_partiya
 * @property string $nastel_no
 * @property int $musteri_id
 * @property string $reg_date
 * @property int $doc_id
 * @property int $order_item_id
 *
 * @property TikuvOutcomeProducts[] $tikuvOutcomeProducts
 * @property ToquvDepartments $department
 * @property BarcodeCustomers $barcodeCustomer
 * @property ToquvDepartments $toDepartment
 * @property TikuvSliceItemBalance $tikuvSliceItemBalance
 * @property Musteri $musteri
 * @property Product $productModel
 * @property ModelsList $modelList
 * @property ModelOrders $orderId
 * @property ModelOrdersItems $orderItem
 * @property ModelsVariations $modelVar
 * @property ModelsVariations[] $modelVars
 * @property int $model_id [smallint(6)]
 * @property int $tikuv_slice_item_balance_id [int(11)]
 * @property int $model_list_id [int(11)]
 * @property int $model_var_id [int(11)]
 * @property int $order_id [int(11)]
 * @property int $to_department [int(11)]
 * @property int $type [smallint(6)]
 * @property int $bsib_id [int(11)]
 * @property int $barcode_customer_id [int(11)]
 * @property int $from_musteri [bigint(20)]
 * @property mixed $order
 * @property ActiveQuery $user
 * @property array $modelListInfo
 * @property array $nastelUsluga
 * @property null|array $departmentsBelongTo
 * @property mixed $modelOrder
 * @property array $users
 * @property array $musteris
 * @property mixed $boyahaneParti
 * @property array $orderItemList
 * @property mixed $boyoqList
 * @property ActiveQuery $fromMusteri
 * @property ActiveQuery $toMusteri
 * @property array $nastelList
 * @property int $to_musteri [bigint(20)]
 */
class TikuvOutcomeProductsPack extends BaseModel
{
    public $username;
    public $model_var;
    public $nastel_model_var;
    public $list;
    public $nastel_list;
    const TYPE_TIKUV = 1;
    const TYPE_USLUGA = 2;
    const TYPE_FROM_MUSTERI = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_outcome_products_pack';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['add_info'], 'string'],
            [['nastel_no', 'department_id', 'musteri_id','barcode_customer_id'], 'required'],
            [['nastel_no', 'department_id', 'musteri_id'], 'required'],
            [['status', 'doc_id', 'order_id','barcode_customer_id','model_var_id','to_department', 'tikuv_slice_item_balance_id', 'created_by', 'model_id', 'created_at', 'updated_at', 'department_id', 'musteri_id', 'order_item_id', 'model_list_id','type','bsib_id', 'from_musteri', 'to_musteri'], 'integer'],
            [['reg_date','nastel_model_var'], 'safe'],
            [['order_no'], 'string', 'max' => 100],
            [['toquv_partiya', 'boyoq_partiya', 'nastel_no'], 'string', 'max' => 20],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['order_item_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['tikuv_slice_item_balance_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvSliceItemBalance::className(), 'targetAttribute' => ['tikuv_slice_item_balance_id' => 'id']],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
            [['barcode_customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => BarcodeCustomers::className(), 'targetAttribute' => ['barcode_customer_id' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->reg_date = date('Y-m-d H:i:s');
            return true;
        } else {
            return false;
        }
        $modelInfo = $this->getModelListInfo();
        if(empty($this->order_id)||$this->order_id==null){
            $this->order_id = $modelInfo['order_id'];
        }
        if(empty($this->order_item_id)||$this->order_item_id==null){
            $this->order_item_id = $modelInfo['order_item_id'];
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->status == self::STATUS_SAVED&&$this->type==self::TYPE_TIKUV) {
            $sql = "select tsib.inventory from tikuv_slice_item_balance tsib where tsib.id
                    IN (select MAX(tsib2.id) from tikuv_slice_item_balance tsib2 
                            where tsib2.nastel_no = '%s' GROUP BY tsib2.size_id) AND tsib.inventory > 0;";
            $sql= sprintf($sql, $this->nastel_no);
            $tikuvBalanceItems = Yii::$app->db->createCommand($sql)->queryScalar();
            if (!$tikuvBalanceItems) {
                $tdi = TikuvDocItems::find()->select(['tikuv_doc_id'])->where(['nastel_party_no' => $this->nastel_no])->asArray()->one();
                if (!empty($tdi)) {
                    $modelMRD = ModelRelDoc::find()->where([
                        'tikuv_doc_id' => $tdi['tikuv_doc_id'],
                        'model_list_id' => $this->model_list_id
                    ])->all();
                    if ($modelMRD !== null) {
                        foreach ($modelMRD as $item) {
                            $item->status = 3;
                            $item->save();
                        }
                    }
                }
            }
        }
    }
    public function afterFind()
    {
        parent::afterFind();
        $modelInfo = $this->getModelListInfo();
        if(empty($this->order_id)||$this->order_id==null){
            $this->order_id = $modelInfo['order_id'];
        }
        if(empty($this->order_item_id)||$this->order_item_id==null){
            $this->order_item_id = $modelInfo['order_item_id'];
        }
        if(!empty($this->from_musteri)){
            $child = TikuvOutcomeProducts::find()->select('nastel_no')->where(['pack_id'=>$this->id])->andWhere(['IS NOT','nastel_no',new Expression('NULL')])->asArray()->groupBy('nastel_no')->all();
            if(!empty($child)){
                $this->nastel_list = ArrayHelper::getColumn($child,'nastel_no');
            }
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_no' => Yii::t('app', 'Order No'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'department_id' => Yii::t('app', 'Qayerdan'),
            'to_department' => Yii::t('app', 'Qayerga'),
            'model_id' => Yii::t('app', 'Model ID'),
            'toquv_partiya' => Yii::t('app', 'Toquv Partiya'),
            'barcode_customer_id' => Yii::t('app', 'Buyurtmachi(barkod)'),
            'boyoq_partiya' => Yii::t('app', 'Boyoq Partiya'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'username' => Yii::t('app', 'User'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'order_item_id' => Yii::t('app', 'Buyurtma haqida ma\'lumotlar'),
            'type' => Yii::t('app', 'Turi'),
            'from_musteri' => Yii::t('app', 'Kasanachi'),
            'to_musteri' => Yii::t('app', 'Kimga'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvOutcomeProducts()
    {
        return $this->hasMany(TikuvOutcomeProducts::className(), ['pack_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarcodeCustomer()
    {
        return $this->hasOne(BarcodeCustomers::className(), ['id' => 'barcode_customer_id']);
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
    public function getOrderId()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'order_id']);
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
    public function getTikuvSliceItemBalance()
    {
        return $this->hasOne(TikuvSliceItemBalance::className(), ['id' => 'tikuv_slice_item_balance_id']);
    }

    public function getBoyahaneParti()
    {
        return $this->hasOne(BoyahaneSiparisPart::className(), ['id' => 'boyoq_partiya']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }

    public function getModelOrder()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'order_no']);
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
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }

    public function getModelVars()
    {
        return $this->hasMany(ModelsVariations::className(), ['tikuv_outcome_products_pack_id' => 'id']);
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
     * @param bool $list
     * @return array
     */
    public function getBarcodeCustomerList($list = false){
        if($list){
            $bc = BarcodeCustomers::find()->asArray()->all();
            return ArrayHelper::map($bc,'id','name');
        }else{
            $bc = BarcodeCustomers::find()->where(['id' => $this->barcode_customer_id])->asArray()->one();
            if(!empty($bc)){
                return [$bc['id'] => $bc['name']];
            }
        }
        return [];
    }

    public function getGoodsBarcodeData($goodsId, $barcode)
    {
        $gb = GoodsBarcode::find()->where([
            'goods_id' => $goodsId,
            'bc_id' => $this->barcode_customer_id
            ])->asArray()->one();
        $out['id'] = null;
        $out['barcode'] = $barcode;
        if(!empty($gb)){
            $out['id'] = $gb['id'];
            $out['barcode'] = $gb['barcode'];
        }
        return $out;
    }
    /**
     * @return array
     * @throws Exception
     */
    public function getUsers()
    {
        $sql = "SELECT id, user_fio FROM users WHERE id IN (SELECT created_by FROM tikuv_outcome_products_pack GROUP BY created_by)";
        $users = Yii::$app->db->createCommand($sql)->queryAll();
        if ($users) {
            return ArrayHelper::map($users, 'id', 'user_fio');
        } else {
            return [];
        }
    }

    /**
     * @return array
     */
    public function getMusteris($type=1)
    {
        $musteri = Musteri::find()->select(['id', 'name'])->where(['musteri_type_id' => $type, 'status' => BaseModel::STATUS_ACTIVE])->asArray()->all();
        $brend = ['id'=>'SAMO','name'=>"SAMO" ];
        array_push($musteri, $brend);
        $list = ArrayHelper::map($musteri, 'id', 'name');
        return $list;
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
    public function getBoyoqList()
    {
        $boyoq = BoyahaneSiparisPart::find()->select(['boyahane_siparis_part.id bid', 'partiya_no', 'p.name product', 'c.color_id color'])->leftJoin('boyahane_siparis_musteri as bsm', 'boyahane_siparis_part.siparis_id = bsm.id')->leftJoin('product p', 'p.id = boyahane_siparis_part.product_id')->leftJoin('color c', 'c.id = boyahane_siparis_part.color_id')->where(['boyahane_siparis_part.id' => $this->boyoq_partiya])->asArray()->all();
        return ArrayHelper::map($boyoq, 'bid', function ($model) {
            return $model['partiya_no'] . ' - ' . $model['color'] . ' - ' . $model['product'];
        });
    }

    public function getOrder()
    {
        $order = ModelOrders::find()->select(['id', 'doc_number', 'musteri_id', 'reg_date'])->all();
        return ArrayHelper::map($order, 'id', function ($model) {
            return $model->doc_number . " (" . $model->musteri->name . ") (" . $model->reg_date . ")";
        });
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
        $model = Yii::$app->db->createCommand($sql)->bindValue(':order_id', $this->order_no)->queryAll();
        $response = [];
        foreach ($model as $key) {
            $response[$key['id']] = $key['doc_number'] . ' (' . $key['model'] . ' - ' . $key['code'] . ')' . ' (' . $key['size_type'] . ')' . ' (' . $key['summa'] . ')' . ' (' . date('d.m.Y', strtotime($key['load_date'])) . ')';
        }
        return $response;
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function getDepartmentsBelongTo()
    {
        $currentID = Yii::$app->user->id;
        $sql = "select 
                    td.id,
                    td.name from toquv_departments td
                    where td.status = %d 
                        AND td.id IN 
                        (SELECT  tud.department_id from 
                                toquv_user_department tud 
                                    WHERE tud.user_id = %d AND tud.status = %d);";
        $sql = sprintf($sql, self::STATUS_ACTIVE, $currentID, self::STATUS_ACTIVE);
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        if (!empty($query)) {
            return ArrayHelper::map($query, 'id', 'name');
        }
        return null;
    }

    /**
     * @param $token
     * @param bool $isMultiple
     * @return array|null
     */
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
            } else return [];
        }
        return [];
    }

    /**
     * @param $q
     * @return array
     * @throws Exception
     */
    public static function getBoyoqPartiya($q)
    {
        $sql = "SELECT
                    bsp.id,
                    CONCAT('<b>', bsp.partiya_no, '</b>', ' - ', c.color_id, ' - ', p.name) text 
                FROM
                    boyahane_siparis_part bsp 
                LEFT JOIN
                    boyahane_siparis_musteri bsm 
                        ON bsm.id = bsp.siparis_id 
                LEFT JOIN
                    musteri m 
                        ON bsm.musteri = m.id 
                LEFT JOIN
                    product p 
                        ON bsp.product_id = p.id 
                LEFT JOIN
                    color c 
                        ON bsp.color_id = c.id 
                WHERE
                    ( ( bsp.partiya_no LIKE :q ) OR ( p.name LIKE :q ) OR ( c.color_id LIKE :q ) ) 
                    AND ( m.token = 'SAMO' )";
        return Yii::$app->db->createCommand($sql)->bindValue(':q', '%' . $q . '%')->queryAll();
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public static function getOrderList($id)
    {
        $sql = "SELECT moi.id,
                        mo.doc_number,
                        cp.code,
                        m.name musteri,
                        ml.article artikul,
                        load_date,
                        mo.sum_item_qty as summa
                FROM model_orders_items moi 
                LEFT JOIN model_orders mo on moi.model_orders_id = mo.id 
                LEFT JOIN models_variations mv on moi.model_var_id = mv.id 
                LEFT JOIN models_variation_colors mvc on mv.id = mvc.model_var_id
                LEFT JOIN color_pantone cp on mv.color_pantone_id = cp.id  
                LEFT JOIN musteri m on mo.musteri_id = m.id 
                LEFT JOIN models_list ml on moi.models_list_id = ml.id 
                WHERE mo.id = :order_id  AND  mvc.is_main = 1";
        return Yii::$app->db->createCommand($sql)->bindValue(':order_id', $id)->queryAll();
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public static function getMusteriList($id)
    {
        $sql = "SELECT
                    mo.id,
                    mo.doc_number,
                    m.name musteri,
                    mo.sum_item_qty sum,
                    mo.reg_date
                FROM model_orders mo
                LEFT JOIN musteri m on mo.musteri_id = m.id 
                WHERE ( m.id = :musteri_id )
            ";
        return Yii::$app->db->createCommand($sql)->bindValue(':musteri_id', $id)->queryAll();
    }

    public static function getNastelNo($q,$dep)
    {
        $out['results'] = [];
        if($dep==0)
        {
           $where='';
        }
        else {
            $where=" AND tsib.department_id={$dep} ";
        }
        if(!empty($q)){
            $sql = "select tsib.nastel_no,
                       m.name as mname,
                       m.id as m_id,
                       b.party_no,
                       tsib.department_id as dep_id,
                       b.musteri_party_no,
                       ml.article,
                       mv.id as model_var_id,
                       mv.name as model_var,
                       mv.color_pantone_id,
                       ml.name as model,
                       ml.id as model_id,
                       ml.article,
                       mrd.order_item_id,
                       mrd.order_id,
                       cp.code,
                       cp.name_ru as codeName 
                from tikuv_slice_item_balance tsib
                         left join musteri m on tsib.musteri_id = m.id
                         inner join (select bgr.nastel_party, bgr.id, bgri.party_no, bgri.musteri_party_no from bichuv_given_roll_items bgri
                                     left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                                     where bgri.party_no IS NOT NULL AND bgr.status = 3 GROUP BY bgr.nastel_party ORDER BY bgr.id DESC) as b
                                    on b.nastel_party  = tsib.nastel_no
                         inner join tikuv_doc_items tdi on tdi.nastel_party_no = tsib.nastel_no
                         inner join model_rel_doc mrd on mrd.tikuv_doc_id = tdi.tikuv_doc_id
                         left join models_list ml on mrd.model_list_id = ml.id
                         left join models_variations mv on mrd.model_var_id = mv.id
                         left join color_pantone cp on mrd.color_id = cp.id
                where tsib.nastel_no like '{$q}' {$where}  GROUP BY mv.id ORDER BY tsib.id DESC;";
            $results = Yii::$app->db->createCommand($sql)->queryAll();
            if (!empty($results)) {
                foreach ($results as $key => $result) {
                    array_push($out['results'], [
                        'id' => $result['model_var_id'],
                        'text' => "{$result['nastel_no']} ({$result['article']} ({$result['code']} {$result['codeName']}))",
                        'data_model_var_id' => $result['model_var_id'],
                        'data_model_var' => "{$result['code']} {$result['codeName']}",
                        'data_musteri_id' => $result['m_id'],
                        'data_nastel_no' => $result['nastel_no'],
                        'data_model' => $result['article'],
                        'data_dep_id' => $result['dep_id'],
                        'data_model_id' => $result['model_id'],
                        'data_party_no' => $result['party_no'],
                        'data_order_id' => $result['order_id'],
                        'data_order_item_id' => $result['order_item_id'],
                        'data_musteri_party_no' => $result['musteri_party_no'],
                    ]);
                }
            }else{
                $out['results'] = [
                    'id' => null,
                    'text' => null
                ];
            }
        }
         else {
             $out['results'] = [
                 'id' => null,
                 'text' => null
             ];
        }
        return $out;
    }
    public function getNastelUsluga() // Bu Usluga Outcome Productsda ishlatilgan
    {
        $sql = "select bsib.nastel_no,
                    max(bsib.id) bsib_id,
                   ml.article,
                   bsib.from_musteri m_id, 
                   m.name musteri, 
                   mv.id as model_var_id,
                   mv.name as model_var,
                   mv.color_pantone_id,
                   ml.name as model,
                   ml.id as model_id,
                   ml.article,
                   bgr.musteri_id model_musteri,
                   bgr.order_id,
                   bgr.order_item_id,
                   cp.code,
                   cp.name_ru as codeName
            from bichuv_service_item_balance bsib
                       left join models_list ml on bsib.model_id = ml.id
                       left join models_variations mv on bsib.model_var = mv.id
                       left join color_pantone cp on mv.color_pantone_id = cp.id
                       left join musteri m on bsib.from_musteri = m.id
                       JOIN (select bgr.nastel_party, ml.article, mv.name, cp.code, bgr.id bgr_id, mrp.order_id, mrp.order_item_id, mo.musteri_id from bichuv_given_rolls bgr
                                left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                                left join models_list ml on mrp.models_list_id = ml.id
                                left join models_variations mv on mv.id = mrp.model_variation_id
                                left join model_orders mo ON mrp.order_id = mo.id
                                left join color_pantone cp on mv.color_pantone_id = cp.id
                       ) bgr ON bgr.nastel_party = bsib.nastel_no 
            where bsib.inventory > 0 AND bsib.department_id = %d AND bsib.from_musteri IS NOT NULL AND bsib.type = %d GROUP BY mv.id, bsib.nastel_no,ml.article,ml.name,ml.id,bsib.from_musteri DESC LIMIT 1000;";
        $uslugaDept = ToquvDepartments::findOne(['token'=>'USLUGA'])['id'];
        $type = UslugaDoc::TYPE_WORK;
        $sql = sprintf($sql,$uslugaDept,$type);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $out = [];
        if (!empty($results)) {
            foreach ($results as $key => $result) {
                $out['data'][$result['bsib_id']] = "{$result['musteri']} {$result['nastel_no']} ({$result['article']} ({$result['code']} {$result['codeName']}))";
                $out['dataAttr'][$result['bsib_id']] = [
                    'data-model-var-id' => $result['model_var_id'],
                    'data-model-var' => "{$result['code']} {$result['codeName']}",
                    'data-musteri-id' => $result['model_musteri'],
                    'data-from-musteri-id' => $result['m_id'],
                    'data-nastel' => $result['nastel_no'],
                    'data-model' => $result['article'],
                    'data-model-id' => $result['model_id'],
                    'data-party-no' => $result['party_no'],
                    'data-order-id' => $result['order_id'],
                    'data-order-item-id' => $result['order_item_id'],
                    'data-musteri-party-no' => $result['musteri_party_no'],
                ];
            }
        } else {
            $out['data'] = [];
            $out['dataAttr'] = null;
        }

        return $out;
    }
    /**
     * @param $modelId
     * @return array
     * @throws Exception
     */
    public static function getOrderItemModelVariation($modelId){

        $sql = "select mv.id as model_var_id,
                       cp.code,
                       cp.id as color_pantone_id, 
                       cp.name as pantone,
                       mv.name as model_var,
                       mo.id as order_id,
                       moi.id as order_item_id,
                       moi.price,
                       moi.pb_id,
                       ml.article as model_no,
                       ml.name as model_name 
                from models_variations mv
                left join color_pantone cp on mv.color_pantone_id = cp.id
                inner join model_orders_items moi on mv.id = moi.model_var_id
                left join models_list ml on moi.models_list_id = ml.id
                left join model_orders mo on moi.model_orders_id = mo.id
                where mo.status > 2 AND cp.code IS NOT NULL AND moi.id IN (SELECT MAX(moi2.id) FROM model_orders_items moi2 where moi2.id = :modelId GROUP BY moi2.model_var_id);";

        return Yii::$app->db->createCommand($sql)->bindValue('modelId',$modelId)->queryAll();
    }
    public function getWorkCount($type = 'slice')
    {
        $result = [];
        $result['count'] = 0;
        $result['size'] = '';
        $child = TikuvOutcomeProducts::find()->with('size')->where(['pack_id'=>$this->id])->asArray()->groupBy('size_id,nastel_no')->all();
        if (!empty($child)) {
            foreach ($child as $key => $item) {
                $result['count'] += $item['quantity'];
                $result['size'] .= ($key==0)?$item['size']['name']:",".$item['size']['name'];
            }
            $result['count'] = number_format($result['count'], 0, '.', '');
        }
        $sql = "SELECT
                   top.id,
                   GROUP_CONCAT(DISTINCT s.name)  size,
                   truncate(SUM(top.quantity), 0) count,
                   (select format(SUM(sort1.quantity),0,'de_DE') sort_1
                    from tikuv_outcome_products sort1
                    where sort1.pack_id = :id AND sort1.sort_type_id=1
                    group by sort1.sort_type_id) sort_1,
                   (select format(SUM(sort2.quantity),0,'de_DE') sort_2
                    from tikuv_outcome_products sort2
                    where sort2.pack_id = :id AND sort2.sort_type_id=2
                    group by sort2.sort_type_id) sort_2,
                   (select format(SUM(sort3.quantity),0,'de_DE') brak
                    from tikuv_outcome_products sort3
                    where sort3.pack_id = :id AND sort3.sort_type_id=3
                    group by sort3.sort_type_id) brak
            FROM tikuv_outcome_products top
                     LEFT JOIN size s on top.size_id = s.id
            WHERE top.pack_id = :id";
        $result = Yii::$app->db->createCommand($sql)->bindValue('id',$this->id)->queryOne();
        return $result;
    }

    public function getModelNoAndPantone()
    {
        $sql = "SELECT GROUP_CONCAT(DISTINCT top.model_no SEPARATOR ', ') as model_no,
                       GROUP_CONCAT(DISTINCT top.color_code SEPARATOR ', ') as color_code,
                       GROUP_CONCAT(DISTINCT mo.doc_number SEPARATOR ', ') as model_order
                FROM tikuv_outcome_products top
                         LEFT JOIN tikuv_outcome_products_pack topp on top.pack_id = topp.id
                         LEFT JOIN model_orders mo on topp.order_id = mo.id
                WHERE pack_id = :id
                group by topp.id";
        $result = Yii::$app->db->createCommand($sql)->bindValue('id',$this->id)->queryOne();
        return $result;
    }

    public function getModelListInfo()
    {
        $sql = "select mv.id as model_var_id, ml.id as model_id, ml.article, mv.name, cp.code, mrd.order_id, mrd.order_item_id from model_rel_doc mrd
                left join models_list ml on mrd.model_list_id = ml.id
                left join models_variations mv on mv.id = mrd.model_var_id
                left join color_pantone cp on mv.color_pantone_id = cp.id
                where mrd.nastel_no = '%s' GROUP BY mv.id;";
        $sql = sprintf($sql, $this->nastel_no);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        if(empty($results)) {
            $sql = "select mv.id as model_var_id, ml.id as model_id, ml.article, mv.name, cp.code, bgr.id bgr_id, mrp.order_id, mrp.order_item_id from bichuv_given_rolls bgr
                    left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                    left join models_list ml on mrp.models_list_id = ml.id
                    left join models_variations mv on mv.id = mrp.model_variation_id
                    left join color_pantone cp on mv.color_pantone_id = cp.id
                    where bgr.nastel_party = '%s' GROUP BY mv.id;";
            $sql = sprintf($sql, $this->nastel_no);
            $results = Yii::$app->db->createCommand($sql)->queryAll();
        }
        $out = [];
        $out['model_var'] = null;
        $out['model'] = null;
        $out['model_var_code'] = null;
        $out['model_id'] = null;
        $out['model_var_id'] = null;
        $out['bgr_id'] = null;
        if (!empty($results)) {
            foreach ($results as $item) {
                $out['model_id'] = $item['model_id'];
                $out['model_var_id'] = $item['model_var_id'];
                $out['model'] = $item['article'];
                $out['bgr_id'] = $item['bgr_id'];
                $out['order_id'] = $item['order_id'];
                $out['order_item_id'] = $item['order_item_id'];
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
    public function getNastelList()
    {
        $sql = "SELECT m.id, m.name, bsib.nastel_no FROM bichuv_service_item_balance bsib
                    INNER JOIN musteri m on bsib.musteri_id = m.id
                    WHERE bsib.id IN (SELECT MAX(bsib2.id) from bichuv_service_item_balance bsib2 WHERE bsib2.musteri_id IS NOT NULL AND bsib.musteri_id = %d GROUP BY bsib2.nastel_no,bsib2.musteri_id,bsib2.size_id)
                    AND bsib.inventory > 0 GROUP BY bsib.nastel_no, bsib.musteri_id;";
        $sql = sprintf($sql,$this->from_musteri);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $list = [];
        if(!empty($results)){
            foreach ($results as $result) {
                $list[$result['nastel_no']] = $result['nastel_no'];
            }
        }
        return $list;
    }

    public function getOrderItems($nastel,$model)
    {
        $sql1 = "select bc.name, bc.id from tikuv_doc_items tdi
                inner join model_rel_doc mrd on tdi.nastel_party_no = mrd.nastel_no
                left join goods g on g.model_id = mrd.model_list_id
                left join goods_barcode gb on g.id = gb.goods_id
                inner join barcode_customers bc on gb.bc_id = bc.id
                where tdi.nastel_party_no = '{$nastel}'
                  AND mrd.model_list_id = {$model}
                  AND g.model_id = {$model} AND g.color = mrd.color_id 
                 GROUP BY bc.id;";

        $results = Yii::$app->db->createCommand($sql1)->queryAll();
        return $results;
    }
}
