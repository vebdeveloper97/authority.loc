<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%model_mini_postal_files}}".
 *
 * @property int $id
 * @property int $model_mini_postal_id
 * @property string $name
 * @property int $size
 * @property string $extension
 * @property string $type
 * @property string $path
 * @property int $isMain
 *
 * @property ModelMiniPostal $modelMiniPostal
 */
class ModelMiniPostalFiles extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_mini_postal_files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_mini_postal_id', 'size', 'isMain'], 'integer'],
            [['name', 'path'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 10],
            [['type'], 'string', 'max' => 120],
            ['file','file'],
            [['model_mini_postal_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelMiniPostal::className(), 'targetAttribute' => ['model_mini_postal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_mini_postal_id' => Yii::t('app', 'Model Mini Postal ID'),
            'name' => Yii::t('app', 'Name'),
            'size' => Yii::t('app', 'Size'),
            'extension' => Yii::t('app', 'Extension'),
            'type' => Yii::t('app', 'Type'),
            'path' => Yii::t('app', 'Path'),
            'isMain' => Yii::t('app', 'Is Main'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelMiniPostal()
    {
        return $this->hasOne(ModelMiniPostal::className(), ['id' => 'model_mini_postal_id']);
    }
}
