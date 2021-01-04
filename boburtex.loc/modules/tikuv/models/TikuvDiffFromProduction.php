<?php

namespace app\modules\tikuv\models;

use app\modules\base\models\Size;
use app\modules\toquv\models\SortName;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "tikuv_diff_from_production".
 *
 * @property int $id
 * @property int $tikuv_doc_item_id
 * @property int $size_id
 * @property int $sort_id
 * @property int $quantity
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Size $size
 * @property SortName $sort
 * @property TikuvDocItems $tikuvDocItem
 * @property int $tikuv_op_id [int(11)]
 * @property string $nastel_no [varchar(30)]
 * @property ActiveQuery $tikuvOutcomeProduct
 * @property int $is_service [smallint(6)]
 */
class TikuvDiffFromProduction extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_diff_from_production';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tikuv_doc_item_id', 'size_id', 'sort_id', 'quantity', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'is_service'], 'integer'],
            ['nastel_no', 'string','max' => 30],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
            [['sort_id'], 'exist', 'skipOnError' => true, 'targetClass' => SortName::className(), 'targetAttribute' => ['sort_id' => 'id']],
            [['tikuv_doc_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvDocItems::className(), 'targetAttribute' => ['tikuv_doc_item_id' => 'id']],
            [['tikuv_op_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvOutcomeProducts::className(), 'targetAttribute' => ['tikuv_op_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tikuv_doc_item_id' => Yii::t('app', 'Tikuv Doc Item ID'),
            'tikuv_op_id' => Yii::t('app', 'Tikuv Outcome Products ID'),
            'size_id' => Yii::t('app', 'Size ID'),
            'sort_id' => Yii::t('app', 'Sort ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvOutcomeProduct()
    {
        return $this->hasOne(TikuvOutcomeProducts::className(), ['id' => 'tikuv_op_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSort()
    {
        return $this->hasOne(SortName::className(), ['id' => 'sort_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvDocItem()
    {
        return $this->hasOne(TikuvDocItems::className(), ['id' => 'tikuv_doc_item_id']);
    }
}
