<?php

namespace app\modules\bichuv\models;

use app\modules\toquv\models\ToquvDepartments;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvPrintAndPatternItemBalance;
use yii\data\SqlDataProvider;

/**
 * BichuvPrintAndPatternItemBalanceSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvPrintAndPatternItemBalance`.
 */
class BichuvPrintAndPatternItemBalanceSearch extends BichuvPrintAndPatternItemBalance
{
    public $reg_date;
    public $_fromDate;
    public $_toDate;
    public $_nastelParty;
    public $_documentNumber;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'entity_id', 'entity_type', 'size_id', 'doc_id', 'doc_type', 'department_id', 'from_department', 'to_department', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['party_no'], 'safe'],
            [['count', 'invalid_count', 'inventory'], 'number'],
        ];
    }

    public function formName()
    {
        return '';
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
        $query = BichuvPrintAndPatternItemBalance::find();

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
            'entity_id' => $this->entity_id,
            'entity_type' => $this->entity_type,
            'size_id' => $this->size_id,
            'count' => $this->count,
            'invalid_count' => $this->invalid_count,
            'inventory' => $this->inventory,
            'doc_id' => $this->doc_id,
            'doc_type' => $this->doc_type,
            'department_id' => $this->department_id,
            'from_department' => $this->from_department,
            'to_department' => $this->to_department,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'party_no', $this->party_no]);

        return $dataProvider;
    }


    public function searchPrintIn($params)
    {
        $docType = BichuvDoc::DOC_TYPE_ACCEPTED_FROM_BICHUV;
        $status = self::STATUS_SAVED;
        $department_id = array_keys(BichuvDoc::getDepartmentsBelongTo());
        $department_id = "(".join(',',$department_id).")";

        $subSql = "";
        if(!empty($params['reg_date'])){
            $subSql .=" AND bd.reg_date BETWEEN '{$params['_fromDate']}' AND '{$params['_toDate']}'";
            $this->reg_date = $params['reg_date'];
        }
        if(!empty($params['_nastelParty'])){
            $subSql .=" AND bsi.nastel_party LIKE '%{$params['_nastelParty']}%'";
            $this->_nastelParty = $params['_nastelParty'];
        }
        if(!empty($params['_documentNumber'])){
            $subSql .=" AND bd.doc_number LIKE '%{$params['_documentNumber']}%'";
            $this->_documentNumber = $params['_documentNumber'];
        }
        if(!empty($params['size_id'])){
            $joinSizeId = join(',',$params['size_id']);
            $subSql .= " AND bsi.size_id IN ({$joinSizeId})";
            $this->size_id = $params['size_id'];
        }
        $sql = "
            SELECT 
                bd.doc_number,
                bd.reg_date,
                GROUP_CONCAT(s.name SEPARATOR ',') as name,
                bsi.nastel_party as nastel_no,
                SUM(bsi.quantity) as quantity
            FROM bichuv_slice_items bsi 
            LEFT JOIN bichuv_doc bd 
                ON bsi.bichuv_doc_id = bd.id
            LEFT JOIN size s 
                ON bsi.size_id = s.id
            WHERE bd.document_type = {$docType}
                AND bd.status = {$status}
                AND bd.to_hr_department IN {$department_id}
                {$subSql}
                GROUP BY bsi.nastel_party
        ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql
        ]);
        return $dataProvider;
    }

    public function searchPrintTransfer($params)
    {
        $docType = BichuvDoc::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV;
        $status = self::STATUS_SAVED;
        $department_id = array_keys(BichuvDoc::getDepartmentsBelongTo());
        $department_id = "(".join(',',$department_id).")";
        $subSql = "";
        if(!empty($params['reg_date'])){
            $subSql .=" AND bd.reg_date BETWEEN '{$params['_fromDate']}' AND '{$params['_toDate']}'";
            $this->reg_date = $params['reg_date'];
        }
        if(!empty($params['_nastelParty'])){
            $subSql .=" AND bsi.nastel_party LIKE '%{$params['_nastelParty']}%'";
            $this->_nastelParty = $params['_nastelParty'];
        }
        if(!empty($params['_documentNumber'])){
            $subSql .=" AND bd.doc_number LIKE '%{$params['_documentNumber']}%'";
            $this->_documentNumber = $params['_documentNumber'];
        }
        if(!empty($params['size_id'])){
            $joinSizeId = join(',',$params['size_id']);
            $subSql .= " AND bsi.size_id IN ({$joinSizeId})";
            $this->size_id = $params['size_id'];
        }
        $sql = "
            SELECT 
                bd.doc_number,
                bd.reg_date,
                s.name,
                bsi.nastel_party as nastel_no,
                bsi.invalid_quantity,
                bsi.add_info,
                bsi.quantity
            FROM bichuv_slice_items bsi 
            LEFT JOIN bichuv_doc bd 
                ON bsi.bichuv_doc_id = bd.id
            LEFT JOIN size s 
                ON bsi.size_id = s.id
            WHERE bd.document_type = {$docType}
                AND bd.status = {$status}
                AND bd.from_hr_department = {$department_id}
                {$subSql}
        ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql
        ]);
        return $dataProvider;
    }

    public function searchPrintRemain($params){
        $department_id = array_keys(BichuvDoc::getDepartmentsBelongTo());

        $department_id = "(".join(',',$department_id).")";;
        $subSql = "";
        if(!empty($params['_nastelParty'])){
            $subSql .=" AND bpapib.party_no LIKE '%{$params['_nastelParty']}%'";
            $this->_nastelParty = $params['_nastelParty'];
        }
        if(!empty($params['_documentNumber'])){
            $subSql .=" AND bd.doc_number LIKE '%{$params['_documentNumber']}%'";
            $this->_documentNumber = $params['_documentNumber'];
        }
        if(!empty($params['size_id'])){
            $joinSizeId = join(',',$params['size_id']);
            $subSql .= " AND bpapib.size_id IN ({$joinSizeId})";
            $this->size_id = $params['size_id'];
        }
        $sql = "SELECT 
            bd.doc_number,
            bpapib.party_no as nastel_no,
            GROUP_CONCAT(s.name SEPARATOR ',') as name,
            SUM(bpapib.inventory) as quantity
            FROM bichuv_print_and_pattern_item_balance bpapib
            LEFT JOIN size s ON bpapib.size_id = s.id
            LEFT JOIN bichuv_doc bd ON bpapib.doc_id = bd.id
            WHERE bpapib.id IN (
                SELECT MAX(id) id FROM bichuv_print_and_pattern_item_balance bpapib2
                 WHERE bpapib2.hr_department_id = {$department_id}
                GROUP BY bpapib2.size_id
            )
            AND bpapib.hr_department_id = {$department_id}
            AND bpapib.inventory > 0
            {$subSql}
            ORDER BY bpapib.size_id
        ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql
        ]);
        return $dataProvider;
    }








}
