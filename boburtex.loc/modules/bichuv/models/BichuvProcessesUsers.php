<?php

namespace app\modules\bichuv\models;

use app\models\Users;
use Yii;

/**
 * This is the model class for table "bichuv_processes_users".
 *
 * @property int $bichuv_processes_id
 * @property int $users_id
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvProcesses $bichuvProcesses
 * @property Users $users
 */
class BichuvProcessesUsers extends BaseModel
{
    public $tables;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_processes_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_processes_id', 'users_id', 'tables'], 'required'],
            [['bichuv_processes_id', 'users_id', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['bichuv_processes_id', 'users_id'], 'unique', 'targetAttribute' => ['bichuv_processes_id', 'users_id']],
            ['tables', 'safe'],
            [['bichuv_processes_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvProcesses::className(), 'targetAttribute' => ['bichuv_processes_id' => 'id']],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bichuv_processes_id' => Yii::t('app', 'Bichuv Processes ID'),
            'users_id' => Yii::t('app', 'Users ID'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvProcesses()
    {
        return $this->hasOne(BichuvProcesses::className(), ['id' => 'bichuv_processes_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }
}
