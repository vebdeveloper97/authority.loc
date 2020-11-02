<?php

namespace app\modules\admin\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "message_attachments".
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
        $lang = Yii::$app->language;
        return "message_attachments_{$lang}";
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attachments_id', 'message_id'], 'integer'],
            [['attachments_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attachments::class, 'targetAttribute' => ['attachments_id' => 'id']],
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

    public static function getImages($images)
    {
        $result = [];
        $count = 0;
        foreach ($images as $key => $image) {
            $saved = $image->attachments?$image->attachments:false;
            if($saved){
                $result[$count] = $saved->path;
                $count++;
            }
        }
        if(!empty($result))
            return $result;
        return false;
    }
}
