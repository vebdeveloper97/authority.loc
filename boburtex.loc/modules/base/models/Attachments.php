<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "attachments".
 *
 * @property int $id
 * @property string $name
 * @property int $size
 * @property string $extension
 * @property string $path
 * @property string $type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ModelRelAttach[] $modelRelAttaches
 * @property ModelVarBaskiRelAttach[] $modelVarBaskiRelAttaches
 * @property ModelVarPrintRelAttach[] $modelVarPrintRelAttaches
 * @property ModelVarRelAttach[] $modelVarRelAttaches
 * @property ModelVarStoneRelAttach[] $modelVarStoneRelAttaches
 */
class Attachments extends BaseModel
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
            [['size', 'status', 'created_by', 'created_at', 'updated_at', 'type'], 'integer'],
            [['name', 'path'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 10],
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
            'size' => Yii::t('app', 'Size'),
            'extension' => Yii::t('app', 'Extension'),
            'path' => Yii::t('app', 'Path'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelRelAttaches()
    {
        return $this->hasMany(ModelRelAttach::className(), ['attachment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarBaskiRelAttaches()
    {
        return $this->hasMany(ModelVarBaskiRelAttach::className(), ['attachment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPrintRelAttaches()
    {
        return $this->hasMany(ModelVarPrintRelAttach::className(), ['attachment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarRelAttaches()
    {
        return $this->hasMany(ModelVarRelAttach::className(), ['attachment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarStoneRelAttaches()
    {
        return $this->hasMany(ModelVarStoneRelAttach::className(), ['attachment_id' => 'id']);
    }
}
