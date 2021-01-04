<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\ModelsRawMaterials;

/**
 * ModelsRawMaterialsSearch represents the model behind the search form of `app\modules\base\models\ModelsRawMaterials`.
 */
class ModelsRawMaterialsSearch extends ModelsRawMaterials
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'model_list_id', 'rm_id', 'is_main', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info'], 'safe'],
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
        $query = ModelsRawMaterials::find();

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
            'model_list_id' => $this->model_list_id,
            'rm_id' => $this->rm_id,
            'is_main' => $this->is_main,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }
}
