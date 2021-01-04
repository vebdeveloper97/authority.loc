<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\WhDocument;
use yii\helpers\VarDumper;

/**
 * WhDocumentSearch represents the model behind the search form of `app\modules\base\models\WhDocument`.
 */
class WhDocumentSearch extends WhDocument
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_type', 'action', 'musteri_id', 'from_department', 'from_employee', 'to_department', 'to_employee', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
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
    public function search($params, $docType, $department_field)
    {
        $query = WhDocument::find()->orderBy(['created_at'=>SORT_DESC]);


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
            'document_type' => $docType,
            'action' => $this->action,
            'reg_date' => $this->reg_date,
            'musteri_id' => $this->musteri_id,
            'from_department' => $this->from_department,
            'from_employee' => $this->from_employee,
            'to_department' => $this->to_department,
            'to_employee' => $this->to_employee,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'musteri_responsible', $this->musteri_responsible])
            ->andFilterWhere(['like', 'add_info', $this->add_info])
            ->andFilterWhere(['in', $department_field, array_keys($this->getDepartments())]);

        return $dataProvider;
    }
}
