<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currency_rate".
 *
 * @property int $id
 * @property string $rate_name
 * @property float $rate_usd
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class CurrencyRate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency_rate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rate_name', 'rate_usd'], 'required'],
            [['rate_usd'], 'safe'],
            [['date'], 'safe'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['rate_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rate_name' => Yii::t('app', 'Rate Name'),
            'rate_usd' => Yii::t('app', 'Rate Usd'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
