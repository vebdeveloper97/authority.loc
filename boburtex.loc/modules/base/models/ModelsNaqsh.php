<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "models_naqsh".
 *
 * @property int $id
 * @property int $models_list_id
 * @property int $models_var_id
 * @property string $title
 * @property string $content
 * @property int $attachments_id
 *
 * @property ModelsList $modelsList
 * @property ModelsVariations $modelsVar
 * @property Attachments $attachments
 */
class ModelsNaqsh extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'models_naqsh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_list_id', 'models_var_id', 'width', 'height', 'base_details_list_id', 'attachments_id'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
            [['models_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['models_var_id' => 'id']],
            [['attachments_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attachments::className(), 'targetAttribute' => ['attachments_id' => 'id']],
            [['base_details_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseDetailLists::className(), 'targetAttribute' => ['base_details_list_id' => 'id']],
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
            'models_var_id' => Yii::t('app', 'Models Var ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'width' => Yii::t('app', 'En(sm)'),
            'height' => Yii::t('app', 'Bo\'yi(sm)'),
            'attachments_id' => Yii::t('app', 'Attachments ID'),
        ];
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
    public function getModelsVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'models_var_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasOne(Attachments::className(), ['id' => 'attachments_id']);
    }

}
