<?php

namespace app\modules\base\models;

use Yii;
use app\models\Size;
use app\models\Color;
use app\models\SizeType;
use app\models\ColorPantone;
use app\modules\bichuv\models\Product;
use app\modules\tikuv\models\TikuvGoodsDoc;
use app\modules\tikuv\models\TikuvGoodsDocAccepted;
use app\modules\tikuv\models\TikuvGoodsDocMoving;
use app\modules\tikuv\models\TikuvOutcomeProducts;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property int $barcode
 * @property int $barcode1
 * @property int $barcode2
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
 *
 * @property ColorPantone $colorPantone
 * @property ModelsList $model
 * @property ModelsVariations $modelVar
 * @property Product $productModel
 * @property Color $productColor
 * @property Size $size0
 * @property SizeType $sizeType
 * @property TikuvGoodsDoc[] $tikuvGoodsDocs
 * @property TikuvGoodsDocAccepted[] $tikuvGoodsDocAccepteds
 * @property TikuvGoodsDocMoving[] $tikuvGoodsDocMovings
 * @property TikuvOutcomeProducts[] $tikuvOutcomeProducts
 * @property int $boyoqhona_model_id [smallint(6)]
 * @property int $boyoqhona_color_id [int(11)]
 * @property int $is_inside [smallint(1)]
 * @property int $doc_item_id [int(11)]
 * @property int $model_var [int(11)]
 * @property int $brand1 [int(11)]
 * @property int $brand2 [int(11)]
 * @property int $brand3 [int(11)]
 * @property string $properties [varchar(255)]
 * @property string $color_name [varchar(255)]
 * @property string $package_code [varchar(255)]
 */
class Goods extends \yii\db\ActiveRecord
{
    const TYPE_ITEM = 1;
    const TYPE_PAKET = 2;
    const TYPE_BLOK = 3;
    const TYPE_QOP = 4;

    const TYPE_MODEL_OUTSIDE = 1;
    const TYPE_MODEL_INSIDE = 2;


    public static function getTypeList($key = null)
    {
        $result = [
            self::TYPE_ITEM => Yii::t('app', 'Dona'),
            self::TYPE_PAKET => Yii::t('app', 'Paket'),
            self::TYPE_BLOK => Yii::t('app', 'Blok'),
            self::TYPE_QOP => Yii::t('app', 'Qop')
        ];
        if (!empty($key)) {
            return $result[$key];
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barcode', 'barcode1', 'barcode2', 'brand1', 'brand2', 'brand3', 'is_inside', 'doc_item_id', 'model_var', 'boyoqhona_color_id', 'boyoqhona_model_id', 'type', 'model_id', 'size_type', 'size', 'color', 'category', 'sub_category', 'model_type', 'season', 'status'], 'integer'],
            [['model_no'], 'string', 'max' => 30],
            [['name', 'old_name','package_code'], 'string', 'max' => 100],
            [['desc1', 'desc2', 'desc3','properties','color_name','size_collection', 'color_collection'], 'string', 'max' => 255],
            [['barcode'], 'unique'],
            [['barcode1'], 'unique'],
            [['barcode2'], 'unique'],
            [['color'], 'exist', 'skipOnError' => true, 'targetClass' => ColorPantone::className(), 'targetAttribute' => ['color' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['model_var'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var' => 'id']],
            [['size'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size' => 'id']],
            [['size_type'], 'exist', 'skipOnError' => true, 'targetClass' => SizeType::className(), 'targetAttribute' => ['size_type' => 'id']],
            [['boyoqhona_color_id'], 'exist', 'skipOnError' => true, 'targetClass' => Color::className(), 'targetAttribute' => ['boyoqhona_color_id' => 'id']],
            [['boyoqhona_model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['boyoqhona_model_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'barcode' => Yii::t('app', 'Barcode'),
            'barcode1' => Yii::t('app', 'Barcode1'),
            'barcode2' => Yii::t('app', 'Barcode2'),
            'brand1' => Yii::t('app', 'Brend-1'),
            'brand2' => Yii::t('app', 'Brend-2'),
            'brand3' => Yii::t('app', 'Brend-3'),
            'type' => Yii::t('app', 'Type'),
            'model_no' => Yii::t('app', 'Model No'),
            'model_var' => Yii::t('app', 'Model Var ID'),
            'model_id' => Yii::t('app', 'Model ID'),
            'size_type' => Yii::t('app', 'Size Type'),
            'package_code' => Yii::t('app', "O'ram kodi"),
            'size' => Yii::t('app', 'Size'),
            'color' => Yii::t('app', 'Color'),
            'name' => Yii::t('app', 'Name'),
            'old_name' => Yii::t('app', 'Old Name'),
            'properties' => Yii::t('app', 'Tarkibi'),
            'color_name' => Yii::t('app', 'Color Name'),
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_var']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize0()
    {
        return $this->hasOne(Size::className(), ['id' => 'size']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductModel()
    {
        return $this->hasOne(Product::className(), ['id' => 'boyoqhona_model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'boyoqhona_color_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSizeType()
    {
        return $this->hasOne(SizeType::className(), ['id' => 'size_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvGoodsDocs()
    {
        return $this->hasMany(TikuvGoodsDoc::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvGoodsDocAccepteds()
    {
        return $this->hasMany(TikuvGoodsDocAccepted::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvGoodsDocMovings()
    {
        return $this->hasMany(TikuvGoodsDocMoving::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvOutcomeProducts()
    {
        return $this->hasMany(TikuvOutcomeProducts::className(), ['goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSizeName()
    {
        return $this->hasOne(Size::className(), ['id' => 'size']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorPantone()
    {
        return $this->hasOne(ColorPantone::className(), ['id' => 'color']);
    }

    public function getBrand($index, $default = null)
    {
        $out = null;
        if(!empty($default)){
            return $default;
        }
        switch ($index) {
            case 1:
                $brand = Brend::find()->where(['id' => $this->brand1])->asArray()->one();
                if (!empty($brand)) {
                    $out = $brand['name'];
                }
                break;
            case 2:
                $brand = Brend::find()->where(['id' => $this->brand2])->asArray()->one();
                if (!empty($brand)) {
                    $out = $brand['name'];
                }
                break;
            case 3:
                $brand = Brend::find()->where(['id' => $this->brand3])->asArray()->one();
                if (!empty($brand)) {
                    $out = $brand['name'];
                }
                break;
        }
        return $out;
    }
}
