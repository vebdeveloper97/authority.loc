<?php

namespace app\modules\base\models;

use app\modules\toquv\models\ToquvRawMaterials;
use Yii;

/**
 * This is the model class for table "{{%model_orders_items_mato}}".
 *
 * @property int $id
 * @property int $model_orders_items_id
 * @property int $toquv_raw_materials_id
 * @property int $models_list_id
 * @property int $model_var_id
 * @property int $status
 *
 * @property ModelOrdersItems $modelOrdersItems
 * @property ModelsList $modelsList
 * @property ToquvRawMaterials $toquvRawMaterials
 */
class ModelOrdersItemsMato extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_items_mato}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_items_id', 'toquv_raw_materials_id', 'models_list_id', 'model_var_id', 'status'], 'integer'],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
            [['toquv_raw_materials_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['toquv_raw_materials_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_orders_items_id' => Yii::t('app', 'Model Orders Items ID'),
            'toquv_raw_materials_id' => Yii::t('app', 'Toquv Raw Materials ID'),
            'models_list_id' => Yii::t('app', 'Models List ID'),
            'model_var_id' => Yii::t('app', 'Model Var ID'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'model_orders_items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'models_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvRawMaterials()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'toquv_raw_materials_id']);
    }
}
