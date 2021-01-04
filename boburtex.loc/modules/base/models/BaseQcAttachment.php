<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "base_qc_attachment".
 *
 * @property int $id
 * @property int $qc_document_id
 * @property string $name
 * @property string $path
 *
 * @property BaseQcDocument $qcDocument
 */
class BaseQcAttachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_qc_attachment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qc_document_id'], 'integer'],
            [['path'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['qc_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseQcDocument::className(), 'targetAttribute' => ['qc_document_id' => 'id']],
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
            'name' => Yii::t('app', 'Name'),
            'path' => Yii::t('app', 'Path'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQcDocument()
    {
        return $this->hasOne(BaseQcDocument::className(), ['id' => 'qc_document_id']);
    }
}
