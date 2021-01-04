<?php

namespace app\modules\bichuv\controllers;

use app\modules\admin\models\ToquvUserDepartment;
use app\modules\bichuv\models\BichuvAcceptedMatoFromProduction;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocExpense;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use app\modules\bichuv\models\BichuvDocResponsible;
use app\modules\bichuv\models\BichuvDocSearch;
use app\modules\bichuv\models\BichuvItemBalance;
use app\modules\bichuv\models\BichuvMatoDocSearch;
use app\modules\bichuv\models\BichuvMatoOrderItems;
use app\modules\bichuv\models\BichuvRmItemBalance;
use app\modules\bichuv\models\BichuvSaldo;
use app\modules\bichuv\models\BichuvServiceItemBalance;
use app\modules\bichuv\models\BichuvSliceItemBalance;
use app\modules\bichuv\models\BichuvSubDocItems;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\TikuvDocItems;
use app\modules\tikuv\models\TikuvRmItems;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocumentItems;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvRawMaterials;
use Yii;
use app\modules\bichuv\models\BichuvMatoOrders;
use app\modules\bichuv\models\BichuvMatoSearch;
use app\modules\bichuv\controllers\BaseController;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BichuvMatoController implements the CRUD actions for BichuvMatoOrders model.
 */
class BichuvMatoController extends BaseController
{
    public $slug;
    public $from_dept = null;
    public $to_dept = null;
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
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $slug = BichuvDoc::DOC_TYPE_MOVING_MATO_LABEL;
            $flag = false;
            if (!empty($slug)) {
                if (array_key_exists($slug, BichuvDoc::getDocTypeBySlug())) {
                    $flag = true;
                    $this->slug = $slug;
                }
            }
            $mato_ombor = ToquvDepartments::findOne(['token'=>'BICHUV_MATO_OMBOR']);
            if($mato_ombor!==null){
                $this->from_dept = $mato_ombor['id'];
            }
            $bichuv = ToquvDepartments::findOne(['token'=>'BICHUV_DEP']);
            if($bichuv!==null){
                $this->to_dept = $bichuv['id'];
            }
            $user_id = Yii::$app->user->id;
            $dept = ToquvUserDepartment::findOne(['user_id'=>$user_id,'department_id'=>$mato_ombor['id']]);
            if($dept===null){
                throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
            }
            return true;
        } else {
            return false;
        }
    }
    /**
     * Lists all BichuvMatoOrders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BichuvMatoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'exportConfig' => false,
            'heading' => Yii::t('app', 'Mato buyurtmalari'),
        ]);
    }
    public function actionIndexDoc($id=null)
    {
        $model = BichuvMatoOrders::findOne($id);
        $searchModel = new BichuvMatoDocSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$model['id']);

        return $this->renderPartial('index-doc', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id,
        ]);
    }
    /**
     * Displays a single BichuvMatoOrders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $models = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_MATO,'bichuv_mato_orders_id'=>$model->id])->all();
        $models_aks = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_ACS,'bichuv_mato_orders_id'=>$model->id])->all();
        /*if($model->bichuvDoc){
            $doc = $model->bichuvDoc;
            $searchModel = new BichuvDocItemsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $doc->id);
            $models = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_ACS,'bichuv_mato_orders_id'=>$model,'status'=>1])->all();
            $responsible = BichuvDocResponsible::findOne(['type'=>2,'bichuv_mato_orders_id'=>$id]);
            if(empty($responsible)){
                $responsible = BichuvDocResponsible::findOne(['bichuv_doc_id'=>$doc->id,'type'=>1]);
            }
            return $this->render('view-doc', [
                'model' => $doc,
                'dataProvider' => $dataProvider,
                'models' => $models,
                'responsible' => $responsible
            ]);
        }*/
        $searchModel = new BichuvMatoDocSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$model['id']);
        return $this->render('view', [
            'model' => $model,
            'models' => (!empty($models))?$models:[new BichuvMatoOrderItems()],
            'models_aks' => (!empty($models_aks))?$models_aks:[new BichuvMatoOrderItems()],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'exportConfig' => null,
            'heading' => Yii::t('app', 'Dokumentlar'),
        ]);
    }
    public function actionViewDoc($id)
    {
        $doc = BichuvDoc::findOne($id);
        $searchModel = new BichuvDocItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $doc->id);
        $models = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_ACS,'bichuv_mato_orders_id'=>$doc['bichuv_mato_orders_id'],'status'=>1])->all();
        $responsible = BichuvDocResponsible::findOne(['type'=>2,'bichuv_mato_orders_id'=>$doc->bichuv_mato_orders_id]);
        if(empty($responsible)){
            $responsible = BichuvDocResponsible::findOne(['bichuv_mato_orders_id'=>$doc->bichuv_mato_orders_id,'type'=>1]);
        }
        return $this->render('view-doc', [
            'model' => $doc,
            'dataProvider' => $dataProvider,
            'models' => $models,
            'responsible' => $responsible,
            'is_view' => null,
        ]);
    }

    /**
     * Creates a new BichuvMatoOrders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate($id)
    {
        $mato_orders = $this->findModel($id);
        $mato_order_items = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_MATO,'bichuv_mato_orders_id'=>$mato_orders['id']])->all();
        $model = new BichuvDoc();
        $modelItems = null;
        $modelTDE = null;
        if(empty($mato_order_items)){
            Yii::$app->session->setFlash('error',Yii::t('app', 'Bu buyurtmada matolar topilmadi!!!'));
            return $this->redirect('index');
        }
        $models = [];
        $modelTDE = new BichuvDocExpense();
        $model->reg_date = date('d.m.Y');
        $lastId = $model::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->doc_number = "B" . $lastId . "/" . date('Y');
        $model->bichuv_mato_orders_id = $id;
        $trm_list = [];
        if(!empty($mato_order_items)){
            foreach ($mato_order_items as $mato) {
                $given = BichuvDocItems::find()->joinWith('bichuvDoc bd')->where(['bichuv_mato_order_items_id'=>$mato['id'],'bd.document_type'=>BichuvDoc::DOC_TYPE_MOVING,'bd.from_department'=>$this->from_dept,'bd.to_department'=>$this->to_dept])->andFilterWhere(['>','bd.status',BichuvDoc::STATUS_INACTIVE])->sum('quantity');
                $models[] = new BichuvDocItems([
                    'name' => "{$mato->trmName}",
                    'document_qty' => $mato['quantity'],
                    'bichuv_mato_order_items_id' => $mato['id'],
                    'given_qty' => $given
                ]);
                $trm_list[][$mato->trmName] = $mato->trmName;
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
                if ($model->load($data) && $model->save()) {
                    $modelId = $model->id;
                    $mato_orders->bichuv_doc_id = $modelId;
                    $mato_orders->save(false);
                    $musteriId = $model->musteri_id;
                    if (!empty($dataTDI)) {
                        foreach ($dataTDI as $item) {
                            $modelDI = new BichuvDocItems();
                            $savedDataTDI = [];
                            $savedDataTDI[$DIModelName] = $item;
                            $savedDataTDI[$DIModelName]['roll_count'] = $item['roll_count'];
                            $savedDataTDI[$DIModelName]['bichuv_doc_id'] = $modelId;
                            $savedDataTDI[$DIModelName]['price_sum'] = 0.01;
                            $savedDataTDI[$DIModelName]['price_usd'] = 0.01;
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
                                        'musteri_id' => $item['musteri_id'],
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
                    if ($saved) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return $this->redirect(["view-doc", 'id' => $model->id]);
                    } else {
                        $transaction->rollBack();
                    }
                }
            } catch (Exception $e) {
                Yii::info('All not saved ' . $e, 'save');
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models,
            'trm_list' => $trm_list,
            'mato_orders' => $mato_orders,
        ]);
    }

    /**
     * Updates an existing BichuvMatoOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = BichuvDoc::findOne($id);
        $mato_orders = $this->findModel($model->bichuv_mato_orders_id);
        if(!$model){
            return $this->redirect('index');
        }
        /*if (!empty($model->bichuvDocItems)) {*/
        $models = $model->bichuvDocItems;
        /*} else {
            $models = [new BichuvDocItems()];
        }*/
        if (!empty($model->bichuvDocExpenses) && !empty($model->bichuvDocExpenses[0])) {
            $modelTDE = $model->bichuvDocExpenses[0];
        } else {
            $modelTDE = new BichuvDocExpense();
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
                if ($model->load($data) && $model->save()) {
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
                    $modelId = $model->id;
                    $mato_orders->bichuv_doc_id = $modelId;
                    $mato_orders->save(false);
                    $musteriId = $model->musteri_id;
                    if (!empty($dataTDI)) {
                        foreach ($dataTDI as $item) {
                            $modelDI = new BichuvDocItems();
                            $savedDataTDI = [];
                            $savedDataTDI[$DIModelName] = $item;
                            $savedDataTDI[$DIModelName]['roll_count'] = $item['roll_count'];
                            $savedDataTDI[$DIModelName]['bichuv_doc_id'] = $modelId;
                            $savedDataTDI[$DIModelName]['price_sum'] = 0.01;
                            $savedDataTDI[$DIModelName]['price_usd'] = 0.01;
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
                                        'musteri_id' => $item['musteri_id'],
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
                    if ($saved) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return $this->redirect(["view-doc", 'id' => $model->id]);
                    } else {
                        $transaction->rollBack();
                    }
                }
            } catch (Exception $e) {
                Yii::info('All not saved ' . $e, 'save');
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models,
            'mato_orders' => $mato_orders,
        ]);
    }

    /**
     * Deletes an existing BichuvMatoOrders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = BichuvDoc::findOne($id);
        $mato_orders = $this->findModel($model->bichuv_mato_orders_id);
        if($model->status<$model::STATUS_SAVED&&$mato_orders) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
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
                $mato_orders->status = $mato_orders::STATUS_SAVED;
                if ($model->delete()&&$mato_orders->save()) {
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }
        return $this->redirect(['index']);
    }
    /**
     * @param $id
     * @return Response
     */
    public function actionSaveAndFinish($id)
    {
        $model = BichuvDoc::findOne($id);
        $mato_orders = $model->bichuvMatoOrders;
        if ($model->status < BichuvItemBalance::STATUS_INACTIVE) {
            $slug = Yii::$app->request->get('slug');
            $t = Yii::$app->request->get('t', 1);
            switch ($model->document_type) {
                case 2:
                    if ($slug == BichuvDoc::DOC_TYPE_MOVING_MATO_LABEL) {
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            $saved = false;
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
                            if ($isClone) {
                                $items = $model->getBichuvDocItems()->asArray()->all();
                                if (!empty($items)) {
                                    $cloneId = $cloneModel->id;
                                    $cloneMusteriId = $model->musteri_id;
                                    $from_dept = $model->from_department;
                                    $to_dept = $model->to_department;
                                    foreach ($items as $item) {
                                        $item['department_id'] = $from_dept;
                                        $remain = BichuvRmItemBalance::getLastRecordMato($item, $item['musteri_id']);
                                        if (($remain['inventory'] - $item['quantity']) < 0) {
                                            $res = [];
                                            $res['item'] = $item;
                                            $res['message'] = 'Qoldiq topilmadi';
                                            Yii::info($res,'save');
                                            $lack_qty = $item['quantity'] - $remain['inventory'];
                                            Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {id} dan {lack} yetishmayapti',
                                                ['id' => $item['quantity']." kg", 'lack' => $lack_qty]));
                                            return $this->redirect(['view-doc', 'id' => $id, 'slug' => $this->slug, 't' => $model->type]);
                                        }
                                    }
                                    foreach ($items as $item) {
                                        $item['department_id'] = $from_dept;
                                        //item balancedan tekwiriw
                                        $lastRec = BichuvRmItemBalance::getLastRecordMato($item, $item['musteri_id']);
                                        $inventory = $item['quantity'];
                                        $rollInventory = $item['roll_count'];
                                        if (!empty($lastRec)) {
                                            $inventory = $lastRec['inventory'] - $item['quantity'];
                                            $rollInventory = $lastRec['roll_inventory'] - $item['roll_count'];
                                            if ($rollInventory < 1 && $inventory > 0) {
                                                $rollInventory = 1;
                                            }
                                            if($inventory>=0){
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
                                                    'from_musteri' => $item['musteri_id'],
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
                                                    'musteri_id' => $item['musteri_id'],
                                                    'entity_type' => 2,
                                                    'quantity' => $item['quantity'],
                                                    'document_quantity' => 0,
                                                    'price_sum' => 0,
                                                    'price_usd' => 0,
                                                    'is_own' => 1,
                                                    'roll_count' => $item['roll_count'],
                                                    'is_accessory' => $item['is_accessory'],
                                                    'party_no' => $item['party_no'],
                                                    'musteri_party_no' => $item['musteri_party_no'],
                                                    'model_id' => $item['model_id'],
                                                ]);
                                                if ($modelDocItems->save()) {
                                                    $saved = true;
                                                    //qabul qiluvchi uchun sub doc item
                                                    $modelSubDocItems = new BichuvSubDocItems();
                                                    $subItem = $modelDocItems->getMatoInfoByEntityId($item['entity_id']);
                                                    $modelSubDocItems->setAttributes([
                                                        'doc_item_id' => $modelDocItems->id,
                                                        'musteri_id' => $item['musteri_id'],
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
                                                    $modelSubDocItems->save();
                                                    $bmoi = BichuvMatoOrderItems::findOne($item['bichuv_mato_order_items_id']);
                                                    if ($bmoi) {
                                                        $sum = BichuvDocItems::find()->joinWith('bichuvDoc bd')->where(['bichuv_mato_order_items_id' => $item['bichuv_mato_order_items_id'], 'bd.document_type' => BichuvDoc::DOC_TYPE_MOVING, 'bd.from_department' => $from_dept, 'bd.to_department' => $to_dept])->andFilterWhere(['>', 'bd.status', BichuvDoc::STATUS_INACTIVE])->sum('quantity');
                                                        if (($sum + $item['quantity']) >= $bmoi->quantity) {
                                                            $bmoi->status = 3;
                                                            $bmoi->save();
                                                        }
                                                    }
                                                }else{
                                                    $saved = false;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                                if($mato_orders){
                                    $given = BichuvDocItems::find()->joinWith('bichuvDoc bd')->where(['bd.bichuv_mato_orders_id'=>$model->bichuv_mato_orders_id,'bd.document_type'=>BichuvDoc::DOC_TYPE_MOVING,'bd.from_department'=>$this->from_dept,'bd.to_department'=>$this->to_dept])->andFilterWhere(['>','bd.status',BichuvDoc::STATUS_INACTIVE])->sum('quantity');
                                    $query_given = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_MATO,'bichuv_mato_orders_id'=>$model->bichuv_mato_orders_id])->sum('quantity');
                                    if($given >= $query_given){
                                        $mato_orders->status = $mato_orders::STATUS_ACCEPTED;
                                        if($mato_orders->save()){
                                            $saved = true;
                                        }else{
                                            $saved = false;
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
                    break;
            }
        }
        return $this->redirect(['view', 'id' => $mato_orders->id, 'slug' => $this->slug, 't' => $model->type]);
    }
    public function actionSaveResponsible($id)
    {
        $bichuv_doc = BichuvDoc::findOne($id);
        if($bichuv_doc){
            $model = BichuvDocResponsible::findOne(['bichuv_mato_orders_id'=>$bichuv_doc->bichuv_mato_orders_id,'type'=>1]);
            if(empty($model)){
                $model = new BichuvDocResponsible(['bichuv_mato_orders_id'=>$bichuv_doc->bichuv_mato_orders_id,'type'=>1,'bichuv_doc_id' => $id]);
            }
            if(Yii::$app->request->isPost){
                if($model->load(Yii::$app->request->post())){
                    $response = [];
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app', "Hatolik yuz berdi");
                    if($model->save()){
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        $response['status'] = 1;
                        $response['message'] = Yii::t('app', 'Saved Successfully');
                    }
                    if(Yii::$app->request->isAjax) {
                        return $response;
                    }
                    return $this->redirect(['view-doc','id'=>$bichuv_doc->id]);
                }
            }
            if(Yii::$app->request->isAjax) {
                return $this->renderAjax('save-responsible',[
                    'model' => $model,
                ]);
            }
            return $this->render('save-responsible',[
                'model' => $model,
            ]);
        }
    }
    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "bichuv-mato_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BichuvMatoOrders::find()->select([
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
     * Finds the BichuvMatoOrders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BichuvMatoOrders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BichuvMatoOrders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
