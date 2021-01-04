<?php

namespace app\modules\bichuv\controllers;

use app\models\Constants;
use app\models\StockLimitInfo;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\Unit;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\bichuv\models\BichuvBeka;
use app\modules\bichuv\models\BichuvGivenRollItems;
use app\modules\bichuv\models\BichuvGivenRolls;
use app\modules\bichuv\models\BichuvNastelDetails;
use app\modules\bichuv\models\BichuvNastelLists;
use app\modules\bichuv\models\BichuvServiceItemBalance;
use app\modules\bichuv\models\BichuvTableRelWmsDoc;
use app\modules\bichuv\models\BichuvTablesEmployees;
use app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls;
use app\modules\hr\models\HrDepartmentResponsiblePerson;
use app\modules\hr\models\HrDepartments;
use app\modules\mobile\models\MobileDocDiffItems;
use app\modules\mobile\models\MobileProcessProduction;
use app\modules\mobile\models\MobileTables;
use app\modules\mobile\models\MobileTablesRelHrEmployee;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\TikuvDocItems;
use app\modules\tikuv\models\TikuvKonveyer;
use app\modules\tikuv\models\TikuvRmItems;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\usluga\models\UslugaDoc;
use app\modules\usluga\models\UslugaDocItems;
use app\modules\wms\models\WmsDepartmentArea;
use Throwable;
use Yii;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvDocumentItems;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\StaleObjectException;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\db\Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use app\modules\bichuv\models\BichuvItemBalance;
use app\modules\bichuv\models\BichuvRmItemBalance;
use app\modules\bichuv\models\BichuvSaldo;
use app\modules\bichuv\models\BichuvSliceItemBalance;
use app\modules\bichuv\models\BichuvSliceItems;
use app\modules\bichuv\models\BichuvSubDocItems;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocSearch;
use yii\helpers\ArrayHelper;
use app\modules\bichuv\models\BichuvDocExpense;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\modules\bichuv\models\BichuvAcceptedMatoFromProduction;
use app\modules\bichuv\models\BichuvPrintAndPatternItemBalance;
use app\components\PermissionHelper as P;

/**
 * DocController implements the CRUD actions for BichuvDoc model.
 */
class DocController extends Controller
{
    public $slug;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST', 'GET'],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $slug = Yii::$app->request->get('slug');
            $flag = false;
            if (!empty($slug)) {
                if (array_key_exists($slug, BichuvDoc::getDocTypeBySlug())) {
                    $flag = true;
                    $this->slug = $slug;
                }
            }
            if (!$flag) {
                throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
            }
            if (Yii::$app->authManager->getPermission(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
                if (!P::can(Yii::$app->controller->id . "/" . $this->slug . "/" . Yii::$app->controller->action->id)) {
                    throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionIndex()
    {
        $searchModel = new BichuvDocSearch();
        $docType = "";
        $entityType = 1;

        switch ($this->slug) {
            case BichuvDoc::DOC_TYPE_INCOMING_LABEL:
                $docType = BichuvDoc::DOC_TYPE_INCOMING;
                break;
            case BichuvDoc::DOC_TYPE_MOVING_LABEL:
                $docType = BichuvDoc::DOC_TYPE_MOVING;
                break;
            case BichuvDoc::DOC_TYPE_SELLING_LABEL:
                $docType = BichuvDoc::DOC_TYPE_SELLING;
                break;
            case BichuvDoc::DOC_TYPE_OUTGOING_LABEL:
                $docType = BichuvDoc::DOC_TYPE_OUTGOING;
                break;
            case BichuvDoc::DOC_TYPE_RETURN_LABEL:
                $docType = BichuvDoc::DOC_TYPE_RETURN;
                break;
            case BichuvDoc::DOC_TYPE_INCOMING_MATO_LABEL:
                $docType = BichuvDoc::DOC_TYPE_INCOMING;
                $entityType = 2;
                break;
            case BichuvDoc::DOC_TYPE_MOVING_MATO_LABEL:
                $docType = BichuvDoc::DOC_TYPE_MOVING;
                $entityType = 2;
                break;
            case BichuvDoc::DOC_TYPE_ACCEPTED_MATO_LABEL:

                $docType = BichuvDoc::DOC_TYPE_ACCEPTED;
                $entityType = 2;
                break;
            case BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL:
            case BichuvDoc::DOC_TYPE_MOVING_SLICE_TAY_LABEL:
            case BichuvDoc::DOC_TYPE_MOVING_SERVICE_LABEL:
            case BichuvDoc::DOC_TYPE_MOVING_SLICE_TO_PRINT_OR_PATTERN_LABEL:
                $docType = BichuvDoc::DOC_TYPE_MOVING;
                $entityType = 2;
                break;
            case BichuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL:
                $docType = BichuvDoc::DOC_TYPE_INSIDE;
                $entityType = 2;
                break;
            case BichuvDoc::DOC_TYPE_INCOMING_SLICE_LABEL:
                $docType = BichuvDoc::DOC_TYPE_ACCEPTED;
                $entityType = 2;
                break;
            case BichuvDoc::DOC_TYPE_REPAIR_MATO_LABEL:
                $docType = BichuvDoc::DOC_TYPE_REPAIR;
                $entityType = 2;
                break;
            case BichuvDoc::DOC_TYPE_ACCEPTED_LABEL:
                $docType = BichuvDoc::DOC_TYPE_ACCEPTED;
                $entityType = 1;
                break;
            case BichuvDoc::DOC_TYPE_SELLING_MATO_LABEL:
                $docType = BichuvDoc::DOC_TYPE_SELLING;
                $entityType = 2;
                break;
            case BichuvDoc::DOC_TYPE_NASTEL_PLAN_LABEL:
                $docType = BichuvDoc::DOC_TYPE_PLAN_NASTEL;
                $entityType = 1;
                break;
            case BichuvDoc::DOC_TYPE_ADJUSTMENT_LABEL:
                $docType = BichuvDoc::DOC_TYPE_ADJUSTMENT;
                $entityType = 2;
                break;
            case BichuvDoc::DOC_TYPE_MOVING_ACS_WITH_NASTEL_LABEL:
                $docType = BichuvDoc::DOC_TYPE_MOVING_ACS_WITH_NASTEL;
                $entityType = 1;
                break;

            case BichuvDoc::DOC_TYPE_ACCEPTED_SlICE_FROM_BICHUV_LABEL:
                $docType = BichuvDoc::DOC_TYPE_ACCEPTED_FROM_BICHUV;
                $entityType = 1;
                break;
            case BichuvDoc::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV_LABEL:
                $docType = BichuvDoc::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV;
                $entityType = 1;
                break;
            case BichuvDoc::DOC_TYPE_ACCEPTED_ACS_FROM_WAREHOUSE_LABEL:
                $docType = BichuvDoc::DOC_TYPE_ACCEPTED_ACS_FROM_WAREHOUSE;
                $entityType = 1;
                break;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $docType, $entityType);
        $tayyorlov = HrDepartments::findOne(['token'=> Constants::$TOKEN_TAYYORLOV])['id'];
        if(!empty($tayyorlov)&&in_array($tayyorlov,BichuvDoc::getDepartmentsBelongTo())){
            return $this->render("index/_kirim_kesim",
                [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
        }


        return $this->render("index/_index_{$this->slug}",
        [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BichuvDoc model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /**
     * Displays a single BichuvDoc model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new BichuvDocItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render("view/_view_{$this->slug}", [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionAcceptRm($id)
        {
            $bichuvDocItems = new BichuvDocItems();
            $model = $this->findModel($id);
            if($bichuvDocItems->load(Yii::$app->request->post())){
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try{
                    $data = Yii::$app->request->post()['BichuvDocItems'];
                    $items = $model->bichuvDocItems;
                    $modelOrderItemId = "";
                    if(!empty($data)){
                        $saved = BichuvDocItems::saveBichuvDocItemsDiff($model,$data);
                        if ($saved && !empty($items)){
                            foreach ($items as $key => $item) {
                                $modelOrderItemId = $item['model_orders_items_id'];
                                $item->scenario = BichuvDocItems::SCENARIO_ACCEPT_MATO;
                                $item['fact_quantity'] = $data[$key]['fact_quantity'];
                                $item['add_info'] = $data[$key]['add_info'];
                                $modelBRIB = new BichuvRmItemBalance();
                                if($item->save() && $modelBRIB->increaseItemBalance($modelBRIB,$model,$item)){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    Yii::$app->session->setFlash('error', Yii::t('app','Saqlanmadi'));
                                    break;
                                }
                            }
                        }
                        if ($saved) {
                            $btrwdId = BichuvTableRelWmsDoc::getBichuvTableRelWmsDocByNastelId($model['bichuv_nastel_list_id'], BichuvTableRelWmsDoc::STATUS_ACCEPTED);
                            $table_id = MobileTables::findOne(['token' => Constants::TOKEN_BICHUV_ACCEPTED_MATO])->id;
                            if(!empty($table_id)){
                                $paramsProccess = [
                                    'nastel_no' => $model->bichuvNastelList->name,
                                    'started_date' => date("d.m.Y H:i:s"),
                                    'ended_date' => date("d.m.Y H:i:s"),
                                    'status' => MobileProcessProduction::STATUS_ENDED,
                                    'doc_id' => $model->id,
                                    'table_name' => BichuvDoc::getTableSchema()->name,
                                    'mobile_tables_id' => $table_id,
                                    'model_orders_items_id' => $modelOrderItemId
                                ];
                                if ($btrwdId && MobileProcessProduction::saveMobileProcess($paramsProccess)) {
                                    $model->updateCounters(['status'=>2]);
                                    Yii::$app->session->setFlash('success', Yii::t('app', 'Qabul qilindi'));
                                }else{
                                    $saved = false;
                                }
                            }else{
                                $saved = false;
                                Yii::$app->session->setFlash('error', Yii::t('app','Jarayon mavjud emas'));
                            }
                        }
                        else{
                            Yii::$app->session->setFlash('error', Yii::t('app','Saqlanmadi'));
                        }
                    }

                    if($saved){
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $model->type]);
                    }else{
                        $transaction->rollBack();
                    }
                }catch(\Exception $e){
                    Yii::info('Not saved'.$e,'save');
                    $transaction->rollBack();
                }
            }
            
            return $this->render("view/_view_{$this->slug}", [
                'model' => $model,
                'bichuvDocItems' => $bichuvDocItems,
            ]);
        }
    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionReturn($id)
    {
        $model = $this->findModel($id);
        $newModel = new BichuvDoc();
        $models = $model->bichuvDocItems;
        $modelsId = "";
        $lastModel = count($models);
        foreach ($models as $key => $m) {
            if ($lastModel == ($key + 1)) {
                $modelsId .= "'{$m->entity_id}'";
            } else {
                $modelsId .= "'{$m->entity_id}', ";
            }
        }

        $params = [
            'id' => $modelsId,
            'type' => 1,
            'depId' => $model->to_department
        ];

        $results = $newModel->getRemain($params, $isAll = true);

        $results = ArrayHelper::index($results, 'entity_id');

        foreach ($models as $key => $m) {
            $models[$key]->document_quantity = $results[$m->entity_id]['inventory'];
        }
        $modelTDE = new BichuvDocExpense();
        $newModel->reg_date = date('d.m.Y');
        $lastId = $newModel::find()->select('id')->where(['document_type' => $newModel::DOC_TYPE_RETURN])->asArray()->count();
        $lastId = $lastId + 1;
        $newModel->doc_number = "BQ-" . $lastId . "/" . date('m-Y');

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $data['BichuvDoc']['reg_date'] = date('Y-m-d H:i:s', strtotime($data['BichuvDoc']['reg_date'] . " " . date('H:i:s')));
            $data['BichuvDoc']['from_department'] = $model->to_department;
            $data['BichuvDoc']['musteri_id'] = $model->musteri_id;
            $data['BichuvDoc']['status'] = $model::STATUS_SAVED;
            $dataTDI = $data['BichuvDocItems'];
            if (true/*$newModel->load($data['BichuvDoc']) && $newModel->save()*/) {
                $data['BichuvDocExpense']['document_id'] = $newModel->id;
                /*if ( !empty($data['BichuvDocExpense']['price']) && $data['BichuvDocExpense']['price'] > 0 ) {
                    if ( $modelTDE->load($data) && $modelTDE->save() ) {}
                }*/
                foreach ($dataTDI as $item) {
                    if ($item['qty'] == 0) {
                        continue;
                    }
                    $modelDI = new BichuvDocItems();
                    $savedDataTDI = [];
                    $savedDataTDI['BichuvDocItems'] = $item;
                    $savedDataTDI['BichuvDocItems'][] = $models->id;
                    $savedDataTDI['BichuvDocItems']['bichuv_doc_id'] = $newModel->id;
                    $savedDataTDI['BichuvDocItems']['price_sum'] = $item['price_usd'] ? $item['price_usd'] : 0;
                    $savedDataTDI['BichuvDocItems']['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                    /*if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                        unset($modelDI);
                    }*/
                }

                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                return $this->redirect(["view", 'id' => $model->id, 'slug' => $this->slug]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models,
            'modelTDE' => $modelTDE,
        ]);
    }


    public function actionReturnToWarehouse($id, $type = 'mato')
    {

        $model = $this->findModel($id);
        if ($model->status != BichuvDoc::STATUS_SAVED) {
            switch ($type) {
                case 'mato':
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $y = date('Y');
                        $cloneModel = new BichuvDoc();
                        $isClone = false;
                        $saved = false;
                        $cloneModel->setAttributes([
                            'document_type' => BichuvDoc::DOC_TYPE_ACCEPTED,
                            'doc_number' => "BK{$model->id}/{$y}",
                            'party_count' => 1,
                            'reg_date' => date('Y-m-d H:i:s'),
                            'status' => 1,
                            'musteri_id' => $model->musteri_id,
                            'from_department' => $model->to_department,
                            'to_department' => $model->from_department,
                            'to_employee' => $model->to_employee,
                            'from_employee' => $model->from_employee,
                            'add_info' => $model->add_info
                        ]);
                        if ($cloneModel->save()) {
                            $isClone = true;
                        }
                        if ($isClone) {
                            $cloneId = $cloneModel->id;
                            $items = $model->bichuvDocItems;
                            if (!empty($items)) {
                                foreach ($items as $item) {
                                    $modelItems = new BichuvDocItems();
                                    $item->bichuv_doc_id = $cloneId;
                                    $modelItems->attributes = $item->attributes;
                                    if ($modelItems->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                        }
                        if ($saved) {
                            $model->is_returned = 1;
                            $model->status = 3;
                            if ($model->save()) {
                                $transaction->commit();
                            }
                        } else {
                            $transaction->rollBack();
                        }
                        return $this->redirect(["view", 'id' => $model->id, 'slug' => $this->slug]);
                    } catch (Exception $e) {
                        Yii::info('Not Saved Returned ' . $e, 'save');
                    }
                    break;
                case 'acs':
                    $model->status = BichuvDoc::STATUS_SAVED; // RETURNED

                    $transaction = Yii::$app->db->beginTransaction();
                    $isAllSaved = true;
                    try {

                        if ($isAllSaved) {
                            $transaction->commit();
                        } else {
                            $transaction->rollback();
                        }
                    } catch (\Throwable $exception) {
                        $transaction->rollback();
                        $isAllSaved = false;
                        Yii::error($exception->getMessage(), 'exception');
                    }

                    if ($isAllSaved) {
                        Yii::$app->session->setFlash('success', 'Mahsulotlar qaytarildi');
                    } else {
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                    }
                    return $this->redirect(['view', 'id' => $model->id, 'slug' => $this->slug]);
            }

        }
        return $this->redirect(['index']);
    }

    /**
     * Creates a new BichuvDoc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BichuvDoc();
        $modelItems = null;
        $modelTDE = null;
        $t = Yii::$app->request->get('t', 1);
        $slug = $this->slug;

        if ($slug == BichuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL
            || $slug == BichuvDoc::DOC_TYPE_MOVING_SERVICE_LABEL
            || ($slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL && $t == 1)
            || ($slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_TAY_LABEL)
            || ($slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL && $t == 5)
            || $slug == BichuvDoc::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV_LABEL
        ) {
            $models = [new BichuvSliceItems()];
            $model->reg_date = date('d.m.Y');
            $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
            $lastId = $lastId ? $lastId['id'] + 1 : 1;
            $model->doc_number = "BN" . $lastId . "/" . date('Y');
        }
        elseif ($slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL && ($t == 2 || $t == 3)) {
            $models = [new BichuvDocItems()];
            $model->reg_date = date('d.m.Y');
            $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
            $lastId = $lastId ? $lastId['id'] + 1 : 1;
            $model->doc_number = "BK" . $lastId . "/" . date('Y');
        }
        elseif ($slug == BichuvDoc::DOC_TYPE_NASTEL_PLAN_LABEL) {
            $models = [new BichuvNastelDetails()];
            $model->reg_date = date('d.m.Y');
            $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
            $lastId = $lastId ? $lastId['id'] + 1 : 1;
            $model->doc_number = "BNP" . $lastId . "/" . date('Y');
        }
        else {
            $models = [new BichuvDocItems()];
            $modelTDE = new BichuvDocExpense();
            $model->reg_date = date('d.m.Y');
            $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
            $lastId = $lastId ? $lastId['id'] + 1 : 1;
            if ($slug == BichuvDoc::DOC_TYPE_REPAIR_MATO_LABEL || $slug == BichuvDoc::DOC_TYPE_SELLING_MATO_LABEL) {
                $model->doc_number = "BT" . $lastId . "/" . date('Y');
            } else {
                $model->doc_number = "B" . $lastId . "/" . date('Y');
            }
            if ($slug == BichuvDoc::DOC_TYPE_INCOMING_MATO_LABEL && $t == 2) {
                $models = [new BichuvSubDocItems()];
            }
        }
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $data['BichuvDoc']['reg_date'] = date('Y-m-d H:i:s', strtotime($data['BichuvDoc']['reg_date'] . " " . date('H:i:s')));
            $DIModelName = BichuvDocItems::getModelName();
            $dataTDI = Yii::$app->request->post($DIModelName, []);
            if (isset($data[$DIModelName])) {
                unset($data[$DIModelName]);
            }
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $saved = false;
                
                // tayyorlovga ko'chirganda model_orders_items_id ni nastel_no ga qarab aniqlash
                if ($slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_TAY_LABEL) {
                    $bichuvSliceItemsTMP = Yii::$app->request->post('BichuvSliceItems');
                    $nastelParty = '';
                    foreach ($bichuvSliceItemsTMP as $sliceItem) {
                        $nastelParty = $sliceItem['nastel_party'];
                    }
                    $model->model_orders_items_id = TikuvDoc::getModelOrdersItemsIdByNastelNo($nastelParty);
                }

                if ($model->load($data) && $model->save()) {
                    $modelId = $model->id;
                    $musteriId = $model->musteri_id;
                    if ($slug == BichuvDoc::DOC_TYPE_INCOMING_MATO_LABEL && $t == 2) {
                        if (!empty($data['BichuvSubDocItems'])) {
                            $lastParty = BichuvSubDocItems::getLastParty($musteriId);
                            foreach ($data['BichuvSubDocItems'] as $key => $item) {
                                $lastMusteriPartyNo = BichuvDocItems::getMusteriPartyNo($musteriId, $item['mijoz_part'], $modelId);
                                if($lastMusteriPartyNo){
                                    Yii::$app->session->setFlash("error", Yii::t('app','{mijoz_party} bunday mijoz partiya raqami avval kiritilgan!',['mijoz_party' => $item['mijoz_part']]));
                                    $saved = false;
                                    break;
                                }
                                $entityId = BichuvSubDocItems::getEntityId($item);
                                $modelDI = new BichuvDocItems();
                                $savedDataTDI = [];
                                $savedDataTDI[$DIModelName]['entity_type'] = 2;
                                $savedDataTDI[$DIModelName]['is_fixed'] = 1;
                                $savedDataTDI[$DIModelName]['entity_id'] = $entityId;
                                $savedDataTDI[$DIModelName]['musteri_id'] = $item['musteri_id'] ?? $model->musteri_id;
                                $savedDataTDI[$DIModelName]['roll_count'] = $item['roll_count'];
                                $savedDataTDI[$DIModelName]['quantity'] = $item['roll_weight'];
                                $savedDataTDI[$DIModelName]['document_quantity'] = $item['roll_weight'];
                                $savedDataTDI[$DIModelName]['bichuv_doc_id'] = $modelId;
                                $savedDataTDI[$DIModelName]['model_id'] = $item['model'];
                                $savedDataTDI[$DIModelName]['musteri_party_no'] = $item['mijoz_part'];
                                $savedDataTDI[$DIModelName]['party_no'] = (string)$lastParty;
                                $savedDataTDI[$DIModelName]['price_sum'] = 0.01;
                                $savedDataTDI[$DIModelName]['price_usd'] = 0.01;
                                if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                    $itemId = $modelDI->id;
                                    $modelSubItems = new BichuvSubDocItems();
                                    $modelSubItems->setAttributes([
                                        'doc_item_id' => $itemId,
                                        'musteri_id' => $item['musteri_id'] ?? $musteriId,
                                        'roll_weight' => $item['roll_weight'],
                                        'first_weight' => $item['roll_weight'],
                                        'roll_count' => $item['roll_count'],
                                        'musteri_party_no' => $item['mijoz_part'],
                                        'party_no' => (string)$lastParty,
                                        'en' => $item['en'],
                                        'gramaj' => $item['gramaj'],
                                        'rm_id' => $item['rm_id'],
                                        'ne_id' => $item['ne_id'],
                                        'thread_id' => $item['thread_id'],
                                        'pus_fine_id' => $item['pus_fine_id'],
                                        'c_id' => $item['c_id'],
                                        'paket_id' => 1,
                                        'mato' => $item['mato'],
                                        'model' => (string)$item['model'],
                                        'thread_consist' => $item['thread_consist']
                                    ]);
                                    if ($modelSubItems->save()) {
                                        unset($modelSubItems);
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                        } else {
                            $saved = true;
                        }
                    }
                    elseif (($slug == BichuvDoc::DOC_TYPE_MOVING_MATO_LABEL && $t == 1)
                        || $slug == BichuvDoc::DOC_TYPE_REPAIR_MATO_LABEL
                        || $slug == BichuvDoc::DOC_TYPE_SELLING_MATO_LABEL ) {
                        if (!empty($dataTDI)) {
                            foreach ($dataTDI as $item) {
                                $modelDI = new BichuvDocItems();
                                $savedDataTDI = [];
                                $savedDataTDI[$DIModelName] = $item;
                                $savedDataTDI[$DIModelName]['roll_count'] = $item['roll_count'];
                                $savedDataTDI[$DIModelName]['bichuv_doc_id'] = $modelId;
                                $savedDataTDI[$DIModelName]['musteri_id'] = $item['musteri_id'] ?? $musteriId;
                                $savedDataTDI[$DIModelName]['musterservice_i_id'] = $item['musteri_id'] ?? $musteriId;
                                $savedDataTDI[$DIModelName]['price_sum'] = 0.01;
                                $savedDataTDI[$DIModelName]['price_usd'] = 0.01;
                                if ($slug == BichuvDoc::DOC_TYPE_SELLING_MATO_LABEL) {
                                    $savedDataTDI[$DIModelName]['price_sum'] = $item['price_sum'] ? $item['price_sum'] : 0;
                                    $savedDataTDI[$DIModelName]['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                                }
                                $savedDataTDI[$DIModelName]['document_quantity'] = 0;
                                if (isset($item['musteri_party_no']) && !empty($item['musteri_party_no'])) {
                                    $savedDataTDI[$DIModelName]['musteri_party_no'] = $item['musteri_party_no'];
                                    $savedDataTDI[$DIModelName]['party_no'] = $item['party_no'];
                                }
                                if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                    $modelSubDocItems = new BichuvSubDocItems();
                                    $saved = true;
                                    $subItem = $modelDI->getMatoInfoByEntityId($item['entity_id']);
                                    if ($subItem) {
                                        $modelSubDocItems->setAttributes([
                                            'doc_item_id' => $modelDI->id,
                                            'musteri_id' => $item['musteri_id'] ?? $musteriId,
                                            'musteri_party_no' => $item['musteri_party_no'],
                                            'party_no' => $item['party_no'],
                                            'roll_weight' => 0,
                                            'en' => $subItem['en'],
                                            'gramaj' => $subItem['gramaj'],
                                            'ne_id' => $subItem['ne_id'],
                                            'thread_id' => $subItem['thread_id'],
                                            'pus_fine_id' => $subItem['pus_fine_id'],
                                            'c_id' => $subItem['c_id'],
                                            'rm_id' => $subItem['rm_id'],
                                            'model' => (string)$subItem['model'],
                                            'thread_consist' => $subItem['thread_consist']
                                        ]);
                                        if ($modelSubDocItems->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            break;
                                        }
                                    }
                                } else {
                                    $saved = false;
                                    break;
                                }
                            }
                        } else {
                            $saved = true;
                        }
                    }
                    elseif ($slug == BichuvDoc::DOC_TYPE_ACCEPTED_LABEL
                        || ($slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL && ($t == 2 || $t == 3))) {
                        foreach ($dataTDI as $item) {
                            $modelDI = new BichuvDocItems();
                            $savedDataTDI = [];
                            $savedDataTDI[$DIModelName] = $item;
                            $savedDataTDI[$DIModelName]['bichuv_doc_id'] = $modelId;
                            $savedDataTDI[$DIModelName]['price_sum'] = $item['price_sum'] ? $item['price_sum'] : 0;
                            $savedDataTDI[$DIModelName]['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                            if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                unset($modelDI);
                                $saved = true;
                            } else {
                                $saved = false;
                                break;
                            }
                        }
                    }
                    elseif ($slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL
                        || $slug == BichuvDoc::DOC_TYPE_MOVING_SERVICE_LABEL
                        || $slug == BichuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL
                        || $slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_TAY_LABEL
                        || ($slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL && $t == 5)) {
                        /*** Added condition for move print or pattern*/

                        $nastelNo = [];

                        if (!empty($data['BichuvSliceItems'])) {
                            $workWeight = $model->work_weight;
                            foreach ($data['BichuvSliceItems'] as $item) {
                               
                                if ($item['quantity'] > 0) {
                                    $modelSliceItems = new BichuvSliceItems();
                                    if (!empty($item['work_weight']) && $item['work_weight'] > 0) {
                                        $workWeight = $item['work_weight'];
                                    }
                                    $nastelNo[$item['nastel_party']] = $item['nastel_party'];
                                    $modelSliceItems->setAttributes([
                                        'bichuv_doc_id' => $modelId,
                                        'size_id' => $item['size_id'],
                                        'models_list_id' => $item['models_list_id'],
                                        'nastel_party' => $item['nastel_party'],
                                        'quantity' => $item['quantity'],
                                        'bichuv_given_roll_id' => $item['bichuv_given_roll_id'],
                                        'bgri_id' => $item['bgri_id'],
                                        'work_weight' => $workWeight
                                    ]);
                                    if ($modelSliceItems->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                        } else {
                            $saved = true;
                        }
                        $getBekaData = false;
                        if (!empty($nastelNo)) {
                            $ids = join(',', $nastelNo);
                            $getBekaData = $model->getBekaDataViaNastelNo($ids);
                        }
                        if ($getBekaData) {
                            if (!empty($data['BichuvBeka'])) {
                                foreach ($data['BichuvBeka'] as $item) {
                                    $modelBekaItems = new BichuvBeka();
                                    $key = array_search($item['nastel_no'], $getBekaData);
                                    if (!empty($key)) {
                                        unset($getBekaData[$key]);
                                        $modelBekaItems->setAttributes([
                                            'bichuv_doc_id' => $modelId,
                                            'weight' => $item['weight'],
                                            'bichuv_given_roll_id' => $item['bichuv_given_roll_id'],
                                            'entity_id' => $item['entity_id'],
                                            'party_no' => $item['party_no'],
                                            'musteri_party_no' => $item['musteri_party_no'],
                                            'roll_count' => $item['roll_count'],
                                            'nastel_no' => $item['nastel_no'],
                                            'model_id' => $item['model_id']
                                        ]);
                                    }
                                    if ($modelBekaItems->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                                if (!empty($getBekaData)) {
                                    foreach ($getBekaData as $item) {
                                        $modelBekaItems = new BichuvBeka();
                                        $modelBekaItems->setAttributes([
                                            'bichuv_doc_id' => $modelId,
                                            'weight' => 0,
                                            'bichuv_given_roll_id' => $item['id'],
                                            'entity_id' => $item['entity_id'],
                                            'party_no' => $item['party_no'],
                                            'musteri_party_no' => $item['musteri_party_no'],
                                            'roll_count' => 0,
                                            'nastel_no' => $item['nastel_no'],
                                            'model_id' => $item['model_id']
                                        ]);
                                        if ($modelBekaItems->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $saved = true;
                                foreach ($getBekaData as $item) {
                                    $modelBekaItems = new BichuvBeka();
                                    $modelBekaItems->setAttributes([
                                        'bichuv_doc_id' => $modelId,
                                        'weight' => 0,
                                        'bichuv_given_roll_id' => $item['id'],
                                        'entity_id' => $item['entity_id'],
                                        'party_no' => $item['party_no'],
                                        'musteri_party_no' => $item['musteri_party_no'],
                                        'roll_count' => 0,
                                        'nastel_no' => $item['nastel_no'],
                                        'model_id' => $item['model_id']
                                    ]);
                                    if ($modelBekaItems->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    elseif (($slug == BichuvDoc::DOC_TYPE_INCOMING_MATO_LABEL && ($t == 1 || $t == 3))
                        || ($slug == BichuvDoc::DOC_TYPE_ADJUSTMENT_LABEL)) {
                        $itemIds = [];
                        $saved = false;
                        $modelId = $model->id;
                        foreach ($dataTDI as $item) {
                            $modelDI = new BichuvDocItems();
                            if($slug != BichuvDoc::DOC_TYPE_ADJUSTMENT_LABEL){
                                $entityId = BichuvSubDocItems::getEntityId($item);
                            }else{
                                $entityId = $item['entity_id'];
                            }
                            $savedDataTDI = [];
                            $savedDataTDI[$DIModelName] = $item;
                            $savedDataTDI[$DIModelName]['bichuv_doc_id'] = $modelId;
                            $savedDataTDI[$DIModelName]['musteri_id'] =  $item['musteri_id'] ?? $musteriId;
                            $savedDataTDI[$DIModelName]['entity_id'] = $entityId;
                            $savedDataTDI[$DIModelName]['is_fixed'] = 1;
                            if (isset($item['musteri_party_no']) && !empty($item['musteri_party_no'])) {
                                $savedDataTDI[$DIModelName]['musteri_party_no'] = $item['musteri_party_no'];
                                $savedDataTDI[$DIModelName]['party_no'] = $item['party_no'];
                            }
                            $savedDataTDI[$DIModelName]['price_sum'] = $item['price_sum'] ? $item['price_sum'] : 0;
                            $savedDataTDI[$DIModelName]['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                            if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                if ($item['roll_count'] && $item['roll_count'] > 0) {
                                    $itemIds[$modelDI->id] = $modelDI->bss_id;
                                }
                                $saved = true;
                                unset($modelDI);
                            } else {
                                $saved = false;
                                break;
                            }
                        }
                        if($slug != BichuvDoc::DOC_TYPE_ADJUSTMENT_LABEL){
                            $rulonInfoBoyoqhona = BichuvDoc::getRMInfo($itemIds);
                            if ($rulonInfoBoyoqhona) {
                                $count = 1;
                                $rmId = [];
                                foreach ($rulonInfoBoyoqhona as $itemRulon) {
                                    $itemId = array_search($itemRulon['id'], $itemIds);
                                    $modelSubItems = new BichuvSubDocItems();
                                    $modelSubItems->setAttributes([
                                        'doc_item_id' => $itemId,
                                        'musteri_id' => $itemRulon['mid'],
                                        'paket_id' => $itemRulon['pid'],
                                        'bss_id' => $itemRulon['id'],
                                        'roll_weight' => $itemRulon['rulon_kg'],
                                        'roll_order' => "{$count}-{$itemRulon['count_rulon']}",
                                        'musteri_party_no' => $itemRulon['mijoz_part'],
                                        'party_no' => $itemRulon['partiya_no'],
                                        'en' => $itemRulon['mato_en'],
                                        'gramaj' => $itemRulon['gramaj'],
                                        'ne' => $itemRulon['ne'],
                                        'ne_id' => $itemRulon['ne_id'],
                                        'rm_id' => $itemRulon['rmid'],
                                        'c_id' => $itemRulon['c_id'],
                                        'thread' => $itemRulon['ip'],
                                        'thread_id' => $itemRulon['thr_id'],
                                        'pus_fine' => $itemRulon['pus_fine'],
                                        'pus_fine_id' => $itemRulon['pf_id'],
                                        'ctone' => $itemRulon['ctone'],
                                        'color_id' => $itemRulon['color_id'],
                                        'pantone' => $itemRulon['pantone'],
                                        'mato' => $itemRulon['mato'],
                                        'model' => $itemRulon['model'],
                                        'paketlama' => $itemRulon['user_fio'],
                                        'thread_consist' => $itemRulon['thread_consist']
                                    ]);
                                    if (empty($rmId)) {
                                        array_push($rmId, $itemRulon['rmid']);
                                        $count++;
                                    } elseif (in_array($itemRulon['rmid'], $rmId)) {
                                        $count++;
                                    } else {
                                        $count = 1;
                                        array_push($rmId, $itemRulon['rmid']);
                                    }
                                    if ($modelSubItems->save()) {
                                        unset($modelSubItems);
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            } else {
                                $saved = true;
                            }
                        }else{
                            $saved = true;
                        }
                    }
                    elseif ($slug == BichuvDoc::DOC_TYPE_NASTEL_PLAN_LABEL) {
                        $saved = false;
                        if (true) {
                            if (!empty($data['BichuvNastelDetails'])) {
                                foreach ($data['BichuvNastelDetails'] as $item) {
                                    $item['bichuv_doc_id'] = $modelId;
                                    $modelBND = new BichuvNastelDetails();
                                    $modelBND->setAttributes($item);
                                    if ($modelBND->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    elseif($slug == BichuvDoc::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV_LABEL){
                        if (!empty($data['BichuvSliceItems'])) {
                            $workWeight = $model->work_weight;
                            foreach ($data['BichuvSliceItems'] as $item) {

                                if ($item['quantity'] > 0) {
                                    $modelSliceItems = new BichuvSliceItems();
                                    if (!empty($item['work_weight']) && $item['work_weight'] > 0) {
                                        $workWeight = $item['work_weight'];
                                    }
                                    $nastelNo[$item['nastel_party']] = $item['nastel_party'];
                                    $modelSliceItems->setAttributes([
                                        'bichuv_doc_id' => $modelId,
                                        'size_id' => $item['size_id'],
                                        'model_id' => $item['model_id'],
                                        'nastel_party' => $item['nastel_party'],
                                        'quantity' => $item['quantity'],
                                        'bichuv_given_roll_id' => $item['bichuv_given_roll_id'],
                                        'work_weight' => $workWeight,
                                        'model_var_print_id' => $item['model_var_print_id'],
                                        'model_var_stone_id' => $item['model_var_stone_id'],
                                        'invalid_quantity' => $item['invalid_quantity'],
                                        'add_info' => $item['add_info'],
                                    ]);
                                    if ($modelSliceItems->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                        } else {
                            $saved = true;
                        }
                    }
                    else {
                        $data['BichuvDocExpense']['document_id'] = $modelId;
                        if (!empty($data['BichuvDocExpense']['price']) && $data['BichuvDocExpense']['price'] > 0) {
                            if ($modelTDE->load($data) && $modelTDE->save()) {
                                $saved = true;
                            }
                        }
                        foreach ($dataTDI as $item) {
                            $modelDI = new BichuvDocItems();
                            $savedDataTDI = [];
                            $savedDataTDI[$DIModelName] = $item;
                            $savedDataTDI[$DIModelName]['bichuv_doc_id'] = $modelId;
                            $savedDataTDI[$DIModelName]['price_sum'] = $item['price_sum'] ? $item['price_sum'] : 0;
                            $savedDataTDI[$DIModelName]['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                            if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                $saved = true;
                            } else {
                                $saved = false;
                                break;
                            }
                        }
                    }
                    if ($saved) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return $this->redirect(["view", 'id' => $model->id, 'slug' => $this->slug, 't' => $t]);
                    } else {
                        $transaction->rollBack();
                    }
                }
            } catch (Exception $e) {
                Yii::info('All not saved ' . $e, 'save');
            }

        }
        return $this->render('create', [
            'model' => $model,
            'models' => $models,
            'modelTDE' => $modelTDE,
        ]);
    }

    /**
     * @return $id
     * */
    public function actionGetModelOrdersAcs()
    {
        $app = Yii::$app;
        $app->response->format = Response::FORMAT_JSON;
        $response['status'] = false;
        $id = $app->request->get('id');
        $result = BichuvDoc::getModelOrdersAcs($id);
        if($result){
            $response['status'] = true;
            $response['data'] = $result;
        }
        else{
            $response['status'] = false;
        }
        return $response;
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $t = Yii::$app->request->get('t', 1);
        if($model->status>$model::STATUS_ACTIVE){
            return $this->redirect(["view", 'id' => $id, 'slug' => $this->slug, 't' => $t]);
        }
        if (!empty($model->bichuvDocItems)) {
            $models = $model->bichuvDocItems;
        } else {
            $models = [new BichuvDocItems()];
        }
        if (!empty($model->bichuvDocExpenses) && !empty($model->bichuvDocExpenses[0])) {
            $modelTDE = $model->bichuvDocExpenses[0];
        } else {
            $modelTDE = new BichuvDocExpense();
        }
        if ($this->slug == BichuvDoc::DOC_TYPE_INCOMING_MATO_LABEL && $t == 2) {
            if (!empty($model->bichuvDocItems)) {
                $modelItemsData = $model->bichuvDocItems;
                $models = [];
                foreach ($modelItemsData as $key => $item) {
                    if (!empty($item->bichuvSubDocItems)) {
                        foreach ($item->bichuvSubDocItems as $subItem) {
                            $models[$subItem->id] = $subItem;
                        }
                    }
                }
            } else {
                $models = [new BichuvSubDocItems()];
            }
        }
        if ($this->slug == BichuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL
            || $this->slug == BichuvDoc::DOC_TYPE_MOVING_SERVICE_LABEL
            || ($this->slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL && $t == 1)
            || ($slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_TAY_LABEL)
            || ($this->slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL && $t == 5)
            || $this->slug == BichuvDoc::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV_LABEL
            || $this->slug == BichuvDoc::DOC_TYPE_ACCEPTED_SlICE_FROM_BICHUV_LABEL
        ) {
            if (!empty($model->bichuvSliceItems)) {
                $models = $model->bichuvSliceItems;
                $modelDATA = $model->getModelListInfo();
                $model->cp['model_list'] = $modelDATA['model'];
                $model->cp['model_var'] = $modelDATA['model_var'];
            } else {
                $models = [new BichuvSliceItems()];
                $model->cp['model_list'] = null;
                $model->cp['model_var'] = null;
            }
        }
        if ($this->slug == BichuvDoc::DOC_TYPE_NASTEL_PLAN_LABEL) {
            if (!empty($model->bichuvNastelDetails)) {
                $models = $model->bichuvNastelDetails;
            } else {
                $models = [new BichuvNastelDetails()];
            }
        }
        if ($this->slug == BichuvDoc::DOC_TYPE_MOVING_ACS_WITH_NASTEL_LABEL) {
            $model->scenario = BichuvDoc::SCENARIO_UPDATE_MOVING_NASTEL;

            if (empty($model->bichuvDocItems)) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
            }
            $models = $model->bichuvDocItems;
            foreach ($models as $docItem) {
                $docItem->scenario = BichuvDocItems::SCENARIO_UPDATE_MOVING_NASTEL;
            }
        }
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $TDIModelName = BichuvDocItems::getModelName();
            $dataTDI = Yii::$app->request->post($TDIModelName, []);
            if (isset($data[$TDIModelName])) {
                unset($data[$TDIModelName]);
            }
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $saved = false;
                if ($model->load($data) && $model->save()) {
                    $musteriId = $model->musteri_id;
                    $modelId = $model->id;
                    $itemIds = [];
                    //delete old all data
                    if (!empty($model->bichuvDocItems)) {
                        foreach ($model->bichuvDocItems as $item) {
                            if (!empty($item->bichuvSubDocItems)) {
                                foreach ($item->bichuvSubDocItems as $itemSub) {
                                    $itemSub->delete();
                                }
                            }
                            if (!empty($item->bichuvRollRecords)) {
                                foreach ($item->bichuvRollRecords as $itemSub) {
                                    $itemSub->delete();
                                }
                            }
                            $item->delete();
                        }
                    }
                    //delete old all data
                    if (!empty($model->bichuvDocExpenses)) {
                        foreach ($model->bichuvDocExpenses as $item) {
                            $item->delete();
                        }
                    }

                    if (($this->slug == BichuvDoc::DOC_TYPE_INCOMING_MATO_LABEL && $t == 2) ) {
                        if (!empty($data['BichuvSubDocItems'])) {
                            $lastParty = BichuvSubDocItems::getLastParty($musteriId);
                            foreach ($data['BichuvSubDocItems'] as $key => $item) {
                                $lastMusteriPartyNo = BichuvDocItems::getMusteriPartyNo($musteriId, $item['mijoz_part'], $modelId);
                                if($lastMusteriPartyNo){
                                    Yii::$app->session->setFlash("error", Yii::t('app','{mijoz_party} bunday mijoz partiya raqami avval kiritilgan!',['mijoz_party' => $item['mijoz_part']]));
                                    $saved = false;
                                    break;
                                }
                                $modelDI = new BichuvDocItems();
                                $entityId = BichuvSubDocItems::getEntityId($item);
                                $savedDataTDI = [];
                                $savedDataTDI[$TDIModelName]['entity_type'] = 2;
                                $savedDataTDI[$TDIModelName]['is_fixed'] = 1;
                                $savedDataTDI[$TDIModelName]['entity_id'] = $entityId;
                                $savedDataTDI[$TDIModelName]['musteri_id'] = $item['musteri_id'] ?? $musteriId;
                                $savedDataTDI[$TDIModelName]['roll_count'] = $item['roll_count'];
                                $savedDataTDI[$TDIModelName]['quantity'] = $item['roll_weight'];
                                $savedDataTDI[$TDIModelName]['document_quantity'] = $item['roll_weight'];
                                $savedDataTDI[$TDIModelName]['bichuv_doc_id'] = $modelId;
                                $savedDataTDI[$TDIModelName]['model_id'] = $item['model'];
                                $savedDataTDI[$TDIModelName]['musteri_party_no'] = $item['mijoz_part'];
                                $savedDataTDI[$TDIModelName]['party_no'] = (string)$lastParty;
                                $savedDataTDI[$TDIModelName]['price_sum'] = 0.01;
                                $savedDataTDI[$TDIModelName]['price_usd'] = 0.01;
                                if ($this->slug == BichuvDoc::DOC_TYPE_SELLING_MATO_LABEL) {
                                    $savedDataTDI[$TDIModelName]['price_sum'] = $item['price_sum'] ? $item['price_sum'] : 0;
                                    $savedDataTDI[$TDIModelName]['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                                }
                                if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                    $saved = true;
                                    $itemId = $modelDI->id;
                                    $modelSubItems = new BichuvSubDocItems();
                                    $modelSubItems->setAttributes([
                                        'doc_item_id' => $itemId,
                                        'musteri_id' => $item['musteri_id'] ?? $musteriId,
                                        'roll_weight' => $item['roll_weight'],
                                        'first_weight' => $item['roll_weight'],
                                        'roll_count' => $item['roll_count'],
                                        'musteri_party_no' => $item['mijoz_part'],
                                        'party_no' => (string)$lastParty,
                                        'en' => $item['en'],
                                        'gramaj' => $item['gramaj'],
                                        'rm_id' => $item['rm_id'],
                                        'ne_id' => $item['ne_id'],
                                        'thread_id' => $item['thread_id'],
                                        'pus_fine_id' => $item['pus_fine_id'],
                                        'c_id' => $item['c_id'],
                                        'paket_id' => 1,
                                        'mato' => $item['mato'],
                                        'model' => (string)$item['model'],
                                        'thread_consist' => $item['thread_consist']
                                    ]);
                                    if ($modelSubItems->save()) {
                                        unset($modelSubItems);
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                } else {
                                    $saved = false;
                                    break;
                                }
                            }
                        } else {
                            $saved = true;
                        }
                    }
                    elseif (($this->slug == BichuvDoc::DOC_TYPE_MOVING_MATO_LABEL && $t == 1)
                        || $this->slug == BichuvDoc::DOC_TYPE_REPAIR_MATO_LABEL ||
                        $this->slug == BichuvDoc::DOC_TYPE_SELLING_MATO_LABEL) {
                        if (!empty($dataTDI)) {
                            foreach ($dataTDI as $item) {
                                $modelDI = new BichuvDocItems();
                                $savedDataTDI = [];
                                $savedDataTDI[$TDIModelName] = $item;
                                $savedDataTDI[$TDIModelName]['roll_count'] = $item['roll_count'];
                                $savedDataTDI[$TDIModelName]['bichuv_doc_id'] = $modelId;
                                $savedDataTDI[$TDIModelName]['musteri_id'] = $item['musteri_id'] ?? $musteriId;
                                $savedDataTDI[$TDIModelName]['price_sum'] = 0.01;
                                $savedDataTDI[$TDIModelName]['price_usd'] = 0.01;
                                if ($this->slug == BichuvDoc::DOC_TYPE_SELLING_MATO_LABEL) {
                                    $savedDataTDI[$TDIModelName]['price_sum'] = $item['price_sum'] ? $item['price_sum'] : 0;
                                    $savedDataTDI[$TDIModelName]['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                                }
                                $savedDataTDI[$TDIModelName]['document_quantity'] = 0;
                                if (isset($item['musteri_party_no']) && !empty($item['musteri_party_no'])) {
                                    $savedDataTDI[$TDIModelName]['musteri_party_no'] = $item['musteri_party_no'];
                                    $savedDataTDI[$TDIModelName]['party_no'] = $item['party_no'];
                                }
                                if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                    $saved = true;
                                    //qabul qiluvchi uchun sub doc item
                                    $modelSubDocItems = new BichuvSubDocItems();
                                    $subItem = $modelDI->getMatoInfoByEntityId($item['entity_id']);
                                    $modelSubDocItems->setAttributes([
                                        'doc_item_id' => $modelDI->id,
                                        'musteri_id' => $item['musteri_id'] ?? $musteriId,
                                        'musteri_party_no' => $item['musteri_party_no'],
                                        'party_no' => $item['party_no'],
                                        'roll_weight' => 0,
                                        'en' => $subItem['en'],
                                        'gramaj' => $subItem['gramaj'],
                                        'ne_id' => $subItem['ne_id'],
                                        'thread_id' => $subItem['thread_id'],
                                        'pus_fine_id' => $subItem['pus_fine_id'],
                                        'c_id' => $subItem['c_id'],
                                        'rm_id' => $subItem['rm_id'],
                                        'model' => (string)$subItem['model'],
                                        'thread_consist' => $subItem['thread_consist']
                                    ]);
                                    if ($modelSubDocItems->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                } else {
                                    $saved = false;
                                    break;
                                }
                            }
                        } else {
                            $saved = true;
                        }
                    }
                    elseif ($this->slug == BichuvDoc::DOC_TYPE_ACCEPTED_MATO_LABEL) {
                        foreach ($dataTDI as $item) {
                            $modelDI = new BichuvDocItems();
                            $savedDataTDI = [];
                            $savedDataTDI[$TDIModelName] = $item;
                            $savedDataTDI[$TDIModelName]['bichuv_doc_id'] = $modelId;
                            if (isset($item['musteri_party_no']) && !empty($item['musteri_party_no'])) {
                                $savedDataTDI[$TDIModelName]['musteri_party_no'] = $item['musteri_party_no'];
                                $savedDataTDI[$TDIModelName]['party_no'] = $item['party_no'];
                            }
                            $savedDataTDI[$TDIModelName]['price_sum'] = $item['price_sum'] ? $item['price_sum'] : 0;
                            $savedDataTDI[$TDIModelName]['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                            if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                unset($modelDI);
                                $saved = true;
                            } else {
                                $saved = false;
                                break;
                            }
                        }
                    }
                    elseif ($this->slug == BichuvDoc::DOC_TYPE_ACCEPTED_LABEL
                        || ($this->slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL && ($t == 2 || $t == 3))) {
                        foreach ($dataTDI as $item) {
                            $modelDI = new BichuvDocItems();
                            $savedDataTDI = [];
                            $savedDataTDI[$TDIModelName] = $item;
                            $savedDataTDI[$TDIModelName]['bichuv_doc_id'] = $modelId;
                            $savedDataTDI[$TDIModelName]['price_sum'] = $item['price_sum'] ? $item['price_sum'] : 0;
                            $savedDataTDI[$TDIModelName]['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                            if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                unset($modelDI);
                                $saved = true;
                            } else {
                                $saved = false;
                                break;
                            }
                        }
                    }
                    elseif ($this->slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL
                        || $this->slug == BichuvDoc::DOC_TYPE_MOVING_SERVICE_LABEL
                        || $this->slug == BichuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL
                        || ($this->slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL && $t == 5)) {
                        /**Added condition for udate slice movig to pattern po print */
                        if (!empty($model->bichuvSliceItems)) {
                            foreach ($model->bichuvSliceItems as $item) {
                                $item->delete();
                            }
                        }
                        if (!empty($model->bichuvNastelRag)) {
                            foreach ($model->bichuvNastelRag as $item) {
                                $item->delete();
                            }
                        }
                        if (!empty($model->bichuvBeka)) {
                            foreach ($model->bichuvBeka as $item) {
                                $item->delete();
                            }
                        }
                        $nastelNo = [];
                        if (!empty($data['BichuvSliceItems'])) {
                            $workWeight = $model->work_weight;
                            foreach ($data['BichuvSliceItems'] as $item) {
                                if ($item['quantity'] > 0) {
                                    $nastelNo[$item['nastel_party']] = "'{$item['nastel_party']}'";
                                    $modelSliceItems = new BichuvSliceItems();
                                    if (!empty($item['work_weight']) && $item['work_weight'] > 0) {
                                        $workWeight = $item['work_weight'];
                                    }
                                    $modelSliceItems->setAttributes([
                                        'bichuv_doc_id' => $modelId,
                                        'size_id' => $item['size_id'],
                                        'models_list_id' => $item['models_list_id'],
                                        'nastel_party' => $item['nastel_party'],
                                        'quantity' => $item['quantity'],
                                        'bichuv_given_roll_id' => $item['bichuv_given_roll_id'],
                                        'bgri_id' => $item['bgri_id'],
                                        'work_weight' => $workWeight
                                    ]);
                                    if ($modelSliceItems->save()) {
                                        $saved = true;
                                    }
                                }
                            }
                        }
                        $getBekaData = false;
                        if (!empty($nastelNo)) {
                            $ids = join(',', $nastelNo);
                            $getBekaData = $model->getBekaDataViaNastelNo($ids);
                        }
                        if ($getBekaData) {
                            if (!empty($data['BichuvBeka'])) {
                                foreach ($data['BichuvBeka'] as $item) {
                                    $modelBekaItems = new BichuvBeka();
                                    $key = array_search($item['nastel_no'], $getBekaData);
                                    if (!empty($key)) {
                                        unset($getBekaData[$key]);
                                        $modelBekaItems->setAttributes([
                                            'bichuv_doc_id' => $modelId,
                                            'weight' => $item['weight'],
                                            'bichuv_given_roll_id' => $item['bichuv_given_roll_id'],
                                            'entity_id' => $item['entity_id'],
                                            'party_no' => $item['party_no'],
                                            'musteri_party_no' => $item['musteri_party_no'],
                                            'roll_count' => $item['roll_count'],
                                            'nastel_no' => $item['nastel_no'],
                                            'model_id' => $item['model_id']
                                        ]);
                                        if ($modelBekaItems->save()) {
                                            $saved = true;
                                        }
                                    }

                                }
                                if (!empty($getBekaData)) {
                                    foreach ($getBekaData as $item) {
                                        $modelBekaItems = new BichuvBeka();
                                        $modelBekaItems->setAttributes([
                                            'bichuv_doc_id' => $modelId,
                                            'weight' => 0,
                                            'bichuv_given_roll_id' => $item['id'],
                                            'entity_id' => $item['entity_id'],
                                            'party_no' => $item['party_no'],
                                            'musteri_party_no' => $item['musteri_party_no'],
                                            'roll_count' => 0,
                                            'nastel_no' => $item['nastel_no'],
                                            'model_id' => $item['model_id']
                                        ]);
                                        if ($modelBekaItems->save()) {
                                            $saved = true;
                                        }
                                    }
                                }
                            } else {
                                foreach ($getBekaData as $item) {
                                    $modelBekaItems = new BichuvBeka();
                                    $modelBekaItems->setAttributes([
                                        'bichuv_doc_id' => $modelId,
                                        'weight' => 0,
                                        'bichuv_given_roll_id' => $item['id'],
                                        'entity_id' => $item['entity_id'],
                                        'party_no' => $item['party_no'],
                                        'musteri_party_no' => $item['musteri_party_no'],
                                        'roll_count' => 0,
                                        'nastel_no' => $item['nastel_no'],
                                        'model_id' => $item['model_id']
                                    ]);
                                    if ($modelBekaItems->save()) {
                                        $saved = true;
                                    }
                                }
                                $saved = true;
                            }
                        }
                    }
                    elseif (($this->slug == BichuvDoc::DOC_TYPE_INCOMING_MATO_LABEL && ($t == 1 || $t == 3))
                        || ($this->slug == BichuvDoc::DOC_TYPE_ADJUSTMENT_LABEL)) {
                        $itemIds = [];
                        $modelId = $model->id;
                        foreach ($dataTDI as $item) {
                            $modelDI = new BichuvDocItems();
                            if($this->slug != BichuvDoc::DOC_TYPE_ADJUSTMENT_LABEL){
                                $entityId = BichuvSubDocItems::getEntityId($item);
                            }else{
                                $entityId = $item['entity_id'];
                            }
                            $savedDataTDI = [];
                            $savedDataTDI[$TDIModelName] = $item;
                            $savedDataTDI[$TDIModelName]['bichuv_doc_id'] = $modelId;
                            $savedDataTDI[$TDIModelName]['musteri_id'] = $item['musteri_id'] ?? $musteriId;
                            $savedDataTDI[$TDIModelName]['entity_id'] = $entityId;
                            $savedDataTDI[$TDIModelName]['is_fixed'] = 1;
                            if (isset($item['musteri_party_no']) && !empty($item['musteri_party_no'])) {
                                $savedDataTDI[$TDIModelName]['musteri_party_no'] = $item['musteri_party_no'];
                                $savedDataTDI[$TDIModelName]['party_no'] = $item['party_no'];
                            }
                            $savedDataTDI[$TDIModelName]['price_sum'] = $item['price_sum'] ? $item['price_sum'] : 0;
                            $savedDataTDI[$TDIModelName]['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                            if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                if ($item['roll_count'] && $item['roll_count'] > 0) {
                                    $itemIds[$modelDI->id] = $modelDI->bss_id;
                                }
                                unset($modelDI);
                            }
                        }
                        if($this->slug != BichuvDoc::DOC_TYPE_ADJUSTMENT_LABEL){
                            $rulonInfoBoyoqhona = BichuvDoc::getRMInfo($itemIds);
                            if ($rulonInfoBoyoqhona) {
                                $count = 1;
                                $rmId = [];
                                foreach ($rulonInfoBoyoqhona as $itemRulon) {
                                    $itemId = array_search($itemRulon['id'], $itemIds);
                                    $modelSubItems = new BichuvSubDocItems();
                                    $modelSubItems->setAttributes([
                                        'doc_item_id' => $itemId,
                                        'musteri_id' => $itemRulon['mid'],
                                        'paket_id' => $itemRulon['pid'],
                                        'bss_id' => $itemRulon['id'],
                                        'roll_weight' => $itemRulon['rulon_kg'],
                                        'roll_order' => "{$count}-{$itemRulon['count_rulon']}",
                                        'musteri_party_no' => $itemRulon['mijoz_part'],
                                        'party_no' => $itemRulon['partiya_no'],
                                        'en' => $itemRulon['mato_en'],
                                        'gramaj' => $itemRulon['gramaj'],
                                        'ne' => $itemRulon['ne'],
                                        'ne_id' => $itemRulon['ne_id'],
                                        'rm_id' => $itemRulon['rmid'],
                                        'c_id' => $itemRulon['c_id'],
                                        'thread' => $itemRulon['ip'],
                                        'thread_id' => $itemRulon['thr_id'],
                                        'pus_fine' => $itemRulon['pus_fine'],
                                        'pus_fine_id' => $itemRulon['pf_id'],
                                        'ctone' => $itemRulon['ctone'],
                                        'color_id' => $itemRulon['color_id'],
                                        'pantone' => $itemRulon['pantone'],
                                        'mato' => $itemRulon['mato'],
                                        'model' => $itemRulon['model'],
                                        'paketlama' => $itemRulon['user_fio'],
                                        'thread_consist' => $itemRulon['thread_consist']
                                    ]);
                                    if (empty($rmId)) {
                                        array_push($rmId, $itemRulon['rmid']);
                                        $count++;
                                    } elseif (in_array($itemRulon['rmid'], $rmId)) {
                                        $count++;
                                    } else {
                                        $count = 1;
                                        array_push($rmId, $itemRulon['rmid']);
                                    }
                                    if ($modelSubItems->save()) {
                                        unset($modelSubItems);
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                    }
                                }
                            } else {
                                $saved = true;
                            }
                        }else{
                            $saved = true;
                        }
                    }
                    elseif ($this->slug == BichuvDoc::DOC_TYPE_NASTEL_PLAN_LABEL) {
                        $saved = false;
                        if (!empty($model->bichuvNastelDetails)) {
                            foreach ($model->bichuvNastelDetails as $bichuvNastelDetail) {
                                $bichuvNastelDetail->delete();
                            }
                        }
                        if (!empty($data['BichuvNastelDetails'])) {
                            foreach ($data['BichuvNastelDetails'] as $item) {
                                $item['bichuv_doc_id'] = $modelId;
                                $modelBND = new BichuvNastelDetails();
                                $modelBND->setAttributes($item);
                                if ($modelBND->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                    }
                    elseif ($this->slug == BichuvDoc::DOC_TYPE_MOVING_ACS_WITH_NASTEL_LABEL) {
                        // load all items
                        if (Model::loadMultiple($models, Yii::$app->request->post())) {
                            foreach ($models as $bichuvDocItem) {
                                if (!$bichuvDocItem->save()) {
                                    $saved = false;
                                    break;
                                }
                                $saved = true;
                            }
                        }
                        else {
                            $saved = false;
                        }
                    }
                    elseif ($this->slug == BichuvDoc::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV_LABEL
                    || $this->slug == BichuvDoc::DOC_TYPE_ACCEPTED_SlICE_FROM_BICHUV_LABEL){
                        if (!empty($model->bichuvSliceItems)) {
                            foreach ($model->bichuvSliceItems as $item) {
                                $item->delete();
                            }
                            if (!empty($data['BichuvSliceItems'])) {
                                $workWeight = $model->work_weight;
                                foreach ($data['BichuvSliceItems'] as $item) {
                                    if ($item['quantity'] > 0) {
                                        $modelSliceItems = new BichuvSliceItems();
                                        if (!empty($item['work_weight']) && $item['work_weight'] > 0) {
                                            $workWeight = $item['work_weight'];
                                        }
                                        $nastelNo[$item['nastel_party']] = $item['nastel_party'];
                                        $modelSliceItems->setAttributes([
                                            'bichuv_doc_id' => $modelId,
                                            'size_id' => $item['size_id'],
                                            'model_id' => $item['model_id'],
                                            'nastel_party' => $item['nastel_party'],
                                            'quantity' => $item['quantity'],
                                            'bichuv_given_roll_id' => $item['bichuv_given_roll_id'],
                                            'work_weight' => $workWeight,
                                            'model_var_print_id' => $item['model_var_print_id'],
                                            'model_var_stone_id' => $item['model_var_stone_id'],
                                            'fact_quantity' => $item['fact_quantity'],
                                            'invalid_quantity' => $item['invalid_quantity'],
                                            'add_info' => $item['add_info'],
                                        ]);
                                        if ($modelSliceItems->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $saved = true;
                            }
                        }

                    }
                    else {
                        $data['BichuvDocExpense']['document_id'] = $modelId;
                        if (!empty($data['BichuvDocExpense']['price']) && $data['BichuvDocExpense']['price'] > 0) {
                            if ($modelTDE->load($data) && $modelTDE->save()) {
                                $saved = true;
                            }
                        }
                        foreach ($dataTDI as $item) {
                            $modelDI = new BichuvDocItems();
                            $savedDataTDI = [];
                            $savedDataTDI[$TDIModelName] = $item;
                            $savedDataTDI[$TDIModelName]['bichuv_doc_id'] = $modelId;
                            $savedDataTDI[$TDIModelName]['price_sum'] = $item['price_sum'] ? $item['price_sum'] : 0;
                            $savedDataTDI[$TDIModelName]['price_usd'] = $item['price_usd'] ? $item['price_usd'] : 0;
                            if ($modelDI->load($savedDataTDI) && $modelDI->save()) {
                                unset($modelDI);
                                $saved = true;
                            }
                        }
                    }
                    if ($saved) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return $this->redirect(["view", 'id' => $modelId, 'slug' => $this->slug, 't' => $t]);
                    } else {
                        $transaction->rollBack();
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::info('All not saved', 'save');
            }
        }
        return $this->render('update', [
            'model' => $model,
            'models' => $models,
            'modelTDE' => $modelTDE,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id)
    {
        $model = $this->findModel($id);
        if ($model->status < BichuvItemBalance::STATUS_SAVED) {
            $musteriId = $model->musteri_id;
            $slug = Yii::$app->request->get('slug');
            switch ($model->document_type) {
                case 1:
                    if ($slug == BichuvDoc::DOC_TYPE_INCOMING_MATO_LABEL) {
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            $items = $model->getBichuvDocItems()->asArray()->all();
                            $saved = false;
                            if (!empty($items)) {
                                $modelId = $model->id;
                                $deptTo = $model->to_department;
                                foreach ($items as $item) {
                                    $modelBIB = new BichuvRmItemBalance();
                                    $musteri_id = $item['musteri_id'] ?? $musteriId;
                                    $item['department_id'] = $deptTo;
                                    $checkExists = $modelBIB::getLastRecordMato($item, $musteri_id);
                                    $inventory = $item['quantity'];
                                    $roll_inventory = $item['roll_count'];
                                    if ($checkExists) {
                                        $inventory += $checkExists['inventory'];
                                        $roll_inventory += $checkExists['roll_inventory'];
                                    }
                                    $modelBIB->setAttributes([
                                        'entity_id' => $item['entity_id'],
                                        'doc_type' => 1,
                                        'inventory' => $inventory,
                                        'count' => $item['quantity'],
                                        'roll_inventory' => $roll_inventory,
                                        'roll_count' => $item['roll_count'],
                                        'party_no' => $item['party_no'],
                                        'model_id' => $item['model_id'],
                                        'doc_id' => $modelId,
                                        'musteri_party_no' => $item['musteri_party_no'],
                                        'to_department' => $deptTo,
                                        'department_id' => $deptTo,
                                        'from_musteri' => $musteri_id,
                                    ]);
                                    if ($modelBIB->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            } else {
                                $saved = true;
                            }
                            $model->updateCounters(['status' => 2]);
                            if ($saved) {
                                $transaction->commit();
                            } else {
                                $transaction->rollBack();
                            }
                        } catch (Exception $e) {
                            Yii::info('Not changed status to 3', 'save');
                        }
                    }
                    else {
                        $TDItems = $model->getBichuvDocItems()->asArray()->all();
                        $flagIB = false;
                        $total = [];
                        $total['sum'] = 0;
                        $total['usd'] = 0;

                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            /** default area for acs warehouse */
                            $defaultAcsAreaId = WmsDepartmentArea::getAreaIdByToken(WmsDepartmentArea::DEFAULT_ZONE_TOKEN_FOR_ACS_WAREHOUSE);
                            if (!$defaultAcsAreaId) {
                                Yii::$app->session->addFlash('error', Yii::t('app', 'Create an accessory storage sector first'));
                                $tokenArea = WmsDepartmentArea::DEFAULT_ZONE_TOKEN_FOR_ACS_WAREHOUSE;
                                throw new \yii\base\Exception("Aksessuar ombori uchun '{$tokenArea}' tokenli sektor yaratilmagan");
                            }

                            if (!empty($TDItems)) {
                                foreach ($TDItems as $item) {
                                    $flagIB = false;
                                    $ItemBalanceModel = new BichuvItemBalance();
                                    $item['department_id'] = $model->to_hr_department;
                                    $inventory = BichuvItemBalance::getLastRecord($item);
                                    $attributesTIB = [
                                        'inventory' => $inventory,
                                        'entity_id' => $item['entity_id'],
                                        'entity_type' => $item['entity_type'],
                                        'count' => $item['quantity'],
                                        'price_uzs' => $item['price_sum'],
                                        'price_usd' => $item['price_usd'],
                                        'document_id' => $model->id,
                                        'department_id' => $model->to_hr_department, // qaysi bo'lim
                                        'to_department' => $model->to_hr_department, // qaysi bo'limga
                                        'dep_area' => $defaultAcsAreaId, // qaysi sektor
                                        'to_area' => $defaultAcsAreaId, // qaysi sektorga
                                        'document_type' => $model->document_type,
                                        'is_own' => $item['is_own'],
                                        'sum_uzs' => $item['price_sum'] * $inventory,
                                        'sum_usd' => $item['price_usd'] * $inventory,
                                        'reg_date' => date('Y-m-d H:i:s')
                                    ];
                                    if ($item['is_own'] == 1) {
                                        $total['sum'] += $item['price_sum'] * $item['quantity'];
                                        $total['usd'] += $item['price_usd'] * $item['quantity'];
                                    }

                                    $ItemBalanceModel->setAttributes($attributesTIB);
                                    if ($ItemBalanceModel->save()) {
                                        $flagIB = true;
                                    }
                                }
                            }
                            if ($flagIB) {
                                $model->updateCounters(['status' => 2]);
                            }

                            // **********************    Bichuv_saldo ****************************** //

                            if ($total['sum'] > 0) {
                                $bichuvSaldo1 = new BichuvSaldo();
                                $attrBS1 = [
                                    'musteri_id' => $model->musteri_id,
                                    'department_id' => $model->to_department,
                                    'operation' => '1', // income
                                    'comment' => $model->doc_number,
                                    'payment_method' => $model->payment_method,
                                    'bd_id' => $model->id,
                                    'reg_date' => date('Y-m-d H:i:s'),
                                    'summa' => $total['sum'] ? $total['sum'] : $total['usd'],
                                    'credit1' => $total['sum'],
                                    'debit2' => $total['sum'],
                                    'pb_id' => 1,
                                ];

                                $bichuvSaldo1->setAttributes($attrBS1);
                                $bichuvSaldo1->save();
                            }

                            if ($total['usd'] > 0) {
                                $bichuvSaldo2 = new BichuvSaldo();
                                $attrBS2 = [
                                    'musteri_id' => $model->musteri_id,
                                    'department_id' => $model->to_department,
                                    'operation' => '1', // income
                                    'comment' => $model->doc_number,
                                    'payment_method' => $model->payment_method,
                                    'bd_id' => $model->id,
                                    'reg_date' => date('Y-m-d H:i:s'),
                                    'summa' => $total['sum'] ? $total['sum'] : $total['usd'],
                                    'credit1' => $total['usd'],
                                    'debit2' => $total['usd'],
                                    'pb_id' => 2,
                                ];
                                $bichuvSaldo2->setAttributes($attrBS2);
                                $bichuvSaldo2->save();
                            }

                            if ($model->paid_amount > 0) {
                                $bichuvSaldo3 = new BichuvSaldo();
                                $attrBS3 = [
                                    'musteri_id' => $model->musteri_id,
                                    'department_id' => $model->to_department,
                                    'operation' => '2', // outcome
                                    'comment' => $model->doc_number,
                                    'payment_method' => $model->payment_method,
                                    'bd_id' => $model->id,
                                    'reg_date' => date('Y-m-d H:i:s'),
                                    'summa' => $total['sum'] ? $total['sum'] : $total['usd'],
                                    'debit1' => $model->paid_amount,
                                    'credit2' => $model->paid_amount,
                                    'pb_id' => $model->payment_method,
                                ];
                                $bichuvSaldo3->setAttributes($attrBS3);
                                $bichuvSaldo3->save();
                            }

                            if ($flagIB) {
                                $transaction->commit();
                            } else {
                                $transaction->rollBack();
                            }
                        } catch (\Throwable $exception) {
                            $transaction->rollBack();
                            Yii::error($exception->getMessage(), 'exception');
                        }
                    }
                    break;
                case 2:
                    if ($slug == BichuvDoc::DOC_TYPE_MOVING_MATO_LABEL) {
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            $saved = false;
                            $modelId = $model->id;
                            $cloneModel = new BichuvDoc();
                            $cloneModel->attributes = $model->attributes;
                            $cloneModel->document_type = 7;
                            $y = date('Y');
                            $cloneModel->doc_number = "BK{$model->id}/{$y}";
                            $isClone = false;
                            if ($cloneModel->save()) {
                                $isClone = true;
                            }
                            $deptFrom = $model->from_hr_department;
                            $deptTo = $model->to_hr_department;
                            if ($isClone) {
                                $items = $model->getBichuvDocItems()->asArray()->all();
                                if (!empty($items)) {
                                    $cloneId = $cloneModel->id;
                                    foreach ($items as $item) {
                                        $cloneMusteriId = $item['musteri_id'] ?? $musteriId;
                                        $item['department_id'] = $deptFrom;
                                        $remain = BichuvRmItemBalance::getLastRecordMato($item, $cloneMusteriId);
                                        if (!empty($remain)) {
                                            if (($remain['inventory'] - $item['quantity']) < 0) {
                                                $lack_qty = $item['quantity'] - $remain['inventory'];
                                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                                    ['id' => $item['quantity']." kg", 'lack' => $lack_qty]));
                                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $model->type]);
                                            }
                                        }

                                    }
                                    foreach ($items as $item) {
                                        $cloneMusteriId = $item['musteri_id'] ?? $musteriId;
                                        //item balancedan tekwiriw
                                        $item['department_id'] = $deptFrom;
                                        $lastRec = BichuvRmItemBalance::getLastRecordMato($item, $cloneMusteriId);
                                        $inventory = $item['quantity'];
                                        $rollInventory = $item['roll_count'];
                                        if (!empty($lastRec)) {
                                            $inventory = $lastRec['inventory'] - $item['quantity'];
                                            $rollInventory = $lastRec['roll_inventory'] - $item['roll_count'];
                                            if ($rollInventory < 1 && $inventory > 0) {
                                                $rollInventory = 1;
                                            }
                                        }
                                        //item balancega yozish rasxod
                                        $modelBRIB = new BichuvRmItemBalance();
                                        $modelBRIB->setAttributes([
                                            'entity_id' => $item['entity_id'],
                                            'doc_type' => 2,
                                            'inventory' => $inventory,
                                            'count' => (-1) * $item['quantity'],
                                            'roll_inventory' => $rollInventory,
                                            'roll_count' => (-1) * $item['roll_count'],
                                            'from_department' => $deptFrom,
                                            'department_id' => $deptFrom,
                                            'to_department' => $deptTo,
                                            'from_musteri' => $cloneMusteriId,
                                            'doc_id' => $modelId,
                                            'party_no' => $item['party_no'],
                                            'musteri_party_no' => $item['musteri_party_no'],
                                            'model_id' => $item['model_id']
                                        ]);
                                        if ($modelBRIB->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            break;
                                        }
                                        //qabul qiluvchi uchun doc item
                                        $modelDocItems = new BichuvDocItems();
                                        $modelDocItems->setAttributes([
                                            'bichuv_doc_id' => $cloneId,
                                            'entity_id' => $item['entity_id'],
                                            'entity_type' => 2,
                                            'quantity' => $item['quantity'],
                                            'musteri_id' => $cloneMusteriId,
                                            'document_quantity' => 0,
                                            'price_sum' => 0,
                                            'price_usd' => 0,
                                            'is_own' => 1,
                                            'roll_count' => $item['roll_count'],
                                            'is_accessory' => $item['is_accessory'],
                                            'party_no' => $item['party_no'],
                                            'musteri_party_no' => $item['musteri_party_no'],
                                            'model_id' => $item['model_id']
                                        ]);
                                        if ($modelDocItems->save()) {
                                            //qabul qiluvchi uchun sub doc item
                                            $modelSubDocItems = new BichuvSubDocItems();
                                            $subItem = $modelDocItems->getMatoInfoByEntityId($item['entity_id']);
                                            $modelSubDocItems->setAttributes([
                                                'doc_item_id' => $modelDocItems->id,
                                                'musteri_id' => $cloneMusteriId,
                                                'musteri_party_no' => $item['musteri_party_no'],
                                                'party_no' => $item['party_no'],
                                                'roll_weight' => 0,
                                                'en' => $subItem['en'],
                                                'gramaj' => $subItem['gramaj'],
                                                'ne_id' => $subItem['ne_id'],
                                                'thread_id' => $subItem['thread_id'],
                                                'pus_fine_id' => $subItem['pus_fine_id'],
                                                'c_id' => $subItem['c_id'],
                                                'rm_id' => $subItem['rm_id'],
                                                'model' => (string)$subItem['model'],
                                                'thread_consist' => $subItem['thread_consist']
                                            ]);
                                            if ($modelSubDocItems->save()) {
                                                $saved = true;
                                            } else {
                                                $saved = false;
                                                break;
                                            }
                                        }
                                    }
                                }
                                if ($saved) {
                                    $model->updateCounters(['status' => 2]);
                                    $transaction->commit();
                                }
                            }
                        } catch (Exception $e) {
                            Yii::info('Not changed status to 3', 'save');
                        }
                    }
                    elseif ($slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL
                        || $slug == BichuvDoc::DOC_TYPE_MOVING_SLICE_TAY_LABEL
                        || $slug == BichuvDoc::DOC_TYPE_MOVING_SERVICE_LABEL
                        || $slug == BichuvDoc::DOC_TYPE_ACCEPTED_SlICE_FROM_BICHUV_LABEL) {
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            $t = Yii::$app->request->get('t');
                            $saved = false;
                            $nastelNo = [];
                            if ($t == 1 || $t == 5) {
                                $sliceItems = $model->getBichuvSliceItems()->asArray()->all();
                                $tayyorlov = HrDepartments::findOne(['token'=> Constants::$TOKEN_TAYYORLOV]);
                                $print = HrDepartments::findOne(['token'=> Constants::$TOKEN_PECHAT]);
                                $pattern = HrDepartments::findOne(['token'=> Constants::$TOKEN_NAQSH]);
                                $setDocType = BichuvDoc::DOC_TYPE_ACCEPTED;
                                if($model->to_hr_department === $print['id']
                                    || $model->to_hr_department === $pattern['id'])
                                {
                                    $setDocType = BichuvDoc::DOC_TYPE_ACCEPTED_FROM_BICHUV;
                                }
                                //Qabul qiluvchi document clone olgan

                                /** print or pattern uchun doc slice items and slice item blance */
                                if(($tayyorlov && $model->to_hr_department === $tayyorlov['id'])
                                    || ($print && $model->to_hr_department === $print['id'])
                                    || ($pattern && $model->to_hr_department === $pattern['id']))
                                {
                                    $cloneAccept = new BichuvDoc();
                                    $cYear = date('Y');
                                    $cloneAccept->setAttributes([
                                        'document_type' => $setDocType,
                                        'doc_number' => "TT{$model->id}/{$cYear}",
                                        'party_count' => 1,
                                        'reg_date' => date('Y-m-d H:i:s'),
                                        'status' => 1,
                                        'type' => $t,
                                        'musteri_id' => $model->musteri_id,
                                        'from_hr_department' => $model->from_hr_department,
                                        'to_hr_department' => $model->to_hr_department,
                                        'to_hr_employee' => $model->to_hr_employee,
                                        'from_hr_employee' => $model->from_hr_employee,
                                        'work_weight' => $model->work_weight,
                                        'add_info' => $model->add_info,
                                        'model_orders_items_id' => $model->model_orders_items_id
                                    ]);
                                    $isClone = false;
                                    if ($cloneAccept->save()) {
                                        $isClone = true;
                                    }
                                    if ($isClone) {
                                        $modelId = $model->id;
                                        $deptId = $model->from_hr_department;
                                        $deptTo = $model->to_hr_department;
                                        $cloneId = $cloneAccept->id;
                                        foreach ($sliceItems as $key => $item) {
                                            $itemNastel = $item['nastel_party'];
                                            if (!array_search($itemNastel,(array)$nastelNo))
                                                $nastelNo[$itemNastel] = $itemNastel;

                                            $modelAcceptItems = new BichuvSliceItems();
                                            $modelBalanceItems = new BichuvSliceItemBalance();
                                            $item['department_id'] = $deptId;
                                            $checkExists = $modelBalanceItems::getLastRecordSlice($item);
                                            if($checkExists['inventory']<=0||$checkExists['inventory']-$item['quantity']<0){
                                                $lack_qty = $item['quantity'] - $checkExists['inventory'];
                                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda <b>{id}</b> dan <b>{lack}</b> yetishmayapti',
                                                    ['id' => $item['nastel_party']." (".$item['size']['name'].")", 'lack' => $lack_qty]));
                                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $t]);
                                            }
                                            $inventory = $item['quantity'];
                                            if ($checkExists) {
                                                $inventory = $checkExists['inventory'] - $inventory;
                                            }
                                            $modelBalanceItems->setAttributes([
                                                'entity_id' => $item['id'],
                                                'entity_type' => 2,
                                                'party_no' => $item['nastel_party'],
                                                'size_id' => $item['size_id'],
                                                'count' => (-1) * $item['quantity'],
                                                'work_weight' => $item['work_weight'],
                                                'inventory' => $inventory,
                                                'doc_id' => $modelId,
                                                'doc_type' => 2,
                                                'model_id' => $item['model_id'],
                                                'hr_department_id' => $deptId,
                                                'to_hr_department' => $deptTo,
                                                'from_hr_department' => $deptId
                                            ]);


                                            if (!$modelBalanceItems->save()) {
                                                Yii::info('Not saved slice item balance', 'save');
                                                $saved = false;
                                                break;
                                            }
                                            $modelAcceptItems->setAttributes([
                                                'bichuv_doc_id' => $cloneId,
                                                'size_id' => $item['size_id'],
                                                'entity_id' => $item['entity_id'],
                                                'entity_type' => $item['entity_type'],
                                                'quantity' => $item['quantity'],
                                                'fact_quantity' => (int)$item['quantity'],
                                                'doc_qty' => $item['quantity'],
                                                'nastel_party' => $item['nastel_party'],
                                                'work_weight' => (!empty($item['work_weight']) ? (int)$item['work_weight'] : 0), // TODO:
                                                'status' => 1,
                                                'type' => $item['type'],
                                                'model_id' => $item['model_id'],
                                                'bichuv_given_roll_id' => $item['bichuv_given_roll_id']
                                            ]);
                                            if ($modelAcceptItems->save()) {
                                                $saved = true;
                                            } else {
                                                $saved = false;
                                                break;
                                            }
                                        }
                                    }
                                }else{
                                    $modelId = $model->id;
                                    $deptId = $model->from_hr_department;
                                    $deptTo = $model->to_hr_department;
                                    foreach ($sliceItems as $item) {
                                        // $modelAcceptItems = ($usluga&&$model->to_department===$usluga['id'])?new UslugaDocItems():new TikuvDocItems();
                                        $modelBalanceItems = new BichuvSliceItemBalance();
                                        $item['department_id'] = $deptId;
                                        $checkExists = $modelBalanceItems::getLastRecordSlice($item);
                                        if($checkExists['inventory']<=0||$checkExists['inventory']-$item['quantity']<0){
                                            $lack_qty = $item['quantity'] - $checkExists['inventory'];
                                            Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda <b>{id}</b> dan <b>{lack}</b> yetishmayapti',
                                                ['id' => $item['nastel_party']." (".$item['size']['name'].")", 'lack' => $lack_qty]));
                                            return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $t]);
                                        }
                                        $inventory = $item['quantity'];
                                        if ($checkExists) {
                                            $inventory = $checkExists['inventory'] - $inventory;
                                        }
                                        $modelBalanceItems->setAttributes([
                                            'entity_id' => $item['id'],
                                            'entity_type' => 2,
                                            'party_no' => $item['nastel_party'],
                                            'size_id' => $item['size_id'],
                                            'count' => (-1) * $item['quantity'],
                                            'work_weight' => $item['work_weight'],
                                            'inventory' => $inventory,
                                            'doc_id' => $modelId,
                                            'doc_type' => 2,
                                            'model_id' => $item['model_id'],
                                            'hr_department_id' => $deptId,
                                            'to_hr_department' => $deptTo,
                                            'from_hr_department' => $deptId
                                        ]);

                                        if ($modelBalanceItems->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            break;
                                        }
                                    }
                                }
                            }
                            elseif ($t == 2) {
                                $bichuvItems = $model->getBichuvDocItems()->asArray()->all();
                                //Qabul qiluvchi document clone olgan
                                $cloneAccept = new TikuvDoc();
                                $cYear = date('Y');
                                $fromDept = $model->from_department;
                                $toDept = $model->to_department;
                                $musteriId = $model->musteri_id;
                                $modelId = $model->id;
                                $modelType = $model->type;
                                $cloneAccept->setAttributes([
                                    'document_type' => 7,
                                    'doc_number' => "TK{$model->id}/{$cYear}",
                                    'party_count' => 1,
                                    'type' => $t,
                                    'reg_date' => date('Y-m-d H:i:s'),
                                    'status' => 1,
                                    'musteri_id' => $musteriId,
                                    'from_department' => $fromDept,
                                    'to_department' => $toDept,
                                    'to_employee' => $model->to_employee,
                                    'from_employee' => $model->from_employee,
                                    'work_weight' => $model->work_weight,
                                    'add_info' => $model->add_info
                                ]);
                                $isClone = false;
                                if ($cloneAccept->save()) {
                                    $isClone = true;
                                    $saved = true;
                                }
                                if ($isClone) {
                                    foreach ($bichuvItems as $item) {
                                        $item['department_id'] = $model->from_department;
                                        $remain = BichuvItemBalance::getLastRecordMoving($item);
                                        if (($remain['inventory'] - $item['quantity']) < 0) {
                                            $lack_qty = $item['quantity'] - $remain['inventory'];
                                            Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                                ['id' => $item['id'], 'lack' => $lack_qty]));
                                            return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $modelType]);
                                        }
                                    }
                                    $cloneModelId = $cloneAccept->id;
                                    foreach ($bichuvItems as $item) {
                                        $modelBIB = new BichuvItemBalance();
                                        $modelTikuvRmItems = new TikuvRmItems();
                                        $params = [];
                                        $params['department_id'] = $fromDept;
                                        $params['entity_id'] = $item['entity_id'];
                                        $params['entity_type'] = $item['entity_type'];
                                        $inventory = BichuvItemBalance::getLastRecordMoving($params);
                                        if (!empty($inventory)) {
                                            $attributesTIB = [
                                                'inventory' => $inventory['inventory'] - $item['quantity'],
                                                'entity_id' => $item['entity_id'],
                                                'entity_type' => $item['entity_type'],
                                                'count' => (-1) * $item['quantity'],
                                                'price_uzs' => $item['price_sum'],
                                                'price_usd' => $item['price_usd'],
                                                'document_id' => $modelId,
                                                'department_id' => $fromDept,
                                                'from_department' => $fromDept,
                                                'to_department' => $toDept,
                                                'comment' => 'BA',
                                                'document_type' => 2,
                                                'is_own' => $item['is_own'],
                                                'sum_uzs' => $item['price_sum'] * $inventory['inventory'],
                                                'sum_usd' => $item['price_usd'] * $inventory['inventory'],
                                                'reg_date' => date('Y-m-d H:i:s')
                                            ];
                                            $modelBIB->setAttributes($attributesTIB);
                                            //Accepted Doc Items
                                            $modelTikuvRmItems->setAttributes([
                                                'tikuv_doc_id' => $cloneModelId,
                                                'quantity' => $item['quantity'],
                                                'model_id' => $item['model_id'],
                                                'nastel_no' => $item['nastel_no'],
                                                'entity_id' => $item['entity_id'],
                                                'entity_type' => 1,
                                                'is_accessory' => 2
                                            ]);
                                            if ($modelBIB->save() && $modelTikuvRmItems->save()) {
                                                $saved = true;
                                            } else {
                                                $saved = false;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            elseif ($t == 3) {
                                $bichuvItems = $model->getBichuvDocItems()->asArray()->all();
                                //Qabul qiluvchi document clone olgan
                                $cYear = date('Y');
                                $fromDept = $model->from_department;
                                $toDept = $model->to_department;
                                $musteriId = $model->musteri_id;
                                $modelId = $model->id;
                                $modelType = $model->type;
                                $ombor = ToquvDepartments::find()->where(['token' => 'BICHUV_MATO_OMBOR'])->asArray()->one();
                                $isOmbor = false;
                                if (!empty($ombor)) {
                                    if ($ombor['id'] == $toDept) {
                                        $isOmbor = true;
                                    }
                                }
                                if ($isOmbor) {
                                    $cloneAccept = new BichuvDoc();
                                } else {
                                    $cloneAccept = new TikuvDoc();
                                }
                                $cloneAccept->setAttributes([
                                    'document_type' => 7,
                                    'doc_number' => "TK{$modelId}/{$cYear}",
                                    'party_count' => 1,
                                    'reg_date' => date('Y-m-d H:i:s'),
                                    'status' => 1,
                                    'type' => $t,
                                    'musteri_id' => $musteriId,
                                    'from_department' => $fromDept,
                                    'to_department' => $toDept,
                                    'to_employee' => $model->to_employee,
                                    'from_employee' => $model->from_employee,
                                    'work_weight' => $model->work_weight,
                                    'add_info' => $model->add_info
                                ]);
                                $isClone = false;
                                if ($cloneAccept->save()) {
                                    $isClone = true;
                                    $saved = true;
                                }
                                if ($isClone) {
                                    foreach ($bichuvItems as $item) {
                                        $item['department_id'] = $fromDept;
                                        $remain = BichuvRmItemBalance::getLastRecordMato($item, $musteriId);
                                        if (!empty($remain)) {
                                            if (($remain['inventory'] - $item['quantity']) < 0) {
                                                $lack_qty = $item['quantity'] - $remain['inventory'];
                                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                                    ['id' => $item['id'], 'lack' => $lack_qty]));
                                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $modelType]);
                                            }
                                        }
                                    }
                                    $cloneModelId = $cloneAccept->id;
                                    foreach ($bichuvItems as $item) {
                                        $modelBIB = new BichuvRmItemBalance();
                                        //Tikuv Doc Items
                                        if ($isOmbor) {
                                            $modelTikuvRmItems = new BichuvDocItems();
                                        } else {
                                            $modelTikuvRmItems = new TikuvRmItems();
                                        }
                                        $item['department_id'] = $fromDept;
                                        $remain = $modelBIB::getLastRecordMato($item, $musteriId);
                                        $inventory = $item['quantity'];
                                        $rollInventory = $item['roll_count'];
                                        if ($remain) {
                                            $inventory = $remain['inventory'] - $inventory;
                                            $rollInventory = $remain['roll_inventory'] - $rollInventory;
                                            if ($rollInventory < 1 && $inventory > 0) {
                                                $rollInventory = 1;
                                            }
                                        }
                                        $modelBIB->setAttributes([
                                            'entity_id' => $item['entity_id'],
                                            'doc_type' => 1,
                                            'inventory' => $inventory,
                                            'count' => (-1) * $item['quantity'],
                                            'roll_inventory' => $rollInventory,
                                            'roll_count' => (-1) * $item['roll_count'],
                                            'party_no' => $item['party_no'],
                                            'model_id' => $item['model_id'],
                                            'doc_id' => $modelId,
                                            'musteri_party_no' => $item['musteri_party_no'],
                                            'from_department' => $fromDept,
                                            'to_department' => $toDept,
                                            'department_id' => $fromDept,
                                            'from_musteri' => $musteriId,
                                        ]);
                                        //Accepted Doc Items
                                        $attributes = [
                                            'tikuv_doc_id' => $cloneModelId,
                                            'quantity' => $item['quantity'],
                                            'roll_count' => (!empty($item['roll_count']) ? (int)$item['roll_count'] : 0),
                                            'party_no' => $item['party_no'],
                                            'musteri_party_no' => $item['musteri_party_no'],
                                            'model_id' => $item['model_id'],
                                            'nastel_no' => $item['nastel_no'],
                                            'entity_id' => $item['entity_id'],
                                            'entity_type' => 2
                                        ];
                                        if ($isOmbor) {
                                            unset($attributes['tikuv_doc_id']);
                                            $attributes['bichuv_doc_id'] = $cloneModelId;
                                            $attributes['model_id'] = $item['rm_model_id'];
                                            $attributes['price_sum'] = 0;
                                            $attributes['price_usd'] = 0;
                                        }
                                        $modelTikuvRmItems->setAttributes($attributes);
                                        if ($modelBIB->save() && $modelTikuvRmItems->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            break;
                                        }
                                    }
                                }
                            }
                            elseif ($t == 4) {
                                $sliceItems = $model->getBichuvSliceItems()->asArray()->all();
                                $modelId = $model->id;
                                $deptId = $model->from_department;
                                $deptTo = $model->to_department;
                                $musteriId = $model->service_musteri_id;
                                $modelData = $model->getModelListInfo();

                                //Qabul qiluvchi document clone olgan
                                $cloneAccept = new TikuvDoc();
                                $cYear = date('Y');
                                $fromDept = $model->from_department;
                                $toDept = $model->to_department;
                                $cloneAccept->setAttributes([
                                    'document_type' => 8,
                                    'doc_number' => "TK{$model->id}/{$cYear}",
                                    'party_count' => 1,
                                    'type' => $t,
                                    'reg_date' => date('Y-m-d H:i:s'),
                                    'status' => 3,
                                    'musteri_id' => $musteriId,
                                    'from_department' => $fromDept,
                                    'to_department' => $toDept,
                                    'to_employee' => $model->to_employee,
                                    'from_employee' => $model->from_employee,
                                    'work_weight' => $model->work_weight,
                                    'add_info' => $model->add_info,
                                ]);
                                $isClone = false;
                                if ($cloneAccept->save()) {
                                    $isClone = true;
                                    $saved = true;
                                }

                                if($isClone){
                                    $cloneId = $cloneAccept->id;
                                    foreach ($sliceItems as $item) {
                                        $nastelNo = $item['nastel_party'];
                                        $modelAcceptItems = new TikuvDocItems();
                                        $modelBalanceItems = new BichuvSliceItemBalance();
                                        $modelServiceItemBlanace  = new BichuvServiceItemBalance();
                                        $checkExists = $modelBalanceItems::getLastRecord($item);
                                        $item['musteri_id'] = $musteriId;
                                        $checkServiceExists = $modelServiceItemBlanace::getLastRecord($item);
                                        $inventory = $item['quantity'];
                                        if ($checkExists) {
                                            $inventory = $checkExists['inventory'] - $inventory;
                                        }
                                        $inventoryService = $item['quantity'];
                                        if ($checkServiceExists) {
                                            $inventoryService = $checkServiceExists['inventory'] - $inventoryService;
                                        }
                                        //Doc Items
                                        $modelAcceptItems->setAttributes([
                                            'tikuv_doc_id' => $cloneId,
                                            'size_id' => $item['size_id'],
                                            'entity_id' => $item['entity_id'],
                                            'entity_type' => $item['entity_type'],
                                            'quantity' => $item['quantity'],
                                            'doc_qty' => $item['quantity'],
                                            'nastel_party_no' => $item['nastel_party'],
                                            'work_weight' => (!empty($item['work_weight']) ? (int)$item['work_weight'] : 0),
                                            'status' => 1,
                                            'boyoqhona_model_id' => $item['model_id']
                                        ]);
                                        $modelBalanceItems->setAttributes([
                                            'entity_id' => $item['id'],
                                            'entity_type' => 2,
                                            'party_no' => $item['nastel_party'],
                                            'size_id' => $item['size_id'],
                                            'count' => (-1) * $item['quantity'],
                                            'work_weight' => $item['work_weight'],
                                            'inventory' => $inventory,
                                            'doc_id' => $modelId,
                                            'doc_type' => 2,
                                            'model_id' => $item['model_id'],
                                            'department_id' => $deptId,
                                            'to_department' => $deptTo,
                                            'from_department' => $deptId
                                        ]);
                                        if ($modelAcceptItems->save() && $modelBalanceItems->save() && $modelServiceItemBlanace->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            break;
                                        }
                                    }
                                    if($saved){
                                        $cloneAccept->party_no = $nastelNo;
                                        $cloneAccept->save();
                                    }
                                }
                            }
                            if ($saved) {
                                $mobileTable = MobileTables::findOne(['token' => Constants::TOKEN_BICHUV_KESIM_KOCHIRISH]);
                                if(!empty($mobileTable) && !empty($nastelNo)){
                                    foreach ($nastelNo as $nastel){
                                        $cardItem = MobileProcessProduction::getCardItemOrder($nastel);
                                        if ($cardItem){
                                            $params = [
                                                'nastel_no' => $nastel,
                                                'started_date' => date("d.m.Y H:i:s"),
                                                'ended_date' => date("d.m.Y H:i:s"),
                                                'status' => MobileProcessProduction::STATUS_ENDED,
                                                'doc_id' => $model->id,
                                                'table_name' => BichuvDoc::getTableSchema()->name,
                                                'mobile_tables_id' => $mobileTable->id,
                                                'model_orders_items_id' => $cardItem['model_orders_items_id'],
                                                'parent_id' => $cardItem['parent_id'],
                                                'bichuv_detail_type_id' => $cardItem['bichuv_detail_type_id'],
                                                'base_detail_list_id' => $cardItem['base_detail_list_id']
                                            ];
                                            if(MobileProcessProduction::saveMobileProcess($params)){
                                                $saved = true;
                                            }else{
                                                $saved = false;
                                                break;
                                            }
                                        }

                                    }
                                    if($saved){
                                        Yii::$app->session->setFlash('success', Yii::t('app','Saqlandi'));
                                        $model->updateCounters(['status' => 2]);
                                        $transaction->commit();
                                    }else{
                                        Yii::$app->session->setFlash('error', Yii::t('app','Saqlashda xatolik!'));
                                    }
                                }else{
                                    Yii::$app->session->setFlash('error', Yii::t('app','Not mobile table'));
                                    $saved = false;
                                }
                            }
                            
                        } catch (Exception $e) {
                            Yii::info('Not changed status to 3', 'save');
                        }
                    }
                    else {
                        $depIdATO = ToquvDepartments::find()->andWhere(['token' => 'ACS_TARQATUVCHI_OMBOR'])->scalar();
                        $transaction = Yii::$app->db->beginTransaction();
                        $saved = false;
                        try {
                            $TDItems = $model->getBichuvDocItems()->asArray()->all();
                            $flagIB = false;
                            $modelId = $model->id;
                            $fromDept = $model->from_hr_department;
                            $toDept = $model->to_hr_department;
                            $docType = $model->document_type;
                            //Qabul qiluvchi document clone olgan
                            $cloneAcceptDocModel = $model;
                            $cloneAccept = new BichuvDoc();
                            //qabul acs uchun doc type 7
                            $cloneAcceptDocModel->document_type = 7;
                            // qabul ATO(SAMO) dan doc type 16
                            if ($depIdATO == $toDept) {
                                $cloneAcceptDocModel->document_type = BichuvDoc::DOC_TYPE_ACCEPTED_ACS_FROM_WAREHOUSE;
                                $cloneAcceptDocModel->parent_id = $model->id;
                            }
                            $cloneAcceptDocModel->status = 1;
                            $cYear = date('Y');
                            $cloneAcceptDocModel->doc_number = "BK{$model->id}/{$cYear}";
                            $cloneAcceptDocModel->action = 1;
                            $cloneAccept->attributes = $cloneAcceptDocModel->attributes;
                            $isClone = false;

                            if ($cloneAccept->save()) {
                                $isClone = true;
                            }
                            if (!empty($TDItems)&&$isClone) {
                                foreach ($TDItems as $item) {
                                    $item['department_id'] = $model->from_hr_department;
                                    $remain = BichuvItemBalance::getLastRecordMoving($item);
                                    if (($remain['inventory'] - $item['quantity']) < 0) {
                                        $lack_qty = $item['quantity'] - $remain['inventory'];
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', Yii::t('app', "Sizda {id} dan {lack} yetishmayapti",
                                            ['id' => BichuvAcs::findOne($item['entity_id'])->name, 'lack' => $lack_qty]));
                                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                    }
                                }
                                foreach ($TDItems as $item) {
                                    $flagIB = false;
                                    if ($isClone) {
                                        $modelAcceptItems = new BichuvDocItems();
                                        $modelAcceptItems->setAttributes($item);
                                        $modelAcceptItems->bichuv_doc_id = $cloneAccept->id;
                                        if ($modelAcceptItems->save()) {
                                            $saved = true;
                                        }else{
                                            $saved = false;
                                            break;
                                        }
                                    }
                                    $ItemBalanceModel = new BichuvItemBalance();
                                    $item['department_id'] = $model->from_hr_department;
                                    $lastRec = BichuvItemBalance::getLastRecordMoving($item);
                                    $inv = $item['quantity'];
                                    if (!empty($lastRec['inventory'])) {
                                        $inv = $lastRec['inventory'] - $item['quantity'];
                                    }else{
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', Yii::t('app', 'Item topilmadi'));
                                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                    }
                                    $attributesTIB = [
                                        'inventory' => $inv,
                                        'entity_id' => $item['entity_id'],
                                        'entity_type' => $item['entity_type'],
                                        'count' => (-1) * $item['quantity'],
                                        'price_uzs' => $lastRec['price_uzs'],
                                        'price_usd' => $lastRec['price_usd'],
                                        'document_id' => $modelId,
                                        'department_id' => $fromDept,
                                        'to_department' => $toDept,
                                        'from_department' => $fromDept,
                                        'document_type' => $docType,
                                        'reg_date' => date('Y-m-d H:i:s'),
                                        'comment' => $item['add_info']
                                    ];
                                    $ItemBalanceModel->setAttributes($attributesTIB);
                                    if ($ItemBalanceModel->save()) {
                                        $dept = ToquvDepartments::findOne(['token'=>'BICHUV_ACS'])['id'];
                                        if($item['entity_type']==1&&$dept==$fromDept){
                                            $bichuv_acs = BichuvAcs::findOne($item['entity_id']);
                                            if($bichuv_acs){
                                                if($bichuv_acs->stock_limit_min>=$inv){
                                                    $info = new StockLimitInfo();
                                                    $params = [
                                                        'table' => 'bichuv_acs',
                                                        'module' => 'bichuv',
                                                        'entity_id' => $item['entity_id'],
                                                        'entity_type' => $item['entity_type'],
                                                        'stock_limit_min' => $bichuv_acs['stock_limit_min'],
                                                        'stock_limit_max' => $bichuv_acs['stock_limit_max'],
                                                        'remain' => $inv,
                                                        'body' => $bichuv_acs->sku." ".$bichuv_acs->name." ".$bichuv_acs->property->name." dan ".$inv." ".$bichuv_acs->unit->name." qoldi",
                                                        'telegram' => [
                                                            376544097,//Doston
                                                            265441481,//G'ayrat aka,
                                                            197418454,//Shukrullox aka
                                                            573952274 //Sherzod aka bichuv aksessuar
                                                        ]
                                                    ];
                                                    $info->saveLog($params);
                                                }
                                            }
                                        }
                                        $flagIB = true;
                                    }else{
                                        $transaction->rollBack();
                                        break;
                                        Yii::$app->session->setFlash('error', Yii::t('app', 'Item saqlanmadi'));
                                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                    }

                                    /** 
                                     * agar Aksessuar tarqatuvchi omborga ko'chirish qilayotgan bo'lsa uni item balance ga yozmaydi 
                                     */
                                    if ($depIdATO === false || $depIdATO != $toDept) { // TODO: shuni keyinroq kommentga olib qo'yish kerak, sababi ko'chirishda qabul qilgandan keyin item balancega yozish kerak
                                        $ItemBalanceModelAccept = new BichuvItemBalance();
                                        $item['department_id'] = $model->to_department;
                                        $lastRec = BichuvItemBalance::getLastRecordMoving($item);
                                        $inv = $item['quantity'];
                                        if (!empty($lastRec['inventory'])) {
                                            $inv = $lastRec['inventory'] + $item['quantity'];
                                        }
                                        $attributesTIB_ACCEPT = [
                                            'inventory' => $inv,
                                            'entity_id' => $item['entity_id'],
                                            'entity_type' => $item['entity_type'],
                                            'count' => $item['quantity'],
                                            'price_uzs' => $lastRec['price_uzs'],
                                            'price_usd' => $lastRec['price_usd'],
                                            'document_id' => $model->id,
                                            'department_id' => $model->to_department,
                                            'from_department' => $model->from_department,
                                            'to_department' => $model->to_department,
                                            'document_type' => $model->document_type,
                                            'reg_date' => date('Y-m-d H:i:s'),
                                            'comment' => $item['add_info']
                                        ];

                                        $ItemBalanceModelAccept->setAttributes($attributesTIB_ACCEPT);
                                        if ($ItemBalanceModelAccept->save()&&$flagIB) {
                                            $flagIB = true;
                                        }
                                        else{
                                            $transaction->rollBack();
                                            break;
                                            Yii::$app->session->setFlash('error', Yii::t('app', 'Item2 saqlanmadi'));
                                            return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                                        }
                                    }
                                }
                            }
                            if ($flagIB) {
                                $saved = true;
                                $model->updateCounters(['status' => 2]);
                            }
                            if($saved) {
                                Yii::$app->session->setFlash('success',  Yii::t('app', 'Saved Successfully'));
                                $transaction->commit();
                            }else{
                                $transaction->rollBack();
                            }
                        } catch (\Exception $e) {
                            Yii::info('Not saved' . $e, 'save');
                            $transaction->rollBack();
                        }
                    }
                    break;
                case 3:
                    $transaction = Yii::$app->db->beginTransaction();
                    try {

                        $saved = false;
                        $modelId = $model->id;
                        $service_musteri_id = $model->service_musteri_id;
                        $items = $model->getBichuvDocItems()->asArray()->all();
                        if (!empty($items)) {
                            foreach ($items as $item) {
                                $cMusteriId = $item['musteri_id'] ?? $musteriId;
                                $item['department_id'] = $model->from_department;
                                $remain = BichuvRmItemBalance::getLastRecordMato($item, $cMusteriId);
                                if (!empty($remain)) {
                                    if (($remain['inventory'] - $item['quantity']) < 0) {
                                        $lack_qty = $item['quantity'] - $remain['inventory'];
                                        Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                            ['id' => $item['quantity']." kg", 'lack' => $lack_qty]));
                                        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $model->type]);
                                    }
                                }

                                //item balancedan tekwiriw
                                $lastRec = BichuvRmItemBalance::getLastRecordMato($item, $cMusteriId);
                                $inventory = $item['quantity'];
                                $rollInventory = $item['roll_count'];
                                if (!empty($lastRec)) {
                                    $inventory = $lastRec['inventory'] - $item['quantity'];
                                    $rollInventory = $lastRec['roll_inventory'] - $item['roll_count'];
                                    if ($rollInventory < 1 && $inventory > 0) {
                                        $rollInventory = 1;
                                    }
                                }
                                //item balancega yozish rasxod
                                $modelBRIB = new BichuvRmItemBalance();
                                $modelBRIB->setAttributes([
                                    'entity_id' => $item['entity_id'],
                                    'doc_type' =>3,
                                    'inventory' => $inventory,
                                    'count' => (-1) * $item['quantity'],
                                    'roll_inventory' => $rollInventory,
                                    'roll_count' => (-1) * $item['roll_count'],
                                    'from_department' => $model->from_department,
                                    'department_id' => $model->from_department,
                                    //  [department_id] => Array ( [0] => «Department Id» qiymati noto`g`ri.
                                    // TODO departmentni foreignlarini ko'rish  kerak
                                    'from_musteri' => $cMusteriId,
                                    'to_musteri' => $service_musteri_id,
                                    'doc_id' => $modelId,
                                    'party_no' => $item['party_no'],
                                    'musteri_party_no' => $item['musteri_party_no'],
                                    'model_id' => $item['model_id']
                                ]);
                                if ($modelBRIB->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    break;
                                }

                            }

                        }
                        if ($saved) {
                            $model->updateCounters(['status' => 2]);
                            $transaction->commit();
                        }
                    } catch (Exception $e) {
                        Yii::info('Not changed status to 3', 'save');
                    }
                    break;
                case 5:
                    $TDItems = $model->getBichuvDocItems()->asArray()->all();
                    $flagIB = false;
                    if (!empty($TDItems)) {
                        foreach ($TDItems as $item) {
                            $item['department_id'] = $model->from_department;
                            $remain = BichuvItemBalance::getLastRecordMoving($item);
                            if (($remain['inventory'] - $item['quantity']) < 0) {
                                $lack_qty = $item['quantity'] - $remain['inventory'];
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                    ['id' => $item['id'], 'lack' => $lack_qty]));
                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug]);
                            }
                        }
                        foreach ($TDItems as $item) {
                            $flagIB = false;
                            $ItemBalanceModel = new BichuvItemBalance();
                            $item['department_id'] = $model->from_department;
                            $lastRec = BichuvItemBalance::getLastRecordMoving($item);

                            $attributesTIB = [
                                'inventory' => $lastRec['inventory'] - $item['quantity'],
                                'entity_id' => $item['entity_id'],
                                'entity_type' => $item['entity_type'],
                                'count' => (-1) * $item['quantity'],
                                'price_uzs' => $lastRec['price_uzs'],
                                'price_usd' => $lastRec['price_usd'],
                                'document_id' => $model->id,
                                'department_id' => $model->from_department,
                                'document_type' => $model->document_type,
                                'reg_date' => date('Y-m-d H:i:s'),
                                'comment' => $item['add_info']
                            ];

                            $ItemBalanceModel->setAttributes($attributesTIB);
                            if ($ItemBalanceModel->save()) {
                                $flagIB = true;
                            }
                        }
                    }
                    if ($flagIB) {
                        $model->updateCounters(['status' => 2]);
                    }
                    break;
                case BichuvDoc::DOC_TYPE_ACCEPTED:
                    /** Qabul qilish **/
                    $items = $model->getBichuvDocItems()->asArray()->all();
                    switch ($slug) {
                        case BichuvDoc::DOC_TYPE_ACCEPTED_MATO_LABEL:
                            /**** Mato qabul qilish ****/
                            $transaction = Yii::$app->db->beginTransaction();

                            try {
                                $saved = false;
                                if (!empty($items)) {
                                    foreach ($items as $item) {
                                        $modelBRIB = new BichuvRmItemBalance();
                                        $saved = $modelBRIB->increaseItemBalance($modelBRIB,$model,$item);
                                        if (!$saved){
                                            Yii::$app->session->setFlash('error', Yii::t('app','Saqlanmadi'));
                                            break;
                                        }
                                    }
                                }
                                if ($saved) {
                                    $btrwdId = BichuvTableRelWmsDoc::getBichuvTableRelWmsDocByNastelId($model['bichuv_nastel_list_id'],BichuvTableRelWmsDoc::STATUS_ACCEPTED);
                                    if($btrwdId){
                                        $model->updateCounters(['status' => 2]);
                                        Yii::$app->session->setFlash('success', Yii::t('app','Qabul qilindi'));
                                        $transaction->commit();
                                    }
                                } else {
                                    $transaction->rollBack();
                                }
                            } catch (Exception $e) {
                                Yii::info('Not all saved ' . $e->getMessage(), 'save');
                            }
                            break;
                        case BichuvDoc::DOC_TYPE_INCOMING_SLICE_LABEL:
                            $transaction = Yii::$app->db->beginTransaction();
                            try {
                                $nastelNo = [];
                                $sliceItems = $model->getBichuvSliceItems()->asArray()->all();
                                $saved = false;
                                $modelId = $model->id;
                                $fromId = $model->from_hr_department;
                                $deptId = $model->to_hr_department;
                                $musteriId = $model->musteri_id;
                                $givenData = [];
                                foreach ($sliceItems as $item) {
                                    $itemNastel = $item['nastel_party'];
                                    if (!array_search($itemNastel,(array)$nastelNo))
                                        $nastelNo[$itemNastel] = $itemNastel;
                                    $modelAcceptItems = new BichuvSliceItemBalance();
                                    $item['department_id'] = $model['to_hr_department'];
                                    $checkExists = BichuvSliceItemBalance::getLastRecord($item);
                                    $inventory = $item['quantity'];
                                    if (!empty($checkExists)) {
                                        $inventory = $inventory + $checkExists['inventory'];
                                    }
                                    $modelAcceptItems->setAttributes([
                                        'entity_id' => $item['id'],
                                        'entity_type' => 2,
                                        'party_no' => $item['nastel_party'],
                                        'size_id' => $item['size_id'],
                                        'model_id' => $item['model_id'],
                                        'count' => $item['quantity'],
                                        'inventory' => $inventory,
                                        'doc_id' => $modelId,
                                        'doc_type' => 2,
                                        'hr_department_id' => $deptId,
                                        'to_hr_department' => $deptId,
                                        'from_hr_department' => $fromId
                                    ]);
                                    if (!array_key_exists($item['bichuv_given_roll_id'], $givenData)) {
                                        $givenData[$item['bichuv_given_roll_id']] = $item['quantity'];
                                    } else {
                                        $givenData[$item['bichuv_given_roll_id']] += $item['quantity'];
                                    }
                                    if ($modelAcceptItems->save()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                                $tableQabulKesim = MobileTables::getTableByToken(Constants::TOKEN_BICHUV_QABUL_KESIM);
                                if (!empty($nastelNo) && !empty($tableQabulKesim)){
                                    $tableId = $tableQabulKesim->id;
                                    foreach ($nastelNo as $nastel){
                                        $cardItem = MobileProcessProduction::getCardItemOrder($nastel);
                                        if ($cardItem){
                                            $params = [
                                                'nastel_no' => $nastel,
                                                'started_date' => date('d.m.Y H:i:s'),
                                                'ended_date' => date('d.m.Y H:i:s'),
                                                'table_name' => BichuvDoc::getTableSchema()->name,
                                                'doc_id' => $model->id,
                                                'mobile_tables_id' => $tableId,
                                                'model_orders_items_id' => $cardItem['model_orders_items_id'],
                                                'parent_id' => $cardItem['parent_id'],
                                                'bichuv_detail_type_id' => $cardItem['bichuv_detail_type_id'],
                                                'base_detail_list_id' => $cardItem['base_detail_list_id']
                                            ];
                                            if( MobileProcessProduction::saveMobileProcess($params)){
                                                $saved = true;
                                            }else{
                                                $saved = false;
                                                break;
                                            }
                                        }

                                    }
                                }
                                if ($saved) {
                                    $model->updateCounters(['status' => 2]);
                                    $transaction->commit();
                                }
                            } catch (Exception $e) {
                                Yii::info('Not all saved ' . $e->getMessage(), 'save');
                            } catch (InvalidConfigException $e) {
                                Yii::info('Not all saved ' . $e->getMessage(), 'save');
                            }
                            break;
                        /*case BichuvDoc::DOC_TYPE_ACCEPTED_LABEL:
                            $transaction = Yii::$app->db->beginTransaction();
                            try {
                                $saved = false;
                                if (!empty($items)) {
                                    $modelId = $model->id;
                                    $toDept = $model->to_department;
                                    $fromDept = $model->from_department;
                                    $deptId = $model->to_department;
                                    foreach ($items as $item) {
                                        $modelBIB = new BichuvItemBalance();
                                        $item['department_id'] = $deptId;
                                        $inventory = BichuvItemBalance::getLastRecord($item);
                                        $attributesTIB = [
                                            'inventory' => $inventory,
                                            'entity_id' => $item['entity_id'],
                                            'entity_type' => $item['entity_type'],
                                            'count' => $item['quantity'],
                                            'price_uzs' => $item['price_sum'],
                                            'price_usd' => $item['price_usd'],
                                            'document_id' => $modelId,
                                            'department_id' => $deptId,
                                            'from_department' => $fromDept,
                                            'to_department' => $toDept,
                                            'comment' => 'BichuvNastel',
                                            'document_type' => 1,
                                            'is_own' => $item['is_own'],
                                            'sum_uzs' => $item['price_sum'] * $inventory,
                                            'sum_usd' => $item['price_usd'] * $inventory,
                                            'reg_date' => date('Y-m-d H:i:s')
                                        ];
                                        $modelBIB->setAttributes($attributesTIB);
                                        if ($modelBIB->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            break;
                                        }
                                    }
                                } else {
                                    $saved = true;
                                }
                                $model->updateCounters(['status' => 2]);
                                if ($saved) {
                                    $transaction->commit();
                                } else {
                                    $transaction->rollBack();
                                }
                            } catch (Exception $e) {
                                Yii::info('Not all saved ' . $e, 'save');
                            }
                            break;*/
                    }
                    break;
                case 8:
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $sliceItems = $model->getBichuvSliceItems()->asArray()->all();
                        $saved = false;
                        $modelId = $model->id;
                        $deptId = $model->to_hr_department;
                        $givenData = [];
                        $nastelData = [];
                        $bichuvDocTableName = BichuvDoc::getTableSchema()->name;

                        $sqlPC = "select  mpp.id, 
                                          mpp.model_orders_items_id 
                                from mobile_process_production mpp
                                left join mobile_tables mt on mpp.mobile_tables_id = mt.id
                                where mpp.nastel_no = '%s' AND mt.token = '%s';";
                        $sqlPC = sprintf($sqlPC, $model->nastel_no, Constants::TOKEN_BICHUV_PRODUCTION_MATO);
                        $parentCard = Yii::$app->db->createCommand($sqlPC)->queryOne();
                        if(!empty($parentCard)){
                            foreach ($sliceItems as $key => $item) {
                                $nastelNo = $item['nastel_party'];
                                $modelAcceptItems = new BichuvSliceItemBalance();
                                $checkExists = BichuvSliceItemBalance::getLastRecord($item);
                                $inventory = $item['quantity'];
                                if (!empty($checkExists)) {
                                    $inventory = $inventory + $checkExists['inventory'];
                                }
                                $modelAcceptItems->setAttributes([
                                    'entity_id' => $item['id'],
                                    'entity_type' => 2,
                                    'party_no' => $item['nastel_party'],
                                    'size_id' => $item['size_id'],
                                    'model_id' => $item['model_id'],
                                    'count' => $item['quantity'],
                                    'inventory' => $inventory,
                                    'doc_id' => $modelId,
                                    'doc_type' => 2,
                                    'hr_department_id' => $deptId,
                                    'to_hr_department' => $deptId,
                                    'from_hr_department' => $deptId
                                ]);

                                if (!array_key_exists($item['bichuv_given_roll_id'], $givenData)) {
                                    $givenData[$item['bichuv_given_roll_id']] = $item['quantity'];
                                } else {
                                    $givenData[$item['bichuv_given_roll_id']] += $item['quantity'];
                                }
                                $nastelData[$item['bichuv_given_roll_id']] = $item['nastel_party'];

                                $mobileTable = MobileTables::findOne(['token' => Constants::TOKEN_BICHUV_QABUL_KESIM]);
                                if(!empty($mobileTable)){
                                    $params = [
                                        'nastel_no' => $nastelNo,
                                        'parent_id' => $parentCard['id'],
                                        'model_orders_items_id' => $parentCard['model_orders_items_id'],
                                        'table_name' => $bichuvDocTableName,
                                        'mobile_tables_id' => $mobileTable->id
                                    ];
                                    $mProcessProduction = MobileProcessProduction::findOne($params);
                                    if(empty($mProcessProduction)){
                                        $sqlMTICH = "select mpp.base_detail_list_id,
                                                           mpp.bichuv_detail_type_id
                                                           from mobile_tables mt
                                                    left join mobile_process_production mpp on mt.id = mpp.mobile_tables_id
                                                    where mt.token = '%s' AND mpp.nastel_no = '%s' LIMIT 1;";
                                        $sqlMTICH = sprintf($sqlMTICH, Constants::TOKEN_BICHUV_PRODUCTION_MATO, $nastelNo);

                                        $mobileTableICH = Yii::$app->db->createCommand($sqlMTICH)->queryOne();;
                                        if(!empty($mobileTableICH)){
                                            $params['started_date'] = date("d.m.Y H:i:s");
                                            $params['ended_date'] = date("d.m.Y H:i:s");
                                            $params['bichuv_detail_type_id'] = $mobileTableICH['bichuv_detail_type_id'];
                                            $params['base_detail_list_id'] = $mobileTableICH['base_detail_list_id'];
                                            $params['status'] = MobileProcessProduction::STATUS_ENDED;
                                            $params['doc_id'] = $modelId;

                                            if(MobileProcessProduction::saveMobileProcess($params)){
                                                $saved = true;
                                            }else{
                                                Yii::$app->session->setFlash('error', Yii::t('app','Saqlashda xatolik!'));
                                                $saved = false;
                                                break;
                                            }
                                        }
                                    }
                                    if($modelAcceptItems->save()){
                                        $saved = true;
                                    }else{
                                        $saved = false;
                                        break;
                                    }
                                }else{
                                    Yii::$app->session->setFlash('error', Yii::t('app','Process not found {processName}', ['processName' => Constants::TOKEN_BICHUV_QABUL_KESIM]));
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                        if($saved){
                            Yii::$app->session->setFlash('success', Yii::t('app','Saqlandi'));
                            $model->updateCounters(['status' => 2]);
                            $transaction->commit();
                        }else{
                            $transaction->rollBack();
                        }
                    } catch (Exception $e) {
                        Yii::info('Not all saved ' . $e->getMessage(), 'save');
                        $transaction->rollBack();
                    } catch (InvalidConfigException $e) {
                        Yii::info('Not all saved ' . $e->getMessage(), 'save');
                        $transaction->rollBack();
                    }
                    break;
                case 9:
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $modelId = $model->id;
                        $docBoyoqhonaModel = new ToquvDocuments();
                        $y = date('Y');
                        $docBoyoqhonaModel->setAttributes([
                            'document_type' => 1,
                            'entity_type' => 2,
                            'doc_number' => "TK{$model->id}/{$y}",
                            'status' => 1,
                            'is_tamir' => 1,
                            'from_department' => $model->from_department,
                            'to_department' => $model->to_department,
                            'add_info' => $model->add_info,
                            'reg_date' => date('Y-m-d H:i:s'),
                        ]);
                        $isClone = false;
                        $saved = false;
                        if ($docBoyoqhonaModel->save()) {
                            $isClone = true;
                        }
                        $cloneId = $docBoyoqhonaModel->id;
                        if ($isClone) {
                            $items = $model->getBichuvDocItems()->asArray()->all();
                            if (!empty($items)) {
                                $cloneMusteriId = $item['musteri_id'] ?? $musteriId;
                                foreach ($items as $item) {
                                    //item balancedan tekwiriw
                                    $lastRec = BichuvRmItemBalance::getLastRecord($item, $cloneMusteriId);
                                    $inventory = $item['inventory'];
                                    $rollInventory = $item['roll_inventory'];
                                    if ($lastRec) {
                                        $inventory = $lastRec['inventory'] - $item['quantity'];
                                        $rollInventory = $lastRec['roll_inventory'] - $item['roll_count'];
                                    }
                                    //item balancega yozish rasxod
                                    $modelBRIB = new BichuvRmItemBalance();
                                    $modelBRIB->setAttributes([
                                        'entity_id' => $item['entity_id'],
                                        'doc_type' => 2,
                                        'inventory' => $inventory,
                                        'count' => (-1) * $item['quantity'],
                                        'roll_inventory' => $rollInventory,
                                        'roll_count' => (-1) * $item['roll_count'],
                                        'from_department' => $model->from_department,
                                        'department_id' => $model->from_department,
                                        'to_department' => $model->to_department,
                                        'from_musteri' => $cloneMusteriId,
                                        'doc_id' => $modelId,
                                        'party_no' => $item['party_no'],
                                        'musteri_party_no' => $item['musteri_party_no'],
                                        'model_id' => $item['model_id']
                                    ]);
                                    if ($modelBRIB->save()) {
                                        $saved = true;
                                    }
                                    //qabul qiluvchi uchun doc items
                                    $modelDocItems = new ToquvDocumentItems();
                                    $modelDocItems->setAttributes([
                                        'toquv_document_id' => $cloneId,
                                        'entity_id' => $item['entity_id'],
                                        'entity_type' => 2,
                                        'quantity' => $item['quantity'],
                                        'document_qty' => $item['quantity'],
                                        'price_sum' => 0.01,
                                        'price_usd' => 0.01,
                                        'is_own' => 1,
                                        'roll_count' => $item['roll_count'],
                                        'count' => 0,
                                        'bss_id' => $item['bss_id']
                                    ]);
                                    if ($modelDocItems->save()) {
                                        $saved = true;
                                    }
                                }
                            }
                        }
                        if ($saved) {
                            $model->setAttributes([
                                'toquv_doc_id' => $cloneId,
                                'status' => 3
                            ]);
                            $model->save();
                            $transaction->commit();
                        }
                    } catch (Exception $e) {
                        Yii::info('Not changed status to 3', 'save');
                    }
                    break;
                case 11:
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $items = $model->getBichuvDocItems()->asArray()->all();
                        $saved = false;
                        if (!empty($items)) {
                            $modelId = $model->id;
                            $deptTo = $model->to_department;
                            $musteri_id = $item['musteri_id'] ?? $musteriId;
                            foreach ($items as $item) {
                                $modelBIB = new BichuvRmItemBalance();
                                $inventory = $item['quantity'];
                                $roll_inventory = $item['roll_count'];
                                $modelBIB->setAttributes([
                                    'entity_id' => $item['entity_id'],
                                    'doc_type' => 1,
                                    'inventory' => $inventory,
                                    'count' => $item['quantity'],
                                    'roll_inventory' => $roll_inventory,
                                    'roll_count' => $item['roll_count'],
                                    'party_no' => $item['party_no'],
                                    'model_id' => $item['model_id'],
                                    'doc_id' => $modelId,
                                    'musteri_party_no' => $item['musteri_party_no'],
                                    'to_department' => $deptTo,
                                    'department_id' => $deptTo,
                                    'from_musteri' => $musteri_id,
                                ]);
                                if ($modelBIB->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    break;
                                }
                            }
                        } else {
                            $saved = true;
                        }
                        $model->updateCounters(['status' => 2]);
                        if ($saved) {
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();
                        }
                    } catch (Exception $e) {
                        Yii::info('Not changed status to 3', 'save');
                    }
                    break;
                case 13:
                    $transaction = Yii::$app->db->beginTransaction();
                    $saved = true;
                    $hasInventory = true;
                    try {
                        $model->status = BichuvDoc::STATUS_SAVED;
                        $saved = $model->save();
                        if ($saved) {
                            /** Akssessuar qoldig'i */
                            
                            $subQuery = BichuvItemBalance::find()
                                ->select(['max(id)'])
                                ->groupBy(['entity_id', 'entity_type', 'department_id'])
                                ->andWhere([
                                    'entity_type' => 1,
                                    'department_id' => HrDepartments::find()->select(['id'])->andWhere(['token' => 'ACS_WAREHOUSE'])->scalar()
                                ]);
                            $inventoryAcs = BichuvItemBalance::find()
                                ->select(['entity_id', 'inventory'])
                                ->andWhere(['id' => $subQuery])
                                ->andWhere(['>', 'inventory', 0])
                                ->asArray()
                                ->all();
                            $inventoryAcs = ArrayHelper::map($inventoryAcs, 'entity_id', 'inventory');

                            if ($model->bichuvDocItems) {

                                $clonedDoc = new BichuvDoc();
                                $clonedDoc->setAttributes([
                                    'document_type' => BichuvDoc::DOC_TYPE_ACCEPTED,
                                    'doc_number' => 'TT' . BichuvDoc::getLastId() . '/' . date('Y'),
                                    'reg_date' => date('d.m.Y'),
                                    'from_hr_department' => $model['from_hr_department'],
                                    'from_hr_employee' => $model['from_hr_employee'],
                                    'to_hr_department' => $model['to_hr_department'],
                                    'to_hr_employee' => $model['to_hr_employee'],
                                    'status' => BichuvDoc::STATUS_ACTIVE
                                ]);
                                $saved = $clonedDoc->save();

                                $defaultAcsAreaId = WmsDepartmentArea::getAreaIdByToken(WmsDepartmentArea::DEFAULT_ZONE_TOKEN_FOR_ACS_WAREHOUSE);
                                if ($saved) {
                                    foreach ($model->bichuvDocItems as $bichuvDocItems) {
                                        if (empty($inventoryAcs[$bichuvDocItems->entity_id]) && $bichuvDocItems->quantity > $inventoryAcs[$bichuvDocItems->entity_id]) {
                                            $saved = $hasInventory = false;
                                            break;
                                        }
                                        else {
                                            /** items balance dan rasxod */
                                            $minusItemBalance = new BichuvItemBalance();
                                            $minusItemBalance->setAttributes([
                                                'entity_id' => $bichuvDocItems['entity_id'],
                                                'entity_type' => $bichuvDocItems['entity_type'],
                                                'count' => -1.0 * (double)$bichuvDocItems['quantity'],
                                                'inventory' => (double)$inventoryAcs[$bichuvDocItems->entity_id] - (double)$bichuvDocItems['quantity'],
                                                'reg_date' => date('Y-m-d H:i:s'),
                                                'department_id' => $model['from_hr_department'],
                                                'from_department' => $model['from_hr_department'],
                                                'to_department' => $model['to_hr_department'],
                                                'dep_area' => $defaultAcsAreaId,
                                                'from_area' => $defaultAcsAreaId,
                                                'document_id' => $model['id'],
                                                'document_type' => $model['document_type'],
                                                'comment' => $model['add_info'],
                                            ]);
                                            $saved = $saved && $minusItemBalance->save();

                                            if (!$saved) {
                                                break;
                                            }
                                        }

                                        $clonedDocItems = new BichuvDocItems();
                                        $clonedDocItems->attributes = $bichuvDocItems->getAttributes(null, ['id', 'bichuv_doc_id', 'created_at', 'updated_at', 'created_by']);
                                        $clonedDocItems->bichuv_doc_id = $clonedDoc->id;
                                        $saved = $saved && $clonedDocItems->save();

                                        if (!$saved) {
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $saved = false;
                            }
                        }

                        if ($saved) {
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();
                        }
                    } catch (\Throwable $e) {
                        $transaction->rollBack();
                        Yii::error($e->getMessage(), 'exception');
                        $saved = false;
                    }

                    if ($saved) {
                        Yii::$app->session->setFlash('success',Yii::t('app','Successfully shipped'));
                    } else {
                        if ($hasInventory === false) {
                            Yii::$app->session->setFlash('warning', Yii::t('app','This product is not available in stock'));
                        } else {
                            Yii::$app->session->setFlash('error', Yii::t('app','An error occurred'));
                        }
                    }
                    break;
                case 15:

                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $saved = false;

                        $pechatId = HrDepartments::getDepartmentIdByToken(Constants::$TOKEN_PECHAT);
                        $naqshId = HrDepartments::getDepartmentIdByToken(Constants::$TOKEN_NAQSH);
                        $unitId = Unit::getIdByCode('DONA');
                        $tableId = "";
                        if($model->from_hr_department == $pechatId){
                            $tableId = MobileTables::getTableByToken(Constants::TOKEN_PECHAT_TRANSFER_SLICE)->id;
                        }elseif($model->from_hr_department == $naqshId){
                            $tableId = MobileTables::getTableByToken(Constants::TOKEN_NAQSH_TRANSFER_SLICE)->id;
                        }
                        $nastelNo = "";

                        if(!empty($tableId)){

                            $modelId = $model->id;
                            $cloneAccept = $model;
                            $cloneModel = new BichuvDoc();
                            $cloneAccept->document_type = 7;
                            $y = date('Y');
                            $cloneAccept->doc_number = "BK{$model->id}/{$y}";
                            $cloneModel->attributes = $cloneAccept->attributes;
                            $isClone = false;
                            if ($cloneModel->save()) {
                                $isClone = true;
                            }
                            $nastelNo = [];
                            if ($isClone) {
                                $items = $model->getBichuvSliceItems()->asArray()->all();

                                if (!empty($items)) {
                                    $cloneId = $cloneModel->id;
                                    foreach ($items as $item) {
                                        $recordData = [];
                                        $recordData['nastel_party'] = $item['nastel_party'];
                                        $recordData['size_id'] = $item['size_id'];
                                        $recordData['department_id'] = $model->from_hr_department;
                                        $remain = BichuvPrintAndPatternItemBalance::getLastRecord($recordData);
                                        if (!empty($remain)) {
                                            if (($remain['inventory'] - $item['quantity'] - $item['invalid_quantity']) < 0) {
                                                $lack_qty = $item['quantity'] - $remain['inventory'];
                                                Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                                    ['id' => $item['quantity']." kg", 'lack' => $lack_qty]));
                                                return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $model->type]);
                                            }
                                        }

                                    }
                                    foreach ($items as $item) {
                                        $itemNastel = $item['nastel_party'];
                                        if (!array_search($itemNastel,(array)$nastelNo))
                                            $nastelNo[$itemNastel] = $itemNastel;
                                        if($item['invalid_quantity'] > 0){
                                            $diff = new MobileDocDiffItems([
                                                'doc_items_id' => $item['id'],
                                                'table_name' => BichuvSliceItems::getTableSchema()->name,
                                                'unit_id' => $unitId,
                                                'diff_qty' => $item['invalid_quantity'],
                                                'department_id' => $model->from_hr_department,
                                                'add_info' => $item['add_info']
                                            ]);
                                            if($diff->save()){
                                                $saved = true;
                                            }else{
                                                $saved = false;
                                                break;
                                            }

                                        }
                                        $recordData = [];
                                        $recordData['nastel_party'] = $item['nastel_party'];
                                        $recordData['size_id'] = $item['size_id'];
                                        $recordData['department_id'] = $model->from_hr_department;
                                        $deptId = $model->from_hr_department;
                                        $modelAcceptItems = new BichuvPrintAndPatternItemBalance();
                                        $checkExists = BichuvPrintAndPatternItemBalance::getLastRecord($recordData);
                                        $inventory = $item['quantity'];
                                        if (!empty($checkExists)) {
                                            $inventory = $checkExists['inventory'] - $inventory - $item['invalid_quantity'];
                                        }
                                        $modelAcceptItems->setAttributes([
                                            'entity_id' => $item['id'],
                                            'entity_type' => 2,
                                            'party_no' => $item['nastel_party'],
                                            'size_id' => $item['size_id'],
                                            'model_id' => $item['model_id'],
                                            'count' => -1 * $item['quantity'],
                                            'invalid_count' => -1 * $item['invalid_quantity'],
                                            'inventory' => $inventory,
                                            'doc_id' => $modelId,
                                            'doc_type' => 2,
                                            'hr_department_id' => $deptId,
                                            'to_hr_department' => $model->to_hr_department,
                                            'from_hr_department' => $deptId
                                        ]);

                                        if($modelAcceptItems->save()){
                                            $saved = true;
                                        }else{
                                            $saved = false;
                                            break;
                                        }
                                        //qabul qiluvchi uchun doc item
                                        $modelDocItems = new BichuvSliceItems();
                                        $modelDocItems->setAttributes([
                                            'bichuv_doc_id' => $cloneId,
                                            'entity_id' => $item['entity_id'],
                                            'entity_type' => 2,
                                            'quantity' => $item['quantity'],
                                            'bichuv_given_roll_id' => $item['bichuv_given_roll_id'],
                                            'nastel_party' => $item['nastel_party'],
                                            'size_id' => $item['size_id'],
                                            'work_weight' => $item['work_weight'],
                                            'model_var_print_id' => $item['model_var_print_id'],
                                            'model_id' => $item['model_id'],
                                            'model_var_stone_id' => $item['model_var_stone_id'],
                                            'add_info' => $item['add_info'],
                                            'invalid_quantity' => $item['invalid_quantity']
                                        ]);

                                        if ($modelDocItems->save()) {
                                            $saved = true;
                                        } else {
                                            $saved = false;
                                            break;
                                        }
                                    }
                                }

                            }
                        }else{
                            $saved = false;
                            Yii::$app->session->setFlash('error', Yii::t('app','Table not found'));
                        }
                        if ($saved && !empty($nastelNo)) {

                            foreach ($nastelNo as $nastel){
                                $cardItem = MobileProcessProduction::getCardItemOrder($nastel);
                                if ($cardItem) {
                                    $params = [
                                        'nastel_no' => $nastel,
                                        'started_date' => date("d.m.Y H:i:s"),
                                        'ended_date' => date("d.m.Y H:i:s"),
                                        'status' => MobileProcessProduction::STATUS_ENDED,
                                        'doc_id' => $model->id,
                                        'table_name' => BichuvDoc::getTableSchema()->name,
                                        'mobile_tables_id' => $tableId,
                                        'model_orders_items_id' => $cardItem['model_orders_items_id'],
                                        'parent_id' => $cardItem['parent_id'],
                                        'bichuv_detail_type_id' => $cardItem['bichuv_detail_type_id'],
                                        'base_detail_list_id' => $cardItem['base_detail_list_id']
                                    ];
                                    if (MobileProcessProduction::saveMobileProcess($params)){
                                        $saved = true;
                                    }else{
                                        $saved = false;
                                        break;
                                    }
                                }
                            }

                            if ($saved){
                                Yii::$app->session->setFlash('success',Yii::t('app','Saqlandi'));
                                $model->updateCounters(['status' => 2]);
                                $transaction->commit();
                            }else{
                                Yii::$app->session->setFlash('error',Yii::t('app','Saqlashda xatolik!'));
                                $transaction->rollBack();
                            }
                        }else{
                            $transaction->rollBack();
                        }
                    } catch (Exception $e) {
                        Yii::info('Not all saved ' . $e, 'save');
                    }
                    break;
                case 16:
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $acsItems = $model->bichuvDocItems;
                        $saved = false;

                        $model->status = BichuvDoc::STATUS_SAVED;
                        $saved = $model->save();
                        if ($saved && is_iterable($acsItems)) {
                            foreach ($acsItems as $acsItem) {
                                $acceptToItemBalance = new BichuvItemBalance();
                                $acceptToItemBalance->setAttributes([
                                    'document_id' => $model->id,
                                    'document_type' => $model->document_type,
                                    'entity_id' => $acsItem['entity_id'],
                                    'entity_type' => $acsItem['entity_type'],
                                    'count' => $acsItem['quantity'],
                                    'reg_date' => date('Y-m-d H:i:s'),
                                    'department_id' => $model['to_department'],
                                    'to_department' => $model['to_department'],
                                    'from_department' => $model['from_department'],
                                    'comment' => $model['add_info'],
                                ]);

                                $saved = BichuvItemBalance::increaseItem($acceptToItemBalance);
                                if (!$saved) {
                                    Yii::error($acceptToItemBalance->getErrors(), 'save');
                                    break;
                                }
                            }
                        }

                        if ($saved) {
                            $transaction->commit();
                            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successfully accepted'));
                        } else {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', Yii::t('app', "Qabul qilishda xatolik"));
                        }
                    } catch (\Throwable $e) {
                        Yii::info('Not all saved ' . $e, 'save');
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Error: exception!'));
                    }
                    break;
            }
        }
        return $this->redirect(['view', 'id' => $id, 'slug' => $this->slug, 't' => $model->type]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $t = Yii::$app->request->get('t', 1);
        if($model->status>$model::STATUS_ACTIVE){
            return $this->redirect(["view", 'id' => $id, 'slug' => $this->slug, 't' => $t]);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($model->bichuvDocItems)) {
                foreach ($model->bichuvDocItems as $item) {
                    $subItems = $item->bichuvSubDocItems;
                    if (!empty($subItems)) {
                        foreach ($subItems as $subItem) {
                            $subItem->delete();
                        }
                    }
                    $rollRecords = $item->bichuvRollRecords;
                    if (!empty($rollRecords)) {
                        foreach ($rollRecords as $rollItem) {
                            $rollItem->delete();
                        }
                    }
                    $item->delete();
                }
            }
            if (!empty($model->bichuvSaldos)) {
                foreach ($model->bichuvSaldos as $item) {
                    $item->delete();
                }
            }
            if (!empty($model->bichuvSliceItems)) {
                foreach ($model->bichuvSliceItems as $item) {
                    $item->delete();
                }
            }
            if (!empty($model->bichuvNastelRag)) {
                foreach ($model->bichuvNastelRag as $item) {
                    $item->delete();
                }
            }
            if (!empty($model->bichuvBeka)) {
                foreach ($model->bichuvBeka as $item) {
                    $item->delete();
                }
            }
            if (!empty($model->bichuvNastelDetails)) {
                foreach ($model->bichuvNastelDetails as $item) {
                    $item->delete();
                }
            }
            $model->delete();
            $transaction->commit();
        } catch (Exception $e) {
            Yii::info('Not all deleted ' . $e->getMessage(), 'delete');
        }
        return $this->redirect(['index', 'slug' => $this->slug]);
    }

    /**
     * @param $id
     * @param null $all
     * @return array
     */
    public function actionGetDepartmentUser($id,$all=null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        if (!empty($id)) {
            if($all) {
                $employee = HrDepartmentResponsiblePerson::find()
                    ->where([
                        'hr_department_id' => $id,
                        'status' => 1
                        ])
                    ->one();
                if (!empty($employee)) {
                    $response['status'] = 1;
                    $response['list']['id'] = $employee->hr_employee_id;
                    $response['list']['name'] = $employee->hrEmployee->fish;
                }
            }else{
                $userDept = ToquvUserDepartment::findOne(['department_id' => $id]);
                if ($userDept !== null) {
                    if (!empty($userDept->user) && !empty($userDept->user->user_fio)) {
                        $response['status'] = 1;
                        $response['id'] = $userDept->user_id;
                        $response['name'] = $userDept->user->user_fio;
                    }
                }
            }
        }

        return $response;
    }

    /**
     * @param $id
     * @param $type
     * @param $depId
     * @return array
     * @throws Exception
     */
    public function actionGetRemainEntity($id, $type, $depId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;


        $searchModel = new BichuvDoc();
        $params = [
            'id' => $id,
            'type' => $type,
            'depId' => $depId
        ];

        $response['params'] = $params;

        $res = $searchModel->getRemain($params);

        if (!empty($res)) {
            $response['status'] = 1;
            $response['remain'] = $res;
        }

        return $response;
    }


    /**
     * @param $q
     * @param $dept
     * @param $type
     * @param $index
     * @return array
     * @throws Exception
     */
    public function actionAjaxRequest($q, $dept, $type, $index)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['results'] = [];
        $response['p'] = ['index' => $index];
        if (!empty($q)) {
            $params = [];
            $params['entity_type'] = 1;
            $params['department_id'] = $dept;
            $params['query'] = $q;
            $searchModel = new BichuvDoc();
            $res = $searchModel->searchEntities($params);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = "{$item['sku']} - {$item['acsname']} - {$item['prname']}";
                    array_push($response['results'], [
                        'id' => $item['entity_id'],
                        'text' => $name,
                        'summa' => $item['summa'],
                        'tib_id' => $item['id'],
                        'index' => $index
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

    /**
     * @param null $id
     * @return array
     * @throws Exception
     */
    public function actionGetRmInfo()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $t = Yii::$app->request->get('t', 1);
        $result = [];
        $result['status'] = 0;
        $result['message'] = Yii::t('app', 'Bunday shtrixkoddagi mato topilmadi yoki avval kiritilgan :(');
        $partyId = '';

        if (!empty($data) && !empty($data['barcode'])) {
            if ($data['type'] == 1) {
                if (!empty($data['party'])) {
                    $partyId = join(',', $data['party']);
                }
                $existsIdsConditions = "";
                $conditionForRemain = "";
                if ($partyId) {
                    $existsIdsConditions = " AND bss.id NOT IN ({$partyId}) ";
                }
                $existsRolls = '';
                if (!empty($data['saved'])) {
                    $diff = null;
                    $savedIds = explode(',', $data['saved']);
                    if (!empty($data['party'])) {
                        $diff = array_diff($savedIds, $data['party']);
                        $diff = join(',', $diff);
                        if (!empty($diff)) {
                            $existsRolls = "AND p.id NOT IN (select bsdi.paket_id from bichuv_sub_doc_items bsdi where bsdi.bss_id NOT IN ({$diff}))";
                        } else {
                            $existsRolls = 'AND p.id NOT IN (select bsdi.paket_id from bichuv_sub_doc_items bsdi) ';
                        }
                    }
                } else {
                    $existsRolls = 'AND p.id NOT IN (select bsdi.paket_id from bichuv_sub_doc_items bsdi where bsdi.paket_id IS NOT NULL) ';
                }

                $packetId = trim($data['barcode']);
                if ($t == 3) {
                    $res = BichuvDoc::getRMInfoAjaxForRemain($packetId);
                } else {
                    $res = BichuvDoc::getRMInfoAjax($packetId, $existsIdsConditions, $existsRolls);
                }

                if (!empty($res)) {
                    $result['status'] = 1;
                    $result['message'] = 'OK';
                    $result['response'] = [];
                    foreach ($res as $key => $item) {
                        $result['response'][$key]['qty'] = $item['rulon_kg'];
                        $result['response'][$key]['count'] = $item['count_rulon'];
                        if (!empty($item['pus_fine']) && !empty($item['ne']) && !empty($item['mato_en']) && !empty($item['gramaj'])) {
                            $result['response'][$key]['is_accessory'] = 1;
                            $result['response'][$key]['name'] = "{$item['mato']}-{$item['ne']}-{$item['ip']}|{$item['pus_fine']}-({$item['ctone']} {$item['color_id']} {$item['pantone']})";
                        } else {
                            $result['response'][$key]['is_accessory'] = 2;
                            $result['response'][$key]['name'] = "{$item['mato']}-{$item['ip']}-({$item['ctone']} {$item['color_id']} {$item['pantone']})";
                        }
                        $result['response'][$key]['id'] = $item['id'];
                        $result['response'][$key]['party'] = $item['partiya_no'];
                        $result['response'][$key]['musteri_party'] = $item['mijoz_part'];
                        $result['response'][$key]['model_id'] = $item['model_id'];

                        $result['response'][$key]['en'] = $item['mato_en'];
                        $result['response'][$key]['gramaj'] = $item['gramaj'];
                        $result['response'][$key]['ne_id'] = $item['ne_id'];
                        $result['response'][$key]['pus_fine_id'] = $item['pf_id'];
                        $result['response'][$key]['thread_id'] = $item['thr_id'];
                        $result['response'][$key]['c_id'] = $item['c_id'];
                        $result['response'][$key]['rm_id'] = $item['rm_id'];
                    }
                } else {
                    $result['status'] = 2;
                    $result['message'] = Yii::t('app', "Bunday mato ro'yxatga avval qo'shilgan yoki mavjud emas");
                }
            } elseif ($data['type'] == 2) {
                $partyId = $data['barcode'];
                $rollIds = $data['party'];
                $musteriId = $data['musteri_id'];
                $adjustment = !empty($data['adjustment'])?$data['adjustment']:false;
                $items = BichuvDoc::loadRollsByParty($partyId, $rollIds, $musteriId, $adjustment);
                if (!empty($items)) {
                    $result['status'] = 1;
                    $result['message'] = 'OK';
                    $result['items'] = $items;
                }
            }
        }
        return $result;
    }
    public function actionGetNastelInfoAll()
    {

        $tableName = BichuvGivenRollItems::getTableSchema()->name;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        $sql1 = "select  bgr.id,
                    bgri.id as bgri_id,
                    bgri.roll_count as rulon_count,
                    CONCAT( bdt.name, '( ', bdl.name,')') as detail_name,
                    bgr.customer_id as musteri_id,
                    ml.name as model,
                    ml.article,
                    ml.id as modelId,
                    rmt.name as mato,
                    tn.name as ne,
                    tt.name as thread,
                    tpf.name as pus_fine,
                    c.color_id,
                    c.pantone,
                    ct.name as ctone,
                    mpp.nastel_no,
                    bgri.entity_id,
                    bgri.party_no,
                    bgri.musteri_party_no,
                    (
                        select SUM(bamfp.quantity)
                        from bichuv_accepted_mato_from_production bamfp 
                        where bamfp.bichuv_given_roll_id = bgr.id
                    ) as accepted,
                    (
                        select SUM(bgri2.quantity)
                        from bichuv_given_roll_items bgri2 
                        where bgri2.bichuv_given_roll_id= bgr.id
                    )  as rulon_kg
            from bichuv_given_rolls bgr
                 LEFT JOIN bichuv_given_roll_items bgri on bgr.id = bgri.bichuv_given_roll_id
                 LEFT JOIN mobile_process_production mpp ON bgri.id = mpp.doc_items_id
                 LEFT JOIN mobile_process_production mpp2 ON mpp.parent_id = mpp2.id
                 LEFT JOIN bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                 LEFT JOIN base_detail_lists bdl ON mpp.base_detail_list_id = bdl.id
                 LEFT JOIN model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                 LEFT JOIN models_list ml on mrp.models_list_id = ml.id
                 LEFT JOIN models_variations mv on mv.id = mrp.model_variation_id
                 LEFT JOIN wms_mato_info wmi on bgri.entity_id = wmi.id
                 LEFT JOIN color_pantone cp on mv.color_pantone_id = cp.id
                 LEFT JOIN toquv_raw_materials trm on wmi.toquv_raw_materials_id = trm.id
                 LEFT JOIN raw_material_type rmt on wmi.raw_material_type_id = rmt.id
                 LEFT JOIN toquv_ne tn on wmi.ne_id = tn.id
                 LEFT JOIN toquv_thread tt on wmi.thread_id = tt.id
                 LEFT JOIN toquv_pus_fine tpf on wmi.pus_fine_id = tpf.id
                 LEFT JOIN color c on wmi.wms_color_id = c.id
                 LEFT JOIN color_tone ct on c.color_tone = ct.id
            WHERE bgr.nastel_party = :party 
                AND bgri.entity_type = 1
                AND mpp.table_name = '{$tableName}'
            ";
        $nastel_party = MobileProcessProduction::findOne(['nastel_no' => $data['barcode']])->parent->nastel_no;

        $sql2 = "select s.id, s.name, sc.id sc_id from size_col_rel_size scrs
                left join size s on scrs.size_id = s.id
                left join size_collections sc on scrs.sc_id = sc.id
                left join bichuv_given_rolls bgr on bgr.size_collection_id = sc.id
                where bgr.nastel_party = :party ORDER BY s.order ASC";

        $res1 = Yii::$app->db->createCommand($sql1)->bindValue('party', $data['barcode'])->queryAll();
        $res2 = Yii::$app->db->createCommand($sql2)->bindValue('party', $data['barcode'])->queryAll();
        $result = [];

        $result['status'] = 0;
        $result['message'] = Yii::t('app', 'Bunday nastel raqamdagi partiya topilmadi yoki avval kiritilgan :(');
        if (!empty($res1) && !empty($res2)) {
            $result['status'] = 1;
            $result['sizeCollection'] = $res2;
            $result['items'] = $res1;
            $result['message'] = "Nastelda qolgan qoldiq mavjud emas!";
        }
        return $result;
    }

    public function actionGetNastelInfo()
    {

        $tableName = BichuvGivenRollItems::getTableSchema()->name;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        $sql1 = "select  bgr.id,
                    bgri.id as bgri_id,
                    bgri.roll_count as rulon_count,
                    bgri.quantity   as rulon_kg,
                    bdt.name as detail_name,
                    bgr.customer_id as musteri_id,
                    CONCAT(ml.name,':',ml.article) as model,
                    ml.id as modelId,
                    rmt.name as mato,
                    tn.name as ne,
                    tt.name as thread,
                    tpf.name as pus_fine,
                    c.color_id,
                    c.pantone,
                    ct.name as ctone,
                    bgr.nastel_party,
                    bgri.entity_id,
                    bgri.party_no,
                    bgri.musteri_party_no,
                    (
                        select SUM(bamfp.quantity)
                        from bichuv_accepted_mato_from_production bamfp 
                        where bamfp.bichuv_given_roll_id = bgr.id
                        AND bamfp.nastel_no = :party
                    ) as accepted
            from bichuv_given_rolls bgr
                     left join bichuv_given_roll_items bgri on bgr.id = bgri.bichuv_given_roll_id
                     left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id
                     LEFT JOIN mobile_process_production mpp ON bgri.id = mpp.doc_items_id
                     left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                     left join models_list ml on mrp.models_list_id = ml.id
                     left join models_variations mv on mv.id = mrp.model_variation_id
                     left join wms_mato_info wmi on bgri.entity_id = wmi.id
                     left join color_pantone cp on mv.color_pantone_id = cp.id
                     left join toquv_raw_materials trm on wmi.toquv_raw_materials_id = trm.id
                     left join raw_material_type rmt on wmi.raw_material_type_id = rmt.id
                     left join toquv_ne tn on wmi.ne_id = tn.id
                     left join toquv_thread tt on wmi.thread_id = tt.id
                     left join toquv_pus_fine tpf on wmi.pus_fine_id = tpf.id
                     left join color c on wmi.wms_color_id = c.id
                     left join color_tone ct on c.color_tone = ct.id
            WHERE mpp.nastel_no = :party 
                AND bgri.entity_type = 1
                AND mpp.table_name = '{$tableName}'
            GROUP BY bgri.bichuv_detail_type_id
            ";

        $nastel_party = MobileProcessProduction::findOne(['nastel_no' => $data['barcode']])->parent->nastel_no;

        $sql2 = "select s.id, s.name, sc.id sc_id from size_col_rel_size scrs
                left join size s on scrs.size_id = s.id
                left join size_collections sc on scrs.sc_id = sc.id
                left join bichuv_given_rolls bgr on bgr.size_collection_id = sc.id
                where bgr.nastel_party = '{$nastel_party}' ORDER BY s.order ASC";

        $res1 = Yii::$app->db->createCommand($sql1)->bindValue('party', $data['barcode'])->queryAll();
        $res2 = Yii::$app->db->createCommand($sql2)->bindValue('party', $data['barcode'])->queryAll();
        $result = [];

        $result['status'] = 0;
        $result['message'] = Yii::t('app', 'Bunday nastel raqamdagi partiya topilmadi yoki avval kiritilgan :(');
        if (!empty($res1) && !empty($res2)) {
            $result['status'] = 1;
            $result['sizeCollection'] = $res2;
            $result['items'] = $res1;
            $result['message'] = "Nastelda qolgan qoldiq mavjud emas!";
        }
        return $result;
    }

    public function actionGetNastelMoving()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $slug = $this->slug;
        $result['status'] = 0;
        $result['message'] = Yii::t('app', 'Bunday raqamdagi partiya topilmadi yoki avval kiritilgan :(');
        if (!empty($data) && !empty($data['nastel']) && !empty($data['department'])) {
            $type = "qabul_kesim";
            $action = Yii::$app->request->get('action', null);
            if (!empty($data['type'])){
                $type = $data['type'];
            }
            if(!empty($action) && $action == Constants::TOKEN_TAYYORLOV_MOVING_SLICE){
                $slug = BichuvDoc::DOC_TYPE_MOVING_SLICE_TAY_LABEL;
            }
            $sizeCondition = "";

            if ($type == 'qabul_kesim') {
                $sql = "select bsib.size_id,
                           s.name,
                           bsib.party_no,
                           bsib.inventory,
                           bsib.doc_id,
                           p.id as model_id,
                           p.name as model,
                           bd.work_weight,
                           mo.musteri_id,
                           ml.article,
                           mv.id as model_var_id,
                           mv.name as model_var,
                           cp.code
                    from bichuv_slice_item_balance bsib
                             left join mobile_process_production mpp on bsib.party_no = mpp.nastel_no
                             left join mobile_process_production mpp2 on mpp.parent_id = mpp2.id
                             left join bichuv_given_rolls bgr on mpp2.nastel_no = bgr.nastel_party
                             left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                             left join models_list ml on mrp.models_list_id = ml.id
                             left join models_variations mv on mrp.model_variation_id = mv.id
                             left join color_pantone cp on mv.color_pantone_id = cp.id
                             left join bichuv_doc bd on bsib.doc_id = bd.id
                             left join model_orders mo on mrp.order_id = mo.id
                             left join size s on bsib.size_id = s.id
                             left join product p on bsib.model_id = p.id
                    WHERE bsib.id IN (select MAX(bsib2.id) from bichuv_slice_item_balance bsib2
                                      where bsib2.party_no = '%s' AND bsib2.hr_department_id = %d %s  GROUP BY bsib2.size_id)
                      AND bsib.inventory > 0
                      AND mpp.parent_id IS NOT NULL
                    ORDER BY bsib.size_id;";
            }
            else {
                $bichuvGivenRollItems = BichuvGivenRollItems::getTableSchema()->name;
                switch ($slug){
                    case BichuvDoc::DOC_TYPE_MOVING_SLICE_TAY_LABEL:
                        if (!empty($data['sizes'])) {
                            $sizeIds = join(', ', $data['sizes']);
                            $sizeCondition .= " AND bsib2.size_id NOT IN ({$sizeIds})";
                        }
                        $sql = "select bsib.size_id,
                                   s.name,
                                   bsib.party_no,
                                   bsib.inventory,
                                   bsib.doc_id,
                                   p.id as model_id,
                                   p.name as model,
                                   bd.work_weight,
                                   mo.musteri_id,
                                   ml.article,
                                   mv.id as model_var_id,
                                   mv.name as model_var,
                                   cp.code,
                                   IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) AS color_code,
                                   IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) AS color_name
                            from bichuv_slice_item_balance bsib
                                     left join bichuv_given_rolls bgr on bsib.party_no = bgr.nastel_party
                                     left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                                     left join models_list ml on mrp.models_list_id = ml.id
                                     left join models_variations mv on mrp.model_variation_id = mv.id
                                     left join wms_color wc on mv.wms_color_id = wc.id 
                                     left join color_pantone cp on wc.color_pantone_id = cp.id
                                     left join bichuv_doc bd on bsib.doc_id = bd.id
                                     left join model_orders mo on mrp.order_id = mo.id
                                     left join size s on bsib.size_id = s.id
                                     left join product p on bsib.model_id = p.id
                            WHERE bsib.id IN (select MAX(bsib2.id) from bichuv_slice_item_balance bsib2
                                              where bsib2.party_no = '%s' AND bsib2.hr_department_id = %d %s  GROUP BY bsib2.size_id)
                              AND bsib.inventory > 0
                              AND bgr.nastel_party = %d
                            ORDER BY bsib.size_id;";
                        break;
                    case BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL:
                            $sql = "select bsib.size_id,
                                   s.name,
                                   bsib.party_no,
                                   bdt.id detail_id,
                                   bdt.name detail_name,
                                   bsib.inventory,
                                   bsib.doc_id,
                                   bgri.id as bgri_id,
                                   p.id as model_id,
                                   p.name as model,
                                   bsi.work_weight,
                                   mo.musteri_id,
                                   ml.article,
                                   mv.id as model_var_id,
                                   mv.name as model_var,
                                   IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) AS color_code,
                                   IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) AS color_name, 
                                   cp.code
                            from bichuv_slice_item_balance bsib
                                     left join bichuv_slice_items bsi on bsib.party_no = bsi.nastel_party
                                     left join mobile_process_production mpp on mpp.nastel_no = bsib.party_no
                                     left join mobile_process_production mpp2 on mpp.parent_id = mpp2.id
                                     left join bichuv_detail_types bdt on mpp.bichuv_detail_type_id = bdt.id
                                     left join bichuv_given_rolls bgr on mpp2.nastel_no = bgr.nastel_party
                                     left join bichuv_given_roll_items bgri on bgri.bichuv_given_roll_id = bgr.id
                                     left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                                     left join models_list ml on mrp.models_list_id = ml.id
                                     left join models_variations mv on mrp.model_variation_id = mv.id
                                     left join wms_color wc ON mv.wms_color_id = wc.id  
                                     left join color_pantone cp on wc.color_pantone_id = cp.id left join bichuv_doc bd on bsib.doc_id = bd.id
                                     left join model_orders mo on mrp.order_id = mo.id
                                     left join size s on bsib.size_id = s.id
                                     left join product p on bsib.model_id = p.id
                            WHERE bsib.id IN (select MAX(bsib2.id) from bichuv_slice_item_balance bsib2
                                              where bsib2.party_no = '%s' AND bsib2.hr_department_id = %d %s  GROUP BY bsib2.size_id)
                              AND bsib.inventory > 0
                              AND mpp.table_name = '{$bichuvGivenRollItems}'
                             GROUP BY bsib.size_id 
                            ORDER BY bsib.size_id;";
                            break;
                }
            }
            $sql = sprintf($sql, $data['nastel'], $data['department'], $sizeCondition, $data['nastel']);
            $out = [];
            $modelData = [];
            $outModel = [];
            try {
                $results = Yii::$app->db->createCommand($sql)->queryAll();
                foreach ($results as $key => $item) {
                    if (!array_key_exists($item['model_var_id'], $modelData)) {
                        $modelData[$item['model_var_id']] = 0;
                        $outModel['model'] = $item['article'];
                        if (empty($outModel['model_var'])) {
                            $outModel['model_var'] = $item['code'] . " (" . $item['model_var'] . ")";
                        } else {
                            $outModel['model_var'] .= ", " . $item['code'] . " (" . $item['model_var'] . ")";
                        }
                    }
                    $out[$item['size_id']] = $item;
                }
                if (!empty($out)) {
                    $result['status'] = 1;
                    $result['items'] = $out;
                    $result['modelData'] = $outModel;
                    $result['message'] = "OK";
                }
            } catch (Exception $e) {

            }

        }
        return $result;
    }

    public function actionGetNastelTransfer()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $result['status'] = 0;
        $result['message'] = Yii::t('app', 'Bunday raqamdagi partiya topilmadi yoki avval kiritilgan :(');

        if (!empty($data) && !empty($data['nastel']) && !empty($data['department'])) {

            $sizeCondition = "";

            if (!empty($data['sizes'])) {
                $sizeIds = join(', ', $data['sizes']);
                $sizeCondition .= " AND bpapib2.size_id NOT IN ({$sizeIds})";
            }
            $sql = "select bpapib.size_id,
                           s.name,
                           bpapib.party_no,
                           bpapib.inventory,
                           bpapib.doc_id,
                           bd.work_weight,
                           mo.musteri_id,
                           ml.article,
                           mv.id as model_var_id,
                           mv.name as model_var,
                           IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) AS color_code,
                           IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) AS color_name
                    from bichuv_print_and_pattern_item_balance bpapib
                             left join mobile_process_production mpp on bpapib.party_no = mpp.nastel_no
                             left join mobile_process_production mpp2 on mpp.parent_id = mpp2.id
                             left join bichuv_given_rolls bgr on mpp2.nastel_no = bgr.nastel_party
                             left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                             left join models_list ml on mrp.models_list_id = ml.id
                             left join models_variations mv on mrp.model_variation_id = mv.id
                             left join wms_color wc ON mv.wms_color_id = wc.id
                             left join color_pantone cp on wc.color_pantone_id = cp.id
                             left join bichuv_doc bd on bpapib.doc_id = bd.id
                             left join model_orders mo on mrp.order_id = mo.id
                             left join size s on bpapib.size_id = s.id
                    WHERE bpapib.id IN (select MAX(bpapib2.id) from bichuv_print_and_pattern_item_balance bpapib2
                                      where bpapib2.party_no = '%s' AND bpapib2.hr_department_id = %d %s  GROUP BY bpapib2.size_id)
                      AND bpapib.inventory > 0
                      AND mpp.parent_id IS NOT NULL
                    ORDER BY bpapib.size_id;";
            $sql = sprintf($sql, $data['nastel'], $data['department'], $sizeCondition);
            $out = [];
            $modelData = [];
            $outModel = [];
            $results = Yii::$app->db->createCommand($sql)->queryAll();

            foreach ($results as $key => $item) {
                if (!array_key_exists($item['model_var_id'], $modelData)) {
                    $modelData[$item['model_var_id']] = 0;
                    $outModel['model'] = $item['article'];
                    if (empty($outModel['model_var'])) {
                        $outModel['model_var'] = $item['color_code'] . " (" . $item['color_name'] . ")";
                    } else {
                        $outModel['model_var'] .= ", " . $item['color_code'] . " (" . $item['color_name'] . ")";
                    }
                }
                $out[$item['size_id']] = $item;
            }
            if (!empty($out)) {
                $result['status'] = 1;
                $result['items'] = $out;
                $result['modelData'] = $outModel;
                $result['message'] = "OK";
            }
        }
        return $result;
    }
    /**
     * @param $id
     * @param $t
     * @return string
     * @throws Exception
     */
    public function actionLoadRolls($id, $t)
    {
        $items = BichuvDoc::getLoadRolls($id, $t);
        return $this->renderAjax('load-rolls', ['items' => $items, 't' => $t]);
    }

    /**
     * @param $musteriParty
     * @param $musteriId
     * @return array
     */
    public function actionCheckMusteriParty($musteriParty, $musteriId){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = false;
        $response['message'] = "OK";
        $result = BichuvDocItems::find()
            ->leftJoin('bichuv_doc','bichuv_doc.id = bichuv_doc_items.bichuv_doc_id')
            ->where([
                'bichuv_doc.musteri_id' => $musteriId,
                'bichuv_doc_items.musteri_party_no' => $musteriParty,
            ])
            ->asArray()
            ->exists();
        if($result){
            $response['status'] = true;
            $response['message'] = Yii::t('app','{mijoz_party} bunday mijoz partiya raqami avval kiritilgan!',['mijoz_party' => $musteriParty]);
        }
        return $response;
    }

    public function actionAddKonveyer($id,$dept)
    {
        $model = new TikuvKonveyerBichuvGivenRolls([
            'bichuv_given_rolls_id' => $id
        ]);
        $tikuv_konveyer = TikuvKonveyer::find()->where(['dept_id'=>$dept])->asArray()->all();
        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post())){
                $last_index = TikuvKonveyerBichuvGivenRolls::find()->where(['tikuv_konveyer_id'=>$model->tikuv_konveyer_id])->max('indeks');
                $model->indeks = $last_index+1;
                $response = [];
                $response['status'] = 0;
                $response['message'] = Yii::t('app', "Hatolik yuz berdi");
                if($model->save()){
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response['status'] = 1;
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                }
                return $response;
            }
        }
        return $this->renderAjax('add-konveyer',[
            'model' => $model,
            'tikuv_konveyer' => ArrayHelper::map($tikuv_konveyer,'id','name'),
        ]);
    }


    public function actionAcceptAndFinish($id)
    {
        $transaction=Yii::$app->db->beginTransaction();
        $saved=false;
        try {
            $model=self::findModel($id);
            $model->reg_date=date('Y-m-d H:i:s');
            $bichuvSliceItems=$model->bichuvSliceItems;
            $pechatId = HrDepartments::getDepartmentIdByToken(Constants::$TOKEN_PECHAT);
            $naqshId = HrDepartments::getDepartmentIdByToken(Constants::$TOKEN_NAQSH);
            $unitId = Unit::getIdByCode('DONA');
            $tableId = "";
            $tableName = "";

            if($model->to_hr_department == $pechatId){
                $tableId = MobileTables::getTableByToken(Constants::TOKEN_PECHAT_ACCEPTED_SLICE)->id;
                $tableName = Constants::TOKEN_PECHAT_ACCEPTED_SLICE;
            }elseif($model->to_hr_department == $naqshId){
                $tableId = MobileTables::getTableByToken(Constants::TOKEN_NAQSH_ACCEPTED_SLICE)->id;
                $tableName = Constants::TOKEN_NAQSH_ACCEPTED_SLICE;
            }

            $nastelNo = [];
            if(!empty($tableId)){
                if (!empty($bichuvSliceItems)) {
                    foreach ($bichuvSliceItems as $bichuvSliceItem) {
                        $itemNastel = $bichuvSliceItem['nastel_party'];
                        if (!array_search($itemNastel,(array)$nastelNo))
                            $nastelNo[$itemNastel] = $itemNastel;
                        $diffQty =$bichuvSliceItem['fact_quantity'] -  $bichuvSliceItem['quantity'];
                        if($diffQty != 0){
                            $diff = new MobileDocDiffItems([
                                'doc_items_id' => $bichuvSliceItem['id'],
                                'table_name' => BichuvSliceItems::getTableSchema()->name,
                                'diff_qty' => $diffQty,
                                'unit_id' => $unitId,
                                'department_id' => $model->to_hr_department,
                                'add_info' => $bichuvSliceItem['add_info'],
                            ]);
                            if($diff->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                        $dataRecord=[];
                        $dataRecord['size_id']=$bichuvSliceItems['size_id'];
                        $dataRecord['nastel_party']=$bichuvSliceItems['nastel_party'];
                        $dataRecord['department_id']=$model['to_hr_department'];
                        $newBichuvPrintAndPattern=new BichuvPrintAndPatternItemBalance();
                        $lastRecordBPAPItemBalance=$newBichuvPrintAndPattern::getLastRecord($dataRecord);
                        $inventory=$bichuvSliceItem['fact_quantity'];
                        if ($bichuvSliceItem) {
                            $inventory=$lastRecordBPAPItemBalance['inventory'] + $inventory;
                        }
                        $newBichuvPrintAndPattern->setAttributes([
                            'entity_id'=>$bichuvSliceItem['id'],
                            'entity_type'=>2,
                            'party_no'=>$bichuvSliceItem['nastel_party'],
                            'size_id'=>$bichuvSliceItem['size_id'],
                            'count'=>$bichuvSliceItem['fact_quantity'],
                            'work_weight'=>$bichuvSliceItem['work_weight'],
                            'inventory'=>$inventory,
                            'doc_id'=>$id,
                            'doc_type'=>1,
                            'model_id'=>$bichuvSliceItem['model_id'],
                            'hr_department_id'=>$model['to_hr_department'],
                            'to_hr_department'=>$model['to_hr_department'],
                            'from_hr_department'=>$model['from_hr_department']
                        ]);
                        if ($newBichuvPrintAndPattern->save()) {
                            $saved=true;
                        } else {
                            $saved=false;
                            break;
                        }
                    }
                    if ($model->save() && $saved) {
                        if (!empty($nastelNo)){
                            foreach ($nastelNo as $nastel){
                                $cardItem = MobileProcessProduction::getCardItemOrder($nastel);
                                if ($cardItem){
                                    $params = [
                                        'nastel_no' => $nastel,
                                        'started_date' => date('d.m.Y H:i:s'),
                                        'ended_date' => date('d.m.Y H:i:s'),
                                        'table_name' => BichuvDoc::getTableSchema()->name,
                                        'doc_id' => $model->id,
                                        'mobile_tables_id' => $tableId,
                                        'model_orders_items_id' => $cardItem['model_orders_items_id'],
                                        'parent_id' => $cardItem['parent_id'],
                                        'bichuv_detail_type_id' => $cardItem['bichuv_detail_type_id'],
                                        'base_detail_list_id' => $cardItem['base_detail_list_id']
                                    ];
                                    if( MobileProcessProduction::saveMobileProcess($params)){
                                        $saved = true;
                                    }else{
                                        $saved = false;
                                        break;
                                    }
                                }

                            }
                        }

                       if($saved){
                           $model->updateCounters(['status'=>2]);
                           $saved=true;
                       }else{
                           $saved = false;
                       }
                    } else {
                        $saved=false;
                    }
                }
            }else{
                $saved = false;
                Yii::$app->session->setFlash('error', Yii::t('app','Table not found by {tableName}',
                    ['tableName' => $tableName]));
            }
            if ($saved) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Saved successfuly');
            } else {
                $transaction->rollBack();
            }
            return $this->redirect(Yii::$app->request->referrer);
        } catch (\Exception $e) {
            Yii::info('Not saved' . $e, 'save');
            $transaction->rollBack();
        }

    }

    /**
     * @param null $print_id
     * @param null $stone_id
     * @return string
     * @throws Exception
     * Print va Stone get data
     */
    public function actionViewAttachment($print_id = null,$stone_id = null)
    {
        if(!empty($print_id)){
            $sql = " SELECT mvp.name,mvp.code,cp.r,cp.g,cp.b,cp.name_ru,a.path,mvp.id FROM model_var_prints mvp
                    LEFT JOIN model_var_print_rel_attach mvpra ON mvp.id = mvpra.model_var_print_id
                    LEFT JOIN attachments a ON mvpra.attachment_id = a.id
                    LEFT JOIN model_var_prints_colors mvpc ON mvp.id = mvpc.model_var_prints_id
                    LEFT JOIN color_pantone cp ON mvpc.color_pantone_id = cp.id
                    WHERE mvp.id = {$print_id}
                ";
            $resultPrint = Yii::$app->db->createCommand($sql)->queryAll();
        }elseif(!empty($stone_id)){
            $sql = " SELECT mvs.name,mvs.code,a.path,mvs.id FROM model_var_stone mvs
                    LEFT JOIN model_var_print_rel_attach mvpra ON mvs.id = mvpra.model_var_print_id
                    LEFT JOIN attachments a ON mvpra.attachment_id = a.id
                    WHERE mvs.id = {$stone_id}
                ";
            $resultStone = Yii::$app->db->createCommand($sql)->queryAll();
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('view/view_attachment',[
                'resultPrint' => $resultPrint,
                'resultStone' => $resultStone
            ]);
        }

    }

    public function actionGetSelectedTableEmployee(){

        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = false;
        $id = Yii::$app->request->post('id');
       
        if(!empty($id)){
            $employees = MobileTablesRelHrEmployee::getResponsiblePersonByTableId($id);
            if (!empty($employees)){
                $response['status'] = true;
                $response['items'] = $employees;
            }
        }
        return $response;
    }

    public function  actionGetDetailNumberByNastel(){

        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $response = [];
        $response['status'] = false;

        if(!empty($data['nastel'])){
            $parentId =  MobileProcessProduction::find()
                ->where(['nastel_no' => $data['nastel']])
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if (!empty($parentId)){
                $detailNumbers = MobileProcessProduction::find()
                    ->alias('mpp')
                    ->select(['mpp.nastel_no','bdt.name detail_name','bdl.name base_list_name'])
                    ->leftJoin(['bdt' => 'bichuv_detail_types'],'mpp.bichuv_detail_type_id = bdt.id')
                    ->leftJoin(['bdl' => 'base_detail_lists'],'mpp.base_detail_list_id = bdl.id')
                    ->andWhere(['mpp.parent_id' => $parentId->id])
                    ->asArray()
                    ->groupBy(['mpp.nastel_no'])
                    ->all();
                if(!empty($detailNumbers)){
                    $response['status'] = true;
                    $response['items'] = $detailNumbers;
                }
            }
        }

        return $response;
    }
    /**
     * @param $id
     * @return BichuvDoc|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = BichuvDoc::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
