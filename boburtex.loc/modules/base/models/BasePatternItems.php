<?php

namespace app\modules\base\models;

use app\modules\bichuv\models\BichuvDetailTypes;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "base_pattern_items".
 *
 * @property int $id
 * @property int $bichuv_detail_type_id
 * @property int $base_detail_list_id
 * @property int $base_pattern_id
 * @property int $pattern_item_type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BaseDetailLists $baseDetailList
 * @property BasePatternPart $basePatternPart
 * @property BasePatterns $basePattern
 * @property BichuvDetailTypes $bichuvDetailType
 * @property int $base_pattern_part_id [int(11)]
 * @property array $bichuvDetailTypeList
 * @property array $baseDetailTypeList
 * @property array $basePatternPartList
 */
class BasePatternItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_pattern_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_detail_type_id', 'base_patterns_variant_id', 'base_pattern_part_id','base_detail_list_id', 'base_pattern_id', 'pattern_item_type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['base_detail_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseDetailLists::className(), 'targetAttribute' => ['base_detail_list_id' => 'id']],
            [['base_pattern_id'], 'exist', 'skipOnError' => true, 'targetClass' => BasePatterns::className(), 'targetAttribute' => ['base_pattern_id' => 'id']],
            [['bichuv_detail_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDetailTypes::className(), 'targetAttribute' => ['bichuv_detail_type_id' => 'id']],
            [['base_pattern_part_id'], 'exist', 'skipOnError' => true, 'targetClass' => BasePatternPart::className(), 'targetAttribute' => ['base_pattern_part_id' => 'id']],
            [['base_patterns_variant_id'], 'exist', 'skipOnError' => true, 'targetClass' => BasePatternsVariations::className(), 'targetAttribute' => ['base_patterns_variant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bichuv_detail_type_id' => Yii::t('app', 'Bichuv Detail Type ID'),
            'base_detail_list_id' => Yii::t('app', 'Base Detail List ID'),
            'base_pattern_part_id' => Yii::t('app', 'Base Pattern Part ID'),
            'base_pattern_id' => Yii::t('app', 'Base Pattern ID'),
            'pattern_item_type' => Yii::t('app', 'Pattern Item Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseDetailList()
    {
        return $this->hasOne(BaseDetailLists::className(), ['id' => 'base_detail_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatternPart()
    {
        return $this->hasOne(BasePatternPart::className(), ['id' => 'base_pattern_part_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePattern()
    {
        return $this->hasOne(BasePatterns::className(), ['id' => 'base_pattern_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDetailType()
    {
        return $this->hasOne(BichuvDetailTypes::className(), ['id' => 'bichuv_detail_type_id']);
    }

    public function getBichuvDetailTypeList(){
        $bdtl = BichuvDetailTypes::find()->asArray()->all();
        return ArrayHelper::map($bdtl,'id','name');
    }

    public function getBaseDetailTypeList(){
        $bdtl = BaseDetailLists::find()->asArray()->all();
        return ArrayHelper::map($bdtl,'id','name');
    }

    public function getBasePatternPartList(){
        $bdtl = BasePatternPart::find()->asArray()->all();
        return ArrayHelper::map($bdtl,'id','name');
    }

    public function getVariants($id)
    {
        /*$data = ArrayHelper::map(BasePatternsVariations::find()->where(['base_patterns_id' => $id])->all(),'id', function($m){
            return $m['variant_no'].' - '.Yii::t('app', 'Variant');
        });*/
        $data = BasePatternsVariations::find()->where(['base_patterns_id' => $id, 'status' => 1])->one();
        return $data['id'];
    }
}
