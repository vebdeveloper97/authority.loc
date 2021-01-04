<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\ModelRelProduction;

/**
 * ModelChangePriceSearch represents the model behind the search form of `app\modules\bichuv\models\ModelRelProduction`.
 */
class ModelChangePriceSearch extends ModelRelProduction
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status', 'created_by', 'created_at', 'updated_at', 'order_id', 'order_item_id', 'pb_id', 'is_accepted'], 'integer'],
            [['price'], 'number'],
            [['bichuv_given_roll_id','models_list_id', 'model_variation_id'],'safe']
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
        $query = ModelRelProduction::find()
            ->leftJoin('bichuv_given_rolls','bichuv_given_rolls.id = model_rel_production.bichuv_given_roll_id')
            ->leftJoin('models_list','models_list.id = model_rel_production.models_list_id')
            ->leftJoin('models_variations','models_variations.id = model_rel_production.model_variation_id')
            ->leftJoin('model_orders_items','model_orders_items.id = model_rel_production.order_item_id')
            ->leftJoin('model_orders','model_orders.id = model_rel_production.order_id')
            ->leftJoin('color_pantone','color_pantone.id = models_variations.color_pantone_id')
            ->distinct();

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
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order_id' => $this->order_id,
            'order_item_id' => $this->order_item_id,
            'price' => $this->price,
            'pb_id' => $this->pb_id,
            'is_accepted' => $this->is_accepted,
            'bichuv_given_rolls.status' => 3
        ]);
        $query->andFilterWhere(['like','bichuv_given_rolls.nastel_party',$this->bichuv_given_roll_id]);
        $query->andFilterWhere(['like','models_list.article',$this->models_list_id]);
        $query->andFilterWhere(['like','color_pantone.code', $this->model_variation_id]);
        $query->orderBy(['model_rel_production.id' => SORT_DESC]);
        $query->groupBy(['models_variations.id','models_list.id','model_orders_items.id','model_orders.id']);
        return $dataProvider;
    }
}
