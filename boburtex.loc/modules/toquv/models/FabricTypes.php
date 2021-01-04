<?php

namespace app\modules\toquv\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fabric_types".
 *
 * @property int $id
 * @property string $name_en
 * @property string $name_ru
 * @property string $name_uz
 */
class FabricTypes extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fabric_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_en', 'name_ru', 'name_uz'], 'required'],
            [['name_en', 'name_ru', 'name_uz'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_en' => Yii::t('app', 'Name En'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'name_uz' => Yii::t('app', 'Name Uz'),
        ];
    }

    public static function getAllTypes()
    {
        $types = self::find()->all();

        return ArrayHelper::map($types,'id','name_'.Yii::$app->language);
    }

    public static function getName($id)
    {
        $model =  self::find()->where(['id' => $id])->one();
        return $model['name_'.Yii::$app->language];
    }
}
