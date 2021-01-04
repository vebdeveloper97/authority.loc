<?php

namespace app\modules\tikuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tikuv\models\TikuvDoc;
use yii\data\SqlDataProvider;

/**
 * DocSearch represents the model behind the search form of `app\modules\tikuv\models\TikuvDoc`.
 */
class DocSearch extends TikuvDoc
{
    public $doc_number_and_date;
    public $model_list_id;
    public $model_var_id;
    public $party;
    public $musteri_party;
    public $nastel_no;
    public $toquv_doc_id;
    public $model_id;
    public $type;
    public $model;
    public $variation;
    public $model_and_variation;
    public $article_old;
    public $article_new;
    public $color_old;
    public $color_new;
    public $count_work;
    public $executive;

    /**
     * {@inheritdoc}
     */
    public $doc_number1;
    public $doc_number2
    ;
    public function rules()
    {
        return [
            [['id', 'document_type', 'party_count', 'musteri_id', 'from_department', 'from_employee', 'to_department', 'to_employee', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['doc_number','model_and_variation','doc_number_and_date', 'nastel_no', 'party_no', 'reg_date', 'musteri_responsible', 'add_info','model_var_id','model_list_id','model','variation','article_old','article_new','color_old','color_new','executive','doc_number1','doc_number2'], 'safe'],
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
     * @param $modelType
     * @param $docType
     * @param $entityType
     * @param bool $isChangeModel
     * @return ActiveDataProvider
     */
    public function searchOld($params, $modelType, $docType, $entityType, $isChangeModel = false)
    {
        $query = TikuvDoc::find()->leftJoin('tikuv_doc_items','tikuv_doc_items.tikuv_doc_id= tikuv_doc.id');

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
        if(!empty($this->model) || !empty($this->variation)){
            $query->innerJoin('bichuv_given_rolls','bichuv_given_rolls.nastel_party = tikuv_doc_items.nastel_party_no');
            $query->innerJoin('model_rel_production','model_rel_production.bichuv_given_roll_id = bichuv_given_rolls.id');
            $query->innerJoin('models_list','models_list.id = model_rel_production.models_list_id');
            $query->innerJoin('models_variations','models_variations.id = model_rel_production.model_variation_id');
            $query->innerJoin('color_pantone','color_pantone.id = models_variations.color_pantone_id');
        }
        $query->distinct();

        if(!empty($this->model) || !empty($this->variation)){
            $query->andFilterWhere(['OR',['like','models_list.article', $this->model],['like','color_pantone.code', $this->variation]]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'party_count' => $this->party_count,
            'musteri_id' => $this->musteri_id,
            'from_department' => $this->from_department,
            'from_employee' => $this->from_employee,
            'to_department' => $this->to_department,
            'to_employee' => $this->to_employee,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if($isChangeModel){
            $query->andFilterWhere([
               'tikuv_doc.status' => self::STATUS_SAVED
            ]);
            $query->andFilterWhere(['or',
                ['document_type' => TikuvDoc::DOC_TYPE_INSIDE],
                ['document_type' => $docType],
            ]);
            $query->andFilterWhere(['or',
                ['tikuv_doc.type' => 4],
                ['tikuv_doc.type' => $modelType],
            ]);
        }else{
            $query->andFilterWhere( ['tikuv_doc.type' => $modelType]);
            $query->andFilterWhere( ['document_type' => $this->document_type]);
        }



        if(!empty($this->doc_number_and_date)){
            $query->andFilterWhere(['or',
                ['like', 'doc_number', $this->doc_number_and_date],
                ['like', 'reg_date', date('Y-m-d', strtotime($this->doc_number_and_date))]
            ]);
        }
        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'tikuv_doc_items.nastel_party_no', $this->nastel_no])
            ->andFilterWhere(['like', 'musteri_responsible', $this->musteri_responsible])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);
        $query->orderBy(['tikuv_doc.id' => SORT_DESC]);
        return $dataProvider;
    }
    public function search($params, $modelType, $docType, $entityType, $isChangeModel = false)
    {
        $query = TikuvDoc::find()->leftJoin('tikuv_doc_items','tikuv_doc_items.tikuv_doc_id= tikuv_doc.id');

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
        if(!empty($this->model) || !empty($this->variation)){
            $query->innerJoin('bichuv_given_rolls','bichuv_given_rolls.nastel_party = tikuv_doc_items.nastel_party_no');
            $query->innerJoin('model_rel_production','model_rel_production.bichuv_given_roll_id = bichuv_given_rolls.id');
            $query->innerJoin('models_list','models_list.id = model_rel_production.models_list_id');
            $query->innerJoin('models_variations','models_variations.id = model_rel_production.model_variation_id');
            $query->innerJoin('color_pantone','color_pantone.id = models_variations.color_pantone_id');
        }
        $query->distinct();

        if(!empty($this->model) || !empty($this->variation)){
            $query->andFilterWhere(['OR',['like','models_list.article', $this->model],['like','color_pantone.code', $this->variation]]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'party_count' => $this->party_count,
            'musteri_id' => $this->musteri_id,
            'from_department' => $this->from_department,
            'from_employee' => $this->from_employee,
            'to_department' => $this->to_department,
            'to_employee' => $this->to_employee,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if($isChangeModel){
            $query->andFilterWhere([
               'tikuv_doc.status' => self::STATUS_SAVED
            ]);
            $query->andFilterWhere(['or',
                ['document_type' => TikuvDoc::DOC_TYPE_INSIDE],
                ['document_type' => $docType],
            ]);
            $query->andFilterWhere(['or',
                ['tikuv_doc.type' => 4],
                ['tikuv_doc.type' => $modelType],
            ]);
        }else{
            $query->andFilterWhere( ['tikuv_doc.type' => $modelType]);
            $query->andFilterWhere( ['document_type' => $this->document_type]);
        }



        if(!empty($this->doc_number_and_date)){
            $query->andFilterWhere(['or',
                ['like', 'doc_number', $this->doc_number_and_date],
                ['like', 'reg_date', date('Y-m-d', strtotime($this->doc_number_and_date))]
            ]);
        }
        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'tikuv_doc_items.nastel_party_no', $this->nastel_no])
            ->andFilterWhere(['like', 'musteri_responsible', $this->musteri_responsible])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);
        $query->orderBy(['tikuv_doc.id' => SORT_DESC]);
        return $dataProvider;
    }

    public function search_doc($params, $modelType, $docType, $entityType, $isChangeModel = false){
        $addQuery = '';
        $sql = "select DISTINCT td.doc_number,
                       td.id,
                       td.status,
                       DATE_FORMAT(td.reg_date, '%%d.%%m.%%Y') reg_data,
                       tdi.nastel_party_no nastel_no,
                       bgr.article         article_old,
                       mrd.article         article_new,
                       mrd.doc_number as doc_number1,
                       bgr.doc_number as doc_number2,
                       bgr.color_old,
                       mrd.color_new,
                       SUM(tdi.quantity) count_work,
                       td.is_change_model,
                       td.is_service,
                       td.to_musteri,
                       td.musteri_id,
                       if(to_m.name is not null,to_m.name, 'SAMO') tmusteri,
                       m.name musteri,
                       td.type
                from tikuv_doc td
                         left join tikuv_doc_items tdi on td.id = tdi.tikuv_doc_id
                         left join musteri to_m ON to_m.id=td.to_musteri
                         left join musteri m ON m.id=td.musteri_id
                         left join (SELECT mrd.tikuv_doc_id,mo.id as doc_id,mo.doc_number, ml.article, CONCAT(cp.code, ' (', mv.name, ')') color_new
                                    FROM model_rel_doc mrd
                                             left join models_list ml ON mrd.model_list_id = ml.id
                                             left join model_orders as mo ON mrd.order_id = mo.id
                                             left join models_variations mv ON ml.id = mv.model_list_id
                                             left join color_pantone cp ON mrd.color_id = cp.id
                                    GROUP BY mrd.nastel_no, ml.id) mrd ON td.id = mrd.tikuv_doc_id
                         left join (
                                    select mv.id as                            model_var_id,
                                           ml.id as                            model_id,
                                           ml.article,
                                           CONCAT(cp.code, ' (', mv.name, ')') color_old,
                                           mrp.type,
                                           mo.doc_number,
                                           mo.id as doc_id,
                                           bgr.nastel_party,
                                           bgr.nastel_no
                                    from bichuv_given_rolls bgr
                                             left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                                             LEFT JOIN model_orders as mo ON mrp.order_id = mo.id
                                             left join models_list ml on mrp.models_list_id = ml.id
                                             left join models_variations mv on mv.id = mrp.model_variation_id
                                             left join model_variation_parts mvp on mrp.model_var_part_id = mvp.id
                                             left join color_pantone cp on mv.color_pantone_id = cp.id
                                ) bgr ON bgr.nastel_party = tdi.nastel_party_no
                        where 1=1  %s %s %s %s %s %s %s %s %s
                group by td.id  ORDER BY td.status ASC,td.id DESC";

        $this->load($params);
        if (!$this->validate()) {
            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
            ]);
            return $dataProvider;
        }

        if($isChangeModel){
            $addQuery .= " AND td.status = ".self::STATUS_SAVED." AND (td.document_type = {$docType} OR td.document_type = ".self::DOC_TYPE_INSIDE.") AND (td.type = 4 OR td.type = {$modelType})";
        }else{
            $addQuery .= " AND td.type = {$modelType} AND td.document_type = {$docType}";
        }

        if ($this->doc_number_and_date)
            $doc_num_and_date = "AND (td.doc_number LIKE '%$this->doc_number_and_date%' OR DATE_FORMAT(td.reg_date, '%d.%m.%Y') LIKE '%$this->doc_number_and_date%') ";
        if ($this->nastel_no)
            $nastel_no = "AND (tdi.nastel_party_no LIKE '%$this->nastel_no%')";
        if ($this->doc_number1)
         {$where.= " AND (mrd.doc_number LIKE '%$this->doc_number1%')";}
        if ($this->doc_number2)
        { $where.= " AND (bgr.doc_number LIKE '%$this->doc_number2%')";}
        if ($this->article_old)
            $article_old = "AND (bgr.article LIKE '%$this->article_old%')";
        if ($this->article_new)
            $article_new = "AND (mrd.article LIKE '%$this->article_new%')";
        if ($this->color_old)
            $color_old = "AND (bgr.color_old LIKE '%$this->color_old%')";
        if ($this->color_new)
            $color_new = "AND (mrd.color_new LIKE '%$this->color_new%')";

        if ($this->executive && in_array(11,$this->executive)){
            $executiveIn = " AND (td.to_musteri in (".implode(',',$this->executive).") OR td.to_musteri IS NULL)";
        }elseif($this->executive){
            $executiveIn = "AND (td.to_musteri in (".implode(',',$this->executive).") )";
        }

        $sql = sprintf($sql,$doc_num_and_date, $nastel_no,$where, $article_old, $article_new, $color_old, $color_new, $addQuery, $executiveIn);

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchCombine($params)
    {
        $query = TikuvDoc::find()->leftJoin('tikuv_doc_items','tikuv_doc_items.tikuv_doc_id = tikuv_doc.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->innerJoin('bichuv_given_rolls','bichuv_given_rolls.nastel_party = tikuv_doc_items.nastel_party_no');
        $query->innerJoin('model_rel_production','model_rel_production.bichuv_given_roll_id = bichuv_given_rolls.id');

        if(!empty($this->model_and_variation)){
            $query->innerJoin('models_list','models_list.id = model_rel_production.models_list_id');
            $query->innerJoin('models_variations','models_variations.id = model_rel_production.model_variation_id');
            $query->innerJoin('color_pantone','color_pantone.id = models_variations.color_pantone_id');
        }
        $query->distinct();

        if(!empty($this->model_and_variation)){
            $query->andFilterWhere(['OR',['like','models_list.article', $this->model_and_variation],['like','color_pantone.code', $this->model_and_variation]]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tikuv_doc.type' => 1,
            'document_type' => $this->document_type,
            'party_count' => $this->party_count,
            'musteri_id' => $this->musteri_id,
            'from_department' => $this->from_department,
            'from_employee' => $this->from_employee,
            'to_department' => $this->to_department,
            'to_employee' => $this->to_employee,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        $query->andFilterWhere([
            'tikuv_doc.status' => self::STATUS_SAVED,
            'model_rel_production.type' => 2
        ]);

        if(!empty($this->doc_number_and_date)){
            $query->andFilterWhere(['or',
                ['like', 'doc_number', $this->doc_number_and_date],
                ['like', 'reg_date', date('Y-m-d', strtotime($this->doc_number_and_date))]
            ]);
        }
        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'tikuv_doc_items.nastel_party_no', $this->nastel_no])
            ->andFilterWhere(['like', 'musteri_responsible', $this->musteri_responsible])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);
        $query->orderBy(['tikuv_doc.id' => SORT_DESC]);
        return $dataProvider;
    }
}
