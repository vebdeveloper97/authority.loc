<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\BaseNormStandart;

/**
 * BaseNormStandartSearch represents the model behind the search form of `app\modules\base\models\BaseNormStandart`.
 */
class BaseNormStandartSearch extends BaseNormStandart
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'base_standart_id', 'sort_id', 'mobile_process_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
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
        $query = BaseNormStandart::find();

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
            'base_standart_id' => $this->base_standart_id,
            'sort_id' => $this->sort_id,
            'mobile_process_id' => $this->mobile_process_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
