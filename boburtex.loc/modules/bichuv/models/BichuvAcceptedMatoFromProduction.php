<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "bichuv_accepted_mato_from_production".
 *
 * @property int $id
 * @property int $bichuv_given_roll_id
 * @property string $quantity
 * @property int $type
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property BichuvGivenRolls $bichuvGivenRoll
 * @property int $entity_id [int(11)]
 */
class BichuvAcceptedMatoFromProduction extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_accepted_mato_from_production';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_given_roll_id', 'entity_id', 'type', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['quantity'], 'number'],
            [['nastel_no'],'string'],
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
            'bichuv_given_roll_id' => Yii::t('app', 'Bichuv Given Roll ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRoll()
    {
        return $this->hasOne(BichuvGivenRolls::className(), ['id' => 'bichuv_given_roll_id']);
    }
}
