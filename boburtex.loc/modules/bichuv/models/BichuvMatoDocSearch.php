<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 06.05.20 11:36
 */

namespace app\modules\bichuv\models;

use app\modules\toquv\models\ToquvDepartments;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BichuvMatoDocSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvDoc`.
 */
class BichuvMatoDocSearch extends BichuvDoc
{
    public $doc_number_and_date;

    public $party;

    public $musteri_party;

    public $nastel_party;

    public $toquv_doc_id;

    public $model_id;

    public $type;

    public $model_and_variation;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_type', 'action', 'musteri_id', 'from_department', 'from_employee', 'to_department', 'to_employee', 'parent_id', 'status', 'created_at', 'updated_at', 'created_by', 'payment_method', 'pb_id', 'type', 'size_collection_id', 'work_weight', 'toquv_doc_id', 'is_returned', 'nastel_table_no', 'nastel_table_worker', 'service_musteri_id', 'is_service', 'bichuv_mato_orders_id', 'models_list_id', 'model_var_id'], 'integer'],
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
     * @return ActiveDataProvider
     */
    public function search($params,$id=null,$from_dept=null,$to_dept=null,$doc_type=BichuvDoc::DOC_TYPE_MOVING)
    {
        $query = BichuvDoc::find()->where(['document_type'=>$doc_type])->andWhere(['IS NOT','bichuv_mato_orders_id',new \yii\db\Expression('NULL')]);
        if($id){
            $query->andWhere(['bichuv_mato_orders_id'=>$id]);
        }
        if($from_dept){
            $query->andWhere(['from_department'=>$from_dept]);
        }else{
            $mato_ombor = ToquvDepartments::findOne(['token'=>'BICHUV_MATO_OMBOR']);
            if($mato_ombor!==null){
                $query->andWhere(['from_department'=>$mato_ombor['id']]);
            }
        }
        if($to_dept){
            $query->andWhere(['to_department'=>$to_dept]);
        }else{
            $bichuv = ToquvDepartments::findOne(['token'=>'BICHUV_DEP']);
            if($bichuv!==null){
                $query->andWhere(['to_department'=>$bichuv['id']]);
            }
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_ASC,'id'=>SORT_DESC]],
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
            'from_department' => $this->from_department,
            'from_employee' => $this->from_employee,
            'to_department' => $this->to_department,
            'to_employee' => $this->to_employee,
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
            'models_list_id' => $this->models_list_id,
            'model_var_id' => $this->model_var_id,
        ]);
        /*if($id){
            $query->andFilterWhere(['bichuv_mato_orders_id'=>$id]);
        }else{
            $query->andFilterWhere(['bichuv_mato_orders_id' => $this->bichuv_mato_orders_id]);
        }*/
        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'musteri_responsible', $this->musteri_responsible])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }
}