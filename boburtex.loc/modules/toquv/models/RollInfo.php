<?php

namespace app\modules\toquv\models;

use app\modules\base\models\ModelOrdersItems;
use Yii;

/**
 * This is the model class for table "roll_info".
 *
 * @property int $id
 * @property string $code
 * @property int $entity_id
 * @property int $entity_type
 * @property double $quantity
 * @property int $unit_id
 * @property int $tir_id
 * @property int $moi_id
 * @property int $toquv_kalite_id
 * @property int $toquv_departments_id
 * @property int $old_departments_id
 * @property int $sort_name_id
 * @property string $accept_date
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ModelOrdersItems $moi
 * @property SortName $sortName
 * @property ToquvInstructionRm $tir
 * @property ToquvDepartments $toquvDepartments
 * @property ToquvKalite $toquvKalite
 * @property RollMoveInfo[] $rollMoveInfos
 */
class RollInfo extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roll_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_type', 'unit_id', 'tir_id', 'moi_id', 'toquv_kalite_id', 'toquv_departments_id', 'old_departments_id', 'sort_name_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['quantity'], 'number'],
            [['accept_date'], 'safe'],
            [['code'], 'string', 'max' => 30],
            [['moi_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['moi_id' => 'id']],
            [['sort_name_id'], 'exist', 'skipOnError' => true, 'targetClass' => SortName::className(), 'targetAttribute' => ['sort_name_id' => 'id']],
            [['tir_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvInstructionRm::className(), 'targetAttribute' => ['tir_id' => 'id']],
            [['toquv_departments_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['toquv_departments_id' => 'id']],
            [['toquv_kalite_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvKalite::className(), 'targetAttribute' => ['toquv_kalite_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'tir_id' => Yii::t('app', 'Tir ID'),
            'moi_id' => Yii::t('app', 'Moi ID'),
            'toquv_kalite_id' => Yii::t('app', 'Toquv Kalite ID'),
            'toquv_departments_id' => Yii::t('app', 'Toquv Departments ID'),
            'old_departments_id' => Yii::t('app', 'Old Departments ID'),
            'sort_name_id' => Yii::t('app', 'Sort Name ID'),
            'accept_date' => Yii::t('app', 'Accept Date'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->accept_date = date('Y-m-d H:i:s');
            return true;
        } else {
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->accept_date = date('d.m.Y H:i', strtotime($this->accept_date));

    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMoi()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'moi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSortName()
    {
        return $this->hasOne(SortName::className(), ['id' => 'sort_name_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTir()
    {
        return $this->hasOne(ToquvInstructionRm::className(), ['id' => 'tir_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDepartments()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'toquv_departments_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvKalite()
    {
        return $this->hasOne(ToquvKalite::className(), ['id' => 'toquv_kalite_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRollMoveInfos()
    {
        return $this->hasMany(RollMoveInfo::className(), ['roll_info_id' => 'id']);
    }
}
