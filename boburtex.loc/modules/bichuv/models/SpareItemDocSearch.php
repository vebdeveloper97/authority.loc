<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\SpareItemDoc;

/**
 * SpareItemDocSearch represents the model behind the search form of `app\modules\bichuv\models\SpareItemDoc`.
 */
class SpareItemDocSearch extends SpareItemDoc
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_type', 'musteri_id', 'from_department', 'to_department', 'from_employee', 'to_employee', 'from_area', 'to_area', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['doc_number', 'reg_date'], 'safe'],
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
        $query = SpareItemDoc::find();

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
            'document_type' => $this->document_type,
            'reg_date' => $this->reg_date,
            'musteri_id' => $this->musteri_id,
            'from_department' => $this->from_department,
            'to_department' => $this->to_department,
            'from_employee' => $this->from_employee,
            'to_employee' => $this->to_employee,
            'from_area' => $this->from_area,
            'to_area' => $this->to_area,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'doc_number', $this->doc_number]);

        return $dataProvider;
    }
}
