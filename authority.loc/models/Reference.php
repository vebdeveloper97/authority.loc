<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reference".
 *
 * @property int $id
 * @property string $fullname
 * @property string $address
 * @property string $phone
 * @property string $reference_message
 * @property int|null $status
 */
class Reference extends \yii\db\ActiveRecord
{
    public $verifyCode;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reference';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullname', 'address', 'phone', 'reference_message'], 'required'],
            [['reference_message'], 'string'],
            [['status'], 'integer'],
            [['fullname', 'address'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 20],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
            [['date'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fullname' => Yii::t('app', 'Fullname'),
            'address' => Yii::t('app', 'Address'),
            'phone' => Yii::t('app', 'Phone'),
            'reference_message' => Yii::t('app', 'Reference Message'),
            'status' => Yii::t('app', 'Status'),
            'date' => Yii::t('app', 'Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * beforesave Saqlashdan oldin ishlashi uchun
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if(empty($this->status)){
                $this->status = 1;
            }
            if(empty($this->date)){
                $this->date = date('Y-m-d');
            }
            return true;
        }
        else{
            return false;
        }
    }
}
