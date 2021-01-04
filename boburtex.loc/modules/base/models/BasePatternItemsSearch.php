<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\BasePatternItems;

/**
 * BasePatternItemsSearch represents the model behind the search form of `app\modules\base\models\BasePatternItems`.
 */
class BasePatternItemsSearch extends BasePatternItems
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bichuv_detail_type_id', 'base_detail_list_id', 'base_pattern_id', 'pattern_item_type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
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
        $query = BasePatternItems::find();

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
            'bichuv_detail_type_id' => $this->bichuv_detail_type_id,
            'base_detail_list_id' => $this->base_detail_list_id,
            'base_pattern_id' => $this->base_pattern_id,
            'pattern_item_type' => $this->pattern_item_type,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
