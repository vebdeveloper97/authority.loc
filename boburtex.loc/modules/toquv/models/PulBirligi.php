<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "pul_birligi".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvDocumentExpense[] $toquvDocumentExpenses
 * @property ToquvSaldo[] $toquvSaldos
 */
class PulBirligi extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pul_birligi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocumentExpenses()
    {
        return $this->hasMany(ToquvDocumentExpense::className(), ['pb_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvSaldos()
    {
        return $this->hasMany(ToquvSaldo::className(), ['pb_id' => 'id']);
    }
}
