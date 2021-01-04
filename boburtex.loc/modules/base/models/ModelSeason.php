<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "model_season".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ModelsList[] $modelsLists
 * @property string $code [varchar(255)]
 */
class ModelSeason extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_season';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name','code'], 'string', 'max' => 255],
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
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsLists()
    {
        return $this->hasMany(ModelsList::className(), ['model_season' => 'id']);
    }
}
