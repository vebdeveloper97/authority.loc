<?php

namespace app\modules\tikuv\models;

use app\modules\base\models\BarcodeCustomers;
use app\modules\toquv\models\SortName;
use app\modules\toquv\models\Unit;
use Yii;
use app\modules\base\models\Goods;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "tikuv_goods_doc".
 *
 * @property int $id
 * @property int $tgdp_id
 * @property string $barcode
 * @property int $goods_id
 * @property int $created_by
 * @property int $type
 * @property string $quantity
 * @property string $model_no
 * @property int $model_id
 * @property int $size_type
 * @property int $size
 * @property int $color
 * @property string $name
 * @property string $old_name
 * @property int $category
 * @property int $sub_category
 * @property int $model_type
 * @property int $season
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Goods $goods
 * @property TikuvGoodsDocPack $tgdp
 * @property SortName $sortType
 * @property BarcodeCustomers $barcodeCustomer
 * @property string $weight [decimal(10,3)]
 * @property int $unit_id [int(11)]
 * @property int $sort_type_id [int(11)]
 * @property int $package_type [smallint(1)]
 * @property int $remainPackage
 * @property int $barcode_customer_id [int(11)]
 */
class TikuvGoodsDoc extends BaseModel
{
    public $remain;
    public $sort_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_goods_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgdp_id','unit_id','package_type','barcode_customer_id', 'sort_type_id', 'goods_id', 'created_by', 'type', 'model_id', 'size_type', 'size', 'color', 'category', 'sub_category', 'model_type', 'season', 'status', 'created_at', 'updated_at'], 'integer'],
            [['remain','weight'], 'number'],
            [['goods_id'],'required'],
            [['model_no'], 'string', 'max' => 30],
            [['quantity'], 'integer', 'min' => 1],
            [['name', 'old_name','barcode'], 'string', 'max' => 100],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
            [['tgdp_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvGoodsDocPack::className(), 'targetAttribute' => ['tgdp_id' => 'id']],
            [['sort_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SortName::className(), 'targetAttribute' => ['sort_type_id' => 'id']],
            [['barcode_customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => BarcodeCustomers::className(), 'targetAttribute' => ['barcode_customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'tgdp_id' => Yii::t('app', 'Tgdp ID'),
            'barcode' => Yii::t('app', 'Barcode'),
            'goods_id' => Yii::t('app', 'Goods ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'type' => Yii::t('app', 'Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'model_no' => Yii::t('app', 'Model No'),
            'model_id' => Yii::t('app', 'Model ID'),
            'size_type' => Yii::t('app', 'Size Type'),
            'size' => Yii::t('app', 'Size'),
            'color' => Yii::t('app', 'Color'),
            'name' => Yii::t('app', 'Name'),
            'old_name' => Yii::t('app', 'Old Name'),
            'category' => Yii::t('app', 'Category'),
            'sub_category' => Yii::t('app', 'Sub Category'),
            'sort_type_id' => Yii::t('app', 'Sort Type ID'),
            'model_type' => Yii::t('app', 'Model Type'),
            'season' => Yii::t('app', 'Season'),
            'status' => Yii::t('app', 'Status'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'weight' => Yii::t('app', 'Weight'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBarcodeCustomer()
    {
        return $this->hasOne(BarcodeCustomers::className(), ['id' => 'barcode_customer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSortType()
    {
        return $this->hasOne(SortName::className(), ['id' => 'sort_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTgdp()
    {
        return $this->hasOne(TikuvGoodsDocPack::className(), ['id' => 'tgdp_id']);
    }
    public static function getTotal($provider, $fieldName)
    {
        $total = 0;

        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }
        return number_format($total,3,'.',' ');
    }

    public static function getTotalPackage($provider, $fieldName, $type)
    {
        $total = 0;

        foreach ($provider as $item) {
            if($item['type'] == $type){
                $total += $item[$fieldName];
            }
        }
        return number_format($total,3,'.',' ');
    }

    /**
     * @return string
     */
    public function getName(){
        if($this->goods->type == 1){
            $this->name = $this->goods->model_no."-".$this->goods->color_collection."-".$this->goods->size_collection;
        }else{
            $this->name = $this->goods->name;
        }
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name){
        $this->name = $name;
    }

    public function getRemain(){
        $sum = 0;
        if(!empty($this->goods) && ($this->goods->tikuvGoodsDocMovings)){
            $sum = $this->goods->getTikuvGoodsDocMovings()->where([
                'order_id' => $this->tgdp->model_var_id,
                'model' => $this->tgdp->model_list_id
            ])->sum('quantity');
        }
        $this->remain = ($this->quantity - $sum);
        return $this->remain;
    }

    public function getRemainPackage(){

        $out = TikuvPackageItemBalance::find()->where([
            'model_list_id' => $this->tgdp->model_list_id,
            'model_var_id' => $this->tgdp->model_var_id,
            'nastel_no' => $this->tgdp->nastel_no,
            'goods_id' => $this->goods_id,
            'dept_type' => 'TW'
        ])->select(['inventory'])->asArray()->orderBy(['id' => SORT_DESC])->one();
        if(!empty($out) && $out['inventory']){
            return $out['inventory'];
        }
        return 0;
    }

    public static function getGoodsItemsForTabular($nastelNo, $modelId, $modelVarId, $brandType){
        $sql = "select g.id as id,
                           g.name,
                           ml.article,
                           cp.code,
                           s.name as sn,
                           tpib.inventory,
                           tpib.package_type as pt,
                           sn.name as sort,
                           sn.id as sid,
                           IF(bc.code = 'SAMO', g.barcode, tpib.is_main_barcode) as barcode, 
                           tpib.barcode_customer_id as bt 
                    from tikuv_package_item_balance tpib
                             left join models_list ml on tpib.model_list_id = ml.id
                             left join models_variations mv on mv.id = tpib.model_var_id
                             left join color_pantone cp on mv.color_pantone_id = cp.id
                             left join goods g on tpib.goods_id = g.id
                             left join size s on g.size = s.id
                             left join sort_name sn on tpib.sort_type_id = sn.id
                             left join barcode_customers bc on tpib.barcode_customer_id = bc.id
                    where tpib.id IN (select MAX(tpib2.id) from tikuv_package_item_balance tpib2
                                      where tpib2.nastel_no = '%s'
                                        and tpib2.model_list_id = %d
                                        and tpib2.model_var_id =  %d
                                        and tpib2.dept_type = 'TW'
                                        and tpib2.barcode_customer_id = %d
                                      GROUP BY tpib2.goods_id, tpib2.sort_type_id, tpib2.barcode_customer_id)
                    AND tpib.inventory > 0 AND bc.id = %d GROUP BY tpib.goods_id,tpib.sort_type_id,tpib.barcode_customer_id LIMIT 50;";
        $sql = sprintf($sql,$nastelNo, $modelId, $modelVarId, $brandType, $brandType);
        $out = Yii::$app->db->createCommand($sql)->queryAll();
        return $out;
    }

    public static function getPackageVolume($gid, $type, $qty = 1){
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
            return $sum*$qty;
        }
        return $qty;
    }

    public static function getPackageVolumeTotal($models){
        $sum = 0;

        foreach ($models as $item){
            if($item['type']  == 4){
                $sum += self::getPackageVolume($item['gid'], $item['type'], $item['quantity']);
            }
        }
        return $sum;
    }
}
