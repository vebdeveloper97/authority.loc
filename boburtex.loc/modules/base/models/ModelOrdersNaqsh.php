<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "model_orders_naqsh".
 *
 * @property int $id
 * @property string $name
 * @property int $attachment_id
 * @property string $add_info
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Attachments $attachment
 */
class ModelOrdersNaqsh extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_orders_naqsh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'add_info'], 'string'],
            [['attachment_id', 'model_orders_items_id', 'model_orders_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['width', 'height'], 'string'],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
            [['attachment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attachments::className(), 'targetAttribute' => ['attachment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'attachment_id' => Yii::t('app', 'Attachment ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachment()
    {
        return $this->hasOne(Attachments::className(), ['id' => 'attachment_id']);
    }
}
