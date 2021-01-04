<?php

namespace app\modules\toquv\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "raw_material_type".
 *
 * @property int $id
 * @property string $name
 * @property string $type
 *
 * @property ToquvRawMaterials[] $toquvRawMaterials
 */
class ToquvRawMaterialType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'raw_material_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['type'], 'integer'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type'=>'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvRawMaterials()
    {
        return $this->hasMany(ToquvRawMaterials::className(), ['raw_material_type_id' => 'id']);
    }

    public static function getMapList() {
        return ArrayHelper::map(
            static::find()
                ->select(['id', 'name'])
                ->asArray()
                ->all()
            ,
            'id',
            'name'
        );
    }
}
