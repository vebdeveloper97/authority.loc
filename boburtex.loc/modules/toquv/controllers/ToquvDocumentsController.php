<?php

namespace app\modules\toquv\controllers;

use app\models\Notifications;
use app\models\Users;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\toquv\models\MatoInfo;
use app\modules\toquv\models\Musteri;
use app\modules\toquv\models\SortName;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocItemsRelOrder;
use app\modules\toquv\models\ToquvDocumentBalanceSearch;
use app\modules\toquv\models\ToquvDocumentExpense;
use app\modules\toquv\models\ToquvDocumentItems;
use app\modules\toquv\models\ToquvDocumentItemsSearch;
use app\modules\toquv\models\ToquvInstructionItems;
use app\modules\toquv\models\ToquvInstructionRm;
use app\modules\toquv\models\ToquvIp;
use app\modules\toquv\models\ToquvItemBalance;
use app\modules\toquv\models\ToquvMatoItemBalance;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\ToquvSaldo;
use app\modules\toquv\models\Unit;
use app\modules\usluga\models\UslugaDocItems;
use moonland\phpexcel\Excel;
use Yii;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvDocumentsSearch;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\Url;
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
                throw new NotFoundHttpException(Yii::t('app', 'The requested pagyie does not exist.'));
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

        $entityType = $searchModel::ENTITY_TYPE_IP;
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
            case ToquvDocuments::DOC_TYPE_MOVING_ACS_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_MOVING;
                $entityType = $searchModel::ENTITY_TYPE_ACS;
                break;
            case ToquvDocuments::DOC_TYPE_INCOMING_ACS_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_INCOMING;
                $entityType = $searchModel::ENTITY_TYPE_ACS;
                break;
            case ToquvDocuments::DOC_TYPE_OUTCOMING_ACS_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_OUTCOMING;
                $entityType = $searchModel::ENTITY_TYPE_ACS;
                break;
            case ToquvDocuments::DOC_TYPE_WRITE_OFF_GOODS_MATO_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_WRITE_OFF_GOODS;
                $entityType = $searchModel::ENTITY_TYPE_MATO;
                break;
            case ToquvDocuments::DOC_TYPE_WRITE_OFF_GOODS_ACS_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_WRITE_OFF_GOODS;
                $entityType = $searchModel::ENTITY_TYPE_ACS;
                break;
            case ToquvDocuments::DOC_TYPE_INSIDE_MOVING_MATO_LABEL:
                $docType = ToquvDocuments::DOC_TYPE_INSIDE_MOVING;
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
    public function actionView($id,$own=false)
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
        if($this->slug == ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL){
            return $this->render("view/_view_{$this->slug}",
                [ 'model' => $this->findModel($id) ]
            );
        }
        $searchModel = new ToquvDocumentItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        if(Yii::$app->request->isAjax){
            return $this->renderAjax("view/_view_{$this->slug}", [
                'model' => $this->findModel($id),
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'own' => $own
            ]);
        }
        return $this->render("view/_view_{$this->slug}", [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'own' => $own
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
        $model->doc_number = "T-" . $lastId . "/" . date('Y');
        $mato_items = Yii::$app->request->post('tib_id');
        $model->from_department = Yii::$app->request->post('department_id') ?? '';
        if($this->slug==ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL) {
            $curr_date = strtotime(date('Y-m-d'). " 00:00:00");
            $lastParty = $model::find()->select('order')->where("created_at >= {$curr_date}")->andWhere(["entity_type" => ToquvDocuments::ENTITY_TYPE_MATO, 'document_type'=>ToquvDocuments::DOC_TYPE_MOVING])->andWhere(['is not','order',new \yii\db\Expression('null')])->orderBy(['id'=>SORT_DESC])->asArray()->one();
            $order = $lastParty && !empty($lastParty['order']) ? $lastParty['order'] + 1 : 1;
            $model->party = date('dmy')."/".$order;
            $model->order = $order;
        }
        if (Yii::$app->request->isPost && !Yii::$app->request->post('tib_id')) {
            $data = Yii::$app->request->post();
            $TDIModelName = ToquvDocumentItems::getModelName();
            $dataTDI = Yii::$app->request->post($TDIModelName, []);
            /*if (isset($data[$TDIModelName])) {
                unset($data[$TDIModelName]);
            }*/
            $models = [];
            foreach ($dataTDI as $key => $item) {
                $models[$key] = new ToquvDocumentItems($item);
            }
            if ($model->load($data)) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if($model->save()) {
                        $data['ToquvDocumentExpense']['document_id'] = $model->id;
                        if (!empty($data['ToquvDocumentExpense']['price']) && $data['ToquvDocumentExpense']['price'] > 0) {
                            if ($modelTDE->load($data) && $modelTDE->save()) {

                            }
                        }
                        $saved = true;
                        $flagToquvItems = false;
                        if(!empty($dataTDI)&&$saved) {
                            foreach ($dataTDI as $item) {
                                $flagToquvItems = false;
                                $modelDI = new ToquvDocumentItems();
                                $savedDataTDI = [];
                                $savedDataTDI[$TDIModelName] = $item;
                                $savedDataTDI[$TDIModelName]['toquv_document_id'] = $model->id;
                                $savedDataTDI[$TDIModelName]['unit_id'] = 2;
                                $savedDataTDI[$TDIModelName]['price_usd'] = !empty($item['price_usd']) ? $item['price_usd'] : 0;
                                $savedDataTDI[$TDIModelName]['price_sum'] = !empty($item['price_sum']) ? $item['price_sum'] : 0;
                                if ($this->slug == ToquvDocuments::DOC_TYPE_OUTCOMING_MATO_LABEL || $this->slug == ToquvDocuments::DOC_TYPE_INSIDE_MOVING_MATO_LABEL) {
                                    $savedDataTDI[$TDIModelName]['entity_type'] = ToquvDocuments::ENTITY_TYPE_MATO;
                                }
                                if ($this->slug == ToquvDocuments::DOC_TYPE_OUTCOMING_ACS_LABEL) {
                                    $savedDataTDI[$TDIModelName]['entity_type'] = ToquvDocuments::ENTITY_TYPE_ACS;
                                }
                                if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                    $saved = true;
                                    if ($this->slug == ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL ||
                                        $this->slug == ToquvDocuments::DOC_TYPE_INCOMING_MATO_LABEL ||
                                        $this->slug == ToquvDocuments::DOC_TYPE_MOVING_ACS_LABEL ||
                                        $this->slug == ToquvDocuments::DOC_TYPE_INCOMING_ACS_LABEL) {
                                        $TDIRO = new ToquvDocItemsRelOrder([
                                            'toquv_document_items_id' => $modelDI->id,
                                            'toquv_orders_id' => $item['order_id'],
                                            'toquv_rm_order_id' => $item['order_item_id'],
                                        ]);
                                        $TDIRO->save();
                                    }
                                    if (!empty($item['child']) && $saved) {
                                        foreach ($item['child'] as $key => $m) {
                                            $new_item = new ToquvDocumentItems([
                                                'entity_id' => $m['entity_id'],
                                                'entity_type' => ToquvDocuments::ENTITY_TYPE_IP,
                                                'quantity' => $m['quantity'],
                                                'lot' => $m['lot'],
                                                'unit_id' => 2,
                                                'toquv_document_id' => $model->id,
                                                'document_qty' => $m['quantity'],
                                                'tib_id' => $modelDI->id,
                                                'is_own' => 2
                                            ]);
                                            if ($new_item->save()) {
                                                $saved = true;
                                            } else {
                                                $saved = false;
                                                break 2;
                                            }
                                        }
                                    }
                                    $flagToquvItems = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
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
                    }
                    if($saved) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return $this->redirect(["view", 'id' => $model->id, 'slug' => $this->slug, 't' => $t, 'own' => true]);
                    }else{
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
            'models' => $models,
            'modelTDE' => $modelTDE,
            'mato_items' => $mato_items,
            'url' => null,
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
        $models = (!empty($model->toquvDocumentItems))?$model->toquvDocumentItems:[new ToquvDocumentItems()];
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
            if ($model->load($data)) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if($model->save()) {
                        $saved = true;
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
                        if(!empty($dataTDI)&&$saved) {
                            foreach ($dataTDI as $item) {
                                $modelDI = new ToquvDocumentItems();
                                $savedDataTDI = [];
                                $savedDataTDI[$TDIModelName] = $item;
                                $savedDataTDI[$TDIModelName]['toquv_document_id'] = $model->id;
                                $savedDataTDI[$TDIModelName]['price_usd'] = !empty($item['price_usd']) ? $item['price_usd'] : 0;
                                $savedDataTDI[$TDIModelName]['price_sum'] = !empty($item['price_sum']) ? $item['price_sum'] : 0;
                                if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                    $saved = true;
                                    if ($this->slug == ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL ||
                                        $this->slug == ToquvDocuments::DOC_TYPE_INCOMING_MATO_LABEL ||
                                        $this->slug == ToquvDocuments::DOC_TYPE_MOVING_ACS_LABEL ||
                                        $this->slug == ToquvDocuments::DOC_TYPE_INCOMING_ACS_LABEL) {
                                        $TDIRO = new ToquvDocItemsRelOrder([
                                            'toquv_document_items_id' => $modelDI->id,
                                            'toquv_orders_id' => $item['order_id'],
                                            'toquv_rm_order_id' => $item['order_item_id'],
                                        ]);
                                        $TDIRO->save();
                                    }
                                    if (!empty($item['child'])) {
                                        foreach ($item['child'] as $key => $m) {
                                            $new_item = new ToquvDocumentItems([
                                                'entity_id' => $m['entity_id'],
                                                'entity_type' => ToquvDocuments::ENTITY_TYPE_IP,
                                                'quantity' => $m['quantity'],
                                                'lot' => $m['lot'],
                                                'unit_id' => 2,
                                                'toquv_document_id' => $model->id,
                                                'document_qty' => $m['quantity'],
                                                'tib_id' => $modelDI->id,
                                                'is_own' => 2
                                            ]);
                                            if ($new_item->save()) {
                                                $saved = true;
                                            } else {
                                                $saved = false;
                                                break 2;
                                            }
                                        }
                                    }
                                    unset($modelDI);
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                    }
                    if($saved) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id, 'slug' => $this->slug, 't' => $t, 'own' => true]);
                    }else{
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
            'models' => $models,
            'modelTDE' => $modelTDE
        ]);
    }
    public function actionCancel($id)
    {
        $t = Yii::$app->request->get('t',1);
        $incoming_model = $this->findModel($id);
        $model = $this->findModel($incoming_model->parent_doc_id);
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if ($model->load($data) && $model->save()) {
                return $this->redirect(['view', 'id' => $incoming_model->id, 'slug' => $this->slug,'t' => $t, 'own' => true]);
            }
        }
        return $this->render('cancel', [
            'model' => $model,
            'incoming_id' => $incoming_model->id
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
        if ($model->status < 3) {
            switch ($model->document_type) {
                case 1:
                    $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                    if($this->slug == ToquvDocuments::DOC_TYPE_INCOMING_MATO_LABEL || $this->slug == ToquvDocuments::DOC_TYPE_INCOMING_ACS_LABEL) {
                        $flagIB = false;
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            if (!empty($TDItems)) {
                                //items loop
                                foreach ($TDItems as $item) {
                                    $mato = MatoInfo::findOne($item['entity_id']);
                                    if(!$mato){
                                        $res = [
                                            'status' => 'error',
                                            'message' => $item,
                                        ];
                                        Yii::info($res, 'save');
                                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                    }
                                    $flagIB = false;
                                    $item['department_id'] = $model->to_department;
                                    $item['musteri_id'] = $mato['musteri_id'];
                                    $lastRec = ToquvMatoItemBalance::getLastRecordMoving($item);
                                    $newItemBalance = new ToquvMatoItemBalance();
                                    //tekwirish
                                    if (!empty($lastRec)) {
                                        $attributesTIB['entity_id'] = $mato->id;
                                        $attributesTIB['entity_type'] = $item['entity_type'];
                                        $attributesTIB['is_own'] = $item['is_own'];
                                        $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                        $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                        $attributesTIB['document_id'] = $model->id;
                                        $attributesTIB['inventory'] = $lastRec['inventory'] + $item['quantity'];
                                        $attributesTIB['roll_inventory'] = $lastRec['roll_inventory'] + $item['roll_count'];
                                        $attributesTIB['quantity_inventory'] = $lastRec['quantity_inventory'] + $item['count'];
                                        $attributesTIB['lot'] = $item['lot'];
                                        $attributesTIB['count'] = $item['quantity'];
                                        $attributesTIB['roll_count'] = $item['roll_count'];
                                        $attributesTIB['quantity_count'] = $item['count'];
                                        $attributesTIB['department_id'] = $model->to_department;
                                        $attributesTIB['from_department'] = $model->from_department;
                                        $attributesTIB['from_musteri'] = $model->from_musteri;
                                        $attributesTIB['to_department'] = null;
                                        $attributesTIB['document_type'] = $model->document_type;
                                        $attributesTIB['musteri_id'] = $mato['musteri_id'];
                                        $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                    } else {
                                        $attributesTIB['entity_id'] = $mato->id;
                                        $attributesTIB['entity_type'] = $item['entity_type'];
                                        $attributesTIB['is_own'] = $item['is_own'];
                                        $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                        $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                        $attributesTIB['document_id'] = $model->id;
                                        $attributesTIB['inventory'] = $item['quantity'];
                                        $attributesTIB['roll_inventory'] = $item['roll_count'];
                                        $attributesTIB['quantity_inventory'] = $item['count'];
                                        $attributesTIB['lot'] = $item['lot'];
                                        $attributesTIB['count'] = $item['quantity'];
                                        $attributesTIB['roll_count'] = $item['roll_count'];
                                        $attributesTIB['quantity_count'] = $item['count'];
                                        $attributesTIB['department_id'] = $model->to_department;
                                        $attributesTIB['from_department'] = $model->from_department;
                                        $attributesTIB['from_musteri'] = $model->from_musteri;
                                        $attributesTIB['document_type'] = $model->document_type;
                                        $attributesTIB['musteri_id'] = $mato['musteri_id'];
                                        $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                    }
                                    $newItemBalance->setAttributes($attributesTIB);
                                    if ($newItemBalance->save()) {
                                        $flagIB = true;
                                    }else{
                                        $flagIB = false;
                                        break;
                                    }
                                }
                            }
                            if($flagIB){
                                $model->updateCounters(['status' => 2]);
                                $transaction->commit();
                            }else{
                                $transaction->rollBack();
                            }
                        }catch (\Exception $e){
                            Yii::info('Not saved kirim mato' . $e, 'save');
                        }
                    }else{
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
                                    'from_department' => $model->from_department,
                                    'document_type' => $model->document_type,
                                    'musteri_id' => $model->musteri_id,
                                    'is_own' => $item['is_own'],
                                    'reg_date' => date('Y-m-d H:i:s', strtotime($model->reg_date))
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
                        if ($flagIB) {
                            $model->updateCounters(['status' => 2]);
                        }
                    }
                    break;
                case 2:
                    if($this->slug == ToquvDocuments::DOC_TYPE_MOVING_MATO_LABEL || $this->slug == ToquvDocuments::DOC_TYPE_MOVING_ACS_LABEL){
                        $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                        $flagIB = false;
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            if(!empty($TDItems)){
                                //Har bir itemni ostatkadan kop kiritilmagaligini  tekwirish  uchun loop
                                foreach($TDItems as $item){
                                    $mato = MatoInfo::findOne($item['entity_id']);
                                    if(!$mato){
                                        $res = [
                                            'status' => 'error',
                                            'model' => $item,
                                            'message' => 'MatoInfo topilmadi'
                                        ];
                                        Yii::info($res, 'save');
                                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                    }
                                    $item['department_id'] = $model->from_department;
                                    $item['musteri_id'] = $mato['musteri_id'];
                                    $remain = ToquvMatoItemBalance::getLastRecordMovingMato($item, 'from');
                                    if(($remain['inventory'] - $item['quantity']) < 0){
                                        $lack_qty = $item['quantity'] - $remain['inventory'];
                                        Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                            ['id' => $item['id'], 'lack' => $lack_qty]));
//                                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                    }
                                }
                                //Qabul qiluvchi document clone olgan
                                $cloneAcceptDocModel = $model;
                                $cloneAccept = new ToquvDocuments();
                                $cloneAcceptDocModel->document_type = 1;
                                $cloneAcceptDocModel->status = 1;
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
                                    $mato = MatoInfo::findOne($item['entity_id']);
                                    $flagIB = false;
                                    if($isClone){
                                        $modelAcceptItems = new ToquvDocumentItems();
                                        $modelAcceptItems->setAttributes($item);
                                        $modelAcceptItems->toquv_document_id = $cloneAccept->id;
                                        if($modelAcceptItems->save()){}
                                    }
                                    $item['department_id'] = $model->from_department;
                                    $item['musteri_id'] = $mato['musteri_id'];
                                    $lastRec = ToquvMatoItemBalance::getLastRecordMoving($item);
                                    $ItemBalanceModel = new ToquvMatoItemBalance();
                                    //Tekshirish ostatka
                                    if(!empty($lastRec)&&($lastRec['inventory'] - $item['quantity'])>=0&&(($lastRec['roll_inventory'] - $item['roll_count'])>=0||$item['entity_type'] == ToquvDocuments::ENTITY_TYPE_ACS)&&($lastRec['quantity_inventory'] - $item['count'])>=0){
                                        $roll_count = $lastRec['roll_inventory'] - $item['roll_count'];
                                        if($roll_count<0){
                                            if($item['entity_type'] == ToquvDocuments::ENTITY_TYPE_ACS){
                                                $roll_count = 0;
                                            }
                                        }
                                        $attributesTIB['entity_id'] = $item['entity_id'];
                                        $attributesTIB['entity_type'] = $item['entity_type'];
                                        $attributesTIB['is_own'] = $item['is_own'];
                                        $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                        $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                        $attributesTIB['document_id'] = $model->id;
                                        $attributesTIB['inventory'] = $lastRec['inventory'] - $item['quantity'];
                                        $attributesTIB['roll_inventory'] = $roll_count;
                                        $attributesTIB['quantity_inventory'] = $lastRec['quantity_inventory'] - $item['count'];
                                        $attributesTIB['lot'] = $item['lot'];
                                        $attributesTIB['count'] = (-1) * $item['quantity'];
                                        $attributesTIB['roll_count'] = (-1) * $item['roll_count'];
                                        $attributesTIB['quantity_count'] = (-1) * $item['count'];
                                        $attributesTIB['department_id'] = $model->from_department;
                                        $attributesTIB['to_department'] = $model->to_department;
                                        $attributesTIB['from_department'] = null;
                                        $attributesTIB['document_type'] = $model->document_type;
                                        $attributesTIB['musteri_id'] = $item['musteri_id'];
                                        $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                        $ItemBalanceModel->setAttributes($attributesTIB);
                                        if($ItemBalanceModel->save()){
                                            $flagIB = true;
                                        }else{
                                            $flagIB = false;
                                            break;
                                        }
                                    }else{
                                        $res = [
                                            'status' => 'error',
                                            'message' => (($lastRec['inventory'] - $item['quantity'])>=0||($lastRec['roll_inventory'] - $item['roll_count'])>=0||($lastRec['quantity_inventory'] - $item['count'])>=0)?"Nimadir yetishmadi":"lastRec yo'q ko'chirish",
                                            'content' => $lastRec->toArray()
                                        ];
                                        Yii::info($res, 'save');
                                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                    }
                                    //Qabul qiluvchi uchun item balance yozish
                                    /*if(true){
                                        $item['department_id'] = $model->to_department;
                                        $item['musteri_id'] = ToquvItemBalance::getMusteriId($item['entity_id']);
                                        $inventory = ToquvItemBalance::getLastRecordMovingMato($item, 'to');
                                        $ItemBalanceModelAccept = new ToquvItemBalance();
                                        $ItemBalanceModelAccept->setAttributes([
                                            'entity_id' => $item['entity_id'],
                                            'entity_type' => $item['entity_type'],
                                            'is_own' => $item['is_own'],
                                            'price_usd' => $lastRec['price_usd'],
                                            'price_uzs' => $lastRec['price_uzs'],
                                            'document_id' => $cloneAccept->id,
                                            'inventory' => $inventory['inventory'] + $item['quantity'],
                                            'roll_inventory' => $inventory['roll_inventory'] + $item['roll_count'],
                                            'quantity_inventory' => $inventory['quantity_inventory'] + $item['count'],
                                            'lot' => $item['lot'],
                                            'count' => $item['quantity'],
                                            'roll_count' => $item['roll_count'],
                                            'quantity' => $item['count'],
                                            'department_id' => $model->to_department,
                                            'from_department' => $model->from_department,
                                            'document_type' => $model->document_type,
                                            'musteri_id' => $item['musteri_id'],
                                            'reg_date' => date('Y-m-d H:i:s', strtotime($model->reg_date)),
                                        ]);
                                        if($ItemBalanceModelAccept->save()){
                                            $flagIB = true;
                                        }
                                    }*/
                                }
                            }
                            if($flagIB){
                                $model->updateCounters(['status' => 2]);
                                $transaction->commit();
                            }else{
                                $transaction->rollBack();
                            }
                        }catch (\Exception $e){
                            Yii::info('Not saved kochirish mato' . $e, 'save');
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
                                        $attributesTIB['from_department'] = null;
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
                    if($this->slug == ToquvDocuments::DOC_TYPE_OUTCOMING_MATO_LABEL || $this->slug == ToquvDocuments::DOC_TYPE_OUTCOMING_ACS_LABEL){
                        $type = ToquvDocuments::ENTITY_TYPE_IP;
                        $TDItems = $model->getToquvDocumentItems()->where(['!=','entity_type',$type])->asArray()->all();
                        $collection = [];
                        $flagIB = false;
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            if (!empty($TDItems)) {
                                foreach ($TDItems as $item) {
                                    $mato = MatoInfo::findOne($item['entity_id']);
                                    if(!$mato){
                                        $res = [
                                            'status' => 'error',
                                            'model' => $item,
                                            'message' => 'MatoInfo topilmadi'
                                        ];
                                        Yii::info($res, 'save');
                                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                    }
                                    $flagIB = false;
                                    $item['department_id'] = $model->from_department;
                                    $data = ToquvMatoItemBalance::findOne($item['tib_id']);
                                    if($collection[$item['tib_id']]){
                                        $collection[$item['tib_id']] += $item['quantity'];
                                    }else{
                                        $collection[$item['tib_id']] = $item['quantity'];
                                    }
                                    if($data) {
                                        $lastEntity = [
                                            'entity_id' => $data['entity_id'],
                                            'entity_type' => $data['entity_type'],
                                            'lot' => $data['lot'],
                                            'department_id' => $data['department_id'],
                                            'is_own' => $data['is_own'],
                                            'musteri_id' => $data['musteri_id']
                                        ];
                                        $lastRec = ToquvMatoItemBalance::getLastRecordMoving($lastEntity);
                                        if (!empty($lastRec)) {
                                            if (($lastRec['inventory'] - $collection[$item['tib_id']]) < 0) {
                                                $lack_qty = $collection[$item['tib_id']] - $lastRec['inventory'];
                                                $doc_item = ToquvDocumentItems::findOne($item['id']);
                                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                                    ['id' => "<b>{$doc_item->matoInfo}</b>", 'lack' => $lack_qty]));
                                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                            }
                                        }
                                    }
                                }
                                foreach ($TDItems as $item) {
                                    $mato = MatoInfo::findOne($item['entity_id']);
                                    $flagIB = false;
                                    $ItemBalanceModel = new ToquvMatoItemBalance();
                                    $item['department_id'] = $model->from_department;
                                    $data = ToquvMatoItemBalance::findOne($item['tib_id']);
                                    if($data) {
                                        $lastEntity = [
                                            'entity_id' => $data['entity_id'],
                                            'entity_type' => $data['entity_type'],
                                            'lot' => $data['lot'],
                                            'department_id' => $data['department_id'],
                                            'is_own' => $data['is_own'],
                                            'musteri_id' => $data['musteri_id']
                                        ];
                                        $lastRec = ToquvMatoItemBalance::getLastRecordMoving($lastEntity);
                                        if (!empty($lastRec)&&($lastRec['inventory'] - $item['quantity'])>=0&&($lastRec['inventory'] - $item['quantity'])>=0&&(($lastRec['roll_inventory'] - $item['roll_count'])>=0||$item['entity_type'] == ToquvDocuments::ENTITY_TYPE_ACS)&&($lastRec['quantity_inventory'] - $item['count'])>=0) {
                                            $roll_count = $lastRec['roll_inventory'] - $item['roll_count'];
                                            if($roll_count<0){
                                                if($item['entity_type'] == ToquvDocuments::ENTITY_TYPE_ACS){
                                                    $roll_count = 0;
                                                }
                                            }
                                            $attributesTIB['entity_id'] = $item['entity_id'];
                                            $attributesTIB['entity_type'] = $item['entity_type'];
                                            $attributesTIB['is_own'] = $item['is_own'];
                                            $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                            $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                            $attributesTIB['document_id'] = $model->id;
                                            $attributesTIB['inventory'] = $lastRec['inventory'] - $item['quantity'];
                                            $attributesTIB['roll_inventory'] = $roll_count;
                                            $attributesTIB['quantity_inventory'] = $lastRec['quantity_inventory'] - $item['count'];
                                            $attributesTIB['lot'] = $item['lot'];
                                            $attributesTIB['count'] = (-1) * $item['quantity'];
                                            $attributesTIB['roll_count'] = (-1) * $item['roll_count'];
                                            $attributesTIB['quantity_count'] = (-1) * $item['count'];
                                            $attributesTIB['department_id'] = $lastRec['department_id'];
                                            $attributesTIB['to_department'] = $model['to_department'];
                                            $attributesTIB['to_musteri'] = $model['to_musteri'];
                                            $attributesTIB['from_department'] = null;
                                            $attributesTIB['musteri_id'] = $lastRec['musteri_id'];
                                            $attributesTIB['document_type'] = ToquvDocuments::DOC_TYPE_OUTCOMING;
                                            $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                            $ItemBalanceModel->setAttributes($attributesTIB);
                                            if ($ItemBalanceModel->save()) {
                                                $flagIB = true;
                                            }else{
                                                $flagIB = false;
                                                break;
                                            }
                                        }else{
                                            $res = [
                                                'status' => 'error',
                                                'message' => "lastRec yo'q chiqim",
                                                'content' => $item
                                            ];
                                            Yii::info($res, 'save');
                                            $flagIB = false;
                                            break;
                                        }
                                    }
                                }
                            }
                            if($flagIB){
                                $model->updateCounters(['status' => 2]);
                                $transaction->commit();
                            }else{
                                $transaction->rollBack();
                            }
                        }catch (\Exception $e){
                            Yii::info('Not saved chiqim mato' . $e, 'save');
                        }
                    }else{
                        $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                        $flagIB = false;
                        if(!empty($TDItems)){
                            //Har bir itemni ostatkadan kop kiritilmagaligini  tekwirish  uchun loop
                            $collection = [];
                            foreach($TDItems as $item){
                                $item['department_id'] = $model->from_department;
                                if($collection[$item['tib_id']]){
                                    $collection[$item['tib_id']] += $item['quantity'];
                                }else{
                                    $collection[$item['tib_id']] = $item['quantity'];
                                }
                                if($item['is_own'] == 1){
                                    $remain = ToquvItemBalance::getLastRecordMoving($item);
                                }else{
                                    $item['musteri_id'] = $model->musteri_id;
                                    $remain = ToquvItemBalance::getLastRecordMovingMusteri($item);
                                }

                                if(($remain['inventory'] - $collection[$item['tib_id']]) < 0){
                                    $lack_qty = $remain['inventory'] - $collection[$item['tib_id']];
                                    Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                        ['id' => "<b>".ToquvDocumentItems::findOne($item['id'])->getThreadName(true)."({$item['lot']})</b>", 'lack' => "<b>{$lack_qty}</b>"]));
                                    return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                }
                            }
                            //Qabul qiluvchi document clone olgan
                            $cloneAcceptDocModel = $model;
                            $cloneAccept = new ToquvDocuments();
                            $cloneAcceptDocModel->document_type = 1;
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
                                        $attributesTIB['from_department'] = null;
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
                                    $lastRecAccept = ToquvItemBalance::getLastRecordMoving($item);
                                    $inventory = ToquvItemBalance::getLastRecord($item);
                                    $ItemBalanceModel = new ToquvItemBalance();
                                    $ItemBalanceModel->setAttributes([
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
                                        'from_department' => $model->from_department,
                                        'to_department' => null,
                                        'document_type' => $model->document_type,
                                        'musteri_id' => $model->musteri_id,
                                        'reg_date' => date('Y-m-d H:i:s', strtotime($model->reg_date)),
                                    ]);
                                    if($ItemBalanceModel->save()){
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
                                    $attributesTIB['from_department'] = null;
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
                                    $attributesTIB['from_department'] = null;
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
                    if($this->slug == ToquvDocuments::DOC_TYPE_WRITE_OFF_GOODS_MATO_LABEL || $this->slug == ToquvDocuments::DOC_TYPE_WRITE_OFF_GOODS_ACS_LABEL){
                        $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                        $flagIB = false;
                        if(!empty($TDItems)){
                            //Har bir itemni ostatkadan kop kiritilmagaligini  tekwirish  uchun loop
                            foreach($TDItems as $item){
                                $mato = MatoInfo::findOne($item['entity_id']);
                                if(!$mato){
                                    $res = [
                                        'status' => 'error',
                                        'model' => $item,
                                        'message' => 'MatoInfo topilmadi'
                                    ];
                                    Yii::info($res, 'save');
                                    return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                }
                                $item['department_id'] = $model->from_department;
                                $item['musteri_id'] = $mato['musteri_id'];
                                $remain = ToquvMatoItemBalance::getLastRecordMovingMato($item, 'from');
                                if(($remain['inventory'] - $item['quantity']) < 0){
                                    $lack_qty = $item['quantity'] - $remain['inventory'];
                                    Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                        ['id' => $item['id'], 'lack' => $lack_qty]));
                                    return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                }
                            }
                            //items loop
                            foreach($TDItems as $item){
                                $mato = MatoInfo::findOne($item['entity_id']);
                                $flagIB = false;
                                $item['department_id'] = $model->from_department;
                                $item['musteri_id'] = $mato['musteri_id'];
                                $lastRec = ToquvMatoItemBalance::getLastRecordMoving($item);
                                $ItemBalanceModel = new ToquvMatoItemBalance();
                                //Tekshirish ostatka
                                if(!empty($lastRec)){
                                    $attributesTIB['entity_id'] = $item['entity_id'];
                                    $attributesTIB['entity_type'] = $item['entity_type'];
                                    $attributesTIB['is_own'] = $item['is_own'];
                                    $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                    $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                    $attributesTIB['document_id'] = $model->id;
                                    $attributesTIB['inventory'] = $lastRec['inventory'] - $item['quantity'];
                                    $attributesTIB['roll_inventory'] = $lastRec['roll_inventory'] - $item['roll_count'];
                                    $attributesTIB['quantity_inventory'] = $lastRec['quantity_inventory'] - $item['count'];
                                    $attributesTIB['lot'] = $item['lot'];
                                    $attributesTIB['count'] = (-1) * $item['quantity'];
                                    $attributesTIB['roll_count'] = (-1) * $item['roll_count'];
                                    $attributesTIB['quantity_count'] = (-1) * $item['count'];
                                    $attributesTIB['department_id'] = $model->from_department;
                                    $attributesTIB['to_department'] = $model->to_department;
                                    $attributesTIB['from_department'] = null;
                                    $attributesTIB['document_type'] = $model->document_type;
                                    $attributesTIB['musteri_id'] = $item['musteri_id'];
                                    $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                    $ItemBalanceModel->setAttributes($attributesTIB);
                                    if($ItemBalanceModel->save()){
                                        $flagIB = true;
                                    }
                                }else{
                                    $res = [
                                        'status' => 'error',
                                        'message' => "lastRec yo'q",
                                        'content' => $item
                                    ];
                                    Yii::info($res, 'save');
                                    return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                }
                            }
                        }
                        if($flagIB){
                            $model->updateCounters(['status' => 2]);
                        }
                    }else {
                        $TDItems = $model->getToquvDocumentItems()->asArray()->all();
                        $flagIB = false;
                        if (!empty($TDItems)) {
                            foreach ($TDItems as $item) {
                                $item['department_id'] = $model->from_department;
                                if ($item['is_own'] == 1) {
                                    $remain = ToquvItemBalance::getLastRecordMoving($item);
                                } else {
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
                                if ($item['is_own'] == 1) {
                                    $lastRec = ToquvItemBalance::getLastRecordMoving($item);
                                } else {
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
                                    $attributesTIB['from_department'] = null;
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
                    }
                    break;
                case 9:
                    if($this->slug == ToquvDocuments::DOC_TYPE_INSIDE_MOVING_MATO_LABEL){
                        $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $t]);
                        $type = ToquvDocuments::ENTITY_TYPE_IP;
                        $TDItems = $model->getToquvDocumentItems()->where(['!=','entity_type',$type])->asArray()->all();
                        $collection = [];
                        $flagIB = false;
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            if (!empty($TDItems)) {
                                foreach ($TDItems as $item) {
                                    $mato = MatoInfo::findOne($item['entity_id']);
                                    if(!$mato){
                                        $res = [
                                            'status' => 'error',
                                            'model' => $item,
                                            'message' => 'MatoInfo topilmadi'
                                        ];
                                        Yii::info($res, 'save');
                                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                    }
                                    $flagIB = false;
                                    $item['department_id'] = $model->from_department;
                                    $data = ToquvMatoItemBalance::findOne($item['tib_id']);
                                    if($collection[$item['tib_id']]){
                                        $collection[$item['tib_id']] += $item['quantity'];
                                    }else{
                                        $collection[$item['tib_id']] = $item['quantity'];
                                    }
                                    if($data) {
                                        $lastEntity = [
                                            'entity_id' => $data['entity_id'],
                                            'entity_type' => $data['entity_type'],
                                            'lot' => $data['lot'],
                                            'department_id' => $data['department_id'],
                                            'is_own' => $data['is_own'],
                                            'musteri_id' => $data['musteri_id']
                                        ];
                                        $lastRec = ToquvMatoItemBalance::getLastRecordMoving($lastEntity);
                                        if (!empty($lastRec)) {
                                            if (($lastRec['inventory'] - $collection[$item['tib_id']]) < 0) {
                                                $lack_qty = $collection[$item['tib_id']] - $lastRec['inventory'];
                                                $doc_item = ToquvDocumentItems::findOne($item['id']);
                                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                                    ['id' => "<b>{$doc_item->matoInfo}</b>", 'lack' => $lack_qty]));
                                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                            }
                                        }
                                    }
                                }
                                foreach ($TDItems as $item) {
                                    $mato = MatoInfo::findOne($item['entity_id']);
                                    $new_mato = MatoInfo::findOne([
                                        'entity_id' => $mato->entity_id,
                                        'entity_type' => $mato->entity_type,
                                        'pus_fine_id' => $mato->pus_fine_id,
                                        'thread_length' => $mato->thread_length,
                                        'finish_en' => $mato->finish_en,
                                        'finish_gramaj' => $mato->finish_gramaj,
                                        'type_weaving' => $mato->type_weaving,
                                        'musteri_id' => $model->to_musteri,
                                    ]);
                                    if(!$new_mato){
                                        $new_mato = new MatoInfo();
                                        $new_mato->attributes = $mato->attributes;
                                        $new_mato->musteri_id = $model->to_musteri;
                                        $new_mato->save();
                                    }
                                    $flagIB = false;
                                    $ItemBalanceModel = new ToquvMatoItemBalance();
                                    $item['department_id'] = $model->from_department;
                                    $data = ToquvMatoItemBalance::findOne($item['tib_id']);
                                    if($data&&$new_mato) {
                                        $lastEntity = [
                                            'entity_id' => $data['entity_id'],
                                            'entity_type' => $data['entity_type'],
                                            'lot' => $data['lot'],
                                            'department_id' => $data['department_id'],
                                            'is_own' => $data['is_own'],
                                            'musteri_id' => $data['musteri_id']
                                        ];
                                        $lastRec = ToquvMatoItemBalance::getLastRecordMoving($lastEntity);
                                        if (!empty($lastRec)&&($lastRec['inventory'] - $item['quantity'])>=0&&($lastRec['roll_inventory'] - $item['roll_count'])>=0) {
                                            $attributesTIB['entity_id'] = $item['entity_id'];
                                            $attributesTIB['entity_type'] = $item['entity_type'];
                                            $attributesTIB['is_own'] = $item['is_own'];
                                            $attributesTIB['price_usd'] = $lastRec['price_usd'];
                                            $attributesTIB['price_uzs'] = $lastRec['price_uzs'];
                                            $attributesTIB['document_id'] = $model->id;
                                            $attributesTIB['inventory'] = $lastRec['inventory'] - $item['quantity'];
                                            $attributesTIB['roll_inventory'] = $lastRec['roll_inventory'] - $item['roll_count'];
                                            $attributesTIB['quantity_inventory'] = $lastRec['quantity_inventory'] - $item['count'];
                                            $attributesTIB['lot'] = $item['lot'];
                                            $attributesTIB['count'] = (-1) * $item['quantity'];
                                            $attributesTIB['roll_count'] = (-1) * $item['roll_count'];
                                            $attributesTIB['quantity_count'] = (-1) * $item['count'];
                                            $attributesTIB['department_id'] = $model->from_department;
                                            $attributesTIB['to_department'] = null;
                                            $attributesTIB['to_musteri'] = $model['to_musteri'];
                                            $attributesTIB['from_musteri'] = $model['from_musteri'];
                                            $attributesTIB['from_department'] = null;
                                            $attributesTIB['musteri_id'] = $mato['musteri_id'];
                                            $attributesTIB['document_type'] = ToquvDocuments::DOC_TYPE_INSIDE_MOVING;
                                            $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                            $ItemBalanceModel->setAttributes($attributesTIB);
                                            if ($ItemBalanceModel->save()) {
                                                $flagIB = true;
                                            }else{
                                                $flagIB = false;
                                                break;
                                            }
                                        }else{
                                            $res = [
                                                'status' => 'error',
                                                'message' => "lastRec yo'q chiqim",
                                                'content' => $item
                                            ];
                                            Yii::info($res, 'save');
                                            $flagIB = false;
                                            break;
                                        }
                                        $newItemBalanceModel = new ToquvMatoItemBalance();
                                        $new_lastEntity = [
                                            'entity_id' => $new_mato->id,
                                            'entity_type' => $new_mato->entity_type,
                                            'lot' => $data['lot'],
                                            'department_id' => $data['department_id'],
                                        ];
                                        $new_lastRec = ToquvMatoItemBalance::getLastRecordMoving($new_lastEntity);
                                        $attributesTIB['entity_id'] = $new_mato->id;
                                        $attributesTIB['entity_type'] = $new_mato->entity_type;
                                        $attributesTIB['is_own'] = $item['is_own'];
                                        $attributesTIB['price_usd'] = $new_lastRec['price_usd'];
                                        $attributesTIB['price_uzs'] = $new_lastRec['price_uzs'];
                                        $attributesTIB['document_id'] = $model->id;
                                        $attributesTIB['lot'] = $item['lot'];
                                        $attributesTIB['count'] = $item['quantity'];
                                        $attributesTIB['roll_count'] = $item['roll_count'];
                                        $attributesTIB['quantity_count'] = $item['count'];
                                        $attributesTIB['department_id'] = $model->from_department;
                                        $attributesTIB['to_department'] = null;
                                        $attributesTIB['to_musteri'] = $model['to_musteri'];
                                        $attributesTIB['from_musteri'] = $model['from_musteri'];
                                        $attributesTIB['from_department'] = null;
                                        $attributesTIB['musteri_id'] = $model['to_musteri'];
                                        $attributesTIB['document_type'] = ToquvDocuments::DOC_TYPE_INSIDE_MOVING;
                                        $attributesTIB['reg_date'] = date('Y-m-d H:i:s', strtotime($model->reg_date));
                                        if (!empty($new_lastRec)) {
                                            $attributesTIB['inventory'] = $new_lastRec['inventory'] + $item['quantity'];
                                            $attributesTIB['roll_inventory'] = $new_lastRec['roll_inventory'] + $item['roll_count'];
                                            $attributesTIB['quantity_inventory'] = $new_lastRec['quantity_inventory'] + $item['count'];
                                        }else{
                                            $attributesTIB['inventory'] = $item['quantity'];
                                            $attributesTIB['roll_inventory'] = $item['roll_count'];
                                            $attributesTIB['quantity_inventory'] = $item['count'];
                                        }
                                        $newItemBalanceModel->setAttributes($attributesTIB);
                                        if ($newItemBalanceModel->save()) {
                                            $flagIB = true;
                                        }else{
                                            $flagIB = false;
                                            break;
                                        }
                                    }
                                }
                            }
                            if($flagIB){
                                $model->updateCounters(['status' => 2]);
                                $transaction->commit();
                            }else{
                                $transaction->rollBack();
                            }
                        }catch (\Exception $e){
                            Yii::info('Not saved chiqim mato' . $e, 'save');
                        }
                    }
                    break;
            }
        }
        if (Yii::$app->request->isAjax){
            return $this->renderAjax("view/_view_{$this->slug}", [
                'model' => $this->findModel($id),
            ]);
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
        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try {
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
            if($model->delete()){
                $saved = true;
            }else{
                $saved = false;
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
    public function actionGetInstructions()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        $response['data'] = [];
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            if (!empty($id)) {
                $result = ToquvDocuments::getInstructions($id);
                if ($result) {
                    foreach ($result as $item) {
                        $order = ($item['model_doc']) ? " ({$item['model_doc']} - {$item['model_musteri']})" : "";
                        $response['status'] = 1;
                        $name = "<b>{$item['doc']}</b> - <b>{$item['musteri']}</b> - {$item['date']}{$order}";
                        $response['data'][$item['id']] =  $name;
                    }
                }
            }
        }
        return $response;
    }
    public function actionGetItems()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $id = $data['id'];
            $dept = $data['dept'];
            if (!empty($id)) {
                $sql = "SELECT
                           tii.entity_id,
                           tip.name,
                           tii.thread_name,
                           tii.lot,
                           tn.name nename,
                           tt.name tname,
                           cl.name clname,
                           tii.musteri_id,
                           tii.is_own,
                           SUM(tii.fact) fact
                    FROM toquv_instruction_items tii
                    LEFT JOIN toquv_instructions ti ON tii.toquv_instruction_id = ti.id
                    LEFT JOIN toquv_orders tor ON tor.id = ti.toquv_order_id
                    LEFT JOIN musteri m on tor.musteri_id = m.id
                    LEFT JOIN model_orders mo ON tor.model_orders_id = mo.id
                    LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                    LEFT JOIN toquv_ip tip ON tip.id = tii.entity_id
                    LEFT JOIN toquv_ne tn on tip.ne_id = tn.id
                    LEFT JOIN toquv_thread tt on tip.thread_id = tt.id
                    LEFT JOIN toquv_ip_color cl ON tip.color_id = cl.id
                    WHERE (is_closed = 1) AND (ti.id = :id)
                    GROUP BY tii.entity_id, tii.thread_name, tii.lot, tii.musteri_id,tii.is_own
                        ";
                $result = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryAll();
            }
        }
        return $this->renderAjax('get-items',[
            'items' => $result,
            'dept' => $dept
        ]);
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
                        $sql  = "select tdi.quantity from toquv_doc_items_rel_order tdiro
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

    /**
     * @param $q
     * @param $index
     * @param $dept
     * @param $id
     * @return array
     * @throws Exception
     */
    public function actionAjaxRequestMatoMoving($q, $index, $dept, $type=null, $sort=null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $isOwn = Yii::$app->request->get('isOwn',1);
        $response = [];
        $response['results'] = [];
        $response['p'] = ['index' => $index];
        $tip = ($type==null)?ToquvDocuments::ENTITY_TYPE_MATO:$type;
        $st = ($sort==null)?SortName::findOne(['code'=>'SORT1'])['id']:$sort;
        if (!empty($q)) {
            $params = [];
            $params['query'] = $q;
            $params['dept'] = $dept;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchMatoMoving($params,$type=$tip,$sort=$st);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $pf = Yii::t('app','Pus Fine');
                    $lth = Yii::t('app','Thread Length');
                    $gr = Yii::t('app','Finish Gramaj');
                    $en = Yii::t('app','Finish En');
                    $order = Yii::t('app','Buyurtmachi');
                    $model_mushteri = (!empty($item['model_mushteri']))?" (<span style='color:red'>{$item['model_mushteri']}</span>)":'';
                    $name = "<b>{$item['mato_color']} <span style='color:lightblue;background-color: black;padding: 0 5px;'>{$item['mato']}</span></b> (<b>{$item['mushteri']}{$model_mushteri}</b>) (<b>{$item['pus_fine']}</b>) (<b>{$item['length']}</b> | <b>{$item['en']}</b> | <b>{$item['gramaj']}</b>)  <b><span style='color:lightblue;background-color: black;padding: 0 5px;;'>{$item['remain']}</span></b> kg";
                    $color = "<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> ".$item['c_pantone'];
                    $b_color = " <span style='background:{$item['b_color']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> {$item['color_id']}";

                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                        'entity_id' => $item['rmid'],
                        'order_id' => $item['order_id'],
                        'order_item_id' => $item['order_item_id'],
                        'index' => $index,
                        'remain' => $item['remain'],
                        'roll' => number_format($item['roll'],0,'.',''),
                        'lot' => $item['lot'],
                        'count' => $item['count'],
                        'color' => $color,
                        'b_color' => $b_color
                    ]);
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                    'remain' => 0,
                ];
            }
        }
        return $response;
    }
    public function actionAjaxRequestMatoIncoming($q, $index, $type=null, $servic=null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $isOwn = Yii::$app->request->get('isOwn',1);
        $response = [];
        $response['results'] = [];
        $response['p'] = ['index' => $index];
        $tip = ($type==null)?ToquvRawMaterials::MATO:$type;
        $servis = ($servic) ?? 1;
        if (!empty($q)) {
            $params = [];
            $params['query'] = $q;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchMatoIncoming($params,$tip,$servis);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $pf = Yii::t('app','Pus Fine');
                    $lth = Yii::t('app','Ip uz-i');
                    $gr = Yii::t('app','F.Gr-j');
                    $en = Yii::t('app','F.En');
                    $order = Yii::t('app','Buyurtma');
                    $dt = Yii::t('app',"Ko'rsatma");
                    $reg_date = date('d.m.Y H:i', strtotime($item['reg_date']));
                    $qty = number_format($item['quantity'],0, '.', '');
                    $tir_qty = number_format($item['tir_qty'],0, '.', '');
                    $musteri = ($servis==2)?$item['musteri']:'';
                    $name = "{$item['mato']} | {$item['pus_fine']} | {$lth} : {$item['length']} | {$gr} : {$item['gramaj']} | {$en} : {$item['en']}) ({$item['mushteri']} - {$qty} kg) ({$dt} : {$tir_qty} kg - {$reg_date}) ({$item['type_weaving']}) {$musteri}";

                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                        'entity_id' => $item['id'],
                        'order_id' => $item['order_id'],
                        'order_item_id' => $item['order_item_id'],
                        'index' => $index,
                    ]);
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                    'remain' => 0,
                ];
            }
        }
        return $response;
    }
    public function actionAjaxRequestChiqimMato($q,$index,$dept,$type=ToquvDocuments::ENTITY_TYPE_MATO)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $isOwn = Yii::$app->request->get('isOwn',1);
        $response = [];
        $response['results'] = [];
        $response['p'] = ['index' => $index];
        if (!empty($q)) {
            $params = [];
            $params['query'] = $q;
            $params['dept'] = $dept;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchMatoMoving($params,$type);
            $korsatma = Yii::t('app', 'Ko\'rsatma');
            $thread_length = Yii::t('app', 'Thread Length');
            $finish_en = Yii::t('app', 'Finish En');
            $finish_gramaj = Yii::t('app', 'Finish Gramaj');
            if (!empty($res)) {
                foreach ($res as $item) {
                    $pf = Yii::t('app','Pus Fine');
                    $lth = Yii::t('app','Thread Length');
                    $gr = Yii::t('app','Finish Gramaj');
                    $en = Yii::t('app','Finish En');
                    $order = Yii::t('app','Buyurtmachi');
                    $model_mushteri = (!empty($item['model_mushteri']))?" (<span style='color:red'>{$item['model_mushteri']}</span>)":'';
                    $name = "<b>{$item['mato_color']} <span style='color:lightblue;background-color: black;padding: 0 5px;'>{$item['mato']}</span></b> (<b>{$item['mushteri']}{$model_mushteri}</b>) (<b>{$item['pus_fine']}</b>) (<b>{$item['length']}</b> | <b>{$item['en']}</b> | <b>{$item['gramaj']}</b>)  <b><span style='color:lightblue;background-color: black;padding: 0 5px;;'>{$item['remain']}</span></b> kg";
                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                        'entity_id' => $item['rmid'],
                        'order_id' => $item['order_id'],
                        'order_item_id' => $item['order_item_id'],
                        'index' => $index,
                        'remain' => $item['remain'],
                        'roll' => number_format($item['roll']),
                        'lot' => $item['lot'],
                        'count' => $item['count'],
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
    public function actionAjaxRequestAcsMoving($q, $index, $dept, $id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $isOwn = Yii::$app->request->get('isOwn',1);
        $response = [];
        $response['results'] = [];
        $response['p'] = ['index' => $index];
        if (!empty($q)) {
            $params = [];
            $params['query'] = $q;
            $params['dept'] = $dept;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchAcsMoving($params);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $pf = Yii::t('app','Pus Fine');
                    $lth = Yii::t('app','Thread Length');
                    $gr = Yii::t('app','Finish Gramaj');
                    $en = Yii::t('app','Finish En');
                    $order = Yii::t('app','Buyurtmachi');
                    $dt = Yii::t('app',"Ko'rsatma sanasi");
                    $reg_date = date('d.m.Y H:i', strtotime($item['reg_date']));
                    $name = "{$item['mato']}|{$pf}:{$item['pus_fine']}|{$lth}:{$item['length']}|{$gr}:{$item['gramaj']}|{$en}:{$item['en']})({$order}:{$item['mushteri']})({$dt}:{$reg_date})";

                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                        'entity_id' => $item['rmid'],
                        'order_id' => $item['order_id'],
                        'order_item_id' => $item['order_item_id'],
                        'index' => $index,
                        'remain' => $item['remain']
                    ]);
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                    'remain' => 0,
                ];
            }
        }
        return $response;
    }
    public function actionAjaxRequestChiqimAcs($q,$index,$dept,$id=null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $isOwn = Yii::$app->request->get('isOwn',1);
        $response = [];
        $response['results'] = [];
        $response['p'] = ['index' => $index];
        if (!empty($q)) {
            $params = [];
            $params['query'] = $q;
            $params['dept'] = $dept;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchChiqimAcs($params);
            $korsatma = Yii::t('app', 'Ko\'rsatma');
            $thread_length = Yii::t('app', 'Thread Length');
            $finish_en = Yii::t('app', 'Finish En');
            $finish_gramaj = Yii::t('app', 'Finish Gramaj');
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = "{$item['mato']} - {$item['summa']} kg ({$item['musteri']}) ({$korsatma} {$item['qty']}) ({$thread_length} - {$item['thread_length']}, {$finish_en} - {$item['finish_en']}, {$finish_gramaj} - {$item['finish_gramaj']})";
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
    public function actionAjaxRequestChiqimMatoIp()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post('id');
        $index = Yii::$app->request->post('index');
        $text = Yii::$app->request->post('text');
        if (!empty($data)) {
            $items = ToquvInstructionItems::find()->where(['toquv_instruction_rm_id'=>$data,'is_own'=>2])->all();
            return $this->renderAjax('chiqim-mato-ip',[
                'items' => $items,
                'index' => $index,
                'text' => $text
            ]);
        }
        return $this->render('chiqim-mato-ip',[
            'items' => $items,
            'index' => $index,
            'text' => $text
        ]);
    }
    public function actionAjaxRequestInsideMoving($q, $index, $dept, $musteri, $type=null, $sort=1)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $isOwn = Yii::$app->request->get('isOwn',1);
        $response = [];
        $response['results'] = [];
        $response['status'] = 1;
        $tip = ($type==null)?ToquvDocuments::ENTITY_TYPE_MATO:$type;
        if (!empty($dept)) {
            $params = [];
            $params['musteri'] = $musteri;
            $params['dept'] = $dept;
            $st = ($sort==null)?SortName::findOne(['code'=>'SORT1'])['id']:$sort;
            $params['query'] = $q;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchMatoInsideMoving($params,$type=$tip,$st);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $pf = Yii::t('app','Pus Fine');
                    $lth = Yii::t('app','Thread Length');
                    $gr = Yii::t('app','Finish Gramaj');
                    $en = Yii::t('app','Finish En');
                    $order = Yii::t('app','Buyurtmachi');
                    $model_mushteri = (!empty($item['model_mushteri']))?" (<span style='color:red'>{$item['model_mushteri']}</span>)":'';
                    $name = "<b>{$item['mato']}</b> | {$pf}:<b>{$item['pus_fine']}</b> | {$lth}:<b>{$item['length']}</b> | {$en}:<b>{$item['en']}</b> | {$gr}:<b>{$item['gramaj']}</b>) ({$order}:<b>{$item['mushteri']}{$model_mushteri}</b>) <b>{$item['remain']}</b> kg";

                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                        'entity_id' => $item['rmid'],
                        'order_id' => $item['order_id'],
                        'order_item_id' => $item['order_item_id'],
                        'remain' => $item['remain'],
                        'index' => $index,
                        'roll' => number_format($item['roll'],0,'.',''),
                        'lot' => $item['lot'],
                        'count' => $item['count'],
                    ]);
                    $response['status'] = 0;
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                    'remain' => 0,
                ];
            }
        }
        return $response;
    }

    public function actionKirimMato($id)
    {
        $toquv_orders = ToquvOrders::findOne($id);
        if ($toquv_orders->status==ToquvOrders::STATUS_KIRIM_MATO){
            $model = new ToquvDocuments();
            $from_department = null;
            $to_department = ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SKLAD'])['id'];
            $model->from_department = $from_department;
            $model->to_department = $to_department;
            if(Users::findOne(20)){
                $model->to_employee = Users::findOne(20)['id'];
            }
            $type = ToquvRawMaterials::ENTITY_TYPE_MATO;
            $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
            $lastId = $lastId ? $lastId['id'] + 1 : 1;
            $model->doc_number = "MKI-". $lastId . "/" . date('d.m.Y');
            $model->reg_date = date('d.m.Y');
            $model->entity_type = $type;
            $model->document_type = $model::DOC_TYPE_INCOMING;
            $model->musteri_id = $toquv_orders->musteri_id;
            $model->add_info = $toquv_orders->comment;
            $models = [];
            if (!empty($toquv_orders->toquvRmOrders)) {
                $flagToquvItems = false;
                foreach ($toquv_orders->toquvRmOrders as $item) {
                    $flagToquvItems = false;
                    if ($item) {
                        $mato = MatoInfo::findOne([
                            'entity_id' => $item->toquv_raw_materials_id,
                            'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                            'pus_fine_id' => $item->toquv_pus_fine_id,
                            'thread_length' => $item->thread_length,
                            'finish_en' => $item->finish_en,
                            'finish_gramaj' => $item->finish_gramaj,
                            'type_weaving' => $item->type_weaving,
                            'musteri_id' => $item->toquvOrders->musteri_id,
                            'toquv_rm_order_id' => $item->id,
                        ]);
                        if ($mato) {
                            $mato->model_musteri_id = $item->toquvOrders->model_musteri_id;
                            $mato->model_code = $item->model_code;
                            $mato->color_pantone_id = $item->color_pantone_id;
                            $mato->save();
                        }
                        if (!$mato) {
                            $mato = new MatoInfo([
                                'entity_id' => $item->toquv_raw_materials_id,
                                'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                                'pus_fine_id' => $item->toquv_pus_fine_id,
                                'thread_length' => $item->thread_length,
                                'finish_en' => $item->finish_en,
                                'finish_gramaj' => $item->finish_gramaj,
                                'type_weaving' => $item->type_weaving,
                                'toquv_rm_order_id' => $item->id,
                                'musteri_id' => $item->toquvOrders->musteri_id,
                                'model_musteri_id' => $item->toquvOrders->model_musteri_id,
                                'model_code' => $item->model_code,
                                'color_pantone_id' => $item->color_pantone_id,
                            ]);
                            $mato->save();
                        }
                        if ($mato->hasErrors()) {
                            \yii\helpers\VarDumper::dump($mato->getErrors(), 10, true);
                            \yii\helpers\VarDumper::dump($item, 10, true);
                            die;
                        }
                    } else {
                        $flagToquvItems = false;
                        break;
                    }
                    if ($mato) {
                        $modelDI = ToquvDocumentItems::findOne(['toquv_document_id' => $model->id, 'entity_id' => $mato['id'], 'lot' => '1', 'entity_type' => $type]);
                        $modelDI = ($modelDI) ? $modelDI : new ToquvDocumentItems(['roll_count' => 0]);
                        $modelDI->setAttributes([
                            'toquv_document_id' => $model->id,
                            'entity_id' => $mato->id,
                            'entity_type' => ToquvDocuments::ENTITY_TYPE_MATO,
                            'quantity' => $modelDI->quantity + $item['quantity'],
                            'unit_id' => ($unit = Unit::findOne(['code' => 'KG'])) ? $unit['id'] : 2,
                            'document_qty' => $modelDI->document_qty + $item['quantity'],
                            'price_sum' => 1,
                            'price_usd' => 1,
                            'tib_id' => null,
                            'is_own' => 1,
                            'lot' => '1',
                            'roll_count' => $modelDI->roll_count + $item['count']
                        ]);
                        $models[] = $modelDI;
                    }
                }
            }
            $modelTDE = new ToquvDocumentExpense();
            return $this->render('create',[
                'model' => $model,
                'models' => $models,
                'modelTDE' => $modelTDE,
                'mato_items' => null,
                'url' => Url::to(['toquv-documents/create','slug'=>$this->slug])
            ]);
        }
    }
}
