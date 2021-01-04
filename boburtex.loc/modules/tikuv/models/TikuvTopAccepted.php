<?php

namespace app\modules\tikuv\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "top_accepted".
 *
 * @property int $id
 * @property int $top_id
 * @property string $accepted
 * @property int $type
 * @property string $reg_date
 * @property int $status
 * @property string $doc_number
 *
 * @property TikuvOutcomeProducts $top
 */
class TikuvTopAccepted extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_top_accepted';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['top_id', 'type', 'status'], 'integer'],
            [['accepted'], 'number'],
            [['reg_date'], 'safe'],
            [['doc_number'], 'string', 'max' => 20],
            [['top_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvOutcomeProducts::className(), 'targetAttribute' => ['top_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'top_id' => Yii::t('app', 'Top ID'),
            'accepted' => Yii::t('app', 'Accepted'),
            'type' => Yii::t('app', 'Type'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'status' => Yii::t('app', 'Status'),
            'doc_number' => Yii::t('app', 'Doc Number'),
        ];
    }
    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = date('d.m.Y H:i', strtotime($this->reg_date));

    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTop()
    {
        return $this->hasOne(TikuvOutcomeProducts::className(), ['id' => 'top_id']);
    }
}
