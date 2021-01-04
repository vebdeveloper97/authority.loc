<?php

namespace app\modules\base\controllers;

use app\models\Notifications;
use app\models\Size;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\base\models\ModelOrderItemsPrints;
use app\modules\base\models\ModelOrderItemsStone;
use app\modules\base\models\ModelOrdersCommentVarRel;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelOrdersItemsAcs;
use app\modules\base\models\ModelOrdersItemsAcsSearch;
use app\modules\base\models\ModelOrdersItemsChanges;
use app\modules\base\models\ModelOrdersItemsSearch;
use app\modules\base\models\ModelOrdersItemsSize;
use app\modules\base\models\ModelOrdersItemsVariations;
use app\modules\base\models\ModelOrdersPlanning;
use app\modules\base\models\ModelOrdersResponsible;
use app\modules\base\models\ModelOrdersStatus;
use app\modules\base\models\ModelOrdersVariations;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelVarBaski;
use app\modules\base\models\ModelVarPrints;
use app\modules\base\models\ModelVarStone;
use app\modules\base\models\MoiRelDept;
use app\modules\base\models\SizeCollections;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\hr\models\HrDepartmentResponsiblePerson;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use app\modules\hr\models\HrEmployeeUsers;
use app\modules\settings\models\CompanyCategories;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\ToquvRawMaterialType;
use app\modules\wms\models\WmsDocument;
use app\modules\wms\models\WmsDocumentItems;
use app\modules\wms\models\WmsItemBalance;
use app\modules\wms\models\WmsItemBalanceSearch;
use app\modules\wms\models\WmsItemCategorySearch;
use Codeception\Lib\Notification;
use moonland\phpexcel\Excel;
use Throwable;
use Yii;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersSearch;
use yii\base\DynamicModel;
use yii\base\InvalidArgumentException;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ModelOrdersController implements the CRUD actions for ModelOrders model.
 */
class ModelOrdersNewController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ModelOrders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModelOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $token=true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ModelOrders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $mvId = $model->modelOrdersItems;
        $commentForm = new ModelOrdersCommentVarRel();
        $commentForm->type = 2;
        $array = [];
        if(!empty($mvId)){
            foreach ($mvId as $item) {
                $array[] = $item['model_var_id'];
            }
        }
        $request = Yii::$app->request;
        $postData = $request->post();
        if ($request->isPost && $commentForm->load($postData)) {
            $comments = isset($postData['ModelOrdersCommentVarRel']['comments']) ? $postData['ModelOrdersCommentVarRel']['comments'] : '';
            $commentForm->comments = explode(',', $comments);
            if ($commentForm->saveAndChangeStatuses($model)) {
                Yii::$app->session->setFlash('success', 'OK');
                return $this->redirect(['index']);
            }
        }

        $searchModel = new ModelOrdersItemsSearch();
        $dataProviderThread= $searchModel->searchThread(Yii::$app->request->queryParams,$id);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id,null,null,true);
        $dataProviderPlanned = $searchModel->searchPlanned(Yii::$app->request->queryParams,$id);
        $dataProviderPlan=$searchModel->searchPlan(Yii::$app->request->queryParams,$id,MoiRelDept::TYPE_MATO);//Mato plan
        $dataProviderAksessuar=$searchModel->searchAksessuar(Yii::$app->request->queryParams,$id);
        $searchMaterial = new WmsItemBalanceSearch();
        $dataProviderRemainMaterial = $searchMaterial->searchRemainForSupplier(Yii::$app->request->queryParams, $id);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'dataProviderAksessuar' => $dataProviderAksessuar,
            'dataProviderThread' => $dataProviderThread,
            'dataProviderPlanned' => $dataProviderPlanned,
            'dataProviderPlan' => $dataProviderPlan,
            'dataProviderRemainMaterial' => $dataProviderRemainMaterial,
            'commentForm' => $commentForm
        ]);
    }

    public function actionConstructor()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        /** ModelOrders ni olish*/
        $model = $this->findModel($id);
        $model->status = ModelOrders::STATUS_SAVED;
        $items = $model->getModelOrdersItems()->where(['not', ['status' => $model::STATUS_INACTIVE]])->all()?$model->getModelOrdersItems()->where(['not', ['status' => $model::STATUS_INACTIVE]])->all():false;
        if(!$items){
            Yii::$app->session->setFlash('error', Yii::t('app', 'Buyurtmani Elementlari mavjud emas!'));
            return $this->redirect($request->referrer);
        }
        /** Notification ni olish */
        $notifications = Notifications::findOne(['doc_id' => $model->id,'type' => 1])?Notifications::findOne(['doc_id' => $model->id,'type' => 1]):false;

        if(!$notifications)
        {
            Yii::$app->session->setFlash('error', Yii::t('app', "Xabarnoma ga yozib bo'lingan qayat yozishga urunish amalga oshirilyabdi. Yoki Bunday xabarnoma mavjud emas!"));
            return $this->redirect($request->referrer);
        }
        /** Transaction bilan ishlaymiz*/
        $transaction = Yii::$app->db->beginTransaction();
        $saved = true;
        try{
            /** Modellarni statuslarni constructor uchun moslab olamiz*/
            $model->orders_status = ModelOrders::STATUS_PLANNED;
            foreach ($items as $item){
                $item['status'] = ModelOrders::STATUS_PLANNED;
                if($item->save()){
                    $saved = true;
                }
                else{
                    $saved = false;
                    break;
                }
            }
            $notifications->type = 4;
            /** Modellarni malumotlarini saqlaymiz! */
            if($model->save() && $notifications->save()){
                /** barchasi saqlansa commit*/
                $transaction->commit();
                Yii::$app->session->setFlash('success', Yii::t('app', "{$model->doc_number} - Buyurtma tasdiqlandi"));
                return $this->redirect(['index']);
            }
            else{
                /** Saqlanmasi xatolikni qaytaramiz*/
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', Yii::t('app', "{$model->doc_number} - Buyurtma tasdiqlanmadi!"));
                return $this->redirect($request->referrer);
            }
        }
        catch(\Exception $e){
            /** Exception yozib ketamiz */
            Yii::info('error message '.$e->getMessage(),'save');
            Yii::$app->session->setFlash('error', Yii::t('app', "{$model->doc_number} - Buyurtma tasdiqlanmadi!"));
            return $this->redirect($request->referrer);
        }

    }

    /**
     * Creates a new ModelOrders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ModelOrders();
        $model->reg_date = date('d.m.Y');
        $lastId = $model::find()->select('id')->orderBy(['id'=>SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->doc_number =  "MO".$lastId . "/" . date('m-Y');

        $model->status = ModelOrders::STATUS_INACTIVE;

        if($model->save(false)){
            return $this->redirect(['update', 'id' => $model->id]);
        }

        if(Yii::$app->request->referrer){
            return $this->redirect(Yii::$app->request->referrer);
        }else{
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing ModelOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws Exception
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /*if($model->created_by!=$user_id&&$user_id!=1){
            return $this->redirect('index');
        }*/

        if($model->status < ModelOrders::STATUS_SAVED) {
            $models = ($model->modelOrdersItems) ? $model->modelOrdersItems : [new ModelOrdersItems()];
            $model->responsible = $model->responsibleMap;
            if ($model->load(Yii::$app->request->post())){
                $model->status = ModelOrders::STATUS_ACTIVE;
                $sql = "SELECT sum(mois.count) summa FROM model_orders_items_size mois
                LEFT JOIN model_orders_items moi on mois.model_orders_items_id = moi.id
                LEFT JOIN model_orders mo on moi.model_orders_id = mo.id
                WHERE mo.id = %d";
                $sql = sprintf($sql,$model->id);
                $model->sum_item_qty = Yii::$app->db->createCommand($sql)->queryOne()['summa'];
                if ($model->save()) {
                    $model->saveResponsible($model->responsible);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            return $this->render('update', [
                'model' => $model,
                'models' => $models,
                'all_prints' => [],
                'all_stone' => [],
                'all_acs' => [],
                'all_baski' => [],
                'all_rotatsion' => [],
                'copy' => false,
            ]);
        }else{
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Updates an existing ModelOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws Exception
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCopyOrder($id)
    {
        $oldModel = $this->findModel($id);

        if(!Yii::$app->request->isPost){
            $model = new ModelOrders();
            $model->reg_date = date('d.m.Y');
            $lastId = $model::find()->select('id')->orderBy(['id'=>SORT_DESC])->asArray()->one();
            $lastId = $lastId ? $lastId['id'] + 1 : 1;
            $model->doc_number =  "MO".$lastId . "/" . date('m-Y');
            $model->status = ModelOrders::STATUS_INACTIVE;
            if (!$model->save(false)) {
                return Yii::$app->request->referrer ? $this->redirect(Yii::$app->request->referrer) : $this->redirect('index');
            }
            $models = ($oldModel->modelOrdersItems) ? $oldModel->modelOrdersItems : [new ModelOrdersItems()];

            return $this->render('update', [
                'model' => $model,
                'models' => $models,
                'all_prints' => [],
                'all_stone' => [],
                'all_acs' => [],
                'all_baski' => [],
                'all_rotatsion' => [],
                'copy' => true,
            ]);

        }else{
            $model = $this->findModel(Yii::$app->request->post('id'));
            if ($model->load(Yii::$app->request->post())) {
                $model->status = ModelOrders::STATUS_ACTIVE;
                $sql = "SELECT sum(mois.count) summa FROM model_orders_items_size mois
                        LEFT JOIN model_orders_items moi on mois.model_orders_items_id = moi.id
                        LEFT JOIN model_orders mo on moi.model_orders_id = mo.id
                        WHERE mo.id = %d";
                $sql = sprintf($sql,$model->id);
                $model->sum_item_qty = Yii::$app->db->createCommand($sql)->queryOne()['summa'];
                if ($model->save()) {
                    $model->saveResponsible($model->responsible);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
    }


    public function actionSaveItem($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $dataList = Yii::$app->request->post('ModelOrdersItems');
            $data = reset($dataList);
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            if($result = $model->saveOneOrders($data,$dataList['id'])){
                if($result['status']==1){
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                    $response['model'] = $result['model'];
                    $response['status'] = 1;
                    $sql = "SELECT sum(mois.count) summa FROM model_orders_items_size mois
                            LEFT JOIN model_orders_items moi on mois.model_orders_items_id = moi.id
                            LEFT JOIN model_orders mo on moi.model_orders_id = mo.id
                            WHERE mo.id = %d";
                    $sql = sprintf($sql,$model->id);
                    $model->sum_item_qty = Yii::$app->db->createCommand($sql)->queryOne()['summa'];
                    $model->save(false);
                }else{
                    $response['errors'] = $result['errors'];
                }
            }
            return $response;
        }
    }
    public function actionDeleteItem()
    {
        $id = Yii::$app->request->post('id');
        $model = ModelOrdersItems::findOne($id);
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $saved = false;
                if(!empty($model)){
                    ModelOrdersItemsAcs::deleteAll(['model_orders_items_id' => $model->id]);
                    ModelOrderItemsPrints::deleteAll(['model_orders_items_id' => $model->id]);
                    ModelOrderItemsStone::deleteAll(['model_orders_items_id' => $model->id]);
                    ModelOrdersItemsSize::deleteAll(['model_orders_items_id' => $model->id]);
                    if($model->delete()){
                        $saved = true;
                    }
                }
                if($saved){
                    $response['status'] = 1;
                    $response['message'] = Yii::t('app', 'Deleted Successfully');
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                Yii::info('Not saved model orders' . $e, 'save');
            }
            return $response;
        }
    }
    public function actionDeletePlanning()
    {
        $id = Yii::$app->request->post('id');
        $model = ModelOrdersPlanning::findOne($id);
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $saved = false;
                if(!empty($model)){
                    if($model->delete()){
                        $saved = true;
                    }
                }
                if($saved){
                    $response['status'] = 1;
                    $response['message'] = Yii::t('app', 'Deleted Successfully');
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                Yii::info('Not saved model orders' . $e, 'save');
                $response['message'] = $e->errorInfo;
            }
            return $response;
        }
    }
    public function actionSearchBaski()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $data = Yii::$app->request->post();
            $response['model'] = ModelOrders::searchBaski($data['query'],$data['list']);
            if(count($response['model'])==0){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi");
            }
            return $response;
        }
    }
    public function actionSearchRotatsion()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $data = Yii::$app->request->post();
            $response['model'] = ModelOrders::searchRotatsion($data['query'],$data['list']);
            if(count($response['model'])==0){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi");
            }
            return $response;
        }
    }
    public function actionSearchPrints()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $data = Yii::$app->request->post();
            $response['model'] = ModelOrders::searchPrint($data['query'],$data['list']);
            if(count($response['model'])==0){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi");
            }
            return $response;
        }
    }
    public function actionSearchStones()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $data = Yii::$app->request->post();
            $response['model'] = ModelOrders::searchStone($data['query'],$data['list']);
            if(count($response['model'])==0){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi");
            }
            return $response;
        }
    }
    public function actionMoiRelDept($id)
    {
        $model = $this->findModel($id);
        $models = ($model->modelOrdersItems) ? $model->modelOrdersItems : [new ModelOrdersItems()];
        $all_prints = ModelVarPrints::find()->limit(10)->all();
        $all_stone = ModelVarStone::find()->limit(10)->all();
        $all_acs = BichuvAcs::find()->limit(100)->all();
        $all_baski = ModelVarBaski::find()->limit(10)->all();
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'models' => $models,
                'all_stone' => $all_stone,
                'all_prints' => $all_prints,
                'all_acs' => $all_acs,
                'all_baski' => $all_baski,
            ]);
        }
    }
    public function actionSearchAcs()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $data = Yii::$app->request->post();
            $response['model'] = ModelOrders::searchAcs($data['query'],$data['list']);
            if(count($response['model'])==0){
                $response['status'] = 0;
                $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi");
            }
            return $response;
        }
    }
    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $user_id = Yii::$app->user->id;
        if($model->created_by!=$user_id&&$user_id!=1){
            return $this->redirect('index');
        }
        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try {
            if($model->delete()){
                $saved = true;
            }
            if($saved) {
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            Yii::info('Not saved' . $e, 'save');
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }
    public function actionReturnPlan($id)
    {
        $model = $this->findModel($id);
        if($model->status > $model::STATUS_PLANNED && $model->status < $model::STATUS_SAVED){
            return $this->redirect(['view','id'=>$id]);
        }
        $model->status = $model::STATUS_SAVED;
        if($model->save(false)){
            return $this->redirect(['update-planning','id'=>$id]);
        }
        return $this->redirect(['view','id'=>$id]);
    }
    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id){
        $model = $this->findModel($id);
        $user_id = Yii::$app->user->id;
        if($model->created_by!=$user_id&&$user_id!=1){
            return $this->redirect('index');
        }
        if($model->status == ModelOrders::STATUS_SAVED){
            $model->responsible = "1";
            $model->status = ModelOrders::STATUS_SAVED;
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $saved = false;
                if($model->save()){
                    $sql = "SELECT user_id FROM auth_assignment WHERE item_name = 'bichuv-acs-skladi'";
                    $result = Yii::$app->db->createCommand($sql)->queryAll();
                    $notice = new Notifications();
                    $params = [];
                    $params['doc_id'] = $id;
                    $orderInfo = $model->getOrderInfo();
                    $params['body'] = "#{$model->doc_number} buyurtma saqlandi. Buyurtmachi - #{$orderInfo['musteri']}, Modellar - {$orderInfo['model_list']}, Miqdori - {$orderInfo['summa']}";
                    $params['users'] = ArrayHelper::getColumn($result, 'user_id');
                    $params['module'] = 'base';
                    $params['controllers'] = 'model_orders-new';
                    $params['actions'] = 'view';
                    $params['pharams'] = [
                        'id' => $id
                    ];
                    VarDumper::dump($params,10,true);die();
                    $notice->saveNotice($params,true);
                    $saved = $model->saveGoods();
                }
                if($saved){
                    $model->saveOrderStatus(ModelOrdersStatus::STATUS_SAVED);
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                }
            }catch (\Exception $e){
                Yii::info('Not saved '.$e->getMessage(),'save');
            }
        }
        return $this->redirect(['view','id' => $id]);
    }

    public function actionSaveAndPlanned($id){
        $model = $this->findModel($id);
        if($model->status < ModelOrders::STATUS_PLANNED){
            $model->setAttributes([
                'status' => ModelOrders::STATUS_PLANNED,
                'planning_id' => Yii::$app->user->identity->id,
                'planning_date' => date('Y-m-d H:i:s'),
                'responsible' => 1
            ]);
            if($model->save()){
                $model->getPlanningThread($id);
                $model->saveOrderStatus(ModelOrdersStatus::STATUS_PLANNED_TOQUV);
                $sql = "SELECT user_id FROM auth_assignment WHERE item_name = 'model-orders-provision'";
                $result = Yii::$app->db->createCommand($sql)->queryAll();

                $notice = new Notifications();
                $params = [];
                $params['doc_id'] = $id;
                $orderInfo = $model->getOrderInfo();
                $params['body'] = "#{$model->doc_number} buyurtma planlandi. Buyurtmachi - #{$orderInfo['musteri']}, Modellar - {$orderInfo['model_list']}, Miqdori - {$orderInfo['summa']}";
                $params['users'] = ArrayHelper::getColumn($result, 'user_id');
                $params['module'] = 'base';
                $params['controllers'] = 'model_orders';
                $params['actions'] = 'view';
                $params['pharams'] = [
                    'id' => $id
                ];
                $params['telegram'] = [
                    376544097,//Doston
                    265441481,//G'ayrat aka
                    613652 //Ulug'bek aka
                ];
                $notice->saveNotice($params, true);
            }
        }
        return $this->redirect(['view','id' => $id]);
    }

    /**
     * @param $id
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function  actionPlanningReport()
    {
        $searchModel = new ModelOrdersItemsSearch();
        $result = $searchModel->searchReport(Yii::$app->request->queryParams,$info==0);
        $dataProvider = $searchModel->searchReport(Yii::$app->request->queryParams,$info==1);
        return $this->render('planning-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'result'=>$result,
        ]);
    }

    public function  actionPlanningReportAcs()
    {
        $searchModel = new ModelOrdersItemsAcsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null);
        $dateBy = $searchModel->search(Yii::$app->request->queryParams, 1);
        $searchModel->from_date = date("Y-m-d");
        $searchModel->to_date = date("Y-m-t");
        return $this->render('planning-report-acs', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dateBy' => $dateBy,
        ]);
    }

    public function actionSaveAndPlannedToquv($id){
        $model = $this->findModel($id);
        //$user_id = Yii::$app->user->id;
        /*if($model->created_by!=$user_id&&$user_id!=1){
            return $this->redirect('index');
        }*/
        if($model->status === ModelOrders::STATUS_PLANNED && $model->status < ModelOrders::STATUS_PLANNED_TOQUV){
            if($model->saveToquvOrders()) {
                $model->saveOrderStatus(ModelOrdersStatus::STATUS_SEND_TOQUV);
                $sql = "SELECT user_id FROM auth_assignment WHERE item_name = 'toquv-master'";
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                $notice = new Notifications();
                $params = [];
                $params['doc_id'] = $id;
                $params['body'] = "#{$model->doc_number} buyurtma to'quv bo'limiga keldi";
                $params['users'] = ArrayHelper::getColumn($result, 'user_id');
                $params['module'] = 'base';
                $params['controllers'] = 'model_orders';
                $params['actions'] = 'view';
                $params['pharams'] = [
                    'id' => $id
                ];
                $params['telegram'] = [
                    376544097, //Doston
                    1047441270, //Sardor aka to'quv
                    555817684, //Sherzod aka to'quv
                    265441481, //G'ayrat aka
                ];
                $notice->saveNotice($params);
                $model->setAttributes([
                    'status' => ModelOrders::STATUS_PLANNED_TOQUV,
                    'responsible' => Yii::$app->user->id
                ]);
                $model->save(false);
            }
        }
        return $this->redirect(['view','id' => $id]);
    }
    public function actionSaveAndCheckedToquv($id){
        $model = $this->findModel($id);
        if($model->status === ModelOrders::STATUS_CHANGED_MATO){
            if($model->saveCheckedChangeMato()) {
                $model->saveOrderStatus(ModelOrdersStatus::STATUS_SEND_TOQUV);
                $sql = "SELECT user_id FROM auth_assignment WHERE item_name = 'toquv-master'";
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                $notice = new Notifications();
                $params = [];
                $params['doc_id'] = $id;
                $params['body'] = "#{$model->doc_number} buyurtma o'zgargan matolari tasdiqlandi";
                $params['users'] = ArrayHelper::getColumn($result, 'user_id');
                $params['module'] = 'base';
                $params['controllers'] = 'model_orders';
                $params['actions'] = 'view';
                $params['pharams'] = [
                    'id' => $id
                ];
                $params['telegram'] = [
                    376544097, //Doston
                    1047441270, //Sardor aka to'quv
                    555817684, //Sherzod aka to'quv
                    265441481, //G'ayrat aka
                ];
                $notice->saveNotice($params);
            }
        }
        return $this->redirect(['view','id' => $id]);
    }
    public function actionCancelledToquv($id){
        $model = $this->findModel($id);
        if($model->status === ModelOrders::STATUS_CHANGED_MATO){
            if($model->saveCancelledChangeMato()) {
                $add_info = Yii::$app->request->post('add_info') ?? '';
                $model->saveOrderStatus(ModelOrdersStatus::STATUS_SEND_TOQUV);
                $sql = "SELECT user_id FROM auth_assignment WHERE item_name = 'toquv-master'";
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                $notice = new Notifications();
                $params = [];
                $params['doc_id'] = $id;
                $params['body'] = "#{$model->doc_number} buyurtma o'zgargan matolari tasdiqlanmadi!!! Sababi: {$add_info}";
                $params['users'] = ArrayHelper::getColumn($result, 'user_id');
                $params['module'] = 'base';
                $params['controllers'] = 'model_orders';
                $params['actions'] = 'view';
                $params['pharams'] = [
                    'id' => $id
                ];
                $params['telegram'] = [
                    376544097, //Doston
                    1047441270, //Sardor aka to'quv
                    555817684, //Sherzod aka to'quv
                    265441481, //G'ayrat aka
                ];
                $notice->saveNotice($params);
            }
        }
        return $this->redirect(['view','id' => $id]);
    }
    public function actionSaveAndCheckedToquvAks($id){
        $model = $this->findModel($id);
        if($model->status === ModelOrders::STATUS_CHANGED_AKS){
            if($model->saveCheckedChangeMato(ToquvRawMaterials::ACS)) {
                $model->saveOrderStatus(ModelOrdersStatus::STATUS_SEND_TOQUV_AKS);
                $sql = "SELECT user_id FROM auth_assignment WHERE item_name = 'toquv-master'";
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                $notice = new Notifications();
                $params = [];
                $params['doc_id'] = $id;
                $params['body'] = "#{$model->doc_number} buyurtma o'zgargan to'quv aksessuarlari tasdiqlandi";
                $params['users'] = ArrayHelper::getColumn($result, 'user_id');
                $params['module'] = 'base';
                $params['controllers'] = 'model_orders';
                $params['actions'] = 'view';
                $params['pharams'] = [
                    'id' => $id
                ];
                $params['telegram'] = [
                    376544097, //Doston
                    1047441270, //Sardor aka to'quv
                    555817684, //Sherzod aka to'quv
                    265441481, //G'ayrat aka
                ];
                $notice->saveNotice($params);
            }
        }
        return $this->redirect(['view','id' => $id]);
    }
    public function actionCancelledToquvAks($id){
        $model = $this->findModel($id);
        if($model->status === ModelOrders::STATUS_CHANGED_AKS){
            if($model->saveCancelledChangeMato(ToquvRawMaterials::ACS)) {
                $add_info = Yii::$app->request->post('add_info') ?? '';
                $model->saveOrderStatus(ModelOrdersStatus::STATUS_SEND_TOQUV_AKS);
                $sql = "SELECT user_id FROM auth_assignment WHERE item_name = 'toquv-master'";
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                $notice = new Notifications();
                $params = [];
                $params['doc_id'] = $id;
                $params['body'] = "#{$model->doc_number} buyurtma o'zgargan to'quv aksessuarlari tasdiqlanmadi!!! Sababi: {$add_info}";
                $params['users'] = ArrayHelper::getColumn($result, 'user_id');
                $params['module'] = 'base';
                $params['controllers'] = 'model_orders';
                $params['actions'] = 'view';
                $params['pharams'] = [
                    'id' => $id
                ];
                $params['telegram'] = [
                    376544097, //Doston
                    1047441270, //Sardor aka to'quv
                    555817684, //Sherzod aka to'quv
                    265441481, //G'ayrat aka
                ];
                $notice->saveNotice($params);
            }
        }
        return $this->redirect(['view','id' => $id]);
    }
    /**
     * @param $id
     * @return Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionSaveAndPlannedToquvAks($id){
        $model = $this->findModel($id);
        if($model->status < ModelOrders::STATUS_PLANNED_TOQUV_AKS){
            if($model->saveToquvAksOrders()) {
                $model->saveOrderStatus(ModelOrdersStatus::STATUS_SEND_TOQUV_AKS);
                $sql = "SELECT user_id FROM auth_assignment WHERE item_name = 'toquv-aksessuar'";
                $result = Yii::$app->db->createCommand($sql)->queryAll();
                $notice = new Notifications();
                $params = [];
                $params['doc_id'] = $id;
                $params['body'] = "#{$model->doc_number}- buyurtma to'quv aksessuar bo'limiga keldi";
                $params['users'] = ArrayHelper::getColumn($result, 'user_id');
                $params['module'] = 'base';
                $params['controllers'] = 'model_orders';
                $params['actions'] = 'view';
                $params['pharams'] = [
                    'id' => $id
                ];
                $params['telegram'] = [
                    376544097,//Doston
                    265441481,//G'ayrat aka
                    387874542 //Shoxruh to'quv aksessuar
                ];
                $notice->saveNotice($params);
                $model->setAttributes([
                    'status' => ModelOrders::STATUS_PLANNED_TOQUV_AKS,
                    'responsible' => Yii::$app->user->id
                ]);
                $model->save(false);
            }
        }
        return $this->redirect(['view','id' => $id]);
    }

    public function actionSaveDepartment($id){
        $model = ModelOrdersItems::findOne($id);
        $user_id = Yii::$app->user->id;
        if($model->created_by!=$user_id&&$user_id!=1){
            return $this->redirect('index');
        }
        $models = MoiRelDept::findAll(['model_orders_items_id'=>$id,'is_own'=>1]);
        $models_musteri = MoiRelDept::findAll(['model_orders_items_id'=>$id,'is_own'=>2]);
        if(Yii::$app->request->isAjax) {
            if(Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                $response['status'] = 1;
                $response['message'] = Yii::t('app','Xatolik yuz berdi!');
                if(empty($data)){
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app','Saved Successfully');
                    $response['dept_list'] = $model->deleteDepartments();
                }elseif ($model->saveDepartments($data)) {
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app','Saved Successfully');
                    $response['dept_list'] = $model->deptVal;
                }
                return $response;
            }
            return $this->renderAjax('save-department', [
                'id' => $id,
                'models' => $models,
                'models_musteri' => $models_musteri,
            ]);
        }
    }
    public function actionFinishDept()
    {
        $response = [];
        $response['status'] = 1;
        $response['message'] = Yii::t('app', 'Xatolik yuz berdi!');
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $model = ModelOrdersItems::findOne($data['model_orders_items_id']);
                Yii::$app->response->format = Response::FORMAT_JSON;
                if (empty($data)) {
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                    $response['dept_list'] = $model->deleteDepartments();
                    $model->finishDepartments();
                } elseif ($model->saveDepartments($data,3)) {
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                    $response['dept_list'] = $model->deptVal;
                    $model->finishDepartments();
                }
            }
        }
        return $response;
    }
    public function actionSaveDept()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 1;
        $response['message'] = Yii::t('app','Xatolik yuz berdi!');
        if(Yii::$app->request->isAjax){
            if($id = Yii::$app->request->post('id')){
                $dept = MoiRelDept::findOne($id);
                if($dept){
                    $dept->status = 3;
                    if($dept->save()){
                        $response['status'] = 0;
                        $response['message'] = Yii::t('app','Saved Successfully');
                    }
                }
            }
        }
        return $response;
    }
    public function actionAjax($id)
    {
        $model = new ModelOrders();
        $models = new ModelOrdersItems();
        $urlRemain = Url::to('ajax-request');
        if (Yii::$app->request->isAjax){
            return $this->renderAjax('ajax', [
                'model' => $model,
                'models' => $models,
                'i' => $id,
                'urlRemain' => $urlRemain,
            ]);
        }
        return $this->render('ajax', [
            'model' => $model,
            'models' => $models,
            'i' => $id,
            'urlRemain' => $urlRemain,
        ]);
    }
    public function actionSize($id,$num,$model_id=null)
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', "O'lchamlar topilmadi");
            $size_collection = SizeCollections::findOne($num);
            if($size_collection){
                $sizeList = $size_collection->getSizeList(true);
                $res = [];
                foreach ($sizeList as $key => $size) {
                    $res[$key] = [
                        'id' => $size['size_id'],
                        'name' =>  $size['size']['name']
                    ];
                }
                $response['size'] = $res;
                $response['status'] = 1;
                $response['message'] = 'Success';
            }
            return $response;
        }
    }
    public function actionGetPlan()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 0;
            $response['message'] = Yii::t('app', "Planlangan matolar topilmadi");
            $id = Yii::$app->request->post('id');
            $type = Yii::$app->request->post('type') ?? MoiRelDept::TYPE_MATO;
            $moi = ModelOrdersItems::findOne($id);
            if($moi){
                $planning = ModelOrdersPlanning::find()->alias('mop')->joinWith('toquvRawMaterials')->joinWith('colorPantone')->joinWith('color')->where(['mop.type'=>$type,'mop.model_orders_items_id'=>$moi['id']])->asArray()->orderBy(['mop.id'=>SORT_ASC])->all();
                if($planning) {
                    $response['planning'] = $planning;
                    $response['status'] = 1;
                    $response['message'] = 'Success';
                }
            }
            return $response;
        }
    }
    public function actionNewOrder($order_id)
    {
        $model = ModelOrders::findOne($order_id) ?? new ModelOrders();
        $models = new ModelOrdersItems();
        $urlRemain = Url::to('ajax-request');
        if (Yii::$app->request->isAjax){
            return $this->renderAjax('ajax', [
                'model' => $model,
                'models' => $models,
                'i' => $order_id,
                'urlRemain' => $urlRemain,
            ]);
        }
        return $this->render('ajax', [
            'model' => $model,
            'models' => $models,
            'i' => $order_id,
            'urlRemain' => $urlRemain,
        ]);
    }
    public function actionRegPlanning($id)
    {
        $model = $this->findModel($id);
        if($model->status >= $model::STATUS_PLANNED){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new ModelOrdersPlanning();
        $models->scenario = $models::SCENARIO_MATO;
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            if($model->savePlanning($data)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('reg-planning', [
            'model' => $model,
            'models' => $models
        ]);
    }
    public function actionUpdatePlanning($id)
    {
        $model = $this->findModel($id);
        if($model->status >= $model::STATUS_PLANNED){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new ModelOrdersPlanning();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            if($model->savePlanning($data)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('reg-planning', [
            'model' => $model,
            'models' => $models
        ]);
    }
    /**
     * @return array
     */
    public function actionSavePlanning()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        $response['message'] = Yii::t('app','Xatolik yuz berdi!');
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->post()){
                $data = Yii::$app->request->post('ModelOrdersPlanning');
                $order_item = ModelOrdersItems::findOne($data['model_orders_items_id']);
                if($order_item){
                    $response = $order_item->saveOnePlanning($data);
                }
            }
        }
        return $response;
    }

    public function actionToquvAksPlanning($id)
    {
        $model = $this->findModel($id);
        if($model->status >= $model::STATUS_PLANNED_TOQUV_AKS){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new ModelOrdersPlanning();
        $models->scenario = $models::SCENARIO_AKS;
        $models->type = \app\modules\base\models\MoiRelDept::TYPE_MATO_AKS;
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            if($model->savePlanningAks($data)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('toquv-aks-planning', [
            'model' => $model,
            'models' => $models
        ]);
    }
    public function actionRegToquv($id)
    {
        $model = $this->findModel($id);
        $user_id = Yii::$app->user->id;
        if($model->status > $model::STATUS_PLANNED){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new MoiRelDept();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $model->saveDept($data);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $models->company_categories_id = (CompanyCategories::findOne(['token'=>"TOQUV"])) ? CompanyCategories::findOne(['token'=>"TOQUV"])->toArray()['id'] : null;
        $models->toquv_departments_id = (ToquvDepartments::findOne(['token'=>"TOQUV_MATO_SEH"])) ? ToquvDepartments::findOne(['token'=>"TOQUV_MATO_SEH"])->toArray()['id'] : null;
        return $this->render('reg-toquv', [
            'model' => $model,
            'models' => $models,
        ]);
    }
    public function actionUpdateToquv($id)
    {
        $model = $this->findModel($id);
        if($model->status > $model::STATUS_PLANNED){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new MoiRelDept();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $model->saveDept($data);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $models->company_categories_id = (CompanyCategories::findOne(['token'=>"TOQUV"])) ? CompanyCategories::findOne(['token'=>"TOQUV"])->toArray()['id'] : null;
        $models->toquv_departments_id = (ToquvDepartments::findOne(['token'=>"TOQUV_MATO_SEH"])) ? ToquvDepartments::findOne(['token'=>"TOQUV_MATO_SEH"])->toArray()['id'] : null;
        return $this->render('update-toquv', [
            'model' => $model,
            'models' => $models,
        ]);
    }
    public function actionRegToquvAks($id)
    {
        $model = $this->findModel($id);
        if($model->status > $model::STATUS_PLANNED_TOQUV_AKS){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new MoiRelDept(['scenario' => MoiRelDept::SCENARIO_AKS]);
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $model->saveDept($data,$models::TYPE_MATO_AKS);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $models->company_categories_id = (CompanyCategories::findOne(['token'=>"TOQUV"])) ? CompanyCategories::findOne(['token'=>"TOQUV"])->toArray()['id'] : null;
        $models->toquv_departments_id = (ToquvDepartments::findOne(['token'=>"TOQUV_ACS_SEH"])) ? ToquvDepartments::findOne(['token'=>"TOQUV_ACS_SEH"])->toArray()['id'] : null;
        return $this->render('reg-toquv-aks', [
            'model' => $model,
            'models' => $models,
        ]);
    }
    public function actionPlanning($id)
    {
        $model = $this->findModel($id);
        $user_id = Yii::$app->user->id;
        if($model->created_by!=$user_id&&$user_id!=1){
            return $this->redirect(['view','id' => $id]);
        }
        $models = new ModelOrdersPlanning();
        return $this->render('view/planning', [
            'model' => $model,
            'models' => $models
        ]);
    }
    public function actionChangeAks($id)
    {
        $model = $this->findModel($id);
        $all_acs = BichuvAcs::find()->limit(100)->all();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $result = $model->saveAcsOrders($data['ModelOrdersItems']);
            if($result['status']==1){
                $model->saveOrderStatus(ModelOrdersStatus::STATUS_PLANNED_AKS);
            }
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $result;
            }
            if($result['status']==1) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('change-aks', [
            'model' => $model,
            'all_acs' => $all_acs,
        ]);
    }

    /**
     * @param $id
     * @return array
     */
    public function actionToquvAcs($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ml = ModelsList::findOne($id);
        $res = [];
        if($ml){
            $toquv_acs = $ml->toquvAcs;
            $bichuv_acs = $ml->modelsAcs;
            $materials = $ml->modelsRawMaterials;

            if(!empty($toquv_acs)){
                foreach ($toquv_acs as $key => $item) {
                    $res['toquv'][] = [
                        'id' => $item->wmsMatoInfo->toquvRawMaterials['id'],
                        'name' => $item->wmsMatoInfo->toquvRawMaterials['name'],
                        'code' => $item->wmsMatoInfo->toquvRawMaterials['code'],
                        'type' => $item->wmsMatoInfo->toquvRawMaterials->rawMaterialType['name'],
                    ];
                }
            }

            if(!empty($bichuv_acs)){
                foreach ($bichuv_acs as $key => $item) {
                    $rm = $item->bichuvAcs;
                    $res['bichuv'][] = [
                        'name' => $rm->name,
                        'code' => $rm->sku,
                        'type' => $rm->properties->value,
                    ];
                }
            }

            if(!empty($materials)){
                foreach ($materials as $key => $item) {
                    $rm = $item->rm;
                    $res['materials'][] = [
                        'name' => $rm->name,
                        'code' => $rm->code,
                        'type' => $rm->rawMaterialType->name,
                    ];
                }
            }
        }
        return $res;
    }
    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionChangeQuantity($id)
    {
        $model = $this->findModel($id);
        $models = new ModelOrdersPlanning();
        if (Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $model->sum_item_qty = array_sum(
                array_map(
                    function($v) {
                        return $v['items'] ? array_sum($v['items'])  : 0;
                    },
                    $data['ModelOrdersPlanning']
                ));
            $model->responsible = 1;
            $model->save();
            $model->saveChangeQuantity($data);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('change-quantity', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * @return Response
     */
    public function actionCanselItems()
    {
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');
            $item = ModelOrdersItems::findOne($id);
            if ($item) {
                ModelOrdersPlanning::updateAll(['status'=>2],['model_orders_items_id'=>$id]);
                $item->status = 2;
                if($item->save()){
                    $order = $item->modelOrders;
                    $order->sum_item_qty = $order->count;
                    $order->save(false);
                }
            }
            if (!empty($order)) {
                return $this->redirect(['view', 'id' => $order->id]);
            }
        }
    }
    public function actionStatus($id)
    {
        $toquv_orders = ToquvOrders::findOne($id);
        return $this->render('view/status',[
            'toquv_orders' => $toquv_orders
        ]);
    }
    public function actionAddRow()
    {
        $id = Yii::$app->request->post('id');
        $key = Yii::$app->request->post('key');
        $is_own = Yii::$app->request->post('is_own');
        return $this->renderAjax('save-department/ajax',[
            'id' => $id,
            'key' => $key,
            'is_own' => $is_own,
            'disabled' => false,
        ]);
    }
    /**
     * @param $q
     * @return array
     */
    public function actionAjaxRequest($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
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
                            'rotatsion' => $item['rotatsion'],
                            'prints' => $item['prints'],
                            'stone' => $item['stone'],
                            'brend_id' => $item['brend_id'],
                            'acs' => $res['acs'][$item['id']],
                            'toquv_acs' => $res['toquvAcs'][$item['id']]
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
                                'rotatsion' => $item['rotatsion'],
                                'prints' => $item['prints'],
                                'stone' => $item['stone'],
                                'brend_id' => $item['brend_id'],
                                'acs' => $res['acs'][$item['id']],
                                'toquv_acs' => $res['toquvAcs'][$item['id']]
                            ]);
                        }
                    }
                }
            }
            else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                ];
            }
        }
        return $response;
    }

    /**
     * Export to excel
     */
    public function actionExportExcel(){
        $session = Yii::$app->session;
        if ($session->has('_query')) {
            $sql = $session->get('_query');
        }

        $searchModel = new ModelOrdersSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /*$exportQuery = ModelOrders::find()->select([
            'id',
            'doc_number',
            'musteri_id',
            'reg_date',
            'add_info'
        ])->with('musteri')->all();
        if (isset($sql) && is_string($sql)) {
            $exportQuery = ModelOrders::findBySql($sql);
        }*/

        header('Content-Type: application/vnd.ms-excel');
        $filename = "Model Buyurtmalar ".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        Excel::export([
            'models' => $dataProvider->getModels(),
            'columns' => [
                'id',
                'doc_number',
                'reg_date',
                [
                    'attribute' => 'musteri_id',
                    'value' => function($model){
                        return $model->musteri->name;
                    }
                ],
                [
                    'attribute' => 'artikul',
                    'value' => function($model){
                        return $model->getModelArticles(true);
                    },
                ],
                [
                    'attribute' => 'sum_item_qty',
                    'label' => Yii::t('app', 'Quantity'),
                    'value' => function($model){
                        return (int)$model->sum_item_qty;
                    },
                ],
                'add_info',
                [
                    'attribute' => 'status',
                    'value' => function($model){
                        return (\app\modules\base\models\ModelOrders::getStatusList($model->status))?\app\modules\base\models\ModelOrders::getStatusList($model->status):$model->status;
                    },
                ],
                [
                    'attribute' => 'created_by',
                    'value' => function($model){
                        return $model->author->user_fio;
                    },
                ],
            ],
            /*'headers' => [
                'id' => 'Id',
            ],*/
            'autoSize' => true,
        ]);
    }
    public function actionGetModelVariations($id){
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $arr = [];
        $response['status'] = 0;
        $response['message'] = 'error';
        $color['results'] = [];
        $model = ModelsList::findOne($id);
        $response['baski'] = $model->baski;
        $response['prints'] = $model->prints;
        $response['stone'] = $model->stone;
        /*if(Yii::$app->request->isAjax){
            $sql = "SELECT mv.id, mv.name as mname, mv.code as mcode, r,g,b, mvc.is_main
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
                        padding-left:7px;padding-right:7px;border:1px solid;border-radius: 20px'></span> &nbsp; <b> " . $item['mname'] . " </b> <small>". $item['mcode'] ."</small>";
                        array_push($color['results'],[
                            'id' => $item['id'],
                            'text' => $name,
                        ]);
                        $arr['check'][$item['id']] = [$item['id']];
                    }else{
                        if(!array_key_exists($item['id'], $arr['check'])){
                            $name = "<span style='background:rgb(".$item['r'].",".$item['g'].",".$item['b']."); width:80px;
                        padding-left:5px;padding-right:5px;border:1px solid'></span>  &nbsp; <b> " . $item['mname'] . " </b> <small>". $item['mcode'] ."</small>";
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

        }*/
        return $response;
    }
    /**
     * Finds the ModelOrders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelOrders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelOrders::findOne($id)) !== null) {
            return $model;
        }
        \yii\helpers\VarDumper::dump('er',10,true); die;
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionCombineOrderItems($id)
    {
        $model_orders = Yii::$app->db->createCommand("SELECT DISTINCT model_orders_id  FROM toquv_rm_order_moi WHERE toquv_orders_id = {$id}")->queryAll();
        print_r($model_orders);
    }



    public function actionModelReport()
    {
        $searchModel = new ModelOrdersSearch();
        //$rm_type = new ToquvRawMaterialType();
        $model = $searchModel->searchModelReport(Yii::$app->request->queryParams);
        return $this->render('model-report', [
            'searchModel' => $searchModel,
            'model' => $model,
//            'rm_type' => $rm_type,
        ]);
    }

    public function actionConfirmBySupply($id) {
        $model = $this->findModel($id);

        $model->confirm_supply = 1;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Confirmed'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'An error occurred'));
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Taminot uchun item balance dan tanlangan mato qoldiq
     * ma'lumotlari va order id ga qarab items larini jo'natadi
     */
    public function actionGetMaterialData() {
        $request = Yii::$app->request;

        $response = [
            'success' => false,
            'message' => 'Method not allowed or request not ajax',
        ];

        if ($request->isPost && $request->isAjax) {
            try {
                $requestData = Json::decode($request->getRawBody());

                $validateModel = new DynamicModel(['orderId', 'selectedRows']);
                $validateModel->addRule(['orderId', 'selectedRows'], 'required');
                $validateModel->addRule(['orderId'], 'integer');
                $validateModel->addRule(['selectedRows'], 'each', ['rule' => ['integer']]);
                $validateModel->setAttributes([
                    'orderId' => $requestData['orderId'],
                    'selectedRows' => $requestData['keysSelectedRows'],
                ]);
                if (!$validateModel->validate()) {
                    $response['message'] = 'Data not validated!';
                } else {
                    $materialList = WmsItemBalanceSearch::getItemsByIds($validateModel['selectedRows']);
                    $orderItems = ModelOrdersItems::getItemsByModelOrdersId($validateModel['orderId']);
                    Yii::info($orderItems, 'info');
                    $response['success'] = true;
                    $response['message'] = 'Data found.';
                    $response['materialList'] = $materialList;
                    $response['orderItems'] = $orderItems;
                }


            } catch (InvalidArgumentException $exception) {
                $response['message'] = $exception->getMessage();
            }

            return $this->asJson($response);
        }

        return $this->asJson($response);
    }

    /**
     * Ta'minot uchun
     * matolarni aynan bir model_orders_items_id ga o'tkazib(bronlab) qo'yadi
     * @return Response
     */
    public function actionBookMaterial() {
        $request = Yii::$app->request;
        $response = [
            'success' => false,
            'message' => 'Method not allowed or request not ajax',
        ];

        if ($request->isPost && $request->isAjax) {
            $success = false;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $docItemsData = $request->post('bookingMaterial', []);
                $orderId = $request->post('orderId');

                if (!$docItemsData || !$orderId) {
                    $response['message'] = Yii::t('app', 'The data is blank');
                } else {
                    $success = true;
                }

                if ($success) {
                    // create WmsDocument
                    $wmsDocumentForChangeOrder = new WmsDocument(['scenario' => WmsDocument::SCENARIO_CHANGE_ORDER_BY_TAMINOT]);

                    $materialWarehouseId = HrDepartments::getDepartmentIdByToken(HrDepartments::TOKEN_MATERIAL_WAREHOUSE);
                    if (!$materialWarehouseId) {
                        $response['message'] = "Mato ombori bo'limi yaratilmagan";
                        $success = false;
                    } else {
                        $success = true;
                    }

                    $employeeForTaminot = HrDepartmentResponsiblePerson::getResponsiblePersonByDepartmentId(HrDepartments::getDepartmentIdByToken(HrDepartments::TOKEN_TAMINOT));
                    if ($success && !$employeeForTaminot) {
                        $response['message'] = "Taminot bo'limi uchun ma'sul shaxs mavjud emas yoki taminot bo'limi yaratilmagan";
                        $success = false;
                    }

                    $wmsDocumentForChangeOrder->setAttributes([
                        'document_type' => WmsDocument::DOCUMENT_TYPE_CHANGE_ORDER,
                        'doc_number' => "CHO-" . (WmsDocument::getLastId()) . "/" . date("Y"),
                        'reg_date' => date('d.m.Y'),
                        'from_department' => $materialWarehouseId,
                        'to_department' => $materialWarehouseId,
                        'department_id' => $materialWarehouseId,
                        'from_employee' => $employeeForTaminot['id'],
                        'to_employee' => $employeeForTaminot['id'],
                        'model_orders_id' => $orderId,
                        'add_info' => 'taminot',
                    ]);

                    if ($success && !$wmsDocumentForChangeOrder->save()) {
                        $response['message'] = 'Hujjat saqlanmadi';
                        $response['error'] = $wmsDocumentForChangeOrder->getErrors();
                        $success = false;
                    }

                    if ($success) {
                        // create WmsDocumentItems
                        foreach ($docItemsData as $docItem) {
                            $wibInstance = WmsItemBalance::findOne(['id' => $docItem['wms_item_balance_id']]);

                            if ($wibInstance) {
                                $docItemsForChangeOrder = new WmsDocumentItems(['scenario' => WmsDocumentItems::SCENARIO_CHANGE_ORDER]);
                                $docItemsForChangeOrder->setAttributes([
                                    'wms_document_id' => $wmsDocumentForChangeOrder['id'],
                                    'roll_count' => 1,
                                    'quantity' => $docItem['fact_quantity'],
                                    'fact_quantity' => $docItem['fact_quantity'],
                                    'dep_area' => $wibInstance['dep_area'],
                                    'party_no' => (string)$wibInstance['lot'],
                                    'musteri_party_no' => $wibInstance['musteri_party_no'],
                                    'entity_id' => $wibInstance['entity_id'],
                                    'entity_type' => $wibInstance['entity_type'],
                                    'musteri_id' => $wibInstance['musteri_id'],
                                    'to_musteri' => $wibInstance['to_musteri'],
                                    'incoming_pb_id' => $wibInstance['incoming_pb_id'],
                                    'incoming_price' => $wibInstance['incoming_price'],
                                    'old_model_orders_items_id' => $wibInstance['model_orders_items_id'],
                                    'model_orders_items_id' => $docItem['model_orders_items_id'],
                                ]);

                                $success = $docItemsForChangeOrder->save();
                                if (!$success) {
                                    $response['message'] = "Hujjat ilovasi saqlanmadi";
                                    $response['error'] = $docItemsForChangeOrder->getErrors();
                                }
                            } else {
                                $response['message'] = "Item balance da ma'lumot topilmadi'";
                                $success = false;
                                break;
                            }
                        }

                    }

                }

                // update item balance
                if ($success) {
                    // hujjat statusini saved qilamiz
                    $wmsDocument = WmsDocument::findOne(['id' => $wmsDocumentForChangeOrder['id']]);
                    $wmsDocument->status = WmsDocument::STATUS_SAVED;
                    if (!$wmsDocument->save()) {
                        $success = false;
                        $response['message'] = "Hujjatni statusini o'zgarishda xatolik!";
                    }

                    // item balance dan ayiramiz
                    if ($success) {
                        foreach ($wmsDocument->wmsDocumentItems as $documentItem) {
                            $decreasedItemBalance = new WmsItemBalance(['scenario' => WmsItemBalance::SCENARIO_CHANGE_ORDER]);
                            $data = [
                                'wms_document_id' => $wmsDocument['id'],
                                'reg_date' => date('Y-m-d H:i:s'),
                                'incoming_price' => $documentItem['incoming_price'],
                                'incoming_pb_id' => $documentItem['incoming_pb_id'],
                                'roll_count' => $documentItem['roll_count'],
                                'quantity' => $documentItem['fact_quantity'],
                                'from_department_id' => $wmsDocument['from_department'],
                                'to_department_id' => $wmsDocument['from_department'],
                                'from_dep_area' => $documentItem['dep_area'],
                                'to_dep_area' => $documentItem['dep_area'],
                                'add_info' => $wmsDocument['add_info'],
                                'department_id' => $wmsDocument['from_department'],
                                'dep_area' => $documentItem['dep_area'],
                                'lot' => $documentItem['party_no'],
                                'musteri_id' => $documentItem['musteri_id'],
                                'to_musteri' => $documentItem['to_musteri'],
                                'entity_id' => $documentItem['entity_id'],
                                'entity_type' => $documentItem['entity_type'],
                                'musteri_party_no' => $documentItem['musteri_party_no'],
                                'model_orders_items_id' => $documentItem['old_model_orders_items_id'],
                            ];
                            $decreasedItemBalance->setAttributes($data);

                            /** item balance dan ayirish*/
                            $success = WmsItemBalance::decreaseItem($decreasedItemBalance);

                            $increasedItemBalance = new WmsItemBalance(['scenario' => WmsItemBalance::SCENARIO_CHANGE_ORDER]);
                            $data = [
                                'wms_document_id' => $wmsDocument['id'],
                                'reg_date' => date('Y-m-d H:i:s'),
                                'incoming_price' => $documentItem['incoming_price'],
                                'incoming_pb_id' => $documentItem['incoming_pb_id'],
                                'roll_count' => $documentItem['roll_count'],
                                'quantity' => $documentItem['fact_quantity'],
                                'from_department_id' => $wmsDocument['from_department'],
                                'to_department_id' => $wmsDocument['from_department'],
                                'add_info' => $wmsDocument['add_info'],
                                'from_dep_area' => $documentItem['dep_area'],
                                'to_dep_area' => $documentItem['dep_area'],
                                'lot' => $documentItem['party_no'],
                                'department_id' => $wmsDocument['from_department'],
                                'dep_area' => $documentItem['dep_area'],
                                'to_musteri' => $wmsDocument->modelOrders->musteri_id,
                                'musteri_id' => $documentItem['musteri_id'],
                                'entity_id' => $documentItem['entity_id'],
                                'entity_type' => $documentItem['entity_type'],
                                'musteri_party_no' => $documentItem['musteri_party_no'],
                                'model_orders_items_id' => $documentItem['model_orders_items_id'],
                            ];
                            $increasedItemBalance->setAttributes($data);
                            /** item balance ga qo'shish*/
                            $success = $success && WmsItemBalance::increaseItem($increasedItemBalance);

                            if (!$success) {
                                Yii::error('-IB => ' . $decreasedItemBalance->getErrors(), 'save');
                                Yii::error('+IB => ' . $increasedItemBalance->getErrors(), 'save');
                                $response['message'] = "Tranzaksiya amalga oshmadi";
                                break;
                            }
                        }
                    }
                }

                if ($success) {
                    $transaction->commit();
                    $response['success'] = true;
                    $response['message'] = "Matolar bronlandi";
                } else {
                    $transaction->rollBack();
                    $response['success'] = false;
                }
            } catch (\Throwable $exception) {
                $response['message'] = "Ma'lumotlar saqlanmadi";
                $response['error'] = $exception->getMessage();
                $transaction->rollBack();
            }
        }

        return $this->asJson($response);
    }
}