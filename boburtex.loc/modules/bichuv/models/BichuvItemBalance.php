<?php

namespace app\modules\bichuv\models;

use app\modules\hr\models\HrDepartments;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\wms\models\WmsDepartmentArea;
use Yii;

/**
 * This is the model class for table "bichuv_item_balance".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $lot
 * @property string $count
 * @property double $inventory
 * @property string $reg_date
 * @property int $department_id
 * @property int $is_own
 * @property string $price_uzs
 * @property string $price_usd
 * @property string $price_rub
 * @property string $price_eur
 * @property string $sold_price_uzs
 * @property string $sold_price_usd
 * @property string $sold_price_rub
 * @property string $sold_price_eur
 * @property string $sum_uzs
 * @property string $sum_usd
 * @property string $sum_rub
 * @property string $sum_eur
 * @property int $document_id
 * @property int $to_department
 * @property string $document_type
 * @property int $version
 * @property string $comment
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvDepartments $department
 * @property ToquvDepartments $departmentTo
 * @property ToquvDepartments $fromDepartment
 * @property int $from_department [int(11)]
 */
class BichuvItemBalance extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_item_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_type','from_department', 'department_id','to_department', 'is_own', 'document_id', 'version', 'status', 'created_by', 'created_at', 'updated_at', 'document_type', 'to_area', 'from_area', 'dep_area'], 'integer'],
            [['count', 'inventory', 'price_uzs', 'price_usd', 'price_rub', 'price_eur', 'sold_price_uzs', 'sold_price_usd', 'sold_price_rub', 'sold_price_eur', 'sum_uzs', 'sum_usd', 'sum_rub', 'sum_eur'], 'number'],
            [['reg_date'], 'safe'],
            [['comment'], 'string'],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['dep_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['dep_area' => 'id']],
            [['to_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['to_area' => 'id']],
            [['from_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['from_area' => 'id']],
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
            'lot' => Yii::t('app', 'Lot'),
            'count' => Yii::t('app', 'Count'),
            'inventory' => Yii::t('app', 'Inventory'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'department_id' => Yii::t('app', 'Department ID'),
            'to_department' => Yii::t('app', 'Department To'),
            'from_department' => Yii::t('app', 'From Department'),
            'is_own' => Yii::t('app', 'Is Own'),
            'price_uzs' => Yii::t('app', 'Price Uzs'),
            'price_usd' => Yii::t('app', 'Price Usd'),
            'price_rub' => Yii::t('app', 'Price Rub'),
            'price_eur' => Yii::t('app', 'Price Eur'),
            'sold_price_uzs' => Yii::t('app', 'Sold Price Uzs'),
            'sold_price_usd' => Yii::t('app', 'Sold Price Usd'),
            'sold_price_rub' => Yii::t('app', 'Sold Price Rub'),
            'sold_price_eur' => Yii::t('app', 'Sold Price Eur'),
            'sum_uzs' => Yii::t('app', 'Sum Uzs'),
            'sum_usd' => Yii::t('app', 'Sum Usd'),
            'sum_rub' => Yii::t('app', 'Sum Rub'),
            'sum_eur' => Yii::t('app', 'Sum Eur'),
            'document_id' => Yii::t('app', 'Document ID'),
            'document_type' => Yii::t('app', 'Document Type'),
            'version' => Yii::t('app', 'Version'),
            'comment' => Yii::t('app', 'Comment'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
    public function getDepartmentTo()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'to_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'from_department']);
    }

    public function afterFind()
    {
        $this->reg_date = date('d.m.Y', strtotime($this->reg_date));
        parent::afterFind();
    }

    /**
     * @param array $data
     * @return int|mixed
     */
    public static function getLastRecord($data = []){
        if(!empty($data)){
            $lastEntity = self::find()
                ->where([
                    'entity_id' => $data['entity_id'],
                    'entity_type' => $data['entity_type'],
                    'department_id' => $data['department_id']])
                ->orderBy(['id' => SORT_DESC])->asArray()->one();
            if(!empty($lastEntity)){
                return (double)$lastEntity['inventory'] + (double)$data['quantity'];
            }else{
                return $data['quantity'];
            }
        }
        return 0;
    }


    /**
     * @param array $data
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getLastRecordMoving($data = []){
        if(!empty($data)){
            $lastEntity = self::find()->where(['entity_id' => $data['entity_id'], 'entity_type' => $data['entity_type'], 'department_id' => $data['department_id']])
                ->orderBy(['id' => SORT_DESC])->asArray()->one();
            if(!empty($lastEntity)){
                return $lastEntity;
            }
        }
        return [];
    }

    /**
     * @param int $type
     * @param null $entityId
     * @return array|string|null
     * @throws \yii\db\Exception
     */
    public function getEntities($type = 1, $entityId = null){
        switch ($type){
            case BichuvDoc::DOC_TYPE_INCOMING:
                $modelTDI = new BichuvDocItems;
                return $modelTDI->getAccessories($entityId);
                break;
            case BichuvDoc::DOC_TYPE_MOVING:
                break;
        }
    }

    /**
     * @return array
     */
    public function getAccsProperties()
    {
        $model = new BichuvAcs();
        return $model->getAllProperties();
    }
    public function getAksInfo()
    {
        $aks = $this->aks;
        return "<b><span style='color:lime;background-color: maroon;padding: 0 5px;;'>{$aks->name}</span></b> (<b>{$aks->property->name}</b>) - <b>{$this->inventory}</b> kg)";
    }

    public function getAks()
    {
        $aks = BichuvAcs::findOne($this->entity_id);
        if($aks){
            return $aks;
        }
        return false;
    }

    public static function increaseItem(self $itemBalance) {
        $itemInventory = self::getInventory($itemBalance->entity_id, $itemBalance->entity_type, $itemBalance->department_id);

        $itemBalance->inventory = (double)$itemBalance->count + (double)$itemInventory;

        return $itemBalance->save();
    }

    public static function decreaseItem(self $itemBalance) {
        $itemInventory = self::getInventory($itemBalance->entity_id, $itemBalance->entity_type, $itemBalance->department_id);

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

    public static function getInventory($entityId, $entityType, $departmentId = null) {
        $inventory = static::find()
            ->andWhere([
                'entity_id' => $entityId,
                'entity_type' => $entityType,
                'department_id' => $departmentId,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->limit(1)
            ->one();

        return isset($inventory['inventory']) ? $inventory['inventory'] : null;
    }
}
