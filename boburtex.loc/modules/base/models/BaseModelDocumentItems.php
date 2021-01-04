<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%base_model_document_items}}".
 *
 * @property int $id
 * @property int $doc_id
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseModelDocument $doc
 * @property BaseModelSizes[] $baseModelSizes
 * @property BaseModelTableFile[] $baseModelTableFiles
 * @property BaseModelTikuvFiles[] $baseModelTikuvFiles
 * @property BaseModelTikuvNote[] $baseModelTikuvNotes
 */
class BaseModelDocumentItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_model_document_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['add_info'], 'string'],
            [['doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseModelDocument::className(), 'targetAttribute' => ['doc_id' => 'id']],
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
    public function getDoc()
    {
        return $this->hasOne(BaseModelDocument::className(), ['id' => 'doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseModelSizes()
    {
        return $this->hasMany(BaseModelSizes::className(), ['doc_items_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseModelTableFiles()
    {
        return $this->hasMany(BaseModelTableFile::className(), ['doc_items_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseModelTikuvFiles()
    {
        return $this->hasMany(BaseModelTikuvFiles::className(), ['doc_items_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseModelTikuvNotes()
    {
        return $this->hasMany(BaseModelTikuvNote::className(), ['doc_items_id' => 'id']);
    }
}
