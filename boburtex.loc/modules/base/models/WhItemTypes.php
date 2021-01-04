<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wh_item_types".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @property WhItemCategory[] $whItemCategories
 * @property WhItems[] $whItems
 */
class WhItemTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wh_item_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code','name'],'required'],
            ['code','unique'],
            [['code'], 'string', 'max' => 50],
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
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhItemCategories()
    {
        return $this->hasMany(WhItemCategory::className(), ['type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhItems()
    {
        return $this->hasMany(WhItems::className(), ['type_id' => 'id']);
    }

    public static function getList($key=null)
    {
        $list = self::find()->asArray()->all();
        return ArrayHelper::map($list,'id','name');
    }
}
