<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\ModelMiniPostal;

/**
 * ModelMiniPostalSearch represents the model behind the search form of `app\modules\base\models\ModelMiniPostal`.
 */
class ModelMiniPostalSearch extends ModelMiniPostal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'models_list_id', 'users_id', 'type', 'count_items', 'total_patterns', 'total_patterns_loid', 'size_collection_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'safe'],
            [['eni', 'uzunligi', 'samaradorlik', 'specific_weight', 'total_weight', 'used_weight', 'lossed_weight', 'cost_surface', 'cost_weight', 'loss_surface', 'loss_weight', 'spent_surface', 'spent_weight'], 'number'],
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
        $query = ModelMiniPostal::find();

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
            'models_list_id' => $this->models_list_id,
            'users_id' => $this->users_id,
            'eni' => $this->eni,
            'uzunligi' => $this->uzunligi,
            'samaradorlik' => $this->samaradorlik,
            'type' => $this->type,
            'count_items' => $this->count_items,
            'total_patterns' => $this->total_patterns,
            'total_patterns_loid' => $this->total_patterns_loid,
            'specific_weight' => $this->specific_weight,
            'total_weight' => $this->total_weight,
            'used_weight' => $this->used_weight,
            'lossed_weight' => $this->lossed_weight,
            'size_collection_id' => $this->size_collection_id,
            'cost_surface' => $this->cost_surface,
            'cost_weight' => $this->cost_weight,
            'loss_surface' => $this->loss_surface,
            'loss_weight' => $this->loss_weight,
            'spent_surface' => $this->spent_surface,
            'spent_weight' => $this->spent_weight,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
