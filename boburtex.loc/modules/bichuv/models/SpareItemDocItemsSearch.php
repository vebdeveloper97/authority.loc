<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\SpareItemDocItems;
use yii\helpers\VarDumper;

/**
 * SpareItemDocItemsSearch represents the model behind the search form of `app\modules\bichuv\models\SpareItemDocItems`.
 */
class SpareItemDocItemsSearch extends SpareItemDocItems
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'spare_item_doc_id', 'entity_id', 'from_area', 'to_area', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['quantity', 'price_sum', 'price_usd', 'summa', 'summa_usd'], 'number'],
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
     * @param int $id
     *
     * @return ActiveDataProvider
     */
    public function search($params, $id = null)
    {
        $query = SpareItemDocItems::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);


        if (!$this->validate()) {
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'spare_item_doc_id' => $id,
            'entity_id' => $this->entity_id,
            'quantity' => $this->quantity,
            'price_sum' => $this->price_sum,
            'price_usd' => $this->price_usd,
            'from_area' => $this->from_area,
            'to_area' => $this->to_area,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'summa' => $this->summa,
            'summa_usd' => $this->summa_usd,
        ]);
        return $dataProvider;
    }
}
