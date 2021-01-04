<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "spare_item_doc_expence".
 *
 * @property int $id
 * @property int $document_id
 * @property string $price
 * @property int $pb_id
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property SpareItemDoc $document
 */
class SpareItemDocExpence extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare_item_doc_expence';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_id', 'pb_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['price'], 'number'],
            [['add_info'], 'string'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItemDoc::className(), 'targetAttribute' => ['document_id' => 'id']],
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
            'price' => 'Price',
            'pb_id' => 'Pb ID',
            'add_info' => 'Add Info',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(SpareItemDoc::className(), ['id' => 'document_id']);
    }
}
