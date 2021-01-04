<?php

namespace app\modules\hr\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrEmployeeWorkPlace;

/**
 * HrEmployeeWorkPlaceSearch represents the model behind the search form of `app\modules\hr\models\HrEmployeeWorkPlace`.
 */
class HrEmployeeWorkPlaceSearch extends HrEmployeeWorkPlace
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'hr_employee_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['from', 'to', 'organization', 'position'], 'safe'],
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
        $query = HrEmployeeWorkPlace::find();

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
            'hr_employee_id' => $this->hr_employee_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'from', $this->from])
            ->andFilterWhere(['like', 'to', $this->to])
            ->andFilterWhere(['like', 'organization', $this->organization])
            ->andFilterWhere(['like', 'position', $this->position]);

        return $dataProvider;
    }
}
