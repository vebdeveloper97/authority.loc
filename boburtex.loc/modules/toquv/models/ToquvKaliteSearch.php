<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ToquvKaliteSearch represents the model behind the search form of `app\modules\toquv\models\ToquvKalite`.
 */
class ToquvKaliteSearch extends ToquvKalite
{
    public $date_from;
    public $date_to;
    public $name;
    public $type;
    public $m_code;
    public $pus_fine_id;
    public $thread_length;
    public $finish_en;
    public $finish_gramaj;
    public $finish_gramaj_end;
    public $toquv_ne;
    public $toquv_thread;
    public $working_user_id;
    public $musteri_id;
    public $model_musteri_id;
    public $model_id;
    public $orders_id;
    public $moi_id;
    public $per_page;
    public $model_code;
    public $order_type;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_by', 'updated_at', 'order_type'],'integer'],
            [['toquv_instructions_id', 'toquv_instruction_rm_id', 'toquv_rm_order_id', 'musteri_id', 'toquv_makine_id', 'user_id', 'sort_name_id', 'pus_fine_id', 'toquv_raw_materials_id', 'order', 'user_kalite_id'], 'safe'],
            ['per_page', 'integer', 'max' => 1000],
            [['model_code'], 'string'],
            [['quantity'], 'number'],
            [['date_to','date_from','code', 'smena', 'created_at'],'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'toquv_instructions_id' => Yii::t('app', 'Toquv Instructions ID'),
            'toquv_instruction_rm_id' => Yii::t('app', 'Toquv Instruction Rm ID'),
            'toquv_rm_order_id' => Yii::t('app', 'Toquv Rm Order ID'),
            'toquv_makine_id' => Yii::t('app', 'Toquv Makine ID'),
            'user_id' => Yii::t('app', 'To\'quvchi'),
            'quantity' => Yii::t('app', 'Quantity'),
            'sort_name_id' => Yii::t('app', 'Sort'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'type' => Yii::t('app', 'Type'),
            'toquv_raw_materials_id' => Yii::t('app', 'Toquv Raw Materials ID'),
            'order' => Yii::t('app', 'Order'),
            'code' => Yii::t('app', 'Code'),
            'smena' => Yii::t('app', 'Smena'),
            'count' => Yii::t('app', 'Count'),
            'roll' => Yii::t('app', 'Roll'),
            'user_kalite_id' => Yii::t('app', 'Tekshiruvchi'),
            'send_date' => Yii::t('app', "Omborga jo'natilgan sana"),
            'send_user_id' => Yii::t('app', "Omborga jo'natuvchi"),

            'musteri_id' => Yii::t('app', 'Buyurtmachi'),
            'model_musteri_id' => Yii::t('app', 'Model buyurtmachisi'),
            'model_id' => Yii::t('app', 'Model'),
            'orders_id' => Yii::t('app', 'Model buyurtma'),
            'moi_id' => Yii::t('app', 'Model buyurtma pozitsiyasi'),
            'sort_id' => Yii::t('app', 'Sort'),
            'per_page' => Yii::t('app', "Ro'yxat miqdori"),
            'pus_fine_id' => Yii::t('app', 'Pus/Fine'),
            'thread_length' => Yii::t('app', 'Thread Length'),
            'finish_en' => Yii::t('app', 'Finish En'),
            'finish_gramaj' => Yii::t('app', 'Finish Gramaj'),
            'model_code' => Yii::t('app', 'Model kodi'),
            'order_type' => Yii::t('app', 'Buyurtma turi'),
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
    public function search($params,$type=1)
    {
        $query = ToquvKalite::find()->alias('tk')->where(['tk.type'=>$type]);
        if(!Yii::$app->request->get('sort')){
            $query = $query->orderBy(['tk.id'=>SORT_DESC]);
        }
        $user = Yii::$app->user->identity;
        if($user->user_role==7){
            $query = $query->andWhere(['tk.user_kalite_id'=>$user->id]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);
        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 20;
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tk.quantity' => $this->quantity,
            'tk.status' => $this->status,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'order' => $this->order,
        ])->andFilterWhere(['>=', 'tk.created_at', $this->date_from ? strtotime($this->date_from) : null])
            ->andFilterWhere(['<=', 'tk.created_at', $this->date_to ? strtotime($this->date_to) : null]);
        if($this->toquv_makine_id) {
            if(is_array($this->toquv_makine_id)){
                $query->andFilterWhere(['IN','tk.toquv_makine_id', $this->toquv_makine_id]);
            }else{
                $query->andFilterWhere(['tk.toquv_makine_id' => $this->toquv_makine_id]);
            }
        }
        if($this->user_id) {
            if(is_array($this->user_id)){
                $query->andFilterWhere(['IN','tk.user_id', $this->user_id]);
            }else{
                $query->andFilterWhere(['tk.user_id' => $this->user_id]);
            }
        }
        if($this->user_kalite_id) {
            if(is_array($this->user_kalite_id)){
                $query->andFilterWhere(['IN','tk.user_kalite_id', $this->user_kalite_id]);
            }else{
                $query->andFilterWhere(['tk.user_kalite_id' => $this->user_kalite_id]);
            }
        }
        if($this->sort_name_id) {
            if(is_array($this->sort_name_id)){
                $query->andFilterWhere(['IN','tk.sort_name_id', $this->sort_name_id]);
            }else{
                $query->andFilterWhere(['tk.sort_name_id' => $this->sort_name_id]);
            }
        }
        if($this->code) {
            $count_array = explode(',', $this->code);
            if(count($count_array)>1){
                $query->andFilterWhere(['IN','tk.code', $count_array]);
            }else{
                $query->andFilterWhere(['like', 'tk.code', $this->code]);
            }
        }
        if($this->model_code) {
            $model_code_array = explode(',', $this->model_code);
            if(count($model_code_array)>1){
                $query->joinWith(['toquvRmOrder' => function ($q) use($model_code_array) {
                    $q->where(['IN', 'toquv_rm_order.model_code', $model_code_array]);
                }]);
            }else{
                $query->joinWith(['toquvRmOrder' => function ($q) {
                    $q->where('toquv_rm_order.model_code = ' . $this->model_code);
                }]);
            }
        }
        if($this->toquv_rm_order_id) {
            if($type==2){
                if(is_array($this->toquv_rm_order_id)){
                    $query->andFilterWhere(['IN','tk.toquv_rm_order_id' , $this->toquv_rm_order_id]);
                }else{
                    $query->andFilterWhere(['toquv_rm_order_id' => $this->toquv_rm_order_id]);
                }
            } else {
                if(is_array($this->toquv_rm_order_id)){
                    $query->joinWith(['toquvRmOrder' => function ($q) {
                        $q->where('toquv_rm_order.toquv_raw_materials_id IN (' . implode(',', $this->toquv_rm_order_id).')');
                    }]);
                }else{
                    $query->joinWith(['toquvRmOrder' => function ($q) {
                        $q->where('toquv_rm_order.toquv_raw_materials_id = ' . $this->toquv_rm_order_id);
                    }]);
                }
            }
        }
        if($this->order_type) {
            if($type==2){
                if(is_array($this->order_type)){
                    $query->andFilterWhere(['IN','tk.$this->order_type' , $this->order_type]);
                }else{
                    $query->andFilterWhere(['$this->order_type' => $this->order_type]);
                }
            } else {
                if(is_array($this->order_type)){
                    $query->joinWith(['toquvRmOrder' => function ($q) {
                        $q->joinWith(['toquvOrders' => function($a){
                            $a->where('toquv_orders.order_type IN (' . implode(',', $this->order_type).')');
                        }]);
                    }]);
                }else{
                    $query->joinWith(['toquvRmOrder' => function ($q) {
                        $q->joinWith(['toquvOrders' => function($a){
                            $a->where('toquv_orders.order_type = ' . $this->order_type);
                        }]);
                    }]);
                }
            }
        }
        if($this->toquv_instructions_id) {
            if(is_array($this->toquv_instructions_id)){
                $query->joinWith(['toquvRmOrder' => function ($q) {
                    $q->where('toquv_rm_order.toquv_orders_id IN (' . implode(',', $this->toquv_instructions_id).')');
                }]);
            }else{
                $query->joinWith(['toquvRmOrder' => function ($q) {
                    $q->where('toquv_rm_order.toquv_orders_id = ' . $this->toquv_instructions_id);
                }]);
            }
        }
        if($this->pus_fine_id) {
            if(is_array($this->pus_fine_id)){
                $query->joinWith(['toquvMakine' => function ($q) {
                    $q->where('toquv_makine.pus_fine_id IN (' . implode(',', $this->pus_fine_id).')');
                }]);
            }else{
                $query->joinWith(['toquvMakine' => function ($q) {
                    $q->where('toquv_makine.pus_fine_id = ' . $this->pus_fine_id);
                }]);
            }
        }
        if($this->musteri_id) {
            if(is_array($this->musteri_id)){
                $query->joinWith(['toquvRmOrder' => function ($q) {
                    $q->joinWith(['toquvOrders' => function ($q) {
                        $q->where('toquv_orders.musteri_id IN (' . implode(',', $this->musteri_id).')');
                    }]);
                }]);
            }else{
                $query->joinWith(['toquvRmOrder' => function ($q) {
                    $q->joinWith(['toquvOrders' => function ($q) {
                        $q->where('toquv_orders.musteri_id = ' . $this->musteri_id);
                    }]);
                }]);
            }
        }
        if($this->toquv_raw_materials_id) {
            if(is_array($this->toquv_raw_materials_id)){
                $query->joinWith(['toquvRawMaterials' => function ($q) {
                    $q->where('toquv_raw_materials.id IN (' . implode(',', $this->toquv_raw_materials_id).')');
                }]);
            }else{
                $query->joinWith(['toquvRawMaterials' => function ($q) {
                    $q->where('toquv_raw_materials.id = ' . $this->toquv_raw_materials_id);
                }]);
            }
        }
        $query->andFilterWhere(['like', 'tk.smena', $this->smena]);
        return $dataProvider;
    }
    public function makineSearch($params)
    {
        $query = ToquvMakine::find()->where(['>','id','0']);

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
            'm_code' => $this->m_code,
            'pus_fine_id' => $this->pus_fine_id,
            'thread_length' => $this->thread_length,
            'finish_en' => $this->finish_en,
            'finish_gramaj' => $this->finish_gramaj,
            'finish_gramaj_end' => $this->finish_gramaj_end,
            'toquv_ne' => $this->toquv_ne,
            'toquv_thread' => $this->toquv_thread,
            'working_user_id' => $this->working_user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }


    public function searchGroup($params,$type=1)
    {
        $query = ToquvKalite::find()->alias('tk')->select('tk.toquv_raw_materials_id,tk.toquv_instruction_rm_id,SUM(tk.quantity) quantity,COUNT(tk.id) count,tk.user_id,tk.toquv_makine_id,tk.sort_name_id,tk.toquv_rm_order_id')->where(['tk.type'=>$type])->groupBy('tk.user_id,tk.toquv_makine_id,tk.sort_name_id,tk.toquv_raw_materials_id,tk.toquv_instruction_rm_id,');
        if(!Yii::$app->request->get('sort')){
            $query = $query->orderBy(['tk.user_id'=>SORT_ASC]);
        }
        $user = Yii::$app->user->identity;
        if($user->user_role==7){
            $query = $query->andWhere(['tk.user_kalite_id'=>$user->id]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);
        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 10;
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tk.quantity' => $this->quantity,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'order' => $this->order,
        ])->andFilterWhere(['>=', 'tk.created_at', $this->date_from ? strtotime($this->date_from) : null])
            ->andFilterWhere(['<=', 'tk.created_at', $this->date_to ? strtotime($this->date_to) : null]);
        if($this->toquv_makine_id) {
            if(is_array($this->toquv_makine_id)){
                $query->andFilterWhere(['IN','tk.toquv_makine_id', $this->toquv_makine_id]);
            }else{
                $query->andFilterWhere(['tk.toquv_makine_id' => $this->toquv_makine_id]);
            }
        }
        if($this->user_id) {
            if(is_array($this->user_id)){
                $query->andFilterWhere(['IN','tk.user_id', $this->user_id]);
            }else{
                $query->andFilterWhere(['tk.user_id' => $this->user_id]);
            }
        }
        if($this->user_kalite_id) {
            if(is_array($this->user_kalite_id)){
                $query->andFilterWhere(['IN','tk.user_kalite_id', $this->user_kalite_id]);
            }else{
                $query->andFilterWhere(['tk.user_kalite_id' => $this->user_kalite_id]);
            }
        }
        if($this->sort_name_id) {
            if(is_array($this->sort_name_id)){
                $query->andFilterWhere(['IN','tk.sort_name_id', $this->sort_name_id]);
            }else{
                $query->andFilterWhere(['tk.sort_name_id' => $this->sort_name_id]);
            }
        }
        if($this->code) {
            $count_array = explode(',', $this->code);
            if(count($count_array)>1){
                $query->andFilterWhere(['IN','tk.code', $count_array]);
            }else{
                $query->andFilterWhere(['like', 'tk.code', $this->code]);
            }
        }
        if($this->model_code) {
            $model_code_array = explode(',', $this->model_code);
            if(count($model_code_array)>1){
                $query->joinWith(['toquvRmOrder' => function ($q) use($model_code_array) {
                    $q->where(['IN', 'toquv_rm_order.model_code', $model_code_array]);
                }]);
            }else{
                $query->joinWith(['toquvRmOrder' => function ($q) {
                    $q->where('toquv_rm_order.model_code = ' . $this->model_code);
                }]);
            }
        }
        if($this->toquv_rm_order_id) {
            if($type==2){
                if(is_array($this->toquv_rm_order_id)){
                    $query->andFilterWhere(['IN','tk.toquv_rm_order_id' , $this->toquv_rm_order_id]);
                }else{
                    $query->andFilterWhere(['toquv_rm_order_id' => $this->toquv_rm_order_id]);
                }
            } else {
                if(is_array($this->toquv_rm_order_id)){
                    $query->joinWith(['toquvRmOrder' => function ($q) {
                        $q->where('toquv_rm_order.toquv_raw_materials_id IN (' . implode(',', $this->toquv_rm_order_id).')');
                    }]);
                }else{
                    $query->joinWith(['toquvRmOrder' => function ($q) {
                        $q->where('toquv_rm_order.toquv_raw_materials_id = ' . $this->toquv_rm_order_id);
                    }]);
                }
            }
        }
        if($this->order_type) {
            if($type==2){
                if(is_array($this->order_type)){
                    $query->andFilterWhere(['IN','tk.$this->order_type' , $this->order_type]);
                }else{
                    $query->andFilterWhere(['$this->order_type' => $this->order_type]);
                }
            } else {
                if(is_array($this->order_type)){
                    $query->joinWith(['toquvRmOrder' => function ($q) {
                        $q->joinWith(['toquvOrders' => function($a){
                            $a->where('toquv_orders.order_type IN (' . implode(',', $this->order_type).')');
                        }]);
                    }]);
                }else{
                    $query->joinWith(['toquvRmOrder' => function ($q) {
                        $q->joinWith(['toquvOrders' => function($a){
                            $a->where('toquv_orders.order_type = ' . $this->order_type);
                        }]);
                    }]);
                }
            }
        }
        if($this->toquv_instructions_id) {
            if(is_array($this->toquv_instructions_id)){
                $query->joinWith(['toquvRmOrder' => function ($q) {
                    $q->where('toquv_rm_order.toquv_orders_id IN (' . implode(',', $this->toquv_instructions_id).')');
                }]);
            }else{
                $query->joinWith(['toquvRmOrder' => function ($q) {
                    $q->where('toquv_rm_order.toquv_orders_id = ' . $this->toquv_instructions_id);
                }]);
            }
        }
        if($this->pus_fine_id) {
            if(is_array($this->pus_fine_id)){
                $query->joinWith(['toquvMakine' => function ($q) {
                    $q->where('toquv_makine.pus_fine_id IN (' . implode(',', $this->pus_fine_id).')');
                }]);
            }else{
                $query->joinWith(['toquvMakine' => function ($q) {
                    $q->where('toquv_makine.pus_fine_id = ' . $this->pus_fine_id);
                }]);
            }
        }
        if($this->musteri_id) {
            if(is_array($this->musteri_id)){
                $query->joinWith(['toquvRmOrder' => function ($q) {
                    $q->joinWith(['toquvOrders' => function ($q) {
                        $q->where('toquv_orders.musteri_id IN (' . implode(',', $this->musteri_id).')');
                    }]);
                }]);
            }else{
                $query->joinWith(['toquvRmOrder' => function ($q) {
                    $q->joinWith(['toquvOrders' => function ($q) {
                        $q->where('toquv_orders.musteri_id = ' . $this->musteri_id);
                    }]);
                }]);
            }
        }
        if($this->toquv_raw_materials_id) {
            if(is_array($this->toquv_raw_materials_id)){
                $query->joinWith(['toquvRawMaterials' => function ($q) {
                    $q->where('toquv_raw_materials.id IN (' . implode(',', $this->toquv_raw_materials_id).')');
                }]);
            }else{
                $query->joinWith(['toquvRawMaterials' => function ($q) {
                    $q->where('toquv_raw_materials.id = ' . $this->toquv_raw_materials_id);
                }]);
            }
        }
        $query->andFilterWhere(['like', 'tk.smena', $this->smena]);
        return $dataProvider;
    }

    public function monthReport($params)
    {
        $query = ToquvKalite::find()
            ->alias('tk')
            ->select('tk.toquv_raw_materials_id, '.
                'SUM(tk.quantity) quantity, COUNT(tk.id) count, '.
                'tk.sort_name_id')
            ->groupBy('tk.sort_name_id, tk.toquv_raw_materials_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->pagination = false;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*echo strtotime(date('Y-m-d', strtotime($this->created_at)));
        echo "<br>";
        echo date('Y-m-d H:i:s', strtotime($this->created_at));
        echo "<br>";
        echo strtotime('next month', strtotime($this->created_at));
        echo "<br>";
        echo date('Y-m-d H:i:s', strtotime('next month', strtotime($this->created_at)));
        echo "<br>";
        echo $this->created_at;*/

        // grid filtering conditions
        $query->andFilterWhere(['>=', 'tk.created_at', $this->created_at ? strtotime($this->created_at) : null])
            ->andFilterWhere(['<', 'tk.created_at', $this->created_at ? strtotime('next month', strtotime($this->created_at)) : null]);

        return $dataProvider;
    }
}
