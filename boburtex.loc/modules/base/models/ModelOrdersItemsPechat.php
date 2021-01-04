<?php

namespace app\modules\base\models;

use Yii;
use yii\db\ActiveQuery;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%model_orders_items_pechat}}".
 *
 * @property int $id
 * @property int $model_orders_id
 * @property int $model_orders_items_id
 * @property string $whom
 * @property string $width
 * @property string $height
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property ModelOrders $modelOrders
 * @property ModelOrdersItems $modelOrdersItems
 */
class ModelOrdersItemsPechat extends BaseModel
{
    /** @var UploadedFile */
    public $_file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_items_pechat}}';
    }
    public $files;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_id', 'model_orders_items_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'attachment_id'], 'integer'],
            [['whom', 'subject', 'width', 'height'], 'string'],
            [['name', 'url'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 100],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            ['_file', 'safe'],
            [['attachment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attachments::className(), 'targetAttribute' => ['attachment_id' => 'id']],
            [['name'], 'string'],
            ['attachment_id', 'required', 'when' => function($model){
                return !empty($model->name);
            }, 'message' => 'Pechat faylni yuklash majburiy']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_orders_id' => 'Model Orders ID',
            'model_orders_items_id' => 'Model Orders Items ID',
            'whom' => 'Whom',
            'width' => 'Width',
            'height' => 'Height',
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
     * */
    public function getAttachment()
    {
        return $this->hasOne(Attachments::className(), ['id' => 'attachment_id']);
    }
}
