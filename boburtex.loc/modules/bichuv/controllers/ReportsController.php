<?php

namespace app\modules\bichuv\controllers;

use app\modules\admin\models\ToquvUserDepartment;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\bichuv\models\BichuvDocSearch;
use app\modules\bichuv\models\BichuvDocSliceMovingSearch;
use app\modules\bichuv\models\BichuvGivenRolls;
use app\modules\bichuv\models\BichuvPrintAndPatternItemBalance;
use app\modules\bichuv\models\BichuvPrintAndPatternItemBalanceSearch;
use app\modules\bichuv\models\BichuvReportSearch;
use app\modules\bichuv\models\BichuvRmItemBalance;
use app\modules\bichuv\models\BichuvServiceItemBalance;
use app\modules\bichuv\models\BichuvSliceItemBalance;
use app\modules\bichuv\models\BichuvSliceItems;
use app\modules\tikuv\models\TikuvDocItems;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvKaliteSearch;
use yii\data\ArrayDataProvider;
use const Grpc\STATUS_INTERNAL;
use Yii;
use app\modules\bichuv\models\BichuvItemBalance;
use app\modules\bichuv\models\BichuvItemBalanceSearch;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReportsController extends BaseController
{

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $searchModel = new BichuvItemBalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionKunlikXisobot()
    {
        $searchModel = new BichuvDocSearch();

        $dataProvider = $searchModel->searchXisobot(Yii::$app->request->queryParams );

        return $this->render('kunlik-xisobot',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionReportAccsSklad()
    {
        $searchModel = new BichuvItemBalanceSearch();
        $params = Yii::$app->request->post();
        $data = [];
        $data['from_date'] = date('01.01.Y');
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        if (!empty($params['BichuvItemBalanceSearch'])) {
            if (!empty($params['BichuvItemBalanceSearch']) && !empty($params['BichuvItemBalanceSearch']['from_date'])) {
                $data['from_date'] = $params['BichuvItemBalanceSearch']['from_date'];
            }
            if (!empty($params['BichuvItemBalanceSearch']) && !empty($params['BichuvItemBalanceSearch']['to_date'])) {
                $data['to_date'] = $params['BichuvItemBalanceSearch']['to_date'];
            }
        }
        $items = $searchModel->search($params, BichuvDoc::DOC_TYPE_INCOMING);

        return $this->render('report-accs-sklad', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data
        ]);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionReportAccsMoving()
    {
        $searchModel = new BichuvItemBalanceSearch();

        $params = Yii::$app->request->post();
        $data = [];
        $data['from_date'] = '01.01.2019';
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        if (!empty($params['BichuvItemBalanceSearch'])) {
            if (!empty($params['BichuvItemBalanceSearch']) && !empty($params['BichuvItemBalanceSearch']['from_date'])) {
                $data['from_date'] = $params['BichuvItemBalanceSearch']['from_date'];
            }
            if (!empty($params['BichuvItemBalanceSearch']) && !empty($params['BichuvItemBalanceSearch']['to_date'])) {
                $data['to_date'] = $params['BichuvItemBalanceSearch']['to_date'];
            }
        }
        $items = $searchModel->search($params, BichuvDoc::DOC_TYPE_MOVING);

        return $this->render('report-accs-moving', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data,
            'priceUSD' => null,
        ]);
    }


    public function actionReportModelOrderAcs(){
        $reports = ModelOrders::getRemainReportAcs();
        return $this->render('reports-model-order-acs',['items' => $reports]);
    }

    /**
     * Displays a single BichuvItemBalance model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BichuvItemBalance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BichuvItemBalance();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BichuvItemBalance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionMatoRemain()
    {
        $model = new BichuvReportSearch();
        $params = Yii::$app->request->queryParams;
        $items = $model->search($params);
        $deptName = null;
        $data = null;
        if (!empty($items)) {
            $deptName = $items[0]['dept'];
        }
        return $this->render('report-mato-remain', [
                'items' => $items,
                'deptName' => $deptName,
                'model' => $model,
                'params' => $params
            ]
        );
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionMatoSotishRemain()
    {
        $model = new BichuvReportSearch();
        $params = Yii::$app->request->queryParams;
        $items = $model->searchMatoSotishRemain($params);
        $deptName = null;
        $data = null;
        if (!empty($items)) {
            $deptName = $items[0]['dept'];
        }
        return $this->render('report-mato-sotish-remain', [
                'items' => $items,
                'deptName' => $deptName,
                'model' => $model,
                'params' => $params
            ]
        );
    }



    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionSliceRemain()
    {
        $currentUserId = Yii::$app->user->id;
        $model = new BichuvReportSearch();
        $params = Yii::$app->request->queryParams;
        $items = $model->searchSlice($params);


        return $this->render('report-slice-remain', [
            'items' => $items,
            'model' => $model
        ]);


    }

    public function actionAcsRemain()
    {
        $currentUserId = Yii::$app->user->id;
        $dept = ToquvUserDepartment::find()->select(['department_id'])->where(['user_id' => $currentUserId])->all();

        if (count($dept) == 1) {
            $deptName = $dept[0]->department->name;
        } else {
            $count = count($dept);
            $deptName = "";
            foreach ($dept as $key => $item) {
                $deptName .= "{$item->department->name}";
                if ($key != $count - 1) {
                    $deptName .= ", ";
                }
            }
        }
        $sql = "select p.name as model, acs.sku, acs.name, bap.name as property, bib.inventory from bichuv_item_balance bib
                left join bichuv_doc bd on bib.document_id = bd.id
                left join bichuv_doc_items bdi on bd.id = bdi.bichuv_doc_id
                left join product p on bdi.model_id = p.id
                left join bichuv_acs acs on bib.entity_id = acs.id
                left join bichuv_acs_property bap on acs.property_id = bap.id
                where bib.id IN (select MAX(bib2.id) from bichuv_item_balance bib2 WHERE bib2.department_id IN (select tud.department_id from toquv_user_department tud where tud.user_id = %d) GROUP BY bib2.entity_id)
                AND bib.inventory > 0
                GROUP BY bib.entity_id ORDER BY acs.sku, acs.name DESC;";
        $sql = sprintf($sql, $currentUserId);
        $items = Yii::$app->db->createCommand($sql)->queryAll();
        return $this->render('report-acs-remain', ['items' => $items, 'deptName' => $deptName]);
    }
    public function actionIndexService(){
        $searchModel = new BichuvDocSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,BichuvDoc::DOC_TYPE_ADJUSTMENT_SERVICE);
        return $this->render('index-service',[
           'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionViewService($id)
    {
        $model = BichuvDoc::findOne($id);
        return $this->render('view-service',[
            'model' => $model,
        ]);
    }
    /**
     * @return string|Response
     */
    public function actionFormService($id=null)
    {
        $model = ($id)?BichuvDoc::findOne($id):new BichuvDoc(['document_type' => BichuvDoc::DOC_TYPE_ADJUSTMENT_SERVICE]);
        $models = (!empty($model->bichuvSliceItems)) ? $model->bichuvSliceItems : [new BichuvSliceItems()];
        $model->reg_date = date('d.m.Y');
        $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->doc_number = "BU" . $lastId . "/" . date('Y');
        $list = ModelsList::getListModel($model->models_list_id);
        $modelVar = $model->modelVar;
        $model->cp['data'] = ModelsVariations::getListVar($model->models_list_id);
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $slice = Yii::$app->request->post('BichuvSliceItems',[]);
            $models = [];
            foreach ($slice as $key => $item) {
                $models[$key] = new BichuvSliceItems();
            }
            if($model->load(Yii::$app->request->post()) && Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)){
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if ( $model->save() ) {
                        if ( !empty($model->bichuvSliceItems) ) {
                            foreach ($model->bichuvSliceItems as $item) {
                                $item->delete();
                            }
                        }
                        $modelId = $model->id;
                        if ( !empty($data['BichuvSliceItems']) ) {
                            $workWeight = $model->work_weight;
                            foreach ($data['BichuvSliceItems'] as $item) {
                                if ( $item['quantity'] > 0 ) {
                                    $nastelNo[$item['nastel_party']] = "'{$item['nastel_party']}'";
                                    $modelSliceItems = new BichuvSliceItems();
                                    if ( !empty($item['work_weight']) && $item['work_weight'] > 0 ) {
                                        $workWeight = $item['work_weight'];
                                    }
                                    $modelSliceItems->setAttributes([
                                        'bichuv_doc_id' => $modelId,
                                        'size_id' => $item['size_id'],
                                        'model_id' => $item['model_id'],
                                        'nastel_party' => $item['nastel_party'],
                                        'quantity' => $item['quantity'],
                                        'bichuv_given_roll_id' => $item['bichuv_given_roll_id'],
                                        'work_weight' => $workWeight
                                    ]);
                                    if ( $modelSliceItems->save() ) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        if ( $modelSliceItems->hasErrors() ) {
                                            \yii\helpers\VarDumper::dump($modelSliceItems->getErrors(), 10, true);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ( $saved ) {
                        $transaction->commit();
                        return $this->redirect(['view-service', 'id' => $model->id, 'slug' => Yii::$app->request->get('slug',BichuvDoc::DOC_TYPE_ADJUSTMENT_SERVICE_LABEL)]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                }
            }
        }
        return $this->render('form-service', [
            'model' => $model,
            'models' => $models,
            'list' => $list,
        ]);
    }
    public function actionDeleteService($id)
    {
        $model = BichuvDoc::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($model->bichuvSliceItems)) {
                foreach ($model->bichuvSliceItems as $item) {
                    $item->delete();
                }
            }
            $model->delete();
            $transaction->commit();
        } catch (Exception $e) {
            Yii::info('Not all deleted ' . $e->getMessage(), 'delete');
        }
        return $this->redirect(['index-service', 'slug' => Yii::$app->request->get('slug',BichuvDoc::DOC_TYPE_ADJUSTMENT_SERVICE_LABEL)]);
    }
    public function actionAjaxRequest($q)
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
        }
        $response = [];
        $arr = [];
        $response['results'] = [];
        if (!empty($q)) {
            $searchModel = new ModelOrders();
            $res = $searchModel->getModelList($q);
            if (!empty($res)) {
                $arr['check'] = [];
                foreach ($res['list'] as $item) {
                    if($item['is_main']!='0') {
                        $name = ($item['path']) ?
                            "<img src='/web/" . $item['path'] . "' style='width:30px;height:30px;border:1px solid'> <b> " .
                            $item['mart'] . " </b> - " . $item['mname'] . " - " . $item['tname'] :
                            "<b> " .$item['mart'] . " </b> - " . $item['mname'] . " - " . $item['tname'];
                        array_push($response['results'],[
                            'id' => $item['id'],
                            'text' => $name,
                            'baski' => $item['baski'],
                            'prints' => $item['prints'],
                            'stone' => $item['stone'],
                            'brend_id' => $item['brend_id'],
                            'acs' => $res['acs'][$item['id']]
                        ]);
                        $arr['check'][$item['id']] = [$item['id']];
                    }else{
                        if(!array_key_exists($item['id'], $arr['check'])){
                            $name = ($item['path']) ?
                                "<img src='/web/" . $item['path'] . "' style='width:30px;height:30px;border:1px solid'> <b> " .
                                $item['mart'] . " </b> - " . $item['mname'] . " - " . $item['tname'] :
                                "<b> " .$item['mart'] . " </b> - " . $item['mname'] . " - " . $item['tname'];
                            array_push($response['results'],[
                                'id' => $item['id'],
                                'text' => $name,
                                'baski' => $item['baski'],
                                'prints' => $item['prints'],
                                'stone' => $item['stone'],
                                'brend_id' => $item['brend_id'],
                                'acs' => $res['acs'][$item['id']]
                            ]);
                        }
                    }
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                ];
            }
        }
        return $response;
    }

    public function actionGetModelVariations($id){
        if(Yii::$app->request->isAjax){
            $this->layout = false;
            Yii::$app->response->format = Response::FORMAT_JSON;
        }
        $response = [];
        $arr = [];
        $response['status'] = 0;
        $response['message'] = 'error';
        $color['results'] = [];
        $model = ModelsList::findOne($id);
        $response['baski'] = $model->baski;
        $response['prints'] = $model->prints;
        $response['stone'] = $model->stone;
        $sql = "SELECT mv.id, mv.name as mname, mv.code as mcode, r,g,b, mvc.is_main, cp.code
                FROM models_variations as mv
                LEFT JOIN models_variation_colors as mvc ON mv.id = mvc.model_var_id
                LEFT JOIN color_pantone cp on mvc.color_pantone_id = cp.id
                WHERE mv.status = 1 AND mv.model_list_id =  {$id}
                ORDER BY mvc.is_main
        ";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        if (!empty($res)) {
            $arr['check'] = [];
            foreach ($res as $item) {
                if($item['is_main']!='0') {
                    $name = "<span style='background:rgb(".$item['r'].",".$item['g'].",".$item['b'].");
                    padding-left:7px;padding-right:7px;border:1px solid;border-radius: 20px'></span> &nbsp; <b> {$item['code']} " . $item['mname'] . " </b> <small>". $item['mcode'] ."</small>";
                    array_push($color['results'],[
                        'id' => $item['id'],
                        'text' => $name,
                    ]);
                    $arr['check'][$item['id']] = [$item['id']];
                }else{
                    if(!array_key_exists($item['id'], $arr['check'])){
                        $name = "<span style='background:rgb(".$item['r'].",".$item['g'].",".$item['b']."); width:80px;
                    padding-left:5px;padding-right:5px;border:1px solid'></span>  &nbsp; <b>  {$item['code']} " . $item['mname'] . " </b> <small>". $item['mcode'] ."</small>";
                        array_push($color['results'],[
                            'id' => $item['id'],
                            'text' => $name,
                        ]);
                    }
                }
            }
            $response['status'] = 1;
            $response['message'] = 'success';
            $response['data'] = ArrayHelper::map($color['results'],'id','text');
        }
        if(Yii::$app->request->isAjax){
            return $response;
        }
        return $response['data'];
    }

    public function actionSaveAndFinish($id)
    {
        $model = BichuvDoc::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        if($model->status < $model::STATUS_INACTIVE)
        try {
            $sliceItems = $model->getBichuvSliceItems()->asArray()->all();
            $saved = false;
            $modelId = $model->id;
            $modelListId = $model->models_list_id;
            $modelVarId = $model->model_var_id;
            $deptId = $model->from_department;
            $musteriId = $model->service_musteri_id;
            foreach ($sliceItems as $item) {
                $modelServiceItemBlanace  = new BichuvServiceItemBalance();
                $item['musteri_id'] = $musteriId;
                $item['department_id'] = $deptId;
                $item['model_list_id'] = $modelListId;
                $item['model_var_id'] = $modelVarId;
                $checkServiceExists = $modelServiceItemBlanace::getLastSliceService($item);
                $inventoryService = $item['quantity'];
                if ($checkServiceExists) {
                    $inventoryService = $checkServiceExists['inventory'] + $inventoryService;
                }
                $modelServiceItemBlanace->setAttributes([
                    'musteri_id' => $musteriId,
                    'size_id' => $item['size_id'],
                    'sort_id' => 1,
                    'nastel_no' => $item['nastel_party'],
                    'department_id' => $deptId,
                    'count' => (int)$item['quantity'],
                    'inventory' => (int)$inventoryService,
                    'doc_type' => 1,
                    'model_id' => $modelListId,
                    'model_var' => $modelVarId,
                    'doc_id' => $modelId,
                ]);

                if ($modelServiceItemBlanace->save()) {
                    $saved = true;
                } else {
                    $saved = false;
                    break;
                }
            }
            if ($saved) {
                $model->updateCounters(['status' => 2]);
                $transaction->commit();
            }
        } catch (Exception $e) {
            Yii::info('Not all saved ' . $e, 'save');
        }
        return $this->redirect(['view-service', 'id' => $model->id]);
    }

    /**
     * Lists all BichuvDoc models.
     * @return mixed
     */
    public function actionReportSliceMoving()
    {
        $searchModel = new BichuvDocSliceMovingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render("report-slice-moving", [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMovingSliceExportExcel() {
        header('Content-Type: application/vnd.ms-excel');
        $filename = "kochirish_kesim_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BichuvDoc::find()->select([
                'id',
            ])->all(),
            'columns' => [
                'id',
            ],
            'headers' => [
                'id' => 'Id',
            ],
            'autoSize' => true,
        ]);
    }

    /**
     * Finds the BichuvItemBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BichuvItemBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BichuvItemBalance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function actionExportExcel(){
        $searchModel = new BichuvReportSearch();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $searchModel->searchSlice(Yii::$app->request->queryParams),
            'pagination' => false,
        ]);
//        print_r("<pre>");
//        print_r($searchModel->searchSlice(Yii::$app->request->queryParams));
//        die;
//        if(!empty($searchModel->status)){
//            $status =
//                [
//                    'attribute' => 'send_user_id',
//                    'value' => function($model){
//                        $tabel = (!empty($model->sendedUser->usersInfo['tabel']))?" T-".$model->sendedUser->usersInfo['tabel']:'';
//                        return $model->sendedUser['user_fio'].$tabel;
//                    },
//                ];
//        }
        header('Content-Type: application/vnd.ms-excel');
        $filename = "reports_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => $dataProvider->getModels(),
            'columns' => [
                'depart_name',
                'party_no',
                'model',
                'size',
                'inventory',
            ],
            'headers' => [
                'depart_name' => Yii::t('app','Department'),
                'party_no' => Yii::t('app','Nastel No'),
                'model' => Yii::t('app', 'Model'),
                'size' => Yii::t('app', 'Size'),
                'inventory' => Yii::t('app', 'Miqdori(dona)'),
            ],
            'autoSize' => true,
        ]);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionReportDay()
    {
        $model = new BichuvReportSearch();
        $params = Yii::$app->request->queryParams;
        $model->from_date = date('Y-m-01');
        $model->to_date = date("Y-m-t");
        $items = $model->searchReportDay($params);

        return $this->render('report-day', [
                'items' => $items,
                'params' => $params,
                'model' => $model
            ]
        );
    }

    public function actionDayReportExportExcel(){
        $searchModel = new BichuvReportSearch();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $searchModel->searchReportDay(Yii::$app->request->queryParams),
            'pagination' => false,
        ]);
//        print_r("<pre>");
//        print_r($searchModel->searchSlice(Yii::$app->request->queryParams));
//        die;
//        if(!empty($searchModel->status)){
//            $status =
//                [
//                    'attribute' => 'send_user_id',
//                    'value' => function($model){
//                        $tabel = (!empty($model->sendedUser->usersInfo['tabel']))?" T-".$model->sendedUser->usersInfo['tabel']:'';
//                        return $model->sendedUser['user_fio'].$tabel;
//                    },
//                ];
//        }
        header('Content-Type: application/vnd.ms-excel');
        $filename = "reports_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => $dataProvider->getModels(),
            'columns' => [
                'sana',
                'two',
                'three',
                'service',
                'summ',
                'works',
            ],
            /*'headers' => [
                'sana' => Yii::t('app','Department'),
                'party_no' => Yii::t('app','Nastel No'),
                'model' => Yii::t('app', 'Model'),
                'size' => Yii::t('app', 'Size'),
                'inventory' => Yii::t('app', 'Miqdori(dona)'),
            ],*/
            'autoSize' => true,
        ]);
    }


    public function actionReportPrintIn(){

        $searchModel = new BichuvPrintAndPatternItemBalanceSearch();
        $dataProvider = $searchModel->searchPrintIn(Yii::$app->request->queryParams);

        return $this->render("report-print-in", [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
    public function actionReportPrintTransfer(){

        $searchModel = new BichuvPrintAndPatternItemBalanceSearch();
        $dataProvider = $searchModel->searchPrintTransfer(Yii::$app->request->queryParams);

        return $this->render("report-print-transfer", [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
    public function actionReportPrintRemain(){

        $searchModel = new BichuvPrintAndPatternItemBalanceSearch();
        $dataProvider = $searchModel->searchPrintRemain(Yii::$app->request->queryParams);

        return $this->render("report-print-remain", [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionReportBichuvSliceAccept(){
        $searchModel = new BichuvReportSearch();
        $dataProvider = $searchModel->searchBichuvSliceAccept(Yii::$app->request->queryParams);

        return $this->render("report-bichuv-slice-accept", [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionReportAccsOrders()
    {
        $isBoolean = true;
        $searchModel = new BichuvItemBalanceSearch();
        $params = Yii::$app->request->post();
        $data = [];
        $data['from_date'] = date('01.01.Y');
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));
        
        if(Yii::$app->request->isPost){
            $items = $searchModel->searchBichuvAcsModelAccept($params);
        }

        return $this->render('report-bichuv-acs-model', [
            'model' => $searchModel,
            'items' => $items,
            'data' => $data,
            'isBoolean' => $isBoolean
        ]);
    }


}
