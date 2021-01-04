<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "{{%bichuv_acs_properties}}".
 *
 * @property int $id
 * @property int $bichuv_acs_id
 * @property int $bichuv_acs_property_list_id
 * @property string $value
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BichuvAcs $bichuvAcs
 * @property BichuvAcsPropertyList $bichuvAcsPropertyList
 */
class BichuvAcsProperties extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bichuv_acs_properties}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_acs_id', 'bichuv_acs_property_list_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['bichuv_acs_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvAcs::className(), 'targetAttribute' => ['bichuv_acs_id' => 'id']],
            [['bichuv_acs_property_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvAcsPropertyList::className(), 'targetAttribute' => ['bichuv_acs_property_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bichuv_acs_id' => 'Bichuv Acs ID',
            'bichuv_acs_property_list_id' => 'Bichuv Acs Property List ID',
            'value' => 'Value',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvAcs()
    {
        return $this->hasOne(BichuvAcs::className(), ['id' => 'bichuv_acs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvAcsPropertyList()
    {
        return $this->hasOne(BichuvAcsPropertyList::className(), ['id' => 'bichuv_acs_property_list_id']);
    }
}
