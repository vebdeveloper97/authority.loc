<?php

namespace app\modules\toquv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvAksModel;

/**
 * ToquvAksModelSearch represents the model behind the search form of `app\modules\toquv\models\ToquvAksModel`.
 */
class ToquvAksModelSearch extends ToquvAksModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'trm_id', 'qavat', 'palasa', 'pb_id', 'musteri_id', 'color_pantone_id', 'color_boyoq_id', 'raw_material_type', 'color_type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'code', 'image'], 'safe'],
            [['width', 'height', 'price'], 'number'],
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
        $query = ToquvAksModel::find();

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
            'trm_id' => $this->trm_id,
            'width' => $this->width,
            'height' => $this->height,
            'qavat' => $this->qavat,
            'palasa' => $this->palasa,
            'price' => $this->price,
            'pb_id' => $this->pb_id,
            'musteri_id' => $this->musteri_id,
            'color_pantone_id' => $this->color_pantone_id,
            'color_boyoq_id' => $this->color_boyoq_id,
            'raw_material_type' => $this->raw_material_type,
            'color_type' => $this->color_type,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
