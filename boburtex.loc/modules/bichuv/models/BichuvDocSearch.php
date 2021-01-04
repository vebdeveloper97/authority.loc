<?php

namespace app\modules\bichuv\models;

use app\models\Constants;
use app\modules\hr\models\HrDepartments;
use phpDocumentor\Reflection\Types\Self_;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * BichuvDocSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvDoc`.
 */
class BichuvDocSearch extends BichuvDoc
{
    public $doc_number_and_date;

    public $party;

    public $musteri_party;

    public $nastel_party;

    public $toquv_doc_id;

    public $model_id;

    public $type;

    public $model_and_variation;

    public $from_date;

    public $to_date;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'service_musteri_id','model_id', 'document_type', 'action', 'musteri_id', 'from_hr_department', 'from_employee', 'to_hr_department','to_department', 'to_employee', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['doc_number', 'bichuv_nastel_list_id','toquv_doc_id', 'nastel_party','from_date','to_date', 'party', 'musteri_party', 'reg_date', 'musteri_responsible', 'add_info', 'doc_number_and_date'], 'safe'],
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
     * @param $params
     * @param $docType
     * @param $entityType
     * @param $dataSqlProvider
     * @return ActiveDataProvider
     * @throws \yii\db\Exception
     */

    public function search2($params, $docType, $entityType = 1, $dataSqlProvider = false)
    {
        $query = BichuvDoc::find()->joinWith(['bichuvDocItems.bichuvSubDocItems', 'toquvDoc'])->distinct();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);
    }
    public function search($params, $docType, $entityType = 1, $dataSqlProvider = false)
    {
        $slug = Yii::$app->request->get('slug');
        switch ($slug){
            case self::DOC_TYPE_MOVING_SLICE_LABEL:
            case self::DOC_TYPE_MOVING_SLICE_TAY_LABEL:
            case self::DOC_TYPE_MOVING_SERVICE_LABEL:
            case self::DOC_TYPE_ACCEPTED_SlICE_FROM_BICHUV_LABEL:
            case self::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV_LABEL:
            case self::DOC_TYPE_ACCEPTED_SLICE_LABEL:
            case self::DOC_TYPE_ADJUSTMENT_SERVICE_LABEL:
            case self::DOC_TYPE_INCOMING_SLICE_LABEL:
                $query = BichuvDoc::find()
                    ->joinWith(['bichuvSliceItems', 'bichuvDocItems'])->distinct();
                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
                ]);
                $this->load($params);
                if (!$this->validate()) {
                    return $dataProvider;
                }
                if($slug ==  self::DOC_TYPE_ACCEPTED_SlICE_FROM_BICHUV_LABEL){

                    $departmentInfo = $this->getDepartmentsBelongTo(true);
                    if (!empty($departmentInfo)) {
                        $getAllIds = ArrayHelper::getColumn($departmentInfo, 'id');
                        $query->andFilterWhere(['in', 'to_hr_department', $getAllIds]);
                    }

                }
                elseif($slug == self::DOC_TYPE_MOVING_SLICE_TAY_LABEL){

                    $departmentInfo = $this->getDepartmentsBelongTo(true);
                    if (!empty($departmentInfo)) {
                        $getAllIds = ArrayHelper::getColumn($departmentInfo, 'id');
                        $query->andFilterWhere(['not in','to_hr_department',$getAllIds]);
                        $query->andFilterWhere(['to_hr_department' => HrDepartments::getDepartmentIdByToken(Constants::$TOKEN_TAYYORLOV)]);
                    }

                }
                elseif($slug == self::DOC_TYPE_MOVING_SLICE_LABEL){

                    $departmentInfo = $this->getDepartmentsBelongTo(true);
                    if (!empty($departmentInfo)) {
                        $getAllIds = ArrayHelper::getColumn($departmentInfo, 'id');
                        $query->andFilterWhere(['not in','to_hr_department',$getAllIds]);
                        $query->andFilterWhere(['!=','to_hr_department',HrDepartments::getDepartmentIdByToken(Constants::$TOKEN_TAYYORLOV)]);
                        $query->andFilterWhere(['from_hr_department' => HrDepartments::getDepartmentIdByToken(Constants::$TOKEN_BICHUV)]);
                    }

                }
                elseif($slug !== self::DOC_TYPE_INCOMING_SLICE_LABEL){
                    $departmentInfo = $this->getDepartmentsBelongTo(true);

                    if (!empty($departmentInfo)) {
                        $getAllIds = ArrayHelper::getColumn($departmentInfo, 'id');
                        $query->andFilterWhere(['in', 'from_hr_department', $getAllIds]);
                    }

                }
                else{
                    $this->type = 1;
                    $query->andFilterWhere(['OR',
                        ['bichuv_slice_items.type' => 1],
                        ['bichuv_doc_items.nastel_no' => $this->nastel_party]
                    ]);
                    $departmentInfo = $this->getDepartmentsBelongTo(true);
                    if (!empty($departmentInfo)) {
                        $getAllIds = ArrayHelper::getColumn($departmentInfo, 'id');
                        $query->andFilterWhere(['in', 'to_hr_department', $getAllIds]);
                    }
                }


                $query->andFilterWhere([
                    'document_type' => $docType,
                    'bichuv_doc.type' => $this->type,
                    /*'bichuv_slice_items.model_id' => $this->model_id,*/

                ]);
                $query->andFilterWhere(['OR',
                    ['bichuv_slice_items.nastel_party' => $this->nastel_party],
                    ['bichuv_doc_items.nastel_no' => $this->nastel_party]
                ]);
                if($slug == BichuvDoc::DOC_TYPE_MOVING_SERVICE_LABEL){
                    $query->andFilterWhere(['is_service' => 1]);
                }
                if (!empty($this->doc_number_and_date)) {
                    $query->andFilterWhere(['or',
                        ['like', 'doc_number', $this->doc_number_and_date],
                        ['like', 'reg_date', date('Y-m-d', strtotime($this->doc_number_and_date))]
                    ]);
                }
                if (!empty($this->party)) {
                    $query->innerJoin('bichuv_given_roll_items','bichuv_given_roll_items.bichuv_given_roll_id = bichuv_slice_items.bichuv_given_roll_id');
                    $query->andFilterWhere(['bichuv_given_roll_items.party_no' => $this->party]);
                }
                if (!empty($this->service_musteri_id)) {
                    $query->andFilterWhere(['service_musteri_id' => $this->service_musteri_id]);
                }
                break;
            default:
                if($docType == self::DOC_TYPE_PLAN_NASTEL){
                    $query = BichuvDoc::find()->joinWith(['bichuvNastelDetails'])->distinct();
                }else{
                    $query = BichuvDoc::find()->joinWith(['bichuvDocItems.bichuvSubDocItems', 'toquvDoc','bichuvNastelList'])->distinct();
                }

                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
                ]);
                if($docType == self::DOC_TYPE_PLAN_NASTEL){
                    $query->andFilterWhere(['bichuv_nastel_details.entity_type' => $entityType]);
                }else{
                    $query->andFilterWhere(['bichuv_doc_items.entity_type' => $entityType]);
                }
                if ($slug == self::DOC_TYPE_MOVING_MATO_LABEL || $slug == self::DOC_TYPE_REPAIR_MATO_LABEL || $slug == self::DOC_TYPE_ACCEPTED_MATO_LABEL) {
                    if (!empty($this->party)) {
                        $parties = explode(',', $this->party);
                        $query->andFilterWhere(['IN', 'bichuv_doc_items.party_no', $parties]);
                    }
                    if (!empty($this->musteri_party)) {
                        $mParties = explode(',', $this->musteri_party);
                        $query->andFilterWhere(['IN', 'bichuv_doc_items.musteri_party_no', $mParties]);
                    }
                } elseif ($slug == self::DOC_TYPE_NASTEL_PLAN_LABEL){

                }else {
                    if (!empty($this->party)) {
                        $parties = explode(',', $this->party);
                        $query->andFilterWhere(['IN', 'bichuv_sub_doc_items.party_no', $parties]);
                    }
                    if (!empty($this->nastel_party)) {
                        $query->andFilterWhere(['bichuv_doc_items.nastel_no' => $this->nastel_party]);
                    }
                    if (!empty($this->model_id)) {
                        $query->andFilterWhere(['bichuv_doc_items.model_id' => $this->model_id]);
                    }
                    if (!empty($this->musteri_party)) {
                        $mParties = explode(',', $this->musteri_party);
                        $query->andFilterWhere(['IN', 'bichuv_sub_doc_items.musteri_party_no', $mParties]);
                    }
                }

                if (!empty($this->toquv_doc_id)) {
                    $query->andFilterWhere(['toquv_documents.status' => $this->toquv_doc_id]);
                }
                $query->andFilterWhere(['like', 'bichuv_doc.doc_number', $this->doc_number]);

                switch ($docType) {
                    case 1:
                    case 11:
                        $departmentInfo = $this->getDepartmentsBelongTo(true);
                        if (!empty($departmentInfo)) {
                            $getAllIds = ArrayHelper::getColumn($departmentInfo, 'id');
                            $query->andFilterWhere(['in', 'bichuv_doc.to_hr_department', $getAllIds]);
                        }
                        break;
                    case 2:
                    case 5:
                        $departmentInfo = $this->getDepartmentsBelongTo(true);
                        if (!empty($departmentInfo)) {
                            $getAllIds = ArrayHelper::getColumn($departmentInfo, 'id');
                            $query->andFilterWhere(['in', 'bichuv_doc.from_hr_department', $getAllIds]);
                        }
                        break;
                    case 7:
                        /** Mato omboridan bichuv bo'limiga ko'chirilgan matolar documentini qaytaradi**/

                        $bichuvDepartmentId = HrDepartments::findOne(['token' => 'BICHUV'])['id'];
                        if(!empty($bichuvDepartmentId)){
                            $query->andFilterWhere(['in', 'bichuv_doc.to_hr_department', $bichuvDepartmentId]);
                            break;
                        }

                }
                if (!empty($this->add_info)) {
                    $query->andFilterWhere(['like', 'bichuv_doc.add_info', $this->add_info]);
                }

                if (!empty($this->doc_number_and_date)) {
                    $query->andFilterWhere(['or',
                        ['like', 'bichuv_doc.doc_number', $this->doc_number_and_date],
                        ['like', 'bichuv_doc.reg_date', date('Y-m-d', strtotime($this->doc_number_and_date))]
                    ]);
                }
                if ($docType == 7) {
                    $query->orderBy([
                        'bichuv_doc.status' => SORT_ASC,
                        'bichuv_doc.id' => SORT_DESC
                    ]);
                } else {
                    $query->orderBy(['bichuv_doc.id' => SORT_DESC]);
                }
                break;
        }

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'bichuv_doc.id' => $this->id,
            'bichuv_doc.document_type' => $docType,
            'bichuv_doc.action' => $this->action,
            'bichuv_doc.reg_date' => $this->reg_date,
            'bichuv_doc.musteri_id' => $this->musteri_id,
            'bichuv_doc.from_employee' => $this->from_employee,
            'bichuv_doc.to_employee' => $this->to_employee,
            'bichuv_doc.to_hr_department' => $this->to_hr_department,
            'bichuv_doc.from_hr_department' => $this->from_hr_department,
            'bichuv_doc.type' => $this->type,
            'bichuv_doc.status' => $this->status,
            'bichuv_doc.created_at' => $this->created_at,
            'bichuv_doc.updated_at' => $this->updated_at,
            'bichuv_doc.created_by' => $this->created_by,
        ]);
        $query->andFilterWhere(['like','bichuv_nastel_lists.name', $this->bichuv_nastel_list_id]);
        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 20;
        return $dataProvider;

    }


    /**
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public function searchXisobot( $params)
    {  $add='';
        $this->load($params);

        if(!empty($params['BichuvDocSearch']['to_date']) && !empty($params['BichuvDocSearch']['from_date']) ){

            $m=$params['BichuvDocSearch']['from_date'];
            $n=$params['BichuvDocSearch']['to_date'];

            $add .= " AND (('$m'<= bd.reg_date  AND '$n'>= bd.reg_date) OR ('$m'>=bd.reg_date  AND '$n'<=bd.reg_date)) ";       }



        $sql="SELECT
                    DATE_FORMAT(bd.reg_date,'%d.%m.%Y') as sana,
                    sum(bdi.quantity) as keldi,
                    sum(bdi2.quantity) as ketdi
            FROM `bichuv_doc` as bd
            LEFT JOIN bichuv_doc_items as bdi  ON (bd.id=bdi.bichuv_doc_id) AND bd.document_type=1
            LEFT JOIN bichuv_doc_items as bdi2  ON (bd.id=bdi2.bichuv_doc_id) AND bd.document_type=2
            WHERE bd.id IS NOT NULL {$add} AND bd.type = 1 AND (bdi.entity_type = 2 OR bdi2.entity_type = 2 )
            GROUP BY DATE_FORMAT(bd.reg_date,'%d.%m.%Y') ORDER BY bd.reg_date
        ";
        $dataProvider = Yii::$app->db->createCommand($sql)->queryAll();

        return $dataProvider;
    }
}
