<?php

namespace app\api\modules\v1\controllers;

use app\models\Users;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\base\models\Goods;
use app\modules\base\models\GoodsItems;
use app\modules\base\models\SizeColRelSize;
use app\modules\tikuv\models\TikuvGoodsDocAccepted;
use app\modules\tikuv\models\TikuvOutcomeProducts;
use app\modules\tikuv\models\TikuvOutcomeProductsPack;
use app\modules\tikuv\models\TikuvTopAccepted;
use app\modules\toquv\models\ToquvDepartments;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use app\api\modules\v1\components\CorsCustom;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use app\modules\toquv\models\ToquvMakine;
use app\modules\tikuv\models\TikuvGoodsDocPack;

/**
 * Country Controller API
 *
 * @author Omadbek Onorov <omadbek.onorov@gmail.com>
 */
class TransferController extends ActiveController
{
    const USER_NAME = 'SAMO-API-USER';
    const PASSWORD  = 'samo-api-password';

    public $modelClass = 'app\modules\bichuv\models\BichuvDoc';

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

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => CorsCustom::className()
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'list'              => ['GET','POST', 'HEAD','OPTIONS'],
                    'list-docs'         => ['GET','POST', 'HEAD','OPTIONS'],
                    'search'            => ['GET','POST', 'HEAD','OPTIONS'],
                    'nastel-remain'     => ['GET','POST', 'HEAD','OPTIONS'],
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
    public function conditions($getData)
    {

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

    public function actionList(){

        $data = Yii::$app->request->post();
        $flagCurrentUser = false;
        $deptId = null;

        if(!isset($data['dept_token'])){
            $flagCurrentUser = true;
        }else{
            $deptModel = ToquvDepartments::findOne(['token' => $data['dept_token']]);
            if($deptModel === null){
                $flagCurrentUser = true;
            }else{
                $deptId = $deptModel->id;
            }
        }
        if($flagCurrentUser){
            $response['status'] = false;
            $response['message'] = "User didnt connected to department";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $data;
            return $response;
        }

        $conditionNastel = "";

        if(isset($data['q']) && !empty($data['q'])){
            $conditionNastel = " AND (bsib2.party_no LIKE '%{$data['q']}%' OR ml.article LIKE '%{$data['q']}%') ";
        }

        $getData = $this->conditions(Yii::$app->request->get());
        $offset = ($getData['page'] - 1)*$getData['limit'];
        $sql = "select bsib.id,
                       bsib.party_no,
                       (select SUM(bsib3.inventory) from bichuv_slice_item_balance bsib3 where bsib3.party_no = bsib.party_no) as inventory,
                       bsib.doc_id,
                       bd.work_weight,
                       bd.musteri_id,
                       ml.article,
                       cp.code 
                from bichuv_slice_item_balance bsib
                         left join bichuv_doc bd on bsib.doc_id = bd.id
                         inner join bichuv_given_rolls bgr on bsib.party_no = bgr.nastel_party
                         left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                         left join models_list ml on mrp.models_list_id = ml.id
                         left join models_variations mv on mv.id = mrp.model_variation_id
                         left join color_pantone cp on mv.color_pantone_id = cp.id  
                WHERE bsib.id IN (select MAX(bsib2.id) from bichuv_slice_item_balance bsib2
                                  where bsib2.department_id = %d  %s 
                GROUP BY bsib2.party_no) 
                AND bsib.inventory > 0 
                ORDER BY bsib.id DESC LIMIT %d OFFSET %d;";
        $sql = sprintf($sql, $deptId, $conditionNastel, $getData['limit'],$offset);
        $items =  Yii::$app->db->createCommand($sql)->queryAll();
        if(empty($items)){
            $response['status'] = false;
            $response['message'] = "Not found data";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $data;
            return $response;
        }
        $results = [];
        foreach ($items as $item) {
            $code = $item['code'];
            if(array_key_exists($item['id'], $results)){
                $results[$item['id']]['colors'] .= ", {$code}";
            }else{
                $results[$item['id']] = [
                    "id" => $item['id'],
                    "inventory"=> $item['inventory'],
                    "party_no" => $item['party_no'],
                    "doc_id"=> $item['doc_id'],
                    "work_weight"=> $item['work_weight'],
                    "musteri_id"=> $item['musteri_id'],
                    "article"=> $item['article'],
                    "colors"=> $code
                ];
            }
        }
        ArrayHelper::multisort($results,'id', SORT_DESC);
        $response = [
            'status' => true,
            'message' => 'OK',
            'data' => $results,
            'auth' => $data
        ];
        return $response;
    }

    public function actionNastelRemain(){
        $data = Yii::$app->request->post();
        $flagCurrentUser = false;
        $flagNastel = false;
        $deptId = null;

        if(!isset($data['dept_token'])){
            $flagCurrentUser = true;
        }else{
            $deptModel = ToquvDepartments::findOne(['token' => $data['dept_token']]);
            if($deptModel === null){
                $flagCurrentUser = true;
            }else{
                $deptId = $deptModel->id;
            }
        }
        if(!isset($data['nastel_no']) || empty($data['nastel_no'])){
            $flagNastel = true;
        }
        if($flagCurrentUser){
            $response['status'] = false;
            $response['message'] = "User didnt connected to department";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $data;
            return $response;
        }

        if($flagNastel){
            $response['status'] = false;
            $response['message'] = "Nastel number is empty";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $data;
            return $response;
        }

        $sql = "select bsib.size_id,
                       s.name,
                       bsib.party_no,
                       bsib.inventory,
                       bsib.doc_id,
                       bd.work_weight,
                       bd.musteri_id
                from bichuv_slice_item_balance bsib
                         left join bichuv_doc bd on bsib.doc_id = bd.id
                         left join size s on bsib.size_id = s.id
                WHERE bsib.id IN (select MAX(bsib2.id) from bichuv_slice_item_balance bsib2
                                  where bsib2.party_no = '%s' AND bsib2.department_id = %d  GROUP BY bsib2.size_id)
                  AND bsib.inventory > 0
                ORDER BY bsib.size_id;";
        $sql = sprintf($sql, $data['nastel_no'], $deptId);
        $items =  Yii::$app->db->createCommand($sql)->queryAll();
        if(empty($items)){
            $response['status'] = false;
            $response['message'] = "Not found data";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $data;
            return $response;
        }
        $response = [
            'status' => true,
            'message' => 'OK',
            'data' => $items,
            'auth' => $data
        ];
        return $response;

    }

    public function actionListDocs(){

        $data = Yii::$app->request->post();
        $flagCurrentUser = false;
        $deptId = null;

        $getData = $this->conditions(Yii::$app->request->get());
        $offset = ($getData['page'] - 1)*$getData['limit'];

        if(!isset($data['dept_token'])){
            $flagCurrentUser = true;
        }else{
            $deptModel = ToquvDepartments::findOne(['token' => $data['dept_token']]);
            if($deptModel === null){
                $flagCurrentUser = true;
            }else{
                $deptId = $deptModel->id;
            }
        }
        if($flagCurrentUser){
            $response['status'] = false;
            $response['message'] = "User didnt connected to department";
            $response['data'] = [];
            $response['total'] = 0;
            $response['auth'] = $data;
            return $response;
        }

        $sql = "SELECT bd.id, 
                       td.name as dept, 
                       bsi.nastel_party as nastel_no, 
                       SUM(bsi.quantity) as quantity,
                       bd.status 
                FROM bichuv_doc bd
                LEFT JOIN toquv_departments td on bd.to_department = td.id
                LEFT JOIN bichuv_slice_items bsi on bd.id = bsi.bichuv_doc_id
                WHERE (bd.from_department = %d)
                  AND (bd.document_type = 2)
                GROUP BY bd.id
                ORDER BY bd.id DESC
                LIMIT %d OFFSET %d;";
        $sql = sprintf($sql, $deptId, $getData['limit'],$offset);
        $items =  Yii::$app->db->createCommand($sql)->queryAll();

        $response = [
            'status' => true,
            'message' => 'OK',
            'data' => $items,
            'auth' => $data
        ];
        return $response;
    }
}
