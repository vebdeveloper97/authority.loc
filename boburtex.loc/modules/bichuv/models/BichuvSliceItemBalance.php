<?php

namespace app\modules\bichuv\models;

use app\modules\admin\models\UsersHrDepartments;
use app\modules\base\models\ModelVarPrints;
use app\modules\base\models\ModelVarStone;
use app\modules\hr\models\HrDepartments;
use Yii;
use app\modules\base\models\Size;
use app\modules\toquv\models\ToquvDepartments;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\ToquvUserDepartment;

/**
 * This is the model class for table "bichuv_slice_item_balance".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $party_no
 * @property int $size_id
 * @property string $count
 * @property string $inventory
 * @property int $doc_id
 * @property int $doc_type
 * @property int $department_id
 * @property int $from_department
 * @property int $to_department
 * @property int $status
 * @property int $updated_at
 * @property int $created_at
 *
 * @property ToquvDepartments $department
 * @property Product $productModel
 * @property ToquvDepartments $fromDepartment
 * @property Size $size
 * @property ToquvDepartments $toDepartment
 * @property int $created_by [int(11)]
 * @property int $model_id [smallint(6)]
 * @property int $from_hr_department [int]
 * @property int $to_hr_department [int]
 * @property int $hr_department_id [int]
 */
class BichuvSliceItemBalance extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_slice_item_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id','model_id', 'created_by', 'entity_type', 'size_id', 'doc_id', 'doc_type', 'department_id', 'from_department', 'to_department', 'status', 'updated_at', 'created_at'], 'integer'],
            [['count', 'inventory'], 'number'],
            [['party_no'], 'string', 'max' => 20],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['from_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['from_hr_department' => 'id']],
            [['to_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['to_hr_department' => 'id']],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_department_id' => 'id']],

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
            'inventory' => Yii::t('app', 'Inventory'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'doc_type' => Yii::t('app', 'Doc Type'),
            'department_id' => Yii::t('app', 'Department ID'),
            'from_department' => Yii::t('app', 'From Department'),
            'to_department' => Yii::t('app', 'To Department'),
            'status' => Yii::t('app', 'Status'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
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
    public function getProductModel()
    {
        return $this->hasOne(Product::className(), ['id' => 'model_id']);
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
     * @return BichuvSliceItemBalance|array|bool|yii\db\ActiveRecord|null
     */
    public static function getLastRecord($item){

        $userId = Yii::$app->user->id;
        $dept = UsersHrDepartments::find()->select(['hr_departments_id'])->where(['user_id' => $userId])->asArray()->all();
        $deptId = ArrayHelper::getColumn($dept,'hr_departments_id');
        $result = self::find()->where([
            'party_no' => $item['nastel_party'],
            'size_id' => $item['size_id'],
            'hr_department_id' => $deptId
        ])->orderBy(['id' => SORT_DESC])->asArray()->one();
        if(!empty($result)){
            return $result;
        }
        return false;
    }
    public static function getLastRecordSlice($item){
        $result = self::find()->where([
            'party_no' => $item['nastel_party'],
            'size_id' => $item['size_id'],
            'hr_department_id' => $item['department_id']
        ])->orderBy(['id' => SORT_DESC])->asArray()->one();

        if(!empty($result)){
            return $result;
        }
        return false;
    }
    /**
     * @param $item
     * @return BichuvSliceItemBalance|array|bool|yii\db\ActiveRecord|null
     */
    public static function getLastFromItemDept($item){
        $result = self::find()->where([
            'party_no' => $item['nastel_party'],
            'size_id' => $item['size_id'],
            'department_id' => $item['department_id']
        ])->orderBy(['id' => SORT_DESC])->asArray()->one();

        if(!empty($result)){
            return $result;
        }
        return false;
    }

    public static function increaseItem(self $itemBalance) {
        $itemInventory = self::getInventory($itemBalance->entity_type, $itemBalance->party_no, $itemBalance->size_id, $itemBalance->from_hr_department);

        $itemBalance->inventory = (double)$itemBalance->count + (double)$itemInventory;

        return $itemBalance->save();
    }

    public static function decreaseItem(self $itemBalance) {
        $itemInventory = self::getInventory($itemBalance->entity_type, $itemBalance->party_no, $itemBalance->size_id, $itemBalance->from_hr_department);

        $inv = (double)$itemInventory;
        $cnt = (double)$itemBalance->count;
        if ($inv < $cnt) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'The amount of this product is not enough'));
            return  false;
        }
        $itemBalance->inventory = $inv - $cnt;
        $itemBalance->count = -$cnt;

        return $itemBalance->save();
    }

    public static function getInventory($entityType, $partyNo, $sizeId, $departmentId = null) {
        $inventory = static::find()
            ->andWhere([
                'entity_type' => $entityType,
                'party_no' => $partyNo,
                'size_id' => $sizeId,
                'hr_department_id' => $departmentId,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->one();

        return isset($inventory['inventory']) ? $inventory['inventory'] : null;
    }
}
