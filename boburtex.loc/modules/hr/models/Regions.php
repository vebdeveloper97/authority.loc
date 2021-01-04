<?php

namespace app\modules\hr\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "regions".
 *
 * @property int $id
 * @property string $name
 */
class Regions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'regions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * @return array
     */
    public static function getListMap()
    {
        $list = static::find()->asArray()->all();
        return ArrayHelper::map($list,'id', 'name');
    }
    public static function getListItem($key){
        return self::getListMap()[$key];
    }
}
