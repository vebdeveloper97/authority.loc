<?php

namespace app\modules\hr\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrEmploymentHistory;

/**
 * HrEmploymentHistorySearch represents the model behind the search form of `app\modules\hr\models\HrEmploymentHistory`.
 */
class HrEmploymentHistorySearch extends HrEmploymentHistory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'employee_id', 'position_id', 'from_department', 'to_department', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['reg_date', 'end_date'], 'safe'],
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
        $query = HrEmploymentHistory::find();

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
            'employee_id' => $this->employee_id,
            'position_id' => $this->position_id,
            'from_department' => $this->from_department,
            'to_department' => $this->to_department,
            'reg_date' => $this->reg_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);

        return $dataProvider;
    }
}
