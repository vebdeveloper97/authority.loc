<?php

namespace app\modules\hr\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrDepartmentResponsiblePerson;

/**
 * HrDepartmentResponsiblePersonSearch represents the model behind the search form of `app\modules\hr\models\HrDepartmentResponsiblePerson`.
 */
class HrDepartmentResponsiblePersonSearch extends HrDepartmentResponsiblePerson
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'hr_department_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            ['hr_employee_id', 'each', 'rule' => ['integer']],
            [['start_date', 'end_date'], 'date', 'format' => 'php: d.m.Y'],
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
        $query = HrDepartmentResponsiblePerson::find();

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

        $query->joinWith(['hrEmployee' => function($q) {
            $q->select(['id', 'fish']);
        }]);

        $query->joinWith(['hrDepartment' => function($q) {
            $q->select(['id', 'name']);
        }]);

        $startDate = $endDate = null;
        if (!empty($this->start_date)) {
            $startDate = date('Y-m-d', strtotime($this->start_date));
        }
        if (!empty($this->end_date)) {
            $endDate = date('Y-m-d', strtotime($this->end_date));
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'hr_department_id' => $this->hr_department_id,
            'hr_employee_id' => $this->hr_employee_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->addOrderBy(['hr_department_id' => SORT_ASC, 'start_date' => SORT_DESC]);

        return $dataProvider;
    }
}
