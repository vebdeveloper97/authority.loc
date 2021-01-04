<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

/**
 * ToquvInstructionsSearch represents the model behind the search form of `app\modules\toquv\models\ToquvInstructions`.
 *
 * @property array $instructionStatusList
 */
class ToquvInstructionsSearch extends ToquvInstructions
{
    public $musteri;
    public $doc_number_and_date;
    public $musteri_id;
    public $quantity;
    public $status;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'toquv_order_id','musteri_id', 'to_department', 'from_department', 'type', 'priority', 'notify', 'created_by', 'status', 'created_at', 'updated_at'], 'integer'],
            [['responsible_persons', 'reg_date', 'add_info','doc_number_and_date'], 'safe'],
            ['quantity','number']
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
        $query = ToquvInstructions::find();

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
            'toquv_order_id' => $this->toquv_order_id,
            'to_department' => $this->to_department,
            'from_department' => $this->from_department,
            'type' => $this->type,
            'priority' => $this->priority,
            'reg_date' => $this->reg_date,
            'notify' => $this->notify,
            'created_by' => $this->created_by,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'responsible_persons', $this->responsible_persons])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }

    public function modelSearch($params)
    {
        $query = 'SELECT
                        mo.doc_number,
                        mo.id,
                        COUNT(moi.id) count,
                        mo.status,
                        ti.status ti_status,
                        ti.id ti_id,
                        tor.id tor_id,
                        summa,
                        m.name musteri
                    FROM moi_rel_dept as rel          
                    LEFT JOIN model_orders_items as moi ON rel.model_orders_items_id = moi.id          
                    LEFT JOIN model_orders_planning mop ON moi.id = mop.model_orders_items_id          
                    LEFT JOIN toquv_raw_materials trm ON mop.toquv_raw_materials_id = trm.id         
                    LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                    LEFT JOIN toquv_instructions ti ON mo.id = ti.model_orders_id
                    LEFT JOIN toquv_orders tor ON mo.id = tor.model_orders_id
                    LEFT JOIN (SELECT model_orders_items_id,SUM(count) summa FROM model_orders_items_size mois LEFT JOIN size s ON mois.size_id = s.id GROUP BY mois.model_orders_items_id) mois ON moi.id = mois.model_orders_items_id
                    LEFT JOIN musteri m ON mo.musteri_id = m.id
                    WHERE `toquv_departments_id`=2 AND trm.name IS NOT NULL AND mo.status > 3';

        // add conditions that should always apply here
        $data = Yii::$app->request->get('ToquvInstructionsSearch');
        $musteri = $data['musteri'];
        $status = $data['status'];
        if($musteri){
            $query .= " AND m.id = {$musteri}";
        }
        if($status){
            $query .= " AND ti.status = {$status}";
        }

        $query .= " GROUP BY mo.id";
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

    /**
     * @param $params
     * @return SqlDataProvider
     */
    public function searchInstructionList($params,$type=1){

        $query = "select  ti.id as tid,
                          tir.id as tir_id,
                          t.reg_date,
                          ti.status, 
                          t.id as orderId, 
                          t.document_number,
                          m.id as mid,
                          m.name as mname,
                          SUM(tir.quantity) as quantity 
                    from toquv_orders t
                    left join toquv_instructions ti on t.id = ti.toquv_order_id
                    left join toquv_instruction_rm tir on ti.id = tir.toquv_instruction_id
                    left join musteri m on t.musteri_id = m.id
                    where ti.type = %d";

        $this->load($params);
        if($this->musteri_id){
            $query .= " AND m.id = {$this->musteri_id}";
        }
        $query .= " GROUP BY t.id ORDER BY t.id DESC";
        $query = sprintf($query,$type);
        $dataProvider = new SqlDataProvider([
            'sql' => $query,
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }

    public function getInstructionStatusList(){
        $res = [
            1 => Yii::t('app','Saqlanmagan'),
            3 => Yii::t('app','Saqlangan')
        ];
        return $res;
    }

    /**
     * @return array
     */
    public function getClosedInstructionList(){
        $res = [
            1 => Yii::t('app','Tugatilmagan'),
            2 => Yii::t('app','Tugatilgan')
        ];
        return $res;
    }
}
