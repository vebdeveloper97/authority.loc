<?php

namespace app\modules\base\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%model_orders_rel_fs_attachments}}".
 *
 * @property int $id
 * @property int $model_orders_fs_id
 * @property int $attachments_id
 *
 * @property Attachments $attachments
 * @property ModelOrdersFs $modelOrdersFs
 */
class ModelOrdersRelFsAttachments extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_rel_fs_attachments}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_fs_id'], 'integer'],
            [['attachments_id'], 'string', 'max' => 255],
            [['model_orders_fs_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersFs::className(), 'targetAttribute' => ['model_orders_fs_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_orders_fs_id' => Yii::t('app', 'Model Orders Fs ID'),
            'attachments_id' => Yii::t('app', 'Attachments ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasOne(Attachments::className(), ['id' => 'attachments_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersFs()
    {
        return $this->hasOne(ModelOrdersFs::className(), ['id' => 'model_orders_fs_id']);
    }
}
