<?php

namespace app\modules\base\models;

use app\components\OurCustomBehavior;
use app\models\Notifications;
use app\models\SizeType;
use app\models\UploadForm;
use app\models\Users;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\hr\models\HrEmployee;
use app\modules\tikuv\models\TikuvGoodsDocAccepted;
use app\modules\tikuv\models\TikuvGoodsDocMoving;
use app\modules\tikuv\models\TikuvGoodsDocPack;
use app\modules\toquv\models\Musteri;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\ToquvRmOrder;
use app\modules\toquv\models\ToquvRmOrderItems;
use app\modules\toquv\models\ToquvRmOrderMoi;
use app\modules\wms\models\WmsMatoInfo;
use app\widgets\helpers\Telegram;
use http\Url;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "model_orders".
 *
 * @property int $id
 * @property string $doc_number
 * @property int $musteri_id
 * @property string $reg_date
 * @property string $add_info
 * @property int $status
 * @property double $prepayment
 * @property string $sum_item_qty
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $planning_id
 * @property string $planning_date
 * @property int $updated_by
 * @property int $confirm_supply
 *
 * @property Musteri $musteri
 * @property ModelOrdersItems[] $modelOrdersItems
 * @property ModelOrdersResponsible[] $modelOrdersResponsibles
 * @property ToquvOrders[] $toquvOrders
 * @property MoiRelDept[] $moiRelDepts
 * @property TikuvGoodsDocAccepted[] $tikuvGoodsDocAccepteds
 * @property TikuvGoodsDocMoving[] $tikuvGoodsDocMovings
 * @property TikuvGoodsDocPack[] $tikuvGoodsDocPacks
 * @property string $info
 * @property mixed $musteriList
 * @property ActiveQuery $modelOrdersPlanning
 * @property mixed $printsList
 * @property bool|string $responsibleList
 * @property mixed $sizeList
 * @property ActiveQuery $author
 * @property ActiveQuery $updatedBy
 * @property mixed $usersList
 * @property mixed $stoneList
 * @property null $modelArticles
 * @property mixed responsibleMap
 * @property string $token [varchar(50)]
 * @property-read mixed $planningAks
 * @property-read ActiveQuery $modelOrdersItemsVariations
 * @property-read mixed $sizeArrayList
 * @property-read array $bichuvAcsList
 * @property-read array $orderInfo
 * @property-read ActiveQuery $modelOrdersItemsToquvAcs
 * @property-read mixed $planningMato
 * @property-read ActiveQuery $modelOrdersVariations
 * @property-read array $colorPantoneList
 * @property-read ActiveQuery $modelOrdersItemsMato
 * @property-read ActiveQuery $modelOrdersItemsPechat
 * @property-read ActiveQuery $modelOrdersItemsSize
 * @property-read array $colorBoyoqList
 * @property-read ActiveQuery $modelOrdersItemsAcs
 * @property int $responsible [int]
 */
class ModelOrders extends BaseModel
{
    public $newIs;

    const GENERAL_ORDER_TOKEN = 'GENERAL_MODEL';
    const STATUS_NOACTIVE   = 7;
    const STATUS_ACTIVE             = 1;
    const STATUS_INACTIVE           = 2;
    const STATUS_SAVED              = 3;
    const STATUS_PLANNED            = 4;
    const STATUS_PLANNED_TOQUV      = 5;
    const STATUS_PLANNED_TOQUV_AKS  = 6;
    const STATUS_CHANGED_MATO       = 77;
    const STATUS_CHANGED_AKS        = 88;
    const STATUS_COMBINED           = 99;
    public $responsible = [];

    /** typelar uchun */
    const MODELS_IMG = 1;
    const MODELS_MATO = 2;
    const MODELS_ACS = 3;
    const MODELS_TOQUV_ACS = 4;
    const MODELS_BASE_PATTERNS = 5;
    const MODELS_NAQSH = 6;
    const MODELS_PECHAT = 7;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['doc_number', 'unique'],
            [['musteri_id'], 'required'],
            [['musteri_id', 'planning_id', 'status', 'orders_status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'confirm_supply'], 'integer'],
            [['prepayment', 'sum_item_qty'], 'number'],
            [['reg_date', 'planning_date'], 'safe'],
            [['add_info'], 'string'],
            [['doc_number'], 'string', 'max' => 50],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'sum_item_qty' => Yii::t('app', 'Sum Model Items Quantity'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'prepayment' => Yii::t('app', "Oldindan to'langan miqdor foizi, %"),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', "O'zgartirdi"),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'responsible' => Yii::t('app', 'Responsible Persons'),
            'planning_id' => Yii::t('app', 'Planning ID'),
            'planning_date' => Yii::t('app', 'Planning Date'),
            'confirm_supply' => Yii::t('app', "Confirm by supply"),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(empty($this->doc_number)){
                $currentDate = date('m/d/Y');
                if($this->isNewRecord){
                    $docNumberExist = ModelOrders::find()->select('id')->orderBy(['id'=>SORT_DESC])->asArray()->one();
                    $lastId = $docNumberExist?$docNumberExist['id']+1:1;
                    $this->doc_number = "MO-{$lastId}/{$currentDate}";
                }
                else{
                    $this->doc_number = "MO-{$this->id}/{$currentDate}";
                }
            }
            $date = date('Y-m-d');
            if (!empty($this->reg_date)) {
                $date = date('Y-m-d', strtotime($this->reg_date));
            }
            $currentTime = date('H:i:s');
            $this->reg_date = date('Y-m-d H:i:s', strtotime($date . ' ' . $currentTime));
            return true;
        } else {
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = date('d.m.Y H:i', strtotime($this->reg_date));
        if($this->sum_item_qty){
            $this->sum_item_qty = number_format($this->sum_item_qty,0,'.','');
        }
        $this->responsible = ModelOrdersResponsible::find()
            ->select('users_id')
            ->where(['model_orders_id' => $this->id])
            ->asArray()
            ->column();
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
    public function getToquvOrders()
    {
        return $this->hasMany(ToquvOrders::className(), ['model_orders_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasMany(ModelOrdersItems::className(), ['model_orders_id' => 'id'])->orderBy(['models_list_id' =>SORT_ASC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsSize()
    {
        return $this->hasMany(ModelOrdersItemsSize::className(), ['model_orders_id' => 'id'])->groupBy(['size_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsVariations()
    {
        return $this->hasMany(ModelOrdersItemsVariations::className(), ['model_orders_id' => 'id'])->groupBy(['color_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsMato()
    {
        return $this->hasMany(ModelOrdersItemsMaterial::className(), ['model_orders_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsPechat()
    {
        return $this->hasMany(ModelOrdersItemsPechat::className(), ['model_orders_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersPlanning()
    {
        return $this->hasMany(ModelOrdersPlanning::className(), ['model_orders_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersResponsibles()
    {
        return $this->hasMany(ModelOrdersResponsible::className(), ['model_orders_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getMoiRelDepts()
    {
        return $this->hasMany(MoiRelDept::className(), ['model_orders_id' => 'id']);
    }

    public function getChildDepts($type=MoiRelDept::TYPE_MATO)
    {
        return MoiRelDept::find()->where(['model_orders_id'=>$this->id,'type'=>$type])->all();
    }
    /**
     * @return ActiveQuery
     */
    public function getTikuvGoodsDocAccepteds()
    {
        return $this->hasMany(TikuvGoodsDocAccepted::className(), ['order_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvGoodsDocMovings()
    {
        return $this->hasMany(TikuvGoodsDocMoving::className(), ['order_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTikuvGoodsDocPacks()
    {
        return $this->hasMany(TikuvGoodsDocPack::className(), ['order_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsToquvAcs()
    {
        return $this->hasMany(ModelOrdersItemsToquvAcs::className(), ['model_orders_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsAcs()
    {
        return $this->hasMany(ModelOrdersItemsAcs::className(), ['models_orders_id' => 'id']);
    }

    public function getInfo()
    {
        return $this->doc_number . "( {$this->musteri->name} )";
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }
    /**
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'updated_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrdersVariations()
    {
        return $this->hasMany(ModelOrdersVariations::class, ['model_orders_id' => 'id']);
    }

    public function getMusteriList()
    {
        $musteri = Musteri::find()->select(['id', 'name'])->where(['musteri_type_id' => 1, 'status' => BaseModel::STATUS_ACTIVE])->asArray()->all();
        return ArrayHelper::map($musteri, 'id', 'name');
    }
    public static function getOrdersMusteriList($status=null,$operator='=')
    {
        $musteri = self::find()->joinWith('musteri')->select(['musteri.id', 'musteri.name'])->where(['musteri.musteri_type_id' => 1, 'musteri.status' => BaseModel::STATUS_ACTIVE]);
        if($status){
            $musteri = $musteri->andWhere([$operator,'model_orders.status',$status]);
        }
        $musteri = $musteri->asArray()->all();
        return ArrayHelper::map($musteri, 'id', 'name');
    }
    public function getUsersList()
    {
        $users = HrEmployee::find()->select(['id', 'fish'])->asArray()->all();
        return ArrayHelper::map($users, 'id', 'fish');
    }

    public function getSizeList()
    {
        $row = SizeType::find()->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::map($row, 'id', 'name');
    }
    public static function getSizeCollectionList()
    {
        $row = SizeCollections::find()->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::map($row, 'id', 'name');
    }
    public static function getBaskiList()
    {
        $row = ModelVarBaski::find()->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::map($row, 'id', function ($model) {
            return $model['name'] . " " . $model['code'];
        });
    }

    public function getPrintsList()
    {
        $row = ModelVarPrints::find()->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::map($row, 'id', function ($model) {
            return $model['name'] . " " . $model['code'];
        });
    }

    public function getStoneList()
    {
        $row = ModelVarStone::find()->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::map($row, 'id', function ($model) {
            return $model['name'] . " " . $model['code'];
        });
    }

    public static function getDeptList()
    {
        $row = ToquvDepartments::find()->where(['type' => ToquvDepartments::PRODUCTION])->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::map($row, 'id', 'name');
    }

    public static function getAuthorList()
    {
        $sql = "select u.id,user_fio from users u
                left join model_orders ml on u.id = ml.created_by
                WHERE ml.id is not null
                GROUP BY u.id
        ";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($list,'id','user_fio');
    }

    public static function getUpdatedByList()
    {
        $sql = "select u.id,user_fio from users u
                left join model_orders ml on u.id = ml.updated_by
                WHERE ml.id is not null
                GROUP BY u.id
        ";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($list,'id','user_fio');
    }

    public static function getModelList($q = null)
    {
        $status = ModelsList::STATUS_SAVED;
        $sql = "SELECT m.id as id, m.name as mname, m.article as mart, atch.path, view.name as vname, type.name as tname,
                mra.is_main, m.baski,m.baski_rotatsion as rotatsion,m.prints,m.stone, m.brend_id
                FROM models_list as m
                LEFT JOIN model_rel_attach as mra ON mra.model_list_id = m.id
                LEFT JOIN attachments as atch ON atch.id = mra.attachment_id
                LEFT JOIN model_view as view ON m.view_id = view.id
                LEFT JOIN model_types as type ON m.type_id = type.id
                WHERE m.status = {$status} 
        ";
        $acs = "SELECT m.id as id, ba.id acs_id, ba.name name, ba.sku artikul, 
                ba.barcode barcod, ba.add_info add_info, 
                bap.name turi, u.name unit,u.id unit_id, ma.qty qty
                FROM models_list as m
                LEFT JOIN models_acs ma on m.id = ma.model_list_id
                LEFT JOIN bichuv_acs ba on ma.bichuv_acs_id = ba.id
                LEFT JOIN unit u on ba.unit_id = u.id
                LEFT JOIN bichuv_acs_property bap on ba.property_id = bap.id
                WHERE m.status = {$status}
        ";
        $toquvAcs = "
                SELECT m.id as id, mta.toquv_acs_id, trm.name as tname, trm.code, rmt.name
                FROM
                    models_list as m
                LEFT JOIN
                    models_toquv_acs as mta
                ON
                    m.id = mta.models_list_id
                LEFT JOIN
                    toquv_raw_materials as trm
                ON
                    mta.toquv_acs_id = trm.id
                LEFT JOIN
                    raw_material_type as rmt
                ON
                    trm.raw_material_type_id = rmt.id
                WHERE m.status = {$status}    
        ";
        if ($q) {
            $result = [];
            $acs .= " AND (m.name LIKE '%{$q}%' OR m.article LIKE '%{$q}%') ";
            $toquvAcs .= " AND (m.name LIKE '%{$q}%' OR m.article LIKE '%{$q}%') ";
            $sql .= " AND (m.name LIKE '%{$q}%' OR m.article LIKE '%{$q}%')";
            $sql .= " ORDER BY mra.is_main DESC";
            $result['list'] = Yii::$app->db->createCommand($sql)->queryAll();
            $acsList = Yii::$app->db->createCommand($acs)->queryAll();
            $toquvAcsList = Yii::$app->db->createCommand($toquvAcs)->queryAll();

            foreach ($toquvAcsList as $key => $item) {
                $result['toquvAcs'][$item['id']][$item['toquv_acs_id']] = [
                    'id' => $item['toquv_acs_id'],
                    'name' => $item['tname'],
                    'artikul' => $item['code'],
                    'turi' => $item['name'],
                ];
            };

            foreach ($acsList as $key => $item) {
                $result['acs'][$item['id']][$item['acs_id']] = [
                    'id' => $item['acs_id'],
                    'name' => $item['name'],
                    'artikul' => $item['artikul'],
                    'barcod' => $item['barcod'],
                    'add_info' => $item['add_info'],
                    'turi' => $item['turi'],
                    'unit' => $item['unit'],
                    'unit_id' => $item['unit_id'],
                    'qty' => $item['qty'],
                ];
            };
            return $result;
        }
        $sql .= " ORDER BY mra.is_main DESC";
        $row = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        foreach ($row as $item) {
            $image = (!empty($item['path']))?"<img src='/web/" . $item['path'] . "' style='width:30px;height:30px;border:1px solid'> ":'';
            $res[$item['id']] = [
                'id' => $item['id'],
                'name' => $image."<b> " . $item['mname'] . " </b> - " . $item['mart'] . " - " . $item['tname'],
                'group' => $item['vname']
            ];
        }
        $result = ArrayHelper::map($res, 'id', 'name', 'group');
        return $result;
    }

    public function getResponsibleMap()
    {
        $data = $this->modelOrdersResponsibles;
        $users = [];
        foreach ($data as $key) {
            array_push($users, $key->users_id);
        }
        return $users;
    }

    public function getResponsibleList()
    {
        $data = $this->modelOrdersResponsibles;
        if (!empty($data)) {
            $responsible = "";
            foreach ($data as $key) {
                if ($key->users) {
                    $responsible .= ($data[0]['id'] == $key['id']) ? $key->users['user_fio'] : ', ' . $key->users['user_fio'];
                }
            }
            return $responsible;
        }
        return false;
    }

    public function getPlanningMato()
    {
        return ModelOrdersPlanning::find()->where(['model_orders_items_id'=>$this->id,'type'=>ToquvRawMaterials::MATO])->orderBy(['id' =>SORT_ASC])->all();
    }
    public function getPlanningAks()
    {
        return ModelOrdersPlanning::find()->where(['model_orders_items_id'=>$this->id,'type'=>ToquvRawMaterials::ACS])->orderBy(['id'=> SORT_ASC])->all();
    }

    public function saveResponsible($data)
    {
        $saved = false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            ModelOrdersResponsible::deleteAll('model_orders_id = ' . $this->id);
            if(!empty($data)) {
                foreach ($data as $key) {
                    if (!empty($key)) {
                        $responsible = new ModelOrdersResponsible();
                        $responsible->setAttributes([
                            'model_orders_id' => $this->id,
                            'users_id' => $key,
                        ]);
                        if ($responsible->save()) {
                            $saved = true;
                        } else {
                            $saved = false;
                            break;
                        };
                    }
                }
            }else{
                $saved = true;
            }
            if ($saved) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return false;
            }
        } catch (Exception $e) {
            Yii::info('Not saved model orders' . $e, 'save');
        }
    }

    public function saveOrders($data)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $saved = false;
            ModelOrdersItems::deleteAll('model_orders_id = ' . $this->id);
            if(!empty($data)) {
                foreach ($data as $key) {
                    if (!empty($key['models_list_id']) && !empty($key['model_var_id'])) {
                        $item = new ModelOrdersItems();
                        $item->setAttributes([
                            'model_orders_id' => $this->id,
                            'models_list_id' => $key['models_list_id'],
                            'model_var_id' => $key['model_var_id'],
                            'add_info' => $key['add_info'],
                            'percentage' => $key['percentage'],
                            'load_date' => ($key['load_date']) ? $key['load_date'] : null,
                            'priority' => $key['priority'],
                            'season' => $key['season'],
                            'baski_id' => $key['baski_id'],
                            'prints_id' => $key['prints_id'],
                            'stone_id' => $key['stone_id'],
                            'price' => $key['price'],
                            'pb_id' => $key['pb_id'],
                            'prepayment' => $key['prepayment'],
                            'brend_id' => $key['brend_id'],
                        ]);
                        if ($item->save()) {
                            $saved = true;
                            if (!empty($key['size'])) {
                                foreach ($key['size'] as $m => $val) {
                                    /*if (!empty($val) && $val != 0) {*/
                                    $item_size = new ModelOrdersItemsSize();
                                    $item_size->setAttributes([
                                        'model_orders_items_id' => $item['id'],
                                        'count' => $val,
                                        'size_id' => $m,
                                    ]);
                                    if ($item_size->save()) {
                                        $saved = true;
                                    } else {
                                        if ($item_size->hasErrors()) {
                                            $res = [
                                                'status' => 'error',
                                                'data' => $key['size'],
                                                'message' => $item_size->getErrors(),
                                            ];
                                            Yii::info($res, 'save');
                                        }
                                        $saved = false;
                                        break 2;
                                    };
                                    /* }*/
                                }
                            }
                            if (!empty($key['depts_id'])) {
                                foreach ($key['depts_id'] as $m => $val) {
                                    if (!empty($val) && $val != 0) {
                                        $item_depts = new MoiRelDept();
                                        $item_depts->setAttributes([
                                            'model_orders_items_id' => $item['id'],
                                            'toquv_departments_id' => $val
                                        ]);
                                        if ($item_depts->save()) {
                                            $saved = true;
                                        } else {
                                            if ($item_depts->hasErrors()) {
                                                $res = [
                                                    'status' => 'error',
                                                    'message' => $item_depts->getErrors(),
                                                ];
                                                Yii::info($res, 'save');
                                            }
                                            $saved = false;
                                            break 2;
                                        };
                                    }
                                }
                            }
                            if (!empty($key['print'])) {
                                foreach ($key['print'] as $print) {
                                    if (!empty($print) && $print != 0) {
                                        $item_print = new ModelOrderItemsPrints([
                                            'model_orders_items_id' => $item['id'],
                                            'model_var_prints_id' => $print['id'],
                                        ]);
                                        if ($item_print->save()) {
                                            $saved = true;
                                        } else {
                                            if ($item_print->hasErrors()) {
                                                $res = [
                                                    'status' => 'error',
                                                    'data' => $key['print'],
                                                    'message' => $item_print->getErrors(),
                                                ];
                                                Yii::info($res, 'save');
                                            }
                                            $saved = false;
                                            break 2;
                                        };
                                    }
                                }
                            }
                            if (!empty($key['stone'])) {
                                foreach ($key['stone'] as $stone) {
                                    if (!empty($stone) && $stone != 0) {
                                        $item_stone = new ModelOrderItemsStone([
                                            'model_orders_items_id' => $item['id'],
                                            'model_var_stone_id' => $stone['id'],
                                        ]);
                                        if ($item_stone->save()) {
                                            $saved = true;
                                        } else {
                                            if ($item_stone->hasErrors()) {
                                                $res = [
                                                    'status' => 'error',
                                                    'data' => $key['stone'],
                                                    'message' => $item_stone->getErrors(),
                                                ];
                                                Yii::info($res, 'save');
                                            }
                                            $saved = false;
                                            break 2;
                                        };
                                    }
                                }
                            }
                            if (!empty($key['acs'])) {
                                foreach ($key['acs'] as $acs) {
                                    if (!empty($acs) && $acs != 0) {
                                        $item_acs = new ModelOrdersItemsAcs([
                                            'models_orders_id' => $this->id,
                                            'model_orders_items_id' => $item['id'],
                                            'bichuv_acs_id' => (integer)$acs['id'],
                                            'qty' => ($acs['qty']!=null)?$acs['qty']:0,
                                            'unit_id' => $acs['unit_id'],
                                            'add_info' => $acs['add_info'],
                                        ]);
                                        if ($item_acs->save()) {
                                            $saved = true;
                                        } else {
                                            if ($item_acs->hasErrors()) {
                                                $res = [
                                                    'status' => 'error',
                                                    'data' => $key['acs'],
                                                    'message' => $item_acs->getErrors(),
                                                ];
                                                Yii::info($res, 'save');
                                            }
                                            $saved = false;
                                            break 2;
                                        };
                                    }
                                }
                            }
                        } else {
                            if ($item->hasErrors()) {
                                $res = [
                                    'status' => 'error',
                                    'data' => $data,
                                    'message' => $item->getErrors(),
                                ];
                                Yii::info($res, 'save');
                            }
                            $saved = false;
                            break;
                        };
                    }
                }
            }else{
                $saved = true;
            }
            if ($saved) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return false;
            }
        } catch (Exception $e) {
            Yii::info('Not saved model orders' . $e, 'save');
        }
        return false;
    }
    public function saveOneOrders($data,$id=null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $saved = false;
            $errors = [];
            $errors['size'] = [];
            $errors['print'] = [];
            $errors['stone'] = [];
            $errors['acs'] = [];
            $errors['toquv_acs'] = [];
            $errors['orders'] = [];
            $errors['moi_mato'] = [];
            $result = [];
            $result['status'] = 0;

            if(!empty($data)) {
                if (!empty($data['models_list_id']) && !empty($data['model_var_id'])) {
                    $model = ModelOrdersItems::findOne($id);
                    if(!empty($model)){
                        ModelOrdersItemsAcs::deleteAll(['model_orders_items_id' => $model->id]);
                        ModelOrdersItemsToquvAcs::deleteAll(['model_orders_items_id' => $model->id]);
                        ModelOrderItemsPrints::deleteAll(['model_orders_items_id' => $model->id]);
                        ModelOrderItemsStone::deleteAll(['model_orders_items_id' => $model->id]);
                        ModelOrdersItemsSize::deleteAll(['model_orders_items_id' => $model->id]);
                        ModelOrderItemsBaski::deleteAll(['model_orders_items_id'=>$model->id]);
                        ModelOrderItemsRotatsion::deleteAll(['model_orders_items_id'=>$model->id]);
                        ModelOrdersItemsMaterial::deleteAll(['model_orders_items_id'=>$model->id]);
                        $item = $model;
                    }else {
                        $item = new ModelOrdersItems();
                    }
                    $item->setAttributes([
                        'model_orders_id' => $this->id,
                        'models_list_id' => (integer)$data['models_list_id'],
                        'model_var_id' => (integer)$data['model_var_id'],
                        'add_info' => $data['add_info'],
                        'percentage' => $data['percentage'],
                        'load_date' => ($data['load_date']) ? $data['load_date'] : null,
                        'priority' => $data['priority'],
                        'season' => $data['season'],
                        'baski_id' => $data['baski_id'],
                        'rotatsion_id' => $data['rotatsion_id'],
                        'prints_id' => $data['prints_id'],
                        'stone_id' => $data['stone_id'],
                        'price' => $data['price'],
                        'pb_id' => $data['pb_id'],
                        'prepayment' => $data['prepayment'],
                        'brend_id' => $data['brend_id'],
                        'size_collections_id' => $data['size_collections_id'],
                    ]);
                    if ($item->save()) {
                        $saved = true;
                        $models_list = ModelsList::findOne($item['models_list_id']);
                        if(!empty($models_list)) {
                            if(!empty($models_list->modelsRawMaterials)){
                                foreach ($models_list->modelsRawMaterials as $modelsRawMaterial) {
                                    if($modelsRawMaterial !== null) {
                                        $mato = WmsMatoInfo::findOne(['toquv_raw_materials_id' => $modelsRawMaterial['rm_id']]);
                                        $moi_mato = new ModelOrdersItemsMaterial([
                                            'model_orders_id' => $this->id,
                                            'model_orders_items_id' => $item['id'],
                                            'mato_id' => $mato->id ? $mato->id : '',
                                            'models_list_id' => $models_list['id'],
                                            'model_var_id' => $item['model_var_id']
                                        ]);
                                        if ($moi_mato->save(false)) {
                                            $saved = true;
                                            unset($moi_mato);
                                        }
                                        else {
                                            if ($moi_mato->hasErrors()) {
                                                $res = [
                                                    'status' => 'error',
                                                    'message' => $moi_mato->getErrors(),
                                                ];
                                                Yii::info($res, 'save');
                                                array_push($errors['moi_mato'], $moi_mato->getErrors());
                                            }
                                            $saved = false;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (!empty($data['size'])&&$saved) {
                            foreach ($data['size'] as $m => $val) {
                                /*if (!empty($val) && $val != 0) {*/
                                $item_size = new ModelOrdersItemsSize();
                                $item_size->setAttributes([
                                    'model_orders_items_id' => (integer)$item['id'],
                                    'count' => $val,
                                    'size_id' => $m,
                                ]);
                                if ($item_size->save()) {
                                    $saved = true;
                                } else {
                                    if ($item_size->hasErrors()) {
                                        $res = [
                                            'status' => 'error',
                                            'message' => $item_size->getErrors(),
                                        ];
                                        Yii::info($res, 'save');
                                        array_push($errors['size'], $item_size->getErrors());
                                    }
                                    $saved = false;
                                    break;
                                };
                                /* }*/
                            }
                        }
                        if (!empty($data['depts_id'])&&$saved) {
                            foreach ($data['depts_id'] as $m => $val) {
                                if (!empty($val) && $val != 0) {
                                    $item_depts = new MoiRelDept();
                                    $item_depts->setAttributes([
                                        'model_orders_items_id' => (integer)$item['id'],
                                        'toquv_departments_id' => (integer)$val
                                    ]);
                                    if ($item_depts->save()) {
                                        $saved = true;
                                    } else {
                                        if ($item_depts->hasErrors()) {
                                            $res = [
                                                'status' => 'error',
                                                'message' => $item_depts->getErrors(),
                                            ];
                                            Yii::info($res, 'save');
                                        }
                                        $saved = false;
                                        break;
                                    };
                                }
                            }
                        }
                        if (!empty($data['baski'])&&$saved) {
                            foreach ($data['baski'] as $baski) {
                                if (!empty($baski) && $baski != 0) {
                                    $item_baski = new ModelOrderItemsBaski([
                                        'model_orders_items_id' => (integer)$item['id'],
                                        'model_var_baski_id' => (integer)$baski['id'],
                                    ]);
                                    if ($item_baski->save()) {
                                        $saved = true;
                                    } else {
                                        if ($item_baski->hasErrors()) {
                                            $res = [
                                                'status' => 'error',
                                                'message' => $item_baski->getErrors(),
                                            ];
                                            Yii::info($res, 'save');
                                            array_push($errors['baski'], $item_baski->getErrors());
                                        }
                                        $saved = false;
                                        break;
                                    };
                                }
                            }
                        }
                        if (!empty($data['rotatsion'])&&$saved) {
                            foreach ($data['rotatsion'] as $baski) {
                                if (!empty($baski) && $baski != 0) {
                                    $item_baski = new ModelOrderItemsRotatsion([
                                        'model_orders_items_id' => (integer)$item['id'],
                                        'model_var_rotatsion_id' => (integer)$baski['id'],
                                    ]);
                                    if ($item_baski->save()) {
                                        $saved = true;
                                    } else {
                                        if ($item_baski->hasErrors()) {
                                            $res = [
                                                'status' => 'error',
                                                'message' => $item_baski->getErrors(),
                                            ];
                                            Yii::info($res, 'save');
                                            array_push($errors['baski'], $item_baski->getErrors());
                                        }
                                        $saved = false;
                                        break;
                                    };
                                }
                            }
                        }
                        if (!empty($data['print'])&&$saved) {
                            foreach ($data['print'] as $print) {
                                if (!empty($print) && $print != 0) {
                                    $item_print = new ModelOrderItemsPrints([
                                        'model_orders_items_id' => (integer)$item['id'],
                                        'model_var_prints_id' => (integer)$print['id'],
                                    ]);
                                    if ($item_print->save()) {
                                        $saved = true;
                                    } else {
                                        if ($item_print->hasErrors()) {
                                            $res = [
                                                'status' => 'error',
                                                'message' => $item_print->getErrors(),
                                            ];
                                            Yii::info($res, 'save');
                                            array_push($errors['print'], $item_print->getErrors());
                                        }
                                        $saved = false;
                                        break;
                                    };
                                }
                            }
                        }
                        if (!empty($data['stone'])&&$saved) {
                            foreach ($data['stone'] as $stone) {
                                if (!empty($stone) && $stone != 0) {
                                    $item_stone = new ModelOrderItemsStone([
                                        'model_orders_items_id' => (integer)$item['id'],
                                        'model_var_stone_id' => (integer)$stone['id'],
                                    ]);
                                    if ($item_stone->save()) {
                                        $saved = true;
                                    } else {
                                        if ($item_stone->hasErrors()) {
                                            $res = [
                                                'status' => 'error',
                                                'message' => $item_stone->getErrors(),
                                            ];
                                            Yii::info($res, 'save');
                                            array_push($errors['stone'], $item_stone->getErrors());
                                        }
                                        $saved = false;
                                        break;
                                    };
                                }
                            }
                        }
                        if (!empty($data['acs'])&&$saved) {
                            foreach ($data['acs'] as $acs) {
                                if (!empty($acs) && $acs != 0) {
                                    $item_acs = new ModelOrdersItemsAcs([
                                        'models_orders_id' => $this->id,
                                        'model_orders_items_id' => (integer)$item['id'],
                                        'bichuv_acs_id' => (integer)$acs['id'],
                                        'qty' => ($acs['qty']!=null)?$acs['qty']:0,
                                        'unit_id' => $acs['unit_id'],
                                    ]);
                                    if ($item_acs->save()) {
                                        $saved = true;
                                    } else {
                                        if ($item_acs->hasErrors()) {
                                            $res = [
                                                'status' => 'error',
                                                'message' => $item_acs->getErrors(),
                                            ];
                                            array_push($errors['acs'], $item_acs->getErrors());
                                            Yii::info($res, 'save');
                                        }
                                        $saved = false;
                                        break;
                                    };
                                }
                            }
                        }
                        if (!empty($data['toquv_acs'])&&$saved) {
                            foreach ($data['toquv_acs'] as $toquv_acs) {
                                if (!empty($toquv_acs) && $toquv_acs != 0) {
                                    $item_toquv_acs = new ModelOrdersItemsToquvAcs([
                                        'model_orders_id' => $this->id,
                                        'model_orders_items_id' => (integer)$item['id'],
                                        'toquv_raw_materials_id' => (integer)$toquv_acs['id'],
                                        'quantity' => ($toquv_acs['qty']!=null)?$toquv_acs['qty']:0,
                                        'count' => ($toquv_acs['count']!=null)?$toquv_acs['count']:0,
                                    ]);
                                    if ($item_toquv_acs->save()) {
                                        $saved = true;
                                    } else {
                                        if ($item_toquv_acs->hasErrors()) {
                                            $res = [
                                                'status' => 'error',
                                                'message' => $item_toquv_acs->getErrors(),
                                            ];
                                            array_push($errors['toquv_acs'], $item_toquv_acs->getErrors());
                                            Yii::info($res, 'save');
                                        }
                                        $saved = false;
                                        break;
                                    };
                                }
                            }
                        }
                    } else {
                        $errors['model_order_items'] = $item->getErrors();
                        if ($item->hasErrors()) {
                            $res = [
                                'status' => 'error',
                                'message' => $item->getErrors(),
                            ];
                            array_push($errors['orders'], $item->getErrors());
                            Yii::info($res, 'save');
                        }
                        $saved = false;
                    };
                }
            }
            if ($saved) {
                $transaction->commit();
                $result['status'] = 1;
                $result['model'] = $item;
                return $result;
            } else {
                $transaction->rollBack();
                $result['errors'] = $errors;
            }
        } catch (Exception $e) {
            Yii::info('Not saved model orders' . $e, 'save');
        }
        return $result;
    }
    public function saveAcsOrders($data_list,$id=null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $saved = false;
            $errors = [];
            $errors['acs'] = [];
            $result = [];
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Xatolik yuz berdi!');
            $savedResult = true;
            if(!empty($data_list)) {
                foreach ($data_list as $data) {
                    $item = ModelOrdersItems::findOne($data['id']);
                    if ( !empty($item) ) {
                        $saved = true;
                        ModelOrdersItemsAcs::deleteAll(['model_orders_items_id' => $item->id]);
                        if ( !empty($data['acs']) && $saved ) {
                            foreach ($data['acs'] as $key => $acs) {
                                if ( !empty($acs) && $acs != 0 ) {
                                    $item_acs = new ModelOrdersItemsAcs([
                                        'models_orders_id' => $this->id,
                                        'model_orders_items_id' => (integer)$item['id'],
                                        'bichuv_acs_id' => (integer)$acs['id'],
                                        'qty' => ($acs['qty'] != null) ? $acs['qty'] : 0,
                                        'unit_id' => $acs['unit_id'],
                                        'add_info' => $acs['add_info'],
                                    ]);
                                    if ( $item_acs->save() ) {
                                        $saved = true;
                                    } else {
                                        if ( $item_acs->hasErrors() ) {
                                            $res = [
                                                'status' => 'error',
                                                'message' => $item_acs->getErrors(),
                                            ];
                                            $error = $item_acs->getErrors();
                                            $errors[$key] = $error;
                                            Yii::info($res, 'save');
                                        }
                                        $saved = false;
                                        $savedResult = false;
                                    };
                                }
                            }
                        }
                    } else {
                        $saved = false;
                        break;
                    }
                }
            }
            if ($saved && $savedResult) {
                $transaction->commit();
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Saved Successfully');
                return $result;
            } else {
                $transaction->rollBack();
                $result['errors'] = $errors;
            }
        } catch (Exception $e) {
            Yii::info('Not saved model orders' . $e, 'save');
        }
        return $result;
    }
    public function deleteItems()
    {
        if (!empty($this->modelOrdersResponsibles)) {
            ModelOrdersResponsible::deleteAll('model_orders_id = ' . $this->id);
        }
        if (!empty($this->modelOrdersItems)) {
            ModelOrdersItems::deleteAll('model_orders_id = ' . $this->id);
        }
    }

    /**
     * @param $data
     */
    public function saveItems($data)
    {
        if($this->saveResponsible($data['ModelOrders']['responsible']) && $this->saveOrders($data['ModelOrdersItems'])){
            return true;
        }
        return false;
    }

    public function savePlanning($data)
    {
        if (!empty($data['ModelOrdersPlanning'])) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                ModelOrdersPlanning::deleteAll(['model_orders_id'=>$this->id,'type'=>MoiRelDept::TYPE_MATO]);
                $saved = false;
                foreach ($data['ModelOrdersPlanning'] as $key) {
                    if (!empty($key['child'])) {
                        foreach ($key['child'] as $item) {
                            $plan = new ModelOrdersPlanning();
                            $plan->setAttributes([
                                'model_orders_items_id' => $key['model_orders_items_id'],
                                'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                'work_weight' => $item['work_weight'],
                                'finished_fabric' => $item['finished_fabric'],
                                'raw_fabric' => $item['raw_fabric'],
                                'thread_length' => $item['thread_length'],
                                'finish_en' => $item['finish_en'],
                                'finish_gramaj' => $item['finish_gramaj'],
                                'color_pantone_id' => $item['color_pantone_id'],
                                'color_id' => $item['color_id'],
                                'add_info' => $item['add_info'],
                                'size_list' => $item['size_list'],
                                'size_list_name' => $item['size_list_name'],
                                'size_count' => $item['size_count'],
                                'model_orders_id' => $this->id,
                            ]);
                            if($plan->save()){
                                $saved = true;
                            } else {
                                if($plan->hasErrors()){
                                    $res = [
                                        'status' => 'error',
                                        'message' => $plan->getErrors(),
                                    ];
                                    Yii::info($res, 'save');
                                }
                                $saved = false;
                                break;
                            };
                        }
                    }
                }
                if ($saved) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success',Yii::t('app', 'Saved Successfully'));
                    return true;
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
                }
            } catch (Exception $e) {
                Yii::info('Not saved model orders planning' . $e, 'save');
                Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
            }
        }
        return false;
    }
    public function savePlanningAks($data)
    {
        if (!empty($data['ModelOrdersPlanning'])) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                ModelOrdersPlanning::deleteAll(['model_orders_id'=>$this->id,'type'=>MoiRelDept::TYPE_MATO_AKS]);
                $saved = false;
                foreach ($data['ModelOrdersPlanning'] as $key) {
                    if (!empty($key['child'])) {
                        foreach ($key['child'] as $item) {
                            $plan = new ModelOrdersPlanning();
                            $plan->setAttributes([
                                'model_orders_items_id' => $key['model_orders_items_id'],
                                'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                'work_weight' => $item['work_weight'],
                                'finished_fabric' => $item['finished_fabric'],
                                'raw_fabric' => $item['raw_fabric'],
                                'count' => $item['count'],
                                'thread_length' => $item['thread_length'],
                                'finish_en' => $item['finish_en'],
                                'finish_gramaj' => $item['finish_gramaj'],
                                'color_pantone_id' => $item['color_pantone_id'],
                                'color_id' => $item['color_id'],
                                'add_info' => $item['add_info'],
                                'size_list' => $item['size_list'],
                                'size_list_name' => $item['size_list_name'],
                                'size_count' => $item['size_count'],
                                'type' => MoiRelDept::TYPE_MATO_AKS,
                                'model_orders_id' => $this->id,
                            ]);
                            if($plan->save()){
                                $saved = true;
                            } else {
                                if($plan->hasErrors()){
                                    $res = [
                                        'status' => 'error',
                                        'message' => $plan->getErrors(),
                                    ];
                                    Yii::info($res, 'save');
                                }
                                $saved = false;
                                break;
                            };
                        }
                    }
                }
                if ($saved) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success',Yii::t('app', 'Saved Successfully'));
                    return true;
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
                }
            } catch (Exception $e) {
                Yii::info('Not saved model orders planning' . $e, 'save');
                Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
            }
        }
        return false;
    }
    /*public function savePlanningAks($data)
    {
        if (!empty($data['ModelOrdersPlanning'])) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                ModelOrdersPlanning::deleteAll(['model_orders_id'=>$this->id,'type'=>2]);
                $saved = false;
                foreach ($data['ModelOrdersPlanning'] as $key) {
                    if (!empty($key['child'])) {
                        foreach ($key['child'] as $child) {
                            if (!empty($child['size'])) {
                                foreach ($child['size'] as $size => $item) {
                                    $plan = new ModelOrdersPlanning();
                                    $plan->setAttributes([
                                        'model_orders_items_id' => $item['model_orders_items_id'],
                                        'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                        'work_weight' => $item['work_weight'],
                                        'finished_fabric' => $item['finished_fabric'],
                                        'raw_fabric' => $item['raw_fabric'],
                                        'thread_length' => $item['thread_length'],
                                        'finish_en' => $item['finish_en'],
                                        'finish_gramaj' => $item['finish_gramaj'],
                                        'color_pantone_id' => $item['color_pantone_id'],
                                        'color_id' => $item['color_id'],
                                        'add_info' => $item['add_info'],
                                        'count' => $item['count'],
                                        'size_id' => $item['size_id'],
                                        'type' => $item['type'] ?? ToquvRawMaterials::ACS,
                                        'model_orders_id' => $this->id,
                                    ]);
                                    if ($plan->save()) {
                                        $saved = true;
                                    } else {
                                        if ($plan->hasErrors()) {
                                            $res = [
                                                'status' => 'error',
                                                'message' => $plan->getErrors(),
                                            ];
                                            Yii::info($res, 'save');
                                        }
                                        $saved = false;
                                        break;
                                    };
                                }
                            }
                        }
                    }
                }
                if ($saved) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success',Yii::t('app', 'Saved Successfully'));
                    return true;
                } else {
                    Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
                Yii::info('Not saved model orders planning' . $e, 'save');
                $transaction->rollBack();
            }
        }
        return false;
    }*/
    public function saveDept($data,$type=MoiRelDept::TYPE_MATO)
    {
        if (!empty($data['MoiRelDept'])) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $subQuery = ModelOrdersItems::find()->select('id')->where(['model_orders_id'=>$this->id]);
                MoiRelDept::deleteAll(['AND',['status'=>1,'type'=>$type],['model_orders_items_id'=>$subQuery]]);
                $saved = false;
                foreach ($data['MoiRelDept'] as $key) {
                    if (!empty($key['child'])) {
                        foreach ($key['child'] as $item) {
                            $plan = new MoiRelDept();
                            $plan->setAttributes([
                                'model_orders_id' => $this->id,
                                'model_orders_items_id' => $key['model_orders_items_id'],
                                'company_categories_id' => $key['company_categories_id'],
                                'toquv_departments_id' => $key['toquv_departments_id'],
                                'model_orders_planning_id' => $item['model_orders_planning_id'],
                                'quantity' => $item['quantity'],
                                'count' => $item['count'] ?? null,
                                'size_id' => $item['size_id'] ?? null,
                                'start_date' => $item['start_date'],
                                'end_date' => $item['end_date'],
                                'thread_length' => $item['thread_length'],
                                'finish_en' => $item['finish_en'],
                                'finish_gramaj' => $item['finish_gramaj'],
                                'add_info' => $item['add_info'],
                                'type' => $type
                            ]);
                            if($plan->save()){
                                $saved = true;
                            } else {
                                if($plan->hasErrors()){
                                    $res = [
                                        'status' => 'error',
                                        'message' => $plan->getErrors(),
                                    ];
                                    Yii::info($res, 'save');
                                }
                                $saved = false;
                                break;
                            };
                        }
                    }
                }
                if ($saved) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success',Yii::t('app', 'Saved Successfully'));
                    return true;
                } else {
                    Yii::info('Not saved moi rel dept Saved topilmadi', 'save');
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
                }
            } catch (Exception $e) {
                Yii::info('Not saved moi rel dept' . $e, 'save');
            }
        }
        return false;
    }

    public function saveChangeQuantity($data)
    {
        if (!empty($data['ModelOrdersPlanning'])) {
            foreach ($data['ModelOrdersPlanning'] as $key) {
                $new_change = new ModelOrdersItemsChanges([
                    'model_orders_items_id' => $key['model_orders_items_id'],
                    'add_info' => $key['changes']
                ]);
                if ($new_change->save()) {
                    if (!empty($key['items'])) {
                        foreach ($key['items'] as $n => $value) {
                            $item = ModelOrdersItemsSize::findOne($n);
                            if ($item && $item['count'] != $value) {
                                $cloneItem = $item;
                                $changes = new ModelOrdersItemsSize();
                                $changes->attributes = $cloneItem->attributes;
                                $changes->model_orders_items_id = null;
                                $changes->parent_id = $n;
                                $changes->model_orders_items_changes_id = $new_change->id;
                                if ($changes->save()) {
                                    $item->count = $value;
                                    $item->save();
                                }
                            }
                        }
                    }
                    if (!empty($key['child'])) {
                        foreach ($key['child'] as $item) {
                            $plan = ModelOrdersPlanning::findOne(['model_orders_id' => $this->id, 'model_orders_items_id' => $key['model_orders_items_id'], 'toquv_raw_materials_id' => $item['toquv_raw_materials_id'], 'color_pantone_id'=>$item['color_pantone_id']]);
                            if ($plan) {
                                $clonePlan = $plan;
                                $new_plan = new ModelOrdersPlanning();
                                $new_plan->attributes = $clonePlan->attributes;
                                $plan->setAttributes([
                                    'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                    'finished_fabric' => $item['finished_fabric'],
                                    'raw_fabric' => $item['raw_fabric'],
                                    'add_info' => $item['add_info'],
                                ]);
                                if ($plan->finished_fabric != $new_plan->finished_fabric && $plan->raw_fabric != $new_plan->raw_fabric) {
                                    $new_plan->model_orders_items_id = null;
                                    $new_plan->model_orders_id = null;
                                    $new_plan->parent_id = $plan['id'];
                                    $new_plan->model_orders_items_changes_id = $new_change->id;
                                    if ($new_plan->save()) {
                                        $plan->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function saveGoods()
    {
        $saved = true;
        //TODO Vaqtinchalik orderlarni toliq kiritishni boshlagandan keyin yoqiladi
//        if ($this->modelOrdersItems) {
//            foreach ($this->modelOrdersItems as $key) {
//                if(empty($key->modelVar->id)){
//                   Yii::$app->session->setFlash('error','Model variantlar rangi kiritilmagan');
//                   $saved = false;
//                   break;
//                }
//
//                if ($key->modelOrdersItemsSizes) {
//                    foreach ($key->modelOrdersItemsSizes as $item) {
//                        $check = Goods::findOne([
//                            'model_id' => $key->modelsList->id,
//                            'size_type' => $item->size->size_type_id,
//                            'size' => $item->size_id,
//                            'model_var' => $key->modelVar->id,
//                            'color' => $key->modelVar->color_pantone_id
//                        ]);
//                        $all = Goods::find()->orderBy(['id' => SORT_DESC]);
//                        $count = $all->count();
//                        $barcode = ($count == 0) ? 100000000 : $all->one()->barcode + 1;
//                        if (empty($check)) {
//                            $data = [
//                                'barcode' => $barcode,
//                                'model_no' => $key->modelsList->article,
//                                'model_id' => $key->modelsList->id,
//                                'model_var' => $key->modelVar->id,
//                                'size_type' => $item->size->size_type_id,
//                                'size' => $item->size_id,
//                                'color' => $key->modelVar->color_pantone_id,
//                                'name' => $key->modelsList->name,
//                                'category' => $key->modelsList->type_id,
//                                'sub_category' => $key->modelsList->type_child_id,
//                                'model_type' => $key->modelsList->view_id,
//                                'season' => $key->modelsList->model_season
//                            ];
//                            $goods = new Goods($data);
//                            if ($goods->save()) {
//                                $saved = true;
//                            } else {
//                                $saved = false;
//                                break 2;
//                            }
//                        }
//                    }
//                }
//            }
//        }
        return $saved;
    }

    public function SaveNatification($data)
    {
        if(!empty($data)){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;

            try{
                $updateModel = true;
                if($updateModel){
                    $notification = new Notifications();
                    $notification->setAttributes([
                        'doc_id' => 15,
                        'body' => 'Bbb',
                        'from' => 495,
                        'to' => 1,
                        'status' => 1,
                        'module' => Yii::$app->controller->module->id,
                        'actions' => Yii::$app->controller->action->id,
                        'controllers' => Yii::$app->controller->id,
                        'pharams' => json_encode(['id' => 15]),
                    ]);
                    if($notification->save()){
                        $saved = true;
                    }else{
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Notificationga yozilmadi!'));
                        $saved = false;
                    }
                }
                else{
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Document Yangilanmadi!'));
                    return false;
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
                Yii::info('error message '.$e->getMessage(),'save');
            }
        }
        else{
            return false;
        }
    }

    public function getPlan($id)
    {
        $model = ModelOrdersPlanning::findOne(['id' => $id]);
        /*model_orders_items_id' => $id,
            'toquv_raw_materials_id' => $rawId,
            'color_pantone_id' => $color]*/
        return $model;
    }
    public function getPlanAks($id, $rawId, $size_id)
    {
        $model = ModelOrdersPlanning::findOne(['model_orders_id' => $this->id, 'model_orders_items_id' => $id, 'toquv_raw_materials_id' => $rawId, 'size_id'=>$size_id]);
        return $model;
    }
    public static function getOrdersList($id)
    {
        $sql = "SELECT
                    mo.id,
                    mo.doc_number,
                    m.name musteri,
                    reg_date
                FROM model_orders mo
                LEFT JOIN musteri m on mo.musteri_id = m.id 
                WHERE ( m.id = :musteri_id )
            ";
        $orders = Yii::$app->db->createCommand($sql)->bindValue(':musteri_id', $id)->queryAll();
        $data = [];
        if ($orders) {
            foreach ($orders as $item) {
                $data[$item['id']] = $item['doc_number'];
            }
        }
        return $data;
    }

    public static function getOrderItemsList($id)
    {
        $sql = "SELECT
                    moi.id,
                    mo.doc_number,
                    cp.code,
                    m.name musteri,
                    ml.name model,
                    size_id,
                    st.name size_type,
                    load_date,
                    summa
                FROM model_orders_items moi 
                LEFT JOIN model_orders mo on moi.model_orders_id = mo.id 
                LEFT JOIN models_variations mv on moi.model_var_id = mv.id 
                LEFT JOIN models_variation_colors mvc on mv.id = mvc.model_var_id 
                LEFT JOIN color_pantone cp on mvc.color_pantone_id = cp.id 
                LEFT JOIN musteri m on mo.musteri_id = m.id 
                LEFT JOIN models_list ml on moi.models_list_id = ml.id 
                LEFT JOIN model_orders_items_size mois on moi.id = mois.model_orders_items_id 
                LEFT JOIN ( SELECT model_orders_items_id, SUM(count) summa FROM model_orders_items_size mois3 
                            LEFT JOIN size s2 on mois3.size_id = s2.id GROUP BY mois3.model_orders_items_id ) 
                    mois2 on moi.id = mois2.model_orders_items_id 
                LEFT JOIN size s on mois.size_id = s.id LEFT JOIN size_type st on s.size_type_id = st.id 
                WHERE 
                    ( mo.id = :order_id ) AND 
                    ( mvc.is_main = 1 ) AND 
                    ( mois.id = ( SELECT MAX(id) FROM model_orders_items_size mois WHERE mois.model_orders_items_id = moi.id )
            )";
        $orders = Yii::$app->db->createCommand($sql)->bindValue(':order_id', $id)->queryAll();
        $data = [];
        if ($orders) {
            foreach ($orders as $item) {
                $data[$item['id']] = "{$item['model']} - {$item['code']} - {$item['size_type']} - {$item['summa']}";
            }
        }
        return $data;
    }

    /**
     * @param int $status
     * @return mixed
     * @throws Exception
     */
    public function getCount($status = 1)
    {
        $st = '';
        if ($status) {
            $st = " AND moi.status = {$status}";
        }
        $sql = "select SUM(mois.count) sum
                FROM model_orders_items_size mois
                LEFT JOIN model_orders_items moi ON mois.model_orders_items_id = moi.id
                LEFT JOIN model_orders mo on moi.model_orders_id = mo.id
                WHERE mo.id = %d %s";
        $sql = sprintf($sql, $this->id, $st);
        return Yii::$app->db->createCommand($sql)->queryOne()['sum'];
    }

    public static function searchPrint($query,$list)
    {
        $q = (!empty($query))?" AND (mvp.name LIKE '%{$query}%' OR mvp.desen_no LIKE '%{$query}%' OR mvp.code LIKE '%{$query}%' OR b.name LIKE '%{$query}%' OR m.name LIKE '%{$query}%')":"";
        $arr = (!empty($list))?" AND mvp.id NOT IN (".implode(',', $list).")":"";
        $sql = "SELECT mvp.id id,
                       mvp.name name,
                       mvp.desen_no desen_no,
                       mvp.code code,
                       mvp.width,
                       mvp.height,
                       b.name brend_id,
                       m.name musteri_id,
                       mvp.add_info add_info,
                       a.path image
                       FROM model_var_prints mvp
                            left join brend b on mvp.brend_id = b.id
                            left join model_var_print_rel_attach mvpra on mvp.id = mvpra.model_var_print_id
                            left join attachments a on mvpra.attachment_id = a.id
                            left join musteri m on mvp.musteri_id = m.id
                       WHERE (mvpra.is_main != 0 OR mvpra.is_main IS NULL) %s %s GROUP BY mvp.id,mvpra.id";
        $sql = sprintf($sql,$q,$arr);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }
    public static function searchBaski($query,$list)
    {
        $q = (!empty($query))?" AND (mvp.name LIKE '%{$query}%' OR mvp.desen_no LIKE '%{$query}%' OR mvp.code LIKE '%{$query}%' OR b.name LIKE '%{$query}%' OR m.name LIKE '%{$query}%')":"";
        $arr = (!empty($list))?" AND mvp.id NOT IN (".implode(',', $list).")":"";
        $sql = "SELECT mvp.id id,
                       mvp.name name,
                       mvp.desen_no desen_no,
                       mvp.code code,
                       mvp.width,
                       mvp.height,
                       b.name brend_id,
                       m.name musteri_id,
                       mvp.add_info add_info,
                       a.path image
                       FROM model_var_baski mvp
                            left join brend b on mvp.brend_id = b.id
                            left join model_var_baski_rel_attach mvpra on mvp.id = mvpra.model_var_baski_id
                            left join attachments a on mvpra.attachment_id = a.id
                            left join musteri m on mvp.musteri_id = m.id
                       WHERE (mvpra.is_main != 0 OR mvpra.is_main IS NULL) %s %s GROUP BY mvp.id,mvpra.id";
        $sql = sprintf($sql,$q,$arr);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }

    /**
     * @param $query
     * @param $list
     * @return array
     * @throws Exception
     */
    public static function searchRotatsion($query,$list)
    {
        $q = (!empty($query))?" AND (mvp.name LIKE '%{$query}%' OR mvp.desen_no LIKE '%{$query}%' OR mvp.code LIKE '%{$query}%' OR b.name LIKE '%{$query}%' OR m.name LIKE '%{$query}%')":"";
        $arr = (!empty($list))?" AND mvp.id NOT IN (".implode(',', $list).")":"";
        $sql = "SELECT mvp.id id,
                       mvp.name name,
                       mvp.desen_no desen_no,
                       mvp.code code,
                       mvp.width,
                       mvp.height,
                       b.name brend_id,
                       m.name musteri_id,
                       mvp.add_info add_info,
                       a.path image
                       FROM model_var_rotatsion mvp
                            left join brend b on mvp.brend_id = b.id
                            left join model_var_rotatsion_rel_attach mvpra on mvp.id = mvpra.model_var_rotatsion_id
                            left join attachments a on mvpra.attachment_id = a.id
                            left join musteri m on mvp.musteri_id = m.id
                       WHERE (mvpra.is_main != 0 OR mvpra.is_main IS NULL) %s %s GROUP BY mvp.id,mvpra.id";
        $sql = sprintf($sql,$q,$arr);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }

    public static function searchStone($query,$list)
    {
        $q = (!empty($query))?" AND (mvs.name LIKE '%{$query}%' OR mvs.desen_no LIKE '%{$query}%' OR mvs.code LIKE '%{$query}%' OR b.name LIKE '%{$query}%' OR m.name LIKE '%{$query}%')":"";
        $arr = (!empty($list))?" AND mvs.id NOT IN (".implode(',', $list).")":"";
        $sql = "SELECT mvs.id id,
                       mvs.name name,
                       mvs.desen_no desen_no,
                       mvs.code code,
                       mvs.width,
                       mvs.height,
                       b.name brend_id,
                       m.name musteri_id,
                       mvs.add_info add_info,
                       a.path image
                       FROM model_var_stone mvs
                            left join brend b on mvs.brend_id = b.id
                            left join model_var_stone_rel_attach mvsra on mvs.id = mvsra.model_var_stone_id
                            left join attachments a on mvsra.attachment_id = a.id
                            left join musteri m on mvs.musteri_id = m.id
                       WHERE (mvsra.is_main != 0 OR mvsra.is_main IS NULL) %s %s GROUP BY mvs.id,mvsra.id";
        $sql = sprintf($sql,$q,$arr);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }
    public static function searchAcs($query,$list)
    {
        $q = (!empty($query))?" AND (ba.name LIKE '%{$query}%' OR ba.sku LIKE '%{$query}%' OR ba.barcode LIKE '%{$query}%' OR bap.name LIKE '%{$query}%' OR ba.add_info LIKE '%{$query}%')":"";
        $arr = (!empty($list))?" AND ba.id NOT IN (".implode(',', $list).")":"";
        $sql = "SELECT ba.id id,
                       ba.name name,
                       ba.sku artikul,
                       bap.name turi,
                       u.name unit,
                       u.id unit_id,
                       ba.barcode barcod,
                       ba.add_info add_info,
                       MAX(baa.path) image
                       FROM bichuv_acs ba
                            left join bichuv_acs_property bap on ba.property_id = bap.id
                            left join bichuv_acs_attachment baa on ba.id = baa.bichuv_acs_id
                            left join unit u on ba.unit_id = u.id
                       WHERE (baa.isMain != 0 OR baa.isMain IS NULL) %s %s GROUP BY ba.id";
        $sql = sprintf($sql,$q,$arr);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }

    /**
     * @return bool
     */
    public function saveToquvOrders(){
        $saved = false;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $samo = Musteri::find()->select('id')->where(['token'=>'SAMO'])->one();
            $order_type = ToquvOrders::ORDER_SAMO;
            $toquv_mato = ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SEH']);
            if(!empty($toquv_mato)) {
                $moi_rel_dept = MoiRelDept::find()->where([
                    'model_orders_id' => $this->id,
                    'toquv_departments_id' => $toquv_mato['id'],
                    'status' => MoiRelDept::STATUS_ACTIVE,
                    'type' => MoiRelDept::TYPE_MATO
                ])->orderBy(['end_date' => SORT_DESC])->all();
            }
            $saved = false;
            if(!empty($moi_rel_dept)) {
                $orders = new ToquvOrders();
                $orders->setAttributes([
                    'musteri_id' => $samo['id'],
                    'document_number' => $this->doc_number,
                    'reg_date' => date('Y-m-d'),
                    'type' => ToquvRawMaterials::MATO,
                    'model_orders_id' => $this->id,
                    'model_musteri_id' => $this->musteri_id,
                    'responsible' => 1,
                    'status' => ToquvOrders::STATUS_SAVED,
                    'order_type' => $order_type
                ]);
                if ($orders->save()){
                    $saved = true;
                }else{
                    if ($orders->hasErrors()) {
                        Yii::$app->session->setFlash('error', end($orders->getErrors()));
                        $res = [
                            'status' => 'error',
                            'errors' => $orders->getErrors(),
                            'message' => 'Not saved Toquv Orders in Model orders plan'
                        ];
                        Yii::info($res, 'save');
                        Yii::info(['model'=>$this->doc_number,'type'=>'order nosave'], 'save');
                    }
                    $saved = false;
                }
            }else{
                Yii::$app->session->setFlash('error',Yii::t('app', 'Aktiv buyurtmalar topilmadi !!!'));
                return $saved;
            }
            if ($saved){
                $saved = false;
                if (!empty($moi_rel_dept)) {
                    foreach ($moi_rel_dept as $item) {
                        $plan = $item->modelOrdersPlanning;
                        $rm_order = ToquvRmOrder::findOne([
                            'toquv_orders_id' => $orders['id'],
                            'toquv_raw_materials_id' => $plan->toquv_raw_materials_id,
                            'color_pantone_id' => $item->modelOrdersPlanning->color_pantone_id,
                            'color_id' => $item->modelOrdersPlanning->color_id,
                            'thread_length' => $item->thread_length,
                            'finish_en' => $item->finish_en,
                            'finish_gramaj' => $item->finish_gramaj
                        ]);
                        if(empty($rm_order)) {
                            $rm_order = new ToquvRmOrder();
                        }
                        $rm_order->setAttributes([
                            'toquv_orders_id' => $orders->id,
                            'toquv_raw_materials_id' => $plan->toquv_raw_materials_id,
                            'priority' => $item->modelOrdersItems->priority,
                            'rm_type' => ToquvRawMaterials::MATO,
                            'price' => $rm_order['price'] + ToquvRawMaterials::getNarx($plan->toquv_raw_materials_id,$item->quantity),
                            'price_fakt' => $rm_order['price'] + ToquvRawMaterials::getNarx($plan->toquv_raw_materials_id,$item->quantity),
                            'quantity' => $rm_order['quantity'] + $item->quantity,
                            'unit_id' => 2,
                            'done_date' => $item->end_date,
                            'thread_length' => $item->thread_length,
                            'finish_en' => $item->finish_en,
                            'finish_gramaj' => $item->finish_gramaj,
                            'type_weaving' => ($item->finish_en<=105)?2:1,
                            'model_orders_id' => $this->id,
                            'moi_id' => $item->model_orders_items_id,
                            'planed_date' => date('Y-m-d H:i:s'),
                            'model_musteri_id' => $item->modelOrdersItems->modelOrders->musteri_id,
                            'model_code' => $item->modelOrdersItems->modelsList->article,
                            'color_pantone_id' => $item->modelOrdersPlanning->color_pantone_id,
                            'color_id' => $item->modelOrdersPlanning->color_id,
                            'order_type' => $order_type
                        ]);
                        if($rm_order->save()){
                            $rm_moi_item = new ToquvRmOrderMoi([
                                'toquv_rm_order_id' => $rm_order->id,
                                'model_orders_items_id' => $item->model_orders_items_id,
                                'model_orders_id' => $item->model_orders_id,
                                'moi_rel_dept_id' => $item->id,
                                'quantity' => $item->quantity,
                                'start_date' => $item->start_date,
                                'end_date' => $item->end_date,
                                'size_list_name' => $plan->size_list_name,
                                'toquv_orders_id' => $orders['id']
                            ]);
                            if($rm_moi_item->save()) {
                                foreach ($rm_order->toquvRawMaterials->toquvRawMaterialIps as $ip) {
                                    $rm_item = ToquvRmOrderItems::findOne([
                                        'toquv_rm_order_id' => $rm_order->id,
                                        'toquv_ne_id' => $ip->ne_id,
                                        'toquv_thread_id' => $ip->thread_id
                                    ]);
                                    if(empty($rm_item)) {
                                        $rm_item = new ToquvRmOrderItems();
                                    }
                                    $rm_item->setAttributes([
                                        'percentage' => $ip->percentage,
                                        'own_quantity' => $rm_item['own_quantity'] + ($ip->percentage * $item->quantity / 100),
                                        'toquv_rm_order_id' => $rm_order->id,
                                        'toquv_ne_id' => $ip->ne_id,
                                        'toquv_thread_id' => $ip->thread_id
                                    ]);
                                    if ($rm_item->save()) {
                                        $saved = true;
                                    }else{
                                        if ($rm_item->hasErrors()) {
                                            Yii::$app->session->setFlash('error', end($rm_item->getErrors()));
                                            $res = [
                                                'status' => 'error',
                                                'errors' => $rm_item->getErrors(),
                                                'message' => 'Not saved Toquv Rm Order Item in Model orders plan'
                                            ];
                                            Yii::info($res, 'save');
                                        }
                                        Yii::info(['model'=>$ip,'type'=>'ip nosave'], 'save');
                                        $saved = false;
                                        break 2;
                                    }
                                }
                            }else{
                                if ($rm_moi_item->hasErrors()) {
                                    Yii::$app->session->setFlash('error', end($rm_moi_item->getErrors()));
                                    $res = [
                                        'status' => 'error',
                                        'errors' => $rm_moi_item->getErrors(),
                                        'message' => 'Not saved Toquv Rm Order Moi in Model orders plan'
                                    ];
                                    Yii::info($res, 'save');
                                }
                                Yii::info(['model'=>$item,'type'=>'rm moi nosave'], 'save');
                                $saved = false;
                                break;
                            }
                        }else{
                            if ($rm_order->hasErrors()) {
                                Yii::$app->session->setFlash('error', end($rm_order->getErrors()));
                                $res = [
                                    'status' => 'error',
                                    'errors' => $rm_order->getErrors(),
                                    'message' => 'Not saved Toquv Rm Order in Model orders plan'
                                ];
                                Yii::info($res, 'save');
                            }
                            Yii::info(['model'=>$item,'type'=>'rm nosave'], 'save');
                            $saved = false;
                            break;
                        }
                    }
                }
            }
            if ($saved) {
                $transaction->commit();
                Yii::$app->session->setFlash('success',Yii::t('app', 'Saved Successfully'));
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
            }
        } catch (Exception $e) {
            Yii::info('Not saved model orders moi rel dept' . $e, 'save');
            $transaction->rollBack();
            Yii::$app->session->setFlash('error',$e->getMessage());

            new Telegram([
                'text' => $e->getMessage()." ".json_encode($e),
                'module' => 'Base',
                'controlller' => 'ModelOrders',
            ]);
        }
        return $saved;
    }
    public function saveToquvAksOrders(){
        $saved = false;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $samo = Musteri::find()->select('id')->where(['token'=>'SAMO'])->one();
            $order_type = ToquvOrders::ORDER_SAMO;
            $toquv_mato = ToquvDepartments::findOne(['token'=>'TOQUV_ACS_SEH']);
            if(!empty($toquv_mato)) {
                $moi_rel_dept = MoiRelDept::find()->where([
                    'model_orders_id' => $this->id,
                    'toquv_departments_id' => $toquv_mato['id'],
                    'status' => MoiRelDept::STATUS_ACTIVE,
                    'type' => MoiRelDept::TYPE_MATO_AKS
                ])->orderBy(['end_date' => SORT_DESC])->all();
            }
            $saved = false;
            if(!empty($moi_rel_dept)) {
                $orders = new ToquvOrders();
                $orders->setAttributes([
                    'musteri_id' => $samo['id'],
                    'document_number' => $this->doc_number."-",
                    'reg_date' => date('Y-m-d'),
                    'type' => ToquvRawMaterials::ACS,
                    'model_orders_id' => $this->id,
                    'model_musteri_id' => $this->musteri_id,
                    'responsible' => 1,
                    'status' => 3,
                    'order_type' => $order_type
                ]);
                if ($orders->save()){
                    $saved = true;
                }else{
                    if ($orders->hasErrors()) {
                        Yii::$app->session->setFlash('error', end($orders->getErrors()));
                        $res = [
                            'status' => 'error',
                            'errors' => $orders->getErrors(),
                            'message' => 'Not saved Toquv Orders in Model orders plan'
                        ];
                        Yii::info($res, 'save');
                    }
                    $saved = false;
                }
            }else{
                Yii::$app->session->setFlash('error',Yii::t('app', 'Aktiv buyurtmalar topilmadi !!!'));
                return $saved;
            }
            if ($saved){
                $saved = false;
                if (!empty($moi_rel_dept)) {
                    foreach ($moi_rel_dept as $item) {
                        $plan = $item->modelOrdersPlanning;
                        if (!empty($orders['id'])) {
                            $rm_order = ToquvRmOrder::findOne([
                                'toquv_orders_id' => $orders['id'],
                                'toquv_raw_materials_id' => $plan->toquv_raw_materials_id,
                                'color_pantone_id' => $item->modelOrdersPlanning->color_pantone_id,
                                'color_id' => $item->modelOrdersPlanning->color_id,
                                'thread_length' => $plan->thread_length,
                                'finish_en' => $plan->finish_en,
                                'finish_gramaj' => $plan->finish_gramaj
                            ]);
                        }
                        if(empty($rm_order)) {
                            $rm_order = new ToquvRmOrder();
                        }
                        $rm_order->setAttributes([
                            'toquv_orders_id' => $orders->id,
                            'toquv_raw_materials_id' => $plan->toquv_raw_materials_id,
                            'priority' => $item->modelOrdersItems->priority,
                            'rm_type' => ToquvRawMaterials::ACS,
                            'price' => $rm_order['price'] + ToquvRawMaterials::getNarx($plan->toquv_raw_materials_id,$item->quantity),
                            'price_fakt' => $rm_order['price'] + ToquvRawMaterials::getNarx($plan->toquv_raw_materials_id,$item->quantity),
                            'quantity' => $rm_order['quantity'] + $item->quantity,
                            'count' => $rm_order['count'] + $item->count,
                            'unit_id' => 2,
                            'done_date' => $item->end_date,
                            'thread_length' => $plan->thread_length,
                            'finish_en' => $plan->finish_en,
                            'finish_gramaj' => $plan->finish_gramaj,
                            'moi_id' => $item->model_orders_items_id,
                            'model_orders_id' => $item->model_orders_id,
                            'planed_date' => date('Y-m-d H:i:s'),
                            'model_musteri_id' => $item->modelOrdersItems->modelOrders->musteri_id,
                            'model_code' => $item->modelOrdersItems->modelsList->article,
                            'color_pantone_id' => $item->modelOrdersPlanning->color_pantone_id,
                            'color_id' => $item->modelOrdersPlanning->color_id,
                            'order_type' => $order_type
                        ]);
                        if($rm_order->save()){
                            $rm_moi_item = new ToquvRmOrderMoi([
                                'toquv_rm_order_id' => $rm_order->id,
                                'model_orders_items_id' => $item->model_orders_items_id,
                                'model_orders_id' => $item->model_orders_id,
                                'moi_rel_dept_id' => $item->id,
                                'quantity' => $item->quantity,
                                'count' => $item->count,
                                'start_date' => $item->start_date,
                                'end_date' => $item->end_date,
                                'toquv_orders_id' => $orders['id'],
                            ]);
                            if($rm_moi_item->save()) {
                                foreach ($rm_order->toquvRawMaterials->toquvRawMaterialIps as $ip) {
                                    $rm_item = ToquvRmOrderItems::findOne([
                                        'toquv_rm_order_id' => $rm_order->id,
                                        'toquv_ne_id' => $ip->ne_id,
                                        'toquv_thread_id' => $ip->thread_id
                                    ]);
                                    if(empty($rm_item)) {
                                        $rm_item = new ToquvRmOrderItems();
                                    }
                                    $rm_item->setAttributes([
                                        'percentage' => $ip->percentage,
                                        'own_quantity' => $rm_item['own_quantity'] + ($ip->percentage * $item->quantity / 100),
                                        'toquv_rm_order_id' => $rm_order->id,
                                        'toquv_ne_id' => $ip->ne_id,
                                        'toquv_thread_id' => $ip->thread_id
                                    ]);
                                    if ($rm_item->save()) {
                                        $saved = true;
                                    }else{
                                        if ($rm_item->hasErrors()) {
                                            Yii::$app->session->setFlash('error', end($rm_item->getErrors()));
                                            $res = [
                                                'status' => 'error',
                                                'errors' => $rm_item->getErrors(),
                                                'message' => 'Not saved Toquv Rm Order Item in Model orders plan'
                                            ];
                                            Yii::info($res, 'save');
                                        }
                                        $saved = false;
                                        break 2;
                                    }
                                }
                            }else{
                                if ($rm_moi_item->hasErrors()) {
                                    Yii::$app->session->setFlash('error', end($rm_moi_item->getErrors()));
                                    $res = [
                                        'status' => 'error',
                                        'errors' => $rm_moi_item->getErrors(),
                                        'message' => 'Not saved Toquv Rm Order Moi in Model orders plan'
                                    ];
                                    Yii::info($res, 'save');
                                }
                                $saved = false;
                                break;
                            }
                        }else{
                            if ($rm_order->hasErrors()) {
                                Yii::$app->session->setFlash('error', end($rm_order->getErrors()));
                                $res = [
                                    'status' => 'error',
                                    'errors' => $rm_order->getErrors(),
                                    'message' => 'Not saved Toquv Rm Order in Model orders plan'
                                ];
                                Yii::info($res, 'save');
                            }
                            $saved = false;
                            break;
                        }
                    }
                }else{
                    VarDumper::dump($orders,10,true);die;
                }
            }
            if ($saved) {
                $transaction->commit();
                Yii::$app->session->setFlash('success',Yii::t('app', 'Saved Successfully'));
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
            }
        } catch (Exception $e) {
            Yii::info('Not saved model orders moi rel dept' . $e, 'save');
            $transaction->rollBack();
            Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
        }
        return $saved;
    }
    /**
     * @return array
     * @throws Exception
     */
    public static function getRemainReportAcs(){

        $sql = "select ba.name as acs,
                       ba.sku,
                       bap.name as property,
                       moi.load_date,
                       cp.code,
                       ml.article,
                       m.name as cg,
                       SUM(mo.sum_item_qty)
                from model_orders mo
                left join model_orders_items moi on mo.id = moi.model_orders_id
                left join musteri m on mo.musteri_id = m.id
                left join models_list ml on moi.models_list_id = ml.id
                left join models_variations mv on moi.model_var_id = mv.id
                left join color_pantone cp on mv.color_pantone_id = cp.id
                inner join model_orders_items_acs moia on moi.id = moia.model_orders_items_id
                left join bichuv_acs ba on moia.bichuv_acs_id = ba.id
                left join bichuv_acs_property bap on ba.property_id = bap.id
                where mo.status > 2 GROUP BY ba.id, moi.id;";
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        return $results;
    }

    public function getModelArticles($no_html = false,$telegram=null)
    {
        if($telegram){
            $field = "CONCAT('#',ml.article)";
        } elseif ($no_html) {
            $field = 'ml.article';
        }else {
            $field = "CONCAT('<code>',ml.article,'</code>')";
        }
        $sql = "select GROUP_CONCAT(DISTINCT %s SEPARATOR ', ') as model
                from model_orders mo
                left join model_orders_items moi on mo.id = moi.model_orders_id
                left join models_list ml on moi.models_list_id = ml.id WHERE mo.id = %d;";
        $sql = sprintf($sql, $field, $this->id);
        $results = Yii::$app->db->createCommand($sql)->queryScalar();
        if($results){
            return $results;
        }
        return null;
    }

    // ajax uchun
    public function getSize($model, $select, $column, $id)
    {
        if(!empty($id)) {
            $result = $model::find()
                ->select($select)
                ->where(['LIKE', $column, $id])
                ->asArray()
                ->all();

            if (!empty($result)) {
                return $result;
            } else {
                return false;
            }
        }
    }


    public function getArrayMapModel($model, $col1=null, $col2)
    {
        if(!empty($model) && !empty($col2)){
            if($col1 === null){

            }
            else{
                if($col2 === 'bichuv'){
                    $result = ArrayHelper::map($model::find()->all(), $col1, function($m){
                        return $m['name'].' '.$m['sku'];
                    });
                }
                elseif($col2 === 'toq_acc'){
                    $result = ArrayHelper::map($model::find()->where(['type' => 2])->all(), $col1, function($m){
                        return $m['name'];
                    });
                }
                elseif($col2 === 'color_pantone'){
                    $result = ArrayHelper::map($model::find()->all(), $col1, function($m){
                        return $m['name'].' '.$m['code'];
                    });
                }
                elseif($col2 === 'toq_material'){
                    $result = ArrayHelper::map($model::find()->where(['type' => 1])->all(), $col1, function($m){
                        return $m['name'].' '.$m['code'];
                    });
                }
                elseif($col2 === 'model_items'){
                    $result = ArrayHelper::map($model::find()->all(), $col1, function($m){
                        return $m['name'].' '.$m['article'];
                    });
                }
                elseif($col2 === 'size'){
                    $result = ArrayHelper::map($model::find()->all(), $col1, function($m){
                        return $m['code'];
                    });
                }
                elseif($col2 === 'models_variations'){
                    $result = ArrayHelper::map($model::find()->all(), $col1, function ($m){
                        return $m['name'];
                    });
                }
            }
            return $result;
        }
    }

    /**
     * @params $id
     * Modellar variantini olib ko'rish uchun ajax
     * */
    public function getModelVar($id)
    {
        $variations = "
            SELECT
                cp.name, cp.code, cp.id,
                wc.color_palitra_code, wc.color_name, wc.color_code,
                mv.id as mv_id, mv.model_list_id, mv.color_pantone_id, mv.wms_color_id
            FROM
                models_variations mv
            LEFT JOIN wms_color wc ON mv.wms_color_id = wc.id
            LEFT JOIN color_pantone cp ON cp.id = wc.color_pantone_id
            WHERE mv.model_list_id = {$id}        
        ";
        $query = Yii::$app->db->createCommand($variations)->queryAll();

        if($query){
            return $query;
        }
        else{
            return false;
        }

    }

    // Zakaz boyicha malumotlarni saqlash
    public function getSaveAll($data, $modelsPechat)
    {
        if(!empty($data)){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $doc = $data['ModelOrders'];
                /** Masul shaxslarni olish */
                $responsible = $doc['responsible'];
                $model = new ModelOrders();
                $model->musteri_id = $doc['musteri_id'];
                $model->doc_number = $doc['doc_number'];
                $model->prepayment = $doc['prepayment'];
                $model->add_info = $doc['add_info'];
                $model->sum_item_qty = $doc['sum_item_qty'];
                $model->orders_status = ModelOrders::STATUS_ACTIVE;
                if($model->save()){
                    $saved = true;
                    /** Masul shaxslarni saqlash */
                    if(!empty($responsible)){
                        foreach($responsible as $item){
                            $responsibles = new ModelOrdersResponsible();
                            $responsibles->model_orders_id = $model->id;
                            $responsibles->users_id = $item;
                            if($responsibles->save()){
                                $saved = true;
                                unset($responsibles);
                            }
                            else{
                                new Telegram([
                                    'text' => '#WBM #model_responsible_errors ' . json_encode($responsibles->getErrors()),
                                    'module' => 'Base',
                                    'controlller' => 'ModelOrders',
                                ]);
                                $saved = false;
                                break;
                            }
                        }
                    }
                }
                else{
                    new Telegram([
                        'text' => '#WBM #model_orders_errors ' . json_encode($model->getErrors()),
                        'module' => 'Base',
                        'controlller' => 'ModelOrders',
                    ]);
                    $saved = false;
                }
                // buyurtmani document id
                $id = $model->id;
                $finish_no = 1;
                $res = ModelOrdersVariations::find()
                    ->where(['model_orders_id' => $id])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();

                if(!empty($res)){
                    $finish_no = $res['variant_no']++;
                }
                $agreement = new ModelOrdersVariations();
                $agreement->model_orders_id = $id;
                $agreement->variant_no = $finish_no;
                $agreement->status = ModelOrders::STATUS_ACTIVE;
                if($agreement->save() && $saved){
                    $saved = true;
                }
                else{
                    new Telegram([
                        'text' => '#WBM #model_orders_agrement_errors ' . json_encode($agreement->getErrors()),
                        'module' => 'Base',
                        'controlller' => 'ModelOrders',
                    ]);
                    $saved = false;
                }
                $items = $data['ModelOrdersItems'];
                $size = $data['ModelOrdersItemsSize'];
                $acs = $data['ModelOrdersItemsAcs'];
                $toquvacs = $data['ModelOrdersItemsToquvAcs'];
                $materials = $data['ModelOrdersItemsMaterial'];
                $pechat = $data['ModelOrdersItemsPechat'];
                $naqsh = $data['ModelOrdersNaqsh'];
                if(empty($items)){
                    new Telegram([
                        'text' => '#WBM #Empty_items #model_orders_errors ' . json_encode($items),
                        'module' => 'Base',
                        'controlller' => 'ModelOrders',
                    ]);
                    return false;
                }

                if($saved)
                {
                    $saved = false;
                    $mItems = new ModelOrdersItems();
                    $mItems->setAttributes([
                        'models_list_id' => $items['models_list_id'],
                        'models_list_info' => $items['models_list_info'],
                        'model_orders_id' => $id,
                        'model_var_id' => $items['model_var_id'],
                        'model_var_info' => $items['model_var_info'],
                        'add_info' => $items['add_info'],
                        'status' => ModelOrders::STATUS_ACTIVE,
                        'load_date' => $items['load_date'],
                        'price' => $items['price'],
                        'price_add_info' => $items['price_add_info'],
                        'model_orders_variations_id' => $agreement->id,
                        'size_collections_id' => $items['size_collections_id'],
                        'assorti_count' => $items['assorti_count'],
                        'sum_item_qty' => $items['sum_item_qty'],
                    ]);
                    if($mItems->save()){
                        /** Fayllarni salqash modellar uchun */
                        if(!empty($data['ModelOrdersItems']['files'])){
                            $i = 0;
                            foreach ($data['ModelOrdersItems']['files'] as $k => $v){
                                $attachment = new Attachments();
                                $attachment->setAttributes([
                                    'path' => $v,
                                    'status' => Attachments::STATUS_ACTIVE,
                                ]);
                                if($attachment->save()){
                                    $modelAttachments = new ModelOrdersAttachmentRelations();
                                    $modelAttachments->setAttributes([
                                        'attachments_id' => $attachment->id,
                                        'model_orders_items_id' => $mItems->id,
                                        'status' => ModelOrdersAttachmentRelations::STATUS_ACTIVE,
                                    ]);
                                    if($modelAttachments->save()){
                                        $saved = true;
                                        unset($modelAttachments);
                                    }
                                    else{
                                        new Telegram([
                                            'text' => '#WBM #model_attachments_errors ' . json_encode($modelAttachments->getErrors()),
                                            'module' => 'Base',
                                            'controlller' => 'ModelOrders',
                                        ]);
                                        $saved = false;
                                        break;
                                    }
                                    $saved = true;
                                    unset($attachment);
                                }
                                else{
                                    new Telegram([
                                        'text' => '#WBM #attachment_errors ' . json_encode($attachment->getErrors()),
                                        'module' => 'Base',
                                        'controlller' => 'ModelOrders',
                                    ]);
                                    $saved = false;
                                    break;
                                }
                                $i++;
                            }
                        }
                        /** Modellarni o'lchamini hisoblash */
                        foreach ($size as $item){
                                if(!empty($item['assorti_count'])){
                                    $itemsSize = new ModelOrdersItemsSize();
                                    $itemsSize->setAttributes([
                                        'model_orders_items_id' => $mItems->id,
                                        'count' => $item['count'],
                                        'assorti_count' => $item['assorti_count'],
                                        'size_id' => $item['size_id'],
                                        'model_orders_id' => $id,
                                        'add_info' => $item['add_info']
                                    ]);
                                    if($itemsSize->save()){
                                        $saved = true;
                                        unset($itemsSize);
                                    }
                                    else{
                                        new Telegram([
                                            'text' => '#WBM #model_orders_items_size_errors ' . json_encode($itemsSize->getErrors()),
                                            'module' => 'Base',
                                            'controlller' => 'ModelOrders',
                                        ]);
                                        $saved = false;
                                        break;
                                    }
                                }
                            }

                        /** Toquv Accessorylarini saqlash*/
                        foreach ($toquvacs as $item){
                            $toquvAcsAttributes = [
                                'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                'wms_color_id' => $item['wms_color_id'],
                                'pus_fine_id' => $item['pus_fine_id'],
                                'wms_desen_id' => $item['wms_desen_id'],
                                'en' => $item['en'],
                                'gramaj' => $item['gramaj'],
                                'type' => ToquvRawMaterials::ACS,
                            ];
                            $wmsToquvAcs = WmsMatoInfo::saveAndGetId($toquvAcsAttributes, WmsMatoInfo::SCENARIO_MODEL_ACCESSORY);
                            $toquvAcs = new ModelOrdersItemsToquvAcs();
                            if($wmsToquvAcs){
                                $toquvAcs->setAttributes([
                                    'model_orders_id' => $id,
                                    'model_orders_items_id' => $mItems->id,
                                    'wms_mato_info_id' => $wmsToquvAcs,
                                    'count' => $item['count'],
                                    'status' => ModelOrdersItemsToquvAcs::STATUS_ACTIVE
                                ]);
                                if($toquvAcs->save()){
                                    $saved = true;
                                    unset($toquvAcs);
                                }
                                else{
                                    new Telegram([
                                        'text' => '#WBM #model_orders_toquv_acs_errors ' . json_encode($toquvAcs->getErrors()),
                                        'module' => 'Base',
                                        'controlller' => 'ModelOrders',
                                    ]);
                                    $saved = false;
                                    break;
                                }
                            }
                        }

                        /** Materillarni saqlash */
                        foreach($materials as $item){
                            $matoInfoAttributes = [
                                'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                'wms_color_id' => $item['wms_color_id'],
                                'pus_fine_id' => $item['pus_fine_id'],
                                'wms_desen_id' => $item['wms_desen_id'],
                                'en' => $item['en'],
                                'gramaj' => $item['gramaj'],
                                'type' => ToquvRawMaterials::MATO,
                            ];
                            $wmsMatoInfoId = WmsMatoInfo::saveAndGetId($matoInfoAttributes, WmsMatoInfo::SCENARIO_MODEL_MATERIAL);
                            $saved = $saved && $wmsMatoInfoId;
                            if (!$saved) {
                                new Telegram([
                                    'text' => '#WBM #model_orders_mato_info_errors ' . json_encode($matoInfoAttributes),
                                    'module' => 'Base',
                                    'controlller' => 'ModelOrders',
                                ]);
                                Yii::debug('mato info saqlanmadi');
                                break;
                            }
                            $mato = new ModelOrdersItemsMaterial();
                            $mato->setAttributes([
                                'model_orders_id' => $id,
                                'model_orders_items_id' => $mItems->id,
                                    'mato_id' => $wmsMatoInfoId,
                                'status' => ModelOrders::STATUS_ACTIVE,
                            ]);

                            if($mato->save(false) && $saved){
                                $saved = true;
                                unset($mato);
                            }
                            else{
                                new Telegram([
                                    'text' => '#WBM #model_orders_items_material_errors ' . json_encode($mato->getErrors()),
                                    'module' => 'Base',
                                    'controlller' => 'ModelOrders',
                                ]);
                                $saved = false;
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Mato saqlanmadi!'));
                                $transaction->rollBack();
                                break;
                            }
                        }

                        /** Pechat malumotlarini saqlash */
                        if($pechat && $saved){
                            if($pechat['attachment_id']){
                                $array = [];
                                $i = 0;
                                /** Pechat fayllarni saqlash */
                                foreach ($pechat['attachment_id'] as $k => $item){
                                    $attach = new Attachments();
                                    $attach->setAttributes([
                                        'path' => $item,
                                        'status' => Attachments::STATUS_ACTIVE,
                                    ]);
                                    if($attach->save()){
                                        $array[$i] = $attach->id;
                                        $saved = true;
                                        unset($attach);
                                    }else{
                                        $saved = false;
                                    }
                                    $i++;
                                }
                                $i = 0;
                                /** Pechat malumotlarini saqlash*/
                                foreach($pechat as $k => $item){
                                    if(is_int($k)){
                                        $modelPechats = new ModelOrdersItemsPechat();
                                        $modelPechats->setAttributes([
                                            'model_orders_id' => $id,
                                            'model_orders_items_id' => $mItems->id,
                                            'attachment_id' => $array[$i],
                                            'name' => $item['name'],
                                            'width' => $item['width'],
                                            'height' => $item['height'],
                                            'status' => ModelOrdersItemsPechat::STATUS_ACTIVE,
                                        ]);
                                        if($modelPechats->save() && $saved){
                                            $saved = true;
                                            unset($modelPechats);
                                        }
                                        else{
                                            $saved = false;
                                            break;
                                        }
                                        $i++;
                                    }
                                }
                            }
                        }

                        /** Naqsh malumotlarini saqlash */
                        if($naqsh && $saved){
                            if($naqsh['attachment_id']){
                                $naqsh_array = [];
                                $i = 0;
                                /** Naqsh rasmlarini saqlash */
                                foreach ($naqsh['attachment_id'] as $k => $item){
                                    $attach = new Attachments();
                                    $attach->setAttributes([
                                        'path' => $item,
                                        'status' => Attachments::STATUS_ACTIVE,
                                    ]);
                                    if($attach->save()){
                                        $naqsh_array[$i] = $attach->id;
                                        $saved = true;
                                        unset($attach);
                                    }else{
                                        $saved = false;
                                    }
                                    $i++;
                                }
                                $i = 0;
                                /** Naqsh malumotlarini salqash*/
                                foreach($naqsh as $k => $item){
                                    if(is_int($k)){
                                        $modelsNaqsh = new ModelOrdersNaqsh();
                                        $modelsNaqsh->setAttributes([
                                            'model_orders_id' => $id,
                                            'model_orders_items_id' => $mItems->id,
                                            'attachment_id' => $naqsh_array[$i],
                                            'name' => $item['name'],
                                            'width' => $item['width'],
                                            'height' => $item['height'],
                                            'status' => ModelOrdersNaqsh::STATUS_ACTIVE,
                                        ]);
                                        if($modelsNaqsh->save() && $saved){
                                            $saved = true;
                                            unset($modelsNaqsh);
                                        }
                                        else{
                                            $saved = false;
                                            break;
                                        }
                                        $i++;
                                    }
                                }
                            }
                        }

                        /** Accessory larini saqlash */
                        foreach ($acs as $item){
                            $Acs = new ModelOrdersItemsAcs();
                            $Acs->models_orders_id = $id;
                            $Acs->model_orders_items_id = $mItems->id;
                            $Acs->bichuv_acs_id = $item['bichuv_acs_id'];
                            $Acs->qty = $item['qty'];
                            $Acs->add_info = $item['add_info'];
                            $Acs->application_part = $item['application_part'];
                            $Acs->status = BichuvAcs::STATUS_ACTIVE;
                            if($Acs->save() && $saved){
                                $saved = true;
                                unset($Acs);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }

                        unset($mItems);
                    }
                    else{
                        $saved = false;
                    }
                    if($saved){
                        $transaction->commit();
                        return $model->id;
                    }
                    else{
                        $transaction->rollBack();
                        return false;
                    }
                }

            }
            catch(\Exception $e){
                Yii::info('Error Data '.$e->getMessage(),'save');
            }
        }
        else{
            return false;
        }
    }

    // Yangilash
    public function getUpdateAll($data, $ordersId, $itemsId)
    {
        if(!empty($data)){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                /** ModelOrders ID*/
                $id = $ordersId;
                /** ModelOrdersItems ID*/
                $itemId = $itemsId;

                $items = $data['ModelOrdersItems'];
                $size = $data['ModelOrdersItemsSize'];
                $acs = $data['ModelOrdersItemsAcs'];
                $toquvacs = $data['ModelOrdersItemsToquvAcs'];
                $materials = $data['ModelOrdersItemsMaterial'];
                $pechat = $data['ModelOrdersItemsPechat'];
                $naqsh = $data['ModelOrdersNaqsh'];


                /** Buyurtma variation id sini olish */
                $variation = ModelOrdersItems::findOne($itemId);
                $agreement = $variation->model_orders_variations_id;

                $saved = true;
                if(empty($itemId)) return false;
                if($saved)
                {
                    ModelOrdersItemsMaterial::deleteAll(['model_orders_items_id' => $itemId]);
                    ModelOrdersItemsToquvAcs::deleteAll(['model_orders_items_id' => $itemId]);
                    ModelOrdersItemsSize::deleteAll(['model_orders_items_id' => $itemId]);
                    ModelOrdersItemsPechat::deleteAll(['model_orders_items_id' => $itemId]);
                    ModelOrdersNaqsh::deleteAll(['model_orders_items_id' => $itemId]);
                    ModelOrdersItemsAcs::deleteAll(['model_orders_items_id' => $itemId]);
                    ModelOrdersItems::deleteAll(['id' => $itemId]);
                    /** ModelOrdersItems ni yangidan create qilish */
                    $mItems = new ModelOrdersItems();
                    $mItems->setAttributes([
                        'model_orders_id' => $ordersId,
                        'models_list_id' => $items['models_list_id'],
                        'models_list_info' => $items['models_list_info'],
                        'model_var_id' => $items['model_var_id'],
                        'model_var_info' => $items['model_var_info'],
                        'add_info' => $items['add_info'],
                        'status' => ModelOrders::STATUS_ACTIVE,
                        'load_date' => $items['load_date'],
                        'price' => $items['price'],
                        'price_add_info' => $items['price_add_info'],
                        'model_orders_variations_id' => $agreement,
                        'size_collections_id' => $items['size_collections_id'],
                        'assorti_count' => $items['assorti_count'],
                        'sum_item_qty' => $items['sum_item_qty'],
                    ]);
                    /** ModelOrdersItems Save*/
                    if($mItems->save() && $saved){
                        /** model rasmlarini yuklash */
                        $imgModel = $data['ModelOrdersItems']['files'];
                        if($imgModel != null){
                            foreach ($imgModel as $k => $v){
                                $attachment = new Attachments();
                                $attachment->setAttributes([
                                    'path' => $v,
                                    'status' => Attachments::STATUS_ACTIVE,
                                ]);
                                if($attachment->save() && $saved){
                                    $modelAttachments = new ModelOrdersAttachmentRelations();
                                    $modelAttachments->setAttributes([
                                        'attachments_id' => $attachment->id,
                                        'model_orders_items_id' => $mItems->id,
                                        'status' => ModelOrdersAttachmentRelations::STATUS_ACTIVE,
                                    ]);
                                    if($modelAttachments->save() && $saved){
                                        $saved = true;
                                        unset($modelAttachments);
                                    }
                                    else{
                                        $saved = false;
                                        Yii::info('error message ModelOrdersAttachmentsRelations ga saqlanmadi!');
                                        break;
                                    }
                                    $saved = true;
                                    unset($attachment);
                                }
                                else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                        /** modellarni o'lchamlarini saqlash */
                        foreach ($size as $item){
                            $itemsSize = new ModelOrdersItemsSize();
                            $itemsSize->setAttributes([
                                'model_orders_items_id' => $mItems->id,
                                'count' => $item['count'],
                                'assorti_count' => $item['assorti_count'],
                                'size_id' => $item['size_id'],
                                'model_orders_id' => $ordersId,
                                'add_info' => $item['add_info']
                            ]);
                            if($itemsSize->save()){
                                $saved = true;
                                unset($itemsSize);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }

                        /** Toquv Accessorylarini saqlash*/
                        foreach ($toquvacs as $item){
                            $item['type'] = ToquvRawMaterials::ACS;
                            $toquvAcsAttributes = [
                                'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                'wms_color_id' => $item['wms_color_id'],
                                'pus_fine_id' => $item['pus_fine_id'],
                                'wms_desen_id' => $item['wms_desen_id'],
                                'en' => $item['en'],
                                'gramaj' => $item['gramaj'],
                                'type' => ToquvRawMaterials::ACS,
                            ];
                            $wmsToquvAcs = WmsMatoInfo::saveAndGetId($toquvAcsAttributes, WmsMatoInfo::SCENARIO_MODEL_ACCESSORY);
                            
                            if($wmsToquvAcs){
                                $toquvAcs = new ModelOrdersItemsToquvAcs();
                                $toquvAcs->setAttributes([
                                    'model_orders_id' => $id,
                                    'model_orders_items_id' => $mItems->id,
                                    'wms_mato_info_id' => $wmsToquvAcs,
                                    'count' => $item['count'],
                                    'status' => ModelOrdersItemsToquvAcs::STATUS_ACTIVE
                                ]);
                                if($toquvAcs->save()){
                                    $saved = true;
                                    unset($toquvAcs);
                                }
                                else{
                                    new Telegram([
                                        'text' => '#WBM #model_orders_toquv_acs_errors ' . json_encode($toquvAcs->getErrors()),
                                        'module' => 'Base',
                                        'controlller' => 'ModelOrders',
                                    ]);
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                        /** Materillarni saqlash */
                        foreach($materials as $item){
                            $item['type'] = ToquvRawMaterials::MATO;
                            $matoInfoAttributes = [
                                'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                'wms_color_id' => $item['wms_color_id'],
                                'pus_fine_id' => $item['pus_fine_id'],
                                'wms_desen_id' => $item['wms_desen_id'],
                                'en' => $item['en'],
                                'gramaj' => $item['gramaj'],
                                'type' => ToquvRawMaterials::MATO,
                            ];
                            $wmsMatoInfoId = WmsMatoInfo::saveAndGetId($matoInfoAttributes, WmsMatoInfo::SCENARIO_MODEL_MATERIAL);
                            $saved = $saved && $wmsMatoInfoId;

                            if (!$saved) {
                                new Telegram([
                                    'text' => '#WBM #model_orders_mato_info_errors ' . json_encode($matoInfoAttributes),
                                    'module' => 'Base',
                                    'controlller' => 'ModelOrders',
                                ]);
                                Yii::debug('mato info saqlanmadi');
                                break;
                            }
                            $mato = new ModelOrdersItemsMaterial();
                            $mato->setAttributes([
                                'model_orders_id' => $id,
                                'model_orders_items_id' => $mItems->id,
                                'mato_id' => $wmsMatoInfoId,
                                'status' => ModelOrders::STATUS_ACTIVE,
                            ]);

                            if($mato->save(false) && $saved){
                                $saved = true;
                                unset($mato);
                            }
                            else{
                                new Telegram([
                                    'text' => '#WBM #model_orders_items_material_errors ' . json_encode($mato->getErrors()),
                                    'module' => 'Base',
                                    'controlller' => 'ModelOrders',
                                ]);
                                $saved = false;
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Mato saqlanmadi!'));
                                $transaction->rollBack();
                                break;
                            }
                        }
                        /** Pechat malumotlarini saqlash */
                        if($pechat && $saved){
                            $count = count($pechat) - 1;
                            $countImg = count($pechat['attachment_id']);
                            if($count === $countImg){
                                if($pechat['attachment_id']){
                                    $array = [];
                                    /** Pechat fayllarni saqlash */
                                    foreach ($pechat['attachment_id'] as $k => $item){
                                        if(!empty($item)){
                                            $attach = new Attachments();
                                            $attach->setAttributes([
                                                'path' => $item,
                                                'status' => Attachments::STATUS_ACTIVE,
                                            ]);
                                            if($attach->save()){
                                                $array[$k] = $attach->id;
                                                $saved = true;
                                                unset($attach);
                                            }else{
                                                $saved = false;
                                            }
                                        }
                                    }
                                    /** Pechat malumotlarini saqlash*/
                                    foreach($pechat as $k => $item){
                                        if(is_int($k) && !empty($item)){
                                            $modelPechats = new ModelOrdersItemsPechat();
                                            $modelPechats->setAttributes([
                                                'model_orders_id' => $id,
                                                'model_orders_items_id' => $mItems->id,
                                                'attachment_id' => $array[$k]==null?'':$array[$k],
                                                'name' => $item['name'],
                                                'width' => $item['width'],
                                                'height' => $item['height'],
                                                'status' => ModelOrdersItemsPechat::STATUS_ACTIVE,
                                            ]);

                                            if($modelPechats->save() && $saved){
                                                $saved = true;
                                                unset($modelPechats);
                                            }
                                            else{
                                                $saved = false;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            else{
                                if($pechat['attachment_id']){
                                    $i = 0;
                                    $array = [];
                                    /** Pechat fayllarni saqlash */
                                    foreach ($pechat['attachment_id'] as $k => $item){
                                        if(!empty($item)){
                                            $attach = new Attachments();
                                            $attach->setAttributes([
                                                'path' => $item,
                                                'status' => Attachments::STATUS_ACTIVE,
                                            ]);
                                            if($attach->save()){
                                                $array[$i] = $attach->id;
                                                $saved = true;
                                                unset($attach);
                                            }else{
                                                $saved = false;
                                            }
                                        }
                                        $i++;
                                    }

                                    $n = 0;
                                    /** Pechat malumotlarini saqlash*/
                                    foreach($pechat as $k => $item){
                                        if(is_int($k) && !empty($item)){
                                            $modelPechats = new ModelOrdersItemsPechat();
                                            $modelPechats->setAttributes([
                                                'model_orders_id' => $id,
                                                'model_orders_items_id' => $mItems->id,
                                                'attachment_id' => $array[$n]==null?'':$array[$n],
                                                'name' => $item['name'],
                                                'width' => $item['width'],
                                                'height' => $item['height'],
                                                'status' => ModelOrdersItemsPechat::STATUS_ACTIVE,
                                            ]);

                                            if($modelPechats->save() && $saved){
                                                $saved = true;
                                                unset($modelPechats);
                                            }
                                            else{
                                                $saved = false;
                                                break;
                                            }
                                        }
                                        $n++;
                                    }
                                }
                            }
                        }
                        /** Naqsh malumotlarini saqlash */
                        if($naqsh && $saved){
                            $count = count($naqsh) - 1;
                            $countImg = count($naqsh['attachment_id']);
                            if($count === $countImg){
                                if($naqsh['attachment_id']){
                                    $naqsh_array = [];
                                    /** Naqsh rasmlarini saqlash */
                                    foreach ($naqsh['attachment_id'] as $k => $item){
                                        if(!empty($item)){
                                            $attach = new Attachments();
                                            $attach->setAttributes([
                                                'path' => $item,
                                                'status' => Attachments::STATUS_ACTIVE,
                                            ]);
                                            if($attach->save()){
                                                $naqsh_array[$k] = $attach->id;
                                                $saved = true;
                                                unset($attach);
                                            }else{
                                                $saved = false;
                                            }
                                        }
                                    }
                                    /** Naqsh malumotlarini salqash*/
                                    foreach($naqsh as $k => $item){
                                        if(is_int($k)){
                                            $modelsNaqsh = new ModelOrdersNaqsh();
                                            $modelsNaqsh->setAttributes([
                                                'model_orders_id' => $id,
                                                'model_orders_items_id' => $mItems->id,
                                                'attachment_id' => $naqsh_array[$k]==null?'':$naqsh_array[$k],
                                                'name' => $item['name'],
                                                'width' => $item['width'],
                                                'height' => $item['height'],
                                                'status' => ModelOrdersNaqsh::STATUS_ACTIVE,
                                            ]);
                                            if($modelsNaqsh->save() && $saved){
                                                $saved = true;
                                                unset($modelsNaqsh);
                                            }
                                            else{
                                                $saved = false;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            else{
                                if($naqsh['attachment_id']){
                                    $naqsh_array = [];
                                    $i = 0;
                                    /** Naqsh rasmlarini saqlash */
                                    foreach ($naqsh['attachment_id'] as $k => $item){
                                        if(!empty($item)){
                                            $attach = new Attachments();
                                            $attach->setAttributes([
                                                'path' => $item,
                                                'status' => Attachments::STATUS_ACTIVE,
                                            ]);
                                            if($attach->save()){
                                                $naqsh_array[$i] = $attach->id;
                                                $saved = true;
                                                unset($attach);
                                            }else{
                                                $saved = false;
                                            }
                                        }
                                        $i++;
                                    }
                                    $n = 0;
                                    /** Naqsh malumotlarini salqash*/
                                    foreach($naqsh as $k => $item){
                                        if(is_int($k)){
                                            $modelsNaqsh = new ModelOrdersNaqsh();
                                            $modelsNaqsh->setAttributes([
                                                'model_orders_id' => $id,
                                                'model_orders_items_id' => $mItems->id,
                                                'attachment_id' => $naqsh_array[$n]==null?'':$naqsh_array[$n],
                                                'name' => $item['name'],
                                                'width' => $item['width'],
                                                'height' => $item['height'],
                                                'status' => ModelOrdersNaqsh::STATUS_ACTIVE,
                                            ]);
                                            if($modelsNaqsh->save() && $saved){
                                                $saved = true;
                                                unset($modelsNaqsh);
                                            }
                                            else{
                                                $saved = false;
                                                break;
                                            }
                                        }
                                        $n++;
                                    }
                                }
                            }
                        }

                        foreach ($acs as $item){
                            $Acs = new ModelOrdersItemsAcs();
                            $Acs->models_orders_id = $ordersId;
                            $Acs->model_orders_items_id = $mItems->id;
                            $Acs->bichuv_acs_id = $item['bichuv_acs_id'];
                            $Acs->qty = $item['qty'];
                            $Acs->add_info = $item['add_info'];
                            $Acs->application_part = $item['application_part'];
                            $Acs->status = ModelOrdersItemsAcs::STATUS_ACTIVE;
                            if($Acs->save()){
                                $saved = true;
                                unset($Acs);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                        unset($mItems);
                    }
                    else{
                        $saved = false;
                    }
                    if($saved){
                        $transaction->commit();
                        return $ordersId;
                    }
                    else{

                        $transaction->rollBack();
                        return false;
                    }
                }

            }
            catch(\Exception $e){
                Yii::info('Error Data '.$e->getMessage(),'save');
            }
        }
        else{
            return false;
        }
    }

    // copy orqali malumotlarni yaratish
    public function getSaveAllCopy($data, $modelId)
    {
        if(!empty($data)){

            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                // buyurtmani document id
                $id = $modelId;
                // TODO Model_list_id kelmaydigan jarayonni ham ko'rib qo'yish kerek
                /** ModelOrdersVariations da variant nomerini oshirish */
                $finish_no = 1;
                $res = ModelOrdersVariations::find()
                    ->select(['variant_no', 'status'])
                    ->where(['model_orders_id' => $id])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if($res && $res['status'] === 2){
                    $finish_no = ++$res->variant_no;
                }
                else{
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Siz yangi variant yarata olmaysiz! Bu buyurtma varianti mavjud!'));
                    return false;
                }
                $agreement = new ModelOrdersVariations();
                $agreement->model_orders_id = $id;
                $agreement->variant_no = $finish_no;
                $agreement->status = ModelOrders::STATUS_ACTIVE;
                if($agreement->save()){
                    $saved = true;
                }
                else{
                    $saved = false;
                }
                $items = $data['ModelOrdersItems'];
                $size = $data['ModelOrdersItemsSize'];
                $acs = $data['ModelOrdersItemsAcs'];
                $toquvacs = $data['ModelOrdersItemsToquvAcs'];
                $materials = $data['ModelOrdersItemsMaterial'];
                $pechat = $data['ModelOrdersItemsPechat'];
                $naqsh = $data['ModelOrdersNaqsh'];
                $models = new ModelOrdersItems();
                $pechat_obj = new ModelOrdersItemsPechat();
                if(empty($items)) return false;

                if($saved)
                {
                    $saved = false;
                    $mItems = new ModelOrdersItems();
                    $mItems->setAttributes([
                        'model_orders_id' => $id,
                        'models_list_id' => $items['models_list_id'],
                        'models_list_info' => $items['models_list_info'],
                        'model_var_id' => $items['model_var_id'],
                        'model_var_info' => $items['model_var_info'],
                        'add_info' => $items['add_info'],
                        'status' => ModelOrders::STATUS_ACTIVE,
                        'load_date' => $items['load_date'],
                        'price' => $items['price'],
                        'price_add_info' => $items['price_add_info'],
                        'model_orders_variations_id' => $agreement->id,
                        'size_collections_id' => $items['size_collections_id'],
                        'assorti_count' => $items['assorti_count'],
                        'sum_item_qty' => $items['sum_item_qty'],
                    ]);
                    if($mItems->save()){
                        /** Fayllarni salqash modellar uchun */
                        if(!empty($data['ModelOrdersItems']['files'])){
                            $i = 0;
                            foreach ($data['ModelOrdersItems']['files'] as $k => $v){
                                $attachment = new Attachments();
                                $attachment->setAttributes([
                                    'path' => $v,
                                    'status' => Attachments::STATUS_ACTIVE,
                                ]);
                                if($attachment->save()){
                                    $modelAttachments = new ModelOrdersAttachmentRelations();
                                    $modelAttachments->setAttributes([
                                        'attachments_id' => $attachment->id,
                                        'model_orders_items_id' => $mItems->id,
                                        'status' => ModelOrdersAttachmentRelations::STATUS_ACTIVE,
                                    ]);
                                    if($modelAttachments->save()){
                                        $saved = true;
                                        unset($modelAttachments);
                                    }
                                    else{
                                        new Telegram([
                                            'text' => '#WBM #model_attachments_errors ' . json_encode($modelAttachments->getErrors()),
                                            'module' => 'Base',
                                            'controlller' => 'ModelOrders',
                                        ]);
                                        $saved = false;
                                        break;
                                    }
                                    $saved = true;
                                    unset($attachment);
                                }
                                else{
                                    new Telegram([
                                        'text' => '#WBM #attachment_errors ' . json_encode($attachment->getErrors()),
                                        'module' => 'Base',
                                        'controlller' => 'ModelOrders',
                                    ]);
                                    $saved = false;
                                    break;
                                }
                                $i++;
                            }
                        }
                        /** Modellarni o'lchamini hisoblash */
                        foreach ($size as $item){
                            if(!empty($item['assorti_count'])){
                                $itemsSize = new ModelOrdersItemsSize();
                                $itemsSize->setAttributes([
                                    'model_orders_items_id' => $mItems->id,
                                    'count' => $item['count'],
                                    'assorti_count' => $item['assorti_count'],
                                    'size_id' => $item['size_id'],
                                    'model_orders_id' => $id,
                                    'add_info' => $item['add_info']
                                ]);
                                if($itemsSize->save()){
                                    $saved = true;
                                    unset($itemsSize);
                                }
                                else{
                                    new Telegram([
                                        'text' => '#WBM #model_orders_items_size_errors ' . json_encode($itemsSize->getErrors()),
                                        'module' => 'Base',
                                        'controlller' => 'ModelOrders',
                                    ]);
                                    $saved = false;
                                    break;
                                }
                            }
                        }

                        /** Toquv Accessorylarini saqlash*/
                        foreach ($toquvacs as $item){
                            $toquvAcsAttributes = [
                                'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                'wms_color_id' => $item['wms_color_id'],
                                'pus_fine_id' => $item['pus_fine_id'],
                                'wms_desen_id' => $item['wms_desen_id'],
                                'en' => $item['en'],
                                'gramaj' => $item['gramaj'],
                                'type' => ToquvRawMaterials::ACS,
                            ];
                            $wmsToquvAcs = WmsMatoInfo::saveAndGetId($toquvAcsAttributes, WmsMatoInfo::SCENARIO_MODEL_ACCESSORY);
                            $toquvAcs = new ModelOrdersItemsToquvAcs();
                            if($wmsToquvAcs){
                                $toquvAcs->setAttributes([
                                    'model_orders_id' => $id,
                                    'model_orders_items_id' => $mItems->id,
                                    'wms_mato_info_id' => $wmsToquvAcs,
                                    'count' => $item['count'],
                                    'status' => ModelOrdersItemsToquvAcs::STATUS_ACTIVE
                                ]);
                                if($toquvAcs->save()){
                                    $saved = true;
                                    unset($toquvAcs);
                                }
                                else{
                                    new Telegram([
                                        'text' => '#WBM #model_orders_toquv_acs_errors ' . json_encode($toquvAcs->getErrors()),
                                        'module' => 'Base',
                                        'controlller' => 'ModelOrders',
                                    ]);
                                    $saved = false;
                                    break;
                                }
                            }
                        }

                        /** Materillarni saqlash */
                        foreach($materials as $item){
                            $matoInfoAttributes = [
                                'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                'wms_color_id' => $item['wms_color_id'],
                                'pus_fine_id' => $item['pus_fine_id'],
                                'wms_desen_id' => $item['wms_desen_id'],
                                'en' => $item['en'],
                                'gramaj' => $item['gramaj'],
                                'type' => ToquvRawMaterials::MATO,
                            ];
                            $wmsMatoInfoId = WmsMatoInfo::saveAndGetId($matoInfoAttributes, WmsMatoInfo::SCENARIO_MODEL_MATERIAL);
                            $saved = $saved && $wmsMatoInfoId;
                            if (!$saved) {
                                new Telegram([
                                    'text' => '#WBM #model_orders_mato_info_errors ' . json_encode($matoInfoAttributes),
                                    'module' => 'Base',
                                    'controlller' => 'ModelOrders',
                                ]);
                                Yii::debug('mato info saqlanmadi');
                                break;
                            }
                            $mato = new ModelOrdersItemsMaterial();
                            $mato->setAttributes([
                                'model_orders_id' => $id,
                                'model_orders_items_id' => $mItems->id,
                                'mato_id' => $wmsMatoInfoId,
                                'status' => ModelOrders::STATUS_ACTIVE,
                            ]);

                            if($mato->save(false) && $saved){
                                $saved = true;
                                unset($mato);
                            }
                            else{
                                new Telegram([
                                    'text' => '#WBM #model_orders_items_material_errors ' . json_encode($mato->getErrors()),
                                    'module' => 'Base',
                                    'controlller' => 'ModelOrders',
                                ]);
                                $saved = false;
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Mato saqlanmadi!'));
                                $transaction->rollBack();
                                break;
                            }
                        }

                        /** Pechat malumotlarini saqlash */
                        if($pechat && $saved){
                            if($pechat['attachment_id']){
                                $array = [];
                                $i = 0;
                                /** Pechat fayllarni saqlash */
                                foreach ($pechat['attachment_id'] as $k => $item){
                                    $attach = new Attachments();
                                    $attach->setAttributes([
                                        'path' => $item,
                                        'status' => Attachments::STATUS_ACTIVE,
                                    ]);
                                    if($attach->save()){
                                        $array[$i] = $attach->id;
                                        $saved = true;
                                        unset($attach);
                                    }else{
                                        $saved = false;
                                    }
                                    $i++;
                                }
                                $i = 0;
                                /** Pechat malumotlarini saqlash*/
                                foreach($pechat as $k => $item){
                                    if(is_int($k)){
                                        $modelPechats = new ModelOrdersItemsPechat();
                                        $modelPechats->setAttributes([
                                            'model_orders_id' => $id,
                                            'model_orders_items_id' => $mItems->id,
                                            'attachment_id' => $array[$i],
                                            'name' => $item['name'],
                                            'width' => $item['width'],
                                            'height' => $item['height'],
                                            'status' => ModelOrdersItemsPechat::STATUS_ACTIVE,
                                        ]);
                                        if($modelPechats->save() && $saved){
                                            $saved = true;
                                            unset($modelPechats);
                                        }
                                        else{
                                            $saved = false;
                                            break;
                                        }
                                        $i++;
                                    }
                                }
                            }
                        }

                        /** Naqsh malumotlarini saqlash */
                        if($naqsh && $saved){
                            if($naqsh['attachment_id']){
                                $naqsh_array = [];
                                $i = 0;
                                /** Naqsh rasmlarini saqlash */
                                foreach ($naqsh['attachment_id'] as $k => $item){
                                    $attach = new Attachments();
                                    $attach->setAttributes([
                                        'path' => $item,
                                        'status' => Attachments::STATUS_ACTIVE,
                                    ]);
                                    if($attach->save()){
                                        $naqsh_array[$i] = $attach->id;
                                        $saved = true;
                                        unset($attach);
                                    }else{
                                        $saved = false;
                                    }
                                    $i++;
                                }
                                $i = 0;
                                /** Naqsh malumotlarini salqash*/
                                foreach($naqsh as $k => $item){
                                    if(is_int($k)){
                                        $modelsNaqsh = new ModelOrdersNaqsh();
                                        $modelsNaqsh->setAttributes([
                                            'model_orders_id' => $id,
                                            'model_orders_items_id' => $mItems->id,
                                            'attachment_id' => $naqsh_array[$i],
                                            'name' => $item['name'],
                                            'width' => $item['width'],
                                            'height' => $item['height'],
                                            'status' => ModelOrdersNaqsh::STATUS_ACTIVE,
                                        ]);
                                        if($modelsNaqsh->save() && $saved){
                                            $saved = true;
                                            unset($modelsNaqsh);
                                        }
                                        else{
                                            $saved = false;
                                            break;
                                        }
                                        $i++;
                                    }
                                }
                            }
                        }

                        /** Accessory larini saqlash */
                        foreach ($acs as $item){
                            $Acs = new ModelOrdersItemsAcs();
                            $Acs->models_orders_id = $id;
                            $Acs->model_orders_items_id = $mItems->id;
                            $Acs->bichuv_acs_id = $item['bichuv_acs_id'];
                            $Acs->qty = $item['qty'];
                            $Acs->add_info = $item['add_info'];
                            $Acs->application_part = $item['application_part'];
                            $Acs->status = BichuvAcs::STATUS_ACTIVE;
                            if($Acs->save() && $saved){
                                $saved = true;
                                unset($Acs);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }

                        $saved = true;
                        unset($mItems);
                    }
                    else{
                        $saved = false;
                    }
                    if($saved){
                        $transaction->commit();
                        return $id;
                    }
                    else{
                        $transaction->rollBack();
                        return false;
                    }
                }

            }
            catch(\Exception $e){
                Yii::info('Error Data '.$e->getMessage(),'save');
            }
        }
        else{
            return false;
        }
    }

    public function getComments($id)
    {
        if(empty($id))
            return false;
        // comments larni olish
        $modelOrdersComment = ModelOrdersCommentVarRel::find()
            ->where(['model_orders_variations_id' => $id])
            ->asArray()
            ->all();
        if(empty($modelOrdersComment))
            return false;
        // comment id larini yig'ib olish
        $comment_id = [];
        $reason = [];
        foreach ($modelOrdersComment as $item){
            $comment_id[] = $item['model_orders_variations_id'];
            $reason[] = $item['model_orders_comment_id'];
        }
        // Sabablarini ko'rish
        $comment = ModelOrdersComment::find()
            ->where(['in', 'id', $reason])
            ->asArray()
            ->all();
        // guruhlab olish
        $modelOrdersComment = ModelOrdersCommentVarRel::find()
            ->where(['model_orders_variations_id' => $id])
            ->groupBy(['model_orders_variations_id'])
            ->asArray()
            ->all();

        $array = [];
        $array['comment_var_rel'] = $modelOrdersComment;
        $array['comment'] = $comment;

        return $array;
    }

    /**
     * @params $data, $modelId
     * @param $data
     * @param $model
     * @return bool
     */
    public function getSaveVariations($data, $model)
    {

        if(!empty($data)){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                // buyurtmani document id
                $id = $model->id;
                /** $listId Models_list_id sini olish */
                $listId = $data['ModelOrdersItems']['models_list_id'];
                $agreement = ModelOrdersVariations::find()
                    ->where(['model_orders_id' => $id])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();

                $items = $data['ModelOrdersItems'];
                $size = $data['ModelOrdersItemsSize'];
                $acs = $data['ModelOrdersItemsAcs'];
                $toquvacs = $data['ModelOrdersItemsToquvAcs'];
                $materials = $data['ModelOrdersItemsMaterial'];
                $pechat = $data['ModelOrdersItemsPechat'];
                $naqsh = $data['ModelOrdersNaqsh'];

                if(empty($items)) return false;
                $saved = true;
                if($saved)
                {
                    $saved = false;
                    $mItems = new ModelOrdersItems();
                    $mItems->setAttributes([
                        'model_orders_id' => $id,
                        'model_var_id' => $items['model_var_id'],
                        'models_list_id' => $listId,
                        'models_list_info' => $items['models_list_info'],
                        'model_var_info' => $items['model_var_info'],
                        'add_info' => $items['add_info'],
                        'status' => ModelOrders::STATUS_ACTIVE,
                        'load_date' => $items['load_date'],
                        'price' => $items['price'],
                        'price_add_info' => $items['price_add_info'],
                        'model_orders_variations_id' => $agreement->id,
                        'size_collections_id' => $items['size_collections_id'],
                        'assorti_count' => $items['assorti_count'],
                        'sum_item_qty' => $items['sum_item_qty'],
                    ]);

                    if($mItems->save()){
                        if(!empty($data['ModelOrdersItems']['files'])){
                            $i = 0;
                            foreach ($data['ModelOrdersItems']['files'] as $k => $v){
                                $attachment = new Attachments();
                                $attachment->setAttributes([
                                    'path' => $v,
                                    'status' => Attachments::STATUS_ACTIVE,
                                ]);
                                if($attachment->save()){
                                    $modelAttachments = new ModelOrdersAttachmentRelations();
                                    $modelAttachments->setAttributes([
                                        'attachments_id' => $attachment->id,
                                        'model_orders_items_id' => $mItems->id,
                                        'status' => ModelOrdersAttachmentRelations::STATUS_ACTIVE,
                                    ]);
                                    if($modelAttachments->save()){
                                        $saved = true;
                                        unset($modelAttachments);
                                    }
                                    else{
                                        $saved = false;
                                        break;
                                    }
                                    $saved = true;
                                    unset($attachment);
                                }
                                else{
                                    $saved = false;
                                    break;
                                }
                                $i++;
                            }
                        }

                        /** Modellarni o'lchamini hisoblash */
                        foreach ($size as $item){
                            if(!empty($item['assorti_count'])){
                                $itemsSize = new ModelOrdersItemsSize();
                                $itemsSize->setAttributes([
                                    'model_orders_items_id' => $mItems->id,
                                    'count' => $item['count'],
                                    'assorti_count' => $item['assorti_count'],
                                    'size_id' => $item['size_id'],
                                    'model_orders_id' => $id,
                                    'add_info' => $item['add_info']
                                ]);
                                if($itemsSize->save()){
                                    $saved = true;
                                    unset($itemsSize);
                                }
                                else{
                                    new Telegram([
                                        'text' => '#WBM #model_orders_items_size_errors ' . json_encode($itemsSize->getErrors()),
                                        'module' => 'Base',
                                        'controlller' => 'ModelOrders',
                                    ]);
                                    $saved = false;
                                    break;
                                }
                            }
                        }

                        /** Toquv Accessorylarini saqlash*/
                        foreach ($toquvacs as $item){
                            $toquvAcsAttributes = [
                                'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                'wms_color_id' => $item['wms_color_id'],
                                'pus_fine_id' => $item['pus_fine_id'],
                                'wms_desen_id' => $item['wms_desen_id'],
                                'en' => $item['en'],
                                'gramaj' => $item['gramaj'],
                                'type' => ToquvRawMaterials::ACS,
                            ];
                            $wmsToquvAcs = WmsMatoInfo::saveAndGetId($toquvAcsAttributes, WmsMatoInfo::SCENARIO_MODEL_ACCESSORY);
                            $toquvAcs = new ModelOrdersItemsToquvAcs();
                            if($wmsToquvAcs){
                                $toquvAcs->setAttributes([
                                    'model_orders_id' => $id,
                                    'model_orders_items_id' => $mItems->id,
                                    'wms_mato_info_id' => $wmsToquvAcs,
                                    'count' => $item['count'],
                                    'status' => ModelOrdersItemsToquvAcs::STATUS_ACTIVE
                                ]);
                                if($toquvAcs->save()){
                                    $saved = true;
                                    unset($toquvAcs);
                                }
                                else{
                                    new Telegram([
                                        'text' => '#WBM #model_orders_toquv_acs_errors ' . json_encode($toquvAcs->getErrors()),
                                        'module' => 'Base',
                                        'controlller' => 'ModelOrders',
                                    ]);
                                    $saved = false;
                                    break;
                                }
                            }
                        }

                        /** Materillarni saqlash */
                        foreach($materials as $item){
                            $matoInfoAttributes = [
                                'toquv_raw_materials_id' => $item['toquv_raw_materials_id'],
                                'wms_color_id' => $item['wms_color_id'],
                                'pus_fine_id' => $item['pus_fine_id'],
                                'wms_desen_id' => $item['wms_desen_id'],
                                'en' => $item['en'],
                                'gramaj' => $item['gramaj'],
                                'type' => ToquvRawMaterials::MATO,
                            ];
                            $wmsMatoInfoId = WmsMatoInfo::saveAndGetId($matoInfoAttributes, WmsMatoInfo::SCENARIO_MODEL_MATERIAL);
                            $saved = $saved && $wmsMatoInfoId;
                            if (!$saved) {
                                new Telegram([
                                    'text' => '#WBM #model_orders_mato_info_errors ' . json_encode($matoInfoAttributes),
                                    'module' => 'Base',
                                    'controlller' => 'ModelOrders',
                                ]);
                                Yii::debug('mato info saqlanmadi');
                                break;
                            }
                            $mato = new ModelOrdersItemsMaterial();
                            $mato->setAttributes([
                                'model_orders_id' => $id,
                                'model_orders_items_id' => $mItems->id,
                                'mato_id' => $wmsMatoInfoId,
                                'status' => ModelOrders::STATUS_ACTIVE,
                            ]);

                            if($mato->save(false) && $saved){
                                $saved = true;
                                unset($mato);
                            }
                            else{
                                new Telegram([
                                    'text' => '#WBM #model_orders_items_material_errors ' . json_encode($mato->getErrors()),
                                    'module' => 'Base',
                                    'controlller' => 'ModelOrders',
                                ]);
                                $saved = false;
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Mato saqlanmadi!'));
                                $transaction->rollBack();
                                break;
                            }
                        }

                        /** Pechat malumotlarini saqlash */
                        if($pechat && $saved){
                            if($pechat['attachment_id']){
                                $array = [];
                                $i = 0;
                                /** Pechat fayllarni saqlash */
                                foreach ($pechat['attachment_id'] as $k => $item){
                                    $attach = new Attachments();
                                    $attach->setAttributes([
                                        'path' => $item,
                                        'status' => Attachments::STATUS_ACTIVE,
                                    ]);
                                    if($attach->save()){
                                        $array[$i] = $attach->id;
                                        $saved = true;
                                        unset($attach);
                                    }else{
                                        $saved = false;
                                    }
                                    $i++;
                                }
                                $i = 0;
                                /** Pechat malumotlarini saqlash*/
                                foreach($pechat as $k => $item){
                                    if(is_int($k)){
                                        $modelPechats = new ModelOrdersItemsPechat();
                                        $modelPechats->setAttributes([
                                            'model_orders_id' => $id,
                                            'model_orders_items_id' => $mItems->id,
                                            'attachment_id' => $array[$i],
                                            'name' => $item['name'],
                                            'width' => $item['width'],
                                            'height' => $item['height'],
                                            'status' => ModelOrdersItemsPechat::STATUS_ACTIVE,
                                        ]);
                                        if($modelPechats->save() && $saved){
                                            $saved = true;
                                            unset($modelPechats);
                                        }
                                        else{
                                            $saved = false;
                                            break;
                                        }
                                        $i++;
                                    }
                                }
                            }
                        }

                        /** Naqsh malumotlarini saqlash */
                        if($naqsh && $saved){
                            if($naqsh['attachment_id']){
                                $naqsh_array = [];
                                $i = 0;
                                /** Naqsh rasmlarini saqlash */
                                foreach ($naqsh['attachment_id'] as $k => $item){
                                    $attach = new Attachments();
                                    $attach->setAttributes([
                                        'path' => $item,
                                        'status' => Attachments::STATUS_ACTIVE,
                                    ]);
                                    if($attach->save()){
                                        $naqsh_array[$i] = $attach->id;
                                        $saved = true;
                                        unset($attach);
                                    }else{
                                        $saved = false;
                                    }
                                    $i++;
                                }
                                $i = 0;
                                /** Naqsh malumotlarini salqash*/
                                foreach($naqsh as $k => $item){
                                    if(is_int($k)){
                                        $modelsNaqsh = new ModelOrdersNaqsh();
                                        $modelsNaqsh->setAttributes([
                                            'model_orders_id' => $id,
                                            'model_orders_items_id' => $mItems->id,
                                            'attachment_id' => $naqsh_array[$i],
                                            'name' => $item['name'],
                                            'width' => $item['width'],
                                            'height' => $item['height'],
                                            'status' => ModelOrdersNaqsh::STATUS_ACTIVE,
                                        ]);
                                        if($modelsNaqsh->save() && $saved){
                                            $saved = true;
                                            unset($modelsNaqsh);
                                        }
                                        else{
                                            $saved = false;
                                            break;
                                        }
                                        $i++;
                                    }
                                }
                            }
                        }

                        /** Accessory larini saqlash */
                        foreach ($acs as $item){
                            $Acs = new ModelOrdersItemsAcs();
                            $Acs->models_orders_id = $id;
                            $Acs->model_orders_items_id = $mItems->id;
                            $Acs->bichuv_acs_id = $item['bichuv_acs_id'];
                            $Acs->qty = $item['qty'];
                            $Acs->add_info = $item['add_info'];
                            $Acs->application_part = $item['application_part'];
                            $Acs->status = BichuvAcs::STATUS_ACTIVE;
                            if($Acs->save() && $saved){
                                $saved = true;
                                unset($Acs);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }

                        $saved = true;
                        unset($mItems);
                    }
                    else{
                        $saved = false;
                    }
                    if($saved){
                        $transaction->commit();
                        return $id;
                    }
                    else{
                        $transaction->rollBack();
                        return false;
                    }
                }

            }
            catch(\Exception $e){
                Yii::info('Error Data '.$e->getMessage(),'save');
            }
        }
        else{
            return false;
        }
    }

    /**
     * Images get all
     * @param $type
     * @param $id
     * @param null $mId
     * @return array
     * @throws Exception
     */
    public static function getImages($type,$id,$mId = null)
    {
        if($type == 'pechat'){
            $sql = "SELECT
                        mo.id as moid,
                        moi.id as moid,
                        moip.attachment_id,
                        a.*
                    FROM model_orders mo
                    INNER JOIN model_orders_items moi
                    ON mo.id = moi.model_orders_id    
                    INNER JOIN model_orders_items_pechat moip
                    ON moi.id = moip.model_orders_items_id
                    INNER JOIN attachments a
                    ON a.id = moip.attachment_id
                    WHERE mo.id = {$id}
                    ";
        }
        elseif($type == 'naqsh'){
            $sql = "SELECT
                        mo.id as moid,
                        moi.id as moid,
                        mon.attachment_id,
                        a.*
                    FROM model_orders mo
                    INNER JOIN model_orders_items moi
                    ON mo.id = moi.model_orders_id    
                    INNER JOIN model_orders_naqsh mon
                    ON moi.id = mon.model_orders_items_id
                    INNER JOIN attachments a
                    ON a.id = mon.attachment_id
                    WHERE mo.id = {$id}";
        }

        elseif ($type == 'model'){
            $sql = "SELECT
                    mo.id as mo_id,
                    moi.id as moi_id,
                    moar.*, a.*
                FROM model_orders mo
                INNER JOIN model_orders_items moi
                ON mo.id = moi.model_orders_id
                INNER JOIN model_orders_attachment_relations moar
                ON moi.id = moar.model_orders_items_id
                INNER JOIN attachments a
                ON a.id = moar.attachments_id
                WHERE mo.id = {$id}";
        }
        if($mId != null){
            $sql .= " AND moi.id = {$mId}";
        }
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        return $query;

    }

    /**
     * Old Images
     * */
    public function getOldImages($id)
    {
        $attachment_path = null;
        $attachment_id = [];
        $relation = ModelOrdersAttachmentRelations::find()
            ->select('attachments_id')
            ->where(['model_orders_items_id' => $id])
            ->asArray()
            ->all();

        if(!empty($relation)){
            foreach ($relation as $item){
                $attachment_id[] = $item['attachments_id'];
            }
            if($relation){
                $attachment_path = Attachments::find()
                    ->where(['in', 'id', $attachment_id])
                    ->asArray()
                    ->all();
            }
            $path = null;
            foreach ($attachment_path as $item){
                $path .= $item['path'].',';
            }
            $path = rtrim($path, ',');
            return $path;
        }
        else{
            return false;
        }
    }

    /**
     * pechat rasmlarini olish
     */
    public function getPechatImg($modelId, $type)
    {
        if($type == 'pechat')
        {
            $sql = "
                SELECT
                    attachments.*,
                    model_orders_items_pechat.model_orders_id
                FROM
                    attachments
                INNER JOIN model_orders_items_pechat
                ON attachments.id = model_orders_items_pechat.attachment_id
                WHERE model_orders_items_pechat.model_orders_id = {$modelId}
            ";
            $query = Yii::$app->db->createCommand($sql)->queryAll();
            $result = [];
            $str = null;
            foreach ($query as $k => $v){
                $result['pechat_name'][] = $v['name'];
                $result['pechat_path'][] = $v['path'];
                $str .= $v['path'].',';
            }
            $str = rtrim($str, ',');
            $result['pechat_str'] = $str;
            return $result;
        }elseif($type == 'naqsh'){
            $sql = "
                SELECT
                    attachments.*,
                    model_orders_naqsh.model_orders_id
                FROM
                    attachments
                INNER JOIN model_orders_naqsh
                ON attachments.id = model_orders_naqsh.attachment_id
                WHERE model_orders_naqsh.model_orders_id = {$modelId}
            ";
            $query = Yii::$app->db->createCommand($sql)->queryAll();
            $result = [];
            $str = null;
            foreach ($query as $k => $v){
                $result['naqsh_name'][] = $v['name'];
                $result['naqsh_path'][] = $v['path'];
                $str .= $v['path'].',';
            }
            $str = rtrim($str, ',');
            $result['naqsh_str'] = $str;
            return $result;
        }
    }

    /** ModelOrdersItems ni idsini olish */
    public static function getOrdersItemsId($id)
    {
        /** ModelOrdersVariations ni id sini olish */
        $orders_variations_id = ModelOrdersVariations::findOne([
            'model_orders_id' => $id,
            'status' => 1
        ]);

        if(empty($orders_variations_id))
            return null;

        /** ModelOrdersItems ni id sini olish*/
        $items_id = ModelOrdersItems::findOne(['model_orders_variations_id' => $orders_variations_id->id]);

        if(empty($items_id))
            return null;

        /** ModelOrdersItems ni id sini qaytarish */
        return $items_id;
    }

    /** modelOrdersVariationsni base_patters_id sini tekshiradi qolibga biriktirilganligini*/
    public function isGetPatterns($id)
    {
        $model = ModelOrdersVariations::find()
            ->where(['status' => 3])
            ->andWhere(['model_orders_id' => $id])
            ->one();
        if($model['base_patterns_id'] == NULL)
            return true;
        return false;
    }

    /** Biriktirilgan qoliplarni korish */
    public function getPatterns($modelId)
    {
        $sql = "
            SELECT
                model_orders.doc_number, model_orders.id,
                model_orders_variations.model_orders_id, model_orders_variations.variant_no, model_orders_variations.base_patterns_id,
                base_patterns.id, base_patterns.name,
                base_pattern_items.bichuv_detail_type_id, base_pattern_items.base_detail_list_id,
                base_pattern_items.base_pattern_id, base_pattern_items.base_pattern_part_id, base_pattern_items.base_patterns_variant_id,
                base_detail_lists.name as bdl_name,
                base_pattern_part.name as bpp_name,
                base_patterns_variations.variant_no as bpv_vn,
                bichuv_detail_types.name as bdt_name
            FROM
                model_orders
            INNER JOIN 
                model_orders_variations
            ON
                model_orders.id = model_orders_variations.model_orders_id
            INNER JOIN
                base_patterns
            ON
                model_orders_variations.base_patterns_id = base_patterns.id
            INNER JOIN 
                base_pattern_items
            ON
                base_patterns.id = base_pattern_items.base_pattern_id
            INNER JOIN 
                base_detail_lists
            ON
                base_pattern_items.base_detail_list_id = base_detail_lists.id
            INNER JOIN
                base_pattern_part
            ON
                base_pattern_items.base_pattern_part_id = base_pattern_part.id
            INNER JOIN
                base_patterns_variations
            ON
                base_pattern_items.base_patterns_variant_id = base_patterns_variations.id
            INNER JOIN
                bichuv_detail_types
            ON
                base_pattern_items.bichuv_detail_type_id = bichuv_detail_types.id
            WHERE
                    model_orders.id = {$modelId}  
        ";
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        $array = [];
        if(!empty($query)){
            $array['doc_number'] = $query[0]['doc_number'];
            $array['model_orders_id'] = $query[0]['model_orders_id'];
            $array['variant_no'] = $query[0]['variant_no'];
            $array['base_patterns_id'] = $query[0]['base_patterns_id'];
            $array['pattern_name'] = $query[0]['name'];
            $array['base_patterns_variation_no'] = $query[0]['bpv_vn'];

            foreach($query as $k => $v){
                $array['patterns_items']['base_pattern_part'][] = $v['bpp_name'];
                $array['patterns_items']['base_detail_list'][] = $v['bdl_name'];
                $array['patterns_items']['bichuv_detail_type_name'][] = $v['bdt_name'];
            }
        }

        return $array;
    }

    /** Biriktirilgan qoliplarni fayllarini ko'rish*/
    public function getPatternsFiles($id)
    {
        $sql = "
            SELECT
                model_orders.id,
                model_orders_variations.model_orders_id,
                base_patterns.id,
                base_pattern_rel_attachment.attachment_id,
                attachments.*
            FROM
                model_orders
            INNER JOIN 
                model_orders_variations
            ON
                model_orders.id = model_orders_variations.model_orders_id
            INNER JOIN
                base_patterns
            ON
                model_orders_variations.base_patterns_id = base_patterns.id
            INNER JOIN
                base_pattern_rel_attachment
            ON
                base_patterns.id = base_pattern_rel_attachment.base_pattern_id
            INNER JOIN
                attachments
            ON
                base_pattern_rel_attachment.attachment_id = attachments.id
            WHERE
                model_orders.id = {$id}
        ";
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($query))
            return $query;
        return false;
    }

    /** MiniPostal malumotlarini olib kelish */
    public function getMiniPostal($id)
    {
        $sql = "
            SELECT
                model_orders.id,
                model_orders_variations.base_patterns_id,
                base_patterns.id,
                base_pattern_mini_postal.loss,
                base_pattern_mini_postal_sizes.*,
                size.*
            FROM 
                model_orders
            INNER JOIN
                model_orders_variations
            ON
                model_orders.id = model_orders_variations.model_orders_id
            INNER JOIN
                base_patterns
            ON
                model_orders_variations.base_patterns_id = base_patterns.id
            INNER JOIN
                base_pattern_mini_postal
            ON
                base_patterns.id = base_pattern_mini_postal.base_patterns_id
            INNER JOIN
                base_pattern_mini_postal_sizes
            ON
                base_pattern_mini_postal.id = base_pattern_mini_postal_sizes.base_pattern_mini_postal_id
            INNER JOIN
                size
            ON
                base_pattern_mini_postal_sizes.size_id = size.id
            WHERE 
                model_orders.id = {$id}
        ";
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($query))
            return $query;
        return false;
    }

    /** Project qismida model yaratilganligini tekshriish*/
    public function isModelLists($id, $status=3, $type=1)
    {
        if($type==1){
            $isModelsList = ModelOrdersVariations::find()
                ->where(['model_orders_id' => $id, 'status' => $status])
                ->one();
            if($isModelsList['models_list_id'] === null){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            $isModelsList = ModelOrdersVariations::find()
                ->where(['model_orders_id' => $id, 'status' => $status])
                ->one();
            return $isModelsList;
        }
    }

    /** HrEmployee dan olish*/
    public function getHrEmployee($arg)
    {
        $array = [];
        foreach($arg as $row){
            $array[] = $row['users_id'];
        }
        $result = HrEmployee::find()
            ->where(['in', 'id', $array])
            ->asArray()
            ->all();
        $str = '';
        if(!empty($result)){
            foreach($result as $key => $item){
                $str .= $item['fish'].', ';
            }
            $str = rtrim($str,', ');
        }
        return $str;
    }

    /** Materiallarni olish modellarni tanlaganda */
    public function getModelRawMaterials($modelListId)
    {
        if(!empty($modelListId)){
            $sql = "
                SELECT
                    models_raw_materials.rm_id,
                    toquv_raw_materials.name as trm_name, toquv_raw_materials.code as trmcode,
                    raw_material_type.name as rmtname,   
                    wms_mato_info.wms_color_id, wms_mato_info.pus_fine_id, wms_mato_info.wms_desen_id, toquv_raw_materials.id as toquv_raw_materials_id,
                    wms_color.color_name, wms_color.color_code,
                    wms_desen.name as wd_name, wms_desen.code,
                    toquv_pus_fine.name as tpf_name,
                    toquv_pus_fine.id as tpf_id,
                    toquv_ne.name as tn_name,
                    raw_material_type.name as rmt_name,
                    cp.name as cpname, cp.code as cpcode,
                    wms_desen.code as wdcode, wbt.name as wbtname
                FROM
                    models_raw_materials
                LEFT JOIN
                    toquv_raw_materials
                ON
                    models_raw_materials.rm_id = toquv_raw_materials.id
                LEFT JOIN
                    wms_mato_info
                ON
                    toquv_raw_materials.id = wms_mato_info.toquv_raw_materials_id
                LEFT JOIN
                    wms_color
                ON
                    wms_mato_info.wms_color_id = wms_color.id
                LEFT JOIN
                    wms_desen
                ON	
                    wms_mato_info.wms_desen_id = wms_desen.id
                LEFT JOIN 
                    wms_baski_type wbt
                ON
                    wbt.id = wms_desen.wms_baski_type_id
                LEFT JOIN
                    toquv_pus_fine
                ON
                    wms_mato_info.pus_fine_id = toquv_pus_fine.id
                LEFT JOIN
                    toquv_ne
                ON
                    wms_mato_info.ne_id = toquv_ne.id
                LEFT JOIN
                    raw_material_type
                ON
                    wms_mato_info.raw_material_type_id = raw_material_type.id
                LEFT JOIN 
                    color_pantone cp
                ON 
                    cp.id = wms_color.color_pantone_id
                WHERE
                    models_raw_materials.model_list_id = {$modelListId}
                GROUP BY
                    toquv_raw_materials.name
            ";
            $query = Yii::$app->db->createCommand($sql)->queryAll();
            if(!empty($query)){
                return $query;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    /** Project qismi uchun bichuv acc larini olib kelish */
    public function getBichuvAcc($modelsListId)
    {
        if(!empty($modelsListId)){
            $acc = "
                SELECT 
                    bichuv_acs.sku, bichuv_acs.name,
                    models_acs.bichuv_acs_id, models_acs.model_list_id
                FROM
                    bichuv_acs
                INNER JOIN 
                    models_acs
                ON 
                    bichuv_acs.id = models_acs.bichuv_acs_id
                WHERE 
                    models_acs.model_list_id = {$modelsListId}               
            ";
            $query = Yii::$app->db->createCommand($acc)->queryAll();
            if(!empty($query)){
                return $query;
            }
            else
                return false;
        }
        else
            return false;
    }

    /** Modellarni tanlash da toquv acc larni olish */
    public function getToquvAcc($modelsListId)
    {
        $type = ToquvRawMaterials::ACS;
        if(!empty($modelsListId)){
            $toquvAcc = "
                SELECT 
                    models_toquv_acs.wms_mato_info_id,
                    wms_mato_info.wms_desen_id, wms_mato_info.toquv_raw_materials_id, wms_mato_info.wms_color_id,
                    wms_mato_info.pus_fine_id, wms_mato_info.en, wms_mato_info.gramaj,
                    wms_color.color_code, wms_color.id as wc_id, wms_color.color_name, wms_color.color_pantone_id,
                    wms_desen.name as wdname, wms_desen.id as wd_id, wms_desen.code, wms_desen.wms_baski_type_id,
                    pus_fine.name as pfname, pus_fine.id as pf_id,
                    wms_baski_type.name as wbtname,
                    color_pantone.name as cpname, color_pantone.code as cpcode,
                    toquv_raw_materials.name, toquv_raw_materials.code
                FROM
                    models_toquv_acs
                LEFT JOIN wms_mato_info ON wms_mato_info.id = models_toquv_acs.wms_mato_info_id
                LEFT JOIN wms_color ON wms_color.id = wms_mato_info.wms_color_id
                left join wms_desen on wms_desen.id = wms_mato_info.wms_desen_id
                left join wms_baski_type on wms_baski_type.id = wms_desen.wms_baski_type_id
                left join pus_fine on pus_fine.id = wms_mato_info.pus_fine_id 
                left join color_pantone on wms_color.color_pantone_id = color_pantone.id
                left join toquv_raw_materials ON wms_mato_info.	toquv_raw_materials_id = toquv_raw_materials.id       
                WHERE
                    models_toquv_acs.models_list_id = {$modelsListId}
                    AND wms_mato_info.type = {$type}
            ";
            $query = Yii::$app->db->createCommand($toquvAcc)->queryAll();
            if(!empty($query)){
                return $query;
            }
            else
                return false;
        }
        else
            return false;
    }

    /**
     * Modellarni variantini olish uchun ishlatiladi
     * @param $id
     * @return array|bool
     * @throws Exception
     */
    public function getModelsVariations($id)
    {
        $sql = "
            SELECT
                mv.model_list_id, wc.color_pantone_id, wc.color_code, wc.color_name, wc.color_palitra_code, wd.name as wdname, wd.code as wdcode, cp.name as cpname, cp.code as cpcode, wbt.name as wbtname, trm.name as trmname, rmt.name as rmtname, trm.code as trmcode, trm.id as toquv_raw_material_id, wc.id as wms_color_id, wd.id as wms_desen_id
            FROM
                models_list ml
            LEFT JOIN models_variations mv ON ml.id = mv.model_list_id
            LEFT JOIN models_raw_materials mrm ON mrm.model_list_id = ml.id
            LEFT JOIN toquv_raw_materials trm ON trm.id = mrm.rm_id
            LEFT JOIN wms_color wc ON wc.id = mv.wms_color_id
            LEFT JOIN wms_desen wd ON wd.id = mv.wms_desen_id
            LEFT JOIN color_pantone cp ON cp.id = wc.color_pantone_id
            LEFT JOIN wms_baski_type wbt ON wbt.id = wd.wms_baski_type_id
            LEFT JOIN raw_material_type rmt ON rmt.id = trm.raw_material_type_id
            WHERE mv.id = {$id}
            GROUP BY trm.name
        ";

        $query = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($query))
            return $query;
        return false;
    }

    /**
     * @param $id
     * */
    public function getVariationsAcc($id)
    {
        if(isset($id) && !empty($id)){
            $sql = "
            SELECT 
	ml.name as mlname, ba.sku, ba.name as baname, ba.id as baid, GROUP_CONCAT(bap.value SEPARATOR ' ') value
            FROM
            models_list ml
            LEFT JOIN models_variations mv ON mv.model_list_id = ml.id
            LEFT JOIN models_acs_variations mav ON mv.id = mav.model_var_id
            LEFT JOIN bichuv_acs ba ON ba.id = mav.bichuv_acs_id
            LEFT JOIN bichuv_acs_properties bap ON bap.bichuv_acs_id = ba.id
            WHERE mav.model_var_id = {$id}
            GROUP BY ba.id
        ";
            $query = Yii::$app->db->createCommand($sql)->queryAll();
            if(!empty($query)){
                return $query;
            }
            return false;
        }
        return false;

    }

    public function getColorPantoneList()
    {
        $sql = "SELECT cp.id,cp.code,cp.r,cp.g,cp.b FROM color_pantone cp
                LEFT JOIN model_orders_planning mop ON mop.color_pantone_id = cp.id
                WHERE mop.model_orders_id = %d
        ";
        $sql = sprintf($sql,$this->id);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $list = [];
        if(!empty($res)){
            foreach ($res as $item) {
                $list[$item['id']] = [
                    'code' => $item['code'],
                    'r' => $item['r'],
                    'g' => $item['g'],
                    'b' => $item['b'],
                ];
            }
        }
        return $list;
    }

    public function getColorBoyoqList()
    {
        $sql = "SELECT c.id,c.color,c.color_id FROM color c
                LEFT JOIN model_orders_planning mop ON mop.color_id = c.id
                WHERE mop.model_orders_id = %d
        ";
        $sql = sprintf($sql,$this->id);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $list = [];
        if(!empty($res)){
            foreach ($res as $item) {
                $list[$item['id']] = [
                    'color_id' => $item['color_id'],
                    'color' => $item['color'],
                ];
            }
        }
        return $list;
    }

    public function getSizeArrayList()
    {
        $sql = "SELECT moi.id, s.id size_id, s.name size,mois.count count FROM size s
                LEFT JOIN model_orders_items_size mois ON mois.size_id = s.id
                LEFT JOIN model_orders_items moi on mois.model_orders_items_id = moi.id
                WHERE moi.model_orders_id = %d
                GROUP BY moi.id,mois.id
            ";
        $sql = sprintf($sql,$this->id);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function getItemSizeList($array=null)
    {
        if($array){
            $res = $array;
        }else{
            $sql = "SELECT moi.id,moi.percentage,mois.id mois_id, s.name size,mois.count count FROM size s
                LEFT JOIN model_orders_items_size mois ON mois.size_id = s.id
                LEFT JOIN model_orders_items moi on mois.model_orders_items_id = moi.id
                WHERE moi.model_orders_id = %d
                GROUP BY moi.id,mois.id
            ";
            $sql = sprintf($sql,$this->id);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
        }
        $list = [];
        if(!empty($res)){
            foreach ($res as $item) {
                $list[$item['id']] = $list[$item['id']]."<code>{$item['size']} - <b>{$item['count']}</b></code><br>";
            }
        }
        return $list;
    }

    public function getSizeCustomList($class='',$attribute='',$array=null){
        if($array){
            $res = $array;
        }else{
            $sql = "SELECT moi.id, s.name size,mois.count count FROM size s
                LEFT JOIN model_orders_items_size mois ON mois.size_id = s.id
                LEFT JOIN model_orders_items moi on mois.model_orders_items_id = moi.id
                WHERE moi.model_orders_id = %d
                GROUP BY moi.id,mois.id
            ";
            $sql = sprintf($sql,$this->id);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
        }
        $list = [];
        $list['list'] = [];
        $list['all_count'] = [];
        if(!empty($res)){
            foreach ($res as $item) {
                $list['list'][$item['id']] = $list['list'][$item['id']]."<span class='{$class}' {$attribute} >" . $item['size'] . " - " . $item['count'] . "</span>&nbsp;";
                if(is_int((int)$item['count'])){
                    $list['all_count'][$item['id']] = (isset($list['all_count'][$item['id']]))?$list['all_count'][$item['id']]+$item['count']:$item['count'];
                }
            }
        }
        return $list;
    }

    public function getSizeCustomListPercentage($class='',$attribute='',$checked=false,$checked_list=null,$array=null){
        if($array){
            $res = $array;
        }else{
            $sql = "SELECT moi.id,moi.percentage,mois.id mois_id, s.id size_id, s.name size,mois.count count FROM size s
                LEFT JOIN model_orders_items_size mois ON mois.size_id = s.id
                LEFT JOIN model_orders_items moi on mois.model_orders_items_id = moi.id
                WHERE moi.model_orders_id = %d
                GROUP BY moi.id,mois.id
            ";
            $sql = sprintf($sql,$this->id);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
        }
        $list = [];
        $list['list'] = [];
        $list['all_count'] = [];
        if(!empty($res)){
            if($checked){
                foreach ($res as $item) {
                    $num = $item['percentage']/100;
                    $disabled = '';
                    if($checked_list&&is_array($checked_list)&&in_array($item['size_id'], $checked_list)){
                        $disabled = 'disabled="disabled"';
                    }
                    $count = $num * $item['count'] + $item['count'];
                    $list['list'][$item['id']] = $list['list'][$item['id']]."<span class='{$class}' {$attribute} >" . $item['size'] . " - <span id='size_count_{$item['mois_id']}' class='size_percentage_all_{$this->id}'>" . ceil($count) . "</span><input type='checkbox' class='size_checkbox size_checkbox_{$item['size_id']}' checked value='{$count}' data-name='{$item['size']}' data-id='{$item['size_id']}' $disabled></span>";
                    if(is_int($count)){
                        $list['all_count'][$item['id']] = (isset($list['all_count'][$item['id']]))?$list['all_count'][$item['id']]+$count:$count;
                    }
                }
            }else {
                foreach ($res as $item) {
                    $num = $item['percentage']/100;
                    $count = $num * $item['count'] + $item['count'];
                    $list['list'][$item['id']] = $list['list'][$item['id']]."
                        <span class='{$class}' {$attribute} >" . $item['size'] . " - <span id='size_count_{$item['mois_id']}' class='size_percentage_all_{$this->id}'>" . ceil($count) . "</span></span>";
                    if(is_int($count)){
                        $list['all_count'][$item['id']] = (isset($list['all_count'][$item['id']]))?$list['all_count'][$item['id']]+$count:$count;
                    }
                }
            }
        }
        return $list;
    }

    public function getBichuvAcsList()
    {
        $sql = "SELECT moi.id,bap.id property_id,bap.name property,ba.id acs_id,ba.name,ba.sku,moia.qty,ba.barcode,moia.add_info,u.name unit,
                       (SELECT bat.path FROM bichuv_acs_attachment bat WHERE bat.bichuv_acs_id = ba.id ORDER BY bat.isMain DESC LIMIT 1) image 
                FROM bichuv_acs_property bap
                LEFT JOIN bichuv_acs ba ON bap.id = ba.property_id
                LEFT JOIN unit u ON ba.unit_id = u.id
                LEFT JOIN model_orders_items_acs moia ON moia.bichuv_acs_id = ba.id
                LEFT JOIN model_orders_items moi on moia.model_orders_items_id = moi.id
                WHERE moi.model_orders_id = %d
                GROUP BY moi.id,moia.id
        ";
        $sql = sprintf($sql,$this->id);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $list = [];
        if(!empty($res)){
            foreach ($res as $item) {
                $list[$item['id']][$item['acs_id']] = [
                    'name' => $item['name'],
                    'sku' => $item['sku'],
                    'property' => $item['property'],
                    'image' => $item['image'],
                    'add_info' => $item['add_info'],
                    'barcode' => $item['barcode'],
                    'unit' => $item['unit'],
                    'qty' => $item['qty'],
                ];
            }
        }
        return $list;
    }

    public function getItemModelList($array=null){
        if($array){
            $res = $array;
        }else{
            $sql = $sql = "SELECT moi.id moi_id,m.id as id, m.name as model, m.article as article, view.name as vname, type.name as tname, m.brend_id,
                (SELECT path FROM model_rel_attach as mra
                            LEFT JOIN attachments as atch ON atch.id = mra.attachment_id
                            WHERE mra.model_list_id = m.id ORDER BY mra.is_main DESC LIMIT 1
                ) image,mv.name var,mv.code var_code
                FROM models_variations mv
                LEFT JOIN models_list as m ON mv.model_list_id = m.id
                LEFT JOIN model_view as view ON m.view_id = view.id
                LEFT JOIN model_types as type ON m.type_id = type.id
                LEFT JOIN model_orders_items moi on mv.id = moi.model_var_id
                WHERE moi.model_orders_id = %d
            ";
            $sql = sprintf($sql,$this->id);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
        }
        $list = [];
        if(!empty($res)){
            foreach ($res as $item) {
                $list[$item['moi_id']] = [
                    'name' => $item['model'],
                    'article' => $item['article'],
                    'image' => $item['image'],
                    'var' => $item['var'],
                    'var_code' => $item['var_code'],
                ];
            }
        }
        return $list;
    }
    public function getItemMatoList($array=null){
        if($array){
            $res = $array;
        }else{
            $sql = $sql = "SELECT 
                moi.id moi_id,
                trm.id mato_id,trm.name mato,trm.code mato_code
                FROM model_orders_items moi
                LEFT JOIN model_orders_planning mop ON moi.id = mop.model_orders_items_id
                LEFT JOIN toquv_raw_materials trm ON trm.id = mop.toquv_raw_materials_id
                WHERE moi.model_orders_id = %d
                GROUP BY trm.id
            ";
            $sql = sprintf($sql,$this->id);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
        }
        $list = [];
        if(!empty($res)){
            foreach ($res as $item) {
                $list[$item['mato_id']] = [
                    'id' => $item['mato_id'],
                    'name' => $item['mato'],
                    'code' => $item['mato_code'],
                ];
            }
        }
        return $list;
    }

    public function pantoneCodeList(){
        $sql = "select
                    cp.id, concat('(', cp.code, ')', ' - <b>', cp.name, '</b>') name
                from model_orders mo
                         left join model_orders_items i on mo.id = i.model_orders_id
                         left join models_list l on i.models_list_id = l.id
                         left join models_variations mv on i.model_var_id = mv.id
                         left join color_pantone cp on mv.color_pantone_id = cp.id
                where cp.id is not null 
                group by cp.id";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        return  ArrayHelper::map($list, 'id', 'name');
    }
    
    /** Buyurtmaga biriktirilgan qoliplarni chiqarish */
    public function getBasePatternsVariation($id)
    {
        $sql = "
            SELECT	
                model_orders.id,
                model_orders_items.models_list_id, model_orders_items.model_var_id,
                model_orders_variations.variant_no, 
                models_list.name, models_list.base_pattern_id,
                models_variations.name as mvname,
                base_patterns.id as bp_id,
                base_pattern_items.bichuv_detail_type_id, base_pattern_items.base_detail_list_id, 
                bichuv_detail_types.name,
                base_detail_lists.name as bdl_name,
                base_pattern_part.name as bpp_name,
                base_pattern_mini_postal.loss,
                base_pattern_mini_postal_sizes.size_id,
                size.name as sname,
                base_pattern_rel_attachment.attachment_id,
                attachments.name, attachments.path
            FROM model_orders LEFT JOIN model_orders_items ON model_orders.id = model_orders_items.model_orders_id
                LEFT JOIN model_orders_variations ON model_orders_variations.model_orders_id = model_orders.id
                LEFT JOIN models_list ON models_list.id = model_orders_items.models_list_id
                LEFT JOIN models_variations ON models_variations.model_list_id = models_list.id
                LEFT JOIN base_patterns ON base_patterns.id = models_list.base_pattern_id
                left JOIN base_pattern_items ON base_pattern_items.base_pattern_id = base_patterns.id
                LEFT JOIN bichuv_detail_types ON bichuv_detail_types.id = base_pattern_items.bichuv_detail_type_id
                LEFT JOIN base_detail_lists ON base_detail_lists.id = base_pattern_items.base_detail_list_id
                LEFT JOIN base_pattern_part ON base_pattern_part.id = base_pattern_items.base_pattern_part_id
                LEFT JOIN base_pattern_mini_postal ON base_pattern_mini_postal.base_patterns_id = base_patterns.id
                LEFT JOIN base_pattern_mini_postal_sizes ON base_pattern_mini_postal_sizes.base_pattern_mini_postal_id = base_pattern_mini_postal.id
                LEFT JOIN size ON size.id = base_pattern_mini_postal_sizes.size_id
                LEFT JOIN base_pattern_rel_attachment ON base_pattern_rel_attachment.base_pattern_id = base_patterns.id
                LEFT JOIN attachments ON attachments.id = base_pattern_rel_attachment.id
                WHERE
                    model_orders.id = {$id} AND model_orders_variations.status = 1
                GROUP BY
                    models_list.name, base_pattern_part.name, attachments.path, size.name, base_pattern_mini_postal_sizes.id
        ";
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        return $query;
    }

    public function saveOrderStatus($status,$type=1,$info=null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try {
            /*$order_status = ModelOrdersStatus::findOne([
                'order_status' => $status,
                'model_orders_id' => $this->id,
                'type' => $type
            ]);
            if($order_status==null){
                $order_status = new ModelOrdersStatus();
            }*/
            $order_status = new ModelOrdersStatus();
            $order_status->setAttributes([
                'order_status' => $status,
                'model_orders_id' => $this->id,
                'type' => $type,
                'add_info' => $info,
            ]);
            if($order_status->save()) {
                $saved = true;
            }
            if($saved){
                $transaction->commit();
            }else{
                Yii::info($order_status->getErrors(), 'save');
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            Yii::info('Not saved Model Orders Status' . $e, 'save');
            $transaction->rollBack();
        }
        return $saved;
    }


    public function getOrderInfo()
    {
        $musteri = $this->musteri->name;
        $model_list = $this->getModelArticles(true,true);
        $summa = (!empty($this->sum_item_qty)) ? number_format($this->sum_item_qty,0,'',''):$this->sum_item_qty;
        return [
            'musteri' => $musteri,
            'model_list' => $model_list,
            'summa' => $summa
        ];
    }

    public function getPlanningThread($id)
    {
        $sql=" SELECT
                        ne.id as ne_id,
                        thread.id as thread_id,
                        mop.raw_fabric * trmi.percentage/100 as xom_mato,
                        (thread.wastage_percent/100 + 1)*trmi.percentage*mop.raw_fabric/100 as miqdor,
                         moi.load_date  as date,
                        mo.id as mo_id,
                        mop.id as mop_id,
                        moi.id as moi_id,
                        trm.name AS nomi
                FROM `model_orders_items` AS moi
                LEFT JOIN model_orders AS mo ON moi.`model_orders_id` = mo.id
                LEFT JOIN model_orders_planning AS mop ON mop.model_orders_items_id = moi.id
                LEFT JOIN toquv_raw_materials AS trm ON trm.id = mop.toquv_raw_materials_id
                LEFT JOIN toquv_raw_material_ip AS trmi ON trmi.toquv_raw_material_id = trm.id
                LEFT JOIN toquv_ne as ne ON trmi.ne_id=ne.id
                LEFT JOIN toquv_thread as thread ON trmi.thread_id=thread.id
                WHERE mo.id = {$id}";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try {
            ModelOrdersPlanningThread::deleteAll(['model_orders_id'=>$id]);
            foreach ($result as $item)
            {
                $plan= new ModelOrdersPlanningThread();
                $plan->setAttributes([
                    'toquv_ne_id'=>$item['ne_id'],
                    'toquv_thread_id'=>$item['thread_id'],
                    'xom_mato'=>$item['xom_mato'],
                    'quantity'=>$item['miqdor'],
                    'load_date'=>$item['date'],
                    'model_orders_id'=>$item['mo_id'],
                    'model_orders_items_id'=>$item['moi_id'],
                    'model_orders_planning_id'=>$item['mop_id'],
                    'reg_date' => date("Y-m-d"),
                    'status'=>1,
                ]);
                if($plan->save()) {
                    $saved = true;
                }
                else
                {
                    $saved=false;
                    break;
                }

            }
            if($saved){
                $transaction->commit();
            }else{
                Yii::info($plan->getErrors(), 'save');
                $transaction->rollBack();
            }

        }
        catch (\Exception $e) {
            Yii::info('Not saved Model Orders Status' . $e, 'save');
            $transaction->rollBack();
        }
        return $saved;
    }

    /**
     * Modellarni rasmlarni olish uchun
     * */
    public function getAttachmentsImages($id)
    {
        $modelOrdersItems = ModelOrdersItems::find()
            ->select('id')
            ->where(['model_orders_id' => $id])
            ->column();
        $q = "moi.id in (".implode(',', $modelOrdersItems).')';
        $sql = "
            SELECT moi.id, mora.attachments_id, a.*
            FROM model_orders_items moi left join model_orders_attachment_relations mora
            on moi.id = mora.model_orders_items_id
            left join attachments a on mora.attachments_id = a.id
            WHERE $q
        ";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            foreach($result as $k => $v){

            }
        }
    }

    public static function getItemsAcc($id=null,$mId=null)
    {
        $acsQuery = ModelOrders::find()
            ->alias('mo')
            ->select([
                'ba.sku AS artikul',
                'ba.name AS acs_name',
                "GROUP_CONCAT(bapl.name, ': ', bap.value SEPARATOR ', ') AS acs_properties",
                'moia.qty AS order_acs_qty',
                'u.name AS unit_name',
                'moia.add_info AS order_acs_info',
            ])
            ->leftJoin(['moi' => 'model_orders_items'], 'mo.id = moi.model_orders_id')
            ->leftJoin(['mov' => 'model_orders_variations'], 'moi.model_orders_variations_id = mov.id')
            ->leftJoin(['moia' => 'model_orders_items_acs'], 'moi.id = moia.model_orders_items_id')
            ->leftJoin(['ba' => 'bichuv_acs'], 'moia.bichuv_acs_id = ba.id')
            ->leftJoin(['u' => 'unit'], 'ba.unit_id = u.id')
            ->leftJoin(['bap' => 'bichuv_acs_properties'], 'ba.id = bap.bichuv_acs_id')
            ->leftJoin(['bapl' => 'bichuv_acs_property_list'], 'bap.bichuv_acs_property_list_id = bapl.id')
            ->andFilterWhere([
                'mo.id' => $id,
            ])
            ->andFilterWhere([
                'moi.id' => $mId
            ])
            ->groupBy('mo.id, moia.id')
            ->asArray()
            ->all();

        return $acsQuery;
    }

    public static function getItemsMaterials($id=null, $mId=null){
        $variantQuery = ModelOrders::find()
            ->alias('mo')
            ->select([
                'trm.code as rcode',
                'trm.name as rname',
                'type.name as tname',
                'tn.name AS ne',
                'tt.name AS thread',
                'tpf.name AS pus_fine',
                /*'wc.color_name AS wc_name',
                'wc.color_code AS wc_code',
                'cp.name AS cp_name',
                'cp.code AS cp_code',*/
                'IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as color_code',
                'IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as color_name',
                'wmi.en',
                'wmi.gramaj',
                'wd.name AS desen_name',
                'wd.code AS desen_code',
                'wbt.name AS baski_name',
                'moi.model_orders_variations_id AS model_orders_variations',
                'moim.add_info AS material_info',
            ])
            ->leftJoin(['moi' => 'model_orders_items'], 'mo.id = moi.model_orders_id')
            ->leftJoin(['mov' => 'model_orders_variations'], 'moi.model_orders_variations_id = mov.id')
            ->leftJoin(['moim' => 'model_orders_items_material'], 'moi.id = moim.model_orders_items_id')
            ->leftJoin(['wmi' => 'wms_mato_info'], 'moim.mato_id = wmi.id')
            ->leftJoin(['trm' => 'toquv_raw_materials'], 'wmi.toquv_raw_materials_id = trm.id')
            ->leftJoin(['type' => 'raw_material_type'], 'trm.raw_material_type_id = type.id')
            ->leftJoin(['trmi' => 'toquv_raw_material_ip'], 'trm.id = trmi.toquv_raw_material_id')
            ->leftJoin(['tn' => 'toquv_ne'], 'trmi.ne_id = tn.id')
            ->leftJoin(['tt' => 'toquv_thread'], 'trmi.thread_id = tt.id')
            ->leftJoin(['tpf' => 'toquv_pus_fine'], 'wmi.pus_fine_id = tpf.id')
            ->leftJoin(['wc' => 'wms_color'], 'wmi.wms_color_id = wc.id')
            ->leftJoin(['cp' => 'color_pantone'], 'wc.color_pantone_id = cp.id')
            ->leftJoin(['wd' => 'wms_desen'], 'wmi.wms_desen_id = wd.id')
            ->leftJoin(['wbt' => 'wms_baski_type'], 'wd.wms_baski_type_id = wbt.id')
            ->andFilterWhere([
                'mo.id' => $id,
            ])
            ->andFilterWhere([
                'moi.id' => $mId
            ])
            ->groupBy(['rname'])
            ->asArray()
            ->all();
        return $variantQuery;
    }

    public static function getItemsToquvAcc($id=null, $mId=null) {
        $acsQuery = ModelOrders::find()
            ->alias('mo')
            ->select([
                'trm.name AS trmname',
                'rmt.name AS rmt_name',
                'wmi.wms_desen_id',
                'wmi.wms_color_id',
                'wmi.pus_fine_id',
                'wmi.toquv_raw_materials_id',
                'wmi.en',
                'wmi.gramaj',
                'wmi.ne_id',
                'wd.name as wdname','wd.code as wdcode', 'wbt.name as wbtname',
                'wc.color_pantone_id', 'wc.color_code', 'wc.color_name', 'wc.color_palitra_code',
                'cp.name as cpname', 'cp.code as cpcode',
                'tpf.name as tpf_name',
                'tn.name as tn_name',
                'moita.count'
            ])
            ->leftJoin(['moi' => 'model_orders_items'], 'mo.id = moi.model_orders_id')
            ->leftJoin(['mov' => 'model_orders_variations'], 'moi.model_orders_variations_id = mov.id')
            ->leftJoin(['moita' => 'model_orders_items_toquv_acs'], 'moi.id = moita.model_orders_items_id')
            ->leftJoin(['wmi' => 'wms_mato_info'], 'moita.wms_mato_info_id = wmi.id')
            ->leftJoin(['wd' => 'wms_desen'], 'wmi.wms_desen_id = wd.id')
            ->leftJoin(['wc' => 'wms_color'], 'wmi.wms_color_id = wc.id')
            ->leftJoin(['cp' => 'color_pantone'], 'wc.color_pantone_id = cp.id')
            ->leftJoin(['tpf' => 'toquv_pus_fine'], 'wmi.pus_fine_id = tpf.id')
            ->leftJoin(['tn' => 'toquv_ne'], 'wmi.ne_id = tn.id')
            ->leftJoin(['trm' => 'toquv_raw_materials'], 'wmi.toquv_raw_materials_id = trm.id')
            ->leftJoin(['rmt' => 'raw_material_type'], 'trm.raw_material_type_id = rmt.id')
            ->leftJoin(['wbt' => 'wms_baski_type'],'wd.wms_baski_type_id = wbt.id')
            ->andFilterWhere([
                'mo.id' => $id,
            ])
            ->andFilterWhere([
                'moi.id' => $mId
            ])
            ->groupBy('mo.id, moita.id')
            ->asArray()
            ->all();

        return $acsQuery;
    }

    public static function getBasePatterns($id,$mId=null)
    {
        $query = "SELECT
                    mo.id as mo_id,
                    moi.id as moi_id,
                    ml.base_pattern_id,
                    bp.name as bp_name,
                    mt.name as mt_name,
                    b.name as bname,
                    he.fish,
                    bpi.*,
                    bdt.name as bdt_name,
                    bdl.name as bdl_name,
                    bpp.name as bpp_name
                FROM model_orders mo
                INNER JOIN model_orders_items moi
                ON mo.id = moi.model_orders_id
                INNER JOIN models_list ml
                ON ml.id = moi.models_list_id
                INNER JOIN base_patterns bp
                ON bp.id = ml.base_pattern_id
                INNER JOIN model_types mt
                ON mt.id = bp.model_type_id
                INNER JOIN brend b
                ON bp.brend_id = b.id
                INNER JOIN hr_employee he
                ON bp.constructor_id = he.id
                INNER JOIN base_pattern_items bpi
                ON bp.id = bpi.base_pattern_id
                INNER JOIN bichuv_detail_types bdt
                ON bpi.bichuv_detail_type_id = bdt.id
                INNER JOIN base_detail_lists bdl
                ON bdl.id = bpi.base_detail_list_id
                INNER JOIN base_pattern_part bpp
                ON bpi.base_pattern_part_id = bpp.id
                WHERE mo.id = {$id}";
        if($mId != null){
            $query .= " AND moi.id = {$mId}";
        }
        $result = Yii::$app->db->createCommand($query)->queryAll();
        return $result;
    }

    public static function getBasePatternsImages($id,$mId=null)
    {
        $sql = "SELECT
                mo.id as mo_id,
                moi.id as moi_id,
                ml.base_pattern_id,
                bp.name as bp_name,
                bpra.attachment_id,
                a.*
            FROM
                model_orders mo
            INNER JOIN model_orders_items moi
            ON moi.model_orders_id = mo.id
            INNER JOIN models_list ml
            ON ml.id = moi.models_list_id
            INNER JOIN base_patterns bp
            ON ml.base_pattern_id = bp.id
            INNER JOIN base_pattern_rel_attachment bpra
            ON bpra.base_pattern_id = bp.id
            INNER JOIN attachments a
            ON a.id = bpra.attachment_id
            WHERE mo.id = {$id}";
        if($mId != null){
            $sql .= " AND moi.id = {$mId}";
        }
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        return $result;
    }

    public static function getBasePatternsMiniPostal($id,$mId)
    {
        $sql = "SELECT
                    mo.id as mo_id,
                    moi.id as moi_id,
                    ml.base_pattern_id,
                    bp.name as bp_name,
                    bpmp.loss, bpmp.path, bpmp.name as bpmp_name,
                    bpmps.size_id,
                    s.name as sname, bpmp.extension
                FROM
                    model_orders mo
                INNER JOIN model_orders_items moi
                ON moi.model_orders_id = mo.id
                INNER JOIN models_list ml
                ON ml.id = moi.models_list_id
                INNER JOIN base_patterns bp
                ON ml.base_pattern_id = bp.id
                INNER JOIN base_pattern_mini_postal bpmp
                ON bpmp.base_patterns_id = bp.id
                INNER JOIN base_pattern_mini_postal_sizes bpmps
                ON bpmp.id = bpmps.base_pattern_mini_postal_id
                INNER JOIN size s
                ON s.id = bpmps.size_id
                WHERE mo.id = {$id}";
        if($mId != null){
            $sql .= " AND moi.id = {$mId}";
        }
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        return $result;
    }

    public static function getModelsList($listId=null,$type){
        $addQuery = '';
        $status = ModelOrders::STATUS_SAVED;
        if(!empty($listId)){
            $addQuery = "AND ml.id = {$listId}";
        }
        if($type === self::MODELS_IMG){
            $sql = "
                SELECT
                    ml.name as mlname, ml.article, a.name as aname, a.path
                FROM
                    models_list ml
                LEFT JOIN model_rel_attach mra ON mra.model_list_id = ml.id
                LEFT JOIN attachments a ON a.id = mra.attachment_id
                WHERE ml.status = {$status}
                %s
            ";
        }
        elseif($type === self::MODELS_MATO){

        }
        $sql = sprintf($sql,$addQuery);
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        if($query)
            return $query;
        return false;
    }

    /**
     * Planlangan zakazlarni qaytaradi
     * @return array
     */
    public static function getPlannedOrders()
    {
        return ArrayHelper::map(
            ModelOrders::find()
                ->select(['id', 'doc_number'])
                ->andWhere(['status' => ModelOrders::STATUS_PLANNED])
                ->asArray()
                ->all(),
            'id',
            'doc_number'
        );
    }

    public static function getOrderItemsAndMaterialsByOrderId($orderId)
    {
        $sql = "
        SELECT moi.id, ml.name, ml.article, cp.code, cp.r, cp.g, cp.b
        FROM model_orders mo
            LEFT JOIN model_orders_items moi ON mo.id = moi.model_orders_id
            LEFT JOIN model_orders_items_mato moim ON moi.id = moim.model_orders_items_id
            LEFT JOIN models_variations mv ON moi.model_var_id = mv.id
            LEFT JOIN color_pantone cp on mv.color_pantone_id = cp.id
            LEFT JOIN models_list ml ON moi.models_list_id = ml.id
        WHERE mo.id = :model_orders_id
        ";
        $query = ModelOrders::find()
            ->alias('mo')
            ->leftJoin(['moi' => 'model_orders_items'], 'mo.id = moi.model_orders_id')
            ->leftJoin(['mv' => 'models_variations'], 'moi.model_var_id = mv.id')
            ->leftJoin(['mv' => 'wms_color_id'], 'moi.model_var_id = mv.id')
            ->leftJoin(['moim' => 'model_orders_items_material'], 'moi.id = moim.model_orders_items_id');
    }

    /**
     * @params $id
     * */
    public function getModelsVariationsPechats($varId)
    {
        if(isset($varId) && !empty($varId)){
                $array = [];
                $pechat = "
                    SELECT mp.id as mpid, mp.width, mp.height, bdl.id as bdlid, mv.id as mvid, mp.name, mp.image, bdl.name as bdlname FROM
                    models_variations mv 
                    LEFT JOIN model_var_prints mp ON mp.id = mv.model_var_prints_id
                    LEFT JOIN base_detail_lists bdl ON mp.base_details_list_id = bdl.id
                    WHERE mv.id = {$varId}
                ";
                $pechatQuery = Yii::$app->db->createCommand($pechat)->queryAll();

                $naqsh = "
                    SELECT mn.id as mnid, mn.width, mn.height, bdl.id as bdlid, mv.id as mvid, mn.name as mnname, mn.image, bdl.name as bdlname FROM
                    models_variations mv 
                    LEFT JOIN model_var_stone mn ON mn.id = mv.model_var_stone_id
                    LEFT JOIN base_detail_lists bdl ON bdl.id = mn.base_details_list_id
                    WHERE mv.id = {$varId}
                ";
                $naqshQuery = Yii::$app->db->createCommand($naqsh)->queryAll();

                if(!empty($pechatQuery)){
                    $array['pechat'] = $pechatQuery;
                }

                if(!empty($naqshQuery)){
                    $array['naqsh'] = $naqshQuery;
                }
                $array['id'] = $varId;
                return $array;
        }else{
            return false;
        }
    }

    /**
     * Ta'minot tasdiqlaganligini aniqlaydi
     * @return bool
     */
    public function isConfirmedBySupply() {
        return $this->confirm_supply == 1;
    }

    public static function getVariantAllData($id)
    {
       $sql = "
        SELECT ba.id, ml.name, ba.name as baname, GROUP_CONCAT(bap.value SEPARATOR ' ') value FROM
        models_list ml
        LEFT JOIN models_acs_variations mav ON ml.id = mav.models_list_id
        LEFT JOIN bichuv_acs ba ON mav.bichuv_acs_id = ba.id
        LEFT JOIN bichuv_acs_properties bap ON bap.bichuv_acs_id = ba.id
        WHERE ml.id = {$id}
        GROUP BY ba.id
       ";
       $query = Yii::$app->db->createCommand($sql)->queryAll();
       if($query){
           return $query;
       }
       else{
           return false;
       }
    }

    public static function getFitSImples($id, $mId)
    {
        $sql = "
            SELECT mof.add_info, GROUP_CONCAT(morfa.attachments_id SEPARATOR ',') images FROM
            model_orders_fs mof
            LEFT JOIN model_orders_rel_fs_attachments morfa ON mof.id = morfa.model_orders_fs_id
            WHERE mof.model_orders_id = {$id} AND mof.model_orders_items_id = {$mId}
            GROUP BY morfa.model_orders_fs_id
        ";
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        return $query;
    }
}
