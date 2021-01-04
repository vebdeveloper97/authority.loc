<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\Goods;

/**
 * GoodsSearch represents the model behind the search form of `app\modules\base\models\Goods`.
 */
class GoodsSearch extends Goods
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'barcode', 'type', 'model_id', 'size_type', 'size', 'color', 'category', 'sub_category', 'model_type', 'season', 'status'], 'integer'],
            [['model_no', 'name', 'old_name'], 'safe'],
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
        $query = Goods::find();

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
            'barcode' => $this->barcode,
            'type' => $this->type,
            'model_id' => $this->model_id,
            'size_type' => $this->size_type,
            'size' => $this->size,
            'color' => $this->color,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'model_type' => $this->model_type,
            'season' => $this->season,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'model_no', $this->model_no])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'old_name', $this->old_name]);

        return $dataProvider;
    }
}
