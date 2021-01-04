<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%model_orders_fs}}".
 *
 * @property int $id
 * @property int $model_orders_id
 * @property int $model_orders_items_id
 * @property string $add_info
 * @property string $who_sewed
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property ModelOrders $modelOrders
 * @property ModelOrdersItems $modelOrdersItems
 * @property ModelOrdersRelFsAttachments[] $modelOrdersRelFsAttachments
 */
class ModelOrdersFs extends BaseModel
{
    public $attachments_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_fs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_id', 'model_orders_items_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['add_info'], 'string'],
            [['attachments_id'], 'safe'],
            [['who_sewed'], 'string', 'max' => 100],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if(empty($this->status)){
                $this->status = $this::STATUS_ACTIVE;
            }
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_orders_id' => Yii::t('app', 'Model Orders ID'),
            'model_orders_items_id' => Yii::t('app', 'Model Orders Items ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'attachments_id' => Yii::t('app', 'Attachments ID'),
            'who_sewed' => Yii::t('app', 'Who Sewed'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrders()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'model_orders_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'model_orders_items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersRelFsAttachments()
    {
        return $this->hasMany(ModelOrdersRelFsAttachments::className(), ['model_orders_fs_id' => 'id']);
    }
}
