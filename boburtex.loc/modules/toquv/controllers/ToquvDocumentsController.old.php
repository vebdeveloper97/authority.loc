<?php

namespace app\modules\toquv\controllers;

use app\models\Notifications;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\toquv\models\Musteri;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocItemsRelOrder;
use app\modules\toquv\models\ToquvDocumentBalanceSearch;
use app\modules\toquv\models\ToquvDocumentExpense;
use app\modules\toquv\models\ToquvDocumentItems;
use app\modules\toquv\models\ToquvDocumentItemsSearch;
use app\modules\toquv\models\ToquvIp;
use app\modules\toquv\models\ToquvItemBalance;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvSaldo;
use moonland\phpexcel\Excel;
use Yii;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvDocumentsSearch;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvDocumentsController implements the CRUD actions for ToquvDocuments model.
 */
class ToquvDocumentsController extends Controller
{

    public $slug;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id == 'view') {
            $this->enableCsrfValidation = false;
        }
        if (parent::beforeAction($action)) {

            $slug = Yii::$app->request->get('slug');
            $flag = false;
            if (!empty($slug)) {
                if (array_key_exists($slug, ToquvDocuments::getDocTypeBySlug())) {
                    $flag = true;
                    $this->slug = $slug;
                }
            }
            if (!$flag) {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
            if (Yii::$app->authManager->getPermission(Yii::$app->controller->id."/".Yii::$app->controller->action->id)) {
                if (!Yii::$app->user->can(Yii::$app->controller->id . "/" . $this->slug . "/" . Yii::$app->controller->action->id)) {
                    throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Lists all ToquvDocuments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvDocumentsSearch();

        $docType = "";
        $isOwn = Yii::$app->request->get('t',1);
        switch ($this->slug) {
            case ToquvDocuments::DOC_TYPE_INCOMING_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_INCOMING;
                break;
            case ToquvDocuments::DOC_TYPE_MOVING_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_MOVING;
                break;
            case ToquvDocuments::DOC_TYPE_SELLING_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_SELLING;
                break;
            case ToquvDocuments::DOC_TYPE_OUTCOMING_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_OUTCOMING;
                break;
            case ToquvDocuments::DOC_TYPE_VIRTUAL_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_VIRTUAL;
                break;
            case ToquvDocuments::DOC_TYPE_SERVICE_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_SERVICE;
                break;
            case ToquvDocuments::DOC_TYPE_WRITE_OFF_GOODS_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_WRITE_OFF_GOODS;
                break;
            case ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_MOVING;
                $entityType = $searchModel::ENTITY_TYPE_MATO;
                break;
            case ToquvDocuments::DOC_TYPE_INCOMING_MATO_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_INCOMING;
                $entityType = $searchModel::ENTITY_TYPE_MATO;
                break;
            case ToquvDocuments::DOC_TYPE_OUTCOMING_MATO_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_OUTCOMING;
                $entityType = $searchModel::ENTITY_TYPE_MATO;
                break;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $docType, $isOwn, $entityType);

        return $this->render("index/_index_{$this->slug}", [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExportExcel($filName, $modelName, $conditions = [], $columns = [], $headers = []){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "MyExcelReport_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        Excel::widget([
            'models' => ToquvDocumentItems::find()->all(),
            'mode' => 'export',
            'columns' => $columns,
            'headers' => $headers,
        ]);
    }
    /**
     * @param $id
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isPost) {
            $action = 'VSM';
            $data = Yii::$app->request->post();
            $oldModel = ToquvDocuments::findOne($id);
            $currentModel = $oldModel;
            $dept = ToquvDepartments::findOne(['token' => 'VIRTUAL_SKLAD']);
            $isGetAll = true;
            if ($dept !== null) {
                $VIRTUAL_SKLAD = $dept->id;
            }
            foreach ($data['Items'] as $key => $item) {
                if ($item['quantity'] <= 0) {
                    unset($data['Items'][$key]);
                    continue;
                }
                $item['department_id'] = $VIRTUAL_SKLAD;
                $remain = ToquvItemBalance::getLastRecordVS($item, $VIRTUAL_SKLAD, $id);
                if (($remain['inventory'] - $item['quantity']) < 0) {
                    $lack_qty = $item['quantity'] - $remain['inventory'];
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                        ['id' => $item['id'], 'lack' => $lack_qty]));
                    return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                }
            }
            if (!empty($data["Items"])) {
                if ($currentModel !== null) {
                    $model = new ToquvDocuments();
                    $model->attributes = $currentModel->attributes;
                    $model->status = 1;
                    $model->action = 10;
                    $curDate = date('Y');
                    $model->reg_date = date('Y-m-d H:i:s');
                    $model->doc_number = "TK{$oldModel->id}/{$curDate}";
                    if (!empty($data['Items'])) {
                        if ($model->save()) {
                            foreach ($data['Items'] as $item) {
                                $modelitems = new ToquvDocumentItems();
                                $modelitems->setAttributes($item);
                                $modelitems->toquv_document_id = $model->id;
                                if (!$modelitems->save()) {
                                    $res = [
                                        'status' => 'error',
                                        'message' => $modelitems->getErrors()
                                    ];
                                    Yii::info($res, 'save');
                                }
                                $isSaved = true;
                            }
                        }
                    }
                    $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                    $flagIB = false;
                    if (!empty($TDItems)) {
                        foreach ($TDItems as $item) {
                            $flagIB = false;
                            $ItemBalanceModel = new ToquvItemBalance();
                            $item['department_id'] = $model->to_department;
                            $lastRecVS = ToquvItemBalance::getLastRecordVS($item, $VIRTUAL_SKLAD, $id);
                            $item['department_id'] = $model->to_department;
                            $inventory = ToquvItemBalance::getLastRecord($item);
                            $attributesTIB = [
                                'inventory' => $inventory,
                                'entity_id' => $item['entity_id'],
                                'entity_type' => $item['entity_type'],
                                'count' => $item['quantity'],
                                'price_uzs' => $item['price_sum'],
                                'price_usd' => $item['price_usd'],
                                'document_id' => $id,
                                'lot' => $item['lot'],
                                'department_id' => $model->to_department,
                                'document_type' => $model->document_type,
                                'is_own' => $item['is_own'],
                                'reg_date' => date('Y-m-d H:i:s')
                            ];

                            $ItemBalanceModel->setAttributes($attributesTIB);
                            if ($ItemBalanceModel->save()) {
                                $flagIB = true;
                            }
                            $diff = $lastRecVS['inventory'] - $item['quantity'];
                            if ($diff <= 0) {
                                $isGetAll *= true;
                            } else {
                                $isGetAll *= false;
                            }
                            if ($action == 'VSM' && $lastRecVS) {
                                $mIBVSM = new ToquvItemBalance();
                                $cloneMI = $ItemBalanceModel;
                                $cloneMI->count = (-1) * $item['quantity'];
                                $cloneMI->inventory = $diff;
                                $cloneMI->department_id = $VIRTUAL_SKLAD;
                                $cloneMI->to_department = $model->to_department;
                                $cloneMI->document_type = 7;
                                $mIBVSM->attributes = $cloneMI->attributes;
                                if ($mIBVSM->save()) {
                                    $flagIB = true;
                                }
                            }
                        }
                    }
                    if ($flagIB) {
                        $model->updateCounters(['status' => 2]);
                    }
                    if ($isGetAll) {
                        $oldModel->updateCounters(['action' => 10]);
                    }
                }
            }
        }
        $searchModel = new ToquvDocumentItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        return $this->render("view/_view_{$this->slug}", [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Finds the ToquvDocuments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvDocuments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvDocuments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new ToquvDocuments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $t = Yii::$app->request->get('t',1);
        $model = new ToquvDocuments();
        $models = [new ToquvDocumentItems()];
        $modelTDE = new ToquvDocumentExpense();
        $model->reg_date = date('d.m.Y');
        $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->doc_number = "T" . $lastId . "/" . date('Y');
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $TDIModelName = ToquvDocumentItems::getModelName();
            $dataTDI = Yii::$app->request->post($TDIModelName, []);
            if (isset($data[$TDIModelName])) {
                unset($data[$TDIModelName]);
            }
            if ($model->load($data) && $model->save()) {
                $data['ToquvDocumentExpense']['document_id'] = $model->id;
                if (!empty($data['ToquvDocumentExpense']['price']) && $data['ToquvDocumentExpense']['price'] > 0) {
                    if ($modelTDE->load($data) && $modelTDE->save()) {

                    }
                }
                $flagToquvItems = false;
                foreach ($dataTDI as $item) {
                    $flagToquvItems = false;
                    $modelDI = new ToquvDocumentItems();
                    $savedDataTDI = [];
                    $savedDataTDI[$TDIModelName] = $item;
                    $savedDataTDI[$TDIModelName]['toquv_document_id'] = $model->id;
                    $savedDataTDI[$TDIModelName]['unit_id'] = 2;
                    $savedDataTDI[$TDIModelName]['price_usd'] = !empty($item['price_usd']) ? $item['price_usd'] : 0;
                    $savedDataTDI[$TDIModelName]['price_sum'] = !empty($item['price_sum']) ? $item['price_sum'] : 0;
                    if($this->slug==ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL || $this->slug==ToquvDocuments::DOC_TYPE_INCOMING_MATO_LABEL || $this->slug==ToquvDocuments::DOC_TYPE_OUTCOMING_MATO_LABEL){
                        $savedDataTDI[$TDIModelName]['entity_type'] = ToquvDocuments::ENTITY_TYPE_MATO;
                    }
                    if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                        if($this->slug==ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL || $this->slug==ToquvDocuments::DOC_TYPE_INCOMING_MATO_LABEL){
                            $TDIRO = new ToquvDocItemsRelOrder([
                                'toquv_document_items_id' => $modelDI->id,
                                'toquv_orders_id' => $item['toquv_orders_id'],
                                'toquv_rm_order_id' => $item['toquv_rm_order_id'],
                            ]);
                            $TDIRO->save();
                        }
                        $flagToquvItems = true;
                    }
                }
                /*if($flagToquvItems){
                    $notification = new Notifications();
                    $dataNotification = [];
                    $dataNotification['Notifications'] = [
                        'subject' => 'Kochirish',
                        'doc_id' => $model->id,
                        'dept_from' => $model->from_department,
                        'dept_to' => $model->to_department,
                        'from' => $model->from_employee,
                        'to' => $model->to_department,
                        'body' => 'Moving'
                    ];
                    if($notification->load($dataNotification) && $notification->save(false)){

                    }
                }*/

                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                return $this->redirect(["view", 'id' => $model->id, 'slug' => $this->slug,'t' => $t]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'models' => $models,
            'modelTDE' => $modelTDE
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id)
    {
        $t = Yii::$app->request->get('t',1);
        $model = $this->findModel($id);
        if (!empty($model->toquvDocumentItems)) {
            $models = $model->toquvDocumentItems;
        } else {
            $models = [new ToquvDocumentItems()];
        }
        if (!empty($model->toquvDocumentExpenses) && !empty($model->toquvDocumentExpenses[0])) {
            $modelTDE = $model->toquvDocumentExpenses[0];
        } else {
            $modelTDE = new ToquvDocumentExpense();
        }
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $TDIModelName = ToquvDocumentItems::getModelName();
            $dataTDI = Yii::$app->request->post($TDIModelName, []);
            if (isset($data[$TDIModelName])) {
                unset($data[$TDIModelName]);
            }
            if ($model->load($data) && $model->save()) {
                //delete old all data
                if (!empty($model->toquvDocumentItems)) {
                    foreach ($model->toquvDocumentItems as $item) {
                        $item->delete();
                    }
                }
                //delete old all data
                if (!empty($model->toquvDocumentExpenses)) {
                    foreach ($model->toquvDocumentExpenses as $item) {
                        $item->delete();
                    }
                }
                $data['ToquvDocumentExpense']['document_id'] = $model->id;
                if (!empty($data['ToquvDocumentExpense']['price']) && $data['ToquvDocumentExpense']['price'] > 0) {
                    if ($modelTDE->load($data) && $modelTDE->save()) {

                    }
                }
                foreach ($dataTDI as $item) {
                    $modelDI = new ToquvDocumentItems();
                    $savedDataTDI = [];
                    $savedDataTDI[$TDIModelName] = $item;
                    $savedDataTDI[$TDIModelName]['toquv_document_id'] = $model->id;
                    $savedDataTDI[$TDIModelName]['price_usd'] = !empty($item['price_usd']) ? $item['price_usd'] : 0;
                    $savedDataTDI[$TDIModelName]['price_sum'] = !empty($item['price_sum']) ? $item['price_sum'] : 0;
                    if($this->slug==ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL || $this->slug==ToquvDocuments::DOC_TYPE_INCOMING_MATO_LABEL || $this->slug==ToquvDocuments::DOC_TYPE_OUTCOMING_MATO_LABEL){
                        $savedDataTDI[$TDIModelName]['entity_type'] = ToquvDocuments::ENTITY_TYPE_MATO;
                    }
                    if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                        if($this->slug==ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL || $this->slug==ToquvDocuments::DOC_TYPE_INCOMING_MATO_LABEL){
                            $TDIRO = new ToquvDocItemsRelOrder([
                                'toquv_document_items_id' => $modelDI->id,
                                'toquv_orders_id' => $item['toquv_orders_id'],
                                'toquv_rm_order_id' => $item['toquv_rm_order_id'],
                            ]);
                            $TDIRO->save();
                        }
                        unset($modelDI);
                    }
                }
                return $this->redirect(['view', 'id' => $model->id, 'slug' => $this->slug,'t' => $t]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'models' => $models,
            'modelTDE' => $modelTDE
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id)
    {
        $action = Yii::$app->request->get('action', '');
        $t = Yii::$app->request->get('t',1);
        $model = $this->findModel($id);
        if ($model->status !== ToquvItemBalance::STATUS_SAVED) {
            switch ($model->document_type) {
                case 1:
                    if($this->slug == ToquvDocuments::DOC_TYPE_INCOMING_MATO_LABEL) {
                        $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                        $flagIB = false;
                        if (!empty($TDItems)) {
                            //items loop
                            foreach ($TDItems as $item) {
                                $flagIB = false;
                                $ItemBalanceModel = new ToquvItemBalance();
                                $item['department_id'] = $model->from_department;
                                $item['musteri_id'] = ToquvDocumentItems::getMusteri($item['id']);
                                $lastRec = ToquvItemBalance::getLastRecordMovingMusteri($item);
                                //tekwirish
                                if (!empty($lastRec)) {
                                    $attributesTIB['entity_id'] = $item['entity_id'];
                                    $attributesTIB['entity_type'] = $item['entity_type'];
                                    $attributesTIB['is_own'] = $item['is_own'];
                                    $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                    $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                    $attributesTIB['document_id'] = $model->id;
                                    $attributesTIB['inventory'] = $lastRec['inventory'] + $item['quantity'];
                                    $attributesTIB['lot'] = $item['lot'];
                                    $attributesTIB['count'] = $item['quantity'];
                                    $attributesTIB['department_id'] = $model->from_department;
                                    $attributesTIB['to_department'] = $model->to_department;
                                    $attributesTIB['document_type'] = $model->document_type;
                                    $attributesTIB['musteri_id'] = $item['musteri_id'];
                                    $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                    $ItemBalanceModel->setAttributes($attributesTIB);
                                    if ($ItemBalanceModel->save()) {
                                        $flagIB = true;
                                    }
                                } else {
                                    $attributesTIB['entity_id'] = $item['entity_id'];
                                    $attributesTIB['entity_type'] = $item['entity_type'];
                                    $attributesTIB['is_own'] = $item['is_own'];
                                    $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                    $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                    $attributesTIB['document_id'] = $model->id;
                                    $attributesTIB['inventory'] = $item['quantity'];
                                    $attributesTIB['lot'] = $item['lot'];
                                    $attributesTIB['count'] = $item['quantity'];
                                    $attributesTIB['department_id'] = $model->from_department;
                                    $attributesTIB['to_department'] = $model->to_department;
                                    $attributesTIB['document_type'] = $model->document_type;
                                    $attributesTIB['musteri_id'] = $item['musteri_id'];
                                    $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                    $ItemBalanceModel->setAttributes($attributesTIB);
                                    if ($ItemBalanceModel->save()) {
                                        $flagIB = true;
                                    }
                                }

                            }
                        }
                    }else{
                        $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                        $flagIB = false;
                        $flagSaldo = false;
                        $total = [];
                        $total['sum'] = 0;
                        $total['usd'] = 0;

                        if (!empty($TDItems)) {
                            foreach ($TDItems as $item) {
                                $flagIB = false;
                                $ItemBalanceModel = new ToquvItemBalance();
                                $item['department_id'] = $model->to_department;
                                if ($item['is_own'] == 1) {
                                    $inventory = ToquvItemBalance::getLastRecord($item);
                                    $total['sum'] += $item['price_sum'] * $item['quantity'];
                                    $total['usd'] += $item['price_usd'] * $item['quantity'];
                                } else {
                                    $item['musteri_id'] = $model->musteri_id;
                                    $inventory = ToquvItemBalance::getLastRecordWithMusteri($item);
                                }

                                $attributesTIB = [
                                    'inventory' => $inventory,
                                    'entity_id' => $item['entity_id'],
                                    'entity_type' => $item['entity_type'],
                                    'count' => $item['quantity'],
                                    'price_uzs' => $item['price_sum'],
                                    'price_usd' => $item['price_usd'],
                                    'document_id' => $model->id,
                                    'lot' => $item['lot'],
                                    'department_id' => $model->to_department,
                                    'document_type' => $model->document_type,
                                    'musteri_id' => $model->musteri_id,
                                    'is_own' => $item['is_own'],
                                    'reg_date' => date('Y-m-d H:i:s')
                                ];

                                $ItemBalanceModel->setAttributes($attributesTIB);
                                if ($ItemBalanceModel->save()) {
                                    $flagIB = true;
                                }
                            }

                            /*if($total['sum'] > 0){
                                $modelToquvSaldo = new ToquvSaldo();
                                $attrTS = [
                                    'credit1' => $total['sum'],
                                    'debit2' => $total['sum'],
                                    'musteri_id' => $model->musteri_id,
                                    'department_id' => $model->to_department,
                                    'operation' => 'INCOMING_WAREHOUSE',
                                    'comment' => 'incoming',
                                    'reg_date' => date('Y-m-d H:i:s'),
                                    'pb_id' => 1,
                                    'td_id' => $model->id
                                ];
                                $modelToquvSaldo->setAttributes($attrTS);
                                if($modelToquvSaldo->save()){
                                    $flagSaldo = true;
                                }
                            }
                            if($total['usd'] > 0){
                                $modelToquvSaldo = new ToquvSaldo();
                                $attrTS = [
                                    'credit1' => $total['usd'],
                                    'debit2' => $total['usd'],
                                    'musteri_id' => $model->musteri_id,
                                    'department_id' => $model->to_department,
                                    'operation' => 'INCOMING_WAREHOUSE',
                                    'comment' => 'incoming',
                                    'reg_date' => date('Y-m-d H:i:s'),
                                    'pb_id' => 2,
                                    'td_id' => $model->id
                                ];
                                $modelToquvSaldo->setAttributes($attrTS);
                                if($modelToquvSaldo->save()){
                                    $flagSaldo = true;
                                }
                            }*/
                        }
                    }
                    if ($flagIB) {
                        $model->updateCounters(['status' => 2]);
                    }
                    break;
                case 2:
                    if($this->slug == ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL){
                        $flagIB = false;
                        $date = date('d/m/Y');
                        foreach ($model->matoRemain as $item) {
                            $all_qty = $model->getRemain($item['tro_id'])[0]['remain'];
                            $remain = $item['summa'] - $all_qty;
                            if(($remain) < 0){
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                    ['id' => $item['mato']."({$item['doc_number']}) - {$item['summa']}", 'lack' => $remain]));
                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                            }
                        }
                        //Qabul qiluvchi document clone olgan
                        $musteri = Musteri::findOne(['token'=>'SAMO']);
                        $cloneAcceptDocModel = $model;
                        $cloneAccept = new ToquvDocuments();
                        $cloneAcceptDocModel->document_type = $model::DOC_TYPE_INCOMING;
                        $cloneAcceptDocModel->status = 1;
                        $cloneAcceptDocModel->doc_number = "TMO{$model->id}/{$date}";
                        $cloneAcceptDocModel->action = 2;
                        $cloneAcceptDocModel->musteri_id = ($musteri)?$musteri['id']:0;
                        $cloneAccept->attributes = $cloneAcceptDocModel->attributes;
                        $isClone = false;
                        if($cloneAccept->save()){
                            $isClone = true;
                        }
                        $TDItems = $model->toquvDocumentItems;
                        //items loop
                        foreach($TDItems as $item) {
                            $flagIB = false;
                            if ($isClone) {
                                $relOrder = $item->toquvDocItemsRelOrders;
                                $modelAcceptItems = new ToquvDocumentItems();
                                $modelAcceptItems->attributes = $item->attributes;
                                $modelAcceptItems->toquv_document_id = $cloneAccept->id;
                                $modelAcceptItems->document_qty = $item->quantity;
                                if ($modelAcceptItems->save()) {
                                    foreach ($relOrder as $rel) {
                                        $newRelOrder = new ToquvDocItemsRelOrder();
                                        $newRelOrder->attributes = $rel->attributes;
                                        $newRelOrder->toquv_document_items_id = $modelAcceptItems->id;
                                        $newRelOrder->save();
                                    }
                                    $flagIB = true;
                                }
                            }
                        }
                        if ($flagIB){
                            $model->updateCounters(['status' => 2]);
                        }
                    }else {
                        $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                        $flagIB = false;
                        if (!empty($TDItems)) {
                            $dept = ToquvDepartments::findOne(['token' => 'VIRTUAL_SKLAD']);
                            if ($dept !== null) {
                                $VIRTUAL_SKLAD = $dept->id;
                            }
                            //Har bir itemni ostatkadan kop kiritilmagaligini  tekwirish  uchun loop
                            foreach ($TDItems as $item) {
                                $item['department_id'] = $model->from_department;
                                $remain = ToquvItemBalance::getLastRecordMoving($item);
                                if (($remain['inventory'] - $item['quantity']) < 0) {
                                    $lack_qty = $item['quantity'] - $remain['inventory'];
                                    Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                        ['id' => $item['id'], 'lack' => $lack_qty]));
                                    return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                }
                            }
                            //items loop
                            foreach ($TDItems as $item) {
                                $flagIB = false;
                                $ItemBalanceModel = new ToquvItemBalance();
                                $item['department_id'] = $model->from_department;
                                $lastRec = ToquvItemBalance::getLastRecordMoving($item);
                                $lastRecVS = ToquvItemBalance::getLastRecordVS($item, $VIRTUAL_SKLAD, $id);
                                //tekwirish
                                if (($lastRec['inventory'] - $item['quantity']) >= 0) {
                                    if (!empty($lastRec)) {
                                        $attributesTIB['entity_id'] = $item['entity_id'];
                                        $attributesTIB['entity_type'] = $item['entity_type'];
                                        $attributesTIB['is_own'] = $item['is_own'];
                                        $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                        $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                        $attributesTIB['document_id'] = $model->id;
                                        $attributesTIB['inventory'] = $lastRec['inventory'] - $item['quantity'];
                                        $attributesTIB['lot'] = $item['lot'];
                                        $attributesTIB['count'] = (-1) * $item['quantity'];
                                        $attributesTIB['department_id'] = $model->from_department;
                                        $attributesTIB['to_department'] = $model->to_department;
                                        $attributesTIB['document_type'] = $model->document_type;
                                        $attributesTIB['musteri_id'] = $model->musteri_id;
                                        $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                        $ItemBalanceModel->setAttributes($attributesTIB);
                                        if ($ItemBalanceModel->save()) {
                                            $flagIB = true;
                                        }
                                    }

                                    if ($action == 'VSP') {
                                        $inventory = $item['quantity'];
                                        if ($lastRecVS) {
                                            $inventory = $item['quantity'] + $lastRecVS['inventory'];
                                        }
                                        $mIBVSP = new ToquvItemBalance();
                                        $cloneMI = $ItemBalanceModel;
                                        $cloneMI->count = $item['quantity'];
                                        $cloneMI->inventory = $inventory;
                                        $cloneMI->department_id = $VIRTUAL_SKLAD;
                                        $cloneMI->to_department = $model->to_department;
                                        $cloneMI->document_type = 7;
                                        $mIBVSP->attributes = $cloneMI->attributes;
                                        if ($mIBVSP->save()) {
                                            $flagIB = true;
                                        }
                                    }
                                    if ($action == 'VPM') {
                                        if ($lastRecVS) {
                                            $mIBVSM = new ToquvItemBalance();
                                            $cloneMI = $ItemBalanceModel;
                                            $cloneMI->count = (-1) * $item['quantity'];
                                            $cloneMI->inventory = $lastRecVS['quantity'] - $item['quantity'];
                                            $cloneMI->department_id = $VIRTUAL_SKLAD;
                                            $cloneMI->to_department = $model->to_department;
                                            $cloneMI->document_type = 7;
                                            $mIBVSM->attributes = $cloneMI->attributes;
                                            if ($mIBVSM->save()) {
                                                $flagIB = true;
                                            }
                                        }
                                    }

                                } else {
                                    $lack_qty = $item['quantity'] - $lastRec['inventory'];
                                    Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti', ['id' => $item['id'], 'lack' => $lack_qty]));
                                }

                            }
                        }
                        if ($flagIB) {
                            $model->updateCounters(['status' => 2]);
                        }
                        /*$cloneDocModel = $model;
                        $clone = new ToquvDocuments();
                        $cloneDocModel->document_type = 7;
                        $cloneDocModel->status = 1;
                        $cYear = date('Y');
                        $cloneDocModel->doc_number = "TK{$model->id}/{$cYear}";
                        $cloneDocModel->action = 2;
                        $clone->attributes = $cloneDocModel->attributes;

                        if($clone->save()){
                            if(!empty($TDItems)){
                                foreach ($TDItems as $item){
                                    $docItems = new ToquvDocumentItems();
                                    $docItems->setAttributes(
                                        $item
                                    );
                                    $docItems->toquv_document_id = $clone->id;
                                    if(!$docItems->save()){
                                       $message = [
                                           'status' => 'error',
                                           'message' => $docItems->getErrors(),
                                       ];
                                       Yii::info($message,'save');
                                    }
                                }
                            }
                        }*/
                    }
                    break;
                case 5:
                    if($this->slug == ToquvDocuments::DOC_TYPE_OUTCOMING_MATO_LABEL){
                        $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                        $flagIB = false;
                        if (!empty($TDItems)) {
                            foreach ($TDItems as $item) {
                                $flagIB = false;
                                $ItemBalanceModel = new ToquvItemBalance();
                                $item['department_id'] = $model->from_department;
                                $data = ToquvItemBalance::findOne($item['tib_id']);
                                if($data){
                                    $lastEntity = [
                                        'entity_id' => $data['entity_id'],
                                        'entity_type' => $data['entity_type'],
                                        'lot' => $data['lot'],
                                        'department_id' => $data['department_id'],
                                        'is_own' => $data['is_own'],
                                        'musteri_id' => $data['musteri_id']
                                    ];
                                    $lastRec = ToquvItemBalance::getLastRecordMovingMusteri($lastEntity);
                                    if (!empty($lastRec)) {
                                        if(($lastRec['inventory'] - $item['quantity']) < 0){
                                            $lack_qty = $item['quantity'] - $lastRec['inventory'];
                                            $doc_item = ToquvDocumentItems::findOne($item['id']);
                                            Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                                ['id' => "<b>{$doc_item->mato->name}</b>", 'lack' => $lack_qty]));
                                            return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                        }
                                        $attributesTIB['entity_id'] = $item['entity_id'];
                                        $attributesTIB['entity_type'] = $item['entity_type'];
                                        $attributesTIB['is_own'] = $item['is_own'];
                                        $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                        $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                        $attributesTIB['document_id'] = $model->id;
                                        $attributesTIB['inventory'] = $lastRec['inventory'] - $item['quantity'];
                                        $attributesTIB['lot'] = $item['lot'];
                                        $attributesTIB['count'] = (-1) * $item['quantity'];
                                        $attributesTIB['department_id'] = $lastRec['department_id'];
                                        $attributesTIB['to_department'] = $lastRec['to_department'];
                                        $attributesTIB['musteri_id'] = $lastRec['musteri_id'];
                                        $attributesTIB['document_type'] = ToquvDocuments::DOC_TYPE_OUTCOMING;
                                        $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                        $ItemBalanceModel->setAttributes($attributesTIB);
                                        if ($ItemBalanceModel->save()) {
                                            $flagIB = true;
                                        }
                                    }
                                }
                            }
                        }
                        if ($flagIB) {
                            $model->updateCounters(['status' => 2]);
                        }
                    }else{
                        $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                        $flagIB = false;
                        if(!empty($TDItems)){
                            //Har bir itemni ostatkadan kop kiritilmagaligini  tekwirish  uchun loop
                            foreach($TDItems as $item){
                                $item['department_id'] = $model->from_department;
                                if($item['is_own'] == 1){
                                    $remain = ToquvItemBalance::getLastRecordMoving($item);
                                }else{
                                    $item['musteri_id'] = $model->musteri_id;
                                    $remain = ToquvItemBalance::getLastRecordMovingMusteri($item);
                                }
                                if(($remain['inventory'] - $item['quantity']) < 0){
                                    $lack_qty = $item['quantity'] - $remain['inventory'];
                                    Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                        ['id' => $item['id'], 'lack' => $lack_qty]));
                                    return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                }
                            }
                            //Qabul qiluvchi document clone olgan
                            $cloneAcceptDocModel = $model;
                            $cloneAccept = new ToquvDocuments();
                            $cloneAcceptDocModel->document_type = 2;
                            $cloneAcceptDocModel->status = 3;
                            $cYear = date('Y');
                            $cloneAcceptDocModel->doc_number = "TK{$model->id}/{$cYear}";
                            $cloneAcceptDocModel->action = 1;
                            $cloneAccept->attributes = $cloneAcceptDocModel->attributes;
                            $isClone = false;
                            if($cloneAccept->save()){
                                $isClone = true;
                            }
                            //items loop
                            foreach($TDItems as $item){
                                $flagIB = false;
                                if($isClone){
                                    $modelAcceptItems = new ToquvDocumentItems();
                                    $modelAcceptItems->setAttributes($item);
                                    $modelAcceptItems->toquv_document_id = $cloneAccept->id;
                                    if($modelAcceptItems->save()){

                                    }
                                }
                                $ItemBalanceModel = new ToquvItemBalance();
                                $item['department_id'] = $model->from_department;
                                if($item['is_own'] == 1){
                                    $lastRec = ToquvItemBalance::getLastRecordMoving($item);
                                }else{
                                    $item['musteri_id'] = $model->musteri_id;
                                    $lastRec = ToquvItemBalance::getLastRecordMovingMusteri($item);
                                }

                                //Tekshirish ostatka
                                if(true){
                                    if(!empty($lastRec)){
                                        $attributesTIB['entity_id'] = $item['entity_id'];
                                        $attributesTIB['entity_type'] = $item['entity_type'];
                                        $attributesTIB['is_own'] = $item['is_own'];
                                        $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                        $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                        $attributesTIB['document_id'] = $model->id;
                                        $attributesTIB['inventory'] = $lastRec['inventory'] - $item['quantity'];
                                        $attributesTIB['lot'] = $item['lot'];
                                        $attributesTIB['count'] = (-1) * $item['quantity'];
                                        $attributesTIB['department_id'] = $model->from_department;
                                        $attributesTIB['to_department'] = $model->to_department;
                                        $attributesTIB['document_type'] = $model->document_type;
                                        $attributesTIB['musteri_id'] = $model->musteri_id;
                                        $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                        $ItemBalanceModel->setAttributes($attributesTIB);
                                        if($ItemBalanceModel->save()){
                                            $flagIB = true;
                                        }
                                    }
                                }
                                //Qabul qiluvchi uchun item balance yozish
                                if(true){
                                    $item['department_id'] = $model->to_department;
                                    $inventory = ToquvItemBalance::getLastRecord($item);
                                    $ItemBalanceModelAccept = new ToquvItemBalance();
                                    $ItemBalanceModelAccept->setAttributes([
                                        'entity_id' => $item['entity_id'],
                                        'entity_type' => $item['entity_type'],
                                        'is_own' => $item['is_own'],
                                        'price_usd' => $lastRec['price_usd'],
                                        'price_uzs' => $lastRec['price_uzs'],
                                        'document_id' => $cloneAccept->id,
                                        'inventory' => $inventory,
                                        'lot' => $item['lot'],
                                        'count' => $item['quantity'],
                                        'department_id' => $model->to_department,
                                        'document_type' => $model->document_type,
                                        'musteri_id' => $model->musteri_id,
                                        'reg_date' => date('Y-m-d H:i:s', strtotime($model->reg_date)),
                                    ]);
                                    if($ItemBalanceModelAccept->save()){
                                        $flagIB = true;
                                    }
                                }
                            }
                        }
                        if($flagIB){
                            $model->updateCounters(['status' => 2]);
                        }
                    }
                    break;
                case 6:
                    $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                    $flagIB = false;
                    if (!empty($TDItems)) {
                        foreach ($TDItems as $item) {
                            $flagIB = false;
                            $ItemBalanceModel = new ToquvItemBalance();
                            $item['department_id'] = $model->from_department;
                            $lastRec = ToquvItemBalance::getLastRecordMoving($item);
                            if (true) {
                                if (!empty($lastRec)) {
                                    $attributesTIB['entity_id'] = $item['entity_id'];
                                    $attributesTIB['entity_type'] = $item['entity_type'];
                                    $attributesTIB['is_own'] = $item['is_own'];
                                    $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                    $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                    $attributesTIB['document_id'] = $model->id;
                                    $attributesTIB['inventory'] = $lastRec['inventory'] - $item['quantity'];
                                    $attributesTIB['lot'] = $item['lot'];
                                    $attributesTIB['count'] = (-1) * $item['quantity'];
                                    $attributesTIB['department_id'] = $model->from_department;
                                    $attributesTIB['to_department'] = $model->to_department;
                                    $attributesTIB['musteri_id'] = $model->musteri_id;
                                    $attributesTIB['document_type'] = 6;
                                    $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                    $ItemBalanceModel->setAttributes($attributesTIB);
                                    if ($ItemBalanceModel->save()) {
                                        $flagIB = true;
                                    }
                                }
                            }
                        }
                    }
                    if ($flagIB) {
                        $model->updateCounters(['status' => 2]);
                    }
                    break;
                case 7:
                    $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                    $flagIB = false;
                    if (!empty($TDItems)) {
                        foreach ($TDItems as $item) {
                            $flagIB = false;
                            $ItemBalanceModel = new ToquvItemBalance();
                            $item['department_id'] = $model->from_department;
                            $lastRec = ToquvItemBalance::getLastRecordMoving($item);
                            if (true) {
                                if (!empty($lastRec)) {
                                    $attributesTIB['entity_id'] = $item['entity_id'];
                                    $attributesTIB['entity_type'] = $item['entity_type'];
                                    $attributesTIB['is_own'] = $item['is_own'];
                                    $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                    $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                    $attributesTIB['document_id'] = $model->id;
                                    $attributesTIB['inventory'] = $lastRec['inventory'] + $item['quantity'];
                                    $attributesTIB['lot'] = $item['lot'];
                                    $attributesTIB['count'] = $item['quantity'];
                                    $attributesTIB['department_id'] = $model->from_department;
                                    $attributesTIB['to_department'] = $model->to_department;
                                    $attributesTIB['musteri_id'] = $model->musteri_id;
                                    $attributesTIB['document_type'] = 2;
                                    $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                    $ItemBalanceModel->setAttributes($attributesTIB);
                                    if ($ItemBalanceModel->save()) {
                                        $flagIB = true;
                                    }
                                }
                            }
                        }
                    }
                    if ($flagIB) {
                        $model->updateCounters(['status' => 2]);
                    }
                    break;
                case 8:
                    $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                    $flagIB = false;
                    if (!empty($TDItems)) {
                        foreach ($TDItems as $item) {
                            $item['department_id'] = $model->from_department;
                            if($item['is_own'] == 1){
                                $remain = ToquvItemBalance::getLastRecordMoving($item);
                            }else{
                                $item['musteri_id'] = $model->musteri_id;
                                $remain = ToquvItemBalance::getLastRecordMovingMusteri($item);
                            }
                            if (($remain['inventory'] - $item['quantity']) < 0) {
                                $lack_qty = $item['quantity'] - $remain['inventory'];
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                    ['id' => $item['id'], 'lack' => $lack_qty]));
                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                            }
                        }
                        foreach ($TDItems as $item) {
                            $flagIB = false;
                            $ItemBalanceModel = new ToquvItemBalance();
                            $item['department_id'] = $model->from_department;
                            if($item['is_own'] == 1){
                                $lastRec = ToquvItemBalance::getLastRecordMoving($item);
                            }else{
                                $item['musteri_id'] = $model->musteri_id;
                                $lastRec = ToquvItemBalance::getLastRecordMovingMusteri($item);
                            }
                            if (!empty($lastRec)) {
                                $attributesTIB['entity_id'] = $item['entity_id'];
                                $attributesTIB['entity_type'] = $item['entity_type'];
                                $attributesTIB['is_own'] = $item['is_own'];
                                $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                $attributesTIB['document_id'] = $model->id;
                                $attributesTIB['inventory'] = $lastRec['inventory'] - $item['quantity'];
                                $attributesTIB['lot'] = $item['lot'];
                                $attributesTIB['count'] = (-1) * $item['quantity'];
                                $attributesTIB['department_id'] = $model->from_department;
                                $attributesTIB['to_department'] = $model->to_department;
                                $attributesTIB['document_type'] = $model->document_type;
                                $attributesTIB['musteri_id'] = $model->musteri_id;
                                $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                $ItemBalanceModel->setAttributes($attributesTIB);
                                if ($ItemBalanceModel->save()) {
                                    $flagIB = true;
                                }
                            }
                        }
                    }
                    if ($flagIB) {
                        $model->updateCounters(['status' => 2]);
                    }
                    break;
            }
        }
        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $t]);
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
        $model = $this->findModel($id);

        if (!empty($model->toquvDocumentItems)) {
            foreach ($model->toquvDocumentItems as $item) {
                $item->delete();
            }
        }
        if (!empty($model->toquvSaldos)) {
            foreach ($model->toquvSaldos as $item) {
                $item->delete();
            }
        }
        $model->delete();

        return $this->redirect(['index', 'slug' => $this->slug]);
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public function actionGetDepartmentUser($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        if (!empty($id)) {
            $sql = "select u.id, u.user_fio
                    from toquv_user_department tud
                    left join users u on tud.user_id = u.id
                    where  tud.department_id = :id AND u.user_role <> 1 LIMIT 1;
                    ";
            $result = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
            if ($result) {
                $response['status'] = 1;
                $response['id'] = $result['id'];
                $response['name'] = $result['user_fio'];
            }
        }
        return $response;
    }

    /**
     * @param $q
     * @param $dept
     * @param $type
     * @param $index
     * @param int $musteri
     * @return array
     * @throws Exception
     */
    public function actionAjaxRequest($q, $dept, $type, $index, $musteri = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $isOwn = Yii::$app->request->get('isOwn',1);
        $response = [];
        $response['results'] = [];
        $response['p'] = ['index' => $index];
        if (!empty($q)) {
            $params = [];
            $params['entity_type'] = 1;
            $params['department_id'] = $dept;
            $params['query'] = $q;
            $params['is_own'] = $isOwn;
            $params['musteri'] = $musteri;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchEntities($params);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = "{$item['ipname']}-{$item['nename']} - {$item['thrname']} - {$item['clname']} ({$item['lot']})";
                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                        'summa' => $item['summa'],
                        'entity_id' => $item['entity_id'],
                        'index' => $index,
                        'lot' => $item['lot']
                    ]);
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                    'summa' => 0,
                    'tib_id' => 0,
                    'index' => null
                ];
            }
        }
        return $response;
    }
    public function actionAjaxRequestMato($q,$index,$id=null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $isOwn = Yii::$app->request->get('isOwn',1);
        $response = [];
        $response['results'] = [];
        $response['p'] = ['index' => $index];
        if (!empty($q)) {
            $params = [];
            $params['query'] = $q;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchMato($params);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $remain = $item['summa'] - $searchModel->getRemain($item['toquv_rm_order_id'])[0]['remain'];
                    $name = "{$item['mato']}({$item['pus_fine']}) - {$item['summa']} kg ({$item['musteri']} - {$item['doc_number']} - ".number_format($item['quantity'],0,'.','')." kg)";
                    if($id){
                        $sql = "select tdi.quantity from toquv_doc_items_rel_order tdiro
                                left join toquv_document_items tdi on tdiro.toquv_document_items_id = tdi.id
                                where tdiro.toquv_document_items_id = :id AND tdiro.toquv_rm_order_id = :rmid LIMIT 1;";
                        $tdir = Yii::$app->db->createCommand($sql)->bindValues(['id' => $id, 'rmid' => $item['toquv_rm_order_id']])->queryOne();

                        if($tdir['quantity']){
                            $remain = $tdir['quantity'] - $remain;
                        }
                    }
                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                        'summa' => $item['summa'],
                        'entity_id' => $item['id'],
                        'toquv_orders_id' => $item['toquv_orders_id'],
                        'toquv_rm_order_id' => $item['toquv_rm_order_id'],
                        'index' => $index,
                        'remain' => $remain
                    ]);
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                    'summa' => 0,
                ];
            }
        }
        return $response;
    }
    public function actionAjaxRequestChiqimMato($q,$index,$id=null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $isOwn = Yii::$app->request->get('isOwn',1);
        $response = [];
        $response['results'] = [];
        $response['p'] = ['index' => $index];
        if (!empty($q)) {
            $params = [];
            $params['query'] = $q;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchChiqimMato($params);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = "{$item['mato']} - {$item['summa']} kg ({$item['musteri']})";
                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                        'summa' => $item['summa'],
                        'entity_id' => $item['tir_id'],
                        'index' => $index,
                        'remain' => $item['summa']
                    ]);
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                    'summa' => 0,
                ];
            }
        }
        return $response;
    }
}
