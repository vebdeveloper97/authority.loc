<?php

namespace app\modules\hr\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrStaff;

/**
 * HrStaffSearch represents the model behind the search form of `app\modules\hr\models\HrStaff`.
 */
class HrStaffSearch extends HrStaff
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','quantity', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['department_id', 'position_id', 'position_type_id'],  'safe']
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
        $query = HrStaff::find()
            ->alias('hs');

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

        $query->joinWith('department');
        $query->joinWith('position');
        $query->joinWith(['positionType' => function ($q) {
            $q->alias('hpt')
                ->andFilterWhere(['hpt.id' => $this->position_type_id]);
        }]);


        // grid filtering conditions
        $query->andFilterWhere([
            'hs.id' => $this->id,
            'hs.quantity' => $this->quantity,
            'hs.status' => $this->status,
            'hs.created_at' => $this->created_at,
            'hs.updated_at' => $this->updated_at,
            'hs.created_by' => $this->created_by,
            'hs.updated_by' => $this->updated_by,
        ]);

        $query
            ->andFilterWhere(['like', 'hr_departments.name', $this->department_id])
            ->andFilterWhere(['like', 'hr_position.name', $this->position_id]);

        return $dataProvider;
    }
}
