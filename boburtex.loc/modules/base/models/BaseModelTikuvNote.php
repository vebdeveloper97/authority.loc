<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%base_model_tikuv_note}}".
 *
 * @property int $id
 * @property int $doc_id
 * @property int $doc_items_id
 * @property string $note
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseModelDocument $doc
 * @property BaseModelDocumentItems $docItems
 */
class BaseModelTikuvNote extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_model_tikuv_note}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_id', 'doc_items_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['note'], 'string'],
            [['note'], 'required'],
            [['doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseModelDocument::className(), 'targetAttribute' => ['doc_id' => 'id']],
            [['doc_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseModelDocumentItems::className(), 'targetAttribute' => ['doc_items_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'doc_items_id' => Yii::t('app', 'Doc Items ID'),
            'note' => Yii::t('app', 'Note'),
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
    public function getDoc()
    {
        return $this->hasOne(BaseModelDocument::className(), ['id' => 'doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocItems()
    {
        return $this->hasOne(BaseModelDocumentItems::className(), ['id' => 'doc_items_id']);
    }
}
