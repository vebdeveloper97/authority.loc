<?php

namespace app\modules\toquv\models;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "toquv_department_musteri_address".
 *
 * @property int $id
 * @property int $toquv_department_id
 * @property string $physical_location
 * @property string $legal_location
 * @property string $email
 * @property string $phone
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Users $createdBy
 * @property ToquvDepartments $toquvDepartment
 */
class ToquvDepartmentMusteriAddress extends \app\modules\toquv\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_department_musteri_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'physical_location', 'physical_location', 'legal_location'], 'required'],
            [['email'], 'email'],
            [['toquv_department_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['physical_location', 'legal_location', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['toquv_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['toquv_department_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_department_id' => Yii::t('app', 'Toquv Department ID'),
            'physical_location' => Yii::t('app', 'Physical Location'),
            'legal_location' => Yii::t('app', 'Legal Location'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'toquv_department_id']);
    }
}
