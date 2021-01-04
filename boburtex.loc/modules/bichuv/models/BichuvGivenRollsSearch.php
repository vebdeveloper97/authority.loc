<?php

namespace app\modules\bichuv\models;

use app\modules\bichuv\models\BichuvGivenRolls;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BichuvGivenRollsSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvGivenRolls`.
 */
class BichuvGivenRollsSearch extends BichuvGivenRolls
{
    public $model_id;

    public $party_no;

    public $musteri_party_no;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','model_id', 'created_by', 'status', 'created_at', 'updated_at'], 'integer'],
            [['reg_date','party_no','musteri_party_no','doc_number', 'add_info', 'nastel_party'], 'safe'],
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
        $query = BichuvGivenRolls::find()->joinWith(['bichuvGivenRollItems'])->distinct();

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

        if(!empty($this->model_id)){
            $query->andFilterWhere(['bichuv_given_roll_items.model_id' => $this->model_id]);
        }

        if(!empty($this->party_no)){
            $query->andFilterWhere(['bichuv_given_roll_items.party_no' => $this->party_no]);
        }
        if(!empty($this->musteri_party_no)){
            $query->andFilterWhere(['bichuv_given_roll_items.musteri_party_no' => $this->musteri_party_no]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'reg_date' => $this->reg_date,
            'created_by' => $this->created_by,
            'nastel_party' => $this->nastel_party,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        $query->orderBy(['id' => SORT_DESC]);
        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 20;
        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchItems($params)
    {
        $query = BichuvGivenRollItems::find()->joinWith(['bichuvGivenRoll'])->distinct();

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
            'bichuv_given_rolls.id' => $this->id,
        ]);

        $query->orderBy(['id' => SORT_DESC]);
        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 20;
        return $dataProvider;
    }


}
