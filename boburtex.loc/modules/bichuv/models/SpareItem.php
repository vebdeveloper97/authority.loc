<?php

namespace app\modules\bichuv\models;

use app\modules\base\models\Unit;
use app\modules\wms\models\WmsDepartmentArea;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "spare_item".
 *
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property int $unit_id
 * @property string $barcode
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Unit $unit
 * @property SpareItemDocItemBalance[] $spareItemDocItemBalances
 * @property SpareItemDocItems[] $spareItemDocItems
 * @property SpareItemProperty[] $spareItemProperties
 */
class SpareItem extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_id', 'status', 'created_at','type','updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['sku'], 'string', 'max' => 50],
            [['add_info'], 'string'],
            [['stock_limit_max', 'stock_limit_min'], 'safe'],
            [['barcode'], 'string', 'max' => 100],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
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
            'sku' => Yii::t('app', 'Sku'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'barcode' => Yii::t('app', 'Barcode Name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'add_info' => Yii::t('app', 'Add Info'),
            'updated_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'stock_limit_min' => Yii::t('app', 'stock_limit_min'),
            'stock_limit_max' => Yii::t('app', 'stock_limit_max'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItemDocItemBalances()
    {
        return $this->hasMany(SpareItemDocItemBalance::className(), ['entity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItemDocItems()
    {
        return $this->hasMany(SpareItemDocItems::className(), ['entity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItemProperties()
    {
        return $this->hasMany(SpareItemProperty::className(), ['spare_item_id' => 'id']);
    }

    public static function getAllUnits()
    {
        $ne = \app\modules\base\models\Unit::find()->all();
        return ArrayHelper::map($ne,'id','name');
    }

    // bichuv properties list malumotlarni select2 ga chiqarish
    public function getAllData($name=null)
    {
        if($name === null) {
            $result = ArrayHelper::map(BichuvAcsPropertyList::find()
                ->all(), 'id', 'name');
            return $result;
        }
    }

    // save
    public function getSave($data)
    {
        if(!$this->validate()){
            return false;
        }

        if(!empty($data)){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $this->status = SpareItem::STATUS_ACTIVE;
                if($this->save()){
                    $saved= true;
                }
                else{
                    $saved = false;
                }

                foreach ($data['SpareItemProperty'] as $item) {
                    $model = new SpareItemProperty();
                    $model->spare_item_id = $this->id;
                    $model->spare_item_property_list_id = $item['spare_item_property_list_id'];
                    $model->status = SpareItemProperty::STATUS_ACTIVE;
                    $model->value = $item['value'];

                    if($model->save()){
                        $saved = true;
                        unset($model);
                    }
                    else{
                        $saved = false;
                    }
                }

                if($saved){
                    $transaction->commit();
                    return true;
                }
                else{
                    $transaction->rollBack();
                    return false;
                }
            }
            catch (\Exception $e){

                Yii::info('error message '.$e->getMessage(), 'save');
            }
        }
        else
            return false;
    }

    // bichuv properties list malumotlarni select2 ga chiqarish
    public function getProperty($name=null)
    {
        if($name === null) {
            $result = ArrayHelper::map(SpareItemPropertyList::find()
                ->all(), 'id', 'name');
            return $result;
        }
    }

    // ajax barcode orqali acc ni topish
    public function getSpares($param1, $param2, $department=null)
    {
        if(!empty($param1) && !empty($param2)){
            if($param2 === SpareItemDoc::DOC_TYPE_INCOMING_LABEL){
                $result = SpareItem::find()
                    ->alias('ba')
                    ->select(['ba.id', 'ba.sku', 'ba.name'])
                    ->joinWith(['spareItemProperties' => function ($query) {
                        $query->select(['spare_item_id','value']);
                    }])
                    ->where(['like', 'ba.barcode', $param1])
                    ->asArray()
                    ->all();

                if(!empty($result)){
                    return $result;
                }
                else{
                    return false;
                }
            }
            elseif($param2 === self::DOC_TYPE_MOVING_LABEL || $param2 === self::DOC_TYPE_OUTGOING_LABEL){
                $sql = "
                    SELECT 
                    spare_item.sku, spare_item.name, spare_item_properties.value,
                    spare_item_balance.inventory, spare_item_balance.quantity, 
                    spare_item_balance.id, spare_item_balance.entity_id 
                    FROM spare_item 
                    LEFT JOIN 
                    bichuv_acs_properties 
                    ON bichuv_acs.id = bichuv_acs_properties.bichuv_acs_id 
                    LEFT JOIN bichuv_item_balance 
                    ON bichuv_item_balance.entity_id = bichuv_acs_properties.bichuv_acs_id 
                    WHERE bichuv_item_balance.id IN ( 
                    SELECT MAX(id) 
                    FROM bichuv_item_balance 
                    GROUP BY bichuv_item_balance.entity_id, bichuv_item_balance.department_id 
                    ORDER BY id DESC
                     ) 
                     AND bichuv_acs.barcode LIKE '%{$param1}%'
                     AND bichuv_item_balance.department_id = $department
                ";
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                if(!empty($result)){
                    return $result;
                }
                else{
                    return false;
                }
            }
        }
        else{
            return false;
        }
    }

    public function showView($data, $keyVal = null)
    {
        if(!empty($data)){
            $sql = "
                SELECT 
                    spare_item.name, spare_item.id,
                    spare_item_property.value
                FROM
                    spare_item
                LEFT JOIN spare_item_property
                ON spare_item.id = spare_item_property.spare_item_id
                WHERE spare_item.id = $data    
            ";
            $query = Yii::$app->db->createCommand($sql)->queryAll();
            if(!empty($query)){
                $str = '';
                $result = [];
                if ($keyVal != null) {
                    return ArrayHelper::map($query, 'id', function ($m) {
                        return $m['sku'] . ' ' . $m['name'] . ' ' . $m['value'];
                    });
                }
                else {
                    foreach ($query as $ip) {
                        if(isset($result['data'][$ip['id']])){
                            $str .= '  '."<span style='color: darkorange'>".$ip['value']."</span>";
                        }
                        else{
                            $str = '';
                            $str .= $ip['name'] . ' ' . "<span style='color: darkorange'>".$ip['value']."</span>";
                        }
                        $result['data'][$ip['id']] = $str;
                    }
                }
                return $result['data'];
            }
        }
        else{
            return false;
        }
    }

    public function getPropertyName()
    {
        $proprtyName = ArrayHelper::map(SpareItemPropertyList::find()->all(),'id', 'name');
        return $proprtyName;
    }

    public function getPropertValue()
    {
        $propertyValue = SpareItemProperty::find()
            ->groupBy(['value'])
            ->all();
        $result = ArrayHelper::map($propertyValue,'value', 'value');
        return $result;
    }

    public function getSpareItemName()
    {
        return ArrayHelper::map(SpareItem::find()->all(), 'name', 'name');
    }

    public function getSpareSku()
    {
        return ArrayHelper::map(SpareItem::find()->all(), 'sku', 'sku');
    }

    public function getUnitAll()
    {
        return ArrayHelper::map(Unit::find()->all(), 'id', 'name');
    }

    public function getArea($id)
    {
        $model = WmsDepartmentArea::find()
            ->where($id)
            ->one();
        return $model->name;
    }

    public static function getListMap()
    {
        return ArrayHelper::map(static::find()->asArray()->all(), 'id','name');
    }


    public static function getSpareName($id){
        return static::findOne(['id' => $id])->name;
    }

    public static function getSpareListNotByTypeMap($type = null){
        if (!is_null($type)){
            $spares = self::find()->where(['not',['type' => $type]])->asArray()->all();
            if (!empty($spares))
                return ArrayHelper::map($spares,'id','name');
        }
        return false;
    }

}
