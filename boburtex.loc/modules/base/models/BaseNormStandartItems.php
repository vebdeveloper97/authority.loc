<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "base_norm_standart_items".
 *
 * @property int $id
 * @property int $norm_standart_id
 * @property int $error_list_id
 * @property int $quantity
 * @property string $info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseErrorList $errorList
 * @property BaseNormStandart $normStandart
 */
class BaseNormStandartItems extends \yii\db\ActiveRecord
{
    /** scenario list **/
    const SCENARIO_CREATE = "scenario-create";
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_norm_standart_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['norm_standart_id', 'error_list_id', 'quantity', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['info'], 'string'],
            [['error_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseErrorList::class, 'targetAttribute' => ['error_list_id' => 'id']],
            [['norm_standart_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseNormStandart::class, 'targetAttribute' => ['norm_standart_id' => 'id']],
            [['error_list_id','quantity'],'required','on'=> self::SCENARIO_CREATE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'norm_standart_id' => Yii::t('app', 'Norm Standart ID'),
            'error_list_id' => Yii::t('app', 'Error List ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'info' => Yii::t('app', 'Info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getErrorList()
    {
        return $this->hasOne(BaseErrorList::class, ['id' => 'error_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNormStandart()
    {
        return $this->hasOne(BaseNormStandart::class, ['id' => 'norm_standart_id']);
    }
}
