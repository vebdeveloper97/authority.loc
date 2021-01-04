<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvNastelDetailItems;

/**
 * BichuvNastelItemsSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvNastelDetailItems`.
 */
class BichuvNastelItemsSearch extends BichuvNastelDetailItems
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'size_id', 'bichuv_nastel_detail_id', 'count', 'required_count', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['weight', 'required_weight'], 'number'],
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
     * @param int $id
     *
     * @return ActiveDataProvider
     */
    public function search($params, $id = null)
    {
        $query = BichuvNastelDetailItems::find();
        if(!empty($id)){
            $query = BichuvNastelDetailItems::find()->joinWith(['bichuvNastelDetail'])->andFilterWhere(['bichuv_nastel_details.bichuv_given_roll_id' => $id]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
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
            'size_id' => $this->size_id,
            'bichuv_nastel_detail_id' => $this->bichuv_nastel_detail_id,
            'count' => $this->count,
            'required_count' => $this->required_count,
            'weight' => $this->weight,
            'required_weight' => $this->required_weight,
            'type' => $this->type,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
