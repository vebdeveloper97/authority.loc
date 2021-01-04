<?php

namespace app\modules\hr\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrServices;

/**
 * HrServicesSearch represents the model behind the search form of `app\modules\hr\models\HrServices`.
 */
class HrServicesSearch extends HrServices
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'hr_employee_id', 'type', 'pb_id', 'hr_country_id', 'region_id','district_id', 'region_type', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['start_date', 'end_date', 'reg_date', 'reason','add_info','initiator', 'other'], 'safe'],
            [['count'], 'number'],
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
    public function search($params,$serviceType)
    {
        $query = HrServices::find();

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
            'type' => $this->type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reg_date' => $this->reg_date,
            'count' => $this->count,
            'pb_id' => $this->pb_id,
            'hr_country_id' => $this->hr_country_id,
            'region_id' => $this->region_id,
            'district_id' => $this->district_id,
            'region_type' => $this->region_type,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'initiator', $this->initiator])
            ->andFilterWhere(['like', 'add_info', $this->initiator])
            ->andFilterWhere(['like', 'other', $this->other])
            ->andFilterWhere(['type' => $serviceType]);

        return $dataProvider;
    }
}
