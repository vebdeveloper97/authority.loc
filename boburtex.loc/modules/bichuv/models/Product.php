<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string $add_info
 *
 * @property BichuvRmItemBalance[] $bichuvRmItemBalances
 * @property BichuvDocItems[] $bichuvDocItems
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['add_info'], 'string'],
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
            'add_info' => Yii::t('app', 'Add Info'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvRmItemBalances()
    {
        return $this->hasMany(BichuvRmItemBalance::className(), ['model_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDocItems()
    {
        return $this->hasMany(BichuvDocItems::className(), ['model_id' => 'id']);
    }
}
