<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_rfid_key".
 *
 * @property int $id
 * @property int $user_id
 * @property string $rfid_key
 */
class UserRfidKey extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_rfid_key';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'rfid_key'], 'required'],
            [['user_id'], 'integer'],
            [['rfid_key'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'rfid_key' => 'Rfid Key',
        ];
    }
}
