<?php

namespace app\models;

use app\modules\base\models\ModelOrdersItemsSize;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "size".
 *
 * @property int $id
 * @property int $size_type_id
 * @property string $name
 * @property string $code
 * @property int $order
 *
 * @property ModelOrdersItemsSize[] $modelOrdersItemsSizes
 * @property SizeType $sizeType
 * @property SizeColRelSize[] $sizeColRelSizes
 * @property TikuvOutcomeProducts[] $tikuvOutcomeProducts
 */
class Size extends \yii\db\ActiveRecord
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
            'order' => Yii::t('app', 'Order'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSizeColRelSizes()
    {
        return $this->hasMany(SizeColRelSize::className(), ['size_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItemsSizes()
    {
        return $this->hasMany(ModelOrdersItemsSize::className(), ['size_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvOutcomeProducts()
    {
        return $this->hasMany(TikuvOutcomeProducts::className(), ['size_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSizeType()
    {
        return $this->hasOne(SizeType::className(), ['id' => 'size_type_id']);
    }
    public static function getSize($id,$start,$end){
        $st = static::findOne(['size_type_id'=>$id,'code'=>$start])['id'];
        $en = static::findOne(['size_type_id'=>$id,'code'=>$end])['id'];
        $size = static::find()->select(['id','name'])->where(['size_type_id'=>$id])->andWhere(['>=','id',$st])->andWhere(['<=','id',$en])->all();
        $response = ArrayHelper::toArray($size);
        return $response;
    }
    public static function getSizeList($id=null,$group=false)
    {
        $size = Size::find()->select(['size.id', 'size.name','size_type_id']);
        if($group) {
           $size = $size->joinWith('sizeType');
        }
        if($id) {
            $size = $size->where(['size_type_id' => $id]);
        }
        $size = $size->asArray()->all();
        if($group){
            return ArrayHelper::map($size, 'id', 'name', 'sizeType.name');
        }
        return ArrayHelper::map($size, 'id', 'name');
    }
}
