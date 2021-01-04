<?php

namespace app\modules\bichuv\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_roll_records".
 *
 * @property int $id
 * @property int $bichuv_sub_doc_id
 * @property string $quantity
 * @property int $type
 * @property string $reg_date
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $doc_item_id
 *
 * @property BichuvSubDocItems $bichuvSubDoc
 * @property BichuvDocItems $docItem
 * @property string $first_qty [decimal(10,3)]
 */
class BichuvRollRecords extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_roll_records';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_sub_doc_id', 'type', 'created_by', 'status', 'created_at', 'updated_at', 'doc_item_id'], 'integer'],
            [['quantity','first_qty'], 'number'],
            [['reg_date'], 'safe'],
            [['bichuv_sub_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvSubDocItems::className(), 'targetAttribute' => ['bichuv_sub_doc_id' => 'id']],
            [['doc_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDocItems::className(), 'targetAttribute' => ['doc_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bichuv_sub_doc_id' => Yii::t('app', 'Bichuv Sub Doc ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'type' => Yii::t('app', 'Type'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'doc_item_id' => Yii::t('app', 'Doc Item ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvSubDoc()
    {
        return $this->hasOne(BichuvSubDocItems::className(), ['id' => 'bichuv_sub_doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocItem()
    {
        return $this->hasOne(BichuvDocItems::className(), ['id' => 'doc_item_id']);
    }
}
