<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\WhDocumentItems;

/**
 * WhDocumentItemsSearch represents the model behind the search form of `app\modules\base\models\WhDocumentItems`.
 */
class WhDocumentItemsSearch extends WhDocumentItems
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'wh_document_id', 'entity_id', 'entity_type', 'dep_section', 'dep_area', 'incoming_pb_id', 'wh_pb_id', 'package_type', 'status'], 'integer'],
            [['lot', 'add_info'], 'safe'],
            [['document_qty', 'quantity', 'incoming_price', 'wh_price', 'package_qty'], 'number'],
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
        $query = WhDocumentItems::find();

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
            'wh_document_id' => $id,
            'entity_id' => $this->entity_id,
            'entity_type' => $this->entity_type,
            'document_qty' => $this->document_qty,
            'quantity' => $this->quantity,
            'dep_section' => $this->dep_section,
            'dep_area' => $this->dep_area,
            'incoming_price' => $this->incoming_price,
            'incoming_pb_id' => $this->incoming_pb_id,
            'wh_price' => $this->wh_price,
            'wh_pb_id' => $this->wh_pb_id,
            'package_type' => $this->package_type,
            'package_qty' => $this->package_qty,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'lot', $this->lot])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }
}
