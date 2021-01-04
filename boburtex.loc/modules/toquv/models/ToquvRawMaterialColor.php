<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_raw_material_color".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvRawMaterials[] $toquvRawMaterials
 */
class ToquvRawMaterialColor extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_raw_material_color';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique']
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
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvRawMaterials()
    {
        return $this->hasMany(ToquvRawMaterials::className(), ['color_id' => 'id']);
    }
}
