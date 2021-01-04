<?php

namespace app\modules\bichuv\models;

use app\modules\admin\models\ToquvUserDepartment;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\base\models\ModelOrdersItems;
use app\modules\hr\models\HrDepartments;
use app\modules\toquv\models\ToquvDepartments;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_rm_item_balance".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $doc_type
 * @property string $inventory
 * @property string $count
 * @property string $roll_inventory
 * @property string $roll_count
 * @property int $from_department
 * @property int $to_department
 * @property int $is_inside
 * @property int $from_musteri
 * @property int $to_musteri
 * @property int $type
 * @property string $party_no
 * @property string $musteri_party_no
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvDepartments $fromDepartment
 * @property ToquvDepartments $department
 * @property Musteri $fromMusteri
 * @property BichuvDoc $doc
 * @property BichuvGivenRolls $bichuvGivenRoll
 * @property ToquvDepartments $toDepartment
 * @property Musteri $toMusteri
 * @property Product $productModel
 * @property int $model_id [smallint(6)]
 * @property int $doc_id [int(11)]
 * @property int $department_id [int(11)]
 * @property string $nastel_no [varchar(20)]
 * @property int $bichuv_given_roll_id [int(11)]
 */
class BichuvRmItemBalance extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_rm_item_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id','department_id','bichuv_given_roll_id','model_id','doc_id','doc_type', 'from_department', 'to_department', 'is_inside', 'from_musteri', 'to_musteri', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['inventory', 'count', 'roll_inventory', 'roll_count'], 'number'],
            [['party_no', 'musteri_party_no','nastel_no'], 'string', 'max' => 50],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['from_musteri'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvMusteri::className(), 'targetAttribute' => ['from_musteri' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['to_musteri'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvMusteri::className(), 'targetAttribute' => ['to_musteri' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::className(), 'targetAttribute' => ['doc_id' => 'id']],
            [['bichuv_given_roll_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRolls::className(), 'targetAttribute' => ['bichuv_given_roll_id' => 'id']],
            [['from_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['from_hr_department' => 'id']],
            [['to_hr_department'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['to_hr_department' => 'id']],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_department_id' => 'id']],
            [['model_orders_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersItems::className(), 'targetAttribute' => ['model_orders_items_id' => 'id']],
            [['bichuv_nastel_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvNastelLists::className(), 'targetAttribute' => ['bichuv_nastel_list_id' => 'id']],
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
            'doc_type' => Yii::t('app', 'Doc Type'),
            'inventory' => Yii::t('app', 'Inventory'),
            'count' => Yii::t('app', 'Count'),
            'roll_inventory' => Yii::t('app', 'Roll Inventory'),
            'roll_count' => Yii::t('app', 'Roll Count'),
            'from_department' => Yii::t('app', 'From Department'),
            'to_department' => Yii::t('app', 'To Department'),
            'is_inside' => Yii::t('app', 'Is Inside'),
            'from_musteri' => Yii::t('app', 'From Musteri'),
            'to_musteri' => Yii::t('app', 'To Musteri'),
            'model_id' => Yii::t('app', 'Model ID'),
            'type' => Yii::t('app', 'Type'),
            'party_no' => Yii::t('app', 'Party No'),
            'musteri_party_no' => Yii::t('app', 'Musteri Party No'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
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
    public function getFromDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'from_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromMusteri()
    {
        return $this->hasOne(BichuvMusteri::className(), ['id' => 'from_musteri']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoc()
    {
        return $this->hasOne(BichuvDoc::className(), ['id' => 'doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRoll()
    {
        return $this->hasOne(BichuvGivenRolls::className(), ['id' => 'bichuv_given_roll_id']);
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
    public function getDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToMusteri()
    {
        return $this->hasOne(BichuvMusteri::className(), ['id' => 'to_musteri']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductModel()
    {
        return $this->hasOne(Product::className(), ['id' => 'model_id']);
    }

    /**
     * @param $item
     * @param $musteriId
     * @return BichuvRmItemBalance|array|bool|\yii\db\ActiveRecord|null
     */
    public function checkExistsParty($item, $musteriId)
    {
        $model = BichuvRmItemBalance::find()->where([
            'from_musteri' => $musteriId,
            'party_no' => $item['party_no'],
            'musteri_party_no' => $item['musteri_party_no']
        ])->asArray()->one();
        if(!empty($model)){
            return $model;
        }
        return false;
    }

    /***
     * @param $item
     * @param $musteri
     * @return BichuvRmItemBalance|array|bool|\yii\db\ActiveRecord|null
     */
    static function getLastRecord($item, $musteri){
        $userId = Yii::$app->user->id;
        $dept = UsersHrDepartments::find()->select(['hr_departments_id'])->where(['user_id' => $userId])->asArray()->all();
        $deptId = ArrayHelper::getColumn($dept,'hr_departments_id');

        $res = BichuvRmItemBalance::find()
            ->andFilterWhere([
                'entity_id' => $item['entity_id'],
                'party_no' => $item['party_no'],
                'musteri_party_no' => $item['musteri_party_no'],
//                'from_musteri' => $musteri,
                'hr_department_id' => $deptId
                ])
            ->asArray()->orderBy(['id'=> SORT_DESC])->one();
        if(!empty($res)){
            return $res;
        }
        return false;
    }
    //TODO item balancega from musteri kelmayapti ya'ni kelmayapti tekshirish kerak i
    /***
     * @param $item
     * @param $musteri
     * @return BichuvRmItemBalance|array|bool|\yii\db\ActiveRecord|null
     */
    static function getLastRecordMato($item, $musteri,$model = null){
        if(!empty($model)){
            $res = BichuvRmItemBalance::find()
                ->andFilterWhere([
                    'entity_id' => $item['entity_id'],
                    'party_no' => $item['party_no'],
                    'musteri_party_no' => $item['musteri_party_no'],
                    'from_musteri' => $musteri,
                    'hr_department_id' => $item['to_hr_department']
                ])
                ->asArray()->orderBy(['id'=> SORT_DESC])->one();
        }else{
            $res = BichuvRmItemBalance::find()
                ->andFilterWhere([
                    'entity_id' => $item['entity_id'],
                    'party_no' => $item['party_no'],
                    'musteri_party_no' => $item['musteri_party_no'],
                    'from_musteri' => $musteri,
                    'department_id' => $item['department_id']
                ])
                ->asArray()->orderBy(['id'=> SORT_DESC])->one();
        }
       
        if(!empty($res)){
            return $res;
        }
        return false;
    }

    static  function getNewLastRecord($dataForLastRecord){

        $lastRecord = BichuvRmItemBalance::find()
            ->andFilterWhere([
                'entity_id' => $dataForLastRecord['entity_id'],
                'party_no' => $dataForLastRecord['party_no'],
                'musteri_party_no' => $dataForLastRecord['musteri_party_no'],
                'from_musteri' => $dataForLastRecord['from_musteri'],
                'hr_department_id' => $dataForLastRecord['hr_department_id']
            ])
            ->asArray()->orderBy(['id'=> SORT_DESC])->one();
        if(!empty($lastRecord)){
            return $lastRecord;
        }

        return false;
    }

    /***
     * @param $modelBIB
     * @param $model
     * @param $item
     * @return mixed
     * Mato kirim bo'lganda mato item balance muntazam oshirib boradi
     */
    public function increaseItemBalance($modelBIB,$model,$item){
        $dataForLastRecord = [];
        $dataForLastRecord['entity_id'] = $item['entity_id'];
        $dataForLastRecord['party_no'] = $item['party_no'];
        $dataForLastRecord['musteri_party_no'] = $item['musteri_party_no'];
//        $dataForLastRecord['from_musteri'] = $item['musteri_id'] ?? $model['musteri_id'];
        $dataForLastRecord['hr_department_id'] = $model['to_hr_department'];
        $checkExists = self::getNewLastRecord($dataForLastRecord);

        $inventory = $item['fact_quantity'];
        $roll_inventory = $item['roll_count'];
        if (!empty($checkExists)) {
            $inventory += $checkExists['inventory'];
            $roll_inventory += $checkExists['roll_inventory'];
        }

        $modelBIB->setAttributes([
            'entity_id' => $item['entity_id'],
            'doc_type' => 1,
            'inventory' => $inventory,
            'count' => $item['fact_quantity'],
            'roll_inventory' => $roll_inventory,
            'roll_count' => $item['roll_count'],
            'party_no' => $item['party_no'],
            'doc_id' => $model->id,
            'musteri_party_no' => $item['musteri_party_no'],
            'from_hr_department' => $model->from_hr_department,
            'to_hr_department' => $model->to_hr_department,
            'hr_department_id' => $model->to_hr_department,
//            'from_musteri' => $dataForLastRecord['from_musteri'],
            'model_orders_items_id' => $item['model_orders_items_id'],
            'bichuv_nastel_list_id' => $model['bichuv_nastel_list_id']
        ]);

        return $modelBIB->save();
    }

    static function getRmInfo($btrwd){
        $exlodeArray = explode(',',$btrwd['musteri_party_no']);
        $musteri_party_no = "('".implode("','",$exlodeArray)."')";
        $fromMusteri = "";

        $sql = "select wmi.id as entity_id,
                       m.name,
                       rmt.name as mato,
                       tn.name as ne,
                       tt.name as thread,
                       tpf.name as pus_fine,
                       c.color_id,
                       ct.name as ctone,
                       c.pantone,
                       p.name as model,
                       wmi.en,
                       wmi.gramaj,
                       brib.roll_inventory as rulon_count,
                       brib.inventory as rulon_kg,
                       brib.party_no,
                       brib.musteri_party_no,
                       brib.model_orders_items_id as moii,
                       p.id as model_id
                from bichuv_rm_item_balance brib
                         left join wms_mato_info wmi on brib.entity_id = wmi.id
                         left join toquv_raw_materials trm on wmi.toquv_raw_materials_id = trm.id
                         left join raw_material_type rmt on trm.raw_material_type_id = rmt.id
                         left join toquv_raw_material_ip trmi on trm.id = trmi.toquv_raw_material_id
                         left join toquv_ne tn on trmi.ne_id = tn.id
                         left join toquv_thread tt on trmi.thread_id = tt.id
                         left join toquv_pus_fine tpf on wmi.pus_fine_id = tpf.id
                         left join color c on wmi.wms_color_id = c.id
                         left join color_tone ct on c.color_tone = ct.id
                         left join musteri m on brib.from_musteri = m.id
                         left join product p on brib.model_id = p.id
                WHERE brib.inventory > 0 AND brib.id IN (
                    select MAX(brib2.id) from bichuv_rm_item_balance brib2
                    where brib2.bichuv_nastel_list_id = {$btrwd['nastel_no_id']}
                         AND brib2.musteri_party_no IN {$musteri_party_no} 
                         GROUP BY brib2.entity_id)
                GROUP BY brib.entity_id ORDER BY trm.name DESC ;";
        $sql = sprintf($sql);

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

}
