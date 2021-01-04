<?php

namespace app\modules\bichuv\models;

use app\models\Users;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use app\modules\wms\models\Musteri;
use app\modules\wms\models\WmsDepartmentArea;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "spare_item_doc".
 *
 * @property int $id
 * @property int $document_type
 * @property string $doc_number
 * @property string $reg_date
 * @property int $musteri_id
 * @property int $from_department
 * @property int $to_department
 * @property int $from_employee
 * @property int $to_employee
 * @property int $from_area
 * @property int $to_area
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property WmsDepartmentArea $fromArea
 * @property HrDepartments $fromDepartment
 * @property HrEmployee $fromEmployee
 * @property WmsDepartmentArea $toArea
 * @property HrDepartments $toDepartment
 * @property HrEmployee $toEmployee
 * @property SpareItemDocExpence[] $spareItemDocExpences
 * @property SpareItemDocItemBalance[] $spareItemDocItemBalances
 * @property SpareItemDocItems[] $spareItemDocItems
 */
class SpareItemDoc extends BaseModel
{
    const DOC_TYPE_INCOMING = 1;
    const DOC_TYPE_MOVING = 2;
    const DOC_TYPE_SELLING = 3;
    const DOC_TYPE_OUTGOING = 4;


    const DOC_TYPE_INCOMING_LABEL = 'kirim_spare';
    const DOC_TYPE_MOVING_LABEL = 'kochirish_spare';
    const DOC_TYPE_SELLING_LABEL = 'sotish_spare';
    const DOC_TYPE_OUTGOING_LABEL = 'chiqim_spare';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare_item_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_type', 'musteri_id', 'from_department', 'to_department', 'from_employee', 'to_employee', 'from_area', 'to_area', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['reg_date'], 'safe'],
            [['add_info'], 'string'],
            [['doc_number', 'musteri_responsible'], 'string', 'max' => 25],
            [['from_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['from_area' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['from_employee'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['from_employee' => 'id']],
            [['to_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['to_area' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['to_employee'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['to_employee' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_type' => Yii::t('app', 'Document Type'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'from_department' => Yii::t('app', 'From Department'),
            'to_department' => Yii::t('app', 'To Department'),
            'from_employee' => Yii::t('app', 'From Employee'),
            'to_employee' => Yii::t('app', 'To Employee'),
            'from_area' => Yii::t('app', 'From Area'),
            'musteri_responsible' => Yii::t('app', 'Musteri Responsible'),
            'to_area' => Yii::t('app', 'To Area'),
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
    public function getFromEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'from_employee']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getToEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'to_employee']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItemDocExpences()
    {
        return $this->hasMany(SpareItemDocExpence::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItemDocItemBalances()
    {
        return $this->hasMany(SpareItemDocItemBalance::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItemDocItems()
    {
        return $this->hasMany(SpareItemDocItems::className(), ['spare_item_doc_id' => 'id']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getDocTypes($key = null)
    {
        $result = [
            self::DOC_TYPE_INCOMING => Yii::t('app', 'Qabul qilish'),
            self::DOC_TYPE_MOVING => Yii::t('app', "O'tkazish"),
            self::DOC_TYPE_SELLING => Yii::t('app', "Sotish"),
            self::DOC_TYPE_OUTGOING => Yii::t('app', "Chiqim"),
        ];
        if (!empty($key)) {
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getDocTypeBySlug($key = null)
    {
        $result = [
            self::DOC_TYPE_INCOMING_LABEL => Yii::t('app', 'Kirim zapchast'),
            self::DOC_TYPE_MOVING_LABEL => Yii::t('app', "O'tkazish zapchast"),
            self::DOC_TYPE_OUTGOING_LABEL => Yii::t('app', "Chiqim zapchast"),
            self::DOC_TYPE_SELLING_LABEL => Yii::t('app', "Sotish zapchast"),
        ];
        if ($key)
            return $result[$key];
        return $result;
    }

    /**
     * @return array|mixed
     */
    public function getSlugLabel($default_slug=null)
    {
        if($default_slug){
            return self::getDocTypeBySlug($default_slug);
        }
        $slug = Yii::$app->request->get('slug');
        if (!empty($slug)) {
            return self::getDocTypeBySlug($slug);
        }
    }

    public function getMusteri()
    {
        return $this->hasOne(Musteri::class,['id' => 'musteri_id']);
    }

    /**
     * @param null $token
     * @param null $musteri_type
     * @return array|null
     */
    public function getMusteries($token = null, $musteri_type = null)
    {
        if ($token) {
            $result = BichuvMusteri::find()->select(['id', 'name'])->where([
                'status' => self::STATUS_ACTIVE,
                'token' => $token
            ])->asArray()->one();
            return [$result['id'] => $result['name']];
        } else {
            $query = BichuvMusteri::find();
            if (!empty($musteri_type)) {
                $id = Constants::$NillGranitID;
                $query->andFilterWhere(['OR', ['musteri_type_id' => $musteri_type], ['id' => $id]]);
            }
            $query->andFilterWhere(['status' => self::STATUS_ACTIVE])->select(['id', 'name']);
            $results = $query->asArray()->orderBy(['name' => SORT_ASC])->all();
            if (!empty($results)) {
                return ArrayHelper::map($results, 'id', 'name');
            }
        }
        return null;
    }

    /**
     * @param bool $isAll
     * @return array|null
     */
    public function getEmployees($isAll = false)
    {

        if ($isAll) {
            $user = Users::find()->select(['id', 'user_fio'])->asArray()->all();
        } else {
            $user = Users::find()->select(['id', 'user_fio'])->where(['id' => Yii::$app->user->id])->asArray()->all();
        }
        if (!empty($user)) {
            return ArrayHelper::map($user, 'id', 'user_fio');
        }
        return null;
    }

    public function getHrEmployee()
    {
        $result = HrEmployee::find()->asArray()->all();
        return ArrayHelper::map($result,'id', 'fish');
    }

    /**
     * @param null $id
     * @param bool $all
     * @param bool $keyVal
     * @return array|string|null
     * @throws Exception
     */
    public static function getSpare($id = null, $all = false, $keyVal = false)
    {
        if (!empty($id)) {
            $sql = "select accs.sku, accs.name, bap.name as property 
                    from bichuv_acs accs left join bichuv_acs_property bap on accs.property_id = bap.id 
                    where accs.id = :id limit 1";
            $accs = Yii::$app->db->createCommand($sql)->bindValues(['id' => $id])->queryOne();
            if ($accs) {
                return $accs['name'] . ' - ' . $accs['property'];
            }
        }
        else {
            $inactive = SpareItemDoc::STATUS_ACTIVE;
            if ($all) {
                $sql = "SELECT
                        spare_item.id, spare_item.barcode, spare_item.sku, spare_item.name,
                        spare_item_property.value
                        FROM spare_item
                        LEFT JOIN spare_item_property
                        ON spare_item.id = spare_item_property.spare_item_id 
                        WHERE spare_item.status = '{$inactive}'";
            } else {
                $sql = "select accs.id, accs.sku, accs.name, bap.name as property from bichuv_acs accs 
                    left join bichuv_acs_property bap on accs.property_id = bap.id
                    left join bichuv_item_balance bib on bib.entity_id = accs.id
                    where accs.status = {$inactive} AND bib.inventory > 0 AND bib.id IN (select MAX(bib2.id) from bichuv_item_balance bib2 
                    where bib2.entity_id = accs.id) limit 1000";
            }
            $accs = Yii::$app->db->createCommand($sql)->queryAll();

            if (!empty($accs)) {
                $str = '';
                $result = [];
                if ($keyVal) {
                    return ArrayHelper::map($accs, 'id', function ($m) {
                        return $m['name'] . ' ' . $m['value'];
                    });
                } else {
                    foreach ($accs as $ip) {
                        if(isset($result['data'][$ip['id']])){
                            $str .= ' ' . $ip['value'];
                        }
                        else{
                            $str = '';
                            $str .= $ip['name'] . ' ' . $ip['value'];
                        }
                        $result['data'][$ip['id']] = $str;
                        $result['barcodeAttr'][$ip['id']] = ['data-barcode' => $ip['barcode']];
                    }
                }
                return $result;
            }
            return null;
        }
    }
    
    /** 
     * @param $save
     * */
    public function getAllSave($data, $id)
    {
        if(!empty($data)){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                foreach ($data['SpareItemDocItems'] as $datum) {
                    $spareDocItems = new SpareItemDocItems();
                    $spareDocItems->spare_item_doc_id = $id;
                    $spareDocItems->entity_id = $datum['entity_id'];
                    $spareDocItems->quantity = $datum['quantity'];
                    $spareDocItems->price_sum = $datum['price_sum'] ?? 0;
                    $spareDocItems->price_usd = $datum['price_usd'] ?? 0;
                    $spareDocItems->from_area = $datum['to_area'];
                    $spareDocItems->status = SpareItemDoc::STATUS_ACTIVE;
                    if($spareDocItems->save()){
                        $saved = true;
                        unset($spareDocItems);
                    }
                    else{
                        $saved = false;
                    }
                }
                if($saved)
                {
                    $transaction->commit();
                    return true;
                }
                else{
                    $transaction->rollBack();
                    return false;
                }
            }
            catch(\Exception $e){
                Yii::info('error message '.$e->getMessage(), 'save');
            }
        }   
        else{
            return false;
        }
    }

    /**
     * @param null $id
     * @param bool $all
     * @param bool $keyVal
     * @return array|string|null
     * @throws Exception
     */
    public static function getSpareProperties($id = null)
    {
        if(!empty($id)){
            $model = SpareItem::find()
                ->alias('si')
                ->joinWith('spareItemProperties')
                ->all();
            return $model;
        }
    }
}
