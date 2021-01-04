<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_kalite_defects".
 *
 * @property int $id
 * @property int $toquv_kalite_id
 * @property int $toquv_rm_defects_id
 * @property string $quantity
 * @property double $metr
 * @property double $from
 * @property double $to
 *
 * @property ToquvKalite $toquvKalite
 * @property ToquvRmDefects $toquvRmDefects
 */
class ToquvKaliteDefects extends \yii\db\ActiveRecord
{
    public $count;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_kalite_defects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_kalite_id', 'toquv_rm_defects_id'], 'integer'],
            [['quantity', 'metr', 'from', 'to'], 'number'],
            [['toquv_kalite_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvKalite::className(), 'targetAttribute' => ['toquv_kalite_id' => 'id']],
            [['toquv_rm_defects_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRmDefects::className(), 'targetAttribute' => ['toquv_rm_defects_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_kalite_id' => Yii::t('app', 'Toquv Kalite ID'),
            'toquv_rm_defects_id' => Yii::t('app', 'Toquv Rm Defects ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'metr' => Yii::t('app', 'O\'lcham'),
            'from' => Yii::t('app', 'Qayerdan'),
            'to' => Yii::t('app', 'Qayergacha'),
        ];
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
    public function getToquvRmDefects()
    {
        return $this->hasOne(ToquvRmDefects::className(), ['id' => 'toquv_rm_defects_id']);
    }
}
