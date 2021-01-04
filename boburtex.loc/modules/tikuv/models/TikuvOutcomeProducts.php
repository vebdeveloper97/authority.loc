<?php

namespace app\modules\tikuv\models;

use app\models\Constants;
use app\modules\base\models\BarcodeCustomers;
use app\modules\base\models\Goods;
use app\modules\base\models\GoodsBarcode;
use app\modules\base\models\ModelOrdersItemsSize;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\Size;
use app\modules\base\models\SizeType;
use app\modules\bichuv\models\BichuvServiceItemBalance;
use app\modules\toquv\models\Unit;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use app\modules\toquv\models\SortName;

/**
 * This is the model class for table "tikuv_outcome_products".
 *
 * @property int $id
 * @property string $model_no
 * @property string $color_code
 * @property int $size_type_id
 * @property int $size_id
 * @property string $pechat
 * @property string $barcode
 * @property string $quantity
 * @property int $accepted_quantity
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $pack_id
 * @property int $sort_type_id
 * @property int $unit_id
 * @property string $reg_date
 * @property int $amount
 * @property int $goods_id
 * @property int $type
 * @property string $price
 * @property int $pb_id
 *
 * @property Goods $goods
 * @property GoodsBarcode $goodsBarcode
 * @property TikuvOutcomeProductsPack $pack
 * @property Size $size
 * @property SizeType $sizeType
 * @property SortName $sortType
 * @property Unit $unit
 * @property TikuvTopAccepted[] $tikuvTopAccepteds
 * @property string $is_main_barcode [varchar(50)]
 * @property int $goods_barcode_id [int(11)]
 * @property int $models_list_id [int(11)]
 * @property int $model_var_id [int(11)]
 * @property int $order_id [int(11)]
 * @property int $order_item_id [int(11)]
 * @property mixed $count
 * @property int $remain
 * @property mixed $barcodeCustomers
 * @property ActiveQuery $modelsList
 * @property ActiveQuery $modelVar
 * @property bool|string $modelUsluga
 * @property int $remainUsluga
 * @property \yii\db\ActiveQuery $tikuvDiffFromProduction
 * @property string $nastel_no [varchar(30)]
 */
class TikuvOutcomeProducts extends BaseModel
{
    public $cp;
    public $barcode1;
    public $barcode2;
    public $brand;
    public $model_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_outcome_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size_type_id', 'goods_barcode_id', 'size_id', 'accepted_quantity', 'status', 'created_by', 'created_at', 'updated_at', 'pack_id', 'sort_type_id', 'unit_id', 'amount', 'goods_id', 'type', 'pb_id', 'models_list_id', 'model_var_id', 'order_id', 'order_item_id'], 'integer'],
            [['quantity', 'price'], 'number'],
            [['model_no', 'color_code', 'barcode', 'size_type_id', 'size_id', 'quantity', 'sort_type_id'], 'required'],
            [['reg_date', 'brand'], 'safe'],
            [['model_no', 'color_code', 'barcode', 'is_main_barcode'], 'string', 'max' => 50],
            [['pechat'], 'string', 'max' => 100],
            [['nastel_no'], 'string', 'max' => 30],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
            [['pack_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvOutcomeProductsPack::className(), 'targetAttribute' => ['pack_id' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
            [['size_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SizeType::className(), 'targetAttribute' => ['size_type_id' => 'id']],
            [['sort_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SortName::className(), 'targetAttribute' => ['sort_type_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
            [['goods_barcode_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodsBarcode::className(), 'targetAttribute' => ['goods_barcode_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_no' => Yii::t('app', 'Model No'),
            'color_code' => Yii::t('app', 'Color Code'),
            'size_type_id' => Yii::t('app', 'Size Type ID'),
            'size_id' => Yii::t('app', 'Size ID'),
            'pechat' => Yii::t('app', 'Pechat'),
            'barcode' => Yii::t('app', 'Barcode'),
            'quantity' => Yii::t('app', 'Quantity'),
            'accepted_quantity' => Yii::t('app', 'Accepted Quantity'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'pack_id' => Yii::t('app', 'Pack ID'),
            'sort_type_id' => Yii::t('app', 'Sort Type ID'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'amount' => Yii::t('app', 'Amount'),
            'goods_id' => Yii::t('app', 'Goods ID'),
            'type' => Yii::t('app', 'Type'),
            'price' => Yii::t('app', 'Price'),
            'pb_id' => Yii::t('app', 'Pb ID'),
            'is_main_barcode' => Yii::t('app', 'Is Main Barcode'),
            'goods_barcode_id' => Yii::t('app', 'Goods Barcode ID'),
            'models_list_id' => Yii::t('app', 'Models List ID'),
            'model_var_id' => Yii::t('app', 'Model Var ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'order_item_id' => Yii::t('app', 'Order Item ID'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
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
    public function getGoodsBarcode()
    {
        return $this->hasOne(GoodsBarcode::className(), ['id' => 'goods_barcode_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvTopAccepteds()
    {
        return $this->hasMany(TikuvTopAccepted::className(), ['top_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvDiffFromProduction()
    {
        return $this->hasMany(TikuvDiffFromProduction::className(), ['tikuv_op_id' => 'id']);
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
    public function getModelsList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'models_list_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getPack()
    {
        return $this->hasOne(TikuvOutcomeProductsPack::className(), ['id' => 'pack_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }

    /**
     * @return array
     */
    public static function getSizeTypes()
    {
        $sizeTypes = SizeType::find()->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::map($sizeTypes, 'id', 'name');
    }

    /**
     * @return ActiveQuery
     */
    public function getSizeType()
    {
        return $this->hasOne(SizeType::className(), ['id' => 'size_type_id']);
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
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    /**
     * @return array
     */
    public static function getSortTypes()
    {
        $sizeTypes = SortName::find()->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::map($sizeTypes, 'id', 'name');
    }

    /**
     * @return array
     */
    public static function getUnitList()
    {
        $sizeTypes = Unit::find()->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::map($sizeTypes, 'id', 'name');
    }

    public function getCountAccepted($list = false)
    {
        $accepted = TikuvTopAccepted::find()->where(['top_id' => $this->id]);
        $count = $accepted->sum('accepted');
        $count = ($count > 0) ? $count : 0;
        if (!$list) {
            return $count;
        } else {
            $accepted = $accepted->all();
            $div = '<table class="table table-striped table-bordered"><thead><tr><th width="110px">' . Yii::t('app', 'Sana') . '</th><th>' . Yii::t('app', 'Soni') . '</th><th>' . Yii::t('app', 'Doc Number') . '</th></thead><tbody>';
            if ($accepted) {
                foreach ($accepted as $key) {
                    $div .= "<tr><td>{$key->reg_date}</td><td><span>{$key->accepted}</span></td><td><span>{$key->doc_number}</span></td></tr>";
                }
            }
            $div .= "<tr><th>" . Yii::t('app', 'Jami') . "</th><th>{$count}</th></tr></tbody></table>";
            return $div;
        }
    }

    public function getCount()
    {
        $size = ModelOrdersItemsSize::findOne(['model_orders_items_id' => $this->pack->order_item_id, 'size_id' => $this->size_id]);
        return $size->count;
    }

    public function getRemain()
    {
        $count = TikuvSliceItemBalance::find()->where(['nastel_no' => $this->pack->nastel_no, 'size_id' => $this->size_id])->orderBy(['id' => SORT_DESC])->asArray()->one();
        if (!empty($count)) {
            return number_format($count['inventory'], 0, '.', ' ');
        }
        return 0;
    }
    public function getRemainUsluga()
    {
        $count = BichuvServiceItemBalance::find()->where(['nastel_no' => $this->nastel_no, 'size_id' => $this->size_id, 'musteri_id' => $this->pack->from_musteri])->orderBy(['id' => SORT_DESC])->asArray()->one();
        if (!empty($count)) {
            return number_format($count['inventory'], 0, '.', ' ');
        }
        return 0;
    }
    public function getModelUsluga()
    {
        $model = ModelRelDoc::find()->where(['nastel_no' => $this->nastel_no])->orderBy(['id' => SORT_DESC])->one();
        if (!empty($model)) {
            return $model->modelList->article." (".$model->modelVar->colorPantone->code.")";
        }
        return false;
    }
    /**
     * @param $provider
     * @param array $fields
     * @param null $sortCode
     * @param bool $withPercentage
     * @return string
     */
    public static function getTotalFooter($provider, $fields = [], $sortCode = null, $withPercentage = false)
    {
        $total = 0;
        $totalAll = 0;
        if (!empty($fields)) {
            foreach ($fields as $fieldName) {
                foreach ($provider as $item) {
                    $totalAll += $item[$fieldName];
                    if (!empty($sortCode)) {
                        if ($item->sortType->code == $sortCode) {
                            $total += $item[$fieldName];
                        }
                    } else {
                        $total += $item[$fieldName];
                    }
                }
            }
        }
        if ($withPercentage) {
            if ($totalAll > 0) {
                $per = number_format($total / $totalAll * 100, 0, '.', ' ');
            } else {
                $per = 0;
            }
            return "<strong>{$total}</strong>  <span class='text-danger'>({$per}%)</span>";
        }
        return "<strong>$total</strong>";
    }
    public static function getTotal($provider, $fields, $sortCode = null, $withPercentage = false)
    {
        $total = 0;
        $totalAll = 0;
        if (!empty($fields)) {
            foreach ($provider as $item) {
                $totalAll += $item[$fields];
                if (!empty($sortCode)) {
                    if ($item->sortType->code == $sortCode) {
                        $total += $item[$fields];
                    }
                } else {
                    $total += $item[$fields];
                }
            }
        }
        if ($withPercentage) {
            if ($totalAll > 0) {
                $per = number_format($total / $totalAll * 100, 0, '.', ' ');
            } else {
                $per = 0;
            }
            return "<strong>{$total}</strong>  <span class='text-danger'>({$per}%)</span>";
        }
        return "<strong>$total</strong>";
    }
    public function getBarcodeCustomers()
    {
        $nastel = $this->pack->nastel_no;
        $sql = "select bc.id, 
                       bc.name 
                from tikuv_outcome_products top
                left join tikuv_outcome_products_pack topp on top.pack_id = topp.id
                left join goods g on top.goods_id = g.id
                left join goods_barcode gb on g.id = gb.goods_id
                left join barcode_customers bc on gb.bc_id = bc.id
                where topp.nastel_no = '%s' GROUP BY bc.id ORDER BY bc.name;";
        $sql = sprintf($sql, $nastel);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $samo = BarcodeCustomers::find()->where(['code' => Constants::$brandSAMO])->asArray()->one();
        $mixed = array_merge($results, $samo);
        return ArrayHelper::map($mixed, 'id', 'name');
    }

}
