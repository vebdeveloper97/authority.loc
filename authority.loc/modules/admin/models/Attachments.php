<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "attachments".
 *
 * @property int $id
 * @property string $name
 * @property string|null $extension
 * @property int|null $size
 * @property string|null $path
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property MessageAttachments[] $messageAttachments
 */
class Attachments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['size', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'path'], 'string', 'max' => 100],
            [['extension'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'extension' => Yii::t('app', 'Extension'),
            'size' => Yii::t('app', 'Size'),
            'path' => Yii::t('app', 'Path'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[MessageAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessageAttachments()
    {
        return $this->hasMany(MessageAttachments::className(), ['attachments_id' => 'id']);
    }
}
