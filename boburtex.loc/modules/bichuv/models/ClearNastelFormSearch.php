<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\ClearNastelForm;

/**
 * ClearNastelFormSearch represents the model behind the search form of `app\modules\bichuv\models\ClearNastelForm`.
 */
class ClearNastelFormSearch extends ClearNastelForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'status', 'created_at', 'updated_at', 'type', 'nastel_no', 'musteri_id', 'bichuv_detail_type_id', 'size_collection_id', 'customer_id', 'nastel_user_id'], 'integer'],
            [['reg_date', 'doc_number', 'add_info', 'nastel_party', 'order_id'], 'safe'],
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
        $query = ClearNastelForm::find()->orderBy(['id'=>SORT_DESC]);

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
            'reg_date' => $this->reg_date,
            'created_by' => $this->created_by,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => $this->type,
            'nastel_no' => $this->nastel_no,
            'musteri_id' => $this->musteri_id,
            'bichuv_detail_type_id' => $this->bichuv_detail_type_id,
            'size_collection_id' => $this->size_collection_id,
            'customer_id' => $this->customer_id,
            'nastel_user_id' => $this->nastel_user_id,
        ]);

        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'add_info', $this->add_info])
            ->andFilterWhere(['like', 'nastel_party', $this->nastel_party]);

        return $dataProvider;
    }
}
