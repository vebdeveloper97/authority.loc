<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BichuvNastelDetailsSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvGivenRollItems`.
 */
class BichuvNastelDetailsSearch extends BichuvGivenRollItems
{
    public $nastel_no;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'entity_id', 'entity_type', 'bichuv_given_roll_id', 'type', 'created_by', 'status', 'created_at', 'updated_at', 'model_id', 'bichuv_detail_type_id'], 'integer'],
            [['quantity', 'roll_count', 'required_count'], 'number'],
            [['party_no', 'musteri_party_no'], 'safe'],
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
        $query = BichuvGivenRollItems::find()->where(['entity_type'=>1])->andWhere(['not in', 'bichuv_detail_type_id', 4])->orderBy(['id'=>SORT_DESC]);

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
            'entity_id' => $this->entity_id,
            'entity_type' => $this->entity_type,
            'bichuv_given_roll_id' => $this->bichuv_given_roll_id,
            'quantity' => $this->quantity,
            'type' => $this->type,
            'created_by' => $this->created_by,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'roll_count' => $this->roll_count,
            'model_id' => $this->model_id,
            'bichuv_detail_type_id' => $this->bichuv_detail_type_id,
            'required_count' => $this->required_count,
        ]);

        $query->andFilterWhere(['like', 'party_no', $this->party_no])
            ->andFilterWhere(['like', 'musteri_party_no', $this->musteri_party_no]);

        return $dataProvider;
    }
    public function searchNastel($params)
    {
        $query = BichuvGivenRollItems::find()->where(['entity_type'=>1])->andWhere(['not in', 'bichuv_detail_type_id', 4])->orderBy(['id'=>SORT_DESC]);

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
            'entity_id' => $this->entity_id,
            'entity_type' => $this->entity_type,
            'bichuv_given_roll_id' => $this->bichuv_given_roll_id,
            'quantity' => $this->quantity,
            'type' => $this->type,
            'created_by' => $this->created_by,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'roll_count' => $this->roll_count,
            'model_id' => $this->model_id,
            'bichuv_detail_type_id' => $this->bichuv_detail_type_id,
            'required_count' => $this->required_count,
        ]);

        $query->andFilterWhere(['like', 'party_no', $this->party_no])
            ->andFilterWhere(['like', 'musteri_party_no', $this->musteri_party_no]);

        return $dataProvider;
    }
}
