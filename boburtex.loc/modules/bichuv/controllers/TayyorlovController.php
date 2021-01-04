<?php

namespace app\modules\bichuv\controllers;

use app\models\Constants;
use app\modules\base\models\Unit;
use app\modules\bichuv\Bichuv;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use app\modules\bichuv\models\BichuvItemBalance;
use app\modules\bichuv\models\BichuvNastelLists;
use app\modules\bichuv\models\BichuvSliceItemBalance;
use app\modules\bichuv\models\BichuvSliceItems;
use app\modules\bichuv\models\BichuvTableRelWmsDoc;
use app\modules\bichuv\models\TayyorlovNastelAcs;
use app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls;
use app\modules\hr\models\HrDepartments;
use app\modules\mobile\models\MobileDocDiffItems;
use app\modules\mobile\models\MobileProcess;
use app\modules\mobile\models\MobileProcessProduction;
use app\modules\mobile\models\MobileTables;
use app\modules\mobile\models\MobileTablesRelHrEmployee;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\TikuvDocItems;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\wms\models\WmsDepartmentArea;
use Yii;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\TayyorlovSearch;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TayyorlovController implements the CRUD actions for BichuvDoc model.
 */
class TayyorlovController extends Controller
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
                    'delete' => ['POST'],
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
            // TODO: permission bilan slugni ko'rib chiqish kerak
            if (Yii::$app->authManager->getPermission(Yii::$app->controller->id . "/" . $this->slug . "/" . Yii::$app->controller->action->id)) {
                if (!Yii::$app->user->can(Yii::$app->controller->id . "/" . $this->slug . "/" . Yii::$app->controller->action->id)) {
                    throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Lists all BichuvDoc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TayyorlovSearch();

        $docType = '';
        switch ($this->slug) {
            case BichuvDoc::DOC_TYPE_QUERY_ACS_LABEL:
                $docType = BichuvDoc::DOC_TYPE_QUERY;
                break;
            case BichuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL:
            case BichuvDoc::DOC_TYPE_ACCEPTED_LABEL:
                $docType = BichuvDoc::DOC_TYPE_ACCEPTED;
                break;
            case BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL:
                $docType = BichuvDoc::DOC_TYPE_MOVING;
                break;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $docType);

        return $this->render("{$this->slug}/index", [
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
    public function actionView($id)
    {
        $model = $this->findModel($id);

        switch ($model->document_type) {
            case BichuvDoc::DOC_TYPE_QUERY:
                $searchModel = new BichuvDocItemsSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
                break;
            case BichuvDoc::DOC_TYPE_ACCEPTED:
                $searchModel = new BichuvDocItemsSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
                break;
        }

        return $this->render("{$this->slug}/view", [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new BichuvDoc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @param null $parent_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate($id = null, $parent_id = null)
    {
        $request = Yii::$app->getRequest();
        $slug = $this->slug;
        $model = new BichuvDoc();
        $models = [];

        switch ($slug) {
            case BichuvDoc::DOC_TYPE_QUERY_ACS_LABEL:
                if($id){
                    if($parent_id==null){
                        $model = $this->findModel($id);
                    }else{
                        $model = BichuvDoc::findOne(['parent_id'=>$id]) ?? new BichuvDoc();
                        $model->parent_id = $id;
                    }
                }
                else{
                    $model = new BichuvDoc();
                }
                if($model->isNewRecord){
                    $model->reg_date = date('d.m.Y');
                    $lastId = BichuvDoc::getLastId();
                    $model->doc_number = "TAKS-" . $lastId . "/" . date('Y');
                }
                if (!empty($model->bichuvDocItems)) {
                    $models = $model->bichuvDocItems;
                } else{
                    if($parent_id != null) {
                        $parent = BichuvDoc::findOne($id);
                        $items = $parent->getSliceMovingView($id);
                        $totalQty = 0;
                        foreach ($items as $item) {
                            $totalQty += $item['fact_quantity'];
                        }
                        foreach ($parent->aks as $key => $val) {
                            $models[$key] = new BichuvDocItems([
                                'entity_id' => $val['id'],
                                'nastel_no' => $val['nastel_party'],
                                'quantity' => $val['qty'] * $totalQty,
                            ]);
                        }
                    }else {
                        $models = [new BichuvDocItems()];
                    }
                }
                break;
            case BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL:
                $models = [new BichuvSliceItems()];
                $model->reg_date = date('d.m.Y');
                $lastId = BichuvDoc::getLastId();
                $model->doc_number = "TK" . $lastId . "/" . date('Y');
        }

        if ($request->isPost) {
            if ($model->load($request->post())) {
                $saved = false;

                switch ($slug) {
//                    case BichuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL:
                    case BichuvDoc::DOC_TYPE_QUERY_ACS_LABEL:

                        $hasBichuvDocItems = $request->post('BichuvDocItems', []);
                        foreach (array_keys($hasBichuvDocItems) as $index) {
                            $models[$index] = new BichuvDocItems();
                        }

                        $saved = Model::loadMultiple($models, $request->post());

                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            if($saved = $saved && $model->save()){
                                $saved = true;
                                $data = Yii::$app->request->post('BichuvDocItems');
                                if(!empty($data)){
                                    foreach ($data as $item) {
                                        $item['bichuv_doc_id'] = $model->id;
                                        $doc_item = new BichuvDocItems($item);
                                        if (!$doc_item->save()) {
                                            $saved = false;
                                            break;
                                        }
                                    }
                                }
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
                        break;
                    case BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL:
                        foreach (array_keys($request->post('BichuvSliceItems', [])) as $index) {
                            $models[$index] = new BichuvSliceItems();
                        }
                        $saved = Model::loadMultiple($models, $request->post());

                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            $saved = $model->save();

                            if ($saved) {
                                foreach ($models as $bichuvSliceItem) {
                                    $bichuvSliceItem->setAttributes([
                                        'bichuv_doc_id' => $model['id'],
                                    ]);
                                    $saved = $bichuvSliceItem->save();
                                    if (!$saved) {
                                        Yii::error($bichuvSliceItem->getErrors(), 'save');
                                        break;
                                    }
                                }
                            }

                            if ($saved) {
                                $transaction->commit();
                            } else {
                                $transaction->rollBack();
                            }
                        } catch (\Throwable $e) {
                            Yii::error($e->getMessage(), 'exception');
                            $saved = false;
                            $transaction->rollBack();
                        }
                        break;
                }

                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = [];
                    if ($saved) {
                        $response['status'] = 0;
                        $response['message'] = Yii::t('app', 'Saved Successfully');
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                        $response['message'] = Yii::t('app', 'An error occurred');
                    }
                    return $response;
                }
                if ($saved) {
                    Yii::$app->session->setFlash('success', 'Saved Successfully');
                    return $this->redirect(['view', 'id' => $model->id, 'slug' => $this->slug]);
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'An error occurred'));
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax("{$this->slug}/form", [
                'model' => $model,
                'models' => $models,
            ]);
        }
        return $this->render("{$this->slug}/form", [
            'model' => $model,
            'models' => $models,
        ]);
    }

    public function actionUpdate($id) {

        $request = Yii::$app->getRequest();
        $slug = $this->slug;

        $model = $this->findModel($id);
        if ($model->status >= 3) {
            throw new NotFoundHttpException();
        }

        switch ($slug) {
            case BichuvDoc::DOC_TYPE_QUERY_ACS_LABEL:
                $models = $model->bichuvDocItems;
                if ($request->isPost) {
                    $BDIpostData = $request->post('BichuvDocItems', []);
                    $models = [];
                    foreach (array_keys($BDIpostData) as $index) {
                        $models[$index] = new BichuvDocItems();
                    }
                    if ($model->load($request->post()) && Model::loadMultiple($models, $request->post())) {
                        $transaction = Yii::$app->db->beginTransaction();
                        $isSaved = true;
                        try {
                            $isSaved = $model->save();

                            if ($isSaved) {
                                BichuvDocItems::deleteAll(['bichuv_doc_id' => $model->id]);
                                foreach ($models as $bichuvDocItem) {
                                    $bichuvDocItem->bichuv_doc_id = $model->id;
                                    $isSaved = $bichuvDocItem->save();
                                    if (!$isSaved) {
                                        Yii::error($bichuvDocItem->getErrors(), 'save');
                                        break;
                                    }
                                }
                            }

                            if ($isSaved) {
                                $transaction->commit();
                            } else {
                                $transaction->rollBack();
                            }
                        } catch (\Throwable $e) {
                            $isSaved = false;
                            Yii::error($e->getMessage(), 'exception');
                            $transaction->rollBack();
                        }
                        if ($isSaved) {
                            Yii::$app->session->setFlash('success', Yii::t('app','Saved Successfully'));
                            return $this->redirect(["view", 'id' => $model->id, 'slug' => $this->slug]);
                        } else {
                            Yii::$app->session->setFlash('error', 'Xatolik yuz berdi');
                        }
                    }

                }
                break;
            case BichuvDoc::DOC_TYPE_MOVING_SLICE_LABEL:
                $models = $model->bichuvSliceItems;

                if ($request->isPost) {
                    foreach (array_keys($request->post('BichuvSliceItems', [])) as $index) {
                        $models[$index] = new BichuvSliceItems();
                    }
                    $saved = Model::loadMultiple($models, $request->post());

                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $saved = $saved && $model->save();

                        if ($saved) {

                            // oldingi itemlarini o'chirish
                            BichuvSliceItems::deleteAll(['bichuv_doc_id' => $model['id']]);

                            foreach ($models as $bichuvSliceItem) {
                                $bichuvSliceItem->setAttributes([
                                    'bichuv_doc_id' => $model['id'],
                                ]);
                                $saved = $bichuvSliceItem->save();
                                if (!$saved) {
                                    Yii::error($bichuvSliceItem->getErrors(), 'save');
                                    break;
                                }
                            }
                        }

                        if ($saved) {
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();
                        }
                    } catch (\Throwable $e) {
                        Yii::error($e->getMessage(), 'exception');
                        $saved = false;
                        $transaction->rollBack();
                    }

                    if ($saved) {
                        Yii::$app->session->setFlash('success', 'Saved Successfully');
                        return  $this->redirect(['view', 'id' => $model->id, 'slug' => $this->slug]);
                    }
                    else {
                        Yii::$app->session->setFlash('error', 'An error occurred');
                    }
                }

                break;
            case BichuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL:
                $models = $model->bichuvSliceItems;

                if ($request->isPost) {
                    foreach ($models as $bichuvSliceItem) {
                        $bichuvSliceItem->setScenario(BichuvSliceItems::SCENARIO_ACCEPT_SLICE);
                    }
                    $saved = Model::loadMultiple($models, $request->post());

                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $saved = $saved && $model->save();

                        if ($saved) {
                            foreach ($models as $bichuvSliceItem) {
                                $saved = $bichuvSliceItem->save();

                                if (!$saved) {
                                    Yii::error($bichuvSliceItem->getErrors(), 'save');
                                    break;
                                }
                            }
                        }

                        if ($saved) {
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();
                        }
                    } catch (\Throwable $e) {
                        Yii::error($e->getMessage(), 'exception');
                        $saved = false;
                        $transaction->rollBack();
                    }

                    if ($saved) {
                        Yii::$app->session->setFlash('success', 'Saved Successfully');
                        return  $this->redirect(['view', 'id' => $model->id, 'slug' => $this->slug]);
                    }
                    else {
                        Yii::$app->session->setFlash('error', 'An error occurred');
                    }
                }

                break;
        }

        return $this->render("{$this->slug}/form.php", [
            'model' => $model,
            'models' => $models,
        ]);
    }

    /**
     * Qabul qilish
     */
    public function actionSaveAndFinish($id) {
        $model = $this->findModel($id);

        if ($model->status < BichuvDoc::STATUS_SAVED) {
            switch($model->document_type) {
                case BichuvDoc::DOC_TYPE_ACCEPTED:
                    if ($this->slug == BichuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL) { // kesim qabul qilish
                        $transaction = Yii::$app->db->beginTransaction();
                        $saved = false;
                        try {
                            /** begin INSERT PROCESS TO CARD */
                            $nastel_no = $model->bichuvSliceItems[0]->nastel_party;
                            $mobileTable = MobileTables::getTableByToken(Constants::TOKEN_TAYYORLOV_ACCEPT_SLICE);

                            if ($mobileTable === null) { // virtual stol topilmasa
                                Yii::$app->session->setFlash('error', 'Process not found');
                                $saved = false;
                            } else {
                                $mobileProcessProduction = [
                                    'nastel_no' => $nastel_no,
                                    'started_date' => date('d.m.Y H:i:s'),
                                    'ended_date' => date('d.m.Y H:i:s'),
                                    'mobile_tables_id' => $mobileTable['id'],
                                    'doc_id' => $model['id'],
                                    'table_name' => $model::getTableSchema()->name,
                                    'status' => MobileProcessProduction::STATUS_ENDED,
                                ];
                                $saved = MobileProcessProduction::saveMobileProcess($mobileProcessProduction);
                            }
                            /** end INSERT PROCESS TO CARD */

                            $sliceItems = $model->getBichuvSliceItems()->asArray()->all();
                            $modelId = $model->id;
                            $fromId = $model->from_hr_department;
                            $deptId = $model->to_hr_department;
                            $givenData = [];
                            if ($saved) {
                                foreach ($sliceItems as $item) {
                                    /** begin INSERT DIFF */
                                    // farq bo'lsa saqlash
                                    $diffArr = [
                                        'doc_items_id' => $item['id'],
                                        'table_name' => 'bichuv_slice_items',
                                        'unit_id' => Unit::getIdByCode(Unit::CODE_DONA),
                                        'department_id' => HrDepartments::getDepartmentIdByToken(HrDepartments::TOKEN_TAYYORLOV),
                                        'add_info' => $item['add_info'],
                                        'status' => 1,
                                    ];
                                    $saved = $saved && MobileDocDiffItems::saveAs($diffArr, $item['quantity'], $item['fact_quantity']);
                                    /** end INSERT DIFF */
                                    $modelAcceptItems = new BichuvSliceItemBalance();
                                    $item['department_id'] = $deptId;
                                    $checkExists = BichuvSliceItemBalance::getLastFromItemDept($item);
                                    $inventory = $item['fact_quantity']; // faktini qabul qiladi
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
                            }
                            if ($saved) {
                                $model->updateCounters(['status' => 2]);
                                $transaction->commit();
                                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Qabul qilindi'));
                            } else {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', Yii::t('app', "Error in reception"));
                            }
                        } catch (\Throwable $e) {
                            Yii::error('Not all saved-> ' . $e->getMessage(), 'save');
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Error: exception!'));
                        }
                    }
                    elseif ($this->slug == BichuvDoc::DOC_TYPE_ACCEPTED_LABEL) { // aksessuar qabul qilish
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            $acsItems = $model->bichuvDocItems;
                            $saved = false;

                            $model->status = BichuvDoc::STATUS_SAVED;
                            $saved = $model->save();
                            $nastelNo = '';

                            /** default area for TAYYORLOV */
                            $defaultAcsAreaId = WmsDepartmentArea::getAreaIdByToken(WmsDepartmentArea::DEFAULT_ZONE_TOKEN_FOR_TAYYORLOV);
                            if (!$defaultAcsAreaId) {
                                Yii::$app->session->addFlash('error', Yii::t('app', 'Create an accessory storage sector first'));
                                $tokenArea = WmsDepartmentArea::DEFAULT_ZONE_TOKEN_FOR_TAYYORLOV;
                                throw new \yii\base\Exception("Tayyorlov uchun '{$tokenArea}' tokenli sektor yaratilmagan");
                            }

                            if ($saved && is_iterable($acsItems)) {
                                $tayyorlovDep = HrDepartments::findOne(['token' => Constants::$TOKEN_TAYYORLOV]);
                                foreach ($acsItems as $sliceItem) {
                                    $nastelNo = $sliceItem['nastel_no'];
                                    $transferItemBalace = new BichuvItemBalance();
                                    $transferItemBalace->setAttributes([
                                        'document_id' => $model->id,
                                        'document_type' => $model->document_type,
                                        'entity_id' => $sliceItem['entity_id'],
                                        'entity_type' => $sliceItem['entity_type'],
                                        'count' => $sliceItem['quantity'],
                                        'reg_date' => date('Y-m-d H:i:s'),
                                        'department_id' => $model['to_hr_department'],
                                        'to_department' => $model['to_hr_department'],
                                        'from_department' => $model['from_hr_department'],
                                        'to_area' => $defaultAcsAreaId,
                                        'dep_area' => $defaultAcsAreaId,
                                        'comment' => $model['add_info'],
                                    ]);

                                    $saved = BichuvItemBalance::increaseItem($transferItemBalace);
                                    if (!$saved) {
                                        Yii::error($transferItemBalace->getErrors(), 'save');
                                        break;
                                    }
                                }
                                /** nastel_no bilan acs doc ni biriktirish */
                                if ($saved && $nastelNo) {
                                    $tayyorlocNastelAcs = new TayyorlovNastelAcs();
                                    $tayyorlocNastelAcs->setAttributes([
                                        'nastel_no' => $nastelNo,
                                        'acs_doc_id' => $model['id'],
                                        'status' => TayyorlovNastelAcs::STATUS_ACTIVE
                                    ]);
                                    $saved = $tayyorlocNastelAcs->save();
                                }
                            }

                            if ($saved) {
                                $transaction->commit();
                                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successfully accepted'));
                            } else {
                                $transaction->rollBack();
                                Yii::$app->getSession()->setFlash('error', Yii::t('app', "Error in reception"));
                            }
                        } catch (\Throwable $e) {
                            Yii::error('Not all saved ' . $e, 'save');
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Error: exception!'));
                        }
                    }
                    break;
                case BichuvDoc::DOC_TYPE_QUERY:
                    $model->status = BichuvDoc::STATUS_SAVED;

                    $transaction = Yii::$app->db->beginTransaction();
                    $isAllSaved = true;
                    try {
                        $isAllSaved = $model->save();
                        if (!$isAllSaved) {
                            throw new ErrorException();
                        }

                        /** begin INSERT PROCESS TO CARD */
                        $nastel_no = $model->bichuvDocItems[0]->nastel_no;
                        $mobileTable = MobileTables::getTableByToken(Constants::TOKEN_TAYYORLOV_QUERY_ACS);

                        if ($mobileTable === null) { // virtual stol topilmasa
                            Yii::$app->session->setFlash('error', 'Process not found');
                            Yii::error(Constants::TOKEN_TAYYORLOV_QUERY_ACS . ' token li stol yaratilmagan(');
                            $isAllSaved = false;
                        } else {
                            $mobileProcessProduction = [
                                'nastel_no' => $nastel_no,
                                'started_date' => date('d.m.Y H:i:s'),
                                'ended_date' => date('d.m.Y H:i:s'),
                                'mobile_tables_id' => $mobileTable['id'],
                                'doc_id' => $model['id'],
                                'table_name' => $model::getTableSchema()->name,
                                'status' => MobileProcessProduction::STATUS_ENDED,
                            ];
                            $isAllSaved = MobileProcessProduction::saveMobileProcess($mobileProcessProduction);
                        }
                        /** end INSERT PROCESS TO CARD */

                        if (!$isAllSaved) {
                            throw new ErrorException();
                        }

                        $bichuvDocAttributes = $model->getAttributes(null, [
                            'id',
                            'parent_id',
                            'doc_number',
                            'reg_date',
                            'status',
                            'created_at',
                            'updated_at',
                            'updated_by',
                        ]);

                        /** begin Aksessuar tarqatuvchi ombor uchun document va itemslarini yaratish */
                        $lastId = BichuvDoc::getLastId();
                        $newBichuvDoc = new BichuvDoc();
                        $newBichuvDoc->attributes = $bichuvDocAttributes;
                        $newBichuvDoc->setAttributes([
                            'parent_id' => $model['id'],
                            'from_hr_department' => $bichuvDocAttributes['to_hr_department'],
                            'to_hr_department' => $bichuvDocAttributes['from_hr_department'],
                            'from_hr_employee' => $bichuvDocAttributes['to_hr_employee'],
                            'to_hr_employee' => $bichuvDocAttributes['from_hr_employee'],
                            'doc_number' => "B" . $lastId . "/" . date('Y'),
                            'reg_date' => date('Y-m-d H:i:s'),
                            'document_type' => BichuvDoc::DOC_TYPE_MOVING_ACS_WITH_NASTEL,
                            'status' => BichuvDoc::STATUS_ACTIVE,
                        ]);
                        $isAllSaved = $isAllSaved && $newBichuvDoc->save();
                        if (!$isAllSaved) {
                            throw new ErrorException('Hujjat saqlanmadi!');
                        }

                        if ($bichuvDocItems = $model->bichuvDocItems) {
                            foreach ($bichuvDocItems as $bichuvDocItem) {
                                $newBichuvDocItem = new BichuvDocItems();
                                $bichuvDocItemAttributes = $bichuvDocItem->getAttributes(null, ['id', 'bichuv_doc_id']);
                                $newBichuvDocItem->bichuv_doc_id = $newBichuvDoc->id;
                                $newBichuvDocItem->attributes = $bichuvDocItemAttributes;
                                $newBichuvDocItem->nastel_no = $bichuvDocItem['nastel_no'];
                                $isAllSaved = $isAllSaved && $newBichuvDocItem->save();
                                if (!$isAllSaved) {
                                    throw new ErrorException('Document itemi saqlanmadi!');
                                    break;
                                }
                            }
                        } else {
                            $isAllSaved = false;
                            throw new ErrorException('Document itemslari mavjud emas');
                        }
                        /** end Aksessuar tarqatuvchi ombor uchun document va itemslarini yaratish */


                        $transaction->commit();
                    } catch (\Throwable $exception) {
                        $isAllSaved = false;
                        $transaction->rollBack();
                        Yii::error($exception->getMessage(), 'exception');
                    }
                    if ($isAllSaved) {
                        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Saved Successfully'));
                    }else {
                        Yii::$app->getSession()->setFlash('error', Yii::t('app', 'An error occurred'));
                    }
                    break;
                case BichuvDoc::DOC_TYPE_MOVING:
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $sliceItems = $model->bichuvSliceItems;
                        $saved = false;
                        $nastelNo = '';

                        $model->status = BichuvDoc::STATUS_SAVED;
                        $saved = $model->save();
                        /** nastellarni item balancedan ayirish */
                        if ($saved && is_iterable($sliceItems)) {
                            foreach ($sliceItems as $sliceItem) {
                                $transferItemBalace = new BichuvSliceItemBalance();
                                $transferItemBalace->setAttributes([
                                    'doc_id' => $model->id,
                                    'doc_type' => $model->document_type,
//                                    'entity_id' => $sliceItem['entity_id'], // TODO: nastel entity id sini yozishni ko'rib chiqish kerak
                                    'entity_type' => 2,
                                    'party_no' => $sliceItem['nastel_party'],
                                    'size_id' => $sliceItem['size_id'],
                                    'count' => $sliceItem['quantity'],
                                    'hr_department_id' => $model['from_hr_department'],
                                    'to_hr_department' => $model['to_hr_department'],
                                    'from_hr_department' => $model['from_hr_department'],
                                ]);

                                $nastelNo = $sliceItem['nastel_party'];

                                $saved = BichuvSliceItemBalance::decreaseItem($transferItemBalace);
                                if (!$saved) {
                                    Yii::error($transferItemBalace->getErrors(), 'save');
                                    break;
                                }
                            }
                        }

                        $acsForNastel = BichuvDoc::getRelDocByNastelNo($nastelNo);
                        /** barcha doc larni olib, itemslari bo'yicha aks item balance dan ayiramiz */
                        if ($saved && is_iterable($acsForNastel)) {
                            foreach ($acsForNastel as $acsDoc) {
                                foreach ($acsDoc->bichuvDocItems as $bichuvDocItem) {
                                    $transferItemBalace = new BichuvItemBalance();
                                    $transferItemBalace->setAttributes([
                                        'document_id' => $acsDoc->id,
                                        'document_type' => $acsDoc->document_type,
                                        'entity_id' => $bichuvDocItem['entity_id'],
                                        'entity_type' => $bichuvDocItem['entity_type'],
                                        'count' => $bichuvDocItem['quantity'],
                                        'reg_date' => date('Y-m-d H:i:s'),
                                        'department_id' => $model['from_hr_department'],
                                        'to_department' => $model['to_hr_department'],
                                        'from_department' => $model['from_hr_department'],
                                        'comment' => $model['add_info'],
                                    ]);

                                    $saved = BichuvItemBalance::decreaseItem($transferItemBalace);
                                    if (!$saved) {
                                        Yii::error($transferItemBalace->getErrors(), 'save');
                                        break;
                                    }
                                }
                            }
                        }

                        /** tikuv bo'limiga doc yaratib beramiz */
                        if ($saved) {

                            // nastel nomerga qarab qaysi konveyerga planlanganini aniqlash
                            $tikuvKonveyer = TikuvKonveyerBichuvGivenRolls::getInstanceByNastelNo($nastelNo);
                            $mobileProcessId = null;
                            if ($tikuvKonveyer === null) {
                                $saved = false;
                                Yii::error("{$nastelNo} raqamli nastel konveyerga biriktirilmagan!", 'find');
                                Yii::$app->session->addFlash('warning', "{$nastelNo} raqamli nastel konveyerga biriktirilmagan!");
                            }else {
                                // mobile_tables_id dan mobile_process_id ni olish
                                $mobileProcessId = MobileTables::getProcessIdById($tikuvKonveyer['mobile_tables_id']);

                                // tikuv stoli ma'sul shaxs idsini olish
                                $mobileTableResponsiblePerson = MobileTablesRelHrEmployee::getResponsiblePersonByTableId($tikuvKonveyer['mobile_tables_id']);
                                if ($mobileTableResponsiblePerson === null) {
                                    $saved = false;
                                    Yii::error($tikuvKonveyer['mobile_tables_id'] . ' stolga masul shaxs biriktirilmagan!', 'find');
                                    Yii::$app->session->addFlash('warning', Yii::t('app', 'The person in charge is not attached to the conveyor'));
                                }
                            }

                            $clonedBichuvDoc = $model->getAttributes(null, ['id', 'created_at', 'updated_at', 'created_by', 'status', 'type', 'is_service']);
                            $tikuvDocAttributes = [
                                'document_type' => TikuvDoc::DOC_TYPE_ACCEPTED,
                                'doc_number' => 'TK' . TikuvDoc::find()->select('id')->orderBy(['id' => SORT_DESC])->limit(1)->scalar() . '/' . date('Y'),
                                'party_no' => $nastelNo,
                                'party_count' => 1,
                                'reg_date' => date('Y-m-d H:i:s'),
                                'status' => 1,
                                'from_hr_department' => $clonedBichuvDoc['from_hr_department'],
                                'to_hr_department' => $clonedBichuvDoc['to_hr_department'],
                                'from_hr_employee' => $clonedBichuvDoc['from_hr_employee'],
                                'to_hr_employee' => $mobileTableResponsiblePerson['id'] ?? null,
                                'mobile_table_id' => isset($tikuvKonveyer) ? $tikuvKonveyer['mobile_tables_id'] : null,
                                'mobile_process_id' => $mobileProcessId,
                            ];
                            $tikuvDoc = new TikuvDoc();
                            $tikuvDoc->setAttributes(array_merge($clonedBichuvDoc, $tikuvDocAttributes));
                            $saved = $saved && $tikuvDoc->save();

                            if ($saved) {
                                foreach ($model->bichuvSliceItems as $sliceItem) {
                                    $tikuvDocItems = new TikuvDocItems();
                                    $tikuvDocItems->setAttributes([
                                        'tikuv_doc_id' => $tikuvDoc['id'],
                                        'entity_type' => 1,
                                        'size_id' => $sliceItem['size_id'],
                                        'quantity' => $sliceItem['quantity'],
                                        'fact_quantity' => (int)$sliceItem['quantity'],
                                        'nastel_party_no' => $sliceItem['nastel_party']
                                    ]);
                                    $saved = $tikuvDocItems->save();
                                    if (!$saved) {
                                        Yii::error($tikuvDocItems->getErrors(), 'save');
                                        break;
                                    }
                                }
                            }

                            if ($saved) {
                                /** begin INSERT PROCESS TO CARD */
                                $mobileTable = MobileTables::getTableByToken(Constants::TOKEN_TAYYORLOV_MOVING_SLICE);

                                if ($mobileTable === null) { // virtual stol topilmasa
                                    Yii::$app->session->setFlash('error', 'Process not found');
                                    $saved = false;
                                } else {
                                    $mobileProcessProduction = [
                                        'nastel_no' => $nastelNo,
                                        'started_date' => date('d.m.Y H:i:s'),
                                        'ended_date' => date('d.m.Y H:i:s'),
                                        'mobile_tables_id' => $mobileTable['id'],
                                        'doc_id' => $model['id'],
                                        'table_name' => $model::getTableSchema()->name,
                                        'status' => MobileProcessProduction::STATUS_ENDED,
                                    ];
                                    $saved = MobileProcessProduction::saveMobileProcess($mobileProcessProduction);
                                }
                                /** end INSERT PROCESS TO CARD */
                            }
                        }
                        /** Deleted card from bichuv navbat **/
                        if($saved){
                            $saved = BichuvTableRelWmsDoc::setStatusFinished($nastelNo);
                        }
                        /** End bichuv navbat **/
                        if ($saved) {
                            $transaction->commit();
                            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successfully shipped'));
                        } else {
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('error', Yii::t('app', "An error occurred"));
                        }
                    } catch (\Throwable $e) {
                        Yii::info('Not all saved ' . $e->getMessage(), 'save');
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Error: exception!'));
                    }

            }

        }

        return $this->redirect(['view', 'id' => $model->id, 'slug' => $this->slug]);
    }

    public function actionGetNastelMoving()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $result['status'] = 0;
        $result['message'] = Yii::t('app', 'Bunday raqamdagi partiya topilmadi yoki avval kiritilgan :(');

        if (!empty($data) && !empty($data['nastel']) && !empty($data['department'])) {

            $sizeCondition = "";

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
                           cp.code
                    from bichuv_slice_item_balance bsib
                             left join bichuv_given_rolls bgr on bsib.party_no = bgr.nastel_party
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
                    ORDER BY bsib.size_id;";
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
        }
        return $result;
    }


    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "tayyorlov_".date("d-m-Y-His").".xls";
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
     * Finds the BichuvDoc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BichuvDoc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BichuvDoc::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
