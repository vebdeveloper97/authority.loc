<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvDetailTypes;

/**
 * BichuvDetailTypesSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvDetailTypes`.
 */
class BichuvDetailTypesSearch extends BichuvDetailTypes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'type_order', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'token'], 'safe'],
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
        $query = BichuvDetailTypes::find();

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
            'type' => $this->type,
            'type_order' => $this->type_order,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'token', $this->token]);

        return $dataProvider;
    }
}
