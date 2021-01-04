<?php

namespace app\modules\bichuv\models;

use app\models\Users;
use app\modules\hr\models\HrEmployee;
use Yii;

/**
 * This is the model class for table "bichuv_tables_users".
 *
 * @property int $bichuv_tables_id
 * @property int $users_id
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvTables $bichuvTables
 * @property Users $users
 */
class BichuvTablesUsers extends BaseModel
{
    public $tables;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_tables_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_employee_id'], 'required'],
            [['bichuv_tables_id', 'hr_employee_id', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['bichuv_tables_id', 'hr_employee_id'], 'unique', 'targetAttribute' => ['bichuv_tables_id', 'users_id']],
            [['bichuv_tables_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvTables::className(), 'targetAttribute' => ['bichuv_tables_id' => 'id']],
            ['tables', 'safe'],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bichuv_tables_id' => Yii::t('app', 'Bichuv Tables ID'),
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
    public function getBichuvTables()
    {
        return $this->hasOne(BichuvTables::className(), ['id' => 'bichuv_tables_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'hr_employee_id']);
    }
}
