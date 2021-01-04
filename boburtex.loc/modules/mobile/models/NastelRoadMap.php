<?php

namespace app\modules\mobile\models;

use Yii;

/**
 * This is the model class for table "nastel_road_map".
 *
 * @property int $id
 * @property string $nastel_no
 * @property int $mobile_process_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property MobileProcess $mobileProcess
 */
class NastelRoadMap extends \app\modules\mobile\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nastel_road_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile_process_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['nastel_no'], 'string', 'max' => 50],
            [['mobile_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileProcess::className(), 'targetAttribute' => ['mobile_process_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'mobile_process_id' => Yii::t('app', 'Mobile Process ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMobileProcess()
    {
        return $this->hasOne(MobileProcess::className(), ['id' => 'mobile_process_id']);
    }
}
