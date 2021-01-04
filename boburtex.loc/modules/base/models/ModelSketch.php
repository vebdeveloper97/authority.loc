<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "model_sketch".
 *
 * @property int $id
 * @property int $models_list_id
 * @property string $name
 * @property int $size
 * @property string $extension
 * @property string $path
 * @property int $is_main
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ModelsList $modelsList
 */
class ModelSketch extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_sketch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_list_id', 'size', 'is_main', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'path'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 10],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'models_list_id' => Yii::t('app', 'Models List ID'),
            'name' => Yii::t('app', 'Name'),
            'size' => Yii::t('app', 'Size'),
            'extension' => Yii::t('app', 'Extension'),
            'path' => Yii::t('app', 'Path'),
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
    public function getModelsList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'models_list_id']);
    }
    public function deleteOne(){
        if (file_exists($this->path)){
            unlink($this->path);
        }
        $this->delete();
    }
}
