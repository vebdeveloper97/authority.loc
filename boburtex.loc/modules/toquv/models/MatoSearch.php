<?php

namespace app\modules\toquv\models;
use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;

/**
 * ToquvKaliteSearch represents the model behind the search form of `app\modules\toquv\models\ToquvKalite`.
 */
class MatoSearch extends ToquvKalite
{
    public $date_from;
    public $date_to;
    public $pus_fine_id;
    public $musteri_id;
    public $order_id;
    public $thread_length;
    public $finish_en;
    public $finish_gramaj;
    public $is_closed;
    public $order_type;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'toquv_instruction_rm_id', 'toquv_rm_order_id', 'status', 'created_by', 'updated_at', 'is_closed', 'order_type'], 'integer'],
            [['thread_length', 'finish_en', 'finish_gramaj'], 'string', 'max' => 30],
            [['quantity'], 'number'],
            [['musteri_id', 'toquv_instructions_id', 'toquv_raw_materials_id', 'user_kalite_id', 'toquv_makine_id',  'sort_name_id', 'user_id', 'created_at', 'date_to', 'date_from', 'order_id', 'pus_fine_id'],'safe']
        ];
    }
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'is_closed' => Yii::t('app', 'Buyurtma holati'),
            'order_type' => Yii::t('app', 'Buyurtma turi'),
        ]);
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
    public function search($params,$is_brak=null,$mak=null)
    {
        $code = Yii::$app->request->get('code');
        $kod = '';
        if(!empty($code)){
            $kod = " AND tk.code like '%{$code}%'";
        }
        $select_makine = ($mak)?",tm.name toquv_makine_id":'';
        $join_makine = ($mak)?"LEFT JOIN toquv_makine tm on tk.toquv_makine_id = tm.id":'';
        $group_makine = ($mak)?",tm.id":'';
        $brak1 = (empty($is_brak)) ? " AND (sn1.code != 'BRAK')" : " AND (sn1.code = 'BRAK')";
        $query = "SELECT
                        tir.id tir_id,
                        tpf.id pus_fine_id,
                        tpf.name pus_fine,
                        trm.id mato_id,
                        CONCAT(trm.code,' ',trm.name) mato,
                        t.document_number doc_number,
                        t.id toquv_orders_id,
                        tro.id toquv_rm_order_id,
                        m.name musteri_id,
                        m2.name model_musteri,
                        SUM(tk.quantity) summa,
                        tro.quantity quantity,
                        (tro.quantity - SUM(tk.quantity)) remain,
                        ##CONCAT(tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj) info,
                        (select SUM(tk1.quantity) FROM toquv_kalite tk1 left join sort_name sn1 ON tk1.sort_name_id = sn1.id WHERE tk1.status = 1 AND tk1.toquv_instruction_rm_id = tk.toquv_instruction_rm_id AND tk1.sort_name_id = tk1.sort_name_id %s) summa_no_sended,
                        tir.thread_length,
                        tir.finish_en,
                        tir.finish_gramaj,
                        MAX(tk.created_at) created_at,
                        cp.name as cname,
                        cp.code as ccode, r, g, b,
                        type.name as tname,
                        tro.color_pantone_id,
                        tro.color_id,
                        c.color_id cl_name,
                        c.color cl_color,
                        tro.model_code
                        %s
                    FROM toquv_kalite tk
                             LEFT JOIN toquv_instruction_rm tir on tk.toquv_instruction_rm_id = tir.id
                             LEFT JOIN toquv_instructions ti ON tir.toquv_instruction_id = ti.id
                             LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                             LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                             LEFT JOIN musteri m ON t.musteri_id = m.id
                             LEFT JOIN musteri m2 ON t.model_musteri_id = m2.id
                             LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                             LEFT JOIN sort_name sn on tk.sort_name_id = sn.id
                             left join color_pantone cp on tro.color_pantone_id = cp.id
                             left join color_panton_type as type ON cp.color_panton_type_id = type.id
                             left join color c ON tro.color_id = c.id
                             %s
                    WHERE tk.type = %d 
                          %s
                        %s
                        %s";
        $this->load($params);
        // add conditions that should always apply here
        $is_closed = (!$this->status)?" ":" AND ti.is_closed = {$this->status}";
        if($this->sort_name_id){
            $brak = " AND tk.sort_name_id IN (".implode(',', $this->sort_name_id).")";
        }else {
            $brak = (empty($is_brak)) ? " AND (sn.code != 'BRAK')" : " AND (sn.code = 'BRAK')";
        }
        if($this->musteri_id){
            $query .= (!is_array($this->musteri_id))?" AND m.id = {$this->musteri_id}":" AND m.id IN (".implode(',', $this->musteri_id).")";
        }
        if($this->toquv_instructions_id){
            $query .= (!is_array($this->toquv_instructions_id))?" AND t.id = {$this->toquv_instructions_id}":" AND t.id IN (".implode(',', $this->toquv_instructions_id).")";
        }
        if($this->toquv_rm_order_id){
            $query .= " AND trm.id = {$this->toquv_rm_order_id}";
        }
        if($this->order_type){
            $query .= " AND t.order_type = {$this->order_type}";
        }
        if($this->toquv_makine_id){
            $query .= (!is_array($this->toquv_makine_id))?" AND tk.toquv_makine_id = {$this->toquv_makine_id}":" AND tk.toquv_makine_id IN (".implode(',', $this->toquv_makine_id).")";
        }
        if($this->user_id){
            $query .= (!is_array($this->user_id))?" AND tk.user_id = {$this->user_id}":" AND tk.user_id IN (".implode(',', $this->user_id).")";
        }
        if($this->user_kalite_id){
            $query .= (!is_array($this->user_kalite_id))?" AND tk.user_kalite_id = {$this->user_kalite_id}":" AND tk.user_kalite_id IN (".implode(',', $this->user_kalite_id).")";
        }
        if($this->pus_fine_id){
            $query .= (!is_array($this->pus_fine_id))?" AND tpf.id = {$this->pus_fine_id}":" AND m.id IN (".implode(',', $this->pus_fine_id).")";
        }
        if($this->thread_length){
            $query .= " AND tir.thread_length = {$this->thread_length}";
        }
        if($this->finish_en){
            $query .= " AND tir.finish_en = {$this->finish_en}";
        }
        if($this->finish_gramaj){
            $query .= " AND tir.finish_gramaj = {$this->finish_gramaj}";
        }
        if($this->date_from){
            $query .= " AND tk.created_at >= ".strtotime($this->date_from);
        }
        if($this->date_to){
            $query .= " AND tk.created_at <= ".strtotime($this->date_to);
        }
        $query .= " GROUP BY tir.id,tk.toquv_instruction_rm_id %s";
        if($this->is_closed){
            switch ($this->is_closed){
                case 1:
                    $query .= " HAVING remain > 0";
                    break;
                case 2:
                    $query .= " HAVING remain < 0";
                    break;
            }
        }
        $query .= " ORDER BY t.id DESC";
        $type = ToquvRawMaterials::MATO;
        $query = sprintf($query,$brak1,$select_makine,$join_makine,$type,$is_closed,$kod,$brak,$group_makine);
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
    public function searchView($params,$id = null, $st = 1, $is_brak = null)
    {
        $tir = '';
        if($id){
            $tir = "AND (tir.id = {$id})";
        }
        $status = '';
        if($st){
            $status = "AND (tk.status = {$st})";
        }
        $code = Yii::$app->request->get('code');
        $kod = '';
        if(!empty($code)){
            $kod = " AND tk.code like '%{$code}%'";
        }
        $query = "SELECT
                tk.id id,
                tir.id tir_id,
                tpf.name pus_fine,
                trm.id mato_id,
                CONCAT(trm.code,' ',trm.name) mato,
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
                         LEFT JOIN sort_name sn on tk.sort_name_id = sn.id
                         LEFT JOIN users u ON tk.user_id = u.id
                         LEFT JOIN users_info ui ON ui.users_id = u.id
                         LEFT JOIN users uk ON tk.user_kalite_id = uk.id
                         LEFT JOIN toquv_makine tm ON tk.toquv_makine_id = tm.id
                WHERE   tk.type = %d
                        %s
                        %s
                        %s
                        %s";
        $this->load($params);
        if($this->sort_name_id){
            $brak = " AND tk.sort_name_id IN (".implode(',', $this->sort_name_id).")";
        }else {
            $brak = (empty($is_brak)) ? " AND (sn.code != 'BRAK')" : " AND (sn.code = 'BRAK')";
        }
        if($this->musteri_id){
            $query .= (!is_array($this->musteri_id))?" AND m.id = {$this->musteri_id}":" AND m.id IN (".implode(',', $this->musteri_id).")";
        }
        if($this->toquv_instructions_id){
            $query .= (!is_array($this->toquv_instructions_id))?" AND t.id = {$this->toquv_instructions_id}":" AND t.id IN (".implode(',', $this->toquv_instructions_id).")";
        }
        if($this->toquv_rm_order_id){
            $query .= " AND trm.id = {$this->toquv_rm_order_id}";
        }
        if($this->toquv_makine_id){
            $query .= (!is_array($this->toquv_makine_id))?" AND tk.toquv_makine_id = {$this->toquv_makine_id}":" AND tk.toquv_makine_id IN (".implode(',', $this->toquv_makine_id).")";
        }
        if($this->user_id){
            $query .= (!is_array($this->user_id))?" AND tk.user_id = {$this->user_id}":" AND tk.user_id IN (".implode(',', $this->user_id).")";
        }
        if($this->user_kalite_id){
            $query .= (!is_array($this->user_kalite_id))?" AND tk.user_kalite_id = {$this->user_kalite_id}":" AND tk.user_kalite_id IN (".implode(',', $this->user_kalite_id).")";
        }
        if($this->pus_fine_id){
            $query .= (!is_array($this->pus_fine_id))?" AND tpf.id = {$this->pus_fine_id}":" AND m.id IN (".implode(',', $this->pus_fine_id).")";
        }
        if($this->thread_length){
            $query .= " AND tir.thread_length = {$this->thread_length}";
        }
        if($this->finish_en){
            $query .= " AND tir.finish_en = {$this->finish_en}";
        }
        if($this->finish_gramaj){
            $query .= " AND tir.finish_gramaj = {$this->finish_gramaj}";
        }
        if($this->date_from){
            $query .= " AND tk.created_at >= ".strtotime($this->date_from);
        }
        if($this->date_to){
            $query .= " AND tk.created_at <= ".strtotime($this->date_to);
        }
        $type = ToquvRawMaterials::MATO;
        $query = sprintf($query,$type,$tir,$status,$kod,$brak);
        $query .= "ORDER BY tm.id ASC";
        $dataProvider = new SqlDataProvider([
            'sql' => $query,
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
