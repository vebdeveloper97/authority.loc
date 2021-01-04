<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 03.01.20 19:22
 */

namespace app\modules\toquv\models;


use Yii;
use yii\base\Model;
use yii\db\Exception as ExceptionAlias;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\ToquvUserDepartment;

/**
 * ToquvDepartmentsSearch represents the model behind the search form of `app\modules\toquv\models\ToquvDepartments`.
 *
 * @property array $musteri
 * @property array $belongToDepartments
 */
class RemainSearchMato extends Model
{

    const SCENARIO_INCOMING     = 'incoming';
    const SCENARIO_MOVING       = 'moving';
    const SCENARIO_INSIDE_MOVING       = 'inside-moving';
    const SCENARIO_OUTCOMING    = 'outcoming';
    const SCENARIO_SERVICE      = 'service';
    const SCENARIO_WRITE_OFF    = 'write-off';
    const SCENARIO_ALL          = 'all';
    const SCENARIO_KALITE       = 'kalite';

    public $is_accepted;
    public $from_date;
    public $department_id;
    public $to_department;
    public $entity_type;
    public $document_type;
    public $to_date;
    public $sort_id;
    public $entity_ids;
    public $musteri_id;
    public $is_own;
    public $add_info;
    public $type;
    public $to_musteri;
    public $pus_fine;
    public $date;
    public $thread_length;
    public $finish_en;
    public $finish_gramaj;
    public $group_by_type;
    public $group_by_user;
    public $makine_id;
    public $user_id;
    public $user_kalite_id;
    public $created_at;
    public $from_musteri;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            ['department_id', 'required'],
            [['entity_type','department_id','is_own', 'to_department','document_type','makine_id','user_id','user_kalite_id','from_musteri','to_musteri'],'integer'],
            [['is_accepted','add_info', 'thread_length', 'finish_en', 'finish_gramaj'],'string'],
            [['from_date', 'to_date', 'sort_id','entity_ids','pus_fine','date','group_by_type','group_by_user','created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_INCOMING   =>  ['from_date', 'to_date', 'sort_id','entity_ids','document_type','entity_type','department_id','is_own','to_department', 'musteri_id', 'pus_fine', 'thread_length', 'finish_en', 'finish_gramaj','created_at'],
            self::SCENARIO_MOVING     =>  ['from_date', 'to_date', 'sort_id','entity_ids','document_type','entity_type','department_id','is_own','to_department', 'musteri_id', 'pus_fine', 'thread_length', 'finish_en', 'finish_gramaj','created_at'],
            self::SCENARIO_INSIDE_MOVING     =>  ['from_date', 'to_date', 'sort_id','entity_ids','document_type','entity_type','department_id','is_own','to_department', 'musteri_id', 'pus_fine', 'thread_length', 'finish_en', 'finish_gramaj','created_at','from_musteri','to_musteri'],
            self::SCENARIO_OUTCOMING     =>  ['from_date', 'to_date', 'sort_id','entity_ids','document_type','entity_type','department_id','is_own','musteri_id','type',  'thread_length', 'finish_en', 'finish_gramaj', 'to_musteri','created_at'],
            self::SCENARIO_SERVICE    =>  ['from_date', 'to_date', 'sort_id','entity_ids','document_type','entity_type','department_id','musteri_id','created_at'],
            self::SCENARIO_WRITE_OFF  =>  ['from_date', 'is_own', 'to_date', 'sort_id','entity_ids','document_type','entity_type','department_id','add_info', 'musteri_id','created_at'],
            self::SCENARIO_ALL   =>  ['from_date', 'to_date', 'sort_id','entity_ids','document_type','entity_type','department_id','is_own','to_department', 'musteri_id', 'pus_fine', 'thread_length', 'finish_en', 'finish_gramaj', 'date','created_at'],
            self::SCENARIO_KALITE   =>  ['from_date', 'to_date', 'sort_id','entity_ids','document_type','entity_type','department_id','is_own','to_department', 'musteri_id', 'pus_fine', 'thread_length', 'finish_en', 'finish_gramaj', 'date', 'makine_id', 'group_by_type','group_by_user','user_id','user_kalite_id','created_at'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'entity_type' => Yii::t('app', 'Entity Type'),
            'department_id' => Yii::t('app', "Bo'limdan"),
            'to_department' => Yii::t('app', "Qaysi bo'limlarga"),
            'is_accepted' => Yii::t('app', 'Is Accepted'),
            'add_info' => Yii::t('app', 'Add Info'),
            'from_date' => Yii::t('app', 'Boshlanish sana'),
            'to_date' => Yii::t('app', 'Tugash Sana'),
            'musteri_id' => Yii::t('app','Kontragent'),
            'to_musteri' => Yii::t('app','Kimga'),
            'sort_id' => Yii::t('app', 'Sort'),
            'entity_ids' => Yii::t('app', 'Ip nomlarini tanlash'),
            'type' => Yii::t('app', 'Qayerga'),
            'pus_fine' => Yii::t('app', 'Pus/Fine'),
            'thread_length' => Yii::t('app', 'Ip uzunligi'),
            'finish_en' => Yii::t('app', 'Finish En'),
            'finish_gramaj' => Yii::t('app', 'Finish Gramaj'),
            'user_id' => Yii::t('app', "To'quvchi"),
            'user_kalite_id' => Yii::t('app', 'Tekshiruvchi'),
            'makine_id' => Yii::t('app', 'Mashina'),
            'group_by_type' => Yii::t('app', "Mato turi bo'yicha guruhlash"),
            'group_by_user' => Yii::t('app', "To'quvchi bo'yicha guruhlash"),
            ];
    }

    /**
     * @param $params
     * @return array
     * @throws ExceptionAlias
     */
    public function search($params)
    {
        $this->load($params);

        $this->from_date = date('Y-m-d' , strtotime($this->from_date));
        $this->to_date = date('Y-m-d' , strtotime($this->to_date));
        switch ($this->document_type){
            case 1:
                $sort = '';
                $entityIds = '';
                $dept_id = '';
                $to_dept = '';
                $isOwn = '';
                if(!empty($this->sort_id)){
                    $sort = " AND (tdi.lot IN (".implode(',', $this->sort_id)."))";
                }
                if(!empty($this->entity_ids)){
                    $entityIds = ' AND (trm.id IN ('.implode(',', $this->entity_ids).'))';
                }
                if(!empty($this->department_id)){
                    $dept_id = " AND (td.from_department = {$this->department_id})";
                }elseif(!empty($this->from_musteri)){
                    $dept_id = " AND (td.from_musteri in ({$this->from_musteri}))";
                }
                if(!empty($this->to_department)){
                    $to_dept = " AND (td.to_department = {$this->to_department})";
                }else{
                    $deptSQL = "select id from toquv_departments where status = 1";
                    $to_dept = " AND (td.to_department in ({$deptSQL}))";
                }
                if(!empty($this->is_own)){
                    $isOwn = " AND tdi.is_own = {$this->is_own}";
                }
                if(!empty($this->pus_fine)){
                    $pus_fine = ' AND (tpf.id IN ('.implode(',', $this->pus_fine).'))';
                }
                if(!empty($this->thread_length)){
                    $thread = ' AND (tir.thread_length = '.$this->thread_length.')';
                }
                if(!empty($this->finish_en)){
                    $finish_en = ' AND (tir.finish_en = '.$this->finish_en.')';
                }
                if(!empty($this->finish_gramaj)){
                    $finish_gramaj = ' AND (tir.finish_gramaj = '.$this->finish_gramaj.')';
                }
                $musteri = '';
                if(!empty($this->musteri_id)){
                    $musteri = " AND (m.id = {$this->musteri_id})";
                }
                $sql = "select td.id,
                           SUM(tdi.quantity) AS count,
                           SUM(tdi.roll_count) AS roll_count,
                           SUM(tdi.count) AS soni,
                           fdept.name        AS from_dept,
                           tdept.name        AS to_dept,
                           trm.name mato,
                           type.name type,
                           tpf.name pus_fine,
                            sn.name sort,
                            CONCAT(tir.thread_length,' | ',tir.finish_en,' | ',tir.finish_gramaj) info,
                            m.name musteri,
                           tdi.entity_id,
                           td.reg_date,
                            m2.name from_musteri
                    from toquv_documents td
                             LEFT JOIN toquv_document_items tdi on td.id = tdi.toquv_document_id
                             INNER JOIN
                                mato_info tir ON tdi.entity_id = tir.id
                             LEFT JOIN
                                toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN
                                toquv_orders tor ON tro.toquv_orders_id = tor.id
                             LEFT JOIN
                                toquv_raw_materials trm ON tir.entity_id = trm.id
                             LEFT JOIN
                                raw_material_type as type                    
                                    ON trm.raw_material_type_id = type.id
                             LEFT JOIN
                                toquv_pus_fine tpf ON tir.pus_fine_id = tpf.id
                             LEFT JOIN sort_name sn ON sn.id = tdi.lot
                             LEFT JOIN musteri m ON tir.musteri_id = m.id
                             LEFT JOIN toquv_departments fdept ON td.from_department = fdept.id
                             LEFT JOIN toquv_departments tdept ON td.to_department = tdept.id
                             LEFT JOIN musteri m2 ON td.from_musteri = m2.id
                    where document_type = 1
                      %s
                      %s
                      AND (td.reg_date BETWEEN '%s' AND '%s')
                      AND td.status = 3
                      AND tdi.entity_type = %d
                      %s
                      %s
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                    GROUP BY td.id,tdi.entity_id,trm.id,m.id,sn.id,tpf.id,tir.thread_length,tir.finish_en,tir.finish_gramaj
                    ORDER BY td.id ASC
                    LIMIT 1000;";
                $sql = sprintf($sql,
                    $dept_id,
                    $to_dept,
                    date('Y-m-d H:i:s', strtotime($this->from_date. ' 00:00:00')),
                    date('Y-m-d H:i:s', strtotime($this->to_date. ' 23:59:59')),
                    $this->entity_type,
                    $isOwn,
                    $sort,
                    $entityIds,
                    $pus_fine,
                    $thread,
                    $finish_en,
                    $finish_gramaj,
                    $musteri
                );
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                return $result;
                break;
            case 2:
                $sort = '';
                $entityIds = '';
                $dept_id = '';
                $to_dept = '';
                $isOwn = '';
                if(!empty($this->sort_id)){
                    $sort = " AND (tdi.lot IN (".implode(',', $this->sort_id)."))";
                }
                if(!empty($this->entity_ids)){
                    $entityIds = ' AND (trm.id IN ('.implode(',', $this->entity_ids).'))';
                }
                if(!empty($this->department_id)){
                    $dept_id = " AND (td.from_department = {$this->department_id})";
                }else{
                    $deptSQL = "select id from toquv_departments where status = 1";
                    $dept_id = " AND (td.from_department in ({$deptSQL}))";
                }
                if(!empty($this->to_department)){
                    $to_dept = " AND (td.to_department = {$this->to_department})";
                }else{
                    $deptSQL = "select id from toquv_departments where status = 1";
                    $to_dept = " AND (td.to_department in ({$deptSQL}))";
                }
                if(!empty($this->is_own)){
                    $isOwn = " AND tdi.is_own = {$this->is_own}";
                }
                if(!empty($this->pus_fine)){
                    $pus_fine = ' AND (tpf.id IN ('.implode(',', $this->pus_fine).'))';
                }
                if(!empty($this->thread_length)){
                    $thread = ' AND (tir.thread_length = '.$this->thread_length.')';
                }
                if(!empty($this->finish_en)){
                    $finish_en = ' AND (tir.finish_en = '.$this->finish_en.')';
                }
                if(!empty($this->finish_gramaj)){
                    $finish_gramaj = ' AND (tir.finish_gramaj = '.$this->finish_gramaj.')';
                }
                $musteri = '';
                if(!empty($this->musteri_id)){
                    $musteri = " AND (m.id = {$this->musteri_id})";
                }
                $sql = "select td.id,
                           SUM(tdi.quantity) AS count,
                           SUM(tdi.roll_count) AS roll_count,
                           SUM(tdi.count) AS soni,
                           fdept.name        AS from_dept,
                           tdept.name        AS to_dept,
                           trm.name mato,
                           type.name type,
                           tpf.name pus_fine,
                            sn.name sort,
                            CONCAT(tir.thread_length,' | ',tir.finish_en,' | ',tir.finish_gramaj) info,
                            m.name musteri,
                           tdi.entity_id,
                           td.reg_date,
                            tdi.add_info
                    from toquv_documents td
                             LEFT JOIN toquv_document_items tdi on td.id = tdi.toquv_document_id
                             INNER JOIN
                                mato_info tir ON tdi.entity_id = tir.id
                             LEFT JOIN
                                toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN
                                toquv_orders tor ON tro.toquv_orders_id = tor.id
                             LEFT JOIN
                                toquv_raw_materials trm ON tir.entity_id = trm.id
                             LEFT JOIN
                                raw_material_type as type                    
                                    ON trm.raw_material_type_id = type.id
                             LEFT JOIN
                                toquv_pus_fine tpf ON tir.pus_fine_id = tpf.id
                             LEFT JOIN sort_name sn ON sn.id = tdi.lot
                             LEFT JOIN musteri m ON tir.musteri_id = m.id
                             LEFT JOIN toquv_departments fdept ON td.from_department = fdept.id
                             LEFT JOIN toquv_departments tdept ON td.to_department = tdept.id
                    where document_type = 2
                      %s
                      %s
                      AND (td.reg_date BETWEEN '%s' AND '%s')
                      AND td.status = 3
                      AND tdi.entity_type = %d
                      %s
                      %s
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                    GROUP BY td.id,tdi.entity_id,trm.id,m.id,sn.id,tpf.id,tir.thread_length,tir.finish_en,tir.finish_gramaj
                    ORDER BY td.id ASC
                    LIMIT 1000;";
                $sql = sprintf($sql,
                    $dept_id,
                    $to_dept,
                    date('Y-m-d H:i:s', strtotime($this->from_date. ' 00:00:00')),
                    date('Y-m-d H:i:s', strtotime($this->to_date. ' 23:59:59')),
                    $this->entity_type,
                    $isOwn,
                    $sort,
                    $entityIds,
                    $pus_fine,
                    $thread,
                    $finish_en,
                    $finish_gramaj,
                    $musteri
                    );
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                return $result;
                break;
            case 5:
                $sort = '';
                $entityIds = '';
                $dept_id = '';
                $to_dept = '';
                $isOwn = '';
                if(!empty($this->sort_id)){
                    $sort = " AND (tdi.lot IN (".implode(',', $this->sort_id)."))";
                }
                if(!empty($this->entity_ids)){
                    $entityIds = ' AND (trm.id IN ('.implode(',', $this->entity_ids).'))';
                }
                if(!empty($this->department_id)){
                    $dept_id = " AND (td.from_department = {$this->department_id})";
                }else{
                    $deptSQL = "select id from toquv_departments where status = 1";
                    $dept_id = " AND (td.from_department in ({$deptSQL}))";
                }
                if(!empty($this->to_musteri)){
                    $to_dept = " AND (td.to_musteri = {$this->to_musteri})";
                }else{
                    $deptSQL = "select id from musteri where status = 1";
                    $to_dept = "";
                }
                if(!empty($this->is_own)){
                    $isOwn = " AND tdi.is_own = {$this->is_own}";
                }
                if(!empty($this->pus_fine)){
                    $pus_fine = ' AND (tpf.id IN ('.implode(',', $this->pus_fine).'))';
                }
                if(!empty($this->thread_length)){
                    $thread = ' AND (tir.thread_length = '.$this->thread_length.')';
                }
                if(!empty($this->finish_en)){
                    $finish_en = ' AND (tir.finish_en = '.$this->finish_en.')';
                }
                if(!empty($this->finish_gramaj)){
                    $finish_gramaj = ' AND (tir.finish_gramaj = '.$this->finish_gramaj.')';
                }
                $musteri = '';
                if(!empty($this->musteri_id)){
                    $musteri = " AND (m.id = {$this->musteri_id})";
                }
                $sql = "select td.id,
                           SUM(tdi.quantity) AS count,
                           SUM(tdi.roll_count) AS roll_count,
                           SUM(tdi.count) AS soni,
                           fdept.name        AS from_dept,
                           tdept.name        AS to_dept,
                           m2.name to_musteri,
                           trm.name mato,
                           type.name type,
                           tpf.name pus_fine,
                            sn.name sort,
                            CONCAT(tir.thread_length,' | ',tir.finish_en,' | ',tir.finish_gramaj) info,
                            m.name musteri,
                           tdi.entity_id,
                           td.reg_date
                    from toquv_documents td
                             LEFT JOIN toquv_document_items tdi on td.id = tdi.toquv_document_id
                             LEFT JOIN musteri m2 ON td.musteri_id = m2.id
                             INNER JOIN
                                mato_info tir ON tdi.entity_id = tir.id
                             LEFT JOIN
                                toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN
                                toquv_orders tor ON tro.toquv_orders_id = tor.id
                             LEFT JOIN
                                toquv_raw_materials trm ON tir.entity_id = trm.id
                             LEFT JOIN
                                raw_material_type as type                    
                                    ON trm.raw_material_type_id = type.id
                             LEFT JOIN
                                toquv_pus_fine tpf ON tir.pus_fine_id = tpf.id
                             LEFT JOIN sort_name sn ON sn.id = tdi.lot
                             LEFT JOIN musteri m ON tir.musteri_id = m.id
                             LEFT JOIN toquv_departments fdept ON td.from_department = fdept.id
                             LEFT JOIN toquv_departments tdept ON td.to_department = tdept.id
                    where document_type = $this->document_type
                      %s
                      %s
                      AND (td.reg_date BETWEEN '%s' AND '%s')
                      AND td.status = 3
                      AND tdi.entity_type = %d
                      %s
                      %s
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                    GROUP BY td.id,tdi.entity_id,trm.id,m.id,sn.id,tpf.id,tir.thread_length,tir.finish_en,tir.finish_gramaj
                    ORDER BY td.id ASC
                    LIMIT 1000;";
                $sql = sprintf($sql,
                    $dept_id,
                    $to_dept,
                    date('Y-m-d H:i:s', strtotime($this->from_date. ' 00:00:00')),
                    date('Y-m-d H:i:s', strtotime($this->to_date. ' 23:59:59')),
                    $this->entity_type,
                    $isOwn,
                    $sort,
                    $entityIds,
                    $pus_fine,
                    $thread,
                    $finish_en,
                    $finish_gramaj,
                    $musteri
                );
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                return $result;
                break;
            case 6:
                break;
            case 8:
                $sort = '';
                $entityIds = '';
                $dept_id = '';
                $to_dept = '';
                $isOwn = '';
                if(!empty($this->sort_id)){
                    $sort = " AND (tdi.lot IN (".implode(',', $this->sort_id)."))";
                }
                if(!empty($this->entity_ids)){
                    $entityIds = ' AND (trm.id IN ('.implode(',', $this->entity_ids).'))';
                }
                if(!empty($this->department_id)){
                    $dept_id = " AND (td.from_department = {$this->department_id})";
                }else{
                    $deptSQL = "select id from toquv_departments where status = 1";
                    $dept_id = " AND (td.from_department in ({$deptSQL}))";
                }
                if(!empty($this->to_musteri)){
                    $to_dept = " AND (td.to_musteri = {$this->to_musteri})";
                }else{
                    $deptSQL = "select id from musteri where status = 1";
                    $to_dept = " AND (td.to_musteri in ({$deptSQL}))";
                }
                if(!empty($this->is_own)){
                    $isOwn = " AND tdi.is_own = {$this->is_own}";
                }
                if(!empty($this->pus_fine)){
                    $pus_fine = ' AND (tpf.id IN ('.implode(',', $this->pus_fine).'))';
                }
                if(!empty($this->thread_length)){
                    $thread = ' AND (tir.thread_length = '.$this->thread_length.')';
                }
                if(!empty($this->finish_en)){
                    $finish_en = ' AND (tir.finish_en = '.$this->finish_en.')';
                }
                if(!empty($this->finish_gramaj)){
                    $finish_gramaj = ' AND (tir.finish_gramaj = '.$this->finish_gramaj.')';
                }
                $sql = "select td.id,
                           SUM(tdi.quantity) AS count,
                           SUM(tdi.roll_count) AS roll_count,
                           fdept.name        AS from_dept,
                           tdept.name        AS to_dept,
                           m2.name to_musteri,
                           trm.name mato,
                           type.name type,
                           tpf.name pus_fine,
                            sn.name sort,
                            CONCAT(tir.thread_length,' | ',tir.finish_en,' | ',tir.finish_gramaj) info,
                            m.name musteri,
                           tdi.entity_id,
                           td.reg_date
                    from toquv_documents td
                             LEFT JOIN toquv_document_items tdi on td.id = tdi.toquv_document_id
                             LEFT JOIN musteri m2 ON td.musteri_id = m2.id
                             INNER JOIN
                                mato_info tir ON tdi.entity_id = tir.id
                             LEFT JOIN
                                toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN
                                toquv_orders tor ON tro.toquv_orders_id = tor.id
                             LEFT JOIN
                                toquv_raw_materials trm ON tir.entity_id = trm.id
                             LEFT JOIN
                                raw_material_type as type                    
                                    ON trm.raw_material_type_id = type.id
                             LEFT JOIN
                                toquv_pus_fine tpf ON tir.pus_fine_id = tpf.id
                             LEFT JOIN sort_name sn ON sn.id = tdi.lot
                             LEFT JOIN musteri m ON tir.musteri_id = m.id
                             LEFT JOIN toquv_departments fdept ON td.from_department = fdept.id
                             LEFT JOIN toquv_departments tdept ON td.to_department = tdept.id
                    where document_type = $this->document_type
                      %s
                      %s
                      AND (td.reg_date BETWEEN '%s' AND '%s')
                      AND td.status = 3
                      AND tdi.entity_type = %d
                      %s
                      %s
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                    GROUP BY td.id,tdi.entity_id,trm.id,m.id,sn.id,tpf.id,tir.thread_length,tir.finish_en,tir.finish_gramaj
                    ORDER BY td.id ASC
                    LIMIT 1000;";
                $sql = sprintf($sql,
                    $dept_id,
                    $to_dept,
                    date('Y-m-d H:i:s', strtotime($this->from_date. ' 00:00:00')),
                    date('Y-m-d H:i:s', strtotime($this->to_date. ' 23:59:59')),
                    $this->entity_type,
                    $isOwn,
                    $sort,
                    $entityIds,
                    $pus_fine,
                    $thread,
                    $finish_en,
                    $finish_gramaj
                );
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                return $result;
                break;
            case 9:
                $sort = '';
                $entityIds = '';
                $dept_id = '';
                $to_dept = '';
                $isOwn = '';
                if(!empty($this->sort_id)){
                    $sort = " AND (tdi.lot IN (".implode(',', $this->sort_id)."))";
                }
                if(!empty($this->entity_ids)){
                    $entityIds = ' AND (trm.id IN ('.implode(',', $this->entity_ids).'))';
                }
                if(!empty($this->department_id)){
                    $dept_id = " AND (td.from_department = {$this->department_id})";
                }else{
                    $deptSQL = "select id from toquv_departments where status = 1";
                    $dept_id = " AND (td.from_department in ({$deptSQL}))";
                }
                $to_dept = " AND (td.to_department IS NULL)";
                if(!empty($this->is_own)){
                    $isOwn = " AND tdi.is_own = {$this->is_own}";
                }
                if(!empty($this->pus_fine)){
                    $pus_fine = ' AND (tpf.id IN ('.implode(',', $this->pus_fine).'))';
                }
                if(!empty($this->thread_length)){
                    $thread = ' AND (tir.thread_length = '.$this->thread_length.')';
                }
                if(!empty($this->finish_en)){
                    $finish_en = ' AND (tir.finish_en = '.$this->finish_en.')';
                }
                if(!empty($this->finish_gramaj)){
                    $finish_gramaj = ' AND (tir.finish_gramaj = '.$this->finish_gramaj.')';
                }
                $musteri = '';
                if(!empty($this->musteri_id)){
                    $musteri = " AND (m.id = {$this->musteri_id})";
                }
                $from_musteri = '';
                $mustSQL = "select id from musteri where status = 1";
                if(!empty($this->from_musteri)){
                    $from_musteri = " AND (from_m.id = {$this->from_musteri})";
                }else{
                    $from_musteri = " AND (from_m.id in ({$mustSQL}))";
                }
                $to_musteri = '';
                if(!empty($this->to_musteri)){
                    $to_musteri = " AND (to_m.id = {$this->to_musteri})";
                }else{
                    $to_musteri = " AND (to_m.id in ({$mustSQL}))";
                }
                $sql = "select td.id,
                           SUM(tdi.quantity) AS count,
                           SUM(tdi.roll_count) AS roll_count,
                           SUM(tdi.count) AS soni,
                           fdept.name        AS from_dept,
                           tdept.name        AS to_dept,
                           from_m.name        AS from_m,
                           to_m.name        AS to_m,
                           trm.name mato,
                           type.name type,
                           tpf.name pus_fine,
                            sn.name sort,
                            CONCAT(tir.thread_length,' | ',tir.finish_en,' | ',tir.finish_gramaj) info,
                            m.name musteri,
                           tdi.entity_id,
                           td.reg_date,
                            tdi.add_info
                    from toquv_documents td
                             LEFT JOIN toquv_document_items tdi on td.id = tdi.toquv_document_id
                             INNER JOIN
                                mato_info tir ON tdi.entity_id = tir.id
                             LEFT JOIN
                                toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN
                                toquv_orders tor ON tro.toquv_orders_id = tor.id
                             LEFT JOIN
                                toquv_raw_materials trm ON tir.entity_id = trm.id
                             LEFT JOIN
                                raw_material_type as type                    
                                    ON trm.raw_material_type_id = type.id
                             LEFT JOIN
                                toquv_pus_fine tpf ON tir.pus_fine_id = tpf.id
                             LEFT JOIN sort_name sn ON sn.id = tdi.lot
                             LEFT JOIN musteri m ON tir.musteri_id = m.id
                             LEFT JOIN toquv_departments fdept ON td.from_department = fdept.id
                             LEFT JOIN toquv_departments tdept ON td.to_department = tdept.id
                            LEFT JOIN musteri from_m on td.from_musteri = from_m.id
                            LEFT JOIN musteri to_m on td.to_musteri = to_m.id
                    where document_type = 9
                      %s
                      %s
                      AND (td.reg_date BETWEEN '%s' AND '%s')
                      AND td.status = 3
                      AND tdi.entity_type = %d
                      %s
                      %s
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                    GROUP BY td.id,tdi.entity_id,trm.id,m.id,sn.id,tpf.id,tir.thread_length,tir.finish_en,tir.finish_gramaj
                    ORDER BY td.id ASC
                    LIMIT 1000;";
                $sql = sprintf($sql,
                    $dept_id,
                    $to_dept,
                    date('Y-m-d H:i:s', strtotime($this->from_date. ' 00:00:00')),
                    date('Y-m-d H:i:s', strtotime($this->to_date. ' 23:59:59')),
                    $this->entity_type,
                    $isOwn,
                    $sort,
                    $entityIds,
                    $pus_fine,
                    $thread,
                    $finish_en,
                    $finish_gramaj,
                    $musteri,
                    $from_musteri,
                    $to_musteri
                );
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                return $result;
                break;
        }
    }

    /**
     * @param $params
     * @return array
     * @throws ExceptionAlias
     */
    public function searchAll($params,$doc_type=1)
    {
        $this->load($params);
        $sort = '';
        $entityIds = '';
        $dept_id = '';
        $isOwn = '';
        if(empty($this->sort_id)){
            $sort = " AND (tdi.lot IN (1,2))";
        }else{
            $sort = " AND (tdi.lot IN (".implode(',', $this->sort_id)."))";
        }
        if(!empty($this->department_id)){
            $dept_id = ($doc_type==1)?" AND (td.to_department = {$this->department_id})":" AND (td.from_department = {$this->department_id})";
        }
        $dept_join = ($doc_type==1)?"LEFT JOIN toquv_departments fdept ON td.to_department = fdept.id":"LEFT JOIN toquv_departments fdept ON td.from_department = fdept.id";
        $docType = ($doc_type==1)?" AND td.document_type in (1)":" AND td.document_type in (2,5)";
        $sql = "select
                    SUM(tdi.quantity) AS count,
                    SUM(tdi.roll_count) AS roll_count,
                    SUM(tdi.count) AS soni,
                    fdept.name        AS from_dept,
                    td.document_type
                    from toquv_documents td
                             LEFT JOIN toquv_document_items tdi on td.id = tdi.toquv_document_id
                             INNER JOIN
                                mato_info tir ON tdi.entity_id = tir.id
                             LEFT JOIN
                                toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN
                                toquv_orders tor ON tro.toquv_orders_id = tor.id
                             LEFT JOIN
                                toquv_raw_materials trm ON tir.entity_id = trm.id
                             LEFT JOIN sort_name sn ON sn.id = tdi.lot
                             %s
                    where td.status = 3
                        %s
                      %s
                      AND (td.reg_date BETWEEN '%s' AND '%s')
                      AND tdi.entity_type = 2
                      %s
                    ORDER BY td.document_type ASC
                    LIMIT 1000;";
        $sql = sprintf($sql,
            $dept_join,
            $dept_id,
            $docType,
            date('Y-m-d H:i:s', strtotime($this->from_date)),
            date('Y-m-d H:i:s', strtotime($this->to_date)),
            $sort
        );
        $result = Yii::$app->db->createCommand($sql)->queryOne();
        return $result;
    }
    public function searchKalite($params,$brak=null,$status=null)
    {
        $this->load($params);
        $sort = '';
        $st = '';
        if($status){
            $st = " AND (tk.status = {$status})";
        }
        if($brak){
            $this->sort_id = [3];
        }
        if(empty($this->sort_id)){
            $sort = " AND (tk.sort_name_id IN (1,2))";
        }else{
            $sort = " AND (tk.sort_name_id IN (".implode(',', $this->sort_id)."))";
        }
        $sql = "select
                    COUNT(tk.id) AS roll_count,
                    SUM(tk.quantity) AS summa
                    from toquv_kalite tk
                    where tk.type = 1
                      AND (tk.created_at BETWEEN '%s' AND '%s')
                      %s
                      %s
                    LIMIT 1000;";
        $sql = sprintf($sql,
            strtotime($this->from_date),
            strtotime($this->to_date),
            $sort,
            $st
        );
        $result = Yii::$app->db->createCommand($sql)->queryOne();
        return $result;
    }
    public function searchMato($params,$type=null)
    {
        $this->load($params);
        $department_id = ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SKLAD'])['id'] ?? 3;
        $department = " AND (department_id = {$department_id})";
        if(!empty($this->department_id)){
            $department = " AND (department_id = {$this->department_id})";
        }
        $sort = ' AND (lot IN (1,2))';
        if(!empty($this->sort_id)){
            $sort = ' AND (lot IN ('.implode(',', $this->sort_id).'))';
        }
        $entity_type = (!$type)?ToquvDocuments::ENTITY_TYPE_MATO:$type;
        $sql = "SELECT
                    sum(t1.inventory) summa,
                    sum(t1.roll_inventory) roll_count,
                    sum(t1.quantity_inventory) soni
                FROM
                    toquv_mato_item_balance t1
                JOIN
                    ( SELECT
                          MAX(toquv_mato_item_balance.id) AS id,
                          SUM(toquv_mato_item_balance.count) AS total
                      FROM toquv_mato_item_balance
                               LEFT JOIN mato_info tir ON tir.id = toquv_mato_item_balance.entity_id
                      WHERE toquv_mato_item_balance.entity_type = {$entity_type}  %s
                      GROUP BY toquv_mato_item_balance.entity_id, toquv_mato_item_balance.lot, tir.id
                      ORDER BY id ASC
                    ) AS t2 ON t1.id = t2.id
                WHERE
                    ( t1.reg_date BETWEEN '%s' AND '%s' )
                  AND ( t1.entity_type = {$entity_type} )
                  %s                
                  AND ( t1.inventory > 0 )";

        $sql = sprintf($sql,
            $department,
            date('Y-m-d', strtotime($this->from_date)),
            date('Y-m-d', strtotime($this->to_date)),
            $department);
        return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public function searchMatoKalite($params,$type=null)
    {
        $this->load($params);
        $query = "SELECT
                tk.id id,
                tir.id tir_id,
                tpf.name pus_fine,
                trm.id mato_id,
                trm.name mato,
                t.document_number doc_number,
                t.id toquv_orders_id,
                tro.id toquv_rm_order_id,
                m.name musteri_id,
                tk.quantity summa,
                tro.quantity quantity,
                CONCAT(tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj) info,
                tk.created_at created_at,
                tk.status status,
                sn.name sort,
                tk.sort_name_id sort_id,
                u.user_fio user_fio,
                ui.tabel,
                tk.code,
                tm.name makine,
                uk.user_fio kalite_user
                FROM toquv_kalite tk
                         LEFT JOIN toquv_instruction_rm tir on tk.toquv_instruction_rm_id = tir.id
                         LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                         LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                         LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                         LEFT JOIN musteri m ON t.musteri_id = m.id
                         LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                         LEFT JOIN raw_material_type rmt ON trm.raw_material_type_id = rmt.id
                         LEFT JOIN sort_name sn on tk.sort_name_id = sn.id
                         LEFT JOIN users u ON tk.user_id = u.id
                         LEFT JOIN users_info ui ON ui.users_id = u.id
                         LEFT JOIN users uk ON tk.user_kalite_id = uk.id
                         LEFT JOIN toquv_makine tm ON tk.toquv_makine_id = tm.id
                         LEFT JOIN toquv_kalite_defects tkd ON tk.id = tkd.toquv_kalite_id
                WHERE   tk.type = %d";
        $this->load($params);
        if($this->sort_id){
            $query .= " AND tk.sort_name_id IN (".implode(',', $this->sort_id).")";
        }
        if($this->musteri_id){
            $query .= (!is_array($this->musteri_id))?" AND m.id = {$this->musteri_id}":" AND m.id IN (".implode(',', $this->musteri_id).")";
        }
        if($this->entity_ids){
            $query .= " AND trm.id IN (".implode(',', $this->entity_ids).")";
        }
        if($this->makine_id){
            $query .= (!is_array($this->makine_id))?" AND tk.toquv_makine_id = {$this->makine_id}":" AND tk.toquv_makine_id IN (".implode(',', $this->makine_id).")";
        }
        if($this->user_id){
            $query .= (!is_array($this->user_id))?" AND tk.user_id = {$this->user_id}":" AND tk.user_id IN (".implode(',', $this->user_id).")";
        }
        if($this->user_kalite_id){
            $query .= (!is_array($this->user_kalite_id))?" AND tk.user_kalite_id = {$this->user_kalite_id}":" AND tk.user_kalite_id IN (".implode(',', $this->user_kalite_id).")";
        }
        if($this->pus_fine){
            $query .= (!is_array($this->pus_fine))?" AND tpf.id = {$this->pus_fine}":" AND m.id IN (".implode(',', $this->pus_fine).")";
        }
        if($this->thread_length){
            $query .= " AND tir.thread_length = '{$this->thread_length}'";
        }
        if($this->finish_en){
            $query .= " AND tir.finish_en = '{$this->finish_en}''";
        }
        if($this->finish_gramaj){
            $query .= " AND tir.finish_gramaj = '{$this->finish_gramaj}'";
        }
        if($this->from_date){
            $query .= " AND tk.created_at >= ".strtotime($this->from_date);
        }
        if($this->to_date){
            $query .= " AND tk.created_at <= ".strtotime($this->to_date);
        }
        $group_by = " GROUP BY";
        $type_group = ($this->group_by_type)?$type_group." rmt.id":"";
        $user_group = ($this->group_by_user)?" u.id":"";
        $group_by .= (!empty($type_group))?$type_group.", u.id":$user_group;
        $query .= ($this->group_by_type || $this->group_by_user)?$group_by:"";
        $query .= " LIMIT 2000";
        $tip = (!$type)?ToquvRawMaterials::MATO:$type;
        $query = sprintf($query,$tip);
        return Yii::$app->db->createCommand($query)->queryAll();
    }
    /**
     * @param int $type
     * @param null $id
     * @return array|string|null
     * @throws ExceptionAlias
     */
    public function getEntities($type = 1, $id = null){
        switch ($type){
            case 1:
                if(!empty($id)){
                    $sql = "select ip.name as ipname, tn.name as ne, tt.name as thr, tic.name as cl
                            from toquv_ip ip
                                     left join toquv_ip_color tic on ip.finish_gramaj = tic.id
                                     left join toquv_thread tt on ip.thread_length = tt.id
                            where ip.id = :id AND ip.status = 1
                    LIMIT 1;";
                    $ip = Yii::$app->db->createCommand($sql)->bindValues(['id' => $id])->queryOne();
                    if($ip){
                        return $ip['ipname'].' - '.$ip['ne'].' - '.$ip['thr'].' - '.$ip['cl'];
                    }
                }else{
                    $sql = "select ip.id, ip.name as ipname, tn.name as ne, tt.name as thr, tic.name as cl
                            from toquv_ip ip
                                     left join toquv_ne tn on ip.finish_en = tn.id
                                     left join toquv_ip_color tic on ip.finish_gramaj = tic.id
                                     left join toquv_thread tt on ip.thread_length = tt.id
                            where ip.status = 1
                            LIMIT 1000;";
                    $iplar = Yii::$app->db->createCommand($sql)->queryAll();
                    if(!empty($iplar)){
                        $result = [];
                        foreach ($iplar as $ip){
                            $result[$ip['id']] = $ip['ipname'].' - '.$ip['ne'].' - '.$ip['thr'].' - '.$ip['cl'];
                        }
                        return $result;
                    }
                    return null;
                }
                break;
            case 2:
                break;

        }

    }

    /**
     * @param bool $isGetAll
     * @return array|null
     */
    public function getDepartments($isGetAll = false){
        if(!$isGetAll){
            $availIds = ToquvUserDepartment::find()->select(['department_id'])
                ->where(['status' => 1, 'user_id' => Yii::$app->user->id])
                ->asArray()->all();
            if (!empty($availIds)) {
                $ids = ArrayHelper::getColumn($availIds,'department_id');
                $result = ToquvDepartments::find()->select(['id','name'])
                    ->andFilterWhere(['status' => 1])
                    ->andFilterWhere(['in','id', $ids])->asArray()->all();
            } else {
                return [];
            }
            if(!empty($result)){
                return ArrayHelper::map($result,'id','name');
            }
        }else{
            $depts = ToquvDepartments::find()->where(['status' => ToquvDepartments::STATUS_ACTIVE])->asArray()->all();
            return ArrayHelper::map($depts,'id','name');
        }

        return [];
    }

    /**
     * @return array
     */
    public function getMusteri(){
        $musteri = ToquvMusteri::find()->select(['id','name'])->where(['status' => 1])->asArray()->all();
        return ArrayHelper::map($musteri, 'id','name');
    }

    /**
     * @return array
     * @throws ExceptionAlias
     */
    public function getBelongToDepartments(){
        $sql = "select td.id,
                       td.name
                from toquv_departments td where td.status = 1 AND td.id 
                IN (select tud.department_id from toquv_user_department tud where tud.user_id = %d);";
        $sql = sprintf($sql, Yii::$app->user->id);
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($result,'id','name');
    }

    //TODO kirim ip uchun ham qilishimiz kerak
    /**
     * @param $params
     * @return array|false
     * @throws ExceptionAlias
     */
    public function searchEntities($params){
        $q ='';
        if(!empty($params['query'])){
            $q = " AND ((ip.name LIKE '%{$params['query']}%') OR (ne.name LIKE '%{$params['query']}%') OR (thr.name LIKE '%{$params['query']}%') OR (cl.name LIKE '%{$params['query']}%') OR (t1.lot LIKE '%{$params['query']}%'))";
        }
        $sql = "SELECT t1.id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname 
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id  
                    JOIN (SELECT MAX(id) as id from toquv_item_balance GROUP BY entity_id, lot ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (entity_type=%d) AND (department_id=%d) %s 
                    GROUP BY t1.entity_id, t1.lot LIMIT 1000";

        $sql = sprintf($sql,
            $params['entity_type'],
            $params['department_id'],
            $q);

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getOwnTypes($key = null){
        $res = [
            1 => Yii::t('app',"Bizniki"),
            2 =>Yii::t('app',"Mijozniki")
        ];
        if(!empty($key)){
            return $res[$key];
        }
        return $res;
    }
}
