<?php

namespace app\modules\toquv\models;

use app\models\Users;
use Yii;

/**
 * This is the model class for table "toquv_makine_users".
 *
 * @property int $toquv_makine_id
 * @property int $users_id
 *
 * @property ToquvMakine $toquvMakine
 * @property Users $users
 */
class ToquvMakineUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_makine_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_makine_id', 'users_id'], 'required'],
            [['toquv_makine_id', 'users_id'], 'integer'],
            [['toquv_makine_id', 'users_id'], 'unique', 'targetAttribute' => ['toquv_makine_id', 'users_id']],
            [['toquv_makine_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvMakine::className(), 'targetAttribute' => ['toquv_makine_id' => 'id']],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'toquv_makine_id' => Yii::t('app', 'Toquv Makine ID'),
            'users_id' => Yii::t('app', 'Users ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvMakine()
    {
        return $this->hasOne(ToquvMakine::className(), ['id' => 'toquv_makine_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }

    public static function checkMakine($user_id,$toquv_makine_id)
    {
        $toquv_makine_users = ToquvMakineUsers::findOne([
            'users_id' => $user_id,
            'toquv_makine_id' => $toquv_makine_id
        ]);
        if($toquv_makine_users){
            return true;
        }
        return false;
    }
}
