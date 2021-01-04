<?php

namespace app\modules\bichuv\models;

use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelVarPrints;
use app\modules\base\models\ModelVarStone;
use Yii;
use app\modules\base\models\Size;

/**
 * This is the model class for table "bichuv_slice_items".
 *
 * @property int $id
 * @property int $size_id
 * @property int $bichuv_doc_id
 * @property string $nastel_party
 * @property string $quantity
 * @property int $fact_quantity
 * @property int $type
 * @property string $work_weight
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvDoc $bichuvDoc
 * @property Size $size
 * @property int $bichuv_given_roll_id [int(11)]
 * @property int $model_id [smallint(6)]
 */
class BichuvSliceItems extends BaseModel
{
    const SCENARIO_ACCEPT_SLICE = 'accept-slice';

    public $detail_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_slice_items';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ACCEPT_SLICE] = ['fact_quantity', 'add_info'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size_id','model_id','bichuv_given_roll_id','bichuv_doc_id', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['work_weight'], 'number'],
            ['quantity','required'],
            ['fact_quantity', 'required', 'on' => [self::SCENARIO_ACCEPT_SLICE]],
            ['fact_quantity', 'integer', 'min' => 1],
            ['quantity','number','min'=>0.01],
            [['nastel_party'], 'required'],
            [['bichuv_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::className(), 'targetAttribute' => ['bichuv_doc_id' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
            [['model_var_print_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelVarPrints::className(), 'targetAttribute' => ['model_var_print_id' => 'id']],
            [['model_var_stone_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelVarStone::className(), 'targetAttribute' => ['model_var_stone_id' => 'id']],
            [['bgri_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRollItems::className(), 'targetAttribute' => ['bgri_id' => 'id']],
            [['invalid_quantity','add_info'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'size_id' => Yii::t('app', 'Size ID'),
            'bichuv_doc_id' => Yii::t('app', 'Bichuv Doc ID'),
            'model_id' => Yii::t('app', 'Model ID'),
            'nastel_party' => Yii::t('app', 'Nastel Party'),
            'quantity' => Yii::t('app', 'Quantity'),
            'type' => Yii::t('app', 'Type'),
            'work_weight' => Yii::t('app', 'Work Weight'),
            'add_info' => Yii::t('app', 'Add info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDoc()
    {
        return $this->hasOne(BichuvDoc::className(), ['id' => 'bichuv_doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductModel()
    {
        return $this->hasOne(Product::className(), ['id' => 'model_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsList()
    {
        return $this->hasOne(ModelsList::class, ['id' => 'models_list_id']);
    }
    /**
 * @return \yii\db\ActiveQuery
 */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPrints()
    {
        return $this->hasOne(ModelVarPrints::class, ['id' => 'model_var_print_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarStone()
    {
        return $this->hasOne(ModelVarStone::class, ['id' => 'model_var_stone_id']);
    }

    public function getBgri(){
        return $this->hasOne(BichuvGivenRollItems::class, ['id' => 'bgri_id']);

    }
    public function getRollCountAndQty()
    {
        $sql = "select  SUM(bgri.roll_count) as count, 
                        SUM(bgri.quantity) as sum,
                        (select SUM(bamfp.quantity) from bichuv_accepted_mato_from_production bamfp where bamfp.bichuv_given_roll_id = bgr.id)  as accepted
                        from bichuv_given_rolls bgr
                        left join bichuv_given_roll_items bgri on bgr.id = bgri.bichuv_given_roll_id
                        where bgr.id = :rollId limit 1;;";
        $res = Yii::$app->db->createCommand($sql)->bindValue('rollId', $this->bichuv_given_roll_id)->queryOne();
        if(!empty($res)){
            if($res['accepted'] && $res['accepted'] > 0){
                return "? dona / ".($res['sum'] - $res['accepted'])." kg";
            }
            return $res['count']." dona / ".$res['sum']." kg";
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public function getRemainSliceQuantity(){
        $ib = BichuvSliceItemBalance::find()->where([
            'party_no' => $this->nastel_party,
            'size_id' => $this->size_id
            ])->asArray()->orderBy(['id' => SORT_DESC])
            ->one();
        if(!empty($ib)){
            return number_format($ib['inventory'],0);
        }
        return null;
    }
    public function getRemainSliceQuantityByPandNItemBalance(){
        $ib = BichuvPrintAndPatternItemBalance::find()->where([
            'party_no' => $this->nastel_party,
            'size_id' => $this->size_id
        ])->asArray()->orderBy(['id' => SORT_DESC])
            ->one();

        if(!empty($ib)){
            return number_format($ib['inventory'],0);
        }
        return null;
    }


}
