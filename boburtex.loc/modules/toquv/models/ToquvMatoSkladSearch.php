<?php

namespace app\modules\toquv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvDocuments;

/**
 * ToquvMatoSkladSearch represents the model behind the search form of `app\modules\toquv\models\ToquvDocuments`.
 */
class ToquvMatoSkladSearch extends ToquvDocuments
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_type', 'action', 'musteri_id', 'from_department', 'from_employee', 'to_department', 'to_employee', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['doc_number', 'reg_date', 'musteri_responsible', 'add_info'], 'safe'],
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
        $query = ToquvDocuments::find()->alias('td')->leftJoin('toquv_document_items tdi','td.id=tdi.toquv_document_id')->distinct();;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['entity_type' => $this::ENTITY_TYPE_MATO]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'document_type' => $this::DOC_TYPE_MOVING,
            'action' => $this->action,
            'reg_date' => $this->reg_date,
            'musteri_id' => $this->musteri_id,
            'from_department' => $this->from_department,
            'from_employee' => $this->from_employee,
            'to_employee' => $this->to_employee,
            'td.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);
        $ids = $this->getUserDeptId();
        $query->andFilterWhere(['in', 'to_department', $ids]);
        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'musteri_responsible', $this->musteri_responsible])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }
}
