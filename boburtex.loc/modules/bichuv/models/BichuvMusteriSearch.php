<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvMusteri;

/**
 * ToquvMusteriSearch represents the model behind the search form of `app\modules\toquv\models\ToquvMusteri`.
 */
class BichuvMusteriSearch extends BichuvMusteri
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'musteri_type_id', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name', 'add_info', 'tel', 'address', 'director'], 'safe'],
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
        $query = BichuvMusteri::find();

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
            'musteri_type_id' => $this->musteri_type_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'add_info', $this->add_info])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'director', $this->director]);
        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 20;

        return $dataProvider;
    }
}
