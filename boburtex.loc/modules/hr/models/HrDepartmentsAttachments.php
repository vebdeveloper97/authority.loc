<?php

namespace app\modules\hr\models;

use app\modules\base\models\Attachments;
use Yii;

/**
 * This is the model class for table "hr_departments_attachments".
 *
 * @property int $hr_departments_id
 * @property int $attachments_id
 * @property int $type
 *
 * @property Attachments $attachments
 * @property HrDepartments $hrDepartments
 */
class HrDepartmentsAttachments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_departments_attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_departments_id', 'attachments_id'], 'required'],
            [['hr_departments_id', 'attachments_id', 'type'], 'integer'],
            [['hr_departments_id', 'attachments_id'], 'unique', 'targetAttribute' => ['hr_departments_id', 'attachments_id']],
            [['attachments_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attachments::className(), 'targetAttribute' => ['attachments_id' => 'id']],
            [['hr_departments_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_departments_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hr_departments_id' => Yii::t('app', 'Hr Departments ID'),
            'attachments_id' => Yii::t('app', 'Attachments ID'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasOne(Attachments::className(), ['id' => 'attachments_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartments()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'hr_departments_id']);
    }
}
