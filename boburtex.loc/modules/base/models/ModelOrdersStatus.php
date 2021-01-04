<?php

namespace app\modules\base\models;

use app\components\OurCustomBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%model_orders_status}}".
 *
 * @property int $id
 * @property int $model_orders_id
 * @property int $order_status
 * @property string $add_info
 * @property int $status
 * @property int $type
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property ModelOrders $modelOrders
 */
class ModelOrdersStatus extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE             = 1;
    const STATUS_INACTIVE           = 2;
    const STATUS_SAVED              = 3;
    const STATUS_PLANNED_AKS        = 4;
    const STATUS_PLANNED_TOQUV      = 5;
    const STATUS_PLANNED_TOQUV_AKS  = 6;
    const STATUS_SEND_TOQUV         = 7;
    const STATUS_SEND_TOQUV_AKS     = 8;
    const STATUS_CHANGED_MATO       = 77;
    const STATUS_CHANGED_AKS        = 88;
    const STATUS_COMBINED           = 99;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_status}}';
    }
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Deleted'),
            self::STATUS_SAVED => Yii::t('app','Saved'),
            self::STATUS_PLANNED_AKS => Yii::t('app','Aksessuar planlangan'),
            self::STATUS_PLANNED_TOQUV => Yii::t('app','Planned'),
            self::STATUS_PLANNED_TOQUV_AKS => Yii::t('app',"To'quv aksessuar planlangan"),
            self::STATUS_SEND_TOQUV => Yii::t('app','Tasdiqlangan'),
            self::STATUS_SEND_TOQUV_AKS => Yii::t('app',"To'quv aksessuar tasdiqlangan"),
            self::STATUS_COMBINED => Yii::t('app',"Birlashtirilgan"),
            self::STATUS_CHANGED_MATO => Yii::t('app',"Matolar o'zgartirilgan"),
            self::STATUS_CHANGED_AKS => Yii::t('app',"To'quv aksessuarlar o'zgartirilgan"),
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_id', 'order_status', 'status', 'type', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['add_info'], 'string'],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_orders_id' => Yii::t('app', 'Model Orders ID'),
            'order_status' => Yii::t('app', 'Order Status'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'type' => Yii::t('app', 'Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrders()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'model_orders_id']);
    }
}
