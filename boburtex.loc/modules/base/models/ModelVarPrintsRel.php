<?php

namespace app\modules\base\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%model_var_prints_rel}}".
 *
 * @property int $id
 * @property int $models_variations_id
 * @property int $model_var_prints_id
 * @property string $add_info
 *
 * @property ModelVarPrints $modelVarPrints
 * @property ModelsVariations $modelsVariations
 */
class ModelVarPrintsRel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_var_prints_rel}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_variations_id', 'model_var_prints_id'], 'integer'],
            [['add_info'], 'string'],
            [['model_var_prints_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelVarPrints::className(), 'targetAttribute' => ['model_var_prints_id' => 'id']],
            [['models_variations_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['models_variations_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'models_variations_id' => Yii::t('app', 'Models Variations ID'),
            'model_var_prints_id' => Yii::t('app', 'Model Var Prints ID'),
            'add_info' => Yii::t('app', 'Add Info'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPrints()
    {
        return $this->hasOne(ModelVarPrints::className(), ['id' => 'model_var_prints_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsVariations()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'models_variations_id']);
    }
}
