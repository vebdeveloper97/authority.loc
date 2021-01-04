<?php

namespace app\modules\toquv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvMakine;

/**
 * ToquvMakineSearch represents the model behind the search form of `app\modules\toquv\models\ToquvMakine`.
 */
class ToquvMakineSearch extends ToquvMakine
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pus_fine_id','thread_length', 'finish_en', 'finish_gramaj','finish_gramaj_end', 'toquv_ne', 'toquv_thread', 'working_user_id', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name'], 'safe'],
            ['m_code','string'],
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
        $query = ToquvMakine::find()->where(['!=','id',0]);

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
            'pus_fine_id' => $this->pus_fine_id,
            'thread_length' => $this->thread_length,
            'finish_en' => $this->finish_en,
            'finish_gramaj' => $this->finish_gramaj,
            'finish_gramaj_end' => $this->finish_gramaj_end,
            'toquv_ne' => $this->toquv_ne,
            'toquv_thread' => $this->toquv_thread,
            'working_user_id' => $this->working_user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'm_code', $this->m_code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
