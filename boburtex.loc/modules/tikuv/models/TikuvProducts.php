<?php

namespace app\modules\tikuv\models;

use Yii;

/**
 * This is the model class for table "tikuv_products".
 *
 * @property int $id
 * @property int $goods_id
 * @property int $barcode
 * @property int $barcode1
 * @property int $barcode2
 * @property int $is_inside
 * @property int $type
 * @property string $model_no
 * @property int $model_id
 * @property int $size_type
 * @property int $size
 * @property int $color
 * @property string $name
 * @property string $old_name
 * @property int $category
 * @property int $sub_category
 * @property int $model_type
 * @property int $season
 * @property int $status
 * @property string $desc1
 * @property string $desc2
 * @property string $desc3
 * @property string $size_collection
 * @property string $color_collection
 * @property int $boyoqhona_model_id
 * @property int $boyoqhona_color_id
 */
class TikuvProducts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'barcode', 'barcode1', 'barcode2', 'is_inside', 'type', 'model_id', 'size_type', 'size', 'color', 'category', 'sub_category', 'model_type', 'season', 'status', 'boyoqhona_model_id', 'boyoqhona_color_id'], 'integer'],
            [['model_no'], 'string', 'max' => 30],
            [['name', 'old_name'], 'string', 'max' => 100],
            [['desc1', 'desc2', 'desc3', 'size_collection', 'color_collection'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_id' => Yii::t('app', 'Goods ID'),
            'barcode' => Yii::t('app', 'Barcode'),
            'barcode1' => Yii::t('app', 'Barcode1'),
            'barcode2' => Yii::t('app', 'Barcode2'),
            'is_inside' => Yii::t('app', 'Is Inside'),
            'type' => Yii::t('app', 'Type'),
            'model_no' => Yii::t('app', 'Model No'),
            'model_id' => Yii::t('app', 'Model ID'),
            'size_type' => Yii::t('app', 'Size Type'),
            'size' => Yii::t('app', 'Size'),
            'color' => Yii::t('app', 'Color'),
            'name' => Yii::t('app', 'Name'),
            'old_name' => Yii::t('app', 'Old Name'),
            'category' => Yii::t('app', 'Category'),
            'sub_category' => Yii::t('app', 'Sub Category'),
            'model_type' => Yii::t('app', 'Model Type'),
            'season' => Yii::t('app', 'Season'),
            'status' => Yii::t('app', 'Status'),
            'desc1' => Yii::t('app', 'Desc1'),
            'desc2' => Yii::t('app', 'Desc2'),
            'desc3' => Yii::t('app', 'Desc3'),
            'size_collection' => Yii::t('app', 'Size Collection'),
            'color_collection' => Yii::t('app', 'Color Collection'),
            'boyoqhona_model_id' => Yii::t('app', 'Boyoqhona Model ID'),
            'boyoqhona_color_id' => Yii::t('app', 'Boyoqhona Color ID'),
        ];
    }
}
