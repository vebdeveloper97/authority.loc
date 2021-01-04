<?php

namespace app\modules\base\models;

use app\modules\tikuv\models\TikuvOutcomeProducts;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "size".
 *
 * @property int $id
 * @property int $size_type_id
 * @property string $name
 * @property string $code
 *
 * @property ModelOrdersItemsSize[] $modelOrdersItemsSizes
 * @property SizeType $sizeType
 * @property SizeColRelSize[] $sizeColRelSizes
 * @property TikuvOutcomeProducts[] $tikuvOutcomeProducts
 * @property int $order [int(11)]
 */
class Size extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'size';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size_type_id', 'name'], 'required'],
            [['size_type_id', 'order'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 20],
            [['size_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SizeType::className(), 'targetAttribute' => ['size_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'size_type_id' => Yii::t('app', 'Size Type ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsSizes()
    {
        return $this->hasMany(ModelOrdersItemsSize::className(), ['size_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSizeType()
    {
        return $this->hasOne(SizeType::className(), ['id' => 'size_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSizeColRelSizes()
    {
        return $this->hasMany(SizeColRelSize::className(), ['size_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvOutcomeProducts()
    {
        return $this->hasMany(TikuvOutcomeProducts::className(), ['size_id' => 'id']);
    }
}
