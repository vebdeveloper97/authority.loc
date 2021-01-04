<?php

namespace app\modules\tikuv\models;

use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\Musteri;
use app\modules\hr\models\HrDepartments;
use app\modules\mobile\models\MobileProcess;
use app\modules\mobile\models\MobileTables;
use app\modules\wms\models\WmsDocument;
use app\modules\wms\models\WmsDocumentItems;
use app\modules\wms\models\WmsItemBalance;
use Yii;
use app\modules\base\models\Size;
use app\modules\bichuv\models\Product;
use app\modules\toquv\models\ToquvDepartments;

/**
 * This is the model class for table "tikuv_slice_item_balance".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $entity_type
 * @property int $size_id
 * @property string $nastel_no
 * @property string $count
 * @property string $inventory
 * @property int $doc_id
 * @property int $doc_type
 * @property int $department_id
 * @property int $from_department
 * @property int $to_department
 * @property int $hr_department_id
 * @property int $from_hr_department
 * @property int $to_hr_department
 * @property int $model_id
 * @property int $created_by
 * @property int $status
 * @property int $updated_at
 * @property int $created_at
 * @property int $mobile_process_id
 * @property int $mobile_tables
 *
 * @property ToquvDepartments $department
 * @property ToquvDepartments $fromDepartment
 * @property MobileTables $mobileTables
 * @property Product $model
 * @property ModelsList $modelList
 * @property Size $size
 * @property Musteri $musteri
 * @property ToquvDepartments $toDepartment
 * @property int $musteri_id [bigint(20)]
 * @property int $boyoqhona_model_id [smallint(6)]
 * @property HrDepartments $hrDepartment
 * @property HrDepartments $toHrDepartment
 * @property MobileProcess $mobileProcess
 * @property HrDepartments $fromHrDepartment
 * @property int $is_combined [smallint(1)]
 * @property int $mobile_tables_id [int]
 * @property int $is_kit [smallint]
 */
class TikuvSliceItemBalance extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_slice_item_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id','is_kit','is_combined', 'musteri_id','entity_type', 'size_id', 'doc_id', 'doc_type', 'department_id', 'from_department', 'to_department', 'model_id', 'boyoqhona_model_id', 'created_by', 'status', 'updated_at', 'created_at'], 'integer'],
            [['count', 'inventory'], 'number'],
            [['nastel_no'], 'string', 'max' => 20],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['hr_department_id' => 'id']],
            [['from_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['from_hr_department' => 'id']],
            [['to_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['to_hr_department' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['boyoqhona_model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['boyoqhona_model_id' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['mobile_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileProcess::className(), 'targetAttribute' => ['mobile_process_id' => 'id']],
            [['mobile_tables_id'], 'exist', 'skipOnError' => true, 'targetClass' => MobileTables::class, 'targetAttribute' => ['mobile_tables_id' => 'id']],
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
            'size_id' => Yii::t('app', 'Size ID'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'count' => Yii::t('app', 'Count'),
            'inventory' => Yii::t('app', 'Inventory'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'doc_type' => Yii::t('app', 'Doc Type'),
            'department_id' => Yii::t('app', 'Department ID'),
            'from_department' => Yii::t('app', 'From Department'),
            'to_department' => Yii::t('app', 'To Department'),
            'hr_department_id' => Yii::t('app', 'Department'),
            'from_hr_department' => Yii::t('app', 'From department'),
            'to_hr_department' => Yii::t('app', 'To department'),
            'model_id' => Yii::t('app', 'Model ID'),
            'created_by' => Yii::t('app', 'Created By'),
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
    public function getFromDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'from_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(Product::className(), ['id' => 'boyoqhona_model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_id']);
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
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'to_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'from_hr_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToHrDepartment()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'to_hr_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMobileProcess()
    {
        return $this->hasOne(MobileProcess::class, ['id' => 'mobile_process_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMobileTables()
    {
        return $this->hasOne(MobileTables::class, ['id' => 'mobile_tables_id']);
    }

    /**
     * @param $item
     * @return TikuvSliceItemBalance|array|bool|\yii\db\ActiveRecord|null
     */
    public static function getLastRecord($item){
        $result = self::find()->where([
            'size_id' => $item['size_id'],
            'nastel_no' => $item['nastel_party_no'],
            'department_id' => $item['department_id']
        ])->asArray()->one();
        if(!empty($result)){
            return $result;
        }
        return false;
    }

    public static function getLastCombinedRecord($params){
        $last = TikuvSliceItemBalance::find()
            ->where($params)
            ->orderBy(['id' => SORT_DESC])
            ->one();
        if(!empty($last)){
            return $last;
        }
        return false;
    }

    /**
     *
     * @param WmsItemBalance $itemBalance
     * @return bool
     */
    public static function increaseItem(self $itemBalance) {
        $itemInventory = self::getInventory($itemBalance);

        $itemBalance->inventory = (double)$itemBalance->count + (double)$itemInventory;

        return $itemBalance->save();
    }

    public static function decreaseItem(self $itemBalance) {
        $itemInventory = self::getInventory($itemBalance);

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

    public static function getInventory(self $itemBalance) {
        $inventory = static::find()
            ->andWhere([
                'size_id' => $itemBalance['size_id'],
                'nastel_no' => $itemBalance['nastel_no'],
                'hr_department_id' => $itemBalance['hr_department_id'],
                'mobile_process_id' => $itemBalance['mobile_process_id'],
                'mobile_tables_id' => $itemBalance['mobile_tables_id'],
            ])
            ->orderBy(['id' => \SORT_DESC])
            ->asArray()
            ->limit(1)
            ->one();

        return isset($inventory['inventory']) ? $inventory['inventory'] : null;
    }

    public static function searchRemainByNastelNo($nastelNo, $departmentId, $mobileTable) {
        $lastIdsSubQuery = static::find()
            ->select(['MAX(id)'])
            ->groupBy([
                'size_id',
                'nastel_no',
                'hr_department_id',
                'mobile_process_id',
                'mobile_tables_id',
            ]);

        $query = static::find()
            ->alias('tsib')
            ->joinWith('size')
            ->andWhere(['in', 'tsib.id', $lastIdsSubQuery])
            ->andWhere(['tsib.nastel_no' => $nastelNo])
            ->andWhere(['>', 'tsib.inventory', 0])
            ->andWhere([
                'tsib.hr_department_id' => $departmentId,
                'tsib.mobile_process_id' => $mobileTable['mobile_process_id'],
                'tsib.mobile_tables_id' => $mobileTable['id'],
            ])
            ->asArray();

        return $query->all();
    }
}
