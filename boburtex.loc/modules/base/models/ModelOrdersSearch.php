<?php

namespace app\modules\base\models;

use app\models\Constants;
use phpDocumentor\Reflection\Types\Null_;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\ModelOrders;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\VarDumper;

/**
 * ModelOrdersSearch represents the model behind the search form of `app\modules\base\models\ModelOrders`.
 */
class ModelOrdersSearch extends ModelOrders
{
    public $artikul;
    public $pantone;
    public $brend;
    public $model_no;
    public $hom_kg;
    public $rm_id;
    public $rm_name;
    public $rm_type_id;
    public $rang1;
    public $rang2;
    public $sum_item_qty;
    public $color_name;
    public $per_page;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'musteri_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'brend', 'per_page'], 'integer'],
            [['doc_number', 'reg_date', 'add_info', 'artikul', 'pantone', 'hom_kg', 'rm_id', 'rang1', 'rang2','sum_item_qty','color_name'], 'safe'],
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

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'per_page' => Yii::t('app', "Ro'yhat miqdori")
        ]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$token=false)
    {
        $query = ModelOrders::find()->orderBy(['id'=>SORT_DESC]);

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
        $planed = ModelOrders::STATUS_PLANNED;
        $saved = ModelOrders::STATUS_SAVED;
        $active = ModelOrders::STATUS_ACTIVE;
        if($token){
            $query->joinWith('modelOrdersItems');
            $query->andWhere("not model_orders.status <=> {$active}");
            $query->andWhere("model_orders.status <=> {$saved}");
            $query->orWhere("model_orders.status <=> {$planed}");
            $query->andWhere("not model_orders_items.model_var_id <=> null");
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'model_orders.musteri_id' => $this->musteri_id,
            'model_orders.reg_date' => $this->reg_date,
            'model_orders.status' => $this->status,
            'model_orders.created_by' => $this->created_by,
            'model_orders.updated_by' => $this->updated_by,
            'model_orders.created_at' => $this->created_at,
            'model_orders.updated_at' => $this->updated_at,
        ]);
        $GO = ModelOrders::GENERAL_ORDER_TOKEN;
//        $query->andWhere(['!=', 'model_orders.token', $GO]);
        $query->andWhere([
            'model_orders.token' => null
        ]);
        /*if($user_id){
            if($this->created_by&&$user_id==1){
                $query->andFilterWhere([
                    'model_orders.created_by' => $this->created_by
                ]);
            }else {
                $query->andFilterWhere([
                    'model_orders.created_by' => $user_id
                ]);
            }
        }else{
            if($this->created_by){
                $query->andFilterWhere([
                    'model_orders.created_by' => $this->created_by
                ]);
            }
        }*/
        if($this->artikul) {
            if(!empty($this->pantone)){
                $query->joinWith(['modelOrdersItems' => function ($q) {
                    $q->joinWith(['modelsList','modelVar mv'])->leftJoin('color_pantone cp','cp.id = mv.color_pantone_id')->where(['AND',['like', 'models_list.article', $this->artikul],['like','cp.code',$this->pantone]]);
                }]);
            }else{
                $query->joinWith(['modelOrdersItems' => function ($q) {
                    $q->joinWith(['modelsList' => function ($q) {
                        $q->where(['like', 'models_list.article', $this->artikul]);
                    }]);
                }]);
            }
        }
        if($this->brend) {
            $query->joinWith(['modelOrdersItems' => function ($q) {
                $q->where(['model_orders_items.brend_id' => $this->brend]);
            }]);
        }
        $query->andFilterWhere(['like', 'model_orders.doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'model_orders.add_info', $this->add_info]);

        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 20;
        $this->storeSqlToSession($query->createCommand()->rawSql);

        return $dataProvider;
    }

    protected function storeSqlToSession(string $query) {
        Yii::$app->session->set('_query', $query);
    }
}
