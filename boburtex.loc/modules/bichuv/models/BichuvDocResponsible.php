<?php

namespace app\modules\bichuv\models;

use app\components\OurCustomBehavior;
use app\models\Users;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%bichuv_doc_responsible}}".
 *
 * @property int $id
 * @property int $users_id
 * @property int $bichuv_doc_id
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $type
 * @property int $bichuv_mato_orders_id
 * @property int $bichuv_mato_order_items_id
 *
 * @property BichuvDoc $bichuvDoc
 * @property BichuvMatoOrderItems $bichuvMatoOrderItems
 * @property Users $users
 */
class BichuvDocResponsible extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bichuv_doc_responsible}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['users_id', 'add_info'], 'required'],
            [['users_id', 'bichuv_doc_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'type', 'bichuv_mato_orders_id', 'bichuv_mato_order_items_id'], 'integer'],
            [['add_info'], 'string'],
            [['bichuv_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::className(), 'targetAttribute' => ['bichuv_doc_id' => 'id']],
            [['bichuv_mato_order_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvMatoOrderItems::className(), 'targetAttribute' => ['bichuv_mato_order_items_id' => 'id']],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'users_id' => Yii::t('app', 'Javobgar shaxs'),
            'bichuv_doc_id' => Yii::t('app', 'Bichuv Doc'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'type' => Yii::t('app', 'Type'),
            'bichuv_mato_orders_id' => Yii::t('app', 'Bichuv Mato Orders ID'),
            'bichuv_mato_order_items_id' => Yii::t('app', 'Bichuv Mato Order Items ID'),
        ];
    }

    /**
     * @return array
     */
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
    public function getBichuvDoc()
    {
        return $this->hasOne(BichuvDoc::className(), ['id' => 'bichuv_doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvMatoOrderItems()
    {
        return $this->hasOne(BichuvMatoOrderItems::className(), ['id' => 'bichuv_mato_order_items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }
}
