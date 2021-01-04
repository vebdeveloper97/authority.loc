<?php

namespace app\modules\toquv\models;


use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\ToquvUserDepartment;
/**
 * ToquvDepartmentsSearch represents the model behind the search form of `app\modules\toquv\models\ToquvDepartments`.
 */
class RemainSearchModel extends Model
{

    const SCENARIO_INCOMING     = 'incoming';
    const SCENARIO_MOVING       = 'moving';
    const SCENARIO_OUTCOMING    = 'outcoming';
    const SCENARIO_SERVICE      = 'service';
    const SCENARIO_WRITE_OFF    = 'write-off';

    public $is_accepted;
    public $from_date;
    public $department_id;
    public $to_department;
    public $entity_type;
    public $document_type;
    public $to_date;
    public $lots;
    public $entity_ids;
    public $musteri_id;
    public $is_own;
    public $add_info;
    public $type;
    public $to_musteri;

    public $ne_id;

    public $thread_id;

    public $color_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['department_id', 'required'],
            [['entity_type','department_id','is_own', 'to_department','document_type',  'thread_id', 'ne_id', 'color_id'],'integer'],
            [['is_accepted','add_info'],'string'],
            [['from_date', 'to_date', 'lots','entity_ids'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_INCOMING   =>  [],
            self::SCENARIO_MOVING     =>  ['from_date', 'to_date', 'lots','entity_ids','document_type','entity_type','department_id','is_own','to_department',  'thread_id', 'ne_id', 'color_id'],
            self::SCENARIO_OUTCOMING     =>  ['from_date', 'to_date', 'lots','entity_ids','document_type','entity_type','department_id','is_own','musteri_id','type',  'thread_id', 'ne_id', 'color_id'],
            self::SCENARIO_SERVICE    =>  ['from_date', 'to_date', 'lots','entity_ids','document_type','entity_type','department_id','musteri_id'],
            self::SCENARIO_WRITE_OFF  =>  ['from_date', 'is_own', 'to_date', 'lots','entity_ids','document_type','entity_type','department_id','add_info', 'musteri_id']
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
            'lots' => Yii::t('app', 'Lot raqamlar'),
            'entity_ids' => Yii::t('app', 'Ip nomlarini tanlash'),
            'type' => Yii::t('app', 'Qayerga'),
            'ne_id' => Yii::t('app', 'Ne ID'),
            'thread_id' => Yii::t('app', 'Thread ID'),
            'color_id' => Yii::t('app', 'Color ID'),
            ];
    }

    /**
     * @param $params
     * @param int $type
     * @return array
     * @throws \yii\db\Exception
     */
    public function search($params)
    {
        $this->load($params);

        $this->from_date = date('Y-m-d' , strtotime($this->from_date));
        $this->to_date = date('Y-m-d' , strtotime($this->to_date));

        switch ($this->document_type){
            case 1:
                //TODO kirim ip
                return null;

                break;
            case 2:
                $lots = '';
                $entityIds = '';
                $to_dept = '';
                $isOwn = '';
                if(!empty($this->lots)){
                    $trim = trim($this->lots);
                    $lots = " AND (tdi.lot IN ({$trim}))";
                }
                if(!empty($this->entity_ids)){
                    $entityIds = ' AND (tdi.entity_id IN ('.implode(',', $this->entity_ids).'))';
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
                if(!empty($this->thread_id)){
                    $thread = ' AND (thr.id IN ('.implode(',', $this->thread_id).'))';
                }
                if(!empty($this->ne_id)){
                    $ne = ' AND (ne.id IN ('.implode(',', $this->ne_id).'))';
                }
                if(!empty($this->color_id)){
                    $color = ' AND (cl.id IN ('.implode(',', $this->color_id).'))';
                }
                $sql = "select td.id,
                           SUM(tdi.quantity) AS count,
                           fdept.name        AS from_dept,
                           tdept.name        AS to_dept,
                           tdi.price_sum,
                           tdi.price_usd,
                           ip.name           AS ip,
                           cl.name           AS color,
                           thr.name          AS thread,
                           ne.name           AS ne,
                           tdi.entity_id,
                           tdi.lot,
                           td.reg_date
                    from toquv_documents td
                             LEFT JOIN toquv_document_items tdi on td.id = tdi.toquv_document_id
                             LEFT JOIN toquv_ip ip ON tdi.entity_id = ip.id
                             LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id
                             LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id
                             LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id
                             LEFT JOIN toquv_departments fdept ON td.from_department = fdept.id
                             LEFT JOIN toquv_departments tdept ON td.to_department = tdept.id
                    where document_type = 2
                      AND td.from_department = %d
                      %s
                      AND (reg_date BETWEEN '%s' AND '%s')
                      AND td.status = 3
                      AND tdi.entity_type = %d
                      %s
                      %s
                      %s                 
                      %s                 
                      %s                 
                      %s                 
                    GROUP BY tdi.entity_id
                    ORDER BY ip.name ASC
                    LIMIT 1000;";

                $sql = sprintf($sql,
                    $this->department_id,
                    $to_dept,
                    date('Y-m-d', strtotime($this->from_date)),
                    date('Y-m-d', strtotime($this->to_date)),
                    $this->entity_type,
                    $isOwn,
                    $lots,
                    $entityIds,
                    $thread,
                    $ne,
                    $color
                    );
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                return $result;
                break;
            case 5:
                $lots = '';
                $entityIds = '';
                $to_dept = '';
                $isOwn = '';
                if(!empty($this->lots)){
                    $trim = trim($this->lots);
                    $lots = " AND (tdi.lot IN ({$trim}))";
                }
                if(!empty($this->entity_ids)){
                    $entityIds = ' AND (tdi.entity_id IN ('.implode(',', $this->entity_ids).'))';
                }
                if(!empty($this->to_department)){
                    $to_dept = " AND (td.to_department = {$this->to_department})";
                }
                if(!empty($this->is_own)){
                    $isOwn = " AND tdi.is_own = {$this->is_own}";
                }
                if(!empty($this->musteri_id)){
                    $musteri = " AND (m.id = {$this->musteri_id})";
                }
                if(!empty($this->thread_id)){
                    $thread = ' AND (thr.id IN ('.implode(',', $this->thread_id).'))';
                }
                if(!empty($this->ne_id)){
                    $ne = ' AND (ne.id IN ('.implode(',', $this->ne_id).'))';
                }
                if(!empty($this->color_id)){
                    $color = ' AND (cl.id IN ('.implode(',', $this->color_id).'))';
                }
                $type = '';
                switch ($this->type){
                    case 1:
                        $type = " AND td.to_department IS NOT NULL";
                        break;
                    case 2:
                        $type = " AND td.to_department IS NULL";
                        break;
                    default:
                        $type = " AND td.to_department IS NOT NULL";
                        break;
                }
                $sql = "select td.id,
                           SUM(tdi.quantity) AS count,
                           fdept.name        AS from_dept,
                           tdept.name        AS to_dept,
                            m.name musteri,
                           tdi.price_sum,
                           tdi.price_usd,
                           ip.name           AS ip,
                           cl.name           AS color,
                           thr.name          AS thread,
                           ne.name           AS ne,
                           tdi.entity_id,
                           tdi.lot,
                           td.reg_date
                    from toquv_documents td
                             LEFT JOIN toquv_document_items tdi on td.id = tdi.toquv_document_id
                             LEFT JOIN toquv_ip ip ON tdi.entity_id = ip.id
                             LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id
                             LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id
                             LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id
                             LEFT JOIN toquv_departments fdept ON td.from_department = fdept.id
                             LEFT JOIN toquv_departments tdept ON td.to_department = tdept.id
                             LEFT JOIN musteri m on td.musteri_id = m.id
                    where td.document_type = 5
                      AND td.from_department = %d
                      %s
                      AND (reg_date BETWEEN '%s' AND '%s')
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
                    GROUP BY tdi.entity_id,tdi.price_sum,tdi.price_usd,tdi.lot,td.reg_date,td.id
                    ORDER BY ip.name ASC
                    LIMIT 1000;";

                $sql = sprintf($sql,
                    $this->department_id,
                    $to_dept,
                    date('Y-m-d', strtotime($this->from_date)),
                    date('Y-m-d', strtotime($this->to_date)),
                    $this->entity_type,
                    $isOwn,
                    $lots,
                    $entityIds,
                    $type,
                    $musteri,
                    $thread,
                    $ne,
                    $color
                );
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                return $result;
                break;
            case 6:
                $lots = '';
                $entityIds = '';
                $musteri = '';
                if(!empty($this->lots)){
                    $trim = trim($this->lots);
                    $lots = " AND (t1.lot IN ({$trim}))";
                }
                if(!empty($this->entity_ids)){
                    $entityIds = ' AND (t1.entity_id IN ('.implode(',', $this->entity_ids).'))';
                }
                if(!empty($this->musteri_id)){
                    $musteri = " AND (mi.id = {$this->musteri_id})";
                }
                $sql = "SELECT t1.id,
                           t2.c         AS count,
                           fd.name      AS from_dept,
                           m.name       AS to_dept,
                           price_uzs,
                           price_usd,
                           ip.name      AS ip,
                           cl.name      AS color,
                           thr.name     AS thread,
                           ne.name      AS ne,
                           tdoc.add_info,
                           entity_id,
                           lot,
                           t1.reg_date,
                           t1.inventory AS summa
                    FROM toquv_item_balance t1
                             LEFT JOIN toquv_documents tdoc ON t1.document_id = tdoc.id
                             LEFT JOIN musteri m ON tdoc.musteri_id = m.id
                             LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                             LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id
                             LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id
                             LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id
                             LEFT JOIN toquv_departments fd ON t1.department_id = fd.id
                             JOIN (SELECT MAX(id) as id, SUM(count) as c
                                   from toquv_item_balance
                                   WHERE department_id = %d
                                     AND document_type = 6
                                   GROUP BY entity_id, lot
                                   ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (t1.reg_date BETWEEN '%s' AND '%s')
                      AND (t1.entity_type = %d)
                      AND (department_id = %d)
                      AND (t1.document_type = 6)
                      %s %s %s 
                    GROUP BY t1.entity_id, t1.lot
                    LIMIT 1000;";

                $sql = sprintf($sql,
                    $this->department_id,
                    $this->from_date,
                    $this->to_date,
                    $this->entity_type,
                    $this->department_id,
                    $lots,
                    $entityIds,
                    $musteri
                );

                $result = Yii::$app->db->createCommand($sql)->queryAll();
                return $result;
                break;
            case 8:
                $lots       = '';
                $entityIds  = '';
                $musteri    = '';
                $isOwn      = '';
                if(!empty($this->lots)){
                    $trim = trim($this->lots);
                    $lots = " AND (t1.lot IN ({$trim}))";
                }
                if(!empty($this->entity_ids)){
                    $entityIds = ' AND (t1.entity_id IN ('.implode(',', $this->entity_ids).'))';
                }
                if(!empty($this->is_own)){
                    $isOwn = " AND t1.is_own = {$this->is_own}";
                    if($isOwn == 2){
                        if(!empty($this->musteri_id)){
                            $musteri = " AND (m.id IN ('.implode(',', $this->musteri_id).'))";
                        }
                    }
                }
                $sql = "SELECT t1.id,
                           t2.c         AS count,
                           fd.name      AS from_dept,
                           m.name       AS to_dept,
                           price_uzs,
                           price_usd,
                           ip.name      AS ip,
                           cl.name      AS color,
                           thr.name     AS thread,
                           ne.name      AS ne,
                           tdoc.add_info,
                           entity_id,
                           lot,
                           t1.reg_date,
                           t1.inventory AS summa
                    FROM toquv_item_balance t1
                             LEFT JOIN toquv_documents tdoc ON t1.document_id = tdoc.id
                             LEFT JOIN musteri m ON tdoc.musteri_id = m.id
                             LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                             LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id
                             LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id
                             LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id
                             LEFT JOIN toquv_departments fd ON t1.department_id = fd.id
                             JOIN (SELECT MAX(id) as id, SUM(count) as c
                                   from toquv_item_balance
                                   WHERE department_id = %d
                                     AND document_type = 8
                                   GROUP BY entity_id, lot
                                   ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (t1.reg_date BETWEEN '%s' AND '%s')
                      AND (t1.entity_type = %d)
                      AND (department_id = %d)
                      AND (t1.document_type = 8)
                      %s %s %s %s
                    GROUP BY t1.entity_id, t1.lot
                    LIMIT 1000;";

                $sql = sprintf($sql,
                    $this->department_id,
                    $this->from_date,
                    $this->to_date,
                    $this->entity_type,
                    $this->department_id,
                    $lots,
                    $entityIds,
                    $musteri,
                    $isOwn
                );

                $result = Yii::$app->db->createCommand($sql)->queryAll();
                return $result;
                break;
        }

    }

    /**
     * @param int $type
     * @param null $id
     * @return array|string|null
     * @throws \yii\db\Exception
     */
    public function getEntities($type = 1, $id = null){
        switch ($type){
            case 1:
                if(!empty($id)){
                    $sql = "select ip.name as ipname, tn.name as ne, tt.name as thr, tic.name as cl
                            from toquv_ip ip
                                     left join toquv_ne tn on ip.ne_id = tn.id
                                     left join toquv_ip_color tic on ip.color_id = tic.id
                                     left join toquv_thread tt on ip.thread_id = tt.id
                            where ip.id = :id AND ip.status = 1
                    LIMIT 1;";
                    $ip = Yii::$app->db->createCommand($sql)->bindValues(['id' => $id])->queryOne();
                    if($ip){
                        return $ip['ipname'].' - '.$ip['ne'].' - '.$ip['thr'].' - '.$ip['cl'];
                    }
                }else{
                    $sql = "select ip.id, ip.name as ipname, tn.name as ne, tt.name as thr, tic.name as cl
                            from toquv_ip ip
                                     left join toquv_ne tn on ip.ne_id = tn.id
                                     left join toquv_ip_color tic on ip.color_id = tic.id
                                     left join toquv_thread tt on ip.thread_id = tt.id
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
                return null;
            }
            if(!empty($result)){
                return ArrayHelper::map($result,'id','name');
            }
        }else{
            $depts = ToquvDepartments::find()->where(['status' => ToquvDepartments::STATUS_ACTIVE])->asArray()->all();
            return ArrayHelper::map($depts,'id','name');
        }

        return null;
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
     * @throws \yii\db\Exception
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
     * @throws \yii\db\Exception
     */
    public function searchEntities($params){
        $q ='';
        if(!empty($params['query'])){
            $q = " AND ((ip.name LIKE '%{$params['query']}%') OR (ne.name LIKE '%{$params['query']}%') OR (thr.name LIKE '%{$params['query']}%') OR (cl.name LIKE '%{$params['query']}%') OR (t1.lot LIKE '%{$params['query']}%'))";
        }
        $sql = "SELECT t1.id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname 
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
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
