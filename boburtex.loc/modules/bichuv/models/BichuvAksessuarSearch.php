<?php

namespace app\modules\bichuv\models;

use app\modules\bichuv\models\BichuvMatoOrders;
use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * BichuvAksessuarSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvMatoOrders`.
 */
class BichuvAksessuarSearch extends BichuvMatoOrders
{
    public $info;
    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['id', 'musteri_id', 'model_orders_id', 'model_orders_items_id', 'bichuv_doc_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['doc_number', 'reg_date', 'add_info', 'info'], 'safe'],
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

     public function search2($params,$id)
     {
         $where='';
         $user_id=Yii::$app->user->id;
          $parent='';
//         if($id!=0) {
//             $parent=' AND bd.parent_id={$id}';
//         }


         $sql=" SELECT bd.id,
                        bd.id expandRowInd,
                        bd.`doc_number` as doc,
                        bd.reg_date,
                        bd2.doc_number, 
                        bd2.article,
                        td.name,
                        bd2.nastel_party nastel_no,
                        p.name as madel_nomi
                        FROM
                        `bichuv_doc` AS bd
                LEFT JOIN toquv_departments AS td ON td.id = bd.to_department
                LEFT JOIN (SELECT bd.id,bd.doc_number,bgr.article,bgr.nastel_party FROM bichuv_doc bd
                LEFT JOIN bichuv_slice_items bsi ON bsi.bichuv_doc_id = bd.id
                LEFT JOIN (SELECT ml.article,bgr.nastel_party FROM bichuv_given_rolls bgr
                LEFT JOIN model_rel_production mrp ON mrp.bichuv_given_roll_id = bgr.id
                LEFT JOIN models_list ml ON mrp.models_list_id = ml.id
                GROUP BY bgr.id) bgr ON bgr.nastel_party = bsi.nastel_party
                GROUP BY bd.id ) bd2 ON bd.parent_id = bd2.id 
                LEFT JOIN bichuv_doc_items AS bdi ON bdi.bichuv_doc_id = bd.id
                LEFT JOIN product as p ON p.id=bdi.model_id
                WHERE bd.`document_type`=6 AND bd.to_department IN (SELECT td.id FROM toquv_departments td LEFT JOIN toquv_user_department tud ON tud.department_id = td.id WHERE tud.user_id={$user_id}  AND tud.type = 0) {$parent}
                GROUP BY bd.id 
                 ";

         if (!$this->validate()) {
             $dataProvider = new SqlDataProvider([
                 'sql' => $sql,
             ]);
             if ($_GET['_tog7ce9367e'] == 'all') {
//                $dataProvider->pagination = false;
                 $dataProvider->pagination = [
                     'pageSize' => 1000
                 ];
             }
           }
         $dataProvider = new SqlDataProvider([
             'sql' => $sql,
         ]);
         if ($_GET['_tog7ce9367e'] == 'all') {
             $dataProvider->pagination = [
                 'pageSize' => 1000
             ];
         }
//         $result = ArrayHelper::map($dataProvider->getModels(), 'id', function($item){
//             return $item;
//         });
//         VarDumper::dump($item, 10, true); die;



         return $dataProvider;
     }
    public function search3($params, $docType = self::DOC_TYPE_ACCEPTED)
    {
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
            'bichuv_mato_orders_id' => $this->bichuv_mato_orders_id,
            'models_list_id' => $this->models_list_id,
            'model_var_id' => $this->model_var_id,
        ]);
        $query->andFilterWhere(['document_type'=>$docType]);
        switch ($docType){
            case self::DOC_TYPE_ACCEPTED:
                $query->andFilterWhere(['to_department'=>$this->tayyorlov]);
        }
        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'musteri_responsible', $this->musteri_responsible])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        // sort by reg_date, status
        $query->orderBy(['reg_date' => SORT_DESC, 'status' => SORT_ASC]);

        return $dataProvider;
    }
     public function search($params)
    {
        $query = BichuvMatoOrders::find()->alias('bmo')->where(['>','bmo.status',self::STATUS_INACTIVE]);

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
            'reg_date' => $this->reg_date,
            'musteri_id' => $this->musteri_id,
            'model_orders_id' => $this->model_orders_id,
            'model_orders_items_id' => $this->model_orders_items_id,
            'bichuv_doc_id' => $this->bichuv_doc_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if($this->info){
            $query->joinWith(['moi moi' => function ($q) {
                $q->where(['moi.id'=>$this->info]);
            }]);
        }
        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }
}
