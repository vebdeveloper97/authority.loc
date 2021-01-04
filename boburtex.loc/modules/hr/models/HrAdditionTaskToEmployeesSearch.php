<?php

namespace app\modules\hr\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrAdditionTaskToEmployees;
use Yii;
/**
 * HrAdditionTaskToEmployeesSearch represents the model behind the search form of `app\modules\hr\models\HrAdditionTaskToEmployees`.
 */
class HrAdditionTaskToEmployeesSearch extends HrAdditionTaskToEmployees
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'hr_employee_id', 'rate', 'status', 'type', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['task', 'expire_date', 'remember_date','reg_date'], 'safe'],
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
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\db\Exception
     */
    public function search($params)
    {
        $sql = "
            SELECT MAX(id) as id FROM hr_addition_task_to_employees hatte
            GROUP BY hatte.hr_employee_id
        ";
        $subQuery = Yii::$app->db->createCommand($sql)->queryAll();
        $query = HrAdditionTaskToEmployees::find()->where(['in','id',$subQuery]);

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
            'rate' => $this->rate,
            'status' => $this->status,
            'expire_date' => $this->expire_date,
            'remember_date' => $this->remember_date,
            'reg_date' => $this->reg_date,
            'type' => $this->type,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'task', $this->task]);

        return $dataProvider;
    }
}
