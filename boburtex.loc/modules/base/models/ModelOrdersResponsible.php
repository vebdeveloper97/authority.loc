<?php

namespace app\modules\base\models;

use app\models\Users;
use app\modules\hr\models\HrEmployee;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "model_orders_responsible".
 *
 * @property int $id
 * @property int $model_orders_id
 * @property int $users_id
 *
 * @property ModelOrders $modelOrders
 * @property Users $users
 */
class ModelOrdersResponsible extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_orders_responsible';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_id', 'users_id'], 'integer'],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['users_id' => 'id']],
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
            'users_id' => Yii::t('app', 'Users ID'),
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
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }
}
