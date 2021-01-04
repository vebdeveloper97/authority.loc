<?php

namespace app\modules\toquv\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_document_expense".
 *
 * @property int $id
 * @property int $document_id
 * @property string $price
 * @property int $pb_id
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvDocuments $document
 * @property PulBirligi $pb
 */
class ToquvDocumentExpense extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_document_expense';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_id', 'pb_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['add_info'], 'string'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDocuments::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['pb_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'document_id' => Yii::t('app', 'Document ID'),
            'price' => Yii::t('app', 'Price'),
            'pb_id' => Yii::t('app', 'Pb ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(ToquvDocuments::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'pb_id']);
    }

    /**
     * @return array
     */
    public function getPulBirligi(){
        $pb = PulBirligi::find()->asArray()->all();
        if(!empty($pb)){
            return ArrayHelper::map($pb,'id','name');
        }
        return [];
    }
}
