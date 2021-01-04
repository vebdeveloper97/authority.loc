<?php

namespace app\modules\base\models;

use app\modules\hr\models\HrEmployee;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%base_method_seam}}".
 *
 * @property int $id
 * @property string $name
 *
 * @property BaseMethodSizeItemsChilds[] $baseMethodSizeItemsChilds
 */
class BaseMethodSeam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_method_seam}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100],
            [['model_type_id'], 'integer'],
            [['model_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelTypes::className(), 'targetAttribute' => ['model_type_id' => 'id']],
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
            'model_type_id' => Yii::t('app', 'Model Type ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseMethodSizeItemsChilds()
    {
        return $this->hasMany(BaseMethodSizeItemsChilds::className(), ['base_method_seam_id' => 'id']);
    }

    /**
     * @return $data
     * */
    public static function getSeamList()
    {
        $model = self::find()->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

    public function getModelType(){
        return $this->hasOne(ModelTypes::class, ['id' => 'model_type_id']);
    }
}