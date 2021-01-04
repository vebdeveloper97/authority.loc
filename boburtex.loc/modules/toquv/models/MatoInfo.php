<?php

namespace app\modules\toquv\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%mato_info}}".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $entity_type
 * @property int $pus_fine_id
 * @property string $thread_length
 * @property string $finish_en
 * @property string $finish_gramaj
 * @property int $type_weaving
 * @property int $toquv_rm_order_id
 * @property int $toquv_instruction_rm_id
 * @property int $toquv_instruction_id
 * @property int $musteri_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvRawMaterials $entity
 * @property ToquvPusFine $pusFine
 * @property ToquvInstructionRm $toquvInstructionRm
 * @property ToquvRmOrder $toquvRmOrder
 * @property int $model_musteri_id [int(11)]
 * @property int $color_pantone_id [int(11)]
 * @property mixed $musteri
 * @property mixed $toquvOrders
 * @property mixed $modelMusteri
 * @property mixed $typeWeaving
 * @property string $model_code [varchar(50)]
 */
class MatoInfo extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mato_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_type', 'pus_fine_id', 'type_weaving', 'musteri_id', 'thread_length', 'finish_en', 'finish_gramaj'], 'required', 'when' => function($model){
                return $model->entity_type == ToquvDocuments::ENTITY_TYPE_MATO;
            } ],
            [['entity_id', 'entity_type', 'pus_fine_id', 'musteri_id'], 'required', 'when' => function($model){
                return $model->entity_type == ToquvDocuments::ENTITY_TYPE_ACS;
            } ],
            [['entity_id', 'entity_type', 'pus_fine_id', 'type_weaving', 'toquv_rm_order_id', 'toquv_instruction_rm_id', 'toquv_instruction_id', 'musteri_id', 'status', 'created_by', 'created_at', 'updated_at', 'model_musteri_id', 'color_pantone_id'], 'integer'],
            [['thread_length', 'finish_en', 'finish_gramaj', 'model_code'], 'string', 'max' => 50],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['entity_id' => 'id']],
            [['pus_fine_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvPusFine::className(), 'targetAttribute' => ['pus_fine_id' => 'id']],
            [['toquv_instruction_rm_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvInstructionRm::className(), 'targetAttribute' => ['toquv_instruction_rm_id' => 'id']],
            [['toquv_rm_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRmOrder::className(), 'targetAttribute' => ['toquv_rm_order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity_id' => Yii::t('app', 'Mato'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'pus_fine_id' => Yii::t('app', 'Pus/Fine'),
            'thread_length' => Yii::t('app', 'Thread Length'),
            'finish_en' => Yii::t('app', 'Finish En'),
            'finish_gramaj' => Yii::t('app', 'Finish Gramaj'),
            'type_weaving' => Yii::t('app', 'Type Weaving'),
            'toquv_rm_order_id' => Yii::t('app', 'Toquv Rm Order ID'),
            'toquv_instruction_rm_id' => Yii::t('app', 'Toquv Instruction Rm ID'),
            'toquv_instruction_id' => Yii::t('app', 'Toquv Instruction ID'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'model_musteri_id' => Yii::t('app', 'Model buyurtmachisi'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'entity_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPusFine()
    {
        return $this->hasOne(ToquvPusFine::className(), ['id' => 'pus_fine_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToquvInstructionRm()
    {
        return $this->hasOne(ToquvInstructionRm::className(), ['id' => 'toquv_instruction_rm_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToquvRmOrder()
    {
        return $this->hasOne(ToquvRmOrder::className(), ['id' => 'toquv_rm_order_id']);
    }
    public function getTypeWeaving()
    {
        return $this->hasOne(MaterialType::className(), ['id' => 'type_weaving']);
    }
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }
    public function getToquvOrders()
    {
        return $this->toquvRmOrder->toquvOrders;
    }
    public function getModelMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'model_musteri_id']);
    }
}
