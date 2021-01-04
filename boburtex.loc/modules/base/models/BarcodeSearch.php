<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\Goods;

/**
 * BarcodeSearch represents the model behind the search form of `app\modules\base\models\Goods`.
 */
class BarcodeSearch extends Goods
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'barcode', 'barcode1', 'barcode2', 'type', 'model_id', 'size_type', 'status'], 'integer'],
            [['model_no', 'name', 'size', 'color', 'category', 'sub_category', 'model_type', 'season', 'old_name'], 'safe'],
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
        $query = Goods::find();

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
            'barcode' => $this->barcode,
            'barcode1' => $this->barcode1,
            'barcode2' => $this->barcode2,
            'type' => $this->type,
            'model_id' => $this->model_id,
            'size_type' => $this->size_type,
            'status' => $this->status,
        ]);
        if($this->size) {
            $query->joinWith(['sizeName' => function ($q) {
                $q->where('size.name LIKE "%' . $this->size . '%"');
            }]);
        }
        if($this->color) {
            $query->joinWith(['colorPantone' => function ($q) {
                $q->where('color_pantone.code LIKE "%' . $this->color . '%"');
            }]);
        }
        if($this->category) {
            $query->joinWith(['model' => function ($q) {
                $q->joinWith(['type' => function($a){
                    $a->where('model_types.name LIKE "%' . $this->category . '%"');
                }]);
            }]);
        }
        if($this->sub_category) {
            $query->joinWith(['model' => function ($q) {
                $q->joinWith(['typeChild' => function($a){
                    $a->where('model_types.name LIKE "%' . $this->sub_category . '%"');
                }]);
            }]);
        }
        if($this->model_type) {
            $query->joinWith(['model' => function ($q) {
                $q->joinWith(['view' => function($a){
                    $a->where('model_view.name LIKE "%' . $this->model_type . '%"');
                }]);
            }]);
        }
        if($this->season) {
            $query->joinWith(['model' => function ($q) {
                $q->joinWith(['modelSeason' => function($a){
                    $a->where('model_season.name LIKE "%' . $this->season . '%"');
                }]);
            }]);
        }
        $query->andFilterWhere(['like', 'model_no', $this->model_no])
            ->andFilterWhere(['like', 'goods.name', $this->name])
            ->andFilterWhere(['like', 'old_name', $this->old_name]);

        return $dataProvider;
    }
}
