<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ToquvDocumentItemsSearch represents the model behind the search form of `app\modules\toquv\models\ToquvDocumentItems`.
 */
class ToquvDocumentItemsSearch extends ToquvDocumentItems
{
    public $number_and_date;

    public $fact;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'toquv_document_id', 'entity_id', 'entity_type', 'is_own', 'package_type', 'package_qty', 'status', 'created_at', 'updated_at', 'created_by', 'unit_id'], 'integer'],
            [['quantity', 'fact', 'price_sum', 'price_usd', 'current_usd', 'document_qty'], 'number'],
            [['lot','number_and_date'], 'safe'],
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
        $query = ToquvDocumentItems::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
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
            'toquv_document_id' => $id,
            'entity_id' => $this->entity_id,
            'entity_type' => $this->entity_type,
            'quantity' => $this->quantity,
            'current_usd' => $this->current_usd,
            'is_own' => $this->is_own,
            'package_type' => $this->package_type,
            'package_qty' => $this->package_qty,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'unit_id' => $this->unit_id,
            'document_qty' => $this->document_qty,
            'fact' => $this->fact
        ]);
        $query->andFilterWhere(['like', 'lot', $this->lot]);
        $query->andFilterWhere(['or',['price_sum' => $this->price_sum],['price_usd' => $this->price_usd]]);

        return $dataProvider;
    }
}
