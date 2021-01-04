<?php

namespace app\models;

use app\components\OurCustomBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "users_info".
 *
 * @property int $users_id
 * @property string $fio
 * @property string $smena
 * @property string $tabel
 * @property string $lavozim
 * @property int $razryad
 * @property string $tel
 * @property string $adress
 * @property int $type
 * @property string $add_info
 * @property int $rfid_key
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Users $users
 */
class UsersInfo extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE  = 'create';
    const SCENARIO_UPDATE  = 'update';
    const SCENARIO_DELETE  = 'delete';
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_info';
    }
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_CREATE => ['razryad', 'type', 'rfid_key', 'status', 'created_by', 'created_at', 'updated_at','add_info','fio', 'adress','smena','tabel','lavozim','tel'],
            self::SCENARIO_UPDATE => ['users_id', 'razryad', 'type', 'rfid_key', 'status', 'created_by', 'created_at', 'updated_at','add_info','fio', 'adress','smena','tabel','lavozim','tel']
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['users_id'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['rfid_key','tabel'], 'unique'],
            [['users_id', 'razryad', 'type', 'rfid_key', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info'], 'string'],
            [['fio', 'adress'], 'string', 'max' => 70],
            [['smena'], 'string', 'max' => 10],
            [['tabel'], 'string', 'max' => 20],
            [['lavozim'], 'string', 'max' => 40],
            [['tel'], 'string', 'max' => 15],
            [['users_id'], 'unique'],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'users_id' => Yii::t('app', 'Users ID'),
            'fio' => Yii::t('app', 'Fio'),
            'smena' => Yii::t('app', 'Smena'),
            'tabel' => Yii::t('app', 'Tabel'),
            'lavozim' => Yii::t('app', 'Lavozim'),
            'razryad' => Yii::t('app', 'Razryad'),
            'tel' => Yii::t('app', 'Tel'),
            'adress' => Yii::t('app', 'Adress'),
            'type' => Yii::t('app', 'Type'),
            'add_info' => Yii::t('app', 'Add Info'),
            'rfid_key' => Yii::t('app', 'Rfid Key'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }
}
