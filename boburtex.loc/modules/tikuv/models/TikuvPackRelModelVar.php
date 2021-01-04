<?php

namespace app\modules\tikuv\models;

use app\modules\base\models\ModelsVariations;
use Yii;

/**
 * This is the model class for table "tikuv_pack_rel_model_var".
 *
 * @property int $models_variations_id
 * @property int $tikuv_outcome_products_pack_id
 *
 * @property ModelsVariations $modelsVariations
 * @property TikuvOutcomeProductsPack $tikuvOutcomeProductsPack
 */
class TikuvPackRelModelVar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_pack_rel_model_var';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_variations_id', 'tikuv_outcome_products_pack_id'], 'required'],
            [['models_variations_id', 'tikuv_outcome_products_pack_id'], 'integer'],
            [['models_variations_id', 'tikuv_outcome_products_pack_id'], 'unique', 'targetAttribute' => ['models_variations_id', 'tikuv_outcome_products_pack_id']],
            [['models_variations_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['models_variations_id' => 'id']],
            [['tikuv_outcome_products_pack_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvOutcomeProductsPack::className(), 'targetAttribute' => ['tikuv_outcome_products_pack_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'models_variations_id' => Yii::t('app', 'Models Variations ID'),
            'tikuv_outcome_products_pack_id' => Yii::t('app', 'Tikuv Outcome Products Pack ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsVariations()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'models_variations_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvOutcomeProductsPack()
    {
        return $this->hasOne(TikuvOutcomeProductsPack::className(), ['id' => 'tikuv_outcome_products_pack_id']);
    }
}
