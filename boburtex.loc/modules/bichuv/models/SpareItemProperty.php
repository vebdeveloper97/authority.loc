<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "spare_item_property".
 *
 * @property int $id
 * @property int $spare_item_id
 * @property int $spare_item_property_list_id
 * @property string $value
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property SpareItem $spareItem
 * @property SpareItemPropertyList $spareItemPropertyList
 */
class SpareItemProperty extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare_item_property';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['spare_item_id', 'spare_item_property_list_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['spare_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItem::className(), 'targetAttribute' => ['spare_item_id' => 'id']],
            [['spare_item_property_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItemPropertyList::className(), 'targetAttribute' => ['spare_item_property_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'spare_item_id' => 'Spare Item ID',
            'spare_item_property_list_id' => 'Spare Item Property List ID',
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
    public function getSpareItem()
    {
        return $this->hasOne(SpareItem::className(), ['id' => 'spare_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItemPropertyList()
    {
        return $this->hasOne(SpareItemPropertyList::className(), ['id' => 'spare_item_property_list_id']);
    }
}
