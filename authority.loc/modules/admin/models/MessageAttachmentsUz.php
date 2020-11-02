<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "message_attachments_uz".
 *
 * @property int $id
 * @property int|null $attachments_id
 * @property int|null $message_id
 *
 * @property Attachments $attachments
 */
class MessageAttachmentsUz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_attachments_uz';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attachments_id', 'message_id'], 'integer'],
            [['attachments_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attachments::className(), 'targetAttribute' => ['attachments_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attachments_id' => Yii::t('app', 'Attachments ID'),
            'message_id' => Yii::t('app', 'Message ID'),
        ];
    }

    /**
     * Gets query for [[Attachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasOne(Attachments::className(), ['id' => 'attachments_id']);
    }
}
