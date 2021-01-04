<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\ModelOrdersItems;
use yii\data\SqlDataProvider;
use app\modules\toquv\models\ToquvRawMaterials;
use yii\helpers\VarDumper;

/**
 * ModelOrdersItemsSearch represents the model behind the search form of `app\modules\base\models\ModelOrdersItems`.
 */
class ModelOrdersItemsSearch extends ModelOrdersItems
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'model_orders_id', 'models_list_id', 'model_var_id', 'priority'], 'integer'],
            [['add_info', 'load_date', 'season'], 'safe'],
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
    public function search($params,$id=null,$var_id=null,$status=null,$isTrue=false)
    {
        $query = ModelOrdersItems::find();
        if($id){
            $query = $query->where(['model_orders_id' => $id]);
        }
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'model_orders_id' => $this->model_orders_id,
            'models_list_id' => $this->models_list_id,
            'model_var_id' => $this->model_var_id,
            'load_date' => $this->load_date,
            'priority' => $this->priority,
        ]);

        $query->andFilterWhere(['like', 'add_info', $this->add_info])
            ->andFilterWhere(['model_orders_variations_id' => $var_id])
            ->andFilterWhere(['status' => $status])
            ->andFilterWhere(['like', 'season', $this->season]);

        if($isTrue) $query->andWhere(['not', ['status' => 2]]);

        return $dataProvider;
    }

    public function searchThread($params,$id){
        $sql="SELECT
                        trm.name AS nomi,
                        SUM(mop.raw_fabric * trmi.percentage/100) as xom_mato,
                        thread.name as tur,
                        ne.name as tur2,
                        moi.load_date as yuklama,
                        SUM((thread.wastage_percent/100 + 1)*trmi.percentage*mop.raw_fabric/100) as miqdor
                FROM `model_orders_items` AS moi
                LEFT JOIN model_orders AS mo ON moi.`model_orders_id` = mo.id
                LEFT JOIN model_orders_planning AS mop ON mop.model_orders_items_id = moi.id
                LEFT JOIN toquv_raw_materials AS trm ON trm.id = mop.toquv_raw_materials_id
                LEFT JOIN toquv_raw_material_ip AS trmi ON trmi.toquv_raw_material_id = trm.id
                LEFT JOIN toquv_ne as ne ON trmi.ne_id=ne.id
                LEFT JOIN toquv_thread as thread ON trmi.thread_id=thread.id
                WHERE mo.id = {$id} GROUP BY thread.name ,ne.name,DATE_FORMAT(moi.load_date,'%d.%m.%Y') ";
        if (!$this->validate()) {
            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
            ]);
            if ($_GET['_tog7ce9367e'] == 'all') {
                $dataProvider->pagination = [
                    'pageSize' => 10000
                ];
            }
        }
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        if ($_GET['_tog7ce9367e'] == 'all') {
            $dataProvider->pagination = [
                'pageSize' => 10000
            ];
        }
        return $dataProvider;
    }

    public function searchPlanned($params,$id)
    {
        $s = ModelOrders::STATUS_INACTIVE;
        $sql="
            SELECT moi.id,
                   mo.doc_number,
                   mo.reg_date,
                   mois.size,
                   mois.count,
                   CONCAT('SM-',moi.id) order_item,
                   CONCAT_WS(' ', mv.name, mv.code)                             as variant,
                   ml.article as model,
                   bap.id                                                             property_id,
                   bap.value                                                           property,
                   ba.id                                                              acs_id,
                   ba.name,
                   ba.sku,
                   moia.qty,
                   ba.barcode,
                   moia.add_info,
                   u.name                                                             unit,
                   (SELECT bat.path
                    FROM bichuv_acs_attachment bat
                    WHERE bat.bichuv_acs_id = ba.id
                    ORDER BY bat.isMain DESC
                    LIMIT 1)                                                          image
            FROM bichuv_acs_properties bap
                     LEFT JOIN bichuv_acs ba ON bap.bichuv_acs_id = ba.id
                     LEFT JOIN unit u ON ba.unit_id = u.id
                     LEFT JOIN model_orders_items_acs moia ON moia.bichuv_acs_id = ba.id
                     LEFT JOIN model_orders_items moi on moia.model_orders_items_id = moi.id
                     LEFT JOIN model_orders as mo ON moi.model_orders_id = mo.id
                     LEFT JOIN models_list as ml ON moi.models_list_id = ml.id
                     LEFT JOIN models_variations mv on moi.model_var_id = mv.id
                    LEFT JOIN (SELECT GROUP_CONCAT(s.name) size,SUM(mois.count)  count,mois.model_orders_items_id FROM model_orders_items_size mois
                        LEFT JOIN size s on mois.size_id = s.id 
                        GROUP BY mois.model_orders_items_id
                    ) mois ON mois.model_orders_items_id = moi.id
            WHERE moi.model_orders_id = {$id} AND moi.status != {$s}
            GROUP BY moi.id, moia.id 
         ";
        if (!$this->validate()) {
            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
            ]);
            if ($_GET['_tog7ce9367e'] == 'all') {
                $dataProvider->pagination = false;
            }
        }
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        if ($_GET['_tog7ce9367e'] == 'all') {

        }
        $dataProvider->pagination = false;
        return $dataProvider;


    }

    public function searchPlan($params,$id,$type)
    {
        /*if(!empty($array)){
            $q = " AND mv.id in (".implode(',', $array).")";
        }*/
        /*SELECT mo.id as modelOrderId, mo.doc_number, moi.models_list_id, moi.model_var_id,
                moia.bichuv_acs_id, moim.mato_id, mois.size_id, moita.toquv_raw_materials_id,
                ml.name as mlname, ml.article, ml.base_pattern_id, mv.wms_desen_id as mv_desen, mv.wms_color_id as mv_color,
                ba.sku, ba.name as baname, bap.value, bapl.name as bapl_name,
                wmi.raw_material_type_id, wmi.wms_color_id, wmi.ne_id,wmi.pus_fine_id, wmi.thread_id, wmi.wms_desen_id,
                twr.name as twr_name, twr.code as twr_code,
                wc.color_code, wc.color_name, wc.color_palitra_code,
                wd.name as wname, wd.code as wcode, wd.wms_baski_type_id,
                wbt.name as wbtname, wmi.id as wmiId, tr.name as trname, wc.color_pantone_id, cp.name as cpname, cp.code as cpcode, cp.r, cp.g, cp.b
            FROM
                model_orders mo LEFT JOIN model_orders_items moi ON mo.id = moi.model_orders_id
                LEFT JOIN model_orders_items_acs moia ON moia.models_orders_id = mo.id
                LEFT JOIN model_orders_items_material moim ON moim.model_orders_id = mo.id
                LEFT JOIN model_orders_items_size mois ON mois.model_orders_id = mo.id
                LEFT JOIN model_orders_items_toquv_acs moita on moita.model_orders_id = mo.id
                LEFT JOIN models_list ml ON ml.id = moi.models_list_id
                LEFT JOIN models_variations mv ON mv.id = moi.model_var_id
                LEFT JOIN bichuv_acs ba ON ba.id = moia.bichuv_acs_id
                LEFT JOIN bichuv_acs_properties bap ON bap.bichuv_acs_id = ba.id
                LEFT JOIN bichuv_acs_property_list bapl ON bapl.id = bap.bichuv_acs_property_list_id
                LEFT JOIN wms_mato_info wmi ON wmi.id = moim.mato_id
                LEFT JOIN toquv_raw_materials twr ON twr.id = wmi.toquv_raw_materials_id
                LEFT JOIN toquv_raw_materials tr ON tr.id = moita.toquv_raw_materials_id
                LEFT JOIN wms_color wc ON wc.id = wmi.wms_color_id
                LEFT JOIN color_pantone cp ON cp.id = wc.color_pantone_id
                LEFT JOIN wms_desen wd ON wd.id = wmi.wms_desen_id
                LEFT JOIN wms_baski_type wbt ON wbt.id = wd.wms_baski_type_id
                WHERE mo.id = {$id}
                $q
                GROUP BY twr.name  */
        $sql="SELECT
                   cp.id as cp_id,
                   cp.code,
                   cp.r,
                   cp.g,
                   cp.b,
                   c.id as c_id,
                   c.color,
                   c.color_id,
                   mo.doc_number,
                   CONCAT('SM-',moi.id) order_item,
                   CONCAT_WS(' ', mv.name, mv.code) as variant,
                   ml.article as model,
                   trm.name as name,
                   mop.work_weight as work_weight,
                   mop.finished_fabric as finished_fabric,
                   mop.raw_fabric as raw_fabric,
                   mop.thread_length as thread_length,
                   mop.finish_en as finish_en,
                   mop.finish_gramaj as finish_gramaj, 
                   mop.add_info as add_info,
                   wmi.wms_color_id, wmi.wms_desen_id,
                   wc.color_code, wc.color_name, wc.color_pantone_id,
                   cp.name as cpname 
                   FROM model_orders_planning as mop
                   LEFT JOIN model_orders_items as moi ON mop.model_orders_items_id = moi.id
                   LEFT JOIN models_list as ml ON moi.models_list_id = ml.id
                   LEFT JOIN models_variations mv ON moi.model_var_id = mv.id
                   LEFT JOIN model_orders as mo ON moi.model_orders_id = mo.id
                   LEFT JOIN color as c ON mop.color_id = c.id
                   LEFT JOIN toquv_raw_materials as trm ON mop.toquv_raw_materials_id = trm.id
                   LEFT JOIN wms_mato_info as wmi ON trm.id = wmi.toquv_raw_materials_id            
                   LEFT JOIN wms_color wc on wc.id = mv.wms_color_id
                   LEFT JOIN color_pantone cp on cp.id = wc.color_pantone_id 
                    WHERE mo.id={$id} AND mop.type = {$type}
                    GROUP BY mop.id
         ";
        if (!$this->validate()) {
            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
            ]);
            if ($_GET['_tog7ce9367e'] == 'all') {
                $dataProvider->pagination = [
                    'pageSize' => 10000
                ];
            }
        }
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        if ($_GET['_tog7ce9367e'] == 'all') {
            $dataProvider->pagination = [
                'pageSize' => 10000
            ];
        }
        return $dataProvider;

    }

    public function searchAksessuar($params,$id,$type = ToquvRawMaterials::ACS)
    {
        $sql="
         SELECT cp.id as cp_id,
       cp.code, cp.name as cpname,
       cp.r,
       cp.g,
       cp.b,
       c.id as c_id,
       c.color,
       c.color_id,
       mo.doc_number,
       CONCAT('SM-',moi.id) order_item,
       CONCAT_WS(' ', mv.name, mv.code) as variant,
       ml.article as model,
       trm.name as name, 
       mop.work_weight as work_weight,
       mop.raw_fabric as raw_fabric,
       mop.count as count,
       mop.thread_length as thread_length,
       mop.finish_en as finish_en,
       mop.finish_gramaj as finish_gramaj,
       mop.add_info as add_info
       FROM model_orders_planning as mop
       LEFT JOIN model_orders_items as moi ON mop.model_orders_items_id = moi.id
       LEFT JOIN color_pantone as cp ON mop.color_pantone_id = cp.id
       LEFT JOIN models_list as ml ON moi.models_list_id = ml.id
       LEFT JOIN models_variations mv ON moi.model_var_id = mv.id
       LEFT JOIN model_orders as mo ON moi.model_orders_id = mo.id
       LEFT JOIN color as c ON mop.color_id = c.id
       LEFT JOIN toquv_raw_materials as trm ON mop.toquv_raw_materials_id = trm.id
   WHERE mo.id={$id} AND mop.type = {$type}
         ";
        if (!$this->validate()) {
            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
            ]);
            if ($_GET['_tog7ce9367e'] == 'all') {
                $dataProvider->pagination = [
                    'pageSize' => 10000
                ];
            }
        }
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        if ($_GET['_tog7ce9367e'] == 'all') {
            $dataProvider->pagination = [
                'pageSize' => 10000
            ];
        }
        return $dataProvider;
    }
}
