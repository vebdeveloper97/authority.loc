<?php

namespace app\modules\boyoq\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\boyoq\models\ColorPantone;

/**
 * ColorPantoneSearch represents the model behind the search form of `app\modules\boyoq\models\ColorPantone`.
 */
class ColorPantoneSearch extends ColorPantone
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'r', 'g', 'b', 'color_panton_type_id', 'color_id'], 'integer'],
            [['name', 'code', 'name_ru', 'name_uz', 'name_ml'], 'safe'],
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
        $query = ColorPantone::find();

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
        $query->andFilterWhere(['status' => 1]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'r' => $this->r,
            'g' => $this->g,
            'b' => $this->b,
            'color_panton_type_id' => $this->color_panton_type_id,
            'color_id' => $this->color_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'name_uz', $this->name_uz])
            ->andFilterWhere(['like', 'name_ml', $this->name_ml]);

        return $dataProvider;
    }
}
