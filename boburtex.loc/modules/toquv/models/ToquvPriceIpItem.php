<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_price_ip_item".
 *
 * @property int $id
 * @property int $toquv_price_ip_id
 * @property int $toquv_ne_id
 * @property int $toquv_thread_id
 * @property string $price
 * @property int $pb_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PulBirligi $pb
 * @property ToquvThread $toquvThread
 * @property ToquvNe $toquvNe
 * @property ToquvPriceIp $toquvPriceIp
 */
class ToquvPriceIpItem extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_price_ip_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_price_ip_id', 'toquv_ne_id', 'toquv_thread_id', 'pb_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['pb_id' => 'id']],
            [['toquv_thread_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvThread::className(), 'targetAttribute' => ['toquv_thread_id' => 'id']],
            [['toquv_ne_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvNe::className(), 'targetAttribute' => ['toquv_ne_id' => 'id']],
            [['toquv_price_ip_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvPriceIp::className(), 'targetAttribute' => ['toquv_price_ip_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_price_ip_id' => Yii::t('app', 'Toquv Price Ip ID'),
            'toquv_ne_id' => Yii::t('app', 'Toquv Ne ID'),
            'toquv_thread_id' => Yii::t('app', 'Toquv Thread ID'),
            'price' => Yii::t('app', 'Price'),
            'pb_id' => Yii::t('app', 'Pb ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'pb_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvThread()
    {
        return $this->hasOne(ToquvThread::className(), ['id' => 'toquv_thread_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvNe()
    {
        return $this->hasOne(ToquvNe::className(), ['id' => 'toquv_ne_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvPriceIp()
    {
        return $this->hasOne(ToquvPriceIp::className(), ['id' => 'toquv_price_ip_id']);
    }
}
