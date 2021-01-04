<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_raw_material_order".
 *
 * @property int $id
 * @property int $order_id
 * @property int $mato_id
 * @property string $comment
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property RawMaterials $mato
 * @property ToquvOrders $order
 */
class ToquvRawMaterialOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_raw_material_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'mato_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['comment'], 'string'],
            [['mato_id'], 'exist', 'skipOnError' => true, 'targetClass' => RawMaterials::className(), 'targetAttribute' => ['mato_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvOrders::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'mato_id' => Yii::t('app', 'Mato ID'),
            'comment' => Yii::t('app', 'Comment'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMato()
    {
        return $this->hasOne(RawMaterials::className(), ['id' => 'mato_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(ToquvOrders::className(), ['id' => 'order_id']);
    }
}
