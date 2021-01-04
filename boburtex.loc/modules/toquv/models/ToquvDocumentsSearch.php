<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ToquvDocumentsSearch represents the model behind the search form of `app\modules\toquv\models\ToquvDocuments`.
 */
class ToquvDocumentsSearch extends ToquvDocuments
{
    public $number_and_date;

    public $entity_type;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_type', 'action', 'musteri_id', 'from_department', 'from_employee', 'to_department', 'to_employee', 'from_musteri', 'to_musteri', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['doc_number', 'reg_date', 'musteri_responsible','number_and_date'], 'safe'],
            ['add_info','string'],
            ['party','string']
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
     * @param int $docType
     * @param int $isOwn
     * @param int $entityType
     *
     * @return ActiveDataProvider
     */
    public function search($params, $docType, $isOwn  = 1, $entityType = 1)
    {
        $query = ToquvDocuments::find()->alias('td')->leftJoin('toquv_document_items tdi','td.id=tdi.toquv_document_id')->distinct();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if(!empty($this->entity_type)){
            $entityType = $this->entity_type;
        }
        $query->andFilterWhere(['is_own' => $isOwn]);
        $query->andFilterWhere(['tdi.entity_type' => $entityType]);
        $slug = Yii::$app->request->get('slug');
        if($slug === 'kochirish_mato' || $slug === 'chiqim_mato' || $slug === 'kirim_mato' || $slug === 'kochirish_aksessuar' || $slug === 'chiqim_aksessuar' || $slug === 'kirim_aksessuar'){
            $query->andFilterWhere(['td.entity_type' => $entityType]);
        }
        if(!empty($this->add_info)){
            $query->andFilterWhere(['like','td.add_info',$this->add_info]);
        }
        switch ($docType){
            case 1:
                // grid filtering conditions
                $query->andFilterWhere([
                    'trim(id)' => $this->id,
                    'action' => $this->action,
                    'document_type' => 1,
                    'musteri_id' => $this->musteri_id,
                    'from_department' => $this->from_department,
                    'from_employee' => $this->from_employee,
                    'to_employee' => $this->to_employee,
                    'status' => $this->status,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                    'created_by' => $this->created_by,
                ]);
                $ids = $this->getUserDeptId();
                $query->andFilterWhere(['in', 'to_department', $ids]);
                if(!empty($this->number_and_date)){
                    $query->andFilterWhere(['or',
                        ['like','doc_number',$this->number_and_date],
                        ['like','reg_date', date('Y-m-d', strtotime($this->number_and_date))]
                    ]);
                }
                break;

            case 2:
                // grid filtering conditions
                $query->andFilterWhere([
                    'id' => $this->id,
                    'document_type' => $docType,
                    'musteri_id' => $this->musteri_id,
                    'from_employee' => $this->from_employee,
                    'to_department' => $this->to_department,
                    'to_employee' => $this->to_employee,
                    'status' => $this->status,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                    'created_by' => $this->created_by,
                ]);
                $query->andFilterWhere(['in', 'action', [1,2,11,12]]);
                $ids = $this->getUserDeptId();
                $query->andFilterWhere(['in', 'from_department', $ids]);
                if(!empty($this->number_and_date)){
                    $query->andFilterWhere(['or',
                        ['like','doc_number',$this->number_and_date],
                        ['like','reg_date', date('Y-m-d', strtotime($this->number_and_date))]
                    ]);
                }
                break;

            case 5:
                $query->andFilterWhere([
                    'id' => $this->id,
                    'document_type' => $docType,
                    'musteri_id' => $this->musteri_id,
                    'from_employee' => $this->from_employee,
                    'to_department' => $this->to_department,
                    'to_employee' => $this->to_employee,
                    'status' => $this->status,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                    'created_by' => $this->created_by,
                ]);
                $query->andFilterWhere(['in', 'action', [1,2,11,12]]);
                $ids = $this->getUserDeptId();
                $query->andFilterWhere(['in', 'from_department', $ids]);
                if(!empty($this->number_and_date)){
                    $query->andFilterWhere(['or',
                        ['like','doc_number',$this->number_and_date],
                        ['like','reg_date', date('Y-m-d', strtotime($this->number_and_date))]
                    ]);
                }
                break;
            case 6:
                // grid filtering conditions
                $query->andFilterWhere([
                    'id' => $this->id,
                    'document_type' => $docType,
                    'musteri_id' => $this->musteri_id,
                    'from_employee' => $this->from_employee,
                    'to_department' => $this->to_department,
                    'to_employee' => $this->to_employee,
                    'status' => $this->status,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                    'created_by' => $this->created_by,
                    'add_info' => $this->add_info
                ]);
                $query->andFilterWhere(['in', 'action', [1,2,11,12]]);
                $ids = $this->getUserDeptId();
                $query->andFilterWhere(['in', 'from_department', $ids]);
                if(!empty($this->number_and_date)){
                    $query->andFilterWhere(['or',
                        ['like','doc_number',$this->number_and_date],
                        ['like','reg_date', date('Y-m-d', strtotime($this->number_and_date))]
                    ]);
                }
                break;

            case 7:
                // grid filtering conditions
                $query->andFilterWhere([
                    'id' => $this->id,
                    'document_type' => 2,
                    'musteri_id' => $this->musteri_id,
                    'from_department' => $this->from_department,
                    'from_employee' => $this->from_employee,
                    'to_employee' => $this->to_employee,
                    'status' => $this->status,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                    'created_by' => $this->created_by,
                ]);
                if(empty($this->to_department)){
                    $ids = $this->getUserDeptId();
                    $query->andFilterWhere(['in', 'to_department', $ids]);
                }else{
                    $query->andFilterWhere(['to_department' => $this->to_department]);
                }
                $query->andFilterWhere(['or', ['action' => 2],['action' => 1]]);
                if(!empty($this->number_and_date)){
                    $query->andFilterWhere(['or',
                        ['like','doc_number',$this->number_and_date],
                        ['like','reg_date', date('Y-m-d', strtotime($this->number_and_date))]
                    ]);
                }
                break;

            case 8:
                // grid filtering conditions
                $query->andFilterWhere([
                    'id' => $this->id,
                    'document_type' => 8,
                    'musteri_id' => $this->musteri_id,
                    'from_department' => $this->from_department,
                    'from_employee' => $this->from_employee,
                    'to_department' => $this->to_department,
                    'to_employee' => $this->to_employee,
                    'status' => $this->status,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                    'created_by' => $this->created_by,
                ]);
                if(!empty($this->number_and_date)){
                    $query->andFilterWhere(['or',
                        ['like','doc_number',$this->number_and_date],
                        ['like','reg_date', date('Y-m-d', strtotime($this->number_and_date))]
                    ]);
                }
                $query->andFilterWhere(['like','add_info', $this->add_info]);
                break;
            case 9:
                // grid filtering conditions
                $query->andFilterWhere([
                    'id' => $this->id,
                    'document_type' => $docType,
                    'musteri_id' => $this->musteri_id,
                    'from_employee' => $this->from_employee,
                    'to_department' => $this->to_department,
                    'to_employee' => $this->to_employee,
                    'status' => $this->status,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                    'created_by' => $this->created_by,
                ]);
                $query->andFilterWhere(['in', 'action', [1,2,11,12]]);
                $ids = $this->getUserDeptId();
                $query->andFilterWhere(['in', 'from_department', $ids]);
                if(!empty($this->number_and_date)){
                    $query->andFilterWhere(['or',
                        ['like','doc_number',$this->number_and_date],
                        ['like','reg_date', date('Y-m-d', strtotime($this->number_and_date))]
                    ]);
                }
                if(!empty($this->to_musteri)){
                    $query->andFilterWhere([
                        'to_musteri' => $this->to_musteri
                    ]);
                }
                if(!empty($this->from_musteri)){
                    $query->andFilterWhere([
                        'from_musteri' => $this->from_musteri
                    ]);
                }
                break;
        }

        return $dataProvider;
    }
}
