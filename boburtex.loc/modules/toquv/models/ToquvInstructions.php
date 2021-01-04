<?php

namespace app\modules\toquv\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\base\models\ModelOrdersPlanning;

/**
 * This is the model class for table "toquv_instructions".
 *
 * @property int $id
 * @property int $toquv_order_id
 * @property int $to_department
 * @property int $from_department
 * @property int $type
 * @property int $priority
 * @property string $responsible_persons
 * @property string $reg_date
 * @property string $add_info
 * @property int $notify
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $model_orders_id
 *
 * @property ToquvKalite[] $toquvKalites
 * @property ToquvInstructionItems[] $toquvInstructionItems
 * @property ToquvInstructionRm[] $toquvInstructionRms
 * @property ToquvDepartments $toDepartment
 * @property ToquvOrders $toquvOrder
 * @property ToquvMusteri $musteri
 * @property array $departments
 * @property array $orderList
 * @property int $is_service [smallint(6)]
 * @property int $musteri_id [bigint(20)]
 * @property mixed $colorList
 * @property array $closedInstructionList
 * @property int $is_closed [smallint(2)]
 */
class ToquvInstructions extends BaseModel
{
    const IS_ACTIVE = 1;
    const IS_CLOSED = 2;
    public $model_musteri_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_instructions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_order_id','musteri_id','to_department','is_closed','is_service', 'from_department', 'type', 'priority', 'notify', 'created_by', 'status', 'created_at', 'updated_at', 'model_orders_id'], 'integer'],
            [['responsible_persons', 'add_info'], 'string'],
            [['reg_date'], 'safe'],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['toquv_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvOrders::className(), 'targetAttribute' => ['toquv_order_id' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvMusteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('app', 'ID'),
            'toquv_order_id' => Yii::t('app', 'Toquv Order ID'),
            'to_department' => Yii::t('app', 'To Department'),
            'from_department' => Yii::t('app', 'From Department'),
            'type' => Yii::t('app', 'Type'),
            'priority' => Yii::t('app', 'Priority'),
            'responsible_persons' => Yii::t('app', 'Responsible Persons'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'add_info' => Yii::t('app', 'Add Info'),
            'notify' => Yii::t('app', 'Notify'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_service' => Yii::t('app', 'Bajariladigan joy'),
            'status' => Yii::t('app', 'Status'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'model_orders_id' => Yii::t('app', 'Model Orders ID'),
            'model_musteri_id' => Yii::t('app', 'Model buyurtmachisi'),
        );
    }
    public static function getStatusActive($key = null){
        $result = [
            self::IS_ACTIVE   => Yii::t('app','Yopilmagan'),
            self::IS_CLOSED => Yii::t('app','Yopilgan'),
        ];
        if(!empty($key)){
            return $result[$key];
        }

        return $result;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvKalites()
    {
        return $this->hasMany(ToquvKalite::className(), ['toquv_instructions_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvInstructionItems()
    {
        return $this->hasMany(ToquvInstructionItems::className(), ['toquv_instruction_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvInstructionRms()
    {
        return $this->hasMany(ToquvInstructionRm::className(), ['toquv_instruction_id' => 'id']);
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
    public function getToquvOrder()
    {
        return $this->hasOne(ToquvOrders::className(), ['id' => 'toquv_order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(ToquvMusteri::className(), ['id' => 'musteri_id']);
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->reg_date = date('Y-m-d', strtotime($this->reg_date)). " ".date('H:i:s');
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        $this->reg_date = date('d.m.Y', strtotime($this->reg_date));
        parent::afterFind();
    }

    /**
     * @return array
     */
    public function getOrderList(){
        $orders = ToquvOrders::find()
            ->select(['document_number','id','reg_date'])
            ->where(['status' => ToquvOrders::STATUS_ACTIVE])
            ->asArray()->all();
        $result = [];
        foreach ($orders as $order){
            $result[$order['id']] = "{$order['document_number']}-{$order['reg_date']}";
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getDepartments(){
        $departments = ToquvDepartments::find()->where(['status' => ToquvDepartments::STATUS_ACTIVE])->asArray()->all();
        return ArrayHelper::map($departments,'id','name');
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getPriorityList($key = null){
        $list = [
            1 => Yii::t('app','Low'),
            2 => Yii::t('app','Normal'),
            3 => Yii::t('app','High'),
            4 => Yii::t('app','Urgent')
        ];
        $options = [
            1 => ['style'=>'background:#ccc;color:white;padding:2px;font-weight:bold'],
            2 => ['style' => 'background:green;color:white;padding:2px;font-weight:bold'],
            3 => ['style' => 'background:#CC7722;color:white;padding:2px;font-weight:bold'],
            4 => ['style' => 'background:red;color:white;padding:2px;font-weight:bold'],
        ];
        if($key && $key !== 'options'){
            return $list[$key];
        }
        if($key && $key === 'options'){
            return $options;
        }
        return $list;
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getOrderInfo($id,$acs=null)
    {
        $tro = ($acs)?" and tro.status = 3":"";
        $sql = "select 
                    tor.id,
                    troi.id troi_id,
                    tn.id as neid,
                    tt.id as ttid,
                    troi.id as troi_id,
                    m.id as mid,  
                    m.name as ca,
                    tn.name as nename,
                    tt.name as thrname,
                    trm.name as mato,
                    trmc.name as mato_color,
                    tro.quantity as qty,
                    troi.own_quantity as own_qty,
                    troi.their_quantity as their_qty,
                    troi.percentage percentage,
                    tro.id tro_id,
                    m2.name order_musteri,
                    tro.moi_id
                from toquv_orders tor
                     left join toquv_rm_order tro on tor.id = tro.toquv_orders_id
                     left join musteri m on tor.musteri_id = m.id
                     left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                     left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                     left join toquv_rm_order_items troi on tro.id = troi.toquv_rm_order_id
                     left join toquv_ne tn on troi.toquv_ne_id = tn.id
                     left join toquv_thread tt on troi.toquv_thread_id = tt.id
                     LEFT JOIN model_orders_items moi ON tro.moi_id = moi.id
                     LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                     LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                where tor.id = :orderId %s
                ORDER BY tro.id,troi.id ASC;";
        $sql = sprintf($sql,$tro);
        $items = Yii::$app->db->createCommand($sql)->bindValues(['orderId' => $id])->queryAll();

        return $items;
    }

    /**
     * @param $orderId
     * @param $insId
     * @param string $type
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getInstructionViewData($orderId, $insId, $type = 'view')
    {
        $sql = "";
        switch ($type){
            case 'view':
                $sql = "select 
                   ti.id,
                   tii.id as tii_id,
                   tro.id as matoid,
                   ti.reg_date,
                   td.name as dept,
                   ti.priority,
                   ti.responsible_persons,
                   ti.add_info,
                   m.name as mname,
                   tii.is_own,
                   trm.name as mato,
                   trmc.name as mato_color,
                   tro.quantity as qty,
                   tii.thread_name as ip,
                   tii.quantity as ipqty,
                   tii.fact,
                   tii.add_info as comment,
                   mo.doc_number model,
                   tor.document_number doc,
                   ti.is_service,
                    tii.musteri_id musteri_id,
                    tii.lot lot,
                    m2.name order_musteri,
                    tro.moi_id
                   from toquv_instructions ti
                     left join toquv_departments td on ti.to_department = td.id
                     left join toquv_instruction_items tii on ti.id = tii.toquv_instruction_id
                     left join toquv_orders tor on ti.toquv_order_id = tor.id
                     left join toquv_rm_order_items troi on tii.rm_item_id = troi.id
                     left join toquv_rm_order tro on troi.toquv_rm_order_id = tro.id
                     left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                     left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                     left join musteri m on tor.musteri_id = m.id
                     LEFT JOIN model_orders_items moi ON tro.moi_id = moi.id
                     LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                     LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                     where ti.toquv_order_id = :orderId AND ti.id = :insId;";
                break;
            case 'update':
                $sql = "select 
                            tn.id as neid,
                            tt.id as ttid,
                            troi.id as troi_id,
                            tn.name as nename,
                            tt.name as thrname,
                            td.name as dept,
                            ti.priority,
                            ti.responsible_persons,
                            ti.add_info,
                            m.name as ca,
                            m.id as mid,
                            tii.is_own,
                            trm.name as mato,
                            trmc.name as mato_color,
                            tro.quantity as qty,
                            tii.thread_name as ip,
                            tii.entity_id,
                            tii.quantity as ipqty,
                            tii.fact,
                            tro.quantity as qty,
                            troi.own_quantity as own_qty,
                            troi.their_quantity as their_qty,
                            tii.add_info as comment,
                            troi.percentage percentage,
                            tro.id tro_id,
                            ti.is_service,
                            tii.musteri_id musteri_id,
                            tii.lot lot,
                            m2.name order_musteri,
                            tro.moi_id
                   from toquv_instructions ti
                     left join toquv_departments td on ti.to_department = td.id
                     left join toquv_instruction_items tii on ti.id = tii.toquv_instruction_id
                     left join toquv_orders tor on ti.toquv_order_id = tor.id
                     left join toquv_rm_order_items troi on tii.rm_item_id = troi.id
                     left join toquv_rm_order tro on troi.toquv_rm_order_id = tro.id
                     left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                     left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                     left join musteri m on tor.musteri_id = m.id
                     left join toquv_ne tn on troi.toquv_ne_id = tn.id
                     left join toquv_thread tt on troi.toquv_thread_id = tt.id
                     LEFT JOIN model_orders_items moi ON tro.moi_id = moi.id
                     LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                     LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                     where ti.toquv_order_id = :orderId AND ti.id = :insId;";
                break;
        }

        $query = Yii::$app->db->createCommand($sql)->bindValues(['orderId' => $orderId,'insId' => $insId])->queryAll();
        return $query;
    }
    /**
     * @param $id
     * @param $orderId
     * @param bool $isView
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getRawMaterials($id, $orderId = null, $isView = false, $acs=null){
        $tro = ($acs)?" and tro.status = 3":"";
        if($isView){
            $sql = "select trm.name as mato,
                       trmc.name mato_color,
                       tir.id id,
                       trm.id as trmid,
                       tro.id as troid,
                       tpf.id as pfid,
                       tpf.name as pf,
                       tir.thread_length,
                       tir.finish_gramaj,
                       tir.finish_en,
                       tir.type_weaving,
                       tro.finish_en as order_finish_en,
                       tro.finish_gramaj as order_finish_gramaj,
                       tro.thread_length as order_thread_length,
                       tro.quantity as order_quantity,
                       tro.type_weaving as order_type_weaving,
                       tir.quantity,
                       tir2.sum,
                       tro.moi_id, 
                       tro.done_date, 
                       tro.count order_count,
                       tro.model_code,
                       cp.name as cname,
                       cp.code as ccode, r, g, b,
                       type.name as tname,
                       tro.color_pantone_id,
                       tro.color_id,
                        c.color_id cl_name,
                        c.color cl_color
                    from toquv_instructions ti
                    left join toquv_instruction_rm tir on ti.id = tir.toquv_instruction_id
                    join (select SUM(t.quantity) sum,t.toquv_rm_order_id
                            from toquv_instruction_rm t GROUP BY t.toquv_rm_order_id) tir2 ON tir2.toquv_rm_order_id = tir.toquv_rm_order_id
                    left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                    left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                    left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                    left join toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                    left join color_pantone cp on tro.color_pantone_id = cp.id
                    left join color_panton_type as type ON cp.color_panton_type_id = type.id
                    left join color c ON tro.color_id = c.id
                    where ti.id = :id GROUP BY tro.id ORDER BY tro.id ASC;";
            $query = Yii::$app->db->createCommand($sql)->bindValues(['id' => $id])->queryAll();
        }else{
            $sql = "select trm.id as trmid,
                       tro.id as troid,
                       trm.name as mato,
                       trmc.name mato_color, 
                       t.id as orderId,
                       tro.finish_en,
                       tro.finish_gramaj,
                       tro.thread_length,
                       tro.type_weaving,
                       tro.quantity as order_quantity,
                       tir.sum,
                       tro.moi_id,
                       tro.done_date,
                       tro.count order_count,
                       tro.model_code,
                       cp.name as cname,
                       cp.code as ccode, r, g, b,
                       type.name as tname,
                       tro.color_pantone_id,
                       tro.color_id,
                        c.color_id cl_name,
                        c.color cl_color
                from toquv_rm_order tro
                         left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                         left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                         left join toquv_orders t on tro.toquv_orders_id = t.id
                         left join (select SUM(tir2.quantity) sum,tir2.toquv_rm_order_id troid2 from toquv_instruction_rm tir2 GROUP BY tir2.toquv_rm_order_id) tir ON tir.troid2 = tro.id
                         left join color_pantone cp on tro.color_pantone_id = cp.id
                         left join color_panton_type as type ON cp.color_panton_type_id = type.id
                         left join color c ON tro.color_id = c.id
                where t.id = :id %s GROUP BY tro.id ORDER BY tro.id ASC;";
            $sql = sprintf($sql,$tro);
            $query = Yii::$app->db->createCommand($sql)->bindValues(['id' => $id])->queryAll();
        }

        return $query;
    }
    public function saveItems($data,$type=null)
    {
        $order = new ToquvOrders();
        $samo = Musteri::find()->select('id')->where(['token'=>'SAMO'])->orderBy(['id'=>SORT_DESC])->one();
        $lastId = $order::find()->select('id')->where(['musteri_id'=>$samo->id])->orderBy(['id'=>SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $order->setAttributes([
            'musteri_id' => $samo->id,
            'document_number' => 'TU-'.$lastId.'-'.date('d.m.Y-H.i'),
            'reg_date' => date('Y-m-d H:i:s'),
            'responsible_persons' => $data['ToquvInstructions']['responsible_persons'],
            'comment' => $data['ToquvInstructions']['add_info'],
            'priority' => $data['ToquvInstructions']['priority'],
            'model_orders_id' => $data['ToquvInstructions']['model_orders_id'],
            'responsible' => 1,
            'status' => 3,
            'type' => ($data['ToquvInstructions']['type'])?$data['ToquvInstructions']['type']:1,
            'model_musteri_id' => $data['ToquvInstructions']['model_musteri_id']
        ]);
        $order->save();
        if($order) {
            $instruction = new ToquvInstructions();
            $instruction->setAttributes([
                'toquv_order_id' => $order->id,
                'from_department' => $data['ToquvInstructions']['from_department'],
                'to_department' => $data['ToquvInstructions']['to_department'],
                'reg_date' => $data['ToquvInstructions']['reg_date'],
                'responsible_persons' => $data['ToquvInstructions']['responsible_persons'],
                'add_info' => $data['ToquvInstructions']['add_info'],
                'priority' => $data['ToquvInstructions']['priority'],
                'model_orders_id' => $data['ToquvInstructions']['model_orders_id'],
                'notify' => 2,
                'type' => ($data['ToquvInstructions']['type'])?$data['ToquvInstructions']['type']:1
            ]);
            $instruction->save();
            foreach ($data['ToquvInstructionRm'] as $key) {
                if (!empty($key['toquv_rm_order_id'])) {
                    $price = ToquvRawMaterials::getNarx($key['toquv_rm_order_id'],$key['quantity']);
                    $pricing = new ToquvRmOrder();
                    $pricing->setAttributes([
                        'toquv_orders_id' => $order->id,
                        'toquv_raw_materials_id' => $key['toquv_rm_order_id'],
                        'quantity' => $key['quantity'],
                        'price' => $price,
                        'price_fakt' => $price,
                        'pb_id' => 2,
                        'priority' => $key['priority'],
                        'done_date' => $key['done_date'],
                        'type_weaving' => ($key['order_type_weaving'])?$key['order_type_weaving']:$key['type_weaving'],
                        'thread_length' => ($key['order_thread_length'])?$key['order_thread_length']:$key['thread_length'],
                        'finish_en' => ($key['order_finish_en'])?$key['order_finish_en']:$key['finish_en'],
                        'finish_gramaj' => ($key['order_finish_gramaj'])?$key['order_finish_gramaj']:$key['finish_gramaj'],
                        'unit_id' => 2,
                        'moi_id' => $key['model_orders_items_id'],
                        'color_pantone_id' => $key['color_pantone_id'],
                        'model_code' => $key['model_code'],
                        'model_musteri_id' => $key['model_musteri_id']
                    ]);
                    if ($pricing->save()) {
                        $modelTIR = new ToquvInstructionRm();
                        $modelTIR->setAttributes([
                            'toquv_instruction_id' => $instruction->id,
                            'toquv_rm_order_id' => $pricing->id,
                            'quantity' => $key['quantity'],
                            'toquv_pus_fine_id' => $key['toquv_pus_fine_id'],
                            'type_weaving' => $key['type_weaving'],
                            'thread_length' => $key['thread_length'],
                            'finish_en' => $key['finish_en'],
                            'finish_gramaj' => $key['finish_gramaj'],
                            'moi_id' => $key['model_orders_items_id']
                        ]);
                        if($modelTIR->save()) {
                            foreach ($key['child'] as $m) {
                                $child = new ToquvRmOrderItems();
                                $child->setAttributes([
                                    'percentage' => $m['percentage'],
                                    'own_quantity' => is_numeric($m['fact']) ? $m['fact'] : 0,
                                    'their_quantity' => is_numeric($m['their_quantity']) ? $m['their_quantity'] : 0,
                                    'toquv_ne_id' => $m['ne_id'],
                                    'toquv_thread_id' => $m['thread_id'],
                                    'toquv_rm_order_id' => $pricing['id'],
                                ]);
                                if ($child->save()) {
                                    $modelTII = new ToquvInstructionItems();
                                    $modelTII->setAttributes([
                                        'entity_id' => $m['entity_id'],
                                        'quantity' => $m['fact'],
                                        'fact' => $m['fact'],
                                        'entity_type' => ToquvInstructionItems::ENTITY_TYPE_IP,
                                        'toquv_instruction_id' => $instruction->id,
                                        'rm_item_id' => $child->id,
                                        'add_info' => $m['add_info'],
                                        'thread_name' => $m['thread_name'],
                                        'toquv_instruction_rm_id' => $modelTIR->id,
                                        'lot' => ($m['lot'])?$m['lot']:null
                                    ]);
                                    $modelTII->save();
                                }
                            }
                        }
                    }
                }
            }
            $response = [];
            $response['insId'] = $instruction->id;
            $response['ordId'] = $order->id;
            return $response;
        }
        return false;
    }
    public static function getModelOrders($id){
        /*$sql = "SELECT
                    mo.doc_number,
                    moi.id,
                    trm.name,
                    mop.finished_fabric,
                    mop.raw_fabric,
                    mop.thread_length,
                    mop.finish_en,
                    mop.finish_gramaj,
                    moi.model_orders_id
                FROM
                    moi_rel_dept as rel
                left join
                    model_orders_items as moi
                        ON rel.model_orders_items_id = moi.id
                left join
                    model_orders_planning mop
                        on moi.id = mop.model_orders_items_id
                left join
                    toquv_raw_materials trm
                        on mop.toquv_raw_materials_id = trm.id
                left join
                    model_orders mo
                        on moi.model_orders_id = mo.id
                WHERE
                    `toquv_departments_id`=2
                    AND trm.name IS NOT NULL
                    AND mo.status > 3
                    AND mo.id = :id";
        return Yii::$app->db->createCommand($sql)->bindValues(['id'=>$id])->queryAll();*/
        $model = ModelOrdersPlanning::find()->leftJoin('model_orders_items moi','moi.id = model_orders_planning.model_orders_items_id')->leftJoin('moi_rel_dept mrd','mrd.model_orders_items_id = moi.id')->leftJoin('model_orders mo','mo.id = moi.model_orders_id')
            ->leftJoin('model_orders_items_size mois', 'moi.id = mois.model_orders_items_id')->where(['mrd.toquv_departments_id'=>ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SEH'])['id'],'mo.id'=>$id])->all();
        return $model;
    }
    public static function getModelOrdersList($id){
        $sql = "SELECT
                    trm.name,
                    CONCAT(ml.article, ' ',ml.name) model,
                    moi.id m_order_id,
                    mo.id,
                    toquv_raw_materials_id trm_id,
                    mop.thread_length,
                    finish_en,
                    finish_gramaj,
                    summa
                FROM model_orders_planning mop                      
                LEFT JOIN model_orders_items moi ON moi.id = mop.model_orders_items_id                         
                LEFT JOIN moi_rel_dept mrd ON mrd.model_orders_items_id = moi.id                         
                LEFT JOIN model_orders mo ON mo.id = moi.model_orders_id                     
                LEFT JOIN toquv_raw_materials trm on mop.toquv_raw_materials_id = trm.id       
                LEFT JOIN models_list ml on moi.models_list_id = ml.id
                LEFT JOIN (SELECT model_orders_items_id,SUM(count) summa FROM model_orders_items_size mois LEFT JOIN size s on mois.size_id = s.id GROUP BY mois.model_orders_items_id) mois on moi.id = mois.model_orders_items_id
                WHERE (mrd.toquv_departments_id=2) AND (mo.id= %d)";
        $sql = sprintf($sql,$id);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getKaliteRM($id){
        //TODO Elbek siz uchun
        $sql = "select
                    tirm.id,
                    trm.name as mato,
                    tpf.name pus_fine,
                    u.user_fio,
                    SUM(tk.quantity) as quantity,
                    MAX(tk.created_at) created_at,
                    sn.name as sortName 
                from toquv_kalite tk 
                left join toquv_rm_order tro on tk.toquv_rm_order_id = tro.id          
                left join toquv_instructions ti on tk.toquv_instructions_id = ti.id          
                left join toquv_instruction_rm tirm on tk.toquv_instruction_rm_id = tirm.id  
                left join toquv_pus_fine tpf on tirm.toquv_pus_fine_id = tpf.id
                left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id          
                left join users u on tk.user_id = u.id          
                left join sort_name sn on tk.sort_name_id = sn.id 
                WHERE ti.id = %d 
                GROUP BY tirm.id, trm.id, u.id, sn.id 
                ORDER BY tirm.id ASC;";
        $sql = sprintf($sql, $id);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        return $results;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getServiceTypes($key = null){
        $res = [
            1 => Yii::t('app',"O'zimizda"),
            2 => Yii::t('app',"Tashqarida")
        ];
        if($key){
            return $res[$key];
        }
        return $res;
    }

    /**
     * @param null $key
     * @return array
     */
    public function getMusteriList($key = null){
        $musteri = ToquvMusteri::find()->asArray()->all();
        if($key){
            $musteri = ToquvMusteri::find()->where(['id' => $key])->asArray()->one();
            return ArrayHelper::map($musteri,'id','name');
        }
        return ArrayHelper::map($musteri,'id','name');
    }

    public function getColorList()
    {
        $sql = "SELECT clp.id,
                clp.name as cname, clp.code as ccode, r, g, b, type.name as tname
                FROM toquv_instruction_rm tir
                LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                LEFT JOIN color_pantone as clp ON clp.id = tro.color_pantone_id
                LEFT JOIN color_panton_type as type ON clp.color_panton_type_id = type.id
                WHERE tir.toquv_instruction_id = $this->id;
        ";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($list,'id', function($color){
            return "<span style='background:rgb(".$color['r'].",
                            ".$color['g'].",".$color['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>"
            .$color['tname'] . "</span></span> ".$color['ccode'] . " - <b>"
            . $color['cname'] . "</b>";
        });
    }
    /**
     * @return array
     */
    public function getClosedInstructionList(){
        $res = [
            1 => Yii::t('app','Tugatilmagan'),
            2 => Yii::t('app','Tugatilgan')
        ];
        return $res;
    }

    /**
     * @param $ins_id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getInsRM($ins_id){
        $sql = "select tir.is_closed from toquv_instruction_rm tir where 
                tir.toquv_instruction_id = :id ORDER BY tir.quantity DESC;";
        $rm = Yii::$app->db->createCommand($sql)->bindValues(['id' => $ins_id])->queryAll();
        $isAllClosed = true;
        if(!empty($rm)){
            foreach ($rm as $item){
                $isAllClosed = true;
                if($item['is_closed'] == 2){
                    $isAllClosed = false;
                }
            }
        }
        if($isAllClosed){
            return false;
        }else{
            return true;
        }
    }
}
