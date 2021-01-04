<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvTablesEmployees;

/**
 * BichuvTablesEmployeesSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvTablesEmployees`.
 */
class BichuvTablesEmployeesSearch extends BichuvTablesEmployees
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bichuv_table_id', 'hr_employee_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['from_date', 'end_date', 'add_info'], 'safe'],
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
        $query = BichuvTablesEmployees::find()->where(['status' => self::STATUS_ACTIVE])->groupBy('hr_employee_id')->orderBy(['id' => SORT_DESC]);

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
            'bichuv_table_id' => $this->bichuv_table_id,
            'hr_employee_id' => $this->hr_employee_id,
            'from_date' => $this->from_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }
}
