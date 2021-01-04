<?php

namespace app\modules\mechanical\models;

use Yii;

/**
 * This is the model class for table "spare_passport_items".
 *
 * @property int $id
 * @property int $sirhe_id
 * @property int $spare_control_id
 * @property string $interval_control_date
 * @property int $control_date_type
 * @property string $start_control_date
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property SpareItemRelHrEmployee $sirhe
 * @property SpareControlList $spareControl
 */
class SparePassportItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare_passport_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sirhe_id', 'spare_control_id', 'control_date_type', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['interval_control_date'], 'number'],
            [['start_control_date'], 'safe'],
            [['sirhe_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItemRelHrEmployee::className(), 'targetAttribute' => ['sirhe_id' => 'id']],
            [['spare_control_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareControlList::className(), 'targetAttribute' => ['spare_control_id' => 'id']],
            [['spare_control_id','interval_control_date','control_date_type'],'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sirhe_id' => Yii::t('app', 'Sirhe ID'),
            'spare_control_id' => Yii::t('app', 'Spare Control ID'),
            'interval_control_date' => Yii::t('app', 'Interval Control Date'),
            'control_date_type' => Yii::t('app', 'Control Date Type'),
            'start_control_date' => Yii::t('app', 'Start Control Date'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSirhe()
    {
        return $this->hasOne(SpareItemRelHrEmployee::className(), ['id' => 'sirhe_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareControl()
    {
        return $this->hasOne(SpareControlList::className(), ['id' => 'spare_control_id']);
    }
}
