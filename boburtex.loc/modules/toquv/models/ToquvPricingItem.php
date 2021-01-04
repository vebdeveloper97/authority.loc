<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_pricing_item".
 *
 * @property int $id
 * @property int $doc_id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $price
 * @property int $pb_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvPricingDoc $doc
 * @property ToquvDocumentItems[] $toquvDocumentItems
 * @property PulBirligi $pb
 */
class ToquvPricingItem extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_pricing_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_id', 'entity_id', 'entity_type', 'pb_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvPricingDoc::className(), 'targetAttribute' => ['doc_id' => 'id']],
            [['pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['pb_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'price' => Yii::t('app', 'Price'),
            'pb_id' => Yii::t('app', 'Pb ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoc()
    {
        return $this->hasOne(ToquvPricingDoc::className(), ['id' => 'doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocumentItems()
    {
        return $this->hasMany(ToquvDocumentItems::className(), ['price_item_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'pb_id']);
    }
    public function getIpName($id = null){
        if(!empty($id)){
            $ip = ToquvIp::find()->with(['ne','thread','color'])->where(['id' => $id, 'status' => ToquvIp::STATUS_ACTIVE])->asArray()->one();
            if(!empty($ip)){
                return $ip['name'].' - '.$ip['ne']['name'].' - '.$ip['thread']['name'].' - '.$ip['color']['name'];
            }
        }
        return false;
    }
    public function getMatoName($id = null){
        if(!empty($id)){
            $mato = ToquvRawMaterials::find()->with(['rawMaterialType'])->where(['id' => $id, 'status' => ToquvRawMaterials::STATUS_ACTIVE])->asArray()->one();
            if(!empty($mato)){
                return $mato['name'].' - '.$mato['rawMaterialType']['name'];
            }
        }
        return false;
    }
    /**
     * @return array
     */
    public function getCurrentPrice(){
        $result = [
            'value'    => 0,
            'currency' => '$'
        ];
        if(!empty($this->price) && (int)$this->price > 0){
            $result = [
                'value' => $this->price,
                'currency' => $this->pb->name,
            ];
        }else{
            $result['value'] = $this->price;
        }
        return $result;
    }
}
