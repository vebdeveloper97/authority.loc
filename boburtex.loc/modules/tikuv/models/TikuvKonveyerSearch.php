<?php

namespace app\modules\tikuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tikuv\models\TikuvKonveyer;

/**
 * TikuvKonveyerSearch represents the model behind the search form of `app\modules\tikuv\models\TikuvKonveyer`.
 */
class TikuvKonveyerSearch extends TikuvKonveyer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'number', 'users_id', 'status', 'created_by', 'created_at', 'updated_at', 'dept_id'], 'integer'],
            [['code', 'name', 'add_info'], 'safe'],
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
        $query = TikuvKonveyer::find();

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
            'number' => $this->number,
            'users_id' => $this->users_id,
            'dept_id' => $this->dept_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }
}
