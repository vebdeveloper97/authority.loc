<?php

namespace app\modules\mechanical\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\mechanical\models\SpareInspection;

/**
 * SpareInspectionSearch represents the model behind the search form of `app\modules\mechanical\models\SpareInspection`.
 */
class SpareInspectionSearch extends SpareInspection
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'spare_passport_item_id','sirhe_id','control_type', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['reg_date', 'add_info'], 'safe'],
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
    public function search($params, $control_type = null, $id = null)
    {
        $query = SpareInspection::find()->orderBy(['id' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'spare_passport_item_id' => $this->spare_passport_item_id,
            'sirhe_id' => $id,
            'control_type' => $control_type,
            'reg_date' => $this->reg_date,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }
}
