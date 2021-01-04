<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "{{%roll_move_info}}".
 *
 * @property int $id
 * @property int $toquv_documents_id
 * @property int $roll_info_id
 * @property int $entity_type
 * @property double $quantity
 * @property int $unit_id
 * @property string $code
 * @property int $from_department
 * @property int $to_department
 * @property string $reg_date
 *
 * @property RollInfo $rollInfo
 * @property ToquvDocuments $toquvDocuments
 */
class RollMoveInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%roll_move_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_documents_id', 'roll_info_id', 'entity_type', 'unit_id', 'from_department', 'to_department'], 'integer'],
            [['quantity'], 'number'],
            [['reg_date'], 'safe'],
            [['code'], 'string', 'max' => 30],
            [['roll_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => RollInfo::className(), 'targetAttribute' => ['roll_info_id' => 'id']],
            [['toquv_documents_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDocuments::className(), 'targetAttribute' => ['toquv_documents_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_documents_id' => Yii::t('app', 'Toquv Documents ID'),
            'roll_info_id' => Yii::t('app', 'Roll Info ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'code' => Yii::t('app', 'Code'),
            'from_department' => Yii::t('app', 'From Department'),
            'to_department' => Yii::t('app', 'To Department'),
            'reg_date' => Yii::t('app', 'Reg Date'),
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->reg_date = date('Y-m-d H:i:s');
            return true;
        } else {
            return false;
        }
    }
    public function afterValidate()
    {
        if($this->hasErrors()){
            $res = [
                'status' => 'error',
                'url' => \yii\helpers\Url::current([], true),
                'table' => self::tableName() ?? '',
                'message' => $this->getErrors(),
            ];
            Yii::info($res, 'save');
        }
    }
    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = date('d.m.Y H:i', strtotime($this->reg_date));

    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRollInfo()
    {
        return $this->hasOne(RollInfo::className(), ['id' => 'roll_info_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocuments()
    {
        return $this->hasOne(ToquvDocuments::className(), ['id' => 'toquv_documents_id']);
    }
}
