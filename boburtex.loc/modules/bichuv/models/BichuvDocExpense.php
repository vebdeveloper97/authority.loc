<?php

namespace app\modules\bichuv\models;

use app\modules\toquv\models\PulBirligi;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_doc_expense".
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
 * @property BichuvDoc $document
 * @property PulBirligi $pb
 */
class BichuvDocExpense extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_doc_expense';
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
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['pb_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_id' => 'Document ID',
            'price' => Yii::t('app','Price'),
            'pb_id' => Yii::t('app','Pb ID'),
            'add_info' => Yii::t('app','Add Info'),
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(BichuvDoc::className(), ['id' => 'document_id']);
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
