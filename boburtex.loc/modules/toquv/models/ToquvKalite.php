<?php

namespace app\modules\toquv\models;

use app\components\behaviors\log\LogBehavior;
use app\components\OurCustomBehavior;
use app\models\Users;
use app\models\UsersInfo;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_kalite".
 *
 * @property int $id
 * @property int $toquv_instructions_id
 * @property int $toquv_instruction_rm_id
 * @property int $toquv_rm_order_id
 * @property int $toquv_makine_id
 * @property int $user_id
 * @property string $quantity
 * @property int $sort_name_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $type
 * @property int $toquv_raw_materials_id
 * @property int $order
 * @property string $code
 * @property string $smena
 * @property double $count
 * @property double $roll
 * @property int $user_kalite_id
 * @property string $send_date
 * @property int $send_user_id
 * @property int $updated_by
 *
 * @property RollInfo[] $rollInfos
 * @property SortName $sortName
 * @property ToquvInstructionRm $toquvInstructionRm
 * @property ToquvInstructions $toquvInstructions
 * @property ToquvRmOrder $toquvRmOrder
 * @property ToquvRawMaterials $toquvRawMaterials
 * @property ToquvMakine $toquvMakine
 * @property ToquvKaliteDefects[] $toquvKaliteDefects
 */
class ToquvKalite extends BaseModel
{
    public $logIgnoredAttributes = ['updated_at','send_user_id','status','updated_by','send_date'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_kalite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_instructions_id', 'toquv_instruction_rm_id', 'toquv_rm_order_id', 'toquv_makine_id', 'user_id', 'sort_name_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'type', 'toquv_raw_materials_id', 'order', 'user_kalite_id', 'send_user_id'], 'integer'],
            [['quantity', 'count', 'roll'], 'number'],
            [['send_date'], 'safe'],
            [['code'], 'string', 'max' => 60],
            [['smena'], 'string', 'max' => 3],
            [['sort_name_id'], 'exist', 'skipOnError' => true, 'targetClass' => SortName::className(), 'targetAttribute' => ['sort_name_id' => 'id']],
            [['toquv_instruction_rm_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvInstructionRm::className(), 'targetAttribute' => ['toquv_instruction_rm_id' => 'id']],
            [['toquv_instructions_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvInstructions::className(), 'targetAttribute' => ['toquv_instructions_id' => 'id']],
            [['toquv_rm_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRmOrder::className(), 'targetAttribute' => ['toquv_rm_order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_instructions_id' => Yii::t('app', 'Toquv Instructions ID'),
            'toquv_instruction_rm_id' => Yii::t('app', 'Toquv Instruction Rm ID'),
            'toquv_rm_order_id' => Yii::t('app', 'Toquv Rm Order ID'),
            'toquv_makine_id' => Yii::t('app', 'Toquv Makine ID'),
            'user_id' => Yii::t('app', 'To\'quvchi'),
            'quantity' => Yii::t('app', 'Quantity'),
            'sort_name_id' => Yii::t('app', 'Sort'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', "O'zgartirdi"),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'type' => Yii::t('app', 'Type'),
            'toquv_raw_materials_id' => Yii::t('app', 'Toquv Raw Materials ID'),
            'order' => Yii::t('app', 'Order'),
            'code' => Yii::t('app', 'Code'),
            'smena' => Yii::t('app', 'Smena'),
            'count' => Yii::t('app', 'Count'),
            'roll' => Yii::t('app', 'Roll'),
            'user_kalite_id' => Yii::t('app', 'Tekshiruvchi'),
            'send_date' => Yii::t('app', "Omborga jo'natilgan sana"),
            'send_user_id' => Yii::t('app', "Omborga jo'natuvchi"),
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
            ],
            [
                'class' => LogBehavior::className()
            ]
        ];
    }
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->send_date) {
                $this->send_date = date('Y-m-d H:i:s', strtotime($this->send_date));
            }
            return true;
        }else{
            return false;
        }
    }
    public function afterFind()
    {
        parent::afterFind();
        if($this->send_date) {
            $this->send_date = date('Y-m-d H:i', strtotime($this->send_date));
        }
    }
    public static function getStatusList($key = null){
        $result = [
            self::STATUS_ACTIVE   => Yii::t('app',"To'quv sexida"),
            self::STATUS_INACTIVE => Yii::t('app','Deleted'),
            self::STATUS_SAVED => Yii::t('app',"Omborga jo'natilgan"),
            self::STATUS_ACCEPTED => Yii::t('app',"Ishchiga berilgan"),
        ];
        if(!empty($key)){
            return $result[$key];
        }

        return $result;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRollInfos()
    {
        return $this->hasMany(RollInfo::className(), ['toquv_kalite_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvInstructionRm()
    {
        return $this->hasOne(ToquvInstructionRm::className(), ['id' => 'toquv_instruction_rm_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSortName()
    {
        return $this->hasOne(SortName::className(), ['id' => 'sort_name_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvInstructions()
    {
        return $this->hasOne(ToquvInstructions::className(), ['id' => 'toquv_instructions_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvRmOrder()
    {
        return $this->hasOne(ToquvRmOrder::className(), ['id' => 'toquv_rm_order_id']);
    }

    public function getToquvRawMaterials()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'toquv_raw_materials_id']);
    }

    public function getToquvMakine()
    {
        return $this->hasOne(ToquvMakine::className(), ['id' => 'toquv_makine_id']);
    }
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
    public function getUserKalite()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_kalite_id']);
    }
    public function getSendedUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'send_user_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvKaliteDefects()
    {
        return $this->hasMany(ToquvKaliteDefects::className(), ['toquv_kalite_id' => 'id']);
    }
    public function getGroupDefects()
    {
        $defect = ToquvKaliteDefects::find()->select(['COUNT(*) as quantity', 'SUM(metr) as metr'])->where(['toquv_kalite_id'=>$this->id])->groupBy(['toquv_rm_defects_id'])->all();
        return $defect;
    }
    public function saveKalite($data){
        $model = new ToquvKalite();
        $rm_order = ToquvRmOrder::findOne($data['toquv_rm_order_id']);
        $type = ToquvRawMaterials::findOne($data['toquv_raw_materials_id'])['type'];
        $curr_date = strtotime(date('Y-m-d'). " 00:00:00");
        $last = ToquvKalite::find()->where("created_at >= {$curr_date}")->andWhere("type = {$type}")->orderBy(['id'=>SORT_DESC])->one();
        $check_date = time();
        $user_id = Yii::$app->user->identity->id;
        if(($check_date-$last['created_at'])<=30&&$last['user_kalite_id']==$user_id&&$last['toquv_makine_id']==$data['toquv_makine_id']&&$last['user_id']==$data['user_id']&&$last['quantity']==$data['quantity']){
            return $last;
        }
        $order = ($last&&$last->order)?$last->order+1:1;
        $day = date('dmy')."/".$order;
        $model->setAttributes([
            'toquv_instructions_id' => $data['toquv_instructions_id'],
            'toquv_instruction_rm_id' => $data['toquv_instruction_rm_id'],
            'toquv_rm_order_id' => $data['toquv_rm_order_id'],
            'toquv_makine_id' => $data['toquv_makine_id'],
            'user_id' => $data['user_id'],
            'quantity' => $data['quantity'],
            'count' => $data['count'],
            'roll' => $data['roll'],
            'sort_name_id' => $data['sort_name_id'],
            'type' => ($type)?$type:1,
            'toquv_raw_materials_id' => $data['toquv_raw_materials_id'],
            'order' => $order,
            'code' => $day,
            'smena' => $data['smena'] ?? UsersInfo::findOne(['users_id'=>$data['user_id']])->smena,
            'user_kalite_id' => $user_id
        ]);
        if ($model->save()) {
            if (!empty($data['defects'])) {
                foreach ($data['defects'] as $n){
                    foreach($n as $m => $key) {
                        $defect = new ToquvKaliteDefects();
                        $defect->setAttributes([
                            'toquv_kalite_id' => $model->id,
                            'toquv_rm_defects_id' => $key['id'],
                            'quantity' => $m,
                            'metr' => $key['metr'],
                            'from' => $key['from'],
                            'to' => $key['to'],
                        ]);
                        $defect->save();
                        /*if ($key['id']==1||$key['id']==2){
                            $qty++;
                        }
                        if ($key['id']==4||$key['id']==5||$key['id']==6){
                            $metr += (is_numeric($key['metr']))?$key['metr']:0;
                        }*/
                    }
                }
            }
            /*if($qty<=5&&$metr<=5){
                $sort = SortName::findOne(['code' => 'SORT1'])->id ?? 1;
            }else if((($qty>5 && $qty<10) || ($metr>5 && $metr<10)) && ($qty<10&&$metr<10)){
                $sort = SortName::findOne(['code' => 'SORT2'])->id ?? 2;
            }else{
                $sort = SortName::findOne(['code' => 'BRAK'])->id ?? 3;
            }
            $model->sort_name_id = $sort;
            $model->save();*/
            return $model;
        }
        return false;
    }
    public function saveDefects($data){
        $model = ToquvKalite::findOne($data['id']);
        if($model){
            $model->sort_name_id = $data['sort_name_id'];
            $model->save();
            if (!empty($data['defects'])) {
                foreach ($data['defects'] as $n){
                    foreach($n as $m => $key) {
                        $defect = new ToquvKaliteDefects();
                        $defect->setAttributes([
                            'toquv_kalite_id' => $model->id,
                            'toquv_rm_defects_id' => $key['id'],
                            'quantity' => $m,
                            'metr' => $key['metr'],
                            'from' => $key['from'],
                            'to' => $key['to'],
                        ]);
                        $defect->save();
                    }
                }
            }
            return $model;
        }
        return false;
    }

    public function getDefect($type)
    {
        $query = ToquvKaliteDefects::find()->where(['toquv_kalite_id'=>$this->id]);
        switch ($type){
            case 1:
                $query = $query->andWhere(['in', 'toquv_rm_defects_id', '1,5']);
                break;
            case 2:
                $query = $query->andWhere(['in', 'toquv_rm_defects_id', '2']);
                break;
        }
        $count = $query->count();
        return $count;
    }

    public static function getTotalNew($provider, $type=1)
    {
        $total = 0;
        foreach ($provider as $item) {
            $total += $item->getDefect($type);
        }

        return $total;
    }
    public static function getMakineList($type=1)
    {
        $sql = "select tm.id, tm.name
                        from toquv_makine tm
                        where tm.type = %d
                        and tm.id in 
                                    ( select tk.toquv_makine_id
                                    from toquv_kalite tk
                                     group by tk.toquv_makine_id )";
        $sql = sprintf($sql,$type);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $arr = ArrayHelper::map($res, 'id', 'name');
        return $arr;
    }
    public static function getMusteriList()
    {
        $sql = "select m.id, m.name
                        from musteri m
                        where m.id in 
                                    ( select tor.musteri_id
                                    from toquv_kalite tk
                                    left join toquv_rm_order tro on tk.toquv_rm_order_id = tro.id
                                    left join toquv_orders tor on tro.toquv_orders_id = tor.id
                                     group by tor.musteri_id )";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $arr = ArrayHelper::map($res, 'id', 'name');
        return $arr;
    }
    public static function getToquvOrdersList()
    {
        $sql = "select tor.id, tor.document_number name
                        from toquv_orders tor
                        where tor.id in 
                                    ( select tro.toquv_orders_id
                                    from toquv_kalite tk
                                    left join toquv_rm_order tro on tk.toquv_rm_order_id = tro.id
                                     group by tro.id )";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $arr = ArrayHelper::map($res, 'id', 'name');
        return $arr;
    }
    public static function getMatoList($entity_type=null)
    {
        $sql = "SELECT
                    raw.id,
                    raw.code,
                    raw.name as rname,
                    type.name as tname,
                    ne_id,
                    tn.name ne,
                    tt.name thread 
                FROM
                    toquv_raw_materials as raw          
                LEFT JOIN
                    raw_material_type as type                    
                        ON raw.raw_material_type_id = type.id     
                LEFT JOIN
                    toquv_raw_material_ip trmi 
                        on raw.id = trmi.toquv_raw_material_id     
                LEFT JOIN
                    toquv_ne tn 
                        on trmi.ne_id = tn.id     
                LEFT JOIN
                    toquv_thread tt 
                        on trmi.thread_id = tt.id
                WHERE raw.id in ( select tro.toquv_raw_materials_id
                                    from toquv_kalite tk
                                    left join toquv_rm_order tro on tk.toquv_rm_order_id = tro.id
                                     group by tro.id )
                %s
        ";
        $type = ($entity_type)?" AND raw.type = {$entity_type}":"";
        $sql = sprintf($sql,$type);
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        $ip = [];
        foreach ($acs as $item) {
            $ip[$item['id']]['ip'] .= " (".$item['ne']."-".$item['thread'] . ")";
            $res[$item['tname']][$item['id']] = $item['code'] ." - <b>". $item['rname'] . " - " . $item['tname'] ."</b>" . $ip[$item['id']]['ip'];
        }
        return $res;
    }
    public static function getAksMakineList()
    {
        $sql = "select tm.id, tm.name
                        from toquv_makine tm
                        where m_code like 'M%'
                        and tm.id in 
                                    ( select tk.toquv_makine_id
                                    from toquv_kalite tk
                                     group by tk.toquv_makine_id )";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $arr = ArrayHelper::map($res, 'id', 'name');
        return $arr;
    }
    public static function getMaterialList(){
        $sql = "SELECT raw.id, raw.name
            FROM toquv_makine_processes tmp
            LEFT JOIN toquv_rm_order tro ON tro.id = tmp.toquv_order_item_id
            LEFT JOIN toquv_raw_materials raw ON raw.id = tro.toquv_raw_materials_id
            GROUP BY raw.id 
            ";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $arr = ArrayHelper::map($res,'id','name');
        return $arr;
    }
    public static function getPusFineList(){
        $sql = "SELECT tpf.id, tpf.name
            FROM toquv_kalite tk
            LEFT JOIN toquv_instruction_rm tir on tk.toquv_instruction_rm_id = tir.id
            LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
            GROUP BY tpf.id 
            ";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $arr = ArrayHelper::map($res,'id','name');
        return $arr;
    }
    public static function getAksMaterialList()
    {
        $sql = "select trm.id, trm.name
                from toquv_raw_materials trm
                where trm.id in ( select tk.toquv_raw_materials_id
                                  from toquv_kalite tk
                                  where tk.type=2
                                  group by tk.toquv_raw_materials_id);
            ";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $arr = ArrayHelper::map($res, 'id', 'name');
        return $arr;
    }

    public static function getDocumentNameList()
    {
        $sql = "select 
                    tro.id as id, 
                    tro.quantity as sum,
                    t.document_number as doc,
                    m.name as name,
                    tro.created_at
                from toquv_rm_order tro
                left join toquv_orders t on tro.toquv_orders_id = t.id
                left join musteri m on t.musteri_id = m.id
                left join toquv_kalite k on tro.id = k.toquv_rm_order_id
                where tro.id in ( select tk.toquv_rm_order_id
                  from toquv_kalite tk
                  where tk.type=2
                  group by tk.toquv_rm_order_id )
                order by tro.created_at desc";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $user = [];
        if ($res) {
            foreach ($res as $key) {
                $user[$key['id']] = [
                    'id' => $key['id'],
                    'name' => $key['name'] . '|  ' . number_format($key['sum'],0) . 'kg |  ' . $key['doc']
                ];
            }
        }
        return ArrayHelper::map($user, 'id', 'name');
    }

    public static function getInstructionsList()
    {
        $sql = "SELECT tor.id, tor.document_number
                FROM toquv_makine_processes tmp
                         LEFT JOIN toquv_rm_order tro ON tro.id = tmp.toquv_order_item_id
                         LEFT JOIN toquv_orders tor ON tor.id = tro.toquv_orders_id
                         LEFT JOIN toquv_instructions ti ON ti.toquv_order_id = tor.id
                GROUP BY tro.toquv_orders_id
            ";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $arr = ArrayHelper::map($res,'id','document_number');
        return $arr;
    }

    public static function getInstructionsWithKalite(){

        $sql = "select ti.id
                from toquv_instructions ti
                left join toquv_kalite tk on ti.id = tk.toquv_instructions_id
                left join sort_name sn on tk.sort_name_id = sn.id
                where sn.id = 1 GROUP BY ti.id ORDER BY ti.id DESC;";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getToquvKaliteWithDefects($id){

        $sql = "select tk.toquv_instructions_id,
                       trm.id as matoid,
                       ti.reg_date,
                       tor.document_number,
                       tk.quantity as qty,
                       trm.name as mato,
                       m.name as musteri,
                       sn.name as sort,
                       tkd.quantity as def_qty,
                       trd.name as def,
                       u.user_fio,
                       tm.name as machine,
                       tm.m_code
                from  toquv_kalite tk
                         left join users u on tk.created_by = u.id
                         left join toquv_makine tm on tk.toquv_makine_id = tm.id
                         left join toquv_instructions ti on tk.toquv_instructions_id = ti.id
                         left join toquv_orders tor on ti.toquv_order_id = tor.id
                         left join musteri m on tor.musteri_id = m.id
                         left join toquv_rm_order tro on tk.toquv_rm_order_id = tro.id
                         left join sort_name sn on tk.sort_name_id = sn.id
                         left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                         left join toquv_kalite_defects tkd on tk.id = tkd.toquv_kalite_id
                         left join toquv_rm_defects trd on tkd.toquv_rm_defects_id = trd.id
                WHERE sn.id = 1 AND ti.id = %d
                LIMIT 1000;";
        $sql = sprintf($sql, $id);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function getAksBuyurtmaList($status){
        $type = ToquvRawMaterials::ACS;
        $sql = "SELECT
                    tir.id as id ,
                    tk.id  AS idd,
                    m.name as klient,
                    t.document_number as dname ,
                    trm.name as aksessuar ,
                    trmc.name as color ,
                    trm.density  as 1kg_miqdori,
                    tir.quantity as KgDona ,
                    m2.name order_musteri,
                    CONCAT(tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj) info,
                    tir.thread_length,
                    tir.finish_en,
                    tir.finish_gramaj,
                    tro.done_date as donedate %s %s          
                from toquv_instruction_rm tir
                left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                left join toquv_raw_material_color trmc ON trm.color_id = trmc.id           
                left join raw_material_type rmt on trm.raw_material_type_id = rmt.id           
                left join toquv_orders t on tro.toquv_orders_id = t.id           
                left join musteri m on t.musteri_id = m.id
                ##left join toquv_rm_order trmo on trmo.toquv_orders_id=t.id           
                left join toquv_kalite tk on tro.id = tk.toquv_rm_order_id      
                left join model_orders_items moi on moi.id = tro.moi_id
                left join model_orders mo on moi.model_orders_id = mo.id
                left join musteri m2 on mo.musteri_id = m2.id
                where t.type=$type 
                    and t.status=3 
                    and tir.status={$status}            
                order by tir.id desc";
        $sel = '';
        $sel2 = '';
        if($status == 3){
            $sel = " ,(SELECT  sum(tk2.quantity)
                     from  toquv_kalite tk2
                     where tk2.toquv_rm_order_id=tk.toquv_rm_order_id) as sum ";
        }
        else if($status == 2){
            $sel2 = ",(SELECT  sum(tk2.quantity)
                     from  toquv_kalite tk2
                     where tk2.toquv_rm_order_id=tk.toquv_rm_order_id) as sum ,tro.planed_date as planned ,tro.created_at as created ,tro.finished_date as finshed";
        }
        $sql = sprintf($sql,$sel,$sel2);
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if ($result) {
            $arr = [];
            foreach ($result as $key => $item) {
                $musteri = (!empty($item['order_musteri']))?" ({$item['order_musteri']})":'';
                if ($status==1){
                    $arr[$key] = [
                        'id' => $item['id'],
                        'name' => $item['klient'] . $musteri . ' - '. $item['dname'] .' | ' .$item['color'] .' ' . $item['aksessuar'].' | ' .$item['info'] . ' | ' . number_format($item['KgDona'], 0,",",".") . 'Kg | ' .  date('d.m',strtotime($item['donedate']) )
                    ];
                }else if ($status==2){
                    $temp=$item['sum'];
                    if (empty($item['sum'])){
                        $temp=0;
                    }
                    $arr[$key] = [
                    'id' => $item['id'],  // todo ombordagi qoldiqni olib kelish kerak item_balance entity _type = toquv aks va entity_id  si raw material
                        'name' => $item['klient'] . $musteri . ' | ' . $item['dname'] . '| ' .$item['color'] .' ' . $item['aksessuar'].' | ' .$item['info'] .' | B '.  date('d.m',$item['created'])  . ' | P ' . date('d.m',strtotime($item['planned']) )   . ' | T ' . date('d.m',strtotime($item['finshed']) ). ' | TK ' . date('d.m',strtotime($item['donedate']) ) . ' | (' . number_format($temp, 0,",",".").'T /'.number_format($item['KgDona'], 0,",","."). 'TK) Kg'  ];
                }else if ($status==3){
                    $temp=$item['sum'];
                    if (empty($item['sum'])){
                        $temp=0;
                    }
                    $arr[$key] = [
                    'id' => $item['id'],
                    'name' => $item['klient'] . $musteri . ' | ' . date('d.m',strtotime($item['donedate']) ) . ' | ' . $item['dname'] . '| ' .$item['color'] .' ' .  $item['aksessuar'].' | ' .$item['info'] . ' | (' . number_format($temp, 0,",",".").'/'.number_format($item['KgDona'], 0,",","."). ') Kg'   ];
                }else {
                    $arr[$key] = [
                        'id' => $item['id'],
                        'name' => $item['klient'] . $musteri . ' | Muddat=>' . $item['donedate'] . ' | Doc=>' . $item['dname'] . '| Turi=>' .$item['color'] .' ' .  $item['aksessuar'].' | ' .$item['info'] . ' | Zakaz=>' . number_format($item['KgDona'], 0,",",".") . 'Kg'];
                }
            }
            $res = ArrayHelper::map($arr, 'id','name');
            return $res;
        }
        return [];
    }
    public static function getOneKalite($id = null, $st = null, $brak = null, $type = 1)
    {
        $tir = '';
        if($id){
            $tir = "AND (tir.id = {$id})";
        }
        if($st){
            $status = "AND (tk.status = {$st})";
        }
        $br = " (sn.code != 'BRAK')";
        if($brak){
            $br = " (sn.code = '{$brak}')";
        }
        $tip = '';
        if($type){
            $tip = " AND (tk.type = {$type})";
        }
        $sql = "SELECT
                moi.id moi_id,
                m2.name order_musteri,
                tir.id tir_id,
                tpf.id pus_fine_id,
                tpf.name pus_fine,
                trm.id mato_id,
                trm.name mato,
                trmc.name as mato_color,
                t.document_number doc_number,
                t.id toquv_orders_id,
                t.order_type order_type,
                tro.id toquv_rm_order_id,
                m.id musteri,
                m.name musteri_id,
                SUM(tk.quantity) summa,
                SUM(tk.count) count,
                COUNT(tk.id) roll,
                tro.quantity quantity,
                CONCAT(tir.thread_length,'|',tir.finish_en,'|',tir.finish_gramaj) info,
                tir.thread_length,
                tir.finish_en,
                tir.finish_gramaj,
                cp.code,
                c.color_id
                FROM toquv_kalite tk
                         LEFT JOIN toquv_instruction_rm tir on tk.toquv_instruction_rm_id = tir.id
                         LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                         LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                         LEFT JOIN color_pantone cp ON tro.color_pantone_id = cp.id
                         LEFT JOIN color c ON tro.color_id = c.id
                         LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                         LEFT JOIN musteri m ON t.musteri_id = m.id
                         LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                         left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                         LEFT JOIN model_orders_items moi ON tro.moi_id = moi.id
                         LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                         LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                         LEFT JOIN sort_name sn on tk.sort_name_id = sn.id
                WHERE   %s 
                        %s 
                        %s 
                        %s 
                GROUP BY tir.id";
        $sql = sprintf($sql, $br, $tir, $status, $tip);
        return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public static function getOneBrakKalite($id = null, $st = null)
    {
        $tir = '';
        if($id){
            $tir = "AND (tir.id = {$id})";
        }
        if($st){
            $status = "AND (tk.status = {$st})";
        }
        $sql = "SELECT
                moi.id moi_id,
                m2.name order_musteri,
                tir.id tir_id,
                tpf.id pus_fine_id,
                tpf.name pus_fine,
                trm.id mato_id,
                trm.name mato,
                t.document_number doc_number,
                t.id toquv_orders_id,
                tro.id toquv_rm_order_id,
                m.id musteri,
                m.name musteri_id,
                SUM(tk.quantity) summa,
                tro.quantity quantity,
                CONCAT(tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj) info,
                tir.thread_length,
                tir.finish_en,
                tir.finish_gramaj
                FROM toquv_kalite tk
                         LEFT JOIN toquv_instruction_rm tir on tk.toquv_instruction_rm_id = tir.id
                         LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                         LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                         LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                         LEFT JOIN musteri m ON t.musteri_id = m.id
                         LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                         LEFT JOIN model_orders_items moi on tro.moi_id = moi.id
                         LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                         LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                         LEFT JOIN sort_name sn on tk.sort_name_id = sn.id
                WHERE (sn.code = 'BRAK') 
                        %s 
                        %s 
                GROUP BY tir.id";
        $sql = sprintf($sql, $tir, $status);
        return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public static function getAllKalite($id = null, $mato_id = null, $pus_fine_id = null, $thread_length = null, $finish_en = null, $finish_gramaj = null, $st = 1, $brak = null, $type = 1)
    {
        $tir = '';
        if($id){
            $tir = "AND (tir.id = {$id})";
        }
        $mato = '';
        /*if($mato_id){
            $mato = "AND (trm.id = {$mato_id})";
        }*/
        $pus = '';
        /*if($pus_fine_id){
            $pus = "AND (tpf.id = {$pus_fine_id})";
        }*/
        $thread = '';
        /*if($thread_length){
            $thread = "AND (tir.thread_length = {$thread_length})";
        }*/
        $en = '';
        /*if($finish_en){
            $en = "AND (tir.finish_en = {$finish_en})";
        }*/
        $gramaj = '';
        /*if($finish_gramaj){
            $gramaj = "AND (tir.finish_gramaj = {$finish_gramaj})";
        }*/
        $status = '';
        if($st){
            $status = "AND (tk.status = {$st})";
        }
        $br = " (sn.code != 'BRAK')";
        if($brak){
            $br = " (sn.code = '{$brak}')";
        }
        $tip = '';
        if($type){
            $tip = " AND (tk.type = {$type})";
        }
        $sql = "SELECT
                tk.id id,
                tk.code code,
                tir.id tir_id,
                tpf.id pus_fine_id,
                tpf.name pus_fine,
                trm.id mato_id,
                trm.name mato,
                t.document_number doc_number,
                t.id toquv_orders_id,
                tro.id toquv_rm_order_id,
                m.name musteri_id,
                tk.quantity summa,
                tk.count count,
                tk.roll roll,
                tro.quantity quantity,
                CONCAT(tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj) info,
                tir.thread_length,
                tir.finish_en,
                tir.finish_gramaj,
                tk.created_at created_at,
                tk.status status,
                u.user_fio user_fio,
                sn.id sort_id,
                sn.name sort
                FROM toquv_kalite tk
                         LEFT JOIN toquv_instruction_rm tir on tk.toquv_instruction_rm_id = tir.id
                         LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                         LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                         LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                         LEFT JOIN musteri m ON t.musteri_id = m.id
                         LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                         LEFT JOIN sort_name sn on tk.sort_name_id = sn.id
                         LEFT JOIN users u ON tk.user_id = u.id
                WHERE   %s 
                        %s
                        %s 
                        %s
                        %s
                        %s
                        %s
                        %s
                        %s";
        $sql = sprintf($sql, $br, $tir, $mato, $pus, $thread, $en, $gramaj, $status, $tip);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
}
