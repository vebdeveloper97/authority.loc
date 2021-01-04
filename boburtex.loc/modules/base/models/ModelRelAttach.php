<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "model_rel_attach".
 *
 * @property int $id
 * @property int $attachment_id
 * @property int $model_list_id
 * @property int $type
 * @property int $is_main
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Attachments $attachment
 * @property ModelsList $modelList
 */
class ModelRelAttach extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_rel_attach';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attachment_id', 'model_list_id', 'type', 'is_main', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['attachment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attachments::className(), 'targetAttribute' => ['attachment_id' => 'id']],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attachment_id' => Yii::t('app', 'Attachment ID'),
            'model_list_id' => Yii::t('app', 'Model List ID'),
            'type' => Yii::t('app', 'Type'),
            'is_main' => Yii::t('app', 'Is Main'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachment()
    {
        return $this->hasOne(Attachments::className(), ['id' => 'attachment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_list_id']);
    }
    public function deleteOne(){
        if (file_exists($this->attachment->path)){
            unlink($this->attachment->path);
        }
        $this->delete();
        $this->attachment->delete();
    }
}
