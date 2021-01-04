<?php

namespace app\modules\toquv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvPricingDoc;

/**
 * ToquvPricingDocSearch represents the model behind the search form of `app\modules\toquv\models\ToquvPricingDoc`.
 */
class ToquvPricingDocSearch extends ToquvPricingDoc
{
    public $number_and_date;
    public $date_from;
    public $date_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'doc_type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['doc_number', 'reg_date', 'add_info', 'number_and_date'], 'safe'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
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
    public function search($params, $docType)
    {
        $query = ToquvPricingDoc::find();

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
            'doc_type' => $docType,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'add_info', $this->add_info])
                ->andFilterWhere(['>=', 'reg_date', $this->date_from ? $this->date_from . ' 00:00:00' : null])
                ->andFilterWhere(['<=', 'reg_date', $this->date_to ? $this->date_to . ' 23:59:59' : null]);
        if(!empty($this->number_and_date)){
            $query->andFilterWhere(['or',
                ['like','doc_number',$this->number_and_date],
                ['like','reg_date', date('Y-m-d', strtotime($this->number_and_date))]
            ]);
        }

        return $dataProvider;
    }
}
