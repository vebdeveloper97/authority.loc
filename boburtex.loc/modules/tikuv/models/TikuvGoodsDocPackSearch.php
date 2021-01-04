<?php

namespace app\modules\tikuv\models;

use app\modules\base\models\BarcodeCustomers;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tikuv\models\TikuvGoodsDocPack;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

/**
 * TikuvGoodsDocPackSearch represents the model behind the search form of `app\modules\tikuv\models\TikuvGoodsDocPack`.
 */
class TikuvGoodsDocPackSearch extends TikuvGoodsDocPack
{
    public $from_date;
    public $to_date;
    public $order_doc_number;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'department_id', 'barcode_customer_id','from_department','order_id', 'order_item_id', 'created_by', 'status'], 'integer'],
            [['doc_number','reg_date','model_list_id','model_var_id','nastel_no','from_date','to_date','to_department','order_doc_number'], 'safe'],
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
        $floor = Yii::$app->request->get('floor', 4);
        $i = Yii::$app->request->get('i', 2);
        $reject = Yii::$app->request->get('reject', 0);
        $query = TikuvGoodsDocPack::find()
            ->leftJoin('models_list','tikuv_goods_doc_pack.model_list_id=models_list.id')
            ->leftJoin('model_rel_doc','tikuv_goods_doc_pack.nastel_no=model_rel_doc.nastel_no')
            ->leftJoin('color_pantone','color_pantone.id=model_rel_doc.color_id')
            ->leftJoin('model_orders','model_orders.id=model_rel_doc.order_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_ASC,'id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tikuv_goods_doc_pack.id' => $this->id,
            'tikuv_goods_doc_pack.is_incoming' => $params['i'],
            'tikuv_goods_doc_pack.department_id' => $this->department_id,
            'tikuv_goods_doc_pack.order_id' => $this->order_id,
            'tikuv_goods_doc_pack.order_item_id' => $this->order_item_id,

            'tikuv_goods_doc_pack.from_department' => $this->from_department,
            'tikuv_goods_doc_pack.created_by' => $this->created_by,
            'tikuv_goods_doc_pack.status' => $this->status,
        ]);
        if($floor == 4 && $i == 2){
            $query->andFilterWhere( ['tikuv_goods_doc_pack.to_department' => 'TMO']);
        }elseif($floor == 5 && $i == 2){
            $query->andFilterWhere( ['tikuv_goods_doc_pack.to_department' => 'SHOWROOM']);
        }else{
            $query->andFilterWhere( ['tikuv_goods_doc_pack.to_department' => $this->to_department]);
        }
        $query->andFilterWhere(['like', 'tikuv_goods_doc_pack.doc_number', $this->doc_number]);
        if(!empty($this->reg_date)){
            $query->andFilterWhere(['like', 'tikuv_goods_doc_pack.reg_date', date('Y-m-d', strtotime($this->reg_date))]);
        }
        if($reject == 1){
            $query->andFilterWhere( ['tikuv_goods_doc_pack.status' => self::STATUS_CENCALED]);
        }
        $query->andFilterWhere(['like', 'model_orders.doc_number', $this->order_doc_number]);
        $query->andFilterWhere(['like', 'tikuv_goods_doc_pack.nastel_no', $this->nastel_no]);
        $query->andFilterWhere(['like', 'models_list.article', $this->model_list_id]);
        $query->andFilterWhere(['like', 'color_pantone.code', $this->model_var_id]);
        return $dataProvider;
    }

    public function searchView($id){

        $sql = "select  tgd.id, 
                        tgd.tgdp_id,
                        tgd.quantity, 
                        tgd.accepted_quantity,
                        tgd.status,
                        g.id as gid, 
                        g.model_no,
                        s.name as sizeName, 
                        cp.code as colorName, 
                        g.name, 
                        g.type, 
                        tgd.weight,
                        sn.name as sort_name,
                        tgd.barcode
                from tikuv_goods_doc tgd 
                left join goods g on tgd.goods_id = g.id
                left join sort_name sn on tgd.sort_type_id = sn.id
                left join tikuv_goods_doc_pack tgdp on tgd.tgdp_id = tgdp.id
                left join size s on g.size = s.id
                left join color_pantone cp on g.color = cp.id
                WHERE tgdp.id = %d";
        $sql = sprintf($sql, $id);

        $sqlCount = "select count(tgd.id) from tikuv_goods_doc tgd 
                        left join goods g on tgd.goods_id = g.id
                        left join tikuv_goods_doc_pack tgdp on tgd.tgdp_id = tgdp.id
                        left join size s on g.size = s.id
                        left join color_pantone cp on g.color = cp.color_id
                        WHERE tgdp.id = %d";

        $sqlCount = sprintf($sqlCount, $id);

        $count = Yii::$app->db->createCommand($sqlCount)->queryScalar();

        $provider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'attributes' => [
                    'model_no',
                    'name'
                ],
            ]]);

        return $provider;
    }
    public function getBarcodeCustomerList()
    {
        $bc = BarcodeCustomers::find()->asArray()->all();
        return ArrayHelper::map($bc,'id','name');
    }
}
