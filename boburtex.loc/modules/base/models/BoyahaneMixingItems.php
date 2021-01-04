<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "boyahane_mixing_items".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $lot
 * @property int $wh_document_id
 * @property int $department_id
 * @property int $dep_section
 * @property int $dep_area
 * @property string $wh_price
 * @property int $wh_pb_id
 * @property int $package_type
 * @property string $quantity
 * @property int $unit_id
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property WhItems $entity
 * @property Unit $unit
 * @property WhDocument $whDocument
 */
class BoyahaneMixingItems extends \app\modules\base\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'boyahane_mixing_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_type', 'wh_document_id', 'department_id', 'dep_section', 'dep_area', 'wh_pb_id', 'package_type', 'unit_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['wh_price', 'quantity'], 'number'],
            [['lot'], 'string', 'max' => 50],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => WhItems::className(), 'targetAttribute' => ['entity_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
            [['wh_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => WhDocument::className(), 'targetAttribute' => ['wh_document_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'lot' => Yii::t('app', 'Lot'),
            'wh_document_id' => Yii::t('app', 'Wh Document ID'),
            'department_id' => Yii::t('app', 'Department ID'),
            'dep_section' => Yii::t('app', 'Dep Section'),
            'dep_area' => Yii::t('app', 'Dep Area'),
            'wh_price' => Yii::t('app', 'Wh Price'),
            'wh_pb_id' => Yii::t('app', 'Wh Pb ID'),
            'package_type' => Yii::t('app', 'Package Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(WhItems::className(), ['id' => 'entity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhDocument()
    {
        return $this->hasOne(WhDocument::className(), ['id' => 'wh_document_id']);
    }
}
