<?php

namespace app\modules\bichuv\controllers;

use app\models\Constants;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\base\models\ModelOrdersItems;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\bichuv\models\BichuvNastelLists;
use app\modules\bichuv\models\BichuvTableRelWmsDoc;
use app\modules\mobile\models\MobileProcessProduction;
use app\modules\mobile\models\MobileTables;
use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\db\Exception;
use app\modules\base\models\Size;
use yii\web\NotFoundHttpException;
use app\modules\base\models\ModelsAcs;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvGivenRolls;
use app\modules\bichuv\models\BichuvDetailTypes;
use app\modules\bichuv\models\ModelRelProduction;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\bichuv\models\BichuvRmItemBalance;
use app\modules\bichuv\models\BichuvNastelDetails;
use app\modules\bichuv\models\BichuvGivenRollItems;
use app\modules\bichuv\models\BichuvGivenRollsSearch;
use app\modules\bichuv\models\BichuvNastelItemsSearch;
use app\modules\bichuv\models\BichuvNastelDetailItems;
use app\modules\bichuv\models\BichuvGivenRollItemsAcs;

/**
 * BichuvGivenRollsController implements the CRUD actions for BichuvGivenRolls model.
 */
class BichuvGivenRollsController extends BaseController
{
    /**
     * Lists all BichuvGivenRolls models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BichuvGivenRollsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BichuvGivenRolls model.
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

    public function actionCode($id)
    {
        $roll = BichuvGivenRolls::getRollOne($id);
        $roll_items = BichuvGivenRolls::getNastelDetalItems($id);
        return $this->renderAjax('code', [
            'roll' => $roll,
            'roll_items' => $roll_items,
        ]);
    }

    public function actionCodeAll($id)
    {
        $roll = BichuvGivenRolls::getRollOne($id);
        $roll_items = BichuvGivenRolls::getNastelDetalItems($roll['nastel_id']);
        return $this->renderAjax('code-all', [
            'roll' => $roll,
            'roll_items' => $roll_items,
        ]);
    }
    public function actionNastelList($id)
    {
        $roll = BichuvGivenRolls::findOne($id);
        $main = \app\modules\bichuv\models\BichuvGivenRollItems::find()->where(['entity_type'=>1,'bichuv_given_roll_id'=>$roll->id,'bichuv_detail_type_id'=>BichuvDetailTypes::getType('MAIN')])->all();
        $rybana = \app\modules\bichuv\models\BichuvGivenRollItems::find()->where(['entity_type'=>1,'bichuv_given_roll_id'=>$roll->id,'bichuv_detail_type_id'=>BichuvDetailTypes::getType('RYBANA')])->all();
        $beyka = \app\modules\bichuv\models\BichuvGivenRollItems::find()->where(['entity_type'=>1,'bichuv_given_roll_id'=>$roll->id,'bichuv_detail_type_id'=>BichuvDetailTypes::getType('BEKA')])->all();
        return $this->renderAjax('nastel-list', [
            'roll' => $roll,
            'main' => $main,
            'beyka' => $beyka,
            'rybana' => $rybana,
        ]);
    }
    public function actionNastelAksessuar($id)
    {
        $roll = BichuvGivenRolls::findOne($id);
        return $this->renderAjax('nastel-aksessuar', [
            'roll' => $roll,
        ]);
    }
    /**
     * @return array
     */
    public function actionGetRm()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $nastel = Yii::$app->request->get('q');
        $modelBD = new BichuvDoc();
        $data = $modelBD->getRmWithNastel($nastel, false, true);
        return $data;
    }

    /**
     * Creates a new BichuvGivenRolls model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws Exception
     */
    public function actionCreate()
    {
        $btrwdId = Yii::$app->request->get("id");
        $t = Yii::$app->request->get('t', 1);

        $btrwd = BichuvTableRelWmsDoc::find()
            ->alias('btrwd')
            ->select([
                'bnl.name as nastel_no',
                'wdi.to_musteri as to_musteri',
                'wdi.musteri_id as musteri_id',
                'bnl.id nastel_no_id',
                'GROUP_CONCAT(DISTINCT wdi.musteri_party_no) as musteri_party_no'
                ])
            ->leftJoin(['wd' => 'wms_document'],'btrwd.wms_doc_id = wd.id')
            ->leftJoin(['wdi' => 'wms_document_items'], 'wdi.wms_document_id = wd.id')
            ->leftJoin(['bnl' => 'bichuv_nastel_lists'],'wd.bichuv_nastel_list_id = bnl.id')
            ->where(['btrwd.id' => $btrwdId])
            ->groupBy(['wdi.wms_document_id'])
            ->asArray()
            ->one();

        $model = new BichuvGivenRolls();
        $model->model_var_id = [];
        $model->cp['data'] = [];
        $model->cp['dataPart'] = [];
        $model->cp['dataAttr'] = [];
        $model->cp['dataPartAttr'] = [];
        $model->cp['model_var_part_id'] = [];
        $model->order_item_id = [];
        $model->reg_date = date('d.m.Y');
        $lastRec = $model::find()->select(['doc_number'])->asArray()->orderBy(['id' => SORT_DESC])->one();
        $docNumber = 1;
        if (!empty($lastRec)) { $docNumber += $lastRec['doc_number'];}
        $model->doc_number = $docNumber;
        $model->nastel_party = $btrwd['nastel_no'];
        $model->musteri_id = $btrwd['musteri_id'];
        $model->customer_id = $btrwd['to_musteri'];
        $modelsAcs = [new BichuvGivenRollItemsAcs()];
        $modelBD = new BichuvDoc();

        $query = BichuvRmItemBalance::getRmInfo($btrwd);
        $models = [];
        if ($query){
            foreach ($query as $key => $item){
                $newGivenRollItem = new BichuvGivenRollItems([
                    'entity_id' => $item['entity_id'],
                    'entity_name' => $item['name'],
                    'model_orders_items_id' => $item['moii'],
                    'party_no' => $item['party_no'],
                    'musteri_party_no' => $item['musteri_party_no'],
                    'quantity' => $item['rulon_kg'],
                    'remain' => $item['rulon_kg'],
                    'roll_count' => $item['rulon_count'],
                    'roll_remain' => $item['rulon_count'],
                ]);
              array_push($models,$newGivenRollItem);
            }
        }
        $data = Yii::$app->request->post();
        if (Yii::$app->request->isPost) {

            $transaction = Yii::$app->db->beginTransaction();
            $data['BichuvGivenRolls']['type'] = $t;
            try {
                $saved = false;
                if ($model->load($data) && $model->save()) {
                    $modelId = $model->id;
                    $productModelId = null;
                    $nastelNo = $model->nastel_party;
                    if (!empty($data['BichuvGivenRollItems'])) {
                        foreach ($data['BichuvGivenRollItems'] as $key => $item) {
                            $modelBGRI = new BichuvGivenRollItems();
                            $modelBGRI->setAttributes([
                                'entity_id' => $item['entity_id'],
                                'quantity' => $item['quantity'],
                                'roll_count' => $item['roll_count'],
                                'required_count' => $item['required_count'],
                                'model_id' => $item['new_model_id'],
                                'mobile_table_id' => $item['mobile_table_id'],
                                'party_no' => $item['party_no'],
                                'musteri_party_no' => $item['musteri_party_no'],
                                'bichuv_detail_type_id' => $item['bichuv_detail_type_id'],
                                'bichuv_given_roll_id' => $modelId,
                                'model_orders_items_id' => $item['model_orders_items_id']
                            ]);
                            if ($modelBGRI->save()) {
                                if (!empty($item['child'])) {
                                    foreach ($item['child'] as $n => $size) {
                                        if (!empty($size)) {
                                            $new_size = new BichuvNastelDetailItems([
                                                'size_id' => $n,
                                                'required_count' => $size,
                                                'bichuv_given_roll_items_id' => $modelBGRI['id'],
                                            ]);
                                            if ($new_size->save()) {
                                                $saved = true;
                                            } else {
                                                Yii::$app->session->setFlash('error', Yii::t('app','Xatolik yuz berdi!'));
                                                $saved = false;
                                                break;
                                            }
                                        }
                                    }
                                }
                            } else {
                                Yii::$app->session->setFlash('error', Yii::t('app','Xatolik yuz berdi!'));
                                $saved = false;
                                break;
                            }
                        }
                    }

                    if (!empty($data['BichuvGivenRollItems'])){
                        $checkArray = [];
                        foreach ($data['BichuvGivenRollItems'] as $item){
                            $orderData =  BichuvDocItems::getOrderDataByModelOrdersItemsId($item['model_orders_items_id']);
                            if(!empty($orderData)){
                                $modelRelProd = new ModelRelProduction([
                                    'models_list_id' => $orderData['data']['models_list_id'],
                                    'model_variation_id' => $orderData['data']['model_var_id'],
                                    'bichuv_given_roll_id' => $modelId,
                                    'order_id' => (int)$orderData['data']['model_orders_id'],
                                    'order_item_id' => $item['model_orders_items_id'],
                                    'price' => $orderData['data']['price'],
                                    'pb_id' => $orderData['data']['pb_id'],
                                    'type' => 2,
                                    'nastel_no' => $model->nastel_party
                                ]);
                                if(!empty($checkArray)){
                                    foreach ($checkArray as $checkItem){
                                        if($checkItem['order_item_id'] != $modelRelProd['order_item_id']){
                                            array_push($checkArray,$modelRelProd);
                                        }
                                    }
                                }else{
                                    array_push($checkArray,$modelRelProd);
                                }
                            }
                        }
                        foreach ($checkArray as $item){
                            $isExists = ModelRelProduction::find()->where([
                                'order_id' => $item['order_id'],
                                'order_item_id' => $item['order_item_id'],
                                'bichuv_given_roll_id' => $item['bichuv_given_roll_id']
                            ])->exists();

                            if (!$isExists){
                                if($item->save()){
                                    $saved = true;
                                }else{
                                    Yii::$app->session->setFlash('error', Yii::t('app','Xatolik yuz berdi!'));
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                    }

                    if ($saved) {
                        Yii::$app->session->setFlash('success',Yii::t('app','Saqlandi'));
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id, 't' => $t]);
                    }
                }
            } catch (Exception $e) {

                Yii::info('Not saved' . $e, 'save');
            }
        }
        return $this->render('create', [
            'model' => $model,
            'models' => $models,
            'modelsAcs' => $modelsAcs
        ]);
    }

    /**
     * @param $orderId
     * @return array
     * @throws Exception
     */
    public function actionGetModelList($orderId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $items = BichuvGivenRolls::getOrderModelLists(true,'model_id',$orderId);
        $response['status'] = false;
        if (!empty($items)) {
            $response['status'] = true;
            $response['items'] = $items;
        }
        return $response;
    }
    /**
     * @param $modelId
     * @param $orderId
     * @return array
     */
    public function actionGetModelVariations($modelId, $orderId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $items = BichuvGivenRolls::getOrderItemList($modelId,$orderId);
        $response['status'] = false;
        if (!empty($items)) {
            $response['status'] = true;
            $response['items'] = $items;
        }
        return $response;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function actionGetModelVariationParts()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $response['status'] = false;
        if(!empty($data)){
            $items = BichuvGivenRolls::getModelVarParts($data);
            if (!empty($items)) {
                $response['status'] = true;
                $response['items'] = $items;
            }
        }
        return $response;
    }

    public function actionGetModelAcs($id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $acs = ModelsAcs::find()->select(['bichuv_acs_id', 'qty'])->where(['model_list_id' => $id])->asArray()->all();
        $response = [];
        $response['status'] = false;
        if (!empty($acs)) {
            $response['status'] = true;
            $response['items'] = $acs;
        }
        return $response;
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionUpdate($id)
    {
        $acs = BichuvDetailTypes::getType('ACCESSORY');
        $t = Yii::$app->request->get('t', 1);
        $model = $this->findModel($id);
        if($model->status>$model::STATUS_ACTIVE){
            return $this->redirect(['view', 'id' => $id, 't' => $t]);
        }
        $matoList = BichuvGivenRollItems::find()->where(['not in', 'bichuv_detail_type_id', $acs])->andWhere(['bichuv_given_roll_id' => $id])->all();
        $models = !empty($matoList) ? $matoList : [new BichuvGivenRollItems()];
        $acsList = BichuvGivenRollItemsAcs::find()->where(['bichuv_detail_type_id' => $acs, 'bichuv_given_roll_id' => $id])->all();
        $modelsAcs = !empty($acsList) ? $acsList : [new BichuvGivenRollItemsAcs()];
        $modelBD = new BichuvDoc();

        $modelNastel = new BichuvNastelDetails();
        $modelNastelItems = new BichuvNastelDetailItems();
        if (empty($models)) {
            $models = [new BichuvGivenRollItems()];
        }
        $isPlan = false;
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost && $t == 2) {
            $isPlan = true;
            $ajaxSaved = false;
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!empty($data['BichuvNastelDetails'])) {
                    $modelId = $model->id;
                    $nastelDetailId = null;
                    $nastelDetailExists = BichuvNastelDetails::find()->where([
                        'bichuv_given_roll_id' => $modelId,
                        'detail_type_id' => $data['BichuvNastelDetails']['detail_type_id']
                    ])->asArray()->one();
                    if (!empty($nastelDetailExists)) {
                        $ajaxSaved = true;
                        $nastelDetailId = $nastelDetailExists['id'];
                    } else {
                        $modelBND = new BichuvNastelDetails();
                        $productModelId = $model->bichuvGivenRollItems[0]->model_id;
                        $nastelNo = $model->nastel_party;
                        $entityId = $data['BichuvNastelDetails']['entity_id'];
                        $entityType = 1;
                        if ($data['BichuvNastelDetails']['token'] == $model::TOKEN_ACCESSORY) {
                            $entityId = $data['BichuvNastelDetails']['acs_entity_id'];
                            $entityType = 2;
                        }
                        $modelBND->setAttributes([
                            'detail_type_id' => $data['BichuvNastelDetails']['detail_type_id'],
                            'size_collection_id' => $data['BichuvNastelDetails']['size_collection_id'],
                            'entity_id' => $entityId,
                            'entity_type' => $entityType,
                            'nastel_no' => $nastelNo,
                            'bichuv_given_roll_id' => $modelId,
                            'model_id' => $productModelId,
                        ]);
                        if ($modelBND->save()) {
                            $ajaxSaved = true;
                            $nastelDetailId = $modelBND->id;
                        } else {
                            $ajaxSaved = false;
                        }
                    }
                    if ($ajaxSaved) {
                        if (!empty($data['BichuvNastelDetailItems'])) {
                            foreach ($data['BichuvNastelDetailItems'] as $key => $bichuvNastelDetailItem) {
                                if ($bichuvNastelDetailItem['required_count'] > 0) {
                                    $oldBNDI = BichuvNastelDetailItems::findOne([
                                        'size_id' => $key,
                                        'bichuv_nastel_detail_id' => $nastelDetailId
                                    ]);
                                    $modelBNDI = $oldBNDI ?? new BichuvNastelDetailItems();
                                    $requiredCount = ($oldBNDI) ? $oldBNDI['required_count'] + $bichuvNastelDetailItem['required_count'] : $bichuvNastelDetailItem['required_count'];
                                    $modelBNDI->setAttributes([
                                        'size_id' => $key,
                                        'bichuv_nastel_detail_id' => $nastelDetailId,
                                        'required_count' => $requiredCount
                                    ]);
                                    if ($modelBNDI->save()) {
                                        $ajaxSaved = true;
                                    } else {
                                        $ajaxSaved = false;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                if ($ajaxSaved) {
                    $transaction->commit();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }

        if ($t == 2) {
            $searchModel = new BichuvNastelItemsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
            $model->cp['searchModel'] = $searchModel;
            $model->cp['dataProvider'] = $dataProvider;
        }
        if (Yii::$app->request->isPost && !$isPlan) {
            $transaction = Yii::$app->db->beginTransaction();
            $data = Yii::$app->request->post();
            try {
                $saved = false;
                if ($model->load($data) && $model->save()) {
                    $sql = "delete bndi from bichuv_nastel_detail_items bndi
                                 join bichuv_given_roll_items bgri on bndi.bichuv_given_roll_items_id = bgri.id
                                 join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                            WHERE bgr.id = %d;";
                    $sql = sprintf($sql, $model->id);
                    Yii::$app->db->createCommand($sql)->execute();
                    if (!empty($model->bichuvNastelDetails)) {
                        BichuvNastelDetails::deleteAll(['bichuv_given_roll_id' => $model->id]);
                    }
                    if (!empty($model->bichuvGivenRollItems)) {
                        BichuvGivenRollItems::deleteAll(['bichuv_given_roll_id' => $model->id]);
                    }
                    if (!empty($model->modelRelProductions)) {
                        ModelRelProduction::deleteAll(['bichuv_given_roll_id' => $model->id]);
                    }
                    $modelId = $model->id;
                    $productModelId = null;
                    $nastelNo = $model->nastel_party;
                    if (!empty($data['BichuvGivenRollItems'])) {
                        foreach ($data['BichuvGivenRollItems'] as $key => $item) {
                            $saved = false;
                            $modelBGRI = new BichuvGivenRollItems();
                            $modelBGRI->setAttributes([
                                'entity_id' => $item['entity_id'],
                                'quantity' => $item['quantity'],
                                'roll_count' => $item['roll_count'],
                                'required_count' => $item['required_count'],
                                'model_id' => $item['new_model_id'],
                                'mobile_table_id' => $item['mobile_table_id'],
                                'party_no' => $item['party_no'],
                                'musteri_party_no' => $item['musteri_party_no'],
                                'bichuv_detail_type_id' => $item['bichuv_detail_type_id'],
                                'bichuv_given_roll_id' => $modelId,

                            ]);
                            if ($modelBGRI->save()) {
                                $saved = true;
                                if (!empty($item['child'])) {
                                    foreach ($item['child'] as $n => $size) {
                                        if (!empty($size)) {
                                            $new_size = new BichuvNastelDetailItems([
                                                'size_id' => $n,
                                                'required_count' => $size,
                                                'bichuv_given_roll_items_id' => $modelBGRI['id'],
                                            ]);
                                            if ($new_size->save()) {
                                                $saved = true;
                                            } else {
                                                $saved = false;
                                                break;
                                            }
                                        }
                                    }
                                }
                            } else {
                                $saved = false;
                                break;
                            }
                        }
                    }

                    if (!empty($data['BichuvGivenRollItems'])){
                        $checkArray = [];
                        foreach ($data['BichuvGivenRollItems'] as $item){
                            $orderData =  BichuvDocItems::getOrderDataByModelOrdersItemsId($item['model_orders_items_id']);
                            if(!empty($orderData)){
                                $modelRelProd = new ModelRelProduction([
                                    'models_list_id' => $orderData['data']['models_list_id'],
                                    'model_variation_id' => $orderData['data']['model_var_id'],
                                    'bichuv_given_roll_id' => $modelId,
                                    'order_id' => (int)$orderData['data']['model_orders_id'],
                                    'order_item_id' => $item['model_orders_items_id'],
                                    'price' => $orderData['data']['price'],
                                    'pb_id' => $orderData['data']['pb_id'],
                                    'type' => 2,
                                    'nastel_no' => $model->nastel_party
                                ]);
                                if(!empty($checkArray)){
                                    foreach ($checkArray as $checkItem){
                                        if($checkItem['order_item_id'] != $modelRelProd['order_item_id']){
                                            array_push($checkArray,$modelRelProd);
                                        }
                                    }
                                }else{
                                    array_push($checkArray,$modelRelProd);
                                }
                            }
                        }
                        foreach ($checkArray as $item){
                            $isExists = ModelRelProduction::find()->where([
                                'order_id' => $item['order_id'],
                                'order_item_id' => $item['order_item_id'],
                                'bichuv_given_roll_id' => $item['bichuv_given_roll_id']
                            ])->exists();
                            if (!$isExists){
                                if($item->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                    }
                    
                    if ($saved) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id, 't' => $t]);
                    }
                }
            } catch (Exception $e) {
                Yii::info('Not saved' . $e, 'save');
            }
        }
        $modelListId = null;
        $modelOrderId = null;
        $modelVarList = [];
        $modelVarData = [];
        $modelVarData['data'] = [];
        $modelVarData['dataPart'] = [];
        $modelVarData['dataPartAttr'] = [];
        $modelVarData['dataAttr'] = [];
        $modelProdArr = $model->modelRelProductions;
        $dataOrderItem = [];
        $dataModelVarPart = [];
        if(!empty($modelProdArr)){
            foreach ($modelProdArr as $item) {
                $dataOrderItem[$item->order_item_id]['mv'] = $item->model_variation_id;
                $dataOrderItem[$item->order_item_id]['price'] = $item->price;
                $dataOrderItem[$item->order_item_id]['pb_id'] = $item->pb_id;
                $dataOrderItem[$item->order_item_id]['type'] = $item->type;
                $dataOrderItem[$item->order_item_id]['model_var_part_id'] = $item->model_var_part_id;
                $modelListId = $item->models_list_id;
                $modelOrderId = $item->order_id;
                $dataModelVarPart[$item->model_var_part_id] = $item->model_var_part_id;
                array_push($modelVarList,$item->model_variation_id);
            }
            $modelVarData = $model::getOrderItemList($modelListId, $modelOrderId, true, true);
            $modelVarPartData = $model::getModelVarParts($modelVarList,true);
            $model->cp['dataPart'] = $modelVarPartData['data'];
            $model->cp['dataPartAttr'] = $modelVarPartData['dataAttr'];
        }
        $model->model_list_id = $modelListId;
        $model->model_var_part_id = $dataModelVarPart;
        $model->model_var_id = $modelVarList;
        $model->order_id = $modelOrderId;
        $model->order_item_id = $dataOrderItem;
        $model->cp['data'] = $modelVarData['data'];
        $model->cp['dataAttr'] = $modelVarData['dataAttr'];
        return $this->render('update', [
            'model' => $model,
            'models' => $models,
            'modelBD' => $modelBD,
            'modelNastel' => $modelNastel,
            'modelNastelItems' => $modelNastelItems,
            'modelsAcs' => $modelsAcs,
        ]);
    }

    /**
     * @param $id
     * @return array
     */
    public function actionGetSizeCollection($id)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $sizes = Size::find()->joinWith(['sizeColRelSizes'])->where(['size_col_rel_size.sc_id' => $id])->asArray()->all();
        $result = [];
        $result['status'] = false;
        $result['message'] = 'Error';
        if (!empty($sizes)) {
            $out = $this->renderAjax('_nastel_items', ['sizes' => $sizes]);
            $result['status'] = true;
            $result['result'] = $out;
            $result['message'] = 'OK';
        }
        return $result;
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
        $t = Yii::$app->request->get('t', 1);
        if($model->status>$model::STATUS_ACTIVE){
            return $this->redirect(['view', 'id' => $id, 't' => $t]);
        }
        $sql = "delete bndi from bichuv_nastel_detail_items bndi
                                 join bichuv_given_roll_items bgri on bndi.bichuv_given_roll_items_id = bgri.id
                                 join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                            WHERE bgr.id = %d;";
        $sql = sprintf($sql, $model->id);
        Yii::$app->db->createCommand($sql)->execute();
        $items = $model->bichuvGivenRollItems;
        $details = $model->bichuvNastelDetails;
        if (!empty($details)) {
            foreach ($details as $detail) {
                $detail->delete();
            }
        }
        if (!empty($items)) {
            foreach ($items as $item) {
                $item->delete();
            }
        }
        $itemAccepted = $model->bichuvAcceptedMatoFromProduction;
        if (!empty($itemAccepted)) {
            foreach ($itemAccepted as $item) {
                $item->delete();
            }
        }
        if (!empty($model->modelRelProductions)) {
            foreach ($model->modelRelProductions as $detail) {
                $detail->delete();
            }
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionExportExcel()
    {
        header('Content-Type: application/vnd.ms-excel');
        $searchModel = new BichuvGivenRollsSearch();
        $dataProvider = $searchModel->searchItems(Yii::$app->request->queryParams);
        $filename = "bichuv-given-rolls_" . date("d-m-Y-His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => $dataProvider->getModels(),
            'columns' => [
                'id',
                'bichuvGivenRoll.reg_date',
                'bichuvGivenRoll.nastel_party',
                'party_no',
                'musteri_party_no',
                'bichuvGivenRoll.musteri.name',
                [
                    'attribute' => 'bichuvGivenRoll.model_list_id',
                    'value' => function($m){
                        $data = $m->bichuvGivenRoll->getModelListInfo();
                        return $data['model'];
                    }
                ],
                [
                    'attribute' => 'bichuvGivenRoll.model_var_id',
                    'value' => function($m){
                        $data = $m->bichuvGivenRoll->getModelListInfo();
                        return $data['model_var'];
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'bichuvGivenRoll.model_name',
                    'value' => function($m){
                        $data = $m->bichuvGivenRoll->getModelListInfo();
                        return $data['model_name'];
                    },
                ],
                [
                    'attribute' => 'bichuvGivenRoll.customer_id',
                    'value' => function($m){
                        return $m->bichuvGivenRoll->customer->name;
                    },
                ],
                [
                    'attribute' => 'entity_id',
                    'value' => function($m){
                        return $m->getMatoName($m->entity_id);
                    },
                ],
                'roll_count',
                'quantity',
                'required_count',
                'bichuvGivenRoll.add_info'
            ],
            'headers' => [
                'id' => 'Id',
            ],
            'autoSize' => true,
        ]);
    }

    /**
     * @param $party
     * @param int $t
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionGetRmInfo($party, $t = 1)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        $response['message'] = Yii::t('app', "Bunday mato ro'yxatga avval qo'shilgan yoki mavjud emas");
        $data = Yii::$app->request->post();
        $res = BichuvGivenRolls::getRemains($party, $t, $data);
        if (!empty($res)) {
            $response['status'] = 1;
            $response['items'] = $res;
        }
        return $response;
    }

    /**
     * @param $id
     * @param int $t
     * @return Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSaveAndFinish($id, $t = 1)
    {
        $model = $this->findModel($id);
        $items = $model->getBichuvGivenRollItems()->where(['bichuv_given_roll_items.entity_type' => 1])->all();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $modelId = $model->id;
            $musteriId = $model->musteri_id;
            $nastel = $model->nastel_party;
            $saved = false;
            $userId = Yii::$app->user->id;
            $dept = UsersHrDepartments::find()->where(['user_id' => $userId])->asArray()->one();
            $deptId = null;
            if (!empty($dept)) {
                $deptId = $dept['hr_departments_id'];
                $modelOrderItemId = "";
                foreach ($items as $item) {
                    $modelOrderItemId = $item['model_orders_items_id'];
                    $remain = BichuvRmItemBalance::getLastRecord($item, $musteriId);
                    if (!empty($remain)) {
                        if (($remain['inventory'] - $item['quantity']) < 0 || ($remain['roll_inventory'] - $item['roll_count']) < 0) {
                            $lack_qty = $item['quantity'] - $remain['inventory'];
                            $lack_roll = $item['roll_count'] - $remain['roll_inventory'];
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Sizda {rm} kg mato va {roll} dona rulon yetishmayapti',
                                ['rm' => $lack_qty, 'roll' => $lack_roll]));
                            return $this->redirect(['view', 'id' => $id, 't' => $t]);
                        }
                    }
                }

                $mobileTableId = MobileTables::findOne(['token' => Constants::TOKEN_BICHUV_PRODUCTION_MATO])->id;

                if(!empty($mobileTableId)){
                    $paramMobileProcessProduction = [
                        'nastel_no' => $model['nastel_party'],
                        'started_date' => date("d.m.Y H:i:s"),
                        'ended_date' => date("d.m.Y H:i:s"),
                        'status' => MobileProcessProduction::STATUS_ENDED,
                        'mobile_tables_id' => $mobileTableId,
                        'doc_id' => $model['id'],
                        'table_name' => BichuvGivenRolls::getTableSchema()->name,
                        'model_orders_items_id' => $modelOrderItemId
                    ];
                    $parentProcess = MobileProcessProduction::saveMobileProcess($paramMobileProcessProduction);
                    if($parentProcess){
                        $i = 1;
                        foreach ($items as $key => $item) {
                            $details = ModelOrdersItems::getDetailTypes($item['model_orders_items_id'],$item['bichuv_detail_type_id']);
                            if ($details){
                                foreach ($details as $detail){
                                    //item balancedan tekwiriw
                                    $lastRec = BichuvRmItemBalance::getLastRecord($item, $musteriId);
                                    $inventory = $item['quantity'];
                                    $rollInventory = $item['roll_count'];
                                    if ($lastRec) {
                                        $inventory = $lastRec['inventory'] - $item['quantity'];
                                        $rollInventory = $lastRec['roll_inventory'] - $item['roll_count'];
                                        if ($rollInventory < 1 && $inventory > 0) {
                                            $rollInventory = 1;
                                        }
                                    }
                                    $modelBRIB = new BichuvRmItemBalance();
                                    $modelBRIB->setAttributes([
                                        'entity_id' => $item['entity_id'],
                                        'doc_type' => 2,
                                        'inventory' => $inventory,
                                        'count' => (-1) * $item['quantity'],
                                        'roll_inventory' => $rollInventory,
                                        'roll_count' => (-1) * $item['roll_count'],
                                        'from_hr_department' => $deptId,
                                        'hr_department_id' => $deptId,
                                        'to_hr_department' => $deptId,
                                        'is_inside' => 2,
                                        'from_musteri' => $model->musteri_id,
                                        'bichuv_given_roll_id' => $modelId,
                                        'party_no' => $item['party_no'],
                                        'musteri_party_no' => $item['musteri_party_no'],
                                        'model_id' => $item['model_id'],
                                        'nastel_no' => $nastel
                                    ]);

                                    $paramsMobileProcessProduction = [
                                        'nastel_no' => $nastel."-{$i}",
                                        'started_date' => date("d.m.Y H:i:s"),
                                        'ended_date' => date("d.m.Y H:i:s"),
                                        'status' => MobileProcessProduction::STATUS_ENDED,
                                        'mobile_tables_id' => $item['mobile_table_id'],
                                        'doc_items_id' => $item['id'],
                                        'doc_id' => $modelId,
                                        'table_name' => BichuvGivenRollItems::getTableSchema()->name,
                                        'parent_id' => $parentProcess['id'],
                                        'bichuv_detail_type_id' => $item['bichuv_detail_type_id'],
                                        'model_orders_items_id' => $item['model_orders_items_id'],
                                        'base_detail_list_id' => $detail['bdl_id']
                                    ];
                                    if ($modelBRIB->save() && MobileProcessProduction::saveMobileProcess($paramsMobileProcessProduction)) {
                                        $saved = true;
                                        $i++;
                                    } else {
                                        $saved = false;
                                        break 2;
                                    }
                                }

                            }else{
                                $modelNo = $item->modelOrderItem->modelsList->article;
                                $modelListId = $item->modelOrderItem->models_list_id;
                                Yii::$app->session->setFlash("error",Yii::t('app','{model} model uchun qolip biriktirilmagan. <a target="_blank" href="{url}">Qolip biritirish</a>',['model' => $modelNo, 'url' => Url::to(["/base/models-list/update?id={$modelListId}"])]));
                                break;
                            }
                        }
                    }else{
                        Yii::$app->session->setFlash("error",Yii::t('app','Saqlashda xatolik!'));
                    }
                }else{
                    Yii::$app->session->setFlash("error",Yii::t('app','Not Mobile Table!'));
                }
            }
            if ($saved) {
                $nastelId = BichuvNastelLists::find()->where(['name' => $model['nastel_party']])->scalar();
                $BTRWD = BichuvTableRelWmsDoc::getBichuvTableRelWmsDocByNastelId($nastelId,BichuvTableRelWmsDoc::STATUS_STARTED);
                if($BTRWD){
                    $model->status = 3;
                    $model->model_var_id = $model->modelRelProductions[0]->model_variation_id;
                    $model->model_list_id = $model->modelRelProductions[0]->models_list_id;
                    if($model->save()){
                        Yii::$app->session->setFlash('success',Yii::t('app','Saqlandi'));
                        $transaction->commit();
                    }
                }

            } else {
                $transaction->rollBack();
            }
        } catch (Exception $e) {
            Yii::info('Not changed status to 3'.$e->getMessage(), 'save');
        }
        return $this->redirect(['view', 'id' => $id, 't' => $t]);
    }

    public function actionNastelDoc($id)
    {

    }

    public function actionSliceMoving(){
        return $this->render('slice-moving');
    }

    /**
     * Finds the BichuvGivenRolls model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BichuvGivenRolls the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BichuvGivenRolls::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
