<?php

namespace app\modules\tikuv\models;

use app\modules\admin\models\ToquvUserDepartment;
use app\modules\toquv\models\SortName;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\tikuv\models\TikuvGoodsDocPack;
use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * LoginForm is the model behind the login form.
 *
 * @property array $departments
 * @property mixed $allKonveyerList
 * @property User|null $user This property is read-only.
 *
 */
class TikuvTempReportForm extends Model
{

    public $nastel_no;
    public $model_no;
    public $model_no2;
    public $to_department;
    public $from_department;
    public $from_musteri;
    public $reg_date;
    public $datetime_start;
    public $datetime_end;
    public $package_type;
    public $color;
    public $color2;
    public $customer;
    public $doer;
    public $code;
    public $inventory;
    public $sort_name;
    public $size;
    public $musteri_id;
    /**
     * For Slice Remain Item  Balans
     */
    public $departament;
    public $konveyer;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['nastel_no','to_department','model_no','model_no2','reg_date','from_department','color','color2'], 'string'],
            [['datetime_start','datetime_start'],'date'],
            [['package_type','customer','doer','from_musteri', 'konveyer','musteri_id'],'integer'],
        ];
    }

    /**
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * @return array
     */
    public function attributeLabels() {
        return [
            'nastel_no' => Yii::t('app','Nastel No'),
            'reg_date' => Yii::t('app','Sana'),
            'package_type' => Yii::t('app', 'Qadoq turi'),
            'convey' => Yii::t('app', 'Konveyer')
        ];
    }

    /**
     * @param $params
     * @return string
     */
    private function getAddQuery($params){

        $addQuery = "";


        if(!empty($params['nastel_no'])){
            $addQuery .= " AND tgdp.nastel_no LIKE '%{$params['nastel_no']}%' ";
            $this->nastel_no = $params['nastel_no'];
        }
        if(!empty($params['model_no'])){
            $addQuery .= " AND ml.article LIKE '%{$params['model_no']}%' ";
            $this->model_no = $params['model_no'];
        }

        if(!empty($params['reg_date'])){
            $addQuery .= " AND tgdp.reg_date BETWEEN '{$params['datetime_start']}' AND '{$params['datetime_end']}' ";
            $this->reg_date = $params['reg_date'];
        }
        if(!empty($params['package_type'])){
            $addQuery .= " AND tgd.package_type = '{$params['package_type']}' ";
            $this->package_type = $params['package_type'];
        }
        if(!empty($params['color'])){
            $addQuery .= " AND CONCAT(cp.code,': ',cp.name_ru) LIKE '%{$params['color']}%' ";
            $this->color = $params['color'];
        }
        if(!empty($params['customer'])){
            $addQuery .= " AND mo.musteri_id = '{$params['customer']}' ";
            $this->customer = $params['customer'];
        }
        if(!empty($params['doer'])){
            $addQuery .= " AND tgdp.barcode_customer_id = '{$params['doer']}' ";
            $this->doer = $params['doer'];
        }
        return $addQuery;
    }
    /**
     * @param $params
     * @return array|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public function searchIncoming($params){

        $addQuery = self::getAddQuery($params);
        if(!empty($params['from_department'])){
            $addQuery .= " AND tgdp.from_department = '{$params['from_department']}' ";
            $this->from_department = $params['from_department'];
        }

        $sql = "SELECT tgdp.from_department, tgdp.nastel_no, ml.article, 
            td.name department_name,  SUM(tgd.quantity) quantity, tgdp.reg_date,
            tgd.package_type,IF(tgd.package_type > 1,g.color_collection,CONCAT(cp.code,': ',cp.name_ru)) color, m.name customer,barcus.name doer
            from tikuv_goods_doc_pack tgdp 
            left join toquv_departments td on tgdp.from_department = td.id
            left join models_list ml on tgdp.model_list_id = ml.id
            left join tikuv_goods_doc tgd on tgdp.id = tgd.tgdp_id
            left join goods g on tgd.goods_id = g.id
            left join models_variations mv on tgdp.model_var_id=mv.id
            left join color_pantone cp on mv.color_pantone_id=cp.id 
            left join model_orders mo on tgdp.order_id = mo.id
            left join musteri m on mo.musteri_id = m.id 
            left join barcode_customers barcus on tgdp.barcode_customer_id = barcus.id
            where is_incoming=1 AND tgdp.status = 3 %s 
            GROUP BY tgdp.nastel_no,tgd.package_type
            ORDER BY reg_date DESC
            ";

        $sql = sprintf($sql, $addQuery);
        $items = Yii::$app->db->createCommand($sql)->queryAll();
        return $items;
    }
    /**
     * @param $params
     * @return array|\yii\db\DataReader
     * @throws \yii\db\Exception
     *
     */
    public function searchOutcoming($params){

        $addQuery = self::getAddQuery($params);

        if(!empty($params['to_department'])){
            $addQuery .= " AND tgdp.to_department = '{$params['to_department']}' ";
            $this->to_department = $params['to_department'];
        }

        $sql = "SELECT  tgdp.nastel_no, 
                        ml.article, 
                        tgdp.to_department,
                        SUM(tgd.quantity) quantity,  
        tgdp.reg_date, tgd.package_type,CONCAT(cp.code,': ',cp.name_ru) color, m.name customer,barcus.name doer 
              from tikuv_goods_doc_pack tgdp
                left join tikuv_goods_doc tgd on tgdp.id = tgd.tgdp_id
                left join goods g on tgd.goods_id = g.id 
                left join models_list ml on tgdp.model_list_id = ml.id
                left join models_variations mv on tgdp.model_var_id=mv.id
                left join color_pantone cp on mv.color_pantone_id=cp.id 
                left join model_orders mo on tgdp.order_id = mo.id
                left join musteri m on mo.musteri_id = m.id
                left join barcode_customers barcus on tgdp.barcode_customer_id = barcus.id
                where tgdp.is_incoming=2 AND (tgdp.status = 3 OR tgdp.status = 5) %s 
                GROUP BY  tgdp.nastel_no,tgd.package_type
                ORDER BY reg_date DESC";
        $sql = sprintf($sql, $addQuery);
        $items = Yii::$app->db->createCommand($sql)->queryAll();

        return $items;
    }

    /**
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public function searchDocitems($params)
    {
        $addQuery='';
        if(!empty($params['departament'])){
            $addQuery .= " AND tikuv_doc.to_department  = '{$params['departament']}'";
            $this->departament = $params['departament'];
        }
        if(!empty($params['konveyer'])){
            $addQuery .= " AND tk.id = '{$params['konveyer']}'";
            $this->konveyer = $params['konveyer'];
        }
        if(!empty($params['nastel_no'])){
            $addQuery .= " AND tdi.nastel_party_no LIKE '%{$params['nastel_no']}%'";
            $this->nastel_no = $params['nastel_no'];
        }
        if(!empty($params['customer'])){
            $addQuery .= " AND tikuv_doc.musteri_id = '{$params['customer']}'";
            $this->customer = $params['customer'];
        }
        if(!empty($params['model_no'])){
            $addQuery .= " AND ml.article LIKE '%{$params['model_no']}%'";
            $this->model_no = $params['model_no'];
        }
        if(!empty($params['model_no2'])){
            $addQuery .= " AND ml2.article LIKE '%{$params['model_no2']}%'";
            $this->model_no = $params['model_no2'];
        }
        if(!empty($params['color'])){
            $addQuery .= " AND cp.code LIKE '%{$params['color']}%'";
            $this->color2 = $params['color'];
        }
        if(!empty($params['color2'])){
            $addQuery .= " AND cp2.code LIKE '%{$params['color2']}%'";
            $this->color2 = $params['color2'];
        }

        $sql="SELECT
                    SUM(tdi.quantity) as inventory,
                    td.name as dept,
                    tk.name AS konvener,
                    tdi.nastel_party_no as party_no,
                    m.name as musteri,
                    GROUP_CONCAT(DISTINCT cp.code SEPARATOR ', ') as model_var,
                    GROUP_CONCAT(DISTINCT ml.article SEPARATOR ', ') as model,
                    GROUP_CONCAT(DISTINCT cp2.code SEPARATOR ', ') as model_var2,
                    GROUP_CONCAT(DISTINCT ml2.article SEPARATOR ', ') as model2
                    FROM
                    `tikuv_doc_items` AS tdi
                    LEFT JOIN tikuv_doc ON tdi.`tikuv_doc_id` = tikuv_doc.id
                    left join toquv_departments td on tikuv_doc.to_department = td.id
                    left join musteri m on tikuv_doc.musteri_id = m.id
                    inner join bichuv_given_rolls bgr on bgr.nastel_party = tdi.nastel_party_no
                    left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
                    left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                    left join models_list ml on mrp.models_list_id = ml.id
                    left join models_variations mv on mv.id = mrp.model_variation_id
                    left join color_pantone cp on mv.color_pantone_id = cp.id
                    left join model_rel_doc mrd on mrd.nastel_no = tdi.nastel_party_no
                    left join models_list ml2 on mrd.model_list_id = ml2.id
                    left join color_pantone cp2 on mrd.color_id = cp2.id
                    left join tikuv_konveyer tk on tkbgr.tikuv_konveyer_id = tk.id
                    WHERE
                    tikuv_doc.document_type = 7 AND tikuv_doc.to_department != 23 %s
                    GROUP BY tdi.nastel_party_no,td.id,tk.name,m.name ORDER BY td.id DESC";
        $sql = sprintf($sql,$addQuery);
        if (!$this->validate()) {
            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
            ]);
            if ($_GET['_tog7ce9367e'] == 'all') {
//                $dataProvider->pagination = false;
                $dataProvider->pagination = [
                    'pageSize' => 1000
                ];
            }
            return $dataProvider;
        }
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        if ($_GET['_tog7ce9367e'] == 'all') {
            $dataProvider->pagination = [
                'pageSize' => 1000
            ];
        }

        return $dataProvider;
    }
    public function searchSlice($params){
        $addQuery = "";
        if(empty($params)){
            return [];
        }
        if(!empty($params['departament'])){
            $addQuery .= " AND tsib.department_id = '{$params['departament']}'";
            $this->departament = $params['departament'];
        }
        if(!empty($params['konveyer'])){
            $konvIds = join(',', $params['konveyer']);
            $addQuery .= " AND tk.id IN ({$konvIds})";
            $this->konveyer = $params['konveyer'];
        }
        if(!empty($params['nastel_no'])){
            $addQuery .= " AND tsib.nastel_no LIKE '%{$params['nastel_no']}%'";
            $this->nastel_no = $params['nastel_no'];
        }
        if(!empty($params['customer'])){
            $addQuery .= " AND tsib.musteri_id = '{$params['customer']}'";
            $this->customer = $params['customer'];
        }
        if(!empty($params['model_no'])){
            $addQuery .= " AND ml.article LIKE '%{$params['model_no']}%'";
            $this->model_no = $params['model_no'];
        }
        if(!empty($params['model_no2'])){
            $addQuery .= " AND ml2.article LIKE '%{$params['model_no2']}%'";
            $this->model_no = $params['model_no2'];
        }
        if(!empty($params['color'])){
            $addQuery .= " AND cp.code LIKE '%{$params['color']}%'";
            $this->color2 = $params['color'];
        }
        if(!empty($params['color2'])){
            $addQuery .= " AND cp2.code LIKE '%{$params['color2']}%'";
            $this->color2 = $params['color2'];
        }


        $sql = "select SUM(tsib.inventory) as inventory,
                       tsib.nastel_no as party_no,
                       td.name as dept,
                       m.name as musteri,
                       m.id as m_id,
                       GROUP_CONCAT(DISTINCT CONCAT(cp.code,'<br>',cp.name_ru) SEPARATOR ', ') as model_var,
                       GROUP_CONCAT(DISTINCT ml.article SEPARATOR ', ') as model,
                       GROUP_CONCAT(DISTINCT CONCAT(cp2.code,'<br>',cp2.name_ru) SEPARATOR ', ') as model_var2,
                       GROUP_CONCAT(DISTINCT ml2.article SEPARATOR ', ') as model2,
                       tk.name as convener
                from tikuv_slice_item_balance tsib
                         left join toquv_departments td on tsib.department_id = td.id
                         left join musteri m on tsib.musteri_id = m.id
                         inner join bichuv_given_rolls bgr on bgr.nastel_party = tsib.nastel_no
                         left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
                         left join tikuv_konveyer tk on tkbgr.tikuv_konveyer_id = tk.id 
                         left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                         left join models_list ml on mrp.models_list_id = ml.id
                         left join models_variations mv on mv.id = mrp.model_variation_id
                         left join color_pantone cp on mv.color_pantone_id = cp.id
                        left join model_rel_doc mrd on mrd.nastel_no = tsib.nastel_no
                        left join models_list ml2 on mrd.model_list_id = ml2.id
                        left join models_variations mv2 on mv2.id = mrd.model_var_id
                        left join color_pantone cp2 on mrd.color_id = cp2.id
                where tsib.id IN (select MAX(tsib2.id) from tikuv_slice_item_balance tsib2 where tsib2.is_combined = 1 GROUP BY tsib2.size_id, tsib2.nastel_no) %s
                  AND tsib.inventory > 0 AND tsib.is_combined = 1 AND tsib.department_id in (select td.id from toquv_departments td where td.token =  'TIKUV_2_FLOOR' OR td.token = 'TIKUV_3_FLOOR')
                GROUP BY tsib.nastel_no ORDER BY tsib.id DESC;";
        $sql = sprintf($sql,$addQuery);

        $items = Yii::$app->db->createCommand($sql)->queryAll();
        return $items;
    }
    public function searchUsluga($params){
        $addQuery = "";
        if(!empty($params['departament'])){
            $addQuery .= " AND tpib.department_id = '{$params['departament']}'";
            $this->departament = $params['departament'];
        }
        if(!empty($params['from_musteri'])){
            $addQuery .= " AND tpib.from_musteri = '{$params['from_musteri']}'";
            $this->from_musteri = $params['from_musteri'];
        }
        if(!empty($params['nastel_no'])){
            $addQuery .= " AND tpib.nastel_no LIKE '%{$params['nastel_no']}%'";
            $this->nastel_no = $params['nastel_no'];
        }
        if(!empty($params['customer'])){
            $addQuery .= " AND tpib.musteri_id = '{$params['customer']}'";
            $this->customer = $params['customer'];
        }
        if(!empty($params['model_no'])){
            $addQuery .= " AND (ml.article LIKE '%{$params['model_no']}%' OR ml2.article LIKE '%{$params['model_no']}%')";
            $this->model_no = $params['model_no'];
        }
        if(!empty($params['color'])){
            $addQuery .= " AND (cp.code LIKE '%{$params['color']}%' OR cp.code LIKE '%{$params['color']}%')";
            $this->color = $params['color'];
        }


        $sql = "select SUM(tpib.inventory) as inventory,
                    tpib.nastel_no as party_no,
                    td.name as dept,
                    /*m.name as musteri,*/
                    /*mk.name as kasanachi,*/
                    GROUP_CONCAT(DISTINCT kasanachi.kasanachi) kasanachi,
                    GROUP_CONCAT(DISTINCT kasanachi.musteri) musteri,
                    m.id as m_id,
                    GROUP_CONCAT(DISTINCT cp.code SEPARATOR ', ') as model_var,
                    GROUP_CONCAT(DISTINCT ml.article SEPARATOR ', ') as model,
                    GROUP_CONCAT(DISTINCT cp2.code SEPARATOR ', ') as model_var2,
                    GROUP_CONCAT(DISTINCT ml2.article SEPARATOR ', ') as model2,
                    s.name as size,
                    sn.name as sort
                    from tikuv_package_item_balance tpib
                    left join toquv_departments td on tpib.from_department = td.id
                    left join musteri m on tpib.to_musteri = m.id
                    /*left join musteri mk on tpib.from_musteri = mk.id*/
                    left join model_rel_doc mrd on mrd.nastel_no = tpib.nastel_no
                    left join models_list ml on mrd.model_list_id = ml.id
                    left join models_variations mv on mv.id = mrd.model_var_id
                    left join color_pantone cp on mrd.color_id = cp.id
                    left join goods g on tpib.goods_id = g.id
                    left join size s on g.size = s.id
                    left join bichuv_given_rolls bgr ON bgr.nastel_party = tpib.nastel_no
                    left join model_rel_production mrp ON bgr.id = mrp.bichuv_given_roll_id
                    left join models_list ml2 ON mrp.models_list_id = ml2.id
                    left join models_variations mv2 ON mrp.model_variation_id = mv2.id
                    left join color_pantone cp2 ON cp2.id = mv2.color_pantone_id
                    LEFT JOIN (SELECT m2.name kasanachi,m.name musteri, tdi.nastel_party_no nastel_no FROM tikuv_doc td LEFT JOIN tikuv_doc_items tdi on td.id = tdi.tikuv_doc_id left join musteri m2 on td.to_musteri = m2.id LEFT JOIN musteri m ON m.id = td.musteri_id GROUP BY tdi.nastel_party_no,m2.id
                    ) kasanachi ON kasanachi.nastel_no = tpib.nastel_no 
                    left join sort_name sn on tpib.sort_type_id = sn.id
                    where tpib.id IN (select MAX(tpib2.id) from tikuv_package_item_balance tpib2 left join goods g on tpib2.goods_id = g.id
                    left join size s on g.size = s.id where tpib2.from_department in (select td.id from toquv_departments td where td.token = 'USLUGA') AND tpib2.dept_type = 'P' AND tpib2.package_type = 1 GROUP BY tpib2.sort_type_id,g.size, tpib2.nastel_no) %s
                    AND tpib.inventory > 0
                    GROUP BY tpib.nastel_no,s.id,sn.id ORDER BY tpib.id DESC";
        $sql = sprintf($sql,$addQuery);

        $items = Yii::$app->db->createCommand($sql)->queryAll();
        return $items;
    }

    /**
     * @param array $params
     * @return array
     * @throws \yii\db\Exception
     */
    public function searchRemainPackage($params= []){
        $addQuery = "";
        $this->load($params);
        $addSubQuery = "";
        if(!empty($params['nastel_no'])){
            $addQuery .= " AND tpib.nastel_no LIKE '%{$params['nastel_no']}%' ";
            $addSubQuery .= " AND tpib.nastel_no LIKE '%{$params['nastel_no']}%' ";
            $this->nastel_no = $params['nastel_no'];
        }
        if(!empty($params['model_no'])){
            $addQuery .= " AND ml.article LIKE '%{$params['model_no']}%' ";
            $this->model_no = $params['model_no'];
        }
        if(!empty($params['package_type'])){
            $addQuery .= " AND tpib.package_type = '{$params['package_type']}' ";
            $addSubQuery .= " AND tpib.package_type = '{$params['package_type']}' ";
            $this->package_type = $params['package_type'];
        }
        if(!empty($params['code'])){
            $addQuery .= " AND cp.code LIKE '{$params['code']}' ";
            $this->code = $params['code'];
        }
        if(!empty($params['inventory'])){
            $addQuery .= " AND  tpib.inventory = '{$params['inventory']}' ";
            $addSubQuery .= " AND  tpib.inventory = '{$params['inventory']}' ";
            $this->inventory = $params['inventory'];
        }
        if(!empty($params['from_department'])){
            $addQuery .= " AND tpib.department_id = '{$params['from_department']}' ";
            $addSubQuery .= " AND tpib.department_id = '{$params['from_department']}' ";
            $this->from_department = $params['from_department'];
        }
        if(!empty($params['sort_name'])){
            $addQuery .= " AND tpib.sort_type_id = '{$params['sort_name']}' ";
            $addSubQuery .= " AND tpib.sort_type_id = '{$params['sort_name']}' ";
            $this->sort_name = $params['sort_name'];
        }
        if(!empty($params['size'])){
            $addQuery .= (" AND s.id IN ('". implode("','",$params['size'])."')");
            $this->size = $params['size'];
        }if(!empty($params['musteri_id'])){
            $addQuery .= (" AND m.id IN ('". implode("','",$params['musteri_id'])."')");
        }
        $currUser = Yii::$app->user->id;
        $sql = "select  ml.article,
                        cp.code,
                        cp.name,
                        s.name as size_name,
                        tpib.inventory,
                        tpib.nastel_no,
                        td.name as dept,
                        st.name as sort_name,
                        tpib.package_type,
                        g.size_collection,
                        m.name musteri,
                        m.id musteri_id
                from tikuv_package_item_balance tpib
                left join models_list ml on tpib.model_list_id = ml.id
                left join models_variations mv on mv.id = tpib.model_var_id
                left join color_pantone cp on mv.color_pantone_id = cp.id
                left join goods g on tpib.goods_id = g.id
                left join size s on g.size = s.id
                left join toquv_departments td on tpib.from_department = td.id
                left join sort_name st on tpib.sort_type_id = st.id
                left join model_rel_doc mrd ON tpib.nastel_no=mrd.nastel_no
                LEFT JOIN tikuv_doc td2 ON mrd.tikuv_doc_id = td2.id
                left join model_orders mo ON mo.id=mrd.order_id
                left join musteri m ON m.id=td2.musteri_id
                where tpib.id in (
                                    select MAX(tpib.id) from tikuv_package_item_balance tpib
                                    where tpib.dept_type='TW' %s  
                                    GROUP BY tpib.goods_id, tpib.sort_type_id
                                ) and tpib.inventory > 0 %s GROUP BY tpib.goods_id, tpib.sort_type_id;";
        $sql = sprintf($sql, $addSubQuery, $addQuery);
        $out = Yii::$app->db->createCommand($sql)->queryAll();
        return $out;
    }

    public function searchRemainBrak($params= []){
        $addQuery = "";
        $addSubQuery = "";
        if(!empty($params['nastel_no'])){
            $addQuery .= " AND tpib.nastel_no LIKE '%{$params['nastel_no']}%' ";
            $addSubQuery .= " AND tpib.nastel_no LIKE '%{$params['nastel_no']}%' ";
            $this->nastel_no = $params['nastel_no'];
        }
        if(!empty($params['model_no'])){
            $addQuery .= " AND ml.article LIKE '%{$params['model_no']}%' ";
            $this->model_no = $params['model_no'];
        }
        if(!empty($params['package_type'])){
            $addQuery .= " AND tpib.package_type = '{$params['package_type']}' ";
            $addSubQuery .= " AND tpib.package_type = '{$params['package_type']}' ";
            $this->package_type = $params['package_type'];
        }
        if(!empty($params['code'])){
            $addQuery .= " AND cp.code LIKE '{$params['code']}' ";
            $this->code = $params['code'];
        }
        if(!empty($params['inventory'])){
            $addQuery .= " AND  tpib.inventory = '{$params['inventory']}' ";
            $addSubQuery .= " AND  tpib.inventory = '{$params['inventory']}' ";
            $this->inventory = $params['inventory'];
        }
        if(!empty($params['from_department'])){
            $addQuery .= " AND tpib.from_department = '{$params['from_department']}' ";
            $addSubQuery .= " AND tpib.from_department = '{$params['from_department']}' ";
            $this->from_department = $params['from_department'];
        }
        if(!empty($params['sort_name'])){
            $addQuery .= " AND tpib.sort_type_id = '{$params['sort_name']}' ";
            $addSubQuery .= " AND tpib.sort_type_id = '{$params['sort_name']}' ";
            $this->sort_name = $params['sort_name'];
        }
        if(!empty($params['size'])){
            $addQuery .= (" AND s.id IN ('". implode("','",$params['size'])."')");
            $this->size = $params['size'];
        }
        $sql = "select  ml.article,
                        cp.code,
                        cp.name,
                        s.name as size_name,
                        tpib.inventory,
                        tpib.nastel_no,
                        td.name as dept,
                        st.name as sort_name,
                        tpib.package_type,
                        g.size_collection
                from tikuv_package_item_balance tpib
                left join models_list ml on tpib.model_list_id = ml.id
                left join models_variations mv on mv.id = tpib.model_var_id
                left join color_pantone cp on mv.color_pantone_id = cp.id
                left join goods g on tpib.goods_id = g.id
                left join size s on g.size = s.id
                left join toquv_departments td on tpib.from_department = td.id
                left join sort_name st on tpib.sort_type_id = st.id
                where tpib.id in (
                                    select MAX(tpib.id) from tikuv_package_item_balance tpib
                                    where tpib.dept_type = 'BW' %s  
                                    GROUP BY tpib.goods_id, tpib.sort_type_id
                                ) and tpib.inventory > 0 %s GROUP BY tpib.goods_id, tpib.sort_type_id;";
        $sql = sprintf($sql, $addSubQuery, $addQuery);
        $out = Yii::$app->db->createCommand($sql)->queryAll();
        return $out;
    }
    /**
     * @param $token
     * @param bool $isMultiple
     * @return array|null
     */
    public function getDepartmentByToken($token, $isMultiple = false)
    {
        if ($token) {
            if ($isMultiple) {
                $result = ToquvDepartments::find()->select(['id', 'name'])
                    ->andFilterWhere(['status' => ToquvDepartments::STATUS_ACTIVE])
                    ->andFilterWhere(['in', 'token', $token])->asArray()->all();
            } else {
                $result = ToquvDepartments::find()->select(['id', 'name'])
                    ->andFilterWhere(['status' => ToquvDepartments::STATUS_ACTIVE])
                    ->andFilterWhere(['token' => $token])->asArray()->all();
            }
            if (!empty($result)) {
                return ArrayHelper::map($result, 'id', 'name');
            } else return null;
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getDepartments(){

        $departments = TikuvGoodsDocPack::find()
            ->where(['not', ['to_department' => null]])
            ->groupBy('to_department')
            ->asArray()
            ->all();

        $departments = ArrayHelper::map($departments, 'to_department', 'to_department');
        return $departments;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getUnitList($key = null)
    {
        $unitList = [
            1 => 'Dona',
            2 => 'Paket',
            3 => 'Blok',
            4 => 'Qop',
        ];
        if(!is_null($key)){
            return $unitList[$key];
        }else{
            return $unitList;
        }
    }

    public static function getSortNameList()
    {
        $sortname = SortName::find()->asArray()->all();
        return ArrayHelper::map($sortname, 'id', 'name');
    }
    public function getUserDepartmentUserId($user_id)
    {
        if ($user_id) {
            $result = ToquvUserDepartment::find()
                ->select(['td.id', 'td.name'])
                ->from('toquv_user_department tud')
                ->innerJoin('toquv_departments td', '`td`.`id` = `tud`.`department_id`')
                ->where(['tud.user_id' => $user_id])
                ->asArray()
                ->all();
            if (!empty($result)) {
                return ArrayHelper::map($result, 'id', 'name');
            }
        }
        return [];
    }

    public function getAllKonveyerList()
    {
        $sql = "SELECT id, name from tikuv_konveyer";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($list, 'id', 'name');
    }
}
