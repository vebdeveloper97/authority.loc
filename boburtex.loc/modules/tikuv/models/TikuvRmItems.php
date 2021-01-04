<?php

namespace app\modules\tikuv\models;

use app\modules\bichuv\models\Product;
use Yii;

/**
 * This is the model class for table "tikuv_rm_items".
 *
 * @property int $id
 * @property int $tikuv_doc_id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $quantity
 * @property string $document_quantity
 * @property int $roll_count
 * @property int $is_accessory
 * @property string $party_no
 * @property string $musteri_party_no
 * @property string $nastel_no
 * @property int $model_id
 * @property int $is_own
 * @property int $status
 * @property string $add_info
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property Product $productModel
 * @property TikuvDoc $tikuvDoc
 */
class TikuvRmItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_rm_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tikuv_doc_id', 'entity_id', 'entity_type', 'roll_count', 'is_accessory', 'model_id', 'is_own', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['quantity', 'document_quantity'], 'number'],
            [['add_info'], 'string'],
            [['party_no', 'musteri_party_no', 'nastel_no'], 'string', 'max' => 25],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['model_id' => 'id']],
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
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'document_quantity' => Yii::t('app', 'Document Quantity'),
            'roll_count' => Yii::t('app', 'Roll Count'),
            'is_accessory' => Yii::t('app', 'Is Accessory'),
            'party_no' => Yii::t('app', 'Party No'),
            'musteri_party_no' => Yii::t('app', 'Musteri Party No'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'model_id' => Yii::t('app', 'Model ID'),
            'is_own' => Yii::t('app', 'Is Own'),
            'status' => Yii::t('app', 'Status'),
            'add_info' => Yii::t('app', 'Add Info'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
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
    public function getTikuvDoc()
    {
        return $this->hasOne(TikuvDoc::className(), ['id' => 'tikuv_doc_id']);
    }
}
