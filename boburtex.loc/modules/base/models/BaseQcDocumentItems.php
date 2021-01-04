<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "base_qc_document_items".
 *
 * @property int $id
 * @property int $qc_document_id
 * @property int $error_list_id
 * @property int $quantity
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseErrorList $errorList
 * @property BaseQcDocument $qcDocument
 */
class BaseQcDocumentItems extends BaseModel
{
    /** scenario list **/
    const SCENARIO_CREATE = "scenario-create";
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_qc_document_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qc_document_id', 'error_list_id', 'quantity', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['add_info'], 'string'],
            [['error_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseErrorList::className(), 'targetAttribute' => ['error_list_id' => 'id']],
            [['qc_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseQcDocument::className(), 'targetAttribute' => ['qc_document_id' => 'id']],
            [['error_list_id','quantity'],'required','on' => self::SCENARIO_CREATE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'qc_document_id' => Yii::t('app', 'Qc Document ID'),
            'error_list_id' => Yii::t('app', 'Error List ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'add_info' => Yii::t('app', 'Add Info'),
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
        return $this->hasOne(BaseErrorList::className(), ['id' => 'error_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQcDocument()
    {
        return $this->hasOne(BaseQcDocument::className(), ['id' => 'qc_document_id']);
    }
}
