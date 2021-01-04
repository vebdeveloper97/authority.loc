<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "bichuv_beka".
 *
 * @property int $id
 * @property int $bichuv_doc_id
 * @property string $weight
 * @property int $type
 * @property int $entity_id
 * @property string $nastel_no
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvDoc $bichuvDoc
 * @property string $party_no [varchar(50)]
 * @property string $musteri_party_no [varchar(50)]
 * @property int $roll_count [int(2)]
 * @property int $model_id [smallint(6)]
 * @property int $bichuv_given_roll_id [int(11)]
 */
class BichuvBeka extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_beka';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_doc_id', 'bichuv_given_roll_id','model_id', 'type','roll_count','entity_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['weight'], 'number'],
            [['nastel_no','party_no','musteri_party_no'], 'string', 'max' => 20],
            [['bichuv_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::className(), 'targetAttribute' => ['bichuv_doc_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['bichuv_given_roll_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRolls::className(), 'targetAttribute' => ['bichuv_given_roll_id' => 'id']],
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
            'weight' => Yii::t('app', 'Weight'),
            'type' => Yii::t('app', 'Type'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
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
    public function getBichuvGivenRoll()
    {
        return $this->hasOne(BichuvGivenRolls::className(), ['id' => 'bichuv_given_roll_id']);
    }

    public function getRemainRm(){
        $sql = "select bgri.quantity,
                       (select SUM(bamfp.quantity) from bichuv_accepted_mato_from_production bamfp where bamfp.bichuv_given_roll_id = bgri.bichuv_given_roll_id)  as accepted
                from bichuv_slice_items bsi
                inner join bichuv_given_roll_items bgri on bgri.bichuv_given_roll_id = bsi.bichuv_given_roll_id
                WHERE bsi.bichuv_doc_id = :docId AND bgri.entity_id = :entityId
                GROUP BY bgri.entity_id;";
        $res = Yii::$app->db->createCommand($sql)->bindValues([
                'docId' => $this->bichuv_doc_id,
                'entityId' => $this->entity_id
            ])->queryOne();
        if(!empty($res)){
            if(!empty($res['accepted']) && $res['accepted'] > 0){
                return $res['quantity'] - $res['accepted'];
            }
            return $res['quantity'];
        }
        return 0;
    }
}
