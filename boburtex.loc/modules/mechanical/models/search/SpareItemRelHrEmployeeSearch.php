<?php

namespace app\modules\mechanical\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\mechanical\models\SpareItemRelHrEmployee;

/**
 * SpareItemRelHrEmployeeSearch represents the model behind the search form of `app\modules\mechanical\models\SpareItemRelHrEmployee`.
 */
class SpareItemRelHrEmployeeSearch extends SpareItemRelHrEmployee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'spare_item_id', 'hr_employee_id', 'hr_department_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info','inv_number'], 'safe'],
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
        $query = SpareItemRelHrEmployee::find();

        $query->andFilterWhere(['status' => self::STATUS_ACTIVE]);

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
            'spare_item_id' => $this->spare_item_id,
            'hr_employee_id' => $this->hr_employee_id,
            'hr_department_id' => $this->hr_department_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'add_info', $this->add_info]);
        $query->andFilterWhere(['like', 'inv_number', $this->inv_number]);

        return $dataProvider;
    }
}
