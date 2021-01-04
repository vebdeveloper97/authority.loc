<?php

namespace app\modules\tikuv\models;

use app\components\OurCustomBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%nastel_combine_list}}".
 *
 * @property int $id
 * @property string $parent
 * @property string $child
 * @property double $quantity
 * @property double $remain
 * @property string $add_info
 * @property string $reg_date
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class NastelCombineList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%nastel_combine_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity', 'remain'], 'number'],
            [['add_info'], 'string'],
            [['reg_date'], 'safe'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['parent', 'child'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent' => Yii::t('app', 'Parent'),
            'child' => Yii::t('app', 'Child'),
            'quantity' => Yii::t('app', 'Quantity'),
            'remain' => Yii::t('app', 'Remain'),
            'add_info' => Yii::t('app', 'Add Info'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->reg_date = date('Y-m-d H:i:s');
            return true;
        }else{
            return false;
        }
    }
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
}
