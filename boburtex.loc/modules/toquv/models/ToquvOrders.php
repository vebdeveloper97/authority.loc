<?php

namespace app\modules\toquv\models;

use app\components\OurCustomBehavior;
use app\models\Users;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersPlanning;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "toquv_orders".
 *
 * @property int $id
 * @property int $musteri_id
 * @property string $document_number
 * @property string $reg_date
 * @property string $responsible_persons
 * @property string $comment
 * @property string $sum_uzs
 * @property string $sum_usd
 * @property string $sum_rub
 * @property string $sum_eur
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $priority
 * @property int $entity_type
 * @property string $done_date
 * @property int $type
 * @property int $model_orders_id
 * @property int $model_musteri_id
 * @property int $order_type
 * @property int $updated_by
 *
 * @property ToquvInstructions[] $toquvInstructions
 * @property ToquvMakineProcesses[] $toquvMakineProcesses
 * @property Musteri $musteri
 * @property ToquvOrdersResponsible[] $toquvOrdersResponsibles
 * @property bool|string $responsibleList
 * @property array $responsibleMap
 * @property mixed $modelMusteri
 * @property mixed $usersList
 * @property mixed $modelOrders
 * @property ToquvRmOrder[] $toquvRmOrders
 */
class  ToquvOrders extends BaseModel
{
    public $rm_order = [];
    public $responsible = [];
    const ORDER_SERVICE = 1;
    const ORDER_SAMO = 2;
    const ORDER_EXPORT = 3;

    const STATUS_CANSELLED = 4;
    const STATUS_FINISHED = 5;
    const STATUS_KIRIM_MATO = 99;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['document_number', 'unique'],
            [['musteri_id', 'document_number', 'reg_date', 'responsible',], 'required'],
            [['musteri_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'priority', 'entity_type', 'type', 'model_orders_id', 'model_musteri_id', 'order_type'], 'integer'],
            [['reg_date', 'done_date'], 'safe'],
            [['responsible_persons', 'comment'], 'string'],
            [['sum_uzs', 'sum_usd', 'sum_rub', 'sum_eur'], 'number'],
            [['document_number'], 'string', 'max' => 50],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
        ];
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getPriorityList($key = null)
    {
        $list = [
            1 => Yii::t('app', 'Low'),
            2 => Yii::t('app', 'Normal'),
            3 => Yii::t('app', 'High'),
            4 => Yii::t('app', 'Urgent')
        ];
        if ($key) {
            return $list[$key];
        }
        return $list;
    }
    public static function getOrderTypeList($key = null)
    {
        $list = [
            1 => Yii::t('app', 'Xizmat'),
            2 => Yii::t('app', 'SAMO model'),
            3 => Yii::t('app', 'Eksport')
        ];
        if ($key) {
            return $list[$key];
        }
        return $list;
    }
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app','Active'),
            self::STATUS_INACTIVE => Yii::t('app','Deleted'),
            self::STATUS_SAVED => Yii::t('app','Saved'),
            self::STATUS_CANSELLED => Yii::t('app','Bekor qilingan'),
            self::STATUS_FINISHED => Yii::t('app','Yopilgan'),
        ];
        if(!empty($key)){
            return $result[$key];
        }

        return $result;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'document_number' => Yii::t('app', 'Document Number'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'responsible' => Yii::t('app', 'Responsible Persons'),
            'comment' => Yii::t('app', 'Comment'),
            'sum_uzs' => Yii::t('app', 'Sum Uzs'),
            'sum_usd' => Yii::t('app', 'Sum Usd'),
            'sum_rub' => Yii::t('app', 'Sum Rub'),
            'sum_eur' => Yii::t('app', 'Sum Eur'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', "O'zgartirdi"),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'priority' => Yii::t('app', 'Priority'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'done_date' => Yii::t('app', 'Done Date'),
            'type' => Yii::t('app', 'Type'),
            'model_orders_id' => Yii::t('app', 'Model Orders ID'),
            'model_musteri_id' => Yii::t('app', 'Model buyurtmachisi'),
            'order_type' => Yii::t('app', 'Buyurtma turi')
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

    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }
    public function getModelMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'model_musteri_id']);
    }
    public function getModelOrders()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'model_orders_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvOrdersResponsibles()
    {
        return $this->hasMany(ToquvOrdersResponsible::className(), ['toquv_orders_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvInstructions()
    {
        return $this->hasMany(ToquvInstructions::className(), ['toquv_order_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvMakineProcesses()
    {
        return $this->hasMany(ToquvMakineProcesses::className(), ['toquv_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvRmOrders()
    {
        return $this->hasMany(ToquvRmOrder::className(), ['toquv_orders_id' => 'id'])->joinWith('moi');
    }

    public function getToquvRawMaterialList($type=1)
    {
        $rawMaterial = ToquvRawMaterials::find()->select(['id', 'name'])->where(['type'=>$type])->asArray()->all();
        return ArrayHelper::map($rawMaterial, 'id', 'name');
    }

    public static function getMusteriList()
    {
        $musteri = Musteri::find()->select(['id', 'name'])->asArray()->orderBy(['name' => SORT_ASC])->all();
        return ArrayHelper::map($musteri, 'id', 'name');
    }

    public function getUsersList()
    {
        $users = Users::find()->select(['id as users_id', 'user_fio'])->asArray()->all();
        return ArrayHelper::map($users, 'users_id', 'user_fio');
    }

    public function getResponsibleMap()
    {
        $data = $this->toquvOrdersResponsibles;
        $users = [];
        foreach ($data as $key) {
            array_push($users, $key->users_id);
        }
        return $users;
    }

    public function getResponsibleList()
    {
        $data = $this->toquvOrdersResponsibles;
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

    public function saveResponsible($data)
    {
        $saved = false;
        foreach ($data as $key) {
            if (!empty($key)) {
                $responsible = new ToquvOrdersResponsible();
                $responsible->setAttributes([
                    'toquv_orders_id' => $this->id,
                    'users_id' => $key,
                ]);
                if($responsible->save()){
                    $saved = true;
                }else{
                    $saved = false;
                    break;
                }
            }
        }
        return $saved;
    }

    public function saveItems($data)
    {
        $saved = false;
        foreach ($data as $key) {
            if (!empty($key['toquv_raw_materials_id']) && !empty($key['quantity'])) {
                $pricing = new ToquvRmOrder();
                $pricing->setAttributes([
                    'toquv_orders_id' => $this->id,
                    'toquv_raw_materials_id' => $key['toquv_raw_materials_id'],
                    'quantity' => $key['quantity'],
                    'count' => $key['count'],
                    'price' => $key['summa'],
                    'price_fakt' => $key['fakt'],
                    'pb_id' => $key['sum'],
                    'priority' => $key['priority'],
                    'done_date' => $key['done_date'],
                    'thread_length' => $key['thread_length'],
                    'finish_en' => $key['finish_en'],
                    'finish_gramaj' => $key['finish_gramaj'],
                    'unit_id' => 2,
                    'rm_type' => $this->type,
                    'type_weaving' => $key['type_weaving'],
                    'color_pantone_id' => $key['color_pantone_id'],
                    'color_id' => $key['color_id'],
                    'model_code' => $key['model_code'],
                    'model_musteri_id' => $this->model_musteri_id,
                    'order_type' => $this->order_type,
                    'toquv_pus_fine_id' => $key['toquv_pus_fine_id'],
                ]);
                if($pricing->save()){
                    $saved = true;
                    if(!empty($key['child'])) {
                        foreach ($key['child'] as $id => $m) {
                            $ip = ToquvRawMaterialIp::find()
                                ->select(['ne_id', 'thread_id'])
                                ->where(['id' => $m['ip_id']])->asArray()->one();
                            $child = new ToquvRmOrderItems();
                            $child->setAttributes([
                                'percentage' => $m['percentage'],
                                'own_quantity' => is_numeric($m['own_quantity']) ? $m['own_quantity'] : 0,
                                'their_quantity' => is_numeric($m['their_quantity']) ? $m['their_quantity'] : 0,
                                'toquv_ne_id' => $ip['ne_id'],
                                'toquv_thread_id' => $ip['thread_id'],
                                'toquv_rm_order_id' => $pricing->id,
                            ]);
                            if($child->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break 2;
                            }
                        }
                    }
                }else{
                    $saved = false;
                    break;
                }
            }
        }
        return $saved;
    }
    public function getDenisty($id){
        $raw = ToquvRawMaterials::findOne($id);
        if($raw->density>0) {
            return $raw->density;
        }
        return 1;
    }
    public function saveOrderItems($data)
    {
        foreach ($data as $m => $key) {
            $item = ToquvRmOrder::findOne([
                'toquv_orders_id' => $this->id, 'toquv_raw_materials_id' => $m
            ]);
            if ($item) {
                foreach ($key as $id => $item) {
                    $ip = ToquvRawMaterialIp::find()
                        ->select(['ne_id', 'thread_id'])
                        ->where(['id' => $id])->asArray()->one();
                    $pricing = new ToquvRmOrderItems();
                    $pricing->setAttributes([
                        'percentage' => $item['percentage'],
                        'own_quantity' => is_numeric($item['own_quantity']) ? $item['own_quantity'] : 0,
                        'their_quantity' => is_numeric($item['their_quantity']) ? $item['their_quantity'] : 0,
                        'toquv_ne_id' => $ip['ne_id'],
                        'toquv_thread_id' => $ip['thread_id'],
                        'toquv_rm_order_id' => $item['id'],
                    ]);
                    $pricing->save();
                }
            }
        }
    }


    /**
     * @param null $order
     * @return string
     * @throws \yii\db\Exception
     */
    public static function getInstructionActionStatus($order = null){
        $status = Yii::$app->request->get('ToquvOrdersSearch')['instructionStatus'];
        $st = ($status)?" AND ti.status = {$status}":"";
        $sql = "select ti.id,
                       ti.status, 
                       tir.quantity,
                       trm.name,
                       tpf.name as pus_fine,
                       tir.thread_length,
                       tir.finish_en,
                       tir.finish_gramaj,
                       ti.is_service,
                       tor.type type,
                       m.name as mname 
                from toquv_instructions ti
                         left join toquv_orders tor on tor.id = ti.toquv_order_id
                         left join musteri m on ti.musteri_id = m.id
                         left join toquv_instruction_rm tir on ti.id = tir.toquv_instruction_id
                         left join toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                         left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                         left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                where tor.id = :id %s ORDER BY ti.id DESC;";
        $sql = sprintf($sql,$st);
        $queries = Yii::$app->db->createCommand($sql)->bindValues(['id' => $order])->queryAll();
        $text = "";
        if($queries){
            foreach ($queries as $item){
                $url = Url::to(['view','id' => $item['id'],'orderId' => $order]);
                $title = Yii::t('app','View');
                $is_service = Yii::t('app',"O'zimizda");
                $is_active = "each-instruction-box";
                $status = Yii::t('app','Saqlanmagan');
                $statusClass = 'instruction-status-box not-saved';
                if($item['type']==ToquvRawMaterials::MATO){
                    $thread_length = Yii::t('app', 'Thread Length');
                    $finish_en = Yii::t('app', 'Finish En');
                    $finish_gramaj = Yii::t('app', 'Finish Gramaj');
                }else{
                    $thread_length = Yii::t('app', 'Uzunligi');
                    $finish_en = Yii::t('app', 'Eni');
                    $finish_gramaj = Yii::t('app', 'Qavati');
                }
                if($item['is_service'] == 2){
                    $is_service = $item['mname'];
                    $is_active = "each-instruction-box active";
                }
                if($item['status'] == 3){
                    $status = Yii::t('app','Saqlangan');
                    $statusClass = 'instruction-status-box';
                }
                $text .= "<a class='{$is_active}' href='{$url}' title='{$title}'>
                              <div><span>".Yii::t('app', 'Mato').":</span> {$item['name']} - ({$item['quantity']} kg)</div>
                              <div><span>".Yii::t('app', 'Bajariladigan joy').":</span> {$is_service}</div>
                              <div><span>".Yii::t('app', 'Pus Fine').":</span> {$item['pus_fine']}</div>
                              <div><span>{$thread_length}:</span> {$item['thread_length']}</div>
                              <div><span>{$finish_en}:</span> {$item['finish_gramaj']}</div>
                              <div><span>{$finish_gramaj}:</span> {$item['finish_en']}</div>
                              <div class='{$statusClass}'><span>Holati:</span> {$status}</div>
                           </a>";
            }
        }
        return $text;
    }
}