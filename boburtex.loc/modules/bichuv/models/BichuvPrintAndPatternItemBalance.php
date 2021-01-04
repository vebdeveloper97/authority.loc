<?php

namespace app\modules\bichuv\models;

use app\modules\admin\models\ToquvUserDepartment;
use app\modules\base\models\ModelOrdersItems;
use app\modules\hr\models\HrDepartments;
use Yii;
use app\modules\bichuv\models\BaseModel;
use app\modules\toquv\models\ToquvDepartments;
use app\models\Size;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_print_and_pattern_item_balance".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $party_no
 * @property int $size_id
 * @property string $count
 * @property string $invalid_count
 * @property string $inventory
 * @property int $doc_id
 * @property int $doc_type
 * @property int $department_id
 * @property int $from_department
 * @property int $to_department
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvDepartments $department
 * @property ToquvDepartments $fromDepartment
 * @property Size $size
 * @property ToquvDepartments $toDepartment
 */
class BichuvPrintAndPatternItemBalance extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_print_and_pattern_item_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_type', 'size_id', 'doc_id', 'doc_type', 'department_id', 'from_department', 'to_department', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['count', 'invalid_count', 'inventory'], 'number'],
            [['party_no'], 'string', 'max' => 20],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['to_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['to_hr_department' => 'id']],
            [['from_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['from_hr_department' => 'id']],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_department_id' => 'id']],
//            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'party_no' => Yii::t('app', 'Party No'),
            'size_id' => Yii::t('app', 'Size ID'),
            'count' => Yii::t('app', 'Count'),
            'invalid_count' => Yii::t('app', 'Invalid Count'),
            'inventory' => Yii::t('app', 'Inventory'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'doc_type' => Yii::t('app', 'Doc Type'),
            'department_id' => Yii::t('app', 'Department ID'),
            'from_department' => Yii::t('app', 'From Department'),
            'to_department' => Yii::t('app', 'To Department'),
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
    public function getFromHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'from_hr_department']);
    }
    /**
     * @return ActiveQuery
     */
    public function getToHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'to_hr_department']);
    }
    /**
     * @return ActiveQuery
     */
    public function getHrDepartmentId()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'from_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'to_department']);
    }
    /**
     * @param $item
     * @return BichuvPrintAndPatternItemBalance|array|bool|yii\db\ActiveRecord|null
     */
    public static function getLastRecord($item){

        $result = self::find()->where(array(
            'party_no' => $item['nastel_party'],
            'size_id' => $item['size_id'],
            'hr_department_id' => $item['department_id']
        ))->orderBy(['id' => SORT_DESC])->asArray()->one();

        if(!empty($result)){
            return $result;
        }
        return false;
    }
}
