<?php

namespace app\modules\bichuv\models;

use app\modules\base\models\SizeCollections;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_nastel_details".
 *
 * @property int $id
 * @property int $bichuv_doc_id
 * @property int $detail_type_id
 * @property string $nastel_no
 * @property int $count
 * @property string $weight
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $type
 * @property int $required_count
 * @property string $required_weight
 * @property int $entity_id
 * @property int $doc_id
 * @property int $entity_type
 * @property int $bichuv_given_roll_items_id
 *
 * @property BichuvDoc $bichuvDoc
 * @property Product $productModel
 * @property SizeCollections $sizeCollection
 * @property BichuvNastelDetailItems[] $bichuvNastelDetailItems
 * @property BichuvGivenRolls $bichuvGivenRoll
 * @property BichuvGivenRollItems $bichuvGivenRollItems
 * @property BichuvDetailTypes $detailType
 * @property int $model_id [smallint(6)]
 * @property int $nastel_count [int(11)]
 * @property int $bichuv_given_roll_id [int(11)]
 * @property int $size_collection_id [int(11)]
 */
class BichuvNastelDetails extends BaseModel
{
    public $accs_doc_id;
    public $token;
    public $acs_entity_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_nastel_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_doc_id','size_collection_id','bichuv_given_roll_id', 'model_id', 'nastel_count', 'detail_type_id', 'count', 'status', 'created_by', 'created_at', 'updated_at', 'type', 'required_count', 'entity_id', 'doc_id', 'entity_type', 'bichuv_given_roll_items_id'], 'integer'],
            [['weight', 'required_weight'], 'number'],
            [['nastel_no'], 'string', 'max' => 20],
            [['bichuv_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::className(), 'targetAttribute' => ['bichuv_doc_id' => 'id']],
            [['detail_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDetailTypes::className(), 'targetAttribute' => ['detail_type_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['bichuv_given_roll_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRolls::className(), 'targetAttribute' => ['bichuv_given_roll_id' => 'id']],
            [['bichuv_given_roll_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRollItems::className(), 'targetAttribute' => ['bichuv_given_roll_items_id' => 'id']],
            [['size_collection_id'], 'exist', 'skipOnError' => true, 'targetClass' => SizeCollections::className(), 'targetAttribute' => ['size_collection_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bichuv_doc_id' => Yii::t('app', 'Bichuv Doc ID'),
            'detail_type_id' => Yii::t('app', 'Detail Type ID'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'nastel_count' => Yii::t('app', 'Nastel Count'),
            'count' => Yii::t('app', 'Count'),
            'weight' => Yii::t('app', 'Weight'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'type' => Yii::t('app', 'Type'),
            'required_count' => Yii::t('app', 'Required Count'),
            'required_weight' => Yii::t('app', 'Required Weight'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'bichuv_given_roll_items_id' => Yii::t('app', 'Bichuv Given Roll Items ID'),
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
    public function getSizeCollection()
    {
        return $this->hasOne(SizeCollections::className(), ['id' => 'size_collection_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailType()
    {
        return $this->hasOne(BichuvDetailTypes::className(), ['id' => 'detail_type_id']);
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
    public function getBichuvGivenRoll()
    {
        return $this->hasOne(BichuvGivenRolls::className(), ['id' => 'bichuv_given_roll_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRollItems()
    {
        return $this->hasOne(BichuvGivenRollItems::className(), ['id' => 'bichuv_given_roll_items_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelDetailItems()
    {
        return $this->hasMany(BichuvNastelDetailItems::className(), ['bichuv_nastel_detail_id' => 'id']);
    }

    public function getDetailName()
    {
        if($this->entity_type == 2){
            return BichuvDoc::getAccessories($this->entity_id);
        }else{
           return $this->getMatoName($this->entity_id);
        }
    }

    /**
     * @return array
     */
    public function getSizeCollectionList(){
        $sc = SizeCollections::find()->select(['id','name'])->asArray()->all();
        return ArrayHelper::map($sc,'id','name');
    }



}
