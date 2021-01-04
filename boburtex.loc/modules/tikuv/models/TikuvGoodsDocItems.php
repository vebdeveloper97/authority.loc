<?php

namespace app\modules\tikuv\models;

use Yii;
/**
 * This is the model class for table "tikuv_goods_doc_items".
 *
 * @property int $id
 * @property int $parent
 * @property int $child
 * @property string $quantity
 * @property int $type
 */
class TikuvGoodsDocItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_goods_doc_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent', 'child', 'type', 'created_at', 'updated_at', 'created_by'], 'integer'],
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
