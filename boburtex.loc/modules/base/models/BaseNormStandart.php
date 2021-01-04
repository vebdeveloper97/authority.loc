<?php

namespace app\modules\base\models;

use app\modules\mobile\models\MobileProcess;
use app\modules\toquv\models\SortName;
use Yii;

/**
 * This is the model class for table "base_norm_standart".
 *
 * @property int $id
 * @property int $base_standart_id
 * @property int $sort_id
 * @property int $mobile_process_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseStandart $baseStandart
 * @property MobileProcess $mobileProcess
 * @property SortName $sort
 * @property BaseNormStandartItems[] $baseNormStandartItems
 */
class BaseNormStandart extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_norm_standart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['base_standart_id', 'sort_id', 'mobile_process_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['base_standart_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseStandart::class, 'targetAttribute' => ['base_standart_id' => 'id']],
            [['mobile_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileProcess::class, 'targetAttribute' => ['mobile_process_id' => 'id']],
            [['sort_id'], 'exist', 'skipOnError' => true, 'targetClass' => SortName::class, 'targetAttribute' => ['sort_id' => 'id']],
            [['base_standart_id','sort_id','mobile_process_id'],'required'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'base_standart_id' => Yii::t('app', 'Base Standart ID'),
            'sort_id' => Yii::t('app', 'Sort Name ID'),
            'mobile_process_id' => Yii::t('app', 'Process'),
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
    public function getBaseStandart()
    {
        return $this->hasOne(BaseStandart::class, ['id' => 'base_standart_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMobileProcess()
    {
        return $this->hasOne(MobileProcess::class, ['id' => 'mobile_process_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSort()
    {
        return $this->hasOne(SortName::class, ['id' => 'sort_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseNormStandartItems()
    {
        return $this->hasMany(BaseNormStandartItems::class, ['norm_standart_id' => 'id']);
    }
}
