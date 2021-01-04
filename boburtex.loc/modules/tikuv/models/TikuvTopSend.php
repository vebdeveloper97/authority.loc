<?php

namespace app\modules\tikuv\models;

use Yii;

/**
 * This is the model class for table "tikuv_top_send".
 *
 * @property int $id
 * @property int $top_id
 * @property string $doc_number
 * @property string $add_info
 * @property string $sent
 * @property int $type
 * @property string $reg_date
 * @property int $status
 *
 * @property TikuvOutcomeProducts $top
 */
class TikuvTopSend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_top_send';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['top_id', 'type', 'status'], 'integer'],
            [['add_info'], 'string'],
            [['sent'], 'number'],
            [['reg_date'], 'safe'],
            [['doc_number'], 'string', 'max' => 255],
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
            'doc_number' => Yii::t('app', 'Doc Number'),
            'add_info' => Yii::t('app', 'Add Info'),
            'sent' => Yii::t('app', 'Sent'),
            'type' => Yii::t('app', 'Type'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTop()
    {
        return $this->hasOne(TikuvOutcomeProducts::className(), ['id' => 'top_id']);
    }
}
