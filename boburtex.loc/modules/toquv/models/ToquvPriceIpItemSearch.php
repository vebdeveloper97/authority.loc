<?php

namespace app\modules\toquv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvPriceIpItem;

/**
 * ToquvPriceIpItemSearch represents the model behind the search form of `app\modules\toquv\models\ToquvPriceIpItem`.
 */
class ToquvPriceIpItemSearch extends ToquvPriceIpItem
{
    public $ne_name;
    public $thread_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'toquv_price_ip_id', 'toquv_ne_id', 'toquv_thread_id', 'pb_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['thread_name','ne_name'],'string']
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
    public function search($params, $id = null)
    {
        $query = ToquvPriceIpItem::find();

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
            'toquv_price_ip_id' => $id,
            'toquv_ne_id' => $this->toquv_ne_id,
            'toquv_thread_id' => $this->toquv_thread_id,
            'pb_id' => $this->pb_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ])->andFilterWhere(['like', 'price', $this->price]);
        $query->joinWith(['toquvNe' => function ($q) {
            $q->where('toquv_ne.name LIKE "%' . $this->ne_name . '%"');
        }]);
        $query->joinWith(['toquvThread' => function ($q) {
            $q->where('toquv_thread.name LIKE "%' . $this->thread_name . '%"');
        }]);
        return $dataProvider;
    }
}
