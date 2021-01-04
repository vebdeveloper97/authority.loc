<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "bichuv_nastel_rag".
 *
 * @property int $id
 * @property string $nastel_party
 * @property int $bichuv_doc_id
 * @property string $quantity
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvDoc $bichuvDoc
 */
class BichuvNastelRag extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_nastel_rag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_doc_id', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['quantity'], 'number'],
            [['nastel_party'], 'string', 'max' => 50],
            [['bichuv_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::className(), 'targetAttribute' => ['bichuv_doc_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nastel_party' => Yii::t('app', 'Nastel Party'),
            'bichuv_doc_id' => Yii::t('app', 'Bichuv Doc ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDoc()
    {
        return $this->hasOne(BichuvDoc::className(), ['id' => 'bichuv_doc_id']);
    }
}
