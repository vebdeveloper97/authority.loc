<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "evaluation_status".
 *
 * @property int $id
 * @property string $ip_address
 * @property int $evaluation_id
 * @property int|null $status
 */
class EvaluationStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evaluation_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip_address', 'evaluation_id'], 'required'],
            [['evaluation_id', 'status'], 'integer'],
            [['ip_address'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'evaluation_id' => Yii::t('app', 'Evaluation ID'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
