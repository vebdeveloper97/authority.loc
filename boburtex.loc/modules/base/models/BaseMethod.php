<?php

namespace app\modules\base\models;

use Yii;
use app\modules\hr\models\HrEmployee;


/**
 * This is the model class for table "{{%base_method}}".
 *
 * @property int $id
 * @property int $model_list_id
 * @property int $doc_number
 * @property string $date
 * @property int $planning_hr_id
 * @property int $model_hr_id
 * @property int $etyud_id
 * @property int $master_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrEmployee $etyud
 * @property HrEmployee $master
 * @property HrEmployee $modelHr
 * @property ModelsList $modelList
 * @property HrEmployee $planningHr
 * @property BaseMethodSizeItems[] $baseMethodSizeItems
 */
class BaseMethod extends BaseModel
{
    public $size;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_method}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_list_id'], 'required'],
            [['model_list_id', 'planning_hr_id', 'model_hr_id', 'etyud_id', 'master_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['doc_number'], 'string', 'max' => 255],
            [['etyud_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['etyud_id' => 'id']],
            [['master_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['master_id' => 'id']],
            [['model_hr_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['model_hr_id' => 'id']],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
            [['planning_hr_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['planning_hr_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_list_id' => Yii::t('app', 'Model List ID'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'date' => Yii::t('app', 'Date'),
            'planning_hr_id' => Yii::t('app', 'Planning Hr ID'),
            'model_hr_id' => Yii::t('app', 'Model Hr ID'),
            'etyud_id' => Yii::t('app', 'Etyud ID'),
            'master_id' => Yii::t('app', 'Master ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->date = date('Y-m-d');
        $this->status = BaseModel::STATUS_ACTIVE;
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtyud()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'etyud_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaster()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'master_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelHr()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'model_hr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanningHr()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'planning_hr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseMethodSizeItems()
    {
        return $this->hasMany(BaseMethodSizeItems::className(), ['base_method_id' => 'id']);
    }

}
