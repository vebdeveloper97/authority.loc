<?php

namespace app\modules\tikuv\models;

use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\Size;
use app\modules\bichuv\models\Product;
use Yii;

/**
 * This is the model class for table "tikuv_doc_items".
 *
 * @property int $id
 * @property int $tikuv_doc_id
 * @property int $size_id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $quantity
 * @property string $doc_qty
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $model_id
 * @property int $fact_quantity
 * @property string $add_info
 *
 * @property Product $productModel
 * @property Size $size
 * @property ModelsList $modelList
 * @property ModelsVariations $modelVariation
 * @property TikuvDoc $tikuvDoc
 * @property int $work_weight [int(5)]
 * @property string $nastel_party_no [varchar(25)]
 * @property int $boyoqhona_model_id [smallint(6)]
 * @property int $model_var_id [int(11)]
 * @property int $is_combined [smallint(1)]
 */
class TikuvDocItems extends BaseModel
{
    const SCENARIO_ACCEPT_SLICE = 'accept-slice';

    // for tabular input
    public $size_name;

    public function scenarios()
    {
        $scenarios =  parent::scenarios();
        $scenarios[self::SCENARIO_ACCEPT_SLICE] = ['fact_quantity', 'add_info'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_doc_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tikuv_doc_id','is_combined','work_weight','size_id','boyoqhona_model_id','model_var_id', 'entity_id', 'entity_type', 'status', 'created_at', 'updated_at', 'created_by', 'model_id'], 'integer'],
            ['fact_quantity', 'integer'],
            [['quantity', 'doc_qty'], 'number'],
            ['nastel_party_no', 'string','max' => 25],
            ['add_info', 'string'],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
            [['boyoqhona_model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['boyoqhona_model_id' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
            [['tikuv_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvDoc::className(), 'targetAttribute' => ['tikuv_doc_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tikuv_doc_id' => Yii::t('app', 'Tikuv Doc ID'),
            'size_id' => Yii::t('app', 'Size ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'doc_qty' => Yii::t('app', 'Doc Qty'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'model_id' => Yii::t('app', 'Model ID'),
            'boyoqhona_model_id' => Yii::t('app', 'Boyoqhona Model ID'),
            'model_var_id' => Yii::t('app', 'Model Var ID'),
            'add_info' => Yii::t('app', 'Add info'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductModel()
    {
        return $this->hasOne(Product::className(), ['id' => 'boyoqhona_model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVariation()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_var_id']);
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
    public function getTikuvDoc()
    {
        return $this->hasOne(TikuvDoc::className(), ['id' => 'tikuv_doc_id']);
    }
}
