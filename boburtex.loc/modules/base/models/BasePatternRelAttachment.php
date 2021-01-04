<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "base_pattern_rel_attachment".
 *
 * @property int $id
 * @property int $base_pattern_id
 * @property int $attachment_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $type
 *
 * @property Attachments $attachment
 * @property BasePatterns $basePattern
 * @property string $path [varchar(255)]
 */
class BasePatternRelAttachment extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_pattern_rel_attachment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['path','string'],
            [['base_pattern_id', 'attachment_id', 'status', 'created_by', 'created_at', 'updated_at', 'type'], 'integer'],
            [['attachment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attachments::className(), 'targetAttribute' => ['attachment_id' => 'id']],
            [['base_pattern_id'], 'exist', 'skipOnError' => true, 'targetClass' => BasePatterns::className(), 'targetAttribute' => ['base_pattern_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'path' => Yii::t('app','Path'),
            'base_pattern_id' => Yii::t('app', 'Base Pattern ID'),
            'attachment_id' => Yii::t('app', 'Attachment ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'type' => Yii::t('app', 'Type'),
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
    public function getBasePattern()
    {
        return $this->hasOne(BasePatterns::className(), ['id' => 'base_pattern_id']);
    }
    public function deleteOne(){
        $attachment = $this->attachment;
        if (file_exists($attachment->path)){
            unlink($attachment->path);
        }
        if($attachment){
            $attachment->delete();
        }
        return $this->delete();
    }
}
