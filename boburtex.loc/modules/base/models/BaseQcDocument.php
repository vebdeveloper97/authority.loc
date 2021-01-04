<?php

namespace app\modules\base\models;

use app\modules\mobile\models\MobileProcessProduction;
use app\modules\toquv\models\SortName;
use Yii;

/**
 * This is the model class for table "base_qc_document".
 *
 * @property int $id
 * @property string $nastel_no
 * @property int $mobile_process_production_id
 * @property int $norm_standart_id
 * @property string $reg_date
 * @property int $sort_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseQcAttachment[] $baseQcAttachments
 * @property MobileProcessProduction $mobileProcessProduction
 * @property BaseNormStandart $normStandart
 * @property SortName $sort
 * @property BaseQcDocumentItems[] $baseQcDocumentItems
 */
class BaseQcDocument extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_qc_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile_process_production_id', 'norm_standart_id', 'sort_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['reg_date'], 'safe'],
            [['nastel_no'], 'string', 'max' => 255],
            [['mobile_process_production_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileProcessProduction::class, 'targetAttribute' => ['mobile_process_production_id' => 'id']],
            [['norm_standart_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseNormStandart::class, 'targetAttribute' => ['norm_standart_id' => 'id']],
            [['sort_id'], 'exist', 'skipOnError' => true, 'targetClass' => SortName::class, 'targetAttribute' => ['sort_id' => 'id']],
            [['sort_id','reg_date','nastel_no'],'required'],
            [['nastel_no'],'unique'],
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
            'mobile_process_production_id' => Yii::t('app', 'Mobile Process Production ID'),
            'norm_standart_id' => Yii::t('app', 'Norm Standart ID'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'sort_id' => Yii::t('app', 'Sort Name ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function afterFind()
    {
        if (!empty($this->reg_date)) {
            $this->reg_date = date('d.m.Y H:i:s', strtotime($this->reg_date));
        }

    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->reg_date)) {
            $this->reg_date = date('Y-m-d H:i:s', strtotime($this->reg_date));
        }

        return true;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseQcAttachments()
    {
        return $this->hasMany(BaseQcAttachment::class, ['qc_document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMobileProcessProduction()
    {
        return $this->hasOne(MobileProcessProduction::class, ['id' => 'mobile_process_production_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNormStandart()
    {
        return $this->hasOne(BaseNormStandart::class, ['id' => 'norm_standart_id']);
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
    public function getBaseQcDocumentItems()
    {
        return $this->hasMany(BaseQcDocumentItems::class, ['qc_document_id' => 'id']);
    }
}
