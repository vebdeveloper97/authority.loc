<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "goods_items".
 *
 * @property int $id
 * @property int $parent
 * @property int $child
 * @property string $quantity
 * @property int $type
 */
class GoodsItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent', 'child', 'type'], 'integer'],
            [['quantity'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent' => Yii::t('app', 'Parent'),
            'child' => Yii::t('app', 'Child'),
            'quantity' => Yii::t('app', 'Quantity'),
            'type' => Yii::t('app', 'Type'),
        ];
    }
}
