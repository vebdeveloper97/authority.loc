<?php

namespace app\modules\tikuv\models;

use app\modules\base\models\BarcodeCustomers;
use app\modules\base\models\Goods;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\Musteri;
use app\modules\toquv\models\SortName;
use app\modules\toquv\models\ToquvDepartments;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * This is the model class for table "tikuv_package_item_balance".
 *
 * @property int $id
 * @property int $goods_id
 * @property int $count
 * @property int $inventory
 * @property string $nastel_no
 * @property int $doc_type
 * @property string $dept_type
 * @property int $department_id
 * @property int $model_list_id
 * @property int $model_var_id
 * @property int $sort_type_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Goods $goods
 * @property ToquvDepartments $fromDepartment
 * @property ModelsList $modelList
 * @property BarcodeCustomers $barcodeCustomer
 * @property ModelOrders $order
 * @property ModelOrdersItems $orderItem
 * @property ModelsVariations $modelVar
 * @property SortName $sortType
 * @property int $from_department [int(11)]
 * @property int $package_type [smallint(1)]
 * @property int $order_id [int(11)]
 * @property int $order_item_id [int(11)]
 * @property string $is_main_barcode [varchar(50)]
 * @property int $brand_type [smallint(1)]
 * @property int $from_musteri [bigint(20)]
 * @property ActiveQuery $toMusteri
 * @property ActiveQuery $fromMusteri
 * @property int $to_musteri [bigint(20)]
 * @property int $barcode_customer_id [int(11)]
 */
class TikuvPackageItemBalance extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_package_item_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id','package_type','barcode_customer_id', 'brand_type','order_id','order_item_id','from_department','count', 'inventory', 'doc_type', 'department_id', 'model_list_id', 'model_var_id', 'sort_type_id', 'status', 'created_by', 'created_at', 'updated_at', 'from_musteri', 'to_musteri'], 'integer'],
            [['nastel_no','is_main_barcode'], 'string', 'max' => 25],
            [['dept_type'], 'string', 'max' => 2],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
            [['sort_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SortName::className(), 'targetAttribute' => ['sort_type_id' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['order_item_id' => 'id']],
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
            'goods_id' => Yii::t('app', 'Goods ID'),
            'count' => Yii::t('app', 'Count'),
            'inventory' => Yii::t('app', 'Inventory'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'doc_type' => Yii::t('app', 'Doc Type'),
            'dept_type' => Yii::t('app', 'Dept Type'),
            'department_id' => Yii::t('app', 'Department ID'),
            'model_list_id' => Yii::t('app', 'Model List ID'),
            'model_var_id' => Yii::t('app', 'Model Var ID'),
            'sort_type_id' => Yii::t('app', 'Sort Type ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'from_musteri' => Yii::t('app', 'Kasanachi'),
            'to_musteri' => Yii::t('app', 'To Musteri'),
        ];
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
    public function getFromDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'from_department']);
    }


    /**
     * @return ActiveQuery
     */
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
    public function getSortType()
    {
        return $this->hasOne(SortName::className(), ['id' => 'sort_type_id']);
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
     * @param array $params
     * @return array
     * @throws Exception
     */


    public static function getPackageType($key = null){
        $out = [
            1 => Yii::t('app','Dona'),
            2 => Yii::t('app','Paket'),
            3 => Yii::t('app','Blok'),
            4 => Yii::t('app','Qop'),
        ];
        if($key){
            if(isset($out[$key])){
                return $out[$key];
            }
            return null;
        }
        return $out;
    }

}
