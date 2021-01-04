<?php

namespace app\modules\boyoq\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\boyoq\models\Color;

/**
 * ColorSearch represents the model behind the search form of `app\modules\boyoq\models\Color`.
 */
class ColorSearch extends Color
{
    public $ctone;
    public $ctype;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'color_tone', 'color_group', 'color_type', 'musteri_id', 'user_id'], 'integer'],
            [['name', 'pantone', 'color_id', 'color', 'reg_date','ctype','ctone'], 'safe'],
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
        $query = Color::find()
            ->leftJoin('color_tone','color.color_tone = color_tone.id')
            ->leftJoin('color_type','color.color_type = color_type.id');

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
            'color_tone' => $this->color_tone,
            'color_group' => $this->color_group,
            'color_type' => $this->color_type,
            'musteri_id' => $this->musteri_id,
            'reg_date' => $this->reg_date,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'color.name', $this->name])
            ->andFilterWhere(['like', 'pantone', $this->pantone])
            ->andFilterWhere(['like', 'color_id', $this->color_id])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'color_tone.name', $this->ctone])
            ->andFilterWhere(['like', 'color_type.name', $this->ctype]);

        return $dataProvider;
    }
}
