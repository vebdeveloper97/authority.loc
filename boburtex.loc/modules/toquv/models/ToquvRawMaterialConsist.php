<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_raw_material_consist".
 *
 * @property int $id
 * @property int $fabric_type_id
 * @property int $raw_material_id
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $percentage
 *
 * @property FabricTypes $fabricType
 * @property ToquvRawMaterials $rawMaterial
 */
class ToquvRawMaterialConsist extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_raw_material_consist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fabric_type_id', 'raw_material_id', 'created_by', 'status', 'created_at', 'updated_at', 'percentage'], 'integer'],
            [['fabric_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FabricTypes::className(), 'targetAttribute' => ['fabric_type_id' => 'id']],
            [['raw_material_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['raw_material_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fabric_type_id' => Yii::t('app', 'Fabric Type ID'),
            'raw_material_id' => Yii::t('app', 'Raw Material ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'percentage' => Yii::t('app', 'Percentage'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFabricType()
    {
        return $this->hasOne(FabricTypes::className(), ['id' => 'fabric_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRawMaterial()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'raw_material_id']);
    }
    public static function deleteRawMaterialConsist($id)
    {
        foreach (self::find()->where(['raw_material_id' => $id])->all() as $child) {
            $child->delete();
        }
    }
    public function getFullName($lang = 'ru'){

        if($this->fabricType->hasAttribute('name_'.$lang)){
            $name = $this->percentage . "% ". $this->fabricType->{'name_'.$lang} .  ", ";
        }else{
            $name = $this->percentage . "% ". $this->fabricType->name_ru .  ", ";
        }
        return $name;
    }
}
