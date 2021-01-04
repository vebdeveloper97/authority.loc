<?php

namespace app\api\modules\v1\controllers;

use app\modules\tikuv\models\TikuvOutcomeProducts;
use app\modules\tikuv\models\TikuvOutcomeProductsPack;
use app\modules\tikuv\models\TikuvTopAccepted;
use Yii;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use app\api\modules\v1\components\CorsCustom;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use app\modules\toquv\models\ToquvMakine;
/**
 * Country Controller API
 *
 * @author Omadbek Onorov <omadbek.onorov@gmail.com>
 */
class ProductOLDController extends ActiveController
{
    const USER_NAME = 'SAMO-API-USER';
    const PASSWORD  = 'samo-api-password';
    public $modelClass = 'app\modules\tikuv\models\TikuvOutcomeProducts';
    public $enableCsrfValidation = false;

    public function actions(){
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => CorsCustom::className()
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'pack'       => ['GET','POST', 'HEAD','OPTIONS'],
                    'list'       => ['GET','POST', 'HEAD','OPTIONS'],
                    'total-pack' => ['GET','POST', 'HEAD','OPTIONS'],
                    'accepted'   => ['GET','POST', 'HEAD','OPTIONS'],
                ],
            ],
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
    public function conditions($getData){

        $conditions = [];
        $conditions['page'] = 1;
        $conditions['limit'] = 10;
        $conditions['lang'] = 'uz';
        $conditions['sort'] = 'DESC';
        if(!empty($getData)){
            if(!empty($getData['limit'])){
                $conditions['limit'] = $getData['limit'];
            }
            if(!empty($getData['page'])){
                $conditions['page'] = $getData['page'];
            }
            if(!empty($getData['lang'])){
                $conditions['lang'] = $getData['lang'];
            }
            if(!empty($getData['sort'])){
                $conditions['sort'] = $getData['sort'];
            }
        }
        return $conditions;
    }


    public function actionPack(){

        $auth = Yii::$app->request->post();
        $response['status'] = false;
        $response['message'] = "Auth Error";
        $response['data'] = [];
        $response['total'] = 0;
        $response['auth'] = $auth;

        if(isset($auth['user']) && isset($auth['password'])){
            if($auth['user'] == self::USER_NAME && $auth['password'] == self::PASSWORD){
                $getData = $this->conditions(Yii::$app->request->get());
                $offset = ($getData['page'] - 1)*$getData['limit'];
                $sql = "select topp.id,
                       topp.order_no,
                       topp.doc_id,
                       topp.add_info,
                       topp.reg_date,
                       topp.toquv_partiya,
                       topp.boyoq_partiya,
                       topp.nastel_no,
                       m.name     as musteri,
                       td.name    as department,
                       u.user_fio as username
                from tikuv_outcome_products_pack as topp
                         left join musteri m on topp.musteri_id = m.id
                         left join toquv_departments td on topp.department_id = td.id
                         left join users u on topp.created_by = u.id
                WHERE topp.status = 3
                 order by topp.id DESC LIMIT :limit OFFSET :offset ;";
                $total = Yii::$app->db->createCommand("select COUNT(id) as c from tikuv_outcome_products_pack where status = 3")->queryScalar();
                $machines = Yii::$app->db->createCommand($sql)
                    ->bindValues([
                        'offset' => $offset,
                        'limit' => $getData['limit']
                    ])->queryAll();
                $response = [
                    'status' => true,
                    'message' => 'OK',
                    'data' => $machines,
                    'total' => $total,
                    'auth' => $auth
                ];
            }
        }
        return $response;
    }

    public function actionList(){

        $auth = Yii::$app->request->post();
        $flagUser = false;
        $flagId = false;
        $flagDocId = false;

        if((!isset($auth['user']) || !isset($auth['password'])) || $auth['user'] != self::USER_NAME || $auth['password'] != self::PASSWORD){
                $flagUser = true;
        }
        if(!isset($auth['id']) || empty($auth['id'])){
            $flagId = true;
        }
        if(!isset($auth['doc_id']) || empty($auth['doc_id'])){
            $flagDocId = true;
        }
        if($flagUser){
            $response['status'] = false;
            $response['message'] = "Auth Error";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        if($flagId){
            $response['status'] = false;
            $response['message'] = "Not found package ID";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        if($flagDocId){
            $response['status'] = false;
            $response['message'] = "Not found Doc ID";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        $modelPack = TikuvOutcomeProductsPack::findOne($auth['id']);
        if($modelPack !== null){
            $modelPack->doc_id = $auth['doc_id'];
            $modelPack->save();
        }else{
            $response['status'] = false;
            $response['message'] = "Invalid Package ID";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        $getData = $this->conditions(Yii::$app->request->get());
        $offset = ($getData['page'] - 1)*$getData['limit'];
        $sql = "select top.id,
                       topp.reg_date,
                       top.model_no,
                       top.color_code,
                       top.barcode,
                       top.pechat,
                       top.quantity,
                       top.accepted_quantity,
                       u.code as unit_code,
                       u.name as unit_name,
                       s.name as size_name,
                       s.code  as size_code,
                       st.code as size_type_code,
                       st.name as size_type_name,
                       sn.code as sort_code,
                       sn.name as sort_name,
                       top.pack_id,
                       topp.order_no,
                       (select SUM(accepted) from tikuv_top_accepted where top.id = tikuv_top_accepted.top_id) as accepted_quantity
                from tikuv_outcome_products top
                         left join tikuv_outcome_products_pack topp on top.pack_id = topp.id
                         left join size s on top.size_id = s.id
                         left join size_type st on s.size_type_id = st.id
                         left join sort_name sn on top.sort_type_id = sn.id
                         left JOIN unit u on top.unit_id = u.id
                WHERE topp.id = :packId
                  AND topp.status = 3 ORDER BY top.model_no LIMIT :limit OFFSET :offset;";

        $machines =  Yii::$app->db->createCommand($sql)->bindValues([
            'packId' => $auth['id'],
            'limit' => $getData['limit'],
            'offset' => $offset
        ])->queryAll();
        $total = Yii::$app->db->createCommand("select count(top.id) from tikuv_outcome_products top where top.pack_id = :id;")->bindValue('id', $auth['id'])->queryScalar();
        $response = [
            'status' => true,
            'message' => 'OK',
            'data' => $machines,
            'total' => $total,
            'auth' => $auth
        ];
        return $response;
    }


    public function actionTotalPack(){

        $auth = Yii::$app->request->post();
        $flagUser = false;

        if((!isset($auth['user']) || !isset($auth['password'])) || $auth['user'] != self::USER_NAME || $auth['password'] != self::PASSWORD){
            $flagUser = true;
        }
        if($flagUser){
            $response['status'] = false;
            $response['message'] = "Auth Error";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $auth;
            return $response;
        }
        $total = Yii::$app->db->createCommand("select COUNT(id) as c from tikuv_outcome_products_pack where status = 3")->queryScalar();
        $response = [
            'status' => true,
            'message' => 'OK',
            'total' => $total,
            'auth' => $auth
        ];
        return $response;
    }

    public function actionAccepted(){

        $auth = Yii::$app->request->post();
        $flagUser = false;
        $flagItem = false;
        $flagPack = false;
        if((!isset($auth['user']) || !isset($auth['password'])) || $auth['user'] != self::USER_NAME || $auth['password'] != self::PASSWORD){
            $flagUser = true;
        }
        if(!isset($auth['items']) || empty($auth['items'])){
            $flagItem = true;
        }

        if(!isset($auth['pack_id']) || empty($auth['pack_id'])){
            $flagPack = true;
        }
        if($flagUser){
            $response['status'] = false;
            $response['message'] = "Auth Error";
            $response['auth'] = $auth;
            return $response;
        }
        if($flagItem){
            $response['status'] = false;
            $response['message'] = "Items are empty";
            $response['auth'] = $auth;
            return $response;
        }
        if($flagPack){
            $response['status'] = false;
            $response['message'] = "Pack Id is not exists";
            $response['auth'] = $auth;
            return $response;
        }
        $savedItem = false;
        $isNotEmpty = false;

        foreach ($auth['items'] as $item){
            $savedItem = false;
            if(!empty($item['accepted'])){
                $model = new TikuvTopAccepted();
                $model->setAttributes([
                    'reg_date' => date('Y-m-d'),
                    'top_id'   => $item['id'],
                    'accepted' => $item['accepted'],
                    'doc_number' => (string)$auth['doc_number']
                ]);
                if($model->save()){
                    $savedItem = true;
                    $isNotEmpty = true;
                }
            }else{
                $savedItem = true;
            }
        }
        if($savedItem && $isNotEmpty){
            $sql = "select SUM(ta.accepted) as apt,
                           top.quantity as qty
                           from tikuv_top_accepted ta
                           left join tikuv_outcome_products top on ta.top_id = top.id
                           WHERE top.pack_id = :packId GROUP BY ta.top_id;";
            $acceptedItems = Yii::$app->db->createCommand($sql)->bindValue('packId',$auth['pack_id'])->queryAll();
            $isCheck = true;
            if(!empty($acceptedItems)){
                foreach ($acceptedItems as $item){
                    if($item['qty'] > $item['apt']){
                        $isCheck = false;
                        break;
                    }
                }
                if($isCheck){
                    $modelPack = TikuvOutcomeProductsPack::findOne($auth['pack_id']);
                    if($modelPack !== null && $modelPack->status != 4){
                        $modelPack->updateCounters(['status' => 1]);
                    }
                }
            }
            $response['status'] = true;
            $response['message'] = "OK";
            $response['auth'] = $auth;
            return $response;
        }else{
            $response['status'] = false;
            $response['message'] = "All items are empty";
            $response['auth'] = $auth;
            return $response;
        }
    }
}
