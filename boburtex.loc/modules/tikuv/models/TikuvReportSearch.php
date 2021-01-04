<?php

namespace app\modules\tikuv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tikuv\models\TikuvOutcomeProductsPack;
use yii\data\SqlDataProvider;

/**
 * TikuvReportSearch represents the model behind the search form of `app\modules\tikuv\models\TikuvOutcomeProductsPack`.
 */
class TikuvReportSearch extends TikuvOutcomeProductsPack
{
    public $size_id;
    public $model;
    public $model_var;
    public $from_date;
    public $to_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_by', 'created_at', 'updated_at', 'doc_id', 'order_item_id', 'model_id', 'tikuv_slice_item_balance_id', 'model_list_id', 'model_var_id', 'order_id', 'to_department', 'type', 'bsib_id','barcode_customer_id'], 'integer'],
            [['order_no', 'add_info', 'toquv_partiya', 'boyoq_partiya', 'nastel_no', 'reg_date', 'model', 'model_var', 'department_id', 'musteri_id', 'from_musteri', 'to_musteri', 'from_date','to_date'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $sql = "SELECT topp.id,
                       GROUP_CONCAT(DISTINCT CONCAT('<code>', top.nastel_no, '</code>') SEPARATOR ',<br>') nastel_no,
                       CONCAT('<code>', topp.nastel_no, '</code>')                                         nastel,
                       CONCAT('<code>', td.name, '</code>')                                                department_id,
                       m2.name                                                                             service,
                       m.name                                                                              musteri,
                       GROUP_CONCAT(DISTINCT CONCAT('<b>', s.name) SEPARATOR ', ')                         size,
                       TRUNCATE(sum(top.quantity), 0)                                                      remain,
                       (SELECT TRUNCATE(sum(top2.quantity), 0)
                        FROM tikuv_outcome_products top2
                        WHERE top2.pack_id = top.pack_id
                          AND top2.sort_type_id = 1)                                                       sort1,
                       (SELECT TRUNCATE(sum(top2.quantity), 0)
                        FROM tikuv_outcome_products top2
                        WHERE top2.pack_id = top.pack_id
                          AND top2.sort_type_id = 2)                                                       sort2,
                       (SELECT TRUNCATE(sum(top2.quantity), 0)
                        FROM tikuv_outcome_products top2
                        WHERE top2.pack_id = top.pack_id
                          AND top2.sort_type_id = 3)                                                       brak,
                       GROUP_CONCAT(DISTINCT concat('<b>', ml.article, '</b> ', ml.name))                  model,
                       GROUP_CONCAT(DISTINCT CONCAT('<b>', cp.code, '</b> '))                              model_var,
                       IF(topp.reg_date > '1970-01-02 00:00:00', CONCAT('<b>', DATE_FORMAT(topp.reg_date, '%d.%m.%Y %H:%i'), '</b> '),
                          '')                                                                              accepted_date
                        FROM tikuv_outcome_products_pack topp
                        LEFT JOIN tikuv_outcome_products top ON topp.id = top.pack_id
                        LEFT JOIN musteri m on topp.musteri_id = m.id
                        LEFT JOIN goods g ON top.goods_id = g.id
                        LEFT JOIN size s on top.size_id = s.id
                        LEFT JOIN models_list ml on g.model_id = ml.id
                        LEFT JOIN models_variations mv on g.model_var = mv.id
                        LEFT JOIN color_pantone cp ON g.color = cp.id
                        LEFT JOIN toquv_departments td on topp.department_id = td.id
                        LEFT JOIN musteri m2 ON topp.from_musteri = m2.id
                        WHERE g.id IS NOT NULL AND top.quantity > 0";
        $this->load($params);
        if (!$this->validate()) {
            $sql .= " GROUP BY topp.id,topp.department_id ORDER BY topp.musteri_id ASC,topp.department_id ASC,ml.id ASC,cp.id ASC,size ASC,accepted_date ASC,topp.id DESC";
            return $dataProvider = new SqlDataProvider([
                'sql' => $sql,
            ]);
        }
        if($this->musteri_id){
                $sql .= (!is_array($this->musteri_id)) ? " AND m.id = {$this->musteri_id}" : " AND m.id IN (" . implode(',', $this->musteri_id) . ")";
        }
        if($this->department_id){
                $sql .= (!is_array($this->department_id)) ? " AND topp.department_id = {$this->department_id}" : " AND topp.department_id IN (" . implode(',', $this->department_id) . ")";
        }
        if($this->from_musteri){
                $sql .= (!is_array($this->from_musteri))?" AND m2.id = {$this->from_musteri}":" AND m2.id IN (".implode(',', $this->from_musteri).")";
        }
        if($this->size_id){
            $sql .= (!is_array($this->size_id))?" AND s.id = {$this->size_id}":" AND s.id IN (".implode(',', $this->size_id).")";
        }
        if($this->nastel_no){
            $sql .= (!is_array($this->nastel_no))?" AND top.nastel_no = '{$this->nastel_no}'":" AND top.nastel_no IN ('".implode("','", $this->nastel_no)."')";
        }
        if($this->model){
            $sql .= " AND (ml.article LIKE '%{$this->model}%' OR ml.name LIKE '%{$this->model}%')";
        }
        if($this->model_var){
            $sql .= " AND (cp.code LIKE '%{$this->model_var}%' OR mv.name LIKE '%{$this->model_var}%')";
        }
        $sql .= " GROUP BY topp.id,topp.department_id ORDER BY topp.musteri_id ASC,topp.department_id ASC,ml.id ASC,cp.id ASC,size ASC,accepted_date ASC,topp.id DESC";
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        if ($_GET['_tog7ce9367e'] == 'all') {
            $dataProvider->pagination = false;
        }
        return $dataProvider;
    }
    public function searchAcceptedReport($params)
    {
        $from = date('Y-m-01');
        $to  = date('Y-m-t');
        $add="AND ({$from} >=tgdp.reg_date AND {$to}<=tgdp.reg_date)";
        $this->load($params);
        if(!empty($this->to_date) && !empty($this->from_date)){
            $add ="";
            $m = $this->from_date;
            $n = $this->to_date;
            $add .= " AND (('$m'<= tgdp.reg_date  AND '$n'>= tgdp.reg_date) OR ('$m'>=tgdp.reg_date  AND '$n'<=tgdp.reg_date)) ";
        }
        $sql = "SELECT
                        DATE_FORMAT(tgdp.reg_date, '%d.%m.%Y') AS sana,
                        SUM(tgd2.quantity) AS qavat2,
                        SUM(tgd3.quantity) AS qavat3,
                        SUM(tgd.quantity) AS usluga
                    FROM
                        `tikuv_goods_doc_pack` AS tgdp
                    LEFT JOIN tikuv_goods_doc AS tgd2
                    ON (tgdp.id = tgd2.tgdp_id) AND tgdp.from_department = 13
                    LEFT JOIN tikuv_goods_doc AS tgd3 ON (tgdp.id = tgd3.tgdp_id) AND tgdp.from_department = 14
                    LEFT JOIN tikuv_goods_doc AS tgd  ON (tgdp.id = tgd.tgdp_id) AND tgdp.from_department = 23
                    WHERE tgdp.is_incoming = 1 {$add}  GROUP BY DATE_FORMAT(tgdp.reg_date, '%Y-%m-%d')
                    ORDER BY tgdp.reg_date";
        $dataProvider = Yii::$app->db->createCommand($sql)->queryAll();
        return $dataProvider;
    }
}
