<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "hr_organization_info".
 *
 * @property int $department_id
 * @property string $tel
 * @property string $address
 * @property string $add_info
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property HrDepartments $department
 */
class HrOrganizationInfo extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_organization_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address', 'add_info'], 'string'],
            [['created_at', 'updated_at', 'created_by'], 'integer'],
            [['tel'], 'string', 'max' => 255],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'department_id' => Yii::t('app', 'Department ID'),
            'tel' => Yii::t('app', 'Tel'),
            'address' => Yii::t('app', 'Address'),
            'add_info' => Yii::t('app', 'Add Info'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'department_id']);
    }
}
