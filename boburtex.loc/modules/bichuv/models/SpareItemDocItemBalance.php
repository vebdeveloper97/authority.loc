<?php

namespace app\modules\bichuv\models;

use app\modules\hr\models\HrDepartments;
use app\modules\wms\models\WmsDepartmentArea;
use Yii;

/**
 * This is the model class for table "spare_item_doc_item_balance".
 *
 * @property int $id
 * @property int $entity_id
 * @property string $quantity
 * @property string $inventory
 * @property string $reg_date
 * @property int $department_id
 * @property string $price_uzs
 * @property string $price_usd
 * @property int $document_id
 * @property string $add_info
 * @property int $document_type
 * @property int $from_department
 * @property int $to_department
 * @property int $dep_area
 * @property int $from_area
 * @property int $to_area
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property WmsDepartmentArea $depArea
 * @property HrDepartments $department
 * @property SpareItemDoc $document
 * @property SpareItem $entity
 * @property WmsDepartmentArea $fromArea
 * @property HrDepartments $fromDepartment
 * @property WmsDepartmentArea $toArea
 * @property HrDepartments $toDepartment
 */
class SpareItemDocItemBalance extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare_item_doc_item_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'department_id', 'document_id', 'document_type', 'from_department', 'to_department', 'dep_area', 'from_area', 'to_area', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['quantity', 'inventory', 'price_uzs', 'price_usd'], 'number'],
            [['reg_date'], 'safe'],
            [['doc_type'], 'safe'],
            [['add_info'], 'string'],
            [['dep_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['dep_area' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItemDoc::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItem::className(), 'targetAttribute' => ['entity_id' => 'id']],
            [['from_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['from_area' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['to_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['to_area' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_id' => 'Entity ID',
            'quantity' => 'Quantity',
            'inventory' => 'Inventory',
            'reg_date' => 'Reg Date',
            'department_id' => 'Department ID',
            'price_uzs' => 'Price Uzs',
            'price_usd' => 'Price Usd',
            'document_id' => 'Document ID',
            'add_info' => 'Add Info',
            'document_type' => 'Document Type',
            'from_department' => 'From Department',
            'to_department' => 'To Department',
            'dep_area' => 'Dep Area',
            'from_area' => 'From Area',
            'to_area' => 'To Area',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepArea()
    {
        return $this->hasOne(WmsDepartmentArea::className(), ['id' => 'dep_area']);
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
    public function getDocument()
    {
        return $this->hasOne(SpareItemDoc::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(SpareItem::className(), ['id' => 'entity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromArea()
    {
        return $this->hasOne(WmsDepartmentArea::className(), ['id' => 'from_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'from_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToArea()
    {
        return $this->hasOne(WmsDepartmentArea::className(), ['id' => 'to_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToDepartment()
    {
        return $this->hasOne(HrDepartments::className(), ['id' => 'to_department']);
    }
    /**
     * @param array $data
     * @return int|mixed
     */
    public static function getLastRecord($data = []){
        if(!empty($data)){
            $lastEntity = self::find()->where(['entity_id' => $data['entity_id'], 'department_id' => $data['department_id']])
                ->orderBy(['id' => SORT_DESC])->asArray()->one();
            if(!empty($lastEntity)){
                return $lastEntity['inventory']+$data['quantity'];
            }else{
                return $data['quantity'];
            }
        }
        return 0;
    }
}
