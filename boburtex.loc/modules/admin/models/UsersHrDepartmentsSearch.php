<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\UsersHrDepartments;

/**
 * UsersHrDepartmentsSearch represents the model behind the search form of `app\modules\admin\models\UsersHrDepartments`.
 */
class UsersHrDepartmentsSearch extends UsersHrDepartments
{
    public $user_fio;
    public $username;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'hr_departments_id', 'type', 'status', 'created_by', 'created_at', 'updated_at', 'departments_2'], 'integer'],
            ['user_fio', 'string'],
            ['username', 'string']
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
        $query = UsersHrDepartments::find()
            ->alias('uhd')
            ->joinWith('user');

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
            'uhd.user_id' => $this->user_id,
            'uhd.hr_departments_id' => $this->hr_departments_id,
            'uhd.status' => $this->status,
            'uhd.created_by' => $this->created_by,
        ]);

        if ($this->hr_departments_id) {
            $query->andWhere([
                'uhd.hr_departments_id' => $this->hr_departments_id,
                'uhd.type' => self::OWN_DEPARTMENT_TYPE
            ]);
        }

        if ($this->departments_2) {
            $query->andWhere([
                'uhd.hr_departments_id' => $this->departments_2,
                'uhd.type' => self::FOREIGN_DEPARTMENT_TYPE
            ]);
        }

        $query->andFilterWhere(['like','users.username',$this->username ]);
        $query->groupBy(['user_id']);

        return $dataProvider;
    }
}
