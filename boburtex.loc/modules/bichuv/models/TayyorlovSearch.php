<?php

namespace app\modules\bichuv\models;

use app\modules\hr\models\HrDepartments;
use app\modules\toquv\models\ToquvDepartments;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvDoc;

/**
 * TayyorlovSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvDoc`.
 */
class TayyorlovSearch extends BichuvDoc
{
    public $tayyorlov;
    public $acsWarehouseId;
    public $bichuvDepId;
    public $tikuvDepId;

    public $doc_number_and_date;

    public $party;

    public $musteri_party;

    public $nastel_party;

    public $toquv_doc_id;

    public $model_id;

    public $type;

    public function init()
    {
        parent::init();
        $tayyorlov = HrDepartments::findOne(['token'=>HrDepartments::TOKEN_TAYYORLOV]);
        if($tayyorlov){
            $this->tayyorlov = $tayyorlov['id'];
        }
        $acsWarehouse = HrDepartments::findOne(['token' => HrDepartments::TOKEN_ACS_WAREHOUSE]);
        if ($acsWarehouse !== null) {
            $this->acsWarehouseId = $acsWarehouse['id'];
        }
        $bichuvDep = HrDepartments::findOne(['token' => HrDepartments::TOKEN_BICHUV]);
        if ($bichuvDep !== null) {
            $this->bichuvDepId = $bichuvDep['id'];
        }
        $tikuvDepId = HrDepartments::findOne(['token' => HrDepartments::TOKEN_TIKUV]);
        if ($tikuvDepId !== null) {
            $this->tikuvDepId = $tikuvDepId['id'];
        }
        return false;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_type', 'action', 'musteri_id', 'from_hr_department', 'from_hr_employee', 'to_hr_department', 'to_hr_employee', 'parent_id', 'status', 'created_at', 'updated_at', 'created_by', 'payment_method', 'pb_id', 'type', 'size_collection_id', 'work_weight', 'toquv_doc_id', 'is_returned', 'nastel_table_no', 'nastel_table_worker', 'service_musteri_id', 'is_service', 'bichuv_mato_orders_id', 'models_list_id', 'model_var_id'], 'integer'],
            [['doc_number', 'reg_date', 'musteri_responsible', 'add_info', 'deadline'], 'safe'],
            [['paid_amount', 'rag', 'slice_weight', 'total_weight'], 'number'],
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
     * @param int $docType
     * @return ActiveDataProvider
     */
    public function search($params, $docType = self::DOC_TYPE_ACCEPTED)
    {
        $slug = Yii::$app->request->get('slug');
        $query = BichuvDoc::find();

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
            'document_type' => $this->document_type,
            'action' => $this->action,
            'reg_date' => $this->reg_date,
            'musteri_id' => $this->musteri_id,
            'from_hr_department' => $this->from_hr_department,
            'from_hr_employee' => $this->from_hr_employee,
            'to_hr_department' => $this->to_hr_department,
            'to_hr_employee' => $this->to_hr_employee,
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'payment_method' => $this->payment_method,
            'paid_amount' => $this->paid_amount,
            'pb_id' => $this->pb_id,
            'type' => $this->type,
            'size_collection_id' => $this->size_collection_id,
            'rag' => $this->rag,
            'work_weight' => $this->work_weight,
            'toquv_doc_id' => $this->toquv_doc_id,
            'slice_weight' => $this->slice_weight,
            'total_weight' => $this->total_weight,
            'is_returned' => $this->is_returned,
            'nastel_table_no' => $this->nastel_table_no,
            'nastel_table_worker' => $this->nastel_table_worker,
            'service_musteri_id' => $this->service_musteri_id,
            'deadline' => $this->deadline,
            'is_service' => $this->is_service,
            'bichuv_mato_orders_id' => $this->bichuv_mato_orders_id,
            'models_list_id' => $this->models_list_id,
            'model_var_id' => $this->model_var_id,
        ]);
        switch ($slug) {
            case self::DOC_TYPE_ACCEPTED_SLICE_LABEL:
                $query->andFilterWhere(['from_hr_department' => $this->bichuvDepId]);
                $query->andFilterWhere(['to_hr_department' => $this->tayyorlov]);
                break;
            case self::DOC_TYPE_ACCEPTED_LABEL:
                $query->andFilterWhere(['from_hr_department' => $this->acsWarehouseId]);
                $query->andFilterWhere(['to_hr_department' => $this->tayyorlov]);
                break;
            case self::DOC_TYPE_MOVING_SLICE_LABEL:
                $query->andFilterWhere(['from_hr_department' => $this->tayyorlov]);
                $query->andFilterWhere(['to_hr_department' => $this->tikuvDepId]);
                break;
        }

        $query->andFilterWhere(['document_type'=>$docType]);
        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'musteri_responsible', $this->musteri_responsible])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        // sort by reg_date, status
        $query->orderBy(['reg_date' => SORT_DESC, 'status' => SORT_ASC]);

        return $dataProvider;
    }
}
