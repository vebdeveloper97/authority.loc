<?php

namespace app\api\modules\v1\controllers;

use app\modules\base\models\BarcodeCustomers;
use app\modules\base\models\Brend;
use app\modules\base\models\Goods;
use app\modules\base\models\GoodsBarcode;
use app\modules\base\models\GoodsItems;
use app\modules\tikuv\models\ModelRelDoc;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\TikuvDocItems;
use app\modules\tikuv\models\TikuvSliceItemBalance;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Country Controller API
 *
 * @author Omadbek Onorov <omadbek.onorov@gmail.com>
 */
class GoodController extends ActiveController
{
    public $modelClass = 'app\modules\base\models\Goods';

    public $enableCsrfValidation = false;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }

    public function beforeAction($action)
    {
        $userId = Yii::$app->user->isGuest;
        if($userId){
            return false;
        }
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @param $getData
     * @return array
     */
    public function conditions($getData)
    {

        $conditions = [];
        $conditions['page'] = 1;
        $conditions['limit'] = 100;
        $conditions['lang'] = 'uz';
        $conditions['sort'] = 'DESC';
        $conditions['name'] = '';
        $conditions['size'] = '';
        $conditions['color'] = '';
        $conditions['type'] = '';
        if (!empty($getData)) {
            if (!empty($getData['limit'])) {
                $conditions['limit'] = $getData['limit'];
            }
            if (!empty($getData['page'])) {
                $conditions['page'] = $getData['page'];
            }
            if (!empty($getData['lang'])) {
                $conditions['lang'] = $getData['lang'];
            }
            if (!empty($getData['sort'])) {
                $conditions['sort'] = $getData['sort'];
            }
            if (!empty($getData['name'])) {
                $conditions['name'] = $getData['name'];
            }
            if (!empty($getData['size'])) {
                $conditions['size'] = $getData['size'];
            }
            if (!empty($getData['color'])) {
                $conditions['color'] = $getData['color'];
            }
            if (!empty($getData['nastel'])) {
                $conditions['nastel'] = $getData['nastel'];
            }
            if (!empty($getData['type'])) {
                $conditions['type'] = $getData['type'];
            }
        }
        return $conditions;
    }

    public function actionSearch()
    {
        $getData = $this->conditions(Yii::$app->request->get());

        $response['status'] = true;

        $modelNo = "";
        $sizeCol = "";
        $color = "";
        $type = '';
        $typeNumber = 1;

        if (!empty($getData['type'])) {
            $typeNumber = $getData['type'];
            $type = " AND g.type = '{$getData['type']}' ";
        }
        if ($typeNumber == 1) {
            if (!empty($getData['name'])) {
                $modelNo = " AND ((g.model_no LIKE '%{$getData['name']}') OR (g.barcode = '{$getData['name']}'))";
            }

            if (!empty($getData['size'])) {
                $sizeCol = " AND (sc.id = '{$getData['size']}') ";
            }

            if (!empty($getData['color'])) {
                $color = " AND (cp.id = '{$getData['color']}')";
            }
            $sql = "select g.id,
                       g.status,
                       g.barcode,
                       g.barcode1,
                       g.barcode2,
                       g.desc1,
                       g.brand2,
                       g.brand3,
                       g.model_no,
                       g.model_id,
                       g.model_var, 
                       g.type,
                       g.name,
                       g.properties,
                       g.color_name, 
                       g.size_collection,
                       g.color_collection,
                       st.id as sizeTypeId,
                       s.id as sizeId,
                       cp.id as colorId,
                       s.name as sizeName,
                       st.name as sizeTypeName,
                       cp.name_ru as colorName,
                       cp.code as pantone,
                       1 as sum_qty 
                from goods g
                         left join size_type st on st.id = g.size_type
                         left join size s on g.size = s.id
                         left join color_pantone cp on cp.id = g.color
                         left join size_col_rel_size scrs on s.id = scrs.size_id
                         left join size_collections sc on scrs.sc_id = sc.id  
                         WHERE g.status = 1 AND g.type=1 %s %s %s %s
                         GROUP BY g.id ORDER BY s.order ASC LIMIT %d OFFSET %d;";
            $sql = sprintf($sql, $type, $modelNo, $sizeCol, $color, $getData['limit'], $getData['offset']);
            $query = Yii::$app->db->createCommand($sql)->queryAll();
        } else {
            if (!empty($getData['name'])) {
                $modelNo = " AND (
                    (g.model_no LIKE '%{$getData['name']}%') OR (g.barcode = '{$getData['name']}')
                    OR 
                    (g2.model_no LIKE '%{$getData['name']}%') OR (g2.barcode = '{$getData['name']}')
                    OR
                    (g3.model_no LIKE '%{$getData['name']}%') OR (g3.barcode = '{$getData['name']}')
                    OR
                    (g4.model_no LIKE '%{$getData['name']}%') OR (g4.barcode = '{$getData['name']}')
                    ) ";
            }
            if (!empty($getData['size'])) {
                $sizeCol = " AND ((sc1.id = '{$getData['size']}') OR (sc2.id = '{$getData['size']}') OR (sc3.id = '{$getData['size']}') OR (sc4.id = '{$getData['size']}'))";
            }
            if (!empty($getData['color'])) {
                $color = " AND ((cp1.id = '{$getData['color']}') OR (cp2.id = '{$getData['color']}') OR (cp3.id = '{$getData['color']}') OR (cp4.id = '{$getData['color']}'))";
            }
            $sql = "select gi.quantity as q1,
                           gi2.quantity as q2,
                           gi3.quantity as q3, 
                           g.id,
                           g.status,
                           g.barcode,
                           g.barcode1,
                           g.barcode2,
                           g.size_collection,
                           g.color_collection,
                           g.color_name,
                           g.properties,
                           g.desc1,
                           g.brand2,
                           g.brand3,
                           g.model_no,
                           g.model_id,
                           g.model_var, 
                           g.type,
                           g.name,
                           g.package_code 
                           from goods g
                               left join goods_items gi on g.id = gi.parent
                               left join goods g2 on g2.id = gi.child
                               left join  goods_items gi2 on g2.id = gi2.parent
                               left join goods g3 on gi2.child = g3.id
                               left join goods_items gi3 on g3.id = gi3.parent
                               left join goods g4 on gi3.child = g4.id
                               left join size s1 on g.size = s1.id
                               left join size_col_rel_size scrs1 on scrs1.size_id = s1.id
                               left join size_collections sc1 on scrs1.sc_id = sc1.id
                               left join size s2 on g2.size = s2.id
                               left join size_col_rel_size scrs2 on scrs2.size_id = s2.id
                               left join size_collections sc2 on scrs2.sc_id = sc2.id
                               left join size s3 on g3.size = s3.id
                               left join size_col_rel_size scrs3 on scrs3.size_id = s3.id
                               left join size_collections sc3 on scrs3.sc_id = sc3.id
                               left join size s4 on g4.size = s4.id
                               left join size_col_rel_size scrs4 on scrs4.size_id = s4.id
                               left join size_collections sc4 on scrs4.sc_id = sc4.id
                               left join color_pantone cp1 on g.color = cp1.id 
                               left join color_pantone cp2 on g2.color = cp2.id 
                               left join color_pantone cp3 on g3.color = cp3.id 
                               left join color_pantone cp4 on g4.color = cp4.id 
                           where g.status = 1 AND g.type=%d %s %s %s GROUP BY g.id, g2.id, g3.id, g4.id ORDER BY s1.order ASC LIMIT %d OFFSET %d;";
            $sql = sprintf($sql, $typeNumber, $modelNo, $sizeCol, $color, $getData['limit'], $getData['offset']);
            $queries = Yii::$app->db->createCommand($sql)->queryAll();
            $result = [];
            $gather = [];

            if (!empty($queries)) {
                foreach ($queries as $key => $item) {
                    if (!empty($item['q1']) && empty($item['q2']) && empty($item['q3'])) {
                        $result[$item['id']]['sum'] += $item['q1'];
                    }
                    if (!empty($item['q1']) && !empty($item['q2']) && empty($item['q3'])) {
                        $result[$item['id']]['sum'] += $item['q1'] * $item['q2'];
                    }
                    if (!empty($item['q1']) && !empty($item['q2']) && !empty($item['q3'])) {
                        $result[$item['id']]['sum'] += $item['q1'] * $item['q2'] * $item['q3'];
                    }
                    $result[$item['id']]['data'] = $item;
                }
                foreach ($result as $key => $item) {
                    $item['data']['sum_qty'] = $item['sum'];
                    $gather[] = $item['data'];
                }
            }
            $query = $gather;
        }
        if (!empty($query)) {
            $response['message'] = "OK";
        }
        $response['data'] = $query;
        return $response;
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionSearchNastel(){
        $type = 'list';
        $getData = $this->conditions(Yii::$app->request->get());
        if(!empty($getData['type'])){
            $type = $getData['type'];
        }
        $sql = "";
        switch ($type){
            case 'list':
                $conditionNastel = "";
                if(!empty($getData['nastel'])){
                    $conditionNastel = " AND tdi.nastel_party_no LIKE '%{$getData['nastel']}%'";
                }
                $sql = "SELECT td.id,
                       tdi.nastel_party_no as nastel_no,
                       SUM(tdi.quantity) as quantity,
                       ml.article as model,
                       m.name as customer 
                FROM tikuv_doc td 
                LEFT JOIN tikuv_doc_items tdi ON tdi.tikuv_doc_id = td.id
                LEFT JOIN musteri m on td.musteri_id = m.id   
                LEFT JOIN model_rel_doc mrd on  mrd.id = (select mrd2.id from model_rel_doc mrd2 where mrd2.tikuv_doc_id = td.id limit 1)
                LEFT JOIN models_list ml on ml.id = mrd.model_list_id
                WHERE td.status > 2 AND td.is_combined = 1 AND td.is_change_model = 2 AND td.type = 1 %s GROUP BY td.id ORDER BY td.id DESC LIMIT %d OFFSET %d;";
                $sql = sprintf($sql, $conditionNastel, $getData['limit'], $getData['offset']);
                break;
            case 'single':
                $sql = "SELECT td.id,
                               s.name as sizeName, 
                               tdi.nastel_party_no as nastel_no,
                               tdi.quantity as quantity,
                               m.name as customer,
                               GROUP_CONCAT(cp.code SEPARATOR ', ') as model_var,
                               GROUP_CONCAT(DISTINCT ml.article SEPARATOR ', ') as model 
                FROM tikuv_doc_items tdi 
                LEFT JOIN tikuv_doc td on tdi.tikuv_doc_id = td.id
                LEFT JOIN size s on tdi.size_id = s.id   
                LEFT JOIN musteri m on td.musteri_id = m.id   
                LEFT JOIN model_rel_doc mrd on mrd.tikuv_doc_id = td.id
                LEFT JOIN models_list ml on ml.id = mrd.model_list_id
                LEFT JOIN models_variations mv on mrd.model_var_id = mv.id
                LEFT JOIN color_pantone cp on mv.color_pantone_id = cp.id
                WHERE td.status > 2 AND td.is_combined = 1 AND 
                      tdi.is_combined = 1 AND td.is_change_model = 2 AND td.type = 1 AND tdi.nastel_party_no = '%s' GROUP BY s.id ORDER BY s.`order`;";
                $sql = sprintf($sql, $getData['nastel']);
                break;
        }

        $response = [];
        $response['status'] = false;
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            $response['status'] = true;
            $response['items'] = $result;
        }
        return $response;
    }

    public function actionSearchCombineNastel(){
        $type = 'list';
        $getData = $this->conditions(Yii::$app->request->get());
        if(!empty($getData['type'])){
            $type = $getData['type'];
        }
        $sql = "";
        switch ($type){
            case 'list':
                $conditionNastel = "";
                if(!empty($getData['nastel'])){
                    $conditionNastel = " AND tdi.nastel_party_no LIKE '%{$getData['nastel']}%'";
                }
                $sql = "select * from tikuv_package_item_balance";
                $sql = "SELECT td.id,
                       tdi.nastel_party_no as nastel_no,
                       SUM(tdi.quantity) as quantity,
                       ml.article as model,
                       m.name as customer 
                FROM tikuv_doc td 
                LEFT JOIN tikuv_doc_items tdi ON tdi.tikuv_doc_id = td.id
                LEFT JOIN musteri m on td.musteri_id = m.id   
                LEFT JOIN model_rel_doc mrd on  mrd.id = (select mrd2.id from model_rel_doc mrd2 where mrd2.tikuv_doc_id = td.id limit 1)
                LEFT JOIN models_list ml on ml.id = mrd.model_list_id
                WHERE td.status > 2 AND td.is_combined = 1 AND td.is_change_model = 2 AND td.type = 1 %s GROUP BY td.id ORDER BY td.id DESC LIMIT %d OFFSET %d;";
                $sql = sprintf($sql, $conditionNastel, $getData['limit'], $getData['offset']);
                break;
            case 'single':
                $sql = "SELECT td.id,
                               s.name as sizeName, 
                               tdi.nastel_party_no as nastel_no,
                               tdi.quantity as quantity,
                               m.name as customer,
                               GROUP_CONCAT(cp.code SEPARATOR ', ') as model_var,
                               GROUP_CONCAT(DISTINCT ml.article SEPARATOR ', ') as model 
                FROM tikuv_doc_items tdi 
                LEFT JOIN tikuv_doc td on tdi.tikuv_doc_id = td.id
                LEFT JOIN size s on tdi.size_id = s.id   
                LEFT JOIN musteri m on td.musteri_id = m.id   
                LEFT JOIN model_rel_doc mrd on mrd.tikuv_doc_id = td.id
                LEFT JOIN models_list ml on ml.id = mrd.model_list_id
                LEFT JOIN models_variations mv on mrd.model_var_id = mv.id
                LEFT JOIN color_pantone cp on mv.color_pantone_id = cp.id
                WHERE td.status > 2 AND td.is_combined = 1 AND 
                      tdi.is_combined = 1 AND td.is_change_model = 2 AND td.type = 1 AND tdi.nastel_party_no = '%s' GROUP BY s.id ORDER BY s.`order`;";
                $sql = sprintf($sql, $getData['nastel']);
                break;
        }

        $response = [];
        $response['status'] = false;
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            $response['status'] = true;
            $response['items'] = $result;
        }
        return $response;
    }

    public function actionCombinedNastel(){
        $type = Yii::$app->request->get('type','list');
        $data = Yii::$app->request->post();
        $response = [];
        $response['status'] = false;
        if($type == 'list'){
            $all = join(',', $data['combined']);
            $sql = "select td.id,
                       m.name as customer,
                       s.name as sizeName,
                       s.id as sizeId, 
                       ml.article as model,
                       (select tdi3.nastel_party_no from tikuv_doc_items tdi3 where  tdi3.tikuv_doc_id = %d limit 1) as nastel_no, 
                       (select tdi2.quantity from tikuv_doc_items tdi2 where tdi2.tikuv_doc_id = %d limit 1) as quantity
                from tikuv_doc td
                left join tikuv_doc_items tdi on td.id = tdi.tikuv_doc_id
                left join musteri m on td.musteri_id = m.id
                left join model_rel_doc mrd on td.id = mrd.tikuv_doc_id
                left join models_list ml on mrd.model_list_id = ml.id
                left join models_variations mv on mrd.model_var_id = mv.id
                left join color_pantone cp on mv.color_pantone_id = cp.id   
                left join size s on tdi.size_id = s.id
                where td.id IN (%s) AND td.is_combined = 1 AND td.is_change_model = 2
                GROUP BY s.id ORDER BY s.name;";
            $sql = sprintf($sql, $data['main'], $data['main'], $all);
            $results = Yii::$app->db->createCommand($sql)->queryAll();
            if(!empty($results)){
                $response['items'] = $results;
                $response['status'] = true;
            }
        }else{
            $combined = $data['data'];
            $main = $data['main'];
            $combinedId = $data['combined'];
            if(!empty($combinedId)){
                $ids = join(',', $combinedId);
                $ids .=",".$main;
                $sql = "select ml.id as model_id,
                               mv.id as model_var,
                               mrd.order_item_id,
                               mrd.order_id,
                               mrd.price,
                               mrd.pb_id
                        from tikuv_doc td
                        left join model_rel_doc mrd on td.id = mrd.tikuv_doc_id
                        left join models_list ml on mrd.model_list_id = ml.id
                        left join models_variations mv on mrd.model_var_id = mv.id
                        where td.id IN (%s) GROUP BY ml.id, mv.id;";
                $sql = sprintf($sql, $ids);
                $mrd = Yii::$app->db->createCommand($sql)->queryAll();
                if(!empty($mrd)){

                    $transaction = Yii::$app->db->beginTransaction();
                    try{
                        $saved = false;
                        TikuvDoc::updateAll(['is_combined' => 2,'combined_nastel' => join(',', $combinedId)],['id' => $combined]);
                        ModelRelDoc::deleteAll(['tikuv_doc_id' => $main]);
                        $mainTD = TikuvDoc::findOne(['id' => $main]);
                        if($mainTD !== null){
                            $mainTD->combined_nastel = join(',', $combinedId);
                            if($mainTD->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                            }
                        }
                        foreach ($mrd as $item) {
                            $modelMRD = new ModelRelDoc();
                            $modelMRD->setAttributes([
                                'model_list_id' => $item['model_id'],
                                'model_var_id' => $item['model_var'],
                                'order_id' => $item['order_id'],
                                'order_item_id' => $item['order_item_id'],
                                'price' => $item['price'],
                                'pb_id' => $item['pb_id'],
                                'tikuv_doc_id' => $main,
                            ]);
                            if($modelMRD->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                        if($saved){
                            //TikuvDocItems::updateAll(['is_combined' => 2],['tikuv_doc_id' => $main]);
                            $oneTDI = TikuvDocItems::find()->where(['tikuv_doc_id' => $main])->asArray()->one();
                            TikuvSliceItemBalance::updateAll(['is_combined' =>2],"doc_id IN ({$ids})");
                            $lastRecOne = TikuvSliceItemBalance::find()->where(['nastel_no' => $oneTDI['nastel_party_no']])->asArray()->one();
                            foreach ($combined as $item){
                                if(!empty($item['quantity']) && $item['quantity'] > 0){
                                    $params = [
                                        'nastel_no' => $oneTDI['nastel_party_no'],
                                        'size_id' => $item['sizeId'],
                                        'is_combined' => 1
                                    ];
                                    $modelTSIB = TikuvSliceItemBalance::getLastCombinedRecord($params);
                                    if($modelTSIB){
                                        $modelTSIB->count = $item['quantity'];
                                        $modelTSIB->inventory = $item['quantity'];
                                    }else{
                                        $modelTSIB = new TikuvSliceItemBalance();
                                        $inventory = $item['quantity'];
                                        $depId = $lastRecOne['department_id'];
                                        $depFrom = $lastRecOne['from_department'];
                                        $depTo = $lastRecOne['to_department'];
                                        $musteri = $lastRecOne['musteri_id'];
                                        $modelTSIB->setAttributes([
                                            'size_id' => $item['sizeId'],
                                            'nastel_no' => $oneTDI['nastel_party_no'],
                                            'count' => $item['quantity'],
                                            'inventory' => $inventory,
                                            'doc_id' => $main,
                                            'department_id' => $depId,
                                            'from_department' => $depFrom,
                                            'to_department' => $depTo,
                                            'musteri_id' => $musteri,
                                            'is_combined' => 1
                                        ]);
                                    }


                                    $modelTDI = TikuvDocItems::find()->where([
                                        'tikuv_doc_id' => $main,
                                        'size_id' => $item['sizeId'],
                                        'nastel_party_no' => $oneTDI['nastel_party_no']
                                    ])->one();
                                    if($modelTDI !== null){
                                          $modelTDI->quantity = $item['quantity'];
                                    }else{
                                        $modelTDI = new TikuvDocItems();
                                        $modelTDI->setAttributes([
                                            'tikuv_doc_id' => $main,
                                            'size_id' => $item['sizeId'],
                                            'quantity' => $item['quantity'],
                                            'work_weight' =>$oneTDI['work_weight'],
                                            'nastel_party_no' => $oneTDI['nastel_party_no']
                                        ]);
                                    }

                                    if($modelTDI->save() && $modelTSIB->save()){
                                        $saved = true;
                                    }else{
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                        }
                        if($saved){
                            $transaction->commit();
                            $response['status'] = true;
                            $response['message'] = Yii::t('app','Nastellar muvofaqqiyatli birlashdi');
                        }
                    }catch (\Exception $e){

                    }
                }
            }
        }
        return $response;
    }

    public function actionFilters()
    {
        $response = [];
        $response['status'] = true;
        $size = "select id, name from size_collections where status = 1 ORDER BY name ASC;";
        $color = "select id, code from color_pantone cp where cp.status = 1 ORDER BY name ASC;";
        $response['size'] = Yii::$app->db->createCommand($size)->queryAll();
        $response['color'] = Yii::$app->db->createCommand($color)->queryAll();
        return $response;
    }

    public function actionColor()
    {
        $response = [];
        $name = Yii::$app->request->get('name');
        $response['status'] = true;
        $color = "select id, code from color_pantone cp where cp.code LIKE '%{$name}%' AND cp.status = 1 ORDER BY name ASC LIMIT 20;";
        $response['data'] = Yii::$app->db->createCommand($color)->queryAll();
        return $response;
    }

    public function actionWrapperItem(){
        $data = Yii::$app->request->post();
        $response = [];
        $response['status'] = false;

        $model = [];
        $out = [];

        if(!empty($data['id'])){
            $type = 1;
            if(!empty($data['type'])){
                $type = $data['type'];
            }
            $sql = "select g.id as g1_id,
                       g.package_code, 
                       g.type as g1_type,
                       gi.quantity as g1_qty,
                       g2.id as g2_id,
                       g2.type as g2_type,
                       gi2.quantity as g2_qty,
                       g3.id as g3_id,
                       g3.type as g3_type, 
                       gi3.quantity as g3_qty,
                       g4.id as g4_id,
                       g4.type as g4_type from goods g
                left join goods_items gi on gi.parent = g.id
                left join goods g2 on g2.id = gi.child
                left join goods_items gi2 on gi2.parent = g2.id
                left join goods g3 on gi2.child = g3.id
                left join goods_items gi3 on gi3.parent = g3.id
                left join goods g4 on gi3.child = g4.id
                left join goods_items gi4 on gi4.parent = g4.id
                where g.id = %d AND g.type = %d;";
            $sql = sprintf($sql,  $data['id'], $type);
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            $packageCode = null;
            if(!empty($result)){
                $packageCode = $result[0]['package_code'];
            }
            $dataGoods = [];
            foreach ($result as $m) {
                if (!empty($m['g1_id']) && $m['g1_type'] > 1) {
                    $qty = $m['g1_qty'];
                    if (!empty($m['g2_id']) && $m['g2_type'] > 1) {
                        $qty = $qty * $m['g2_qty'];
                        if (!empty($m['g3_id']) && $m['g3_type'] > 1) {
                            if (array_key_exists($m['g4_id'], $dataGoods)) {
                                $dataGoods[$m['g4_id']] += $qty * $m['g3_qty'];
                            } else {
                                $dataGoods[$m['g4_id']] = $qty * $m['g3_qty'];
                            }
                        } else {
                            if (array_key_exists($m['g3_id'], $dataGoods)) {
                                $dataGoods[$m['g3_id']] += $qty;
                            } else {
                                $dataGoods[$m['g3_id']] = $qty;
                            }
                        }
                    } else {
                        if (array_key_exists($m['g2_id'], $dataGoods)) {
                            $dataGoods[$m['g2_id']] += $m['g2_qty'];
                        } else {
                            $dataGoods[$m['g2_id']] = $qty;
                        }
                    }
                } else {
                    if (array_key_exists($m['g1_id'], $dataGoods)) {
                        $dataGoods[$m['g1_id']] += $m['g1_qty'];
                    } else {
                        $dataGoods[$m['g1_id']] = $m['g1_qty'];
                    }
                }
            }
            $goodsId = [];
            if (!empty($dataGoods)) {
                foreach ($dataGoods as $id => $quantity) {
                    if (!array_key_exists($id, $goodsId)) {
                        $goodsId[$id] = null;
                        $sql = "select g.name,
                                       g.id, 
                                       g.barcode,
                                       g.barcode1,
                                       g.barcode2,
                                       g.model_no,
                                       s.name  as sizeName,
                                       s.order as sizeOrder, 
                                       ml.long_name,
                                       b2.name as brandName2,
                                       b3.name as brandName3,
                                       ms.name as seasonName,
                                       mv.name as genderName, 
                                       cp.code as colorName,
                                       att.path,
                                       ml.add_info,
                                       g.package_code as packageCode 
                            from goods g
                                     left join size s on g.size = s.id
                                     left join brend b2 on g.brand2 = b2.id  
                                     left join brend b3 on g.brand3 = b3.id  
                                     left join color_pantone cp on g.color = cp.id
                                     inner join models_list ml on g.model_id = ml.id
                                     left join model_view mv on ml.view_id = mv.id
                                     left join model_season ms on ml.model_season = ms.id
                                     left join model_rel_attach mra on ml.id = mra.model_list_id
                                     left join attachments att on mra.attachment_id = att.id
                            where g.id = %d AND mra.is_main = 1
                            GROUP BY g.id  LIMIT 1;";
                        $sql = sprintf($sql, $id);
                        $goods = Yii::$app->db->createCommand($sql)->queryOne();
                        if (!empty($goods)) {
                            $model = [
                                'name'    => $goods['name'],
                                'id'      => $goods['id'],
                                'addInfo' => $goods['add_info'],
                                'model_no' => $goods['model_no'],
                                'path' => 'http://'.$_SERVER['HTTP_HOST'].'/'.$goods['path'],
                                'longName' => $goods['long_name'],
                                'seasonName' => $goods['seasonName'],
                                'genderName' => $goods['genderName'],
                                'packageCode' => $packageCode
                            ];
                            $out[] = [
                                'barcode1' => $goods['barcode'],
                                'sizeOrder' =>$goods['sizeOrder'],
                                'barcode2' => $goods['barcode1'],
                                'barcode3' => $goods['barcode2'],
                                'quantity' => $quantity,
                                'sizeName' => $goods['sizeName'],
                                'brandName2' => $goods['brandName2'],
                                'brandName3' => $goods['brandName3'],
                                'colorName' => $goods['colorName'],
                            ];
                        }
                    }
                }
            }
        }
        ArrayHelper::multisort($out,'sizeOrder');
        $response = [
            'status' => true,
            'data' => $out,
            'model' => $model
        ];
        return $response;
    }

    public function actionAdd()
    {
        $data = Yii::$app->request->post();
        $type = Yii::$app->request->get('type', 4);
        $sum = Yii::$app->request->get('sum', 0);
        $name = "";
        $models = [];
        $lastGoods = null;
        $goods_id = null;
        $barcode = null;
        $model_id = null;
        $nameSize = "";
        $nameColor = "";
        $sabu = "";
        $color_collection = "";

        $model_no = null;
        if (!empty($data)) {
            $model = null;
            $size = [];
            $color = [];
            $checkData = [];
            $checkData['size'] = [];
            $checkData['color'] = [];
            $checkData['model'] = [];
            $checkData['qty'] = [];

            $data = array_filter($data);
            ArrayHelper::multisort($data, ['sizeName'], [SORT_ASC]);
            if ($type == 1) {
                foreach ($data as $item) {
                    $size[$item['sizeId']] = $item['sizeName'];
                    $color[$item['colorId']] = $item['pantone'];
                    $model_id = $item['model_id'];
                    $model_no = $item['model_no'];
                    $model[$item['model_id']] = $item['model_no'];
                    $models[$item['model_id']] = $item['model_no'];
                    $sabu = $item['package_code'];
                }
            } else {
                $ids = [];
                foreach ($data as $item) {
                    if ($item['type'] == 1) {
                        $size[$item['sizeId']] = $item['sizeName'];
                        $color[$item['colorId']] = $item['pantone'];
                        $checkData['size'][$item['sizeId']] = $item['sizeId'];
                        $checkData['color'][$item['colorId']] = $item['colorId'];
                        $checkData['qty'][$item['sizeId']] = $item['quantity'];
                        $model_id = $item['model_id'];
                        $model_no = $item['model_no'];
                    } else {
                        $ids[$item['id']] = $item['id'];
                    }
                    if(!empty($item['package_code'])){
                        $sabu = $item['package_code'];
                    }
                    if(!empty($item['color_collection'])){
                        $color_collection = $item['color_collection'];
                    }
                    $model[$item['model_id']] = $item['model_no'];
                    $models[$item['model_id']] = $item['model_no'];
                    $checkData['model'][$item['model_id']] = $item['model_id'];
                }
                if (!empty($ids) && $type > 2) {
                    $id = join(',', $ids);
                    $ids_sub = [];
                    $size = [];
                    $color = [];
                    $sql = "select g.*,
                                   s.name as sizeName,
                                   s.id as sizeId,
                                   cp.id as colorId,
                                   cp.code as color, 
                                   gi.quantity,
                                   g.package_code 
                                   from goods g
                            left join goods_items gi on g.id = gi.child
                            left join size s on g.size = s.id
                            left join color_pantone cp on cp.id = g.color
                            where gi.parent in (%s) ORDER BY s.`order` ASC";
                    $sql = sprintf($sql, $id);
                    $res = Yii::$app->db->createCommand($sql)->queryAll();
                    foreach ($res as $item) {
                        if ($item['type'] == 1) {
                            $size[$item['sizeId']] = $item['sizeName'];
                            $color[$item['colorId']] = $item['color'];
                            $model_id = $item['model_id'];
                            $model_no = $item['model_no'];
                            $checkData['size'][$item['sizeId']] = $item['sizeId'];
                            $checkData['color'][$item['colorId']] = $item['colorId'];
                            $checkData['qty'][$item['sizeId']] = $item['quantity'];
                        } else {
                            $ids_sub[] = $item['id'];
                        }
                        $model[$item['model_id']] = $item['model_no'];
                        if(!empty($item['package_code'])){
                            $sabu = $item['package_code'];
                        }
                        if(!empty($item['color_collection'])){
                            $color_collection = $item['color_collection'];
                        }
                        $models[$item['model_id']] = $item['model_no'];
                        $checkData['model'][$item['model_id']] = $item['model_id'];
                    }
                    if (!empty($ids_sub) && $type > 3) {
                        $id = join(',', $ids_sub);
                        $size = [];
                        $color = [];
                        $sql = "select g.*,
                                   s.name as sizeName,
                                   s.id as sizeId,
                                   cp.id as colorId,
                                   cp.code as color,
                                   g.package_code, 
                                   gi.quantity from goods g
                                    left join goods_items gi on g.id = gi.child
                                    left join size s on g.size = s.id
                                    left join color_pantone cp on cp.id = g.color
                            where gi.parent in (%s) ORDER BY s.`order` ASC";
                        $sql = sprintf($sql, $id);
                        $res = Yii::$app->db->createCommand($sql)->queryAll();
                        foreach ($res as $item) {
                            if ($item['type'] == 1) {
                                $size[$item['sizeId']] = $item['sizeName'];
                                $color[$item['colorId']] = $item['color'];
                                $model_id = $item['model_id'];
                                $model_no = $item['model_no'];
                                $checkData['size'][$item['sizeId']] = $item['sizeId'];
                                $checkData['color'][$item['colorId']] = $item['colorId'];
                                $checkData['qty'][$item['sizeId']] = $item['quantity'];
                            }
                            $model[$item['model_id']] = $item['model_no'];
                            if(!empty($item['package_code'])){
                                $sabu = $item['package_code'];
                            }
                            if(!empty($item['color_collection'])){
                                $color_collection = $item['color_collection'];
                            }
                            $models[$item['model_id']] = $item['model_no'];
                            $checkData['model'][$item['model_id']] = $item['model_id'];
                        }
                    }
                }
            }
            $typeLabel = "Qop";
            switch ($type) {
                case 3:
                    $typeLabel = "Blok";
                    break;
                case 2:
                    $typeLabel = "Paket";
                    break;
                case 1:
                    $typeLabel = "Maxsulot";
                    break;
            }
            $name = "(" . $typeLabel . ")-" . join(",", $model) . " - " . join(",", $color) . " - (" . join(",", $size) . ") - {$sum} шт.";
            if(!empty($sabu)){
                $name = "({$sabu})"."(" . $typeLabel . ")-" . join(",", $model) . " - " . join(",", $color) . " - (" . join(",", $size) . ") - {$sum} шт.";
            }
            $nameSize = join(",", $size);
            $nameColor = join(",", $color);

            $lastGoods = Goods::find()
                ->where(['name'=>$name])
                ->asArray()
                ->one();
            $mGoods = new Goods();
            if (empty($lastGoods)) {
                $lastGoods = Goods::find()
                    ->select(['barcode'])
                    ->asArray()
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if(!empty($sabu)){
                    $sabuModel = Goods::find()->where(['package_code' => $sabu])->asArray()->one();
                    if(!empty($sabuModel)){
                        $nameColor = $sabuModel['color_collection'];
                    }
                }
                $mGoods->setAttributes([
                    'barcode' => $lastGoods['barcode'] + 1,
                    'name' => $name,
                    'size_collection' => $nameSize,
                    'color_collection' => $nameColor,
                    'model_no' => $model_no,
                    'model_id' => $model_id,
                    'type' => $type,
                    'package_code' => $sabu
                ]);
                if ($mGoods->save()) {
                    $goods_id = $mGoods->id;
                    $barcode = $mGoods->barcode;
                    $model_id = $mGoods->model_id;
                    foreach ($data as $item) {
                        $mGoodsItem = new GoodsItems();
                        $mGoodsItem->setAttributes([
                            'parent' => $mGoods->id,
                            'child' => $item['id'],
                            'quantity' => $item['quantity'],
                            'type' => $type
                        ]);
                        if ($mGoodsItem->save()) {

                        }
                    }
                }
            }else{
                $goods_id = $lastGoods['id'];
                $barcode = $lastGoods['barcode'];
                $model_id = $lastGoods['model_id'];
            }
        }
        if ($goods_id) {
            return ['status' => true,
                'data' => [
                    'id' => $goods_id,
                    'sizeName' => $nameSize,
                    'size_collection' => $nameSize,
                    'color_collection' => $nameColor,
                    'pantone' => $nameColor,
                    'name' => $name,
                    'sum_qty' => $sum,
                    'model_no' => $model_no,
                    'model_id' => $model_id,
                    'barcode' => $barcode,
                    'type' => $type,
                    'sabu' => $sabu
                ]
            ];
        } else {
            return ['status' => false];
        }
    }

    public function actionGoodsItem()
    {
        $goods_id = Yii::$app->request->get('id', null);
        if ($goods_id) {
            $sql = "select g.id,
                           s.name  as sizeName,
                           cp.code as pantone
                    from goods g
                             left join size_type st on st.id = g.size_type
                             left join size s on g.size = s.id
                             left join color_pantone cp on cp.id = g.color
                             join (select gi.quantity
                                   from goods_items gi
                                   left join goods g on g.id = gi.parent
                                   where gi.parent = %d) as gsum
                      WHERE g.id IN (select gi.child
                                   from goods_items gi
                                   left join goods g on g.id = gi.parent
                                   where gi.parent = %d)
                    GROUP BY g.id
                    ORDER BY sizeName;";

            $sql = sprintf($sql, $goods_id, $goods_id);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            $data = [];
            $data['sizeName'] = "";
            $data['pantone'] = "";
            foreach ($res as $key => $item) {
                $data['sizeName'] .= $item['sizeName'];
                $data['pantone'] .= $item['pantone'];
                if ($key !== array_key_last($res)) {
                    $data['sizeName'] .= ", ";
                    $data['pantone'] .= ", ";
                }
            }
            return ['status' => true, 'data' => $data];
        } else {
            return ['status' => false];
        }
    }

    public function actionPack()
    {
        $model_id = Yii::$app->request->get('model', null);
        $goods_id = Yii::$app->request->get('id', null);
        if ($model_id && $goods_id) {
            $sql = "select  ml.long_name as name,
                            g.package_code,
                            GROUP_CONCAT(DISTINCT CONCAT(trmc.percentage,'% ',ft.name_ru) SEPARATOR ', ') as sostav 
                     from goods g
                     left join models_list ml on g.model_id = ml.id
                     left join models_raw_materials mrm on ml.id = mrm.model_list_id
                     left join toquv_raw_materials trm on mrm.rm_id = trm.id
                     left join toquv_raw_material_consist trmc on trm.id = trmc.raw_material_id
                     left join fabric_types ft on trmc.fabric_type_id = ft.id
                     WHERE ml.id = :model AND g.id = :gid AND mrm.is_main = 1
                     GROUP BY g.id;";
            $res = Yii::$app->db->createCommand($sql)->bindValues(['model' => $model_id, 'gid' => $goods_id])->queryOne();
            $sostav = "";
            $fullname = "";
            $packageCode = "";
            if (!empty($res)) {
                $sostav = $res['sostav'];
                $fullname = $res['name'];
                $packageCode = $res['package_code'];
            }
            return ['status' => true, 'data' => ['packageCode' => $packageCode, 'name' => $fullname, 'sostav' => $sostav, 'datetime' => date('d.m.Y H:i:s')]];
        }
        return ['status' => false];
    }

    public function actionSaveBarcode()
    {
        $data = Yii::$app->request->post();

        $model = Goods::findOne(['id' => $data['id']]);
        $response = [];
        $response['status'] = false;
        if ($model !== null) {

            if (!empty($data['data']['bc']) && !empty($data['data']['index'])) {
                $checkBarcode = Goods::find()->where(['OR',
                    ['barcode'  => $data['data']['bc']],
                    ['barcode1' => $data['data']['bc']],
                    ['barcode2' => $data['data']['bc']],
                ])->asArray()->exists();
                if($checkBarcode){
                   return ['status' => false, 'message' => Yii::t('app','Buday barkod mavjud')];
                }
                $model->{'barcode'.((int)$data['data']['index']-1)} = $data['data']['bc'];
                $model->{'brand'.$data['data']['index']} = $data['data']['brand'];
            }
            if ($model->save()) {
                $sql = "select  ml.long_name as name, 
                            GROUP_CONCAT(DISTINCT CONCAT(trmc.percentage,'% ',ft.name_ru) SEPARATOR ', ') as sostav 
                     from goods g
                     left join models_list ml on g.model_id = ml.id
                     left join models_raw_materials mrm on ml.id = mrm.model_list_id
                     left join toquv_raw_materials trm on mrm.rm_id = trm.id
                     left join toquv_raw_material_consist trmc on trm.id = trmc.raw_material_id
                     left join fabric_types ft on trmc.fabric_type_id = ft.id
                     WHERE ml.id = :model AND g.id = :gid AND mrm.is_main = 1
                     GROUP BY g.id;";
                $res = Yii::$app->db->createCommand($sql)->bindValues(['model' => $data['model_id'], 'gid' => $data['id']])->queryOne();
                $sostav = "";
                $fullname = "";
                if (!empty($res)) {
                    $sostav = $res['sostav'];
                    $fullname = $res['name'];
                }
                return [
                    'status' => true,
                    'message' => Yii::t('app','Muvaffaqqiyatli bajarildi'),
                    'data' => [
                        'name' => $fullname,
                        'barcode' => $model->barcode,
                        'barcode1' => $model->barcode1,
                        'barcode2' => $model->barcode2,
                        'brand2' => $model->brand2,
                        'brand3' => $model->brand3,
                        'sostav' => $sostav,
                        'datetime' => date('d.m.Y H:i:s')
                    ]
                ];
            }
        }
        return $response;
    }

    public function actionBrands()
    {
        $response = [];
        $response['status'] = false;
        $data = Yii::$app->request->get('gid');
        if(!empty($data)){
            $brands = BarcodeCustomers::find()->select(['id', 'name'])->asArray()->orderBy(['name' => SORT_ASC])->all();
            $sql = "select b.name,
                          gb.barcode,
                          gb.number  
                    from goods_barcode gb
                    left join barcode_customers b on gb.bc_id = b.id
                    where gb.goods_id = %d ;";
            $sql = sprintf($sql,$data);
            $gb = Yii::$app->db->createCommand($sql)->queryAll();
            $response['brands'] = $brands;
            $response['gb'] = $gb;
            $response['status'] = true;
        }
        return $response;
    }

    public function actionGetGoodsViaSize(){
        $response = [];
        $response['status'] = false;
        $data = Yii::$app->request->post();
        $sql = null;
        $brands = [];
        if(!empty($data) && !empty($data['model_id']) && !empty($data['type']) && $data['type'] > 1){
            $brands = BarcodeCustomers::find()->select(['id', 'name'])->asArray()->orderBy(['name' => SORT_ASC])->all();
            $sql = "select bc.name as brand,
                           gb.barcode,
                           g.name, 
                           gb.number,
                           g.id as gid,
                           s.name as sizeName,
                           s.id as sid,
                           cp.code as colorCode,
                           g.model_no,
                           cp.name_ru,
                           g.model_id,
                           g.model_var,
                           g.color_collection,
                           g.size_collection,
                           g.type 
                    from goods g
                    left join size s on g.size = s.id
                    left join goods_barcode gb on g.id = gb.goods_id
                    left join barcode_customers bc on gb.bc_id = bc.id
                    left join color_pantone cp on g.color = cp.id
                    where g.id = %d ORDER BY bc.name, s.order;";
            $sql = sprintf($sql, $data['id']);
        }elseif(!empty($data) && !empty($data['model_id']) && !empty($data['model_var']) && !empty($data['sizeTypeId'])){
            $brands = BarcodeCustomers::find()->select(['id', 'name'])->asArray()->orderBy(['name' => SORT_ASC])->all();
            $sql = "select bc.name as brand,
                           gb.barcode,
                           gb.number,
                           g.name, 
                           g.id as gid,
                           s.name as sizeName,
                           s.id as sid,
                           cp.code as colorCode,
                           g.model_no,
                           cp.name_ru,
                           g.model_id,
                           g.model_var,
                           g.color_collection,
                           g.size_collection,
                           g.type 
                    from goods g
                    left join size s on g.size = s.id
                    left join goods_barcode gb on g.id = gb.goods_id
                    left join barcode_customers bc on gb.bc_id = bc.id
                    left join color_pantone cp on g.color = cp.id
                    where g.model_id = %d AND g.model_var = %d AND size_type = %d ORDER BY bc.name, s.order;";
            $sql = sprintf($sql, $data['model_id'], $data['model_var'], $data['sizeTypeId']);
        }
        if(!empty($sql)){
            $results = Yii::$app->db->createCommand($sql)->queryAll();
            $out = [];
            $filtered = [];
            $sizeData = [];
            if(!empty($results)){
                foreach ($results as $result){
                    if(!empty($result['barcode'])){
                        array_push($filtered, $result);
                    }
                    if(!array_key_exists($result['sid'], $sizeData)){
                        $sizeData[$result['sid']] = null;
                        array_push($out, [
                            'gid' => $result['gid'],
                            'modelNo' => $result['model_no'],
                            'colorCode' => $result['colorCode'],
                            'sizeId' => $result['sid'],
                            'sizeName' => $result['sizeName'],
                            'sizeCollection' => $result['size_collection'],
                            'colorCollection' => $result['color_collection'],
                            'type' => $result['type'],
                            'modelId' => $result['model_id'],
                            'modelVar' => $result['model_var']
                        ]);
                    }
                }
                $response['status'] = true;
                $response['brands'] = $brands;
                $response['gb']['items'] = $filtered;
                $response['gb']['sizes'] = $out;
            }
        }
        return $response;
    }

    public function actionSaveNewBarcode()
    {
        $data = Yii::$app->request->post();
        $response = [];
        $response['status'] = false;
        if(!empty($data)){
            try{
                $saved = false;
                $transaction = Yii::$app->db->beginTransaction();

                foreach ($data['items'] as $item){
                    if(!empty($item['barcode'])){
                        $goods = GoodsBarcode::find()->where([
                            'bc_id' => $data['brandId'],
                            'barcode' => $item['barcode'],
                            'goods_id' => $item['gid']])->asArray()->exists();
                        if(!$goods){
                            $gbModel = new GoodsBarcode();
                            $gbModel->setAttributes([
                                'barcode' => $item['barcode'],
                                'goods_id' => $item['gid'],
                                'bc_id' => $data['brandId']
                            ]);
                            if($gbModel->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                }
                if($saved){
                    $transaction->commit();
                    $response['status'] = true;
                    $response['message'] = Yii::t('app','Muvaffaqiyatli bajarildi');
                }else{
                    $transaction->rollBack();
                    $response['message'] = Yii::t('app','Bunday barkod mavjud!');
                    $response['status'] = true;
                }

            }catch (\Exception $e){

            }
        }
        return $response;
    }
    

    public function actionSaveProperties(){
        $data = Yii::$app->request->post();
        $response = [];
        $response['status'] = false;
        if(!empty($data)){
            $goods = Goods::findOne($data['id']);
            if($data['type'] > 1){
                if($goods !== null){
                    $goods->properties = $data['properties'];
                    $goods->color_name = $data['color'];
                    if($goods->save()){
                        $response['status'] = true;
                        $response['data'] = $data;
                        $response['message'] = Yii::t('app','Muvaffaqiyatli bajarildi');
                    }
                }
            }else{
                if($goods !== null){
                    $goodsAll = Goods::findAll(['model_id' => $goods->model_id, 'model_var' => $goods->model_var]);
                    $data['changed'] = [];
                    if(!empty($goodsAll)){
                        foreach ($goodsAll as $item) {
                            $item->properties = $data['properties'];
                            $item->color_name = $data['color'];
                            $goods->color_collection = $data['color'];
                            if($item->save()){
                                $data['changed'][$item->id] = $item->id;
                            }
                        }
                    }
                    if(!empty($data['changed'])){
                        $response['status'] = true;
                        $response['data'] = $data;
                        $response['message'] = Yii::t('app','Muvaffaqiyatli bajarildi');
                    }
                }
            }
        }
        return $response;
    }

    /**
     * @return array
     */
    public function actionSaveSomeData(){
        $data = Yii::$app->request->post();
        $response = [];
        $response['status'] = false;
        if(!empty($data)){
            $goods = Goods::findOne(['id' => $data['id']]);
            if($goods !== null){
                if($goods->type > 1){
                    $goods->package_code = $data['packCode'];
                    $packageCode = $goods->name;
                    $goods->color_collection = $data['colorCollection'];
                    $goods->name = "({$data['packCode']}){$packageCode}";
                    if($goods->save()){
                        $response['status'] = true;
                        $response['item'] = $goods;
                        $response['message'] = Yii::t('app','Muvaffaqiyatli bajarildi');
                    }else{
                        Yii::info('Save Error Some data'.$goods->getErrors(),'save');
                    }
                }
            }
        }
        return $response;
    }
}
