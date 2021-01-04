<?php

namespace app\modules\bichuv\models;

use app\modules\admin\models\ToquvUserDepartment;
use app\modules\base\models\ModelsList;
use app\modules\base\models\Musteri;
use app\modules\base\models\Size;
use app\modules\hr\models\HrDepartments;
use app\modules\toquv\models\SortName;
use app\modules\toquv\models\ToquvDepartments;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_service_item_balance".
 *
 * @property int $id
 * @property int $musteri_id
 * @property int $size_id
 * @property int $sort_id
 * @property string $nastel_no
 * @property int $department_id
 * @property int $count
 * @property int $inventory
 * @property int $doc_type
 * @property int $model_id
 * @property int $model_var
 * @property int $doc_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $from_department
 * @property int $to_department
 * @property int $from_musteri
 * @property int $to_musteri
 * @property double $percentage
 * @property int $type [smallint(6)]
 *
 * @property ToquvDepartments $department
 * @property ModelsList $model
 * @property Musteri $musteri
 * @property Size $size
 * @property SortName $sort
 */
class BichuvServiceItemBalance extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_service_item_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['musteri_id', 'size_id', 'sort_id', 'department_id', 'count', 'inventory', 'doc_type', 'model_id', 'model_var', 'doc_id', 'status', 'created_by', 'created_at', 'updated_at', 'from_department', 'to_department', 'from_musteri', 'to_musteri', 'type'], 'integer'],
            [['percentage'], 'number'],
            [['nastel_no'], 'string', 'max' => 50],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
            [['sort_id'], 'exist', 'skipOnError' => true, 'targetClass' => SortName::className(), 'targetAttribute' => ['sort_id' => 'id']],
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
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'size_id' => Yii::t('app', 'Size ID'),
            'sort_id' => Yii::t('app', 'Sort ID'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'department_id' => Yii::t('app', 'Department ID'),
            'count' => Yii::t('app', 'Count'),
            'inventory' => Yii::t('app', 'Inventory'),
            'doc_type' => Yii::t('app', 'Doc Type'),
            'model_id' => Yii::t('app', 'Model ID'),
            'model_var' => Yii::t('app', 'Model Var'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'from_department' => Yii::t('app', 'From Department'),
            'to_department' => Yii::t('app', 'To Department'),
            'from_musteri' => Yii::t('app', 'From Musteri'),
            'to_musteri' => Yii::t('app', 'To Musteri'),
            'percentage' => Yii::t('app', 'Percentage'),
            'type' => Yii::t('app', 'Turi'),
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
     * @return ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'department_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
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
    public function getSort()
    {
        return $this->hasOne(SortName::className(), ['id' => 'sort_id']);
    }

    /**
     * @param $item
     * @return BichuvSliceItemBalance|array|bool|ActiveRecord|null
     */
    public static function getLastRecord($item){
        $userId = Yii::$app->user->id;
        $dept = ToquvUserDepartment::find()->select(['department_id'])->where(['user_id' => $userId])->asArray()->all();
        $deptId = ArrayHelper::getColumn($dept,'department_id');
        $result = self::find()->where([
            'nastel_no' => $item['nastel_party'],
            'musteri_id' => $item['musteri_id'],
            'size_id' => $item['size_id'],
            'department_id' => $deptId
        ])->orderBy(['id' => SORT_DESC])->asArray()->one();

        if(!empty($result)){
            return $result;
        }
        return false;
    }
    /**
     * @param $item
     * @return BichuvSliceItemBalance|array|bool|ActiveRecord|null
     */
    public static function getLastSliceService($item){
        $result = self::find()->where([
            'nastel_no' => $item['nastel_party'],
            'size_id' => $item['size_id'],
            'department_id' => $item['department_id'],
            'model_id' => $item['models_list_id'],
            'model_var' => $item['model_var_id'],
            'type' => $item['type'],
            'sort_id' => $item['sort_name_id'] ?? 1
        ])->orderBy(['id' => SORT_DESC])->asArray()->one();
        if(!empty($result)){
            return $result;
        }
        return false;
    }
    /**
     * @param $item
     * @return BichuvSliceItemBalance|array|bool|ActiveRecord|null
     */
    public static function getLastFromMusteri($item){
        $result = self::find()->where([
            'nastel_no' => $item['nastel_party'],
            'size_id' => $item['size_id'],
            'department_id' => $item['department_id'],
            'from_musteri' => $item['from_musteri'],
            'model_id' => $item['models_list_id'],
            'model_var' => $item['model_var_id'],
            'type' => $item['type'],
            'sort_id' => $item['sort_name_id'] ?? 1
        ])->orderBy(['id' => SORT_DESC])->asArray()->one();
        if(!empty($result)){
            return $result;
        }
        return false;
    }
    /**
     * @param $item
     * @return BichuvSliceItemBalance|array|bool|ActiveRecord|null
     */
    public static function getLastMusteriService($item){
        $result = self::find()->where([
            'nastel_no' => $item['nastel_party'],
            'musteri_id' => $item['musteri_id'],
            'size_id' => $item['size_id'],
            'model_id' => $item['models_list_id'],
            'model_var' => $item['model_var_id'],
        ])->orderBy(['id' => SORT_DESC])->asArray()->one();
        if(!empty($result)){
            return $result;
        }
        return false;
    }
}
