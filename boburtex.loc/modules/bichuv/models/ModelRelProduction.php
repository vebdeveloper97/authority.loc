<?php

namespace app\modules\bichuv\models;

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\ModelVariationParts;
use app\modules\toquv\models\PulBirligi;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "model_rel_production".
 *
 * @property int $id
 * @property int $models_list_id
 * @property int $model_variation_id
 * @property int $bichuv_given_roll_id
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvGivenRolls $bichuvGivenRoll
 * @property ModelsVariations $modelVariation
 * @property ModelVariationParts $modelVarPart
 * @property ModelOrders $order
 * @property ModelOrdersItems $orderItem
 * @property ModelsList $modelsList
 * @property array $pbList
 * @property PulBirligi $pb
 * @property int $order_id [int(11)]
 * @property int $order_item_id [int(11)]
 * @property string $price [decimal(20,3)]
 * @property int $pb_id [int(11)]
 * @property bool $is_accepted [tinyint(1)]
 * @property int $model_var_part_id [int(11)]
 * @property string $modelVarParts
 * @property int $is_combine [smallint(1)]
 * @property string $nastel_no [varchar(30)]
 */
class ModelRelProduction extends BaseModel
{
    public $temp_order_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_rel_production';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_list_id','is_combine','model_var_part_id', 'pb_id','is_accepted','order_id','order_item_id','model_variation_id', 'bichuv_given_roll_id', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
//            ['price','number'], //TODO buni komentdan olib qo'yish kerak tekshirib albatta
            [['nastel_no'], 'string', 'max' => 30],
            [['bichuv_given_roll_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRolls::className(), 'targetAttribute' => ['bichuv_given_roll_id' => 'id']],
            [['model_variation_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_variation_id' => 'id']],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
            [['pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['pb_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['order_item_id' => 'id']],
            [['model_var_part_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelVariationParts::className(), 'targetAttribute' => ['model_var_part_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'models_list_id' => Yii::t('app', 'Article'),
            'order_id' => Yii::t('app', 'Buyurtma'),
            'order_item_id' => Yii::t('app', 'Order Item ID'),
            'model_variation_id' => Yii::t('app', 'Model rangi'),
            'bichuv_given_roll_id' => Yii::t('app', 'Nastel No'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'is_accepted' => Yii::t('app', 'Tasdiqlash'),
            'pb_id' => Yii::t('app', 'Pul birligi'),
            'price' => Yii::t('app', 'Narxi'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRoll()
    {
        return $this->hasOne(BichuvGivenRolls::className(), ['id' => 'bichuv_given_roll_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPart()
    {
        return $this->hasOne(ModelVariationParts::className(), ['id' => 'model_var_part_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'order_item_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'pb_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVariation()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_variation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'models_list_id']);
    }

    /**
     * @return array
     */
    public function getPbList(){
        $pb = PulBirligi::find()->asArray()->all();
        return ArrayHelper::map($pb,'id','name');
    }

    /**
     * @param $modelId
     * @param $modelVarId
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getOrderModelChangePrice($modelId, $modelVarId){

        $sql = "select  mo.id as order_id,
                        mri.id as order_item_id,
                        m.name as musteri,
                        mo.doc_number,
                        ml.article,
                        cp.code,
                        mv.name as variation,
                        mri.price,
                        mri.pb_id
                from models_list ml
                         inner join model_orders_items mri on ml.id = mri.models_list_id
                         left join models_variations mv on mv.id = mri.model_var_id
                         left join color_pantone cp on mv.color_pantone_id = cp.id
                         inner join model_orders mo on mri.model_orders_id = mo.id
                         left join musteri m on mo.musteri_id = m.id
                where mo.status > 2 AND ml.id = %d AND mv.id = %d
                ORDER BY mo.id DESC LIMIT 1;";

        $sql = sprintf($sql,$modelId, $modelVarId);

        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    /**
     * @return string
     */
    public function getModelVarParts(){
        $modelRelProds = self::find()
            ->where([
                'model_variation_id' => $this->model_variation_id,
                'models_list_id' => $this->models_list_id,
                'order_id' => $this->order_id,
                'order_item_id' => $this->order_item_id,
                'model_rel_production.type' => 2,
                'bichuv_given_rolls.status' => 3
                ])
            ->leftJoin('bichuv_given_rolls','bichuv_given_rolls.id = model_rel_production.bichuv_given_roll_id')
            ->all();
        $out = "";
        foreach ($modelRelProds as $modelRelProd){
           $out .= "<div>{$modelRelProd->modelVarPart->basePatternPart->name} {$modelRelProd->modelVariation->colorPan->code} {$modelRelProd->modelVariation->name}</div>";
       }
       return $out;
    }
}
