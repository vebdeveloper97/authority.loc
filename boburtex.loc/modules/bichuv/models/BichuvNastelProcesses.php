<?php

namespace app\modules\bichuv\models;

use app\models\Users;
use Yii;

/**
 * This is the model class for table "bichuv_nastel_processes".
 *
 * @property int $id
 * @property string $nastel_no
 * @property int $bichuv_detail_type_id
 * @property int $bichuv_nastel_stol_id
 * @property int $action
 * @property int $user_started
 * @property string $started_time
 * @property int $user_ended
 * @property string $ended_time
 * @property int $type
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $bichuv_process_id
 * @property int $bichuv_given_roll_items_id
 *
 * @property BichuvGivenRollItemsSub[] $bichuvGivenRollItemsSubs
 * @property BichuvNastelDetailItems[] $bichuvNastelDetailItems
 * @property BichuvNastelProcessBrak[] $bichuvNastelProcessBraks
 * @property BichuvGivenRollItems $bichuvGivenRollItems
 * @property BichuvDetailTypes $bichuvDetailType
 * @property BichuvTables $bichuvNastelStol
 * @property BichuvProcesses $bichuvProcess
 * @property Users $userEnded
 * @property Users $userStarted
 */
class BichuvNastelProcesses extends BaseModel
{
    const ACTION_BEGIN   = 1;
    const ACTION_STOPPED = 2;
    const ACTION_END     = 3;
    const ACTION_REJECT  = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_nastel_processes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_detail_type_id', 'bichuv_nastel_stol_id', 'action', 'user_started', 'user_ended', 'type', 'status', 'created_by', 'created_at', 'updated_at', 'bichuv_process_id', 'bichuv_given_roll_items_id'], 'integer'],
            [['started_time', 'ended_time'], 'safe'],
            [['add_info'], 'string'],
            [['nastel_no'], 'string', 'max' => 50],
            [['bichuv_given_roll_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRollItems::className(), 'targetAttribute' => ['bichuv_given_roll_items_id' => 'id']],
            [['bichuv_detail_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDetailTypes::className(), 'targetAttribute' => ['bichuv_detail_type_id' => 'id']],
            [['bichuv_nastel_stol_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvTables::className(), 'targetAttribute' => ['bichuv_nastel_stol_id' => 'id']],
            [['bichuv_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvProcesses::className(), 'targetAttribute' => ['bichuv_process_id' => 'id']],
            [['user_ended'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_ended' => 'id']],
            [['user_started'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_started' => 'id']],
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->started_time = date('Y-m-d H:i:s', strtotime($this->started_time));
            $this->ended_time = date('Y-m-d H:i:s', strtotime($this->ended_time));
            return true;
        } else {
            return false;
        }
    }


    public function afterFind()
    {
        parent::afterFind();
        $this->started_time = date('d.m.Y H:i', strtotime($this->started_time));
        $this->ended_time = date('d.m.Y H:i', strtotime($this->ended_time));

    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'bichuv_detail_type_id' => Yii::t('app', 'Bichuv Detail Type ID'),
            'bichuv_nastel_stol_id' => Yii::t('app', 'Bichuv Nastel Stol ID'),
            'action' => Yii::t('app', 'Action'),
            'user_started' => Yii::t('app', 'User Started'),
            'started_time' => Yii::t('app', 'Started Time'),
            'user_ended' => Yii::t('app', 'User Ended'),
            'ended_time' => Yii::t('app', 'Ended Time'),
            'type' => Yii::t('app', 'Type'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bichuv_process_id' => Yii::t('app', 'Bichuv Process ID'),
            'bichuv_given_roll_items_id' => Yii::t('app', 'Bichuv Given Roll Items ID'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRollItemsSubs()
    {
        return $this->hasMany(BichuvGivenRollItemsSub::className(), ['bichuv_nastel_processes_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelDetailItems()
    {
        return $this->hasMany(BichuvNastelDetailItems::className(), ['bichuv_nastel_processes_id' => 'id']);
    }
    public function getNastelItemsList()
    {
        return BichuvNastelDetailItems::find()->where(['bichuv_nastel_detail_items.bichuv_nastel_processes_id'=>$this->id])->asArray()->all();
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelProcessBraks()
    {
        return $this->hasMany(BichuvNastelProcessBrak::className(), ['bichuv_nastel_processes_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRollItems()
    {
        return $this->hasOne(BichuvGivenRollItems::className(), ['id' => 'bichuv_given_roll_items_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDetailType()
    {
        return $this->hasOne(BichuvDetailTypes::className(), ['id' => 'bichuv_detail_type_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelStol()
    {
        return $this->hasOne(BichuvTables::className(), ['id' => 'bichuv_nastel_stol_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvProcess()
    {
        return $this->hasOne(BichuvProcesses::className(), ['id' => 'bichuv_process_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserEnded()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_ended']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserStarted()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_started']);
    }

    public static function getList($type=null)
    {
        $sql = "select  bnp.nastel_no,
                        bnp.started_time,
                        bnp.action,
                        bnp.bichuv_given_roll_items_id bgri_id
                from bichuv_nastel_processes bnp
                left join bichuv_detail_types bdt on bnp.bichuv_detail_type_id = bdt.id
                left join bichuv_tables bt on bnp.bichuv_nastel_stol_id = bt.id
                left join bichuv_tables_users btu on bt.id = btu.bichuv_tables_id
                left join bichuv_given_roll_items bgri on bdt.id = bgri.bichuv_detail_type_id
                where btu.users_id = %d group by bnp.id";
        if ($type){
            $sql .= " and bnp.bichuv_detail_type_id = {$type}";
        }
        $sql = sprintf($sql, Yii::$app->user->id);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public static function checkList($process,$type,$table)
    {
        $check = static::find()->where(['bichuv_process_id' => $process, 'bichuv_detail_type_id' => $type, 'bichuv_nastel_stol_id' => $table])->andWhere(['<', 'action', static::ACTION_END])->count();
        return $check;
    }
}
