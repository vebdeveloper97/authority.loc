<?php

namespace app\modules\base\models;

use Yii;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;

/**
 * This is the model class for table "{{%models_acs_variations}}".
 *
 * @property int $id
 * @property int $models_list_id
 * @property int $model_var_id
 * @property int $bichuv_acs_id
 *
 * @property BichuvAcs $bichuvAcs
 * @property ModelsList $modelsList
 * @property ModelsVariations $modelVar
 */
class ModelsAcsVariations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%models_acs_variations}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_list_id', 'model_var_id', 'bichuv_acs_id'], 'integer'],
            [['bichuv_acs_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvAcs::className(), 'targetAttribute' => ['bichuv_acs_id' => 'id']],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
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
            'model_var_id' => Yii::t('app', 'Model Var ID'),
            'bichuv_acs_id' => Yii::t('app', 'Bichuv Acs ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvAcs()
    {
        return $this->hasOne(BichuvAcs::className(), ['id' => 'bichuv_acs_id']);
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
    public function getModelVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_var_id']);
    }
}
