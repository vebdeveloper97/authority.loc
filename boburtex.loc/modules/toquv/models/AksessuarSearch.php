<?php

namespace app\modules\toquv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvKalite;
use yii\data\SqlDataProvider;

/**
 * AksessuarSearch represents the model behind the search form of `app\modules\toquv\models\ToquvKalite`.
 */
class AksessuarSearch extends ToquvKalite
{public $date_from;
    public $date_to;
    public $pus_fine_id;
    public $musteri_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'toquv_instructions_id', 'toquv_instruction_rm_id', 'toquv_rm_order_id', 'musteri_id', 'toquv_makine_id', 'user_id', 'sort_name_id', 'status', 'created_by', 'created_at', 'updated_at', 'pus_fine_id', 'toquv_raw_materials_id'], 'integer'],
            [['quantity'], 'number'],
            [['date_to','date_from'],'safe']
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
     * @param $params
     * @param null $is_brak
     * @return SqlDataProvider
     */
    public function search($params,$is_brak=null)
    {
        $brak = (empty($is_brak))?" AND (sn.code != 'BRAK')":" AND (sn.code = 'BRAK')";
        $query = "SELECT
                        tir.id tir_id,
                        tpf.id pus_fine_id,
                        tpf.name pus_fine,
                        trm.id mato_id,
                        trm.name mato,
                        trmc.name as mato_color,
                        t.document_number doc_number,
                        t.id toquv_orders_id,
                        tro.id toquv_rm_order_id,
                        m.name musteri_id,
                        SUM(tk.quantity) summa,
                        SUM(tk.count) count,
                        tro.quantity quantity,
                        CONCAT(tir.thread_length,'|',tir.finish_en,'|',tir.finish_gramaj) info,
                        tir.thread_length,
                        tir.finish_en,
                        tir.finish_gramaj,
                        m2.name order_musteri,
                        MAX(tk.created_at) created_at
                    FROM toquv_kalite tk
                             LEFT JOIN toquv_instruction_rm tir on tk.toquv_instruction_rm_id = tir.id
                             LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                             LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                             LEFT JOIN musteri m ON t.musteri_id = m.id
                             LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                             left join toquv_raw_material_color trmc ON trm.color_id = trmc.id   
                             LEFT JOIN model_orders_items moi on tro.moi_id = moi.id
                             LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                             LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                             LEFT JOIN sort_name sn on tk.sort_name_id = sn.id
                    WHERE tk.type = %d";
        $this->load($params);
        // add conditions that should always apply here
        if($this->musteri_id){
            $query .= " AND m.id = {$this->musteri_id}";
        }
        if($this->toquv_instructions_id){
            $query .= " AND t.id = {$this->toquv_instructions_id}";
        }
        if($this->toquv_rm_order_id){
            $query .= " AND trm.id = {$this->toquv_rm_order_id}";
        }
        if($this->pus_fine_id){
            $query .= " AND tpf.id = {$this->pus_fine_id}";
        }
        if($this->date_from){
            $query .= " AND tk.created_at >= ".strtotime($this->date_from . ' 00:00:00');
        }
        if($this->date_to){
            $query .= " AND tk.created_at <= ".strtotime($this->date_to . ' 23:59:59');
        }
        $query .= " GROUP BY tir.id";
        $type = ToquvRawMaterials::ACS;
        $query = sprintf($query,$type);
        $dataProvider = new SqlDataProvider([
            'sql' => $query,
        ]);
        return $dataProvider;
    }

    /**
     * @param $params
     * @param null $id
     * @param null $mato_id
     * @param null $pus_fine_id
     * @param null $thread_length
     * @param null $finish_en
     * @param null $finish_gramaj
     * @param int $st
     * @param null $is_brak
     * @return SqlDataProvider
     */
    public function searchView($params,$id = null, $mato_id = null, $pus_fine_id = null, $thread_length = null, $finish_en = null, $finish_gramaj = null,$st = 1, $is_brak = null)
    {
        $tir = '';
        if($id){
            $tir = "AND (tir.id = {$id})";
        }
        $mato = '';
        if($mato_id){
            $mato = "AND (trm.id = {$mato_id})";
        }
        $pus = '';
        if($pus_fine_id){
            $pus = "AND (tpf.id = {$pus_fine_id})";
        }
        $thread = '';
        if($thread_length){
            $thread = "AND (tir.thread_length = {$thread_length})";
        }
        $en = '';
        if($finish_en){
            $en = "AND (tir.finish_en = {$finish_en})";
        }
        $gramaj = '';
        if($finish_gramaj){
            $gramaj = "AND (tir.finish_gramaj = {$finish_gramaj})";
        }
        $status = '';
        if($st){
            $status = "AND (tk.status = {$st})";
        }
        $brak = (empty($is_brak))?" AND (sn.code != 'BRAK')":" AND (sn.code = 'BRAK')";
        $sql = "SELECT
                tir.id tir_id,
                tpf.name pus_fine,
                trm.id mato_id,
                trm.name mato,
                trmc.name as mato_color,
                t.document_number doc_number,
                t.id toquv_orders_id,
                tro.id toquv_rm_order_id,
                m.name musteri_id,
                tk.quantity summa,
                tk.count count,
                tro.quantity quantity,
                CONCAT(tir.thread_length,'|',tir.finish_en,'|',tir.finish_gramaj) info,
                tk.created_at created_at,
                tk.status status,
                sn.name sort,
                m2.name order_musteri,
                u.user_fio user_fio
                FROM toquv_kalite tk
                         LEFT JOIN toquv_instruction_rm tir on tk.toquv_instruction_rm_id = tir.id
                         LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                         LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                         LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                         LEFT JOIN musteri m ON t.musteri_id = m.id
                         LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                         left join toquv_raw_material_color trmc ON trm.color_id = trmc.id 
                         LEFT JOIN sort_name sn on tk.sort_name_id = sn.id
                         LEFT JOIN model_orders_items moi on tro.moi_id = moi.id
                         LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                         LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                         LEFT JOIN users u ON tk.user_id = u.id
                WHERE   tk.type = %d
                        %s
                        %s 
                        %s
                        %s
                        %s
                        %s
                        %s";
        $type = ToquvRawMaterials::ACS;
        $sql = sprintf($sql, $type, $tir, $mato, $pus, $thread, $en, $gramaj,$status);
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
}
