<?php

namespace app\modules\tikuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TikuvOutcomeProductsPackSearch represents the model behind the search form of `app\modules\tikuv\models\TikuvOutcomeProductsPack`.
 */
class TikuvOutcomeProductsPackSearch extends TikuvOutcomeProductsPack
{
    public $barcode_customer_id;
    public $model_no;
    public $model_order;
    public $color_code;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_by', 'created_at', 'updated_at','barcode_customer_id','from_musteri','musteri_id'], 'integer'],
            [['order_no','toquv_partiya', 'boyoq_partiya', 'nastel_no', 'musteri_id', 'add_info','model_no','model_order','color_code'], 'safe'],
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
     * @param int $type
     * @return ActiveDataProvider
     */
    public function search($params,$type = self::TYPE_TIKUV)
    {
        $query = TikuvOutcomeProductsPack::find()->alias('pack')->where(['pack.type'=>$type])->distinct();

        if($type==self::TYPE_USLUGA){
            $query->orFilterWhere(['pack.type'=>self::TYPE_FROM_MUSTERI]);
        }

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
        if(!empty($this->model_no)){
            $query->leftJoin('tikuv_outcome_products top','top.pack_id = pack.id');
            $query->andFilterWhere(['like','top.model_no',$this->model_no]);
        }
        if(!empty($this->model_order)){
            $query->leftJoin('model_orders mo','mo.id = pack.order_id');
            $query->andFilterWhere(['like','mo.doc_number',$this->model_order]);
        }
        if(!empty($this->color_code)){
            $query->leftJoin('tikuv_outcome_products top','top.pack_id = pack.id');
            $query->andFilterWhere(['like','top.color_code',$this->color_code]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_by' => $this->created_by,
            'musteri_id' => $this->musteri_id,
            'from_musteri' => $this->from_musteri,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if($this->status){
            if($this->status == TikuvOutcomeProductsPack::STATUS_ACCEPTED){
                $query->andFilterWhere(['status' => $this->status]);
            }else{
                $query->andFilterWhere(['!=','status',TikuvOutcomeProductsPack::STATUS_ACCEPTED]);
            }

        }

        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'musteri_id', $this->musteri_id])
            ->andFilterWhere(['like', 'add_info', $this->add_info])
            ->andFilterWhere(['like', 'nastel_no', $this->nastel_no])
            ->andFilterWhere(['like', 'toquv_partiya', $this->toquv_partiya])
            ->andFilterWhere(['like', 'boyoq_partiya', $this->boyoq_partiya]);

        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 20;
        return $dataProvider;
    }
}
