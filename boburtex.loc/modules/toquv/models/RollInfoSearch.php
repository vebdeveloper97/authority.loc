<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\RollInfo;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

/**
 * RollInfoSearch represents the model behind the search form of `app\modules\toquv\models\RollInfo`.
 */
class RollInfoSearch extends RollInfo
{
    public $slug;
    public $department;
    public $title;
    public $musteri_id;
    public $date_from;
    public $date_to;
    public $pus_fine_id;
    public $toquv_orders_id;
    public $toquv_rm_order_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'entity_id', 'entity_type', 'unit_id', 'tir_id', 'moi_id', 'toquv_kalite_id', 'toquv_departments_id', 'old_departments_id', 'sort_name_id', 'status', 'created_by', 'created_at', 'updated_at', 'musteri_id','pus_fine_id', 'toquv_orders_id'], 'integer'],
            [['code', 'accept_date', 'date_from', 'date_to'], 'safe'],
            [['quantity'], 'number'],
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
    public function init()
    {
        $slug = Yii::$app->request->get('slug');
        if (!empty($slug)) {
            $this->slug = $slug;
        }
        switch ($this->slug){
            case 'mato_sklad':
                $this->department = self::getDept('TOQUV_MATO_SKLAD')['id'];
                $this->title = self::getDept('TOQUV_MATO_SKLAD')['name'];
                break;
            case 'boyoq':
                $this->department = self::getDept('BOYOQ')['id'];
                $this->title = self::getDept('BOYOQ')['name'];
                break;
        }
        return parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$entity_type=null,$is_brak=null)
    {
        $brak = (empty($is_brak))?" AND (sn.code != 'BRAK')":" AND (sn.code = 'BRAK')";
        $dept = ($this->department)?" AND (ri.toquv_departments_id = ".$this->department.")":"";
        $tip = ToquvRawMaterials::ENTITY_TYPE_MATO;
        $type = ($this->entity_type)?" AND (ri.entity_type = {$this->entity_type})" : " AND (ri.entity_type = {$tip})";
        $where = ($tip||$this->entity_type)?"{$type}":"";
        $query = "SELECT
                        COUNT(ri.id) count,
                        tir.id tir_id,
                        tpf.id pus_fine_id,
                        tpf.name pus_fine,
                        trm.id mato_id,
                        trm.name mato,
                        t.document_number doc_number,
                        t.id toquv_orders_id,
                        tro.id toquv_rm_order_id,
                        m.name musteri,
                        m.id musteri_id,
                        SUM(ri.quantity) summa,
                        tro.quantity quantity,
                        ##CONCAT(tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj) info,
                        tir.thread_length,
                        tir.finish_en,
                        tir.finish_gramaj,
                        MAX(ri.updated_at) accept_date,
                        toquv_departments_id tdi,
                        moi.id moi_id,
                        ml.name model,
                        m2.name order_musteri
                    FROM roll_info ri
                             ##LEFT JOIN toquv_kalite tk ON ri.toquv_kalite_id = tk.id
                             LEFT JOIN toquv_instruction_rm tir on ri.tir_id = tir.id
                             LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                             LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                             LEFT JOIN musteri m ON t.musteri_id = m.id
                             LEFT JOIN model_orders_items moi ON ri.moi_id = moi.id 
                             LEFT JOIN models_list ml on moi.models_list_id = ml.id 
                             LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                             LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                             LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                             LEFT JOIN sort_name sn on ri.sort_name_id = sn.id
                             WHERE ri.status = 1
                            %s 
                            %s
                            %s";
        $this->load($params);
        // add conditions that should always apply here
        if($this->musteri_id){
            $query .= " AND m.id = {$this->musteri_id}";
        }
        if($this->toquv_orders_id){
            $query .= " AND t.id = {$this->toquv_orders_id}";
        }
        if($this->entity_id){
            $query .= " AND trm.id = {$this->entity_id}";
        }
        if($this->pus_fine_id){
            $query .= " AND tpf.id = {$this->pus_fine_id}";
        }
        if($this->date_from){
            $query .= " AND ri.accept_date >= '".date('Y-m-d H:i:s',strtotime($this->date_from . ' 00:00:00'))."'";
        }
        if($this->date_to){
            $query .= " AND ri.accept_date <= '".date('Y-m-d H:i:s',strtotime($this->date_to . ' 23:59:59'))."'";
        }
        $query .= " GROUP BY tir.id";
        $query = sprintf($query,$where,$dept,$brak);
        $dataProvider = new SqlDataProvider([
            'sql' => $query,
        ]);
        return $dataProvider;
    }
    public function searchView($params,$id,$is_model=null,$is_brak=null)
    {
        $brak = (empty($is_brak))?" AND (sn.code != 'BRAK')":" AND (sn.code = 'BRAK')";
        $dept = ($this->department)?" AND (ri.toquv_departments_id = ".$this->department.")":"";
        $tip = ToquvRawMaterials::ENTITY_TYPE_MATO;
        $type = ($this->entity_type)?" AND (ri.entity_type = {$this->entity_type})" : " AND (ri.entity_type = {$tip})";
        $where = ($tip||$this->entity_type)?"{$type}":"";
        $query = "SELECT
                        ri.id,
                        ri.code code,
                        tir.id tir_id,
                        tpf.id pus_fine_id,
                        tpf.name pus_fine,
                        trm.id mato_id,
                        trm.name mato,
                        t.document_number doc_number,
                        t.id toquv_orders_id,
                        tro.id toquv_rm_order_id,
                        m.name musteri,
                        m.id musteri_id,
                        ri.quantity summa,
                        tro.quantity quantity,
                        ##CONCAT(tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj) info,
                        tir.thread_length,
                        tir.finish_en,
                        tir.finish_gramaj,
                        ri.created_at created_at,
                        toquv_departments_id tdi,
                        moi.id moi_id,
                        ml.name model,
                        m2.name order_musteri,
                        sn.name sort,
                        tk.created_at done_date,
                        ri.updated_at accept_date,
                        u.user_fio toquvchi,
                        tk.smena,
                        sn.id sort_id
                    FROM roll_info ri
                             LEFT JOIN toquv_kalite tk ON ri.toquv_kalite_id = tk.id
                             LEFT JOIN toquv_instruction_rm tir on ri.tir_id = tir.id
                             LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                             LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                             LEFT JOIN musteri m ON t.musteri_id = m.id
                             LEFT JOIN model_orders_items moi ON ri.moi_id = moi.id 
                             LEFT JOIN models_list ml on moi.models_list_id = ml.id 
                             LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                             LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                             LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                             LEFT JOIN sort_name sn on ri.sort_name_id = sn.id
                             LEFT JOIN users u ON tk.user_id = u.id
                             WHERE ri.status = 1
                             AND tir.id = %d
                            %s 
                            %s
                            %s";
        $this->load($params);
        // add conditions that should always apply here
        if($this->musteri_id){
            $query .= " AND m.id = {$this->musteri_id}";
        }
        if($this->toquv_orders_id){
            $query .= " AND t.id = {$this->toquv_orders_id}";
        }
        if($this->entity_id){
            $query .= " AND trm.id = {$this->entity_id}";
        }
        if($this->pus_fine_id){
            $query .= " AND tpf.id = {$this->pus_fine_id}";
        }
        if($this->date_from){
            $query .= " AND ri.accept_date >= '".date('Y-m-d H:i:s',strtotime($this->date_from . ' 00:00:00'))."'";
        }
        if($this->date_to){
            $query .= " AND ri.accept_date <= '".date('Y-m-d H:i:s',strtotime($this->date_to . ' 23:59:59'))."'";
        }
        $query = sprintf($query,$id,$where,$dept,$brak);
        if($is_model){
            return Yii::$app->db->createCommand($query)->queryAll();
        }
        $dataProvider = new SqlDataProvider([
            'sql' => $query,
            'pagination' => [
                'pageSize' => 30
            ]
        ]);
        return $dataProvider;
    }
    public function getInfo($id,$is_model=null){
        $sql = "SELECT
                        COUNT(ri.id) count,
                        tir.id tir_id,
                        tpf.id pus_fine_id,
                        tpf.name pus_fine,
                        trm.id mato_id,
                        trm.name mato,
                        t.document_number doc_number,
                        t.id toquv_orders_id,
                        tro.id toquv_rm_order_id,
                        m.name musteri,
                        m.id musteri_id,
                        SUM(ri.quantity) summa,
                        tro.quantity quantity,
                        CONCAT(tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj) info,
                        tir.thread_length,
                        tir.finish_en,
                        tir.finish_gramaj,
                        MAX(ri.created_at) created_at,
                        toquv_departments_id tdi,
                        moi.id moi_id,
                        ml.name model,
                        m2.name order_musteri
                    FROM roll_info ri
                             ##LEFT JOIN toquv_kalite tk ON ri.toquv_kalite_id = tk.id
                             LEFT JOIN toquv_instruction_rm tir on ri.tir_id = tir.id
                             LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                             LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                             LEFT JOIN musteri m ON t.musteri_id = m.id
                             LEFT JOIN model_orders_items moi ON ri.moi_id = moi.id 
                             LEFT JOIN models_list ml on moi.models_list_id = ml.id 
                             LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                             LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                             LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                             LEFT JOIN sort_name sn on ri.sort_name_id = sn.id
                             WHERE ri.status = 1
                             AND tir.id = %d
                             AND ri.toquv_departments_id = %d
                             GROUP BY tir.id";
        $sql = sprintf($sql,$id,$this->department);
        if($is_model){
            return Yii::$app->db->createCommand($sql)->queryOne();
        }
        $dataProvider = new SqlDataProvider([
            'sql' => $sql
        ]);
        return $dataProvider;
    }
    public function getMusteri(){
        $musteri = $this->query;
        return ArrayHelper::map($musteri,'musteri_id','musteri');
    }
    public function getFilter($from,$to){
        $filter = $this->query;
        if(is_array($to)){
            return ArrayHelper::map($filter,$from,function($m) use($to){
                $list = '';
                foreach ($to as $key => $n) {
                    $list .= $m[$n];
                    if($key+1!=count($to)){
                        $list .= " - ";
                    }
                }
                return $list;
            });
        }
        return ArrayHelper::map($filter,$from,$to);
    }
    public function getQuery($entity_type=null,$is_brak=null){
        $brak = (empty($is_brak))?" AND (sn.code != 'BRAK')":" AND (sn.code = 'BRAK')";
        $dept = ($this->department)?" AND (ri.toquv_departments_id = ".$this->department.")":"";
        $tip = ToquvRawMaterials::ENTITY_TYPE_MATO;
        $type = ($this->entity_type)?" AND (ri.entity_type = {$this->entity_type})" : " AND (ri.entity_type = {$tip})";
        $where = ($tip||$this->entity_type)?"{$type}":"";
        $query = "SELECT
                        COUNT(ri.id) count,
                        tir.id tir_id,
                        tpf.id pus_fine_id,
                        tpf.name pus_fine,
                        trm.id mato_id,
                        trm.name mato,
                        t.document_number doc_number,
                        t.id toquv_orders_id,
                        tro.id toquv_rm_order_id,
                        m.name musteri,
                        m.id musteri_id,
                        SUM(ri.quantity) summa,
                        tro.quantity quantity,
                        ##CONCAT(tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj) info,
                        tir.thread_length,
                        tir.finish_en,
                        tir.finish_gramaj,
                        MAX(ri.created_at) created_at,
                        toquv_departments_id tdi,
                        moi.id moi_id,
                        ml.name model,
                        m2.name order_musteri
                    FROM roll_info ri
                             ##LEFT JOIN toquv_kalite tk ON ri.toquv_kalite_id = tk.id
                             LEFT JOIN toquv_instruction_rm tir on ri.tir_id = tir.id
                             LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                             LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                             LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                             LEFT JOIN musteri m ON t.musteri_id = m.id
                             LEFT JOIN model_orders_items moi ON ri.moi_id = moi.id 
                             LEFT JOIN models_list ml on moi.models_list_id = ml.id 
                             LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                             LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                             LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                             LEFT JOIN sort_name sn on ri.sort_name_id = sn.id
                            WHERE ri.status = 1
                            %s
                            %s
                            %s";
        $query .= " GROUP BY tir.id";
        $query = sprintf($query,$where,$dept,$brak);
        return Yii::$app->db->createCommand($query)->queryAll();
    }
    /*public function search($params)
    {
        $query = RollInfo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'entity_id' => $this->entity_id,
            'entity_type' => $this->entity_type,
            'quantity' => $this->quantity,
            'unit_id' => $this->unit_id,
            'tir_id' => $this->tir_id,
            'moi_id' => $this->moi_id,
            'toquv_kalite_id' => $this->toquv_kalite_id,
            'toquv_departments_id' => $this->toquv_departments_id,
            'old_departments_id' => $this->old_departments_id,
            'sort_name_id' => $this->sort_name_id,
            'accept_date' => $this->accept_date,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }*/
    /*public function getDepartment(){
        switch ($this->slug){
            case 'mato_sklad':
                $this->department = self::getDept('TOQUV_MATO_SKLAD')['id'];
                $this->title = self::getDept('TOQUV_MATO_SKLAD')['name'];
                break;
        }
        return $this->department;
    }*/
    protected function getDept($token){
        return ToquvDepartments::findOne(['token'=>$token]);
    }
}
