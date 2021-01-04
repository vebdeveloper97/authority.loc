<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvNastelDetails;

/**
 * BichuvProcessSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvNastelDetails`.
 */
class BichuvProcessSearch extends BichuvNastelDetails
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bichuv_doc_id', 'detail_type_id', 'count', 'status', 'created_by', 'created_at', 'updated_at', 'type', 'required_count', 'entity_id', 'doc_id', 'entity_type', 'model_id'], 'integer'],
            [['nastel_no'], 'safe'],
            [['weight', 'required_weight'], 'number'],
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
        $query = BichuvNastelDetails::find()->where(['detail_type_id'=>2]);

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
            'bichuv_doc_id' => $this->bichuv_doc_id,
            'detail_type_id' => $this->detail_type_id,
            'count' => $this->count,
            'weight' => $this->weight,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => $this->type,
            'required_count' => $this->required_count,
            'required_weight' => $this->required_weight,
            'entity_id' => $this->entity_id,
            'doc_id' => $this->doc_id,
            'entity_type' => $this->entity_type,
            'model_id' => $this->model_id,
        ]);

        $query->andFilterWhere(['like', 'nastel_no', $this->nastel_no]);

        return $dataProvider;
    }
}
