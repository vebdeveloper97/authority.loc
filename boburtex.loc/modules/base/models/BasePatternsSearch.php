<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\BasePatterns;

/**
 * BasePatternsSearch represents the model behind the search form of `app\modules\base\models\BasePatterns`.
 */
class BasePatternsSearch extends BasePatterns
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'brend_id', 'musteri_id', 'model_type_id', 'pattern_type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['code', 'name'], 'safe'],
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
        $query = BasePatterns::find()->orderBy(['updated_at'=>SORT_DESC]);

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
            'brend_id' => $this->brend_id,
            'musteri_id' => $this->musteri_id,
            'model_type_id' => $this->model_type_id,
            'pattern_type' => $this->pattern_type,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function searchItems($params, $patternId, $variantId=null){

        $query = BasePatternItems::find();
        $model = new BasePatternItems();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $model->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $model->id,
            'base_pattern_id' => $patternId,
            'base_detail_list_id' => $model->base_detail_list_id,
            'bichuv_detail_type_id' => $model->bichuv_detail_type_id,
            'status' => $model->status,
            'created_by' => $model->created_by,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ])
        ->andFilterWhere(['base_patterns_variant_id' => $variantId]);

        return $dataProvider;
    }
}
