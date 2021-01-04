<?php

namespace app\modules\toquv\models;

use app\models\Users;
use Yii;

/**
 * This is the model class for table "toquv_orders_responsible".
 *
 * @property int $id
 * @property int $toquv_orders_id
 * @property int $users_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvOrders $toquvOrders
 * @property Users $users
 */
class ToquvOrdersResponsible extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_orders_responsible';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_orders_id', 'users_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['toquv_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvOrders::className(), 'targetAttribute' => ['toquv_orders_id' => 'id']],
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
            'toquv_orders_id' => Yii::t('app', 'Toquv Orders ID'),
            'users_id' => Yii::t('app', 'Users ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvOrders()
    {
        return $this->hasOne(ToquvOrders::className(), ['id' => 'toquv_orders_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }
    public function getUser()
    {
        return Users::findOne($this->users_id);
    }
}
