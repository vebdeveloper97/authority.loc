<?php

namespace app\modules\bichuv\models;

use app\modules\admin\models\ToquvUserDepartment;
use app\modules\base\models\Musteri;
use app\modules\base\models\SizeType;
use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * BichuvAcsSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvAcs`.
 *
 * @property mixed $musteriList
 * @property mixed $musteries
 * @property mixed $modelList
 * @property mixed $rmList
 * @property mixed $deparmentList
 * @property mixed $nastelList
 */
class BichuvReportSearch extends Model
{

    public $model_id;
    public $entity_id;
    public $rm_id;
    public $ne_id;
    public $pus_fine_id;
    public $thread;
    public $color;
    public $pantone;
    public $ctone;
    public $rm_name;
    public $musteri;
    public $nastel_no;
    public $group_by_size;
    public $model_name;
    public $party_nomer;
    public $musteri_party_nomer;
    public $department;
    public $size;
    public $iplik;
    public $artikul;
    public $partiya;
    public $color_id;
    public $kirim_data;
    public $bichuv_moving_date;
    public $from_date;
    public $to_date;

    public $reg_date;
    public $_fromDate;
    public $_toDate;
    public $_documentNumber;
    public $_nastelParty;

    public $__hrDepartment;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_id','entity_id', 'rm_id', 'ne_id', 'color', 'color_id', 'group_by_size','musteri','thread'], 'integer'],
            [['pantone','ctone','rm_name','nastel_no','musteri_party_nomer','party_nomer','iplik','artikul', 'kirim_data', 'bichuv_moving_date', 'from_date', 'to_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function formName()
    {
        return '';
    }

    /**
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public function search($params)
    {

        $isEmpty = true;
        $res = null;
        $rm = "";
        $ne = "";
        $pf = "";
        $model = "";
        $rm_name = "";
        $musteri = "";
        $partyNomer = "";
        $musteriPartyNomer = "";
        $hrDepartment = "";
        $docTypeAccepted = BichuvDoc::DOC_TYPE_ACCEPTED;
        if (!empty($params)) {

            $isEmpty = false;
            if(!empty($params['rm_name'])){
                $rm_name = " AND rmt.name LIKE '%{$params['rm_name']}%' ";
            }
            if(!empty($params['__hrDepartment'])){
                $hrDepartment = " AND brib.hr_department_id = {$params['__hrDepartment']} ";
                $acceptedDate = " AND bd2.to_hr_department = {$params['__hrDepartment']}";

                $this->__hrDepartment = $params['__hrDepartment'];
            }


            if (!empty($params['rm_id'])) {
                $count = count($params['rm_id']);
                if ($count == 1) {
                    $rm = " AND  rmt.id = {$params['rm_id'][0]} ";
                } else {
                    $joinid = join(',', $params['rm_id']);
                    $rm = " AND  rmt.id IN ({$joinid}) ";
                }
            }
            if (!empty($params['ne_id'])) {
                $joinid = join(',', $params['ne_id']);
                $ne = " AND  tn.id IN ({$joinid}) ";
            }
            if (!empty($params['pus_fine_id'])) {
                $joinid = join(',', $params['pus_fine_id']);
                $pf = " AND  tpf.id IN ({$joinid}) ";
            }
            if (!empty($params['model_name'])) {
                $model = " AND  p.name LIKE '%{$params['model_name']}%' ";
            }
            if (!empty($params['musteri'])) {
                $musteri = " AND  brib2.from_musteri = {$params['musteri']}";
            }

            if (!empty($params['party_nomer'])) {
                $partyNomer = " AND  brib.party_no LIKE '%{$params['party_nomer']}%'";
            }

            if (!empty($params['musteri_party_nomer'])) {
                $musteriPartyNomer = " AND brib.musteri_party_no LIKE '%{$params['musteri_party_nomer']}%'";
            }

        }



        if (!$isEmpty) {
            $currentUserId = Yii::$app->user->id;
            $sql = "select IF(wc.color_pantone_id <=> null ,wc.color_name, cp.name)  color,
                       IF(wc.color_pantone_id <=> null ,wc.color_code, cp.code)   color_id,
                       m.name              as mname,
                       rmt.name             as mato,
                       tn.name         as ne,
                       tt.name            as thread,
                       tpf.name             as pus_fine,
                       brib.roll_inventory as rulon_count,
                       brib.inventory      as rulon_kg,
                       brib.party_no,
                       brib.musteri_party_no,
                       kirim_data.vaqt        kirim_data,
                       bichuv_moving.moving bichuv_moving_date
                    from bichuv_rm_item_balance brib
                             left join (select group_concat(DISTINCT DATE_FORMAT(bd2.reg_date, '%%d.%%m.%%Y') SEPARATOR ', ') vaqt,
                                               bdi.entity_id,
                                               bdi.party_no,
                                               bdi.musteri_party_no
                                        from bichuv_doc bd2
                                                 left join bichuv_doc_items bdi on bd2.id = bdi.bichuv_doc_id
                                        where bd2.document_type = {$docTypeAccepted}
                                       {$acceptedDate}
                                        GROUP BY bdi.entity_id, bdi.party_no, bdi.musteri_party_no
                                        ) kirim_data ON kirim_data.entity_id = brib.entity_id and kirim_data.party_no = brib.party_no and
                                          kirim_data.musteri_party_no = brib.musteri_party_no   
                             left join (select group_concat(DISTINCT DATE_FORMAT(bd3.reg_date, '%%d.%%m.%%Y')  SEPARATOR ', ') moving,
                                               bdi.entity_id,
                                               bdi.party_no,
                                               bdi.musteri_party_no
                                        from bichuv_doc bd3
                                                 left join bichuv_doc_items bdi on bd3.id = bdi.bichuv_doc_id
                                        where bd3.document_type = 7
                                          and bd3.to_hr_department = 1
                                        GROUP BY bdi.entity_id, bdi.party_no, bdi.musteri_party_no
                                        ) bichuv_moving ON bichuv_moving.entity_id = brib.entity_id and bichuv_moving.party_no = brib.party_no and
                                    bichuv_moving.musteri_party_no = brib.musteri_party_no
                             left join musteri m on brib.from_musteri = m.id
                             left join wms_mato_info wmi on brib.entity_id = wmi.id
                             left join toquv_raw_materials trm on wmi.toquv_raw_materials_id = trm.id
                             left join raw_material_type rmt on trm.raw_material_type_id = rmt.id
                             left join toquv_raw_material_ip trmi on trm.id = trmi.toquv_raw_material_id
                             left join toquv_ne tn on trmi.ne_id = tn.id
                             left join toquv_thread tt on trmi.thread_id = tt.id
                             left join toquv_pus_fine tpf on wmi.pus_fine_id = tpf.id
                             left join wms_color wc on wmi.wms_color_id = wc.id
                             left join color_pantone cp on wc.color_pantone_id = cp.id
                             left join users_hr_departments uhd on brib.hr_department_id = uhd.id
                    WHERE brib.id IN (
                                select MAX(brib2.id) from bichuv_rm_item_balance brib2
                                where brib2.hr_department_id in (select hud.hr_departments_id from users_hr_departments hud where hud.user_id = %d)
                                %s GROUP BY brib2.entity_id, brib2.party_no/*, brib2.from_musteri*/)
                    %s %s %s %s %s %s %s AND brib.inventory > 0 {$hrDepartment} 
                    GROUP BY brib.entity_id, brib.party_no, brib.from_musteri, brib.id, kirim_data.vaqt, bichuv_moving.moving ORDER BY rmt.name ASC LIMIT 10000;";
            $sql = sprintf($sql, $currentUserId, $musteri, $rm, $ne, $pf, $model, $rm_name,$partyNomer,$musteriPartyNomer);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
        }
        return $res;
    }
    //TODO item balancega from musteri kelmayapti ya'ni kelmayapti tekshirish kerak i

    public function searchMatoSotishRemain($params)
    {
        $isEmpty = true;
        $res = null;
        $doc_number = null;
        $rm = "";
        $ne = "";
        $pf = "";
        $rm_name = "";
        $musteri = "";

        if (!empty($params)) {
            $isEmpty = false;
            if(!empty($params['rm_name'])){
                $rm_name = " AND rm.name LIKE '%{$params['rm_name']}%' ";
            }
            if(!empty($params['doc_number'])){
                $doc_number = " AND bd.doc_number LIKE '%{$params['doc_number']}%' ";
            }
            if (!empty($params['rm_id'])) {
                $count = count($params['rm_id']);
                if ($count == 1) {
                    $rm = " AND  rm.id = {$params['rm_id'][0]} ";
                } else {
                    $joinid = join(',', $params['rm_id']);
                    $rm = " AND  rm.id IN ({$joinid}) ";
                }
            }
            if (!empty($params['ne_id'])) {
                $joinid = join(',', $params['ne_id']);
                $ne = " AND  nename.id IN ({$joinid}) ";
            }
            if (!empty($params['pus_fine_id'])) {
                $joinid = join(',', $params['pus_fine_id']);
                $pf = " AND  pf.id IN ({$joinid}) ";
            }
            if (!empty($params['musteri'])) {
                $musteri = " AND  m.id = {$params['musteri']}";
            }

        }
            $currentUserId = Yii::$app->user->id;
            $sql = "SELECT
               bd.doc_number,
               CONCAT( rm.name, ' | ',
                   nename.name , ' | ',
                   thr.name , ' | ',
                   pf.name , ' | ',
                   ct.name, ' | ',
                   c.color_id, ' | ',
                   c.pantone) name,
               bdi.quantity,
               bdi.price_sum,
               bdi.price_usd,
               (bdi.price_sum*bdi.quantity) sum_sum,
               (bdi.price_usd*bdi.quantity) sum_usd,
               m.name mname
              FROM bichuv_doc bd
                   INNER JOIN bichuv_doc_items bdi ON bd.id = bdi.bichuv_doc_id
                   INNER JOIN toquv_departments td ON bd.from_department = td.id
                   INNER JOIN musteri m ON bd.to_department = m.id
                   INNER JOIN bichuv_mato_info bmi ON bdi.entity_id = bmi.id
                   INNER JOIN raw_material rm ON bmi.rm_id = rm.id
                   INNER JOIN ne nename ON bmi.ne_id = nename.id
                   INNER JOIN pus_fine pf ON bmi.pus_fine_id = pf.id
                   INNER JOIN thread thr ON bmi.thread_id = thr.id
                   INNER JOIN color c ON bmi.color_id = c.id
                   INNER JOIN color_tone ct ON c.color_tone = ct.id
                WHERE bd.document_type = %d
                %s %s %s %s %s %s ";
            $sql = sprintf($sql,
                BichuvDoc::DOC_TYPE_SELLING,
                $rm_name,
                $rm,
                $ne,
                $pf,
                $musteri,
                $doc_number
            );
            $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;

//        if (!$isEmpty) {
//            $sql = "SELECT
//               bd.doc_number,
//               CONCAT( rm.name, '-',
//               nename.name , '-',
//               thr.name , '|',
//               pf.name , '-',
//               ct.name, ' ',
//               c.color_id, '-',
//               c.pantone) name,
//               bdi.quantity,
//               bdi.price_sum,
//               bdi.price_usd,
//               (bdi.price_sum*bdi.quantity) sum_sum,
//               (bdi.price_usd*bdi.quantity) sum_usd,
//               m.name
//              FROM bichuv_doc bd
//                   INNER JOIN bichuv_doc_items bdi ON bd.id = bdi.bichuv_doc_id
//                   INNER JOIN toquv_departments td ON bd.from_department = td.id
//                   INNER JOIN musteri m ON bd.to_department = m.id
//                   INNER JOIN bichuv_mato_info bmi ON bdi.entity_id = bmi.id
//                   INNER JOIN raw_material rm ON bmi.rm_id = rm.id
//                   INNER JOIN ne nename ON bmi.ne_id = nename.id
//                   INNER JOIN pus_fine pf ON bmi.pus_fine_id = pf.id
//                   INNER JOIN thread thr ON bmi.thread_id = thr.id
//                   INNER JOIN color c ON bmi.color_id = c.id
//                   INNER JOIN color_tone ct ON c.color_tone = ct.id
//                WHERE bd.document_type = %d %s";
//            $sql = sprintf($sql, BichuvDoc::DOC_TYPE_SELLING, $where);
//            $res = Yii::$app->db->createCommand($sql)->queryAll();
    }

    public function searchService($params)
    {
        $isEmpty = true;
        $res = [];
        $musteri = "";
        $nastel = "";

        if (!empty($params)) {
            $isEmpty = false;
            if(!empty($params['musteri'])){
                $musteri = " AND bsib2.musteri_id = '{$params['musteri']}' ";
            }
            if (!empty($params['nastel_no'])) {
                $allNastelNo = "";
                $end = end($params['nastel_no']);
                foreach ($params['nastel_no'] as $item){
                    $allNastelNo .= "'{$item}'";
                    if($item != $end){
                        $allNastelNo .=",";
                    }
                }
                $nastel = " AND  bsib2.nastel_no IN ({$allNastelNo}) ";
            }

        }
        if (!$isEmpty) {
            $sql = "select m.name as mname,
                           ml.article,
                           mv.name,
                           cp.code,
                           bsib.nastel_no,
                           s.name as size_name,
                           bsib.inventory,
                           sn.name as sort
                    from bichuv_service_item_balance bsib
                    left join size s on bsib.size_id = s.id
                    left join sort_name sn on bsib.sort_id = sn.id
                    left join models_list ml on bsib.model_id = ml.id
                    left join musteri m on bsib.musteri_id = m.id
                    left join models_variations mv on mv.id = bsib.model_var
                    left join color_pantone cp on mv.color_pantone_id = cp.id
                    where bsib.id IN (select MAX(bsib2.id) from bichuv_service_item_balance bsib2
                            where 1=1 %s %s
                            GROUP BY bsib2.nastel_no, bsib2.musteri_id, bsib2.size_id, bsib2.sort_id
                        );";
            $sql = sprintf($sql, $musteri, $nastel);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
        }
        return $res;
    }

    public function searchSlice($params){
        $addQuery = "";
        if(!empty($params['__hrDepartment'])){
            $hrDepartment = " AND bsib.hr_department_id = {$params['__hrDepartment']} ";
            $this->__hrDepartment = $params['__hrDepartment'];
        }

        if(!empty($params['nastel_no'])){
            $addQuery .= " AND  bsib.party_no LiKE '%{$params['nastel_no']}%'";
            $this->nastel_no = $params['nastel_no'];
        }
        if(!empty($params['department'])){
            $addQuery .= " AND  bsib.department_id = '{$params['department']}'";
            $this->department = $params['department'];
        }
        if(!empty($params['model_name'])){
            $addQuery .= " AND  ml.article LIKE '%{$params['model_name']}%'";
            $this->model_name = $params['model_name'];
        }
        if(!empty($params['size'])){
            $addQuery .= " AND  s.size_type_id = '{$params['size']}'";
            $this->size = $params['size'];
        }
        $currentUserId = Yii::$app->user->id;
        $sql1 = "select SUM(bsib.inventory) as inventory,
                       bsib.party_no,
                       ml.article model,
                       hd.name as depart_name,
                       st.name as size,
                       bgr.add_info 
                       from bichuv_slice_item_balance bsib
                LEFT JOIN mobile_process_production mpp ON bsib.party_no = mpp.nastel_no
                LEFT JOIN mobile_process_production mpp2 ON mpp.parent_id = mpp2.id
                INNER JOIN bichuv_given_rolls bgr on mpp2.nastel_no = bgr.nastel_party
                LEFT JOIN model_rel_production mrp on mrp.bichuv_given_roll_id=bgr.id
                LEFT JOIN models_list ml on mrp.models_list_id = ml.id
                LEFT JOIN hr_departments hd on bsib.hr_department_id = hd.id 
                LEFT JOIN size s on bsib.size_id = s.id
                LEFT JOIN size_type st on s.size_type_id = st.id
                WHERE mpp.table_name = 'bichuv_given_roll_items' AND  bsib.id IN 
                (select MAX(bsib2.id) from bichuv_slice_item_balance bsib2 GROUP BY bsib2.size_id, bsib2.party_no)
                AND bsib.inventory > 0 AND bsib.hr_department_id in (
                select uhd.hr_departments_id from
                 users_hr_departments uhd where uhd.user_id = %d) %s
                {$hrDepartment}
                GROUP BY bsib.party_no ORDER BY bsib.id DESC";

        $sql1 = sprintf($sql1, $currentUserId,$addQuery);

        $sql2 = "
            select SUM(bsib.inventory) as inventory,
                   bsib.party_no,
                   ml.article model,
                   hd.name as depart_name,
                   st.name as size,
                   bgr.add_info 
                   from bichuv_slice_item_balance bsib
            INNER JOIN bichuv_given_rolls bgr on bsib.party_no = nastel_party
            LEFT JOIN model_rel_production mrp on mrp.bichuv_given_roll_id=bgr.id
            LEFT JOIN models_list ml on mrp.models_list_id = ml.id
            LEFT JOIN hr_departments hd on bsib.hr_department_id = hd.id 
            LEFT JOIN size s on bsib.size_id = s.id
            LEFT JOIN size_type st on s.size_type_id = st.id
            WHERE bsib.id IN 
            (select MAX(bsib2.id) from bichuv_slice_item_balance bsib2 GROUP BY bsib2.size_id, bsib2.party_no)
            AND bsib.inventory > 0 AND bsib.hr_department_id in (select uhd.hr_departments_id from users_hr_departments uhd where uhd.user_id = %d) %s
            {$hrDepartment}
            GROUP BY bsib.party_no ORDER BY bsib.id DESC
        ";
        $sql2 = sprintf($sql2, $currentUserId,$addQuery);
        $items1 = Yii::$app->db->createCommand($sql1)->queryAll();
        $items2 = Yii::$app->db->createCommand($sql2)->queryAll();

        return array_merge($items1, $items2);
    }

    public function searchReportDay($params){
        $addQuery = "";
        $this->load($params);
        if(!empty($this->from_date) && !empty($this->to_date)){
            $addQuery = "WHERE  DATE_FORMAT(bd.reg_date, '%Y-%m-%d') >= '$this->from_date' AND DATE_FORMAT(bd.reg_date, '%Y-%m-%d') <= '$this->to_date'";
        }

        $sql = "SELECT
                    DATE_FORMAT(reg_date, '%%d-%%m-%%Y') sana,
                    SUM(bsi2.quantity) two,
                    SUM(bsi3.quantity) three,
                    SUM(bsiu.quantity) service,
                    sum(bsium.quantity) works
                FROM bichuv_doc bd
                LEFT JOIN bichuv_slice_items bsi2 ON bd.id = bsi2.bichuv_doc_id AND bd.to_department = 13 AND bd.from_department = 11 and bd.document_type=2
                LEFT JOIN bichuv_slice_items bsi3 ON bd.id = bsi3.bichuv_doc_id AND bd.to_department = 14 AND bd.from_department = 11 and bd.document_type=2
                LEFT JOIN bichuv_slice_items bsiu ON bd.id = bsiu.bichuv_doc_id AND bd.to_department = 23 AND bd.from_department = 11 and bd.document_type=2
                LEFT JOIN bichuv_slice_items bsium ON bd.id = bsium.bichuv_doc_id AND bd.to_department = 11 and bd.from_department=11 and bd.document_type = 8
                 %s
                GROUP BY
                    DATE_FORMAT(bd.reg_date, '%%Y-%%m-%%d')
                HAVING works IS NOT NULL";

        $sql = sprintf($sql, $addQuery);

        $items = Yii::$app->db->createCommand($sql)->queryAll();

        return $items;
    }

    public function getRmDetail($tableName = 'raw_material')
    {
        $sql = "select id, name as n from {$tableName} rm  LIMIT 100000;";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($res, 'id', 'n');
    }

    public function getRmList()
    {
        $sql = "select bmi.id, 
                       rm.name as mato, 
                       nename.name as ne,
                       thr.name as thread,
                       pf.name as pus_fine,
                       c.color_id,
                       c.pantone,
                       ct.name as ctone  
                from bichuv_mato_info bmi
                left join raw_material rm on bmi.rm_id = rm.id
                left join ne nename on bmi.ne_id = nename.id
                left join thread thr on bmi.thread_id = thr.id
                left join pus_fine pf on bmi.pus_fine_id = pf.id
                left join color c on bmi.color_id = c.id
                left join color_tone ct on c.color_tone = ct.id
                ORDER BY rm.name LIMIT 100000;";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($res, 'id', function ($m) {
            return "{$m['mato']}-{$m['ne']}-{$m['thread']}|{$m['pus_fine']} ({$m['ctone']} {$m['color_id']} {$m['pantone']})";
        });
    }

    public function searchAcceptedSlice($params)
    {
        $sql = "SELECT ml.article        artikul,
                   bgr.nastel_party  nastel_no,
                   (SELECT GROUP_CONCAT(DISTINCT IF(ne.name != '' && thr.name != '' && pf.name != '',
                                           CONCAT('<span class=\"btn btn-default\">',rm.name, '|', ne.name, '-', thr.name, '|', pf.name,'</span>'),
                                           IF(thr.name != '', CONCAT('<span class=\"btn btn-default\">',rm.name, '|', thr.name,'</span>'), CONCAT('<span class=\"btn btn-default\">',rm.name,'</span>'))) SEPARATOR '<br>')
                    FROM bichuv_given_roll_items bgri
                             LEFT JOIN bichuv_mato_info bmi ON bgri.entity_id = bmi.id
                             LEFT JOIN raw_material rm ON bmi.rm_id = rm.id
                             left join ne on ne.id = bmi.ne_id
                             left join pus_fine pf on pf.id = bmi.pus_fine_id
                             left join thread thr on thr.id = bmi.thread_id
                    WHERE bgri.entity_type = 1
                      AND bgri.bichuv_given_roll_id = bgr.id
                   )                 mato,
                   bd.rag            qiyqim_mato,
                   GROUP_CONCAT(s.name) size,
                   sum(bsi.quantity) ish_soni
            FROM bichuv_slice_items bsi
                     LEFT JOIN bichuv_doc bd on bsi.bichuv_doc_id = bd.id
                     LEFT JOIN bichuv_given_rolls bgr ON bsi.nastel_party = bgr.nastel_party
                     LEFT JOIN model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                     LEFT JOIN models_list ml on mrp.models_list_id = ml.id
                    LEFT JOIN size s on bsi.size_id = s.id
            WHERE bd.document_type = 8 %s
            GROUP BY bsi.nastel_party, bd.id, ml.id, bgr.id %s
        ";
        $where = '';
        $this->load($params);

        if($this->nastel_no){
            $where .= "AND bgr.nastel_party LIKE '%{$this->nastel_no}%'";
        }
        if($this->artikul){
            $where .= "AND ml.article LIKE '%{$this->artikul}%'";
        }
        $having = '';
        if($this->rm_name){
            $having = "HAVING mato LIKE '%{$this->rm_name}%'";
        }

        $sql = sprintf($sql,$where,$having);

        $dataProvider = new SqlDataProvider([
           'sql' => $sql
        ]);
        if ($_GET['_tog7ce9367e'] == 'all') {
            $dataProvider->pagination = false;
        }

        return $dataProvider;
    }

    public function searchReportMato($params)
    {
        $sql = "select bgr.nastel_party as nastel_no,
                       ml.article as artikul,
                       rm.name     as mato,
                       thr.name    as iplik,
                       ct.name  as rang_toni,
                       bgri.party_no as party_nomer,
                       bgri.musteri_party_no as musteri_party_nomer,
                       bgri.roll_count as rulon_soni,
                       bgri.quantity as miqdori_kg,
                       bgr.reg_date as sana
                from bichuv_given_rolls bgr
                left join bichuv_given_roll_items bgri on bgr.id = bgri.bichuv_given_roll_id
                left join bichuv_mato_info bmi on bmi.id = bgri.entity_id
                left join raw_material rm on bmi.rm_id = rm.id
                left join ne nename on nename.id = bmi.ne_id
                left join pus_fine pf on pf.id = bmi.pus_fine_id
                left join thread thr on thr.id = bmi.thread_id
                left join color c on bmi.color_id = c.id
                left join color_tone ct on c.color_tone = ct.id
                left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                left join models_list ml on mrp.models_list_id = ml.id
                WHERE bgr.status > 2 %s
                GROUP BY bmi.id, bgr.nastel_party ORDER BY bgr.id DESC";
        $where = '';

        $this->load($params);
        if($this->nastel_no){
            $where .= "AND bgr.nastel_party LIKE '%{$this->nastel_no}%'";
        }
        if($this->rm_name){
            $where .= "AND rm.name LIKE '%{$this->rm_name}%'";
        }
        if($this->artikul){
            $where .= "AND ml.article LIKE '%{$this->artikul}%'";
        }
        if($this->iplik){
            $where .= "AND thr.name LIKE '%{$this->iplik}%'";
        }
        if($this->ctone){
            $where .= "AND ct.name LIKE '%{$this->ctone}%'";
        }
        if($this->party_nomer){
            $where .= "AND bgri.party_no LIKE '%{$this->party_nomer}%'";
        }
        if($this->musteri_party_nomer){
            $where .= "AND bgri.party_no LIKE '%{$this->musteri_party_nomer}%'";
        }

        $sql = sprintf($sql,$where);

        $dataProvider = new SqlDataProvider([
            'sql' => $sql
        ]);
        if ($_GET['_tog7ce9367e'] == 'all') {
            $dataProvider->pagination = false;
        }

        return $dataProvider;
    }

    public function getModelList()
    {
        $model = Product::find()->asArray()->orderBy(['name' => SORT_DESC])->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

    public function getMusteries(){
        $model = new BichuvDoc();
        return $model->getMusteries(null,3);
    }

    public function getMusteriList(){
        $musteri = Musteri::find()->asArray()->orderBy(['name' => SORT_ASC])->all();
        return ArrayHelper::map($musteri,'id','name');
    }

    public function getNastelList(){
        $nastel = BichuvSliceItems::find()->select(['nastel_party'])
            ->leftJoin('bichuv_doc','bichuv_doc.id = bichuv_slice_items.bichuv_doc_id')
            ->where(['bichuv_doc.status' => 3, 'bichuv_doc.is_service' => 1])->asArray()->groupBy(['bichuv_slice_items.nastel_party'])->all();
        return ArrayHelper::map($nastel,'nastel_party','nastel_party');
    }

    public function getDeparmentList(){
        $currentUserId = Yii::$app->user->id;

        $dept = ToquvUserDepartment::find()
            ->select(['tud.department_id id','td.name name'])
            ->from('{{%toquv_user_department}} tud')
            ->leftJoin('{{%toquv_departments}} td','tud.department_id = td.id')
            ->where(['user_id' => $currentUserId])
            ->asArray()
            ->all();

        $dept = ArrayHelper::map($dept, 'id', 'name');
        return $dept;
    }
    public function getSize(){
        $size = SizeType::find()->asArray()->all();
        $size = ArrayHelper::map($size,'id','name');
        return $size;
    }

    public function searchBichuvSliceAccept($params){
        $docType = BichuvDoc::DOC_TYPE_ACCEPTED;
        $statusSaved = BichuvDoc::STATUS_SAVED;

        $subSql = "";
        if(!empty($params['reg_date'])){
            $subSql .=" AND bd.reg_date BETWEEN '{$params['_fromDate']}' AND '{$params['_toDate']}'";
            $this->reg_date = $params['reg_date'];
        }
        if(!empty($params['_nastelParty'])){
            $subSql .=" AND bsi.nastel_party LIKE '%{$params['_nastelParty']}%'";
            $this->_nastelParty = $params['_nastelParty'];
        }
        if(!empty($params['_documentNumber'])){
            $subSql .=" AND bd.doc_number LIKE '%{$params['_documentNumber']}%'";
            $this->_documentNumber = $params['_documentNumber'];
        }
        if(!empty($params['size'])){
            $joinSizeId = join(',',$params['size']);
            $subSql .= " AND bsi.size_id IN ({$joinSizeId})";
            $this->size = $params['size'];
        }
        if(!empty($params['department'])){
            $joinDepartmentId = join(',',$params['department']);
            $subSql .= " AND bd.from_department IN ({$joinDepartmentId})";
            $this->department = $params['department'];
        }
        $sql = "SELECT 
                bd.doc_number,
                GROUP_CONCAT(DISTINCT s.name SEPARATOR ',') as name,
                SUM(bsi.quantity) as quantity,
                bd.reg_date,
                bsi.nastel_party as nastel_no,
                SUM(bsi.invalid_quantity) as invalid_quantity,
                td.name as department_name
                FROM bichuv_slice_items bsi 
            LEFT JOIN bichuv_doc bd 
                ON bsi.bichuv_doc_id = bd.id
            LEFT JOIN size s 
                ON bsi.size_id = s.id
            LEFT JOIN toquv_departments td 
                ON bd.from_department = td.id
            WHERE bd.document_type = {$docType}
            AND bd.status = {$statusSaved}
            {$subSql}
            GROUP BY bsi.nastel_party, bd.doc_number
            ORDER BY bsi.id
        ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql
        ]);

        return $dataProvider;
    }

    /** Buyurtma aksessuarlarini olish qoldigini aks omboriga korsatish */
    public function searchBichuvAcsModelAccept()
    {
        return null;
    }

}
