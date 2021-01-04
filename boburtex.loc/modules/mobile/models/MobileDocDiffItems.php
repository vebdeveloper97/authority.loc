<?php

namespace app\modules\mobile\models;

use app\modules\base\models\Unit;
use app\modules\hr\models\HrDepartments;
use Yii;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "mobile_doc_diff_items".
 *
 * @property int $id
 * @property int $doc_items_id
 * @property string $table_name
 * @property string $diff_qty
 * @property int $unit_id
 * @property int $department_id
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrDepartments $department
 * @property Unit $unit
 */
class MobileDocDiffItems extends \app\modules\mobile\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mobile_doc_diff_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_items_id', 'unit_id', 'department_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['diff_qty'], 'number'],
            [['add_info'], 'string'],
            [['table_name'], 'string', 'max' => 60],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
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
            'doc_items_id' => Yii::t('app', 'Doc Items ID'),
            'table_name' => Yii::t('app', 'Table Name'),
            'diff_qty' => Yii::t('app', 'Diff Qty'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'department_id' => Yii::t('app', 'Department ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    public static function saveMobileDocDiffItems(array $data, $id = null){

        $sql = "SHOW COLUMNS FROM %s ;";
        $sql = sprintf($sql,self::getTableSchema()->name);

        $fields = Yii::$app->db->createCommand($sql)->queryAll();
        $existsFields = [];
        foreach ($fields as $item){
            array_push($existsFields, $item['Field']);
        }
        $model = new self();
        if(!empty($id)){
            $model = self::findOne($id);
            if($model === null){
                Yii::error('Not found Mobile Process Production ID','save');
                return false;
            }
        }
       
        foreach ($data as $field => $value){
            if(in_array($field, $existsFields)){
                $model->{$field} = $value;
            }
        }
        if($model->save()){
            return true;
        }
        return false;
    }

    /**
     * @param array $selfArr
     * @param $quantity
     * @param $factQuantity
     * @return bool
     * @throws InvalidConfigException
     */
    public static function saveAs(array $selfArr, $quantity, $factQuantity) {
        if (!empty($selfArr) && is_array($selfArr)) {
            $tmpSelf = new static();
            foreach ($selfArr as $key => $item) {
                if (!$tmpSelf->hasAttribute($key)) {
                    throw new InvalidConfigException('This attribute was not found: ' . $key);
                }
            }
        }
        else {
            return false;
        }

        // faqat miqdor va fakt miqdorlari farqi bo'lganlarini yozadi
        if (self::hasDiff($quantity, $factQuantity)) {
            $newSelf = new static();
            $newSelf->setAttributes($selfArr);
            $newSelf->diff_qty = $factQuantity - $quantity;

            if (!$newSelf->save()) {
                Yii::error($newSelf->getErrors(), 'save');
                return  false;
            }
        }

        return true;
    }

    public static function hasDiff($quantity, $factQuantity) {
        return ($quantity - $factQuantity) != 0;
    }
}
