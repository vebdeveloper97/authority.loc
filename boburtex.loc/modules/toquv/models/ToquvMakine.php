<?php

namespace app\modules\toquv\models;

use app\models\UserRoles;
use app\models\Users;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_makine".
 *
 * @property int $id
 * @property string $m_code
 * @property string $name
*  @property int $type
 * @property int $thread_length
 * @property int $finish_en
 * @property int $finish_gramaj
 * @property int $finish_gramaj_end
 * @property int $toquv_ne
 * @property int $toquv_thread
 * @property int $working_user_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $pus_fine_id
 * @property int $ms_id
 * @property int $raw_material_type_id
 *
 * @property ToquvPusFine $pusFine
 * @property ToquvMakineProcesses $processes
 * @property ToquvMakineUserAction[] $toquvMakineUserActions
 */
class ToquvMakine extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_makine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['m_code', 'name'], 'required'],
            [['type', 'thread_length', 'finish_en', 'finish_gramaj', 'finish_gramaj_end', 'toquv_ne', 'toquv_thread', 'working_user_id', 'status', 'created_at', 'updated_at', 'created_by', 'pus_fine_id', 'ms_id', 'raw_material_type_id'], 'integer'],
            [['m_code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 30],
            [['pus_fine_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvPusFine::className(), 'targetAttribute' => ['pus_fine_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'm_code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Turi'),
            'pus_fine_id' => Yii::t('app', 'Pus/Fine'),
            'thread_length' => Yii::t('app', 'Thread Length'),
            'finish_en' => Yii::t('app', 'Finish En'),
            'finish_gramaj' => Yii::t('app', 'Finish Gramaj Dan'),
            'finish_gramaj_end' => Yii::t('app', 'Finish Gramaj Gacha'),
            'toquv_ne' => Yii::t('app', 'Toquv Ne'),
            'toquv_thread' => Yii::t('app', 'Toquv Thread'),
            'working_user_id' => Yii::t('app', 'Working User ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'raw_material_type_id' => Yii::t('app', 'Xom Ashyo Turi ID'),
            'ms_id' => Yii::t('app', 'Ms ID'),
        ];
    }
    public function getToquvPusFine()
    {
        return $this->hasOne(ToquvPusFine::className(), ['id' => 'pus_fine_id']);
    }
    public function getUserFIO()
    {
        return $this->hasOne(Users::className(), ['id' => 'working_user_id']);
    }

    public function getProcesses($id = null)
    {
        $processes = (!$id) ? $this->process[0] : ToquvMakineProcesses::findOne($id);
        return $processes;
    }
    public function getToquvMakineUserActions()
    {
        return $this->hasMany(ToquvMakineUserAction::className(), ['toquv_makine_id' => 'id']);
    }
        /**
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getMakine(){
        $sql = "SELECT toq.toquv_makine_id,stats.status_name as status ,
                tpf.name as utype,
                toq.action_id as acId,
                toq.stoped as stopedd, 
                stats.status_color as color, 
                tname.name as name,
                tmu.users_id user_id,
                started,
                userr.user_fio as user_fio 
                FROM `toquv_makine_actions` as toq 
                LEFT JOIN toquv_makine tname ON  toq.toquv_makine_id= tname.id
                LEFT JOIN toquv_macines_statuses stats  ON  stats.id= toq.action_id
                LEFT JOIN toquv_pus_fine tpf ON tname.pus_fine_id=tpf.id
                LEFT JOIN users userr ON  userr.id= toq.user_id
                LEFT JOIN toquv_makine_users tmu on tname.id = tmu.toquv_makine_id
                WHERE toq.id in 
                ( SELECT MAX(id) 
                    FROM `toquv_makine_actions` as toqu
                    Group by toqu.toquv_makine_id)
                    GROUP BY tname.id
                    ORDER by toq.toquv_makine_id asc
        ";
        $row = Yii::$app->db->createCommand($sql)->queryAll();
        return $row;
    }
    public static function getUsersMakine($id = null){
        $sql = "SELECT toq.toquv_makine_id,stats.status_name as status ,
                tpf.name as utype,
                toq.action_id as acId,
                toq.stoped as stopedd, 
                stats.status_color as color, 
                tname.name as name,
                tmu.users_id user_id,
                started,
                userr.user_fio as user_fio 
                FROM `toquv_makine_actions` as toq 
                LEFT JOIN toquv_makine tname ON  toq.toquv_makine_id= tname.id
                LEFT JOIN toquv_macines_statuses stats  ON  stats.id= toq.action_id
                LEFT JOIN toquv_pus_fine tpf ON tname.pus_fine_id=tpf.id
                LEFT JOIN users userr ON  userr.id= toq.user_id
                LEFT JOIN toquv_makine_users tmu on tname.id = tmu.toquv_makine_id
                WHERE toq.id in 
                ( SELECT MAX(id) 
                    FROM `toquv_makine_actions` as toqu
                    Group by toqu.toquv_makine_id)
                AND tmu.users_id = %d
                    ORDER by toq.toquv_makine_id asc
        ";
        $user_id = $id ?? Yii::$app->user->id;
        $sql = sprintf($sql,$user_id);
        $row = Yii::$app->db->createCommand($sql)->queryAll();
        return $row;
    }

    public static function getMakineAks()
    {
        $sql = "SELECT tname.id AS toquv_makine_id,
                       tpf.name as utype,
                       tname.name as name,
                       userr.user_fio as user_fio
                FROM `toquv_makine` as tname
                         LEFT JOIN toquv_pus_fine tpf ON tname.pus_fine_id=tpf.id
                         LEFT JOIN users userr ON  userr.id= tname.working_user_id
                WHERE tname.type = 2
        ";
        $row = Yii::$app->db->createCommand($sql)->queryAll();
        return $row;
    }

    public static function getOrder($id)
    {
        $sql = "SELECT toq.toquv_order_item_id
                            from toquv_makine_processes toq
                            where toq.id IN (select max(id)
                            FROM toquv_makine_processes tmp
                            where tmp.machine_id={$id}
                            group by tmp.machine_id)";

        $query = Yii::$app->db->createCommand($sql)->queryOne();
        return ToquvRmOrder::findOne($query['toquv_order_item_id'])->toquvOrders->musteri['name']." - ".ToquvRmOrder::findOne($query['toquv_order_item_id'])->toquvRawMaterials['name'];
    }

    /**
     * @param $sql
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getBySql($sql)
    {
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public static function getToquvMakineYillik($post)
    {
        $year = $post['year']-1;
        $id = $post['id'] ? $post['id'] : 0;
        $firstMonth = $year . '-'.date("m").'-01';
        $nextMonth = $year . '-'.date("m",strtotime("+1 month")).'-01';
        $return = [];
        for ($i = 1; $i <= 12; $i++) {
            $sql = "SELECT SUM(ac.percentage) as perc, COUNT(ac.toquv_makine_id) as countt
                    FROM toquv_macines_daily as ac
                    WHERE ac.toquv_makine_id={$id} and
                    ac.reg_date BETWEEN '{$firstMonth}' AND '{$nextMonth}'";
            $data = Yii::$app->db->createCommand($sql)->queryAll()[0];
            $return[$i - 1]['MonthName'] = date('M', strtotime($firstMonth));
            if (empty($data['perc'])) {
                $return[$i - 1]['percentage'] = 0;
            } else {
                $return[$i - 1]['Percentage'] = $data['perc'] / $data['countt'];
            }
            $firstMonth = $nextMonth;
            $nextMonth = date('Y-m-01', strtotime('next month', strtotime($nextMonth)));
        }
        if ($return)
            return $return;
        return [];
    }

    /**
     * @param $post
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getToquvMakineOylik($post)
    {
        $id = $post['id'] ? $post['id'] : 0;
        $table = array();
        $firstMonth = date('Y-m-', strtotime( "-29 day", strtotime($post['date'] ) ) ).date("d");
        $nextMonth = date('Y-m-d', strtotime('next day', strtotime($firstMonth)));
        for ($i = 1; $i <= 30; $i++) {
            $sql = "SELECT ac.percentage as pero 
                    FROM toquv_macines_daily as ac
                    WHERE ac.toquv_makine_id={$id} and
                    ac.reg_date BETWEEN '{$firstMonth}' AND '{$nextMonth}'";
            $sql2 = "SELECT ac.percentage as pero2 
                    FROM toquv_macines_daily as ac
                    WHERE ac.toquv_makine_id=0 and
                    ac.reg_date BETWEEN '{$firstMonth}' AND '{$nextMonth}'";
            $data = Yii::$app->db->createCommand($sql)->queryAll()[0];
            $data2 = Yii::$app->db->createCommand($sql2)->queryAll()[0];
            $table[$i - 1]['date'] = $firstMonth;
            if (!$data) {
                $table[$i - 1]['distance'] = 0;
            } else {
                $table[$i - 1]['distance'] = $data['pero'];
            }
            if (!$data2) {
                $table[$i - 1]['distance2'] = 0;
            } else {
                $table[$i - 1]['distance2'] = $data2['pero2'];
            }
            $firstMonth = $nextMonth;
            $nextMonth = date('Y-m-d', strtotime('next day', strtotime($nextMonth)));
        }
        return $table;
    }

    /**
     * @param $post
     * @return array|bool
     * @throws \yii\db\Exception
     */
    public static function getToquvMakineKunlik($post){
        $id = $post['id'] ? $post['id'] : 1;
        $startTime = date("Y-m-d H:i:s", strtotime($post['start']));
        $endTime = date("Y-m-d H:i:s", strtotime($post['end']));
        $time = strtotime($endTime) - strtotime($startTime);
        $table = array();
        if ($post['id']==0){
            $sql = "SELECT  ac.action_id,SUM(TIMESTAMPDIFF(SECOND , ac.started, ac.stoped)) as summ
                    FROM toquv_makine_actions AS ac
                    WHERE  ac.stoped is not null and
                    ac.started BETWEEN '{$startTime}' and '{$endTime}'
                    GROUP by ac.action_id";
        }else{

            $sql = "SELECT  ac.action_id,SUM(TIMESTAMPDIFF(SECOND , ac.started, ac.stoped)) as summ
                FROM toquv_makine_actions AS ac 
                WHERE ac.toquv_makine_id={$id} and 
                ac.stoped is not null and 
                ac.started BETWEEN '{$startTime}' and '{$endTime}' 
                GROUP by ac.action_id";
        }
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if (!$result) return false;
        $temp = 0;
        if ($post['id']==0){
            $time=0;
            for ($i = 0; $i < 10; $i++) {
                if ($result[$temp]['action_id'] == $i) {
                    $time +=$result[$temp]['summ'];
                }
            }
        }
        for ($i = 0; $i < 10; $i++) {

            if ($result[$temp]['action_id'] == $i) { //
                $percentage = round((($result[$temp]['summ']) / $time) * 100, 2);
                $table[$i]['litres'] = $percentage;
                $temp++;
            } else {
                $table[$i]['litres'] = 0;
            }
        }
        return $table;
    }
    public static function getPusFineList(){
        $pusfine = ToquvPusFine::find()->asArray()->all();
        return ArrayHelper::map($pusfine, 'id', 'name');
    }
    public function getRawMaterialType($key=null, $id = null)
    {
        $list = ToquvRawMaterialType::find()->all();
        $result = ArrayHelper::map($list, 'id', 'name');
        if(!empty($key)){
            if($key=='options') {
                $options = ArrayHelper::map($list,'id',function($model) use ($id){
                    return ($id && $model->type == $id)?['type' => $model->type]:['type' => $model->type,'disabled'=>'', 'class'=>'hidden'];
                });
                return $options;
            }
            else {
                return $result[$key];
            }
        }
        return $result;
    }

    public static function getSortNameList()
    {
        $sortname = SortName::find()->asArray()->all();
        return ArrayHelper::map($sortname, 'id', 'name');
    }
    public static function getUserList($option=null,$array=null,$code=null){
        $cod = $code ?? 'TOQUV_TOQUVCHI';
        $user_role = UserRoles::find()->select('id')->where(['code'=>$cod])->asArray()->one();
        $users = Users::find()->with('usersInfo')->where(['user_role'=>$user_role,'users.status'=>1])->asArray()->all();
        $user = [];
        foreach ($users as $key) {
            $user['list'][$key['id']] = [
                'data-id' => $key['id'],
                'data-name' => $key['code'] . ' - ' . $key['user_fio'] . ' - ' . $key['usersInfo']['tabel'],
                'data-table' => $key['usersInfo']['tabel'],
            ];
        }
        if($option){
            return $user['list'];
        }
        return ArrayHelper::map($user['list'], 'data-id', 'data-name');
    }

    public function getProcess()
    {
        /*return $this->hasMany(ToquvMakineProcesses::className(), ['machine_id' => 'id'])->orderBy(['id' => SORT_DESC]);*/
        return $this->hasMany(ToquvMakineProcesses::className(), ['machine_id' => 'id'])->leftJoin('toquv_instructions ti', 'toquv_makine_processes.ti_id = ti.id')->where(['ti.is_closed'=>1])->orderBy(['id' => SORT_DESC]);
    }

    public function getRawMaterialsNameType2()
    {
        $sql = "
            select trm.id, trm.name ,trm.density 
            from toquv_raw_materials trm 
            where trm.raw_material_type_id='{$this->raw_material_type_id}'
            ";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if ($result) {
            $arr = [];
            foreach ($result as $key => $item) {
                $arr[$key] = [
                    'id' => $item['id'],
                    'name' => $item['name'] . '  1kg=' . number_format($item['density'], 0) . 'dona'
                ];
            }
            return ArrayHelper::map($arr, 'id', 'name');
        }
        return false;
    }

    public function getMakineRawMaterialsName()
    {
        $section1 = true;
        $sql = "SELECT tro.id   as id ,tk.id  AS idd,m.name as klient,t.document_number as dname ,trm.name as aksessuar ,trm.density  as density, tro.quantity as KgDona
                  from  toquv_rm_order tro
                  left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                  left join raw_material_type rmt on trm.raw_material_type_id = rmt.id
                  left join toquv_orders t on tro.toquv_orders_id = t.id
                  left join musteri m on t.musteri_id = m.id
                  left join toquv_kalite tk on tro.id = tk.toquv_rm_order_id
                  where t.type=2 and t.status=3 and  tk.toquv_makine_id='{$this->id}'order by tk.id DESC limit 1 ";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if (!$result) {
            $section1 = false;
            $sql = "SELECT   trm.name ,tk.id  AS id ,trm.density as density
                    from toquv_kalite tk
                             left join  toquv_makine tm on tm.id=tk.toquv_makine_id
                             left join toquv_raw_materials trm on tk.toquv_raw_materials_id= trm.id
                    where tm.id='{$this->id}'order by tk.id DESC limit 1";
            $result = Yii::$app->db->createCommand($sql)->queryAll();
        }
        if ($result) {
            $result = $result[0];
            if ($section1) {
                $arr = $result['klient'] . ' | Doc->' . $result['dname'] . '| Turi->' . $result['aksessuar'] . '| 1kg=' . number_format($result['density'], 0) . 'dona | Zakaz->' . number_format($result['KgDona'], 0) . 'Kg';
            } else {
                $arr = 'Turi->' . $result['name'] . '|  1kg=' .$result['density']. 'dona';
            }
            if (!empty($result['klient'])) {
                return $arr;
            }
        }
        return false;
    }


    public static function getMakineRawMaterialName($id)
    {
        $sql ="select  tk.id,trm.name
                from toquv_kalite tk
                left join  toquv_makine tm on tm.id=tk.toquv_makine_id
                left join toquv_raw_materials trm on tk.toquv_raw_materials_id= trm.id
                where tm.id='{$id}' order by tk.id DESC limit 1";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if ($result){
            return $result[0]['name'];
        }
        return false;
    }

    public function getProccesList($array=false){
//        $procces = $this->process;
        $procces = ToquvMakineProcesses::find()->leftJoin('toquv_instructions ti', 'toquv_makine_processes.ti_id = ti.id')->where(['ti.is_closed'=>1,'machine_id'=>$this->id])->orderBy(['id' => SORT_DESC])->limit(1)->all();
        $arr = [];
        foreach ($procces as $key => $item){
            $arr[$key] = [
                'id' => $item['id'],
                'name' => "{$item->toquvOrder->musteri->name} - {$item->toquvOrderItem->toquvRawMaterials->name} ({$item->toquvOrderItem->quantity}) - {$item->toquvInstructionRm->toquvPusFine->name}
                ({$item->toquvOrder->document_number})"
            ];
        }
        if($array){
            return $arr;
        }
        return ArrayHelper::map($arr, 'id','name');
    }
    public function getProccesAksList($last=null){
        $sql = "SELECT  tir.id as id, 
                        ti.id ti_id,
                        tro.id tro_id,
                        m.name as musteri,
                        tor.document_number as doc_number,
                        trm.name as aksessuar,
                        trmc.name as color,
                        trm.id mato_id,
                        trm.density density, 
                        tro.quantity as quantity,
                        tpf.name pus_fine,
                        tir.thread_length,
                        tir.finish_en,
                        tir.finish_gramaj,
                        CONCAT('(',tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj,')') info,
                        m2.name order_musteri
            from toquv_instruction_rm tir
            left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
            left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
            left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
            left join raw_material_type rmt on trm.raw_material_type_id = rmt.id
            left join toquv_orders tor on tro.toquv_orders_id = tor.id
            left join musteri m on tor.musteri_id = m.id
            left join toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
            left join toquv_instructions ti on tir.toquv_instruction_id = ti.id
            left join model_orders_items moi on tir.moi_id = moi.id
            left join model_orders mo on moi.model_orders_id = mo.id
            left join musteri m2 on mo.musteri_id = m2.id
            where tor.type = 2 and tor.status = 3 and tir.status = 3 and ti.status = 3
            and tpf.id = {$this->pus_fine_id}
            order by tir.planed_date DESC
            ";
        if($last){
            return Yii::$app->db->createCommand($sql)->queryOne();
        }
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if ($result) {
            $arr = [];
            foreach ($result as $key => $item) {
                $musteri = (!empty($item['order_musteri']))?" ({$item['order_musteri']})":'';
                $arr['list'][$key] = [
                    'id' => $item['id'],
                    'name' => "<b>".$item['musteri'] . $musteri . '</b> ' . $item['doc_number'] ." ". $item['color']. ' <b>' . $item['aksessuar'] ."</b> | {$item['info']} | Zakaz => <b>" . number_format($item['quantity'],2,'.',' ').'</b> Kg'
                ];
                $arr['options'][$item['id']] = ['mato_id' => $item['mato_id']];
            }
            $res = [];
            $res['list'] = ArrayHelper::map($arr['list'], 'id','name');
            $res['options'] = $arr['options'];
            return $res;
        }
        return false;
    }
    public static function getTir($id){

        $sql = "SELECT  tir.id  as id, 
                        ti.id ti_id,
                        tro.id tro_id,
                        m.name as musteri,
                        tor.document_number as doc_number,
                        trm.name as aksessuar,
                        trm.id mato_id,
                        trm.density density, 
                        tro.quantity as quantity,
                        tpf.name pus_fine,
                        tir.thread_length,
                        tir.finish_en,
                        tir.finish_gramaj,
                        m2.name order_musteri
                from toquv_instruction_rm tir
                left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                left join raw_material_type rmt on trm.raw_material_type_id = rmt.id
                left join toquv_orders tor on tro.toquv_orders_id = tor.id
                left join musteri m on tor.musteri_id = m.id
                left join toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                left join toquv_instructions ti on tir.toquv_instruction_id = ti.id
                left join model_orders_items moi on tir.moi_id = moi.id
                left join model_orders mo on moi.model_orders_id = mo.id
                left join musteri m2 on mo.musteri_id = m2.id
                where tir.id = %d
            ";
        $sql = sprintf($sql,$id);
        $result = Yii::$app->db->createCommand($sql)->queryOne();
        if ($result) {
            return $result;
        }
        return false;
    }
    public static function getDefects(){
        $defects = ToquvRmDefects::find()->asArray()->all();
        return $defects;
    }
}
