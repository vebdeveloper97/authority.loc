<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 15.12.19 21:35
 */

namespace app\modules\toquv\controllers;

use app\modules\toquv\models\MatoInfo;
use app\modules\toquv\models\Musteri;
use app\modules\toquv\models\ToquvInstructionRm;
use app\modules\toquv\models\ToquvInstructionsSearch;
use app\modules\toquv\models\ToquvPusFine;
use app\modules\toquv\models\ToquvRawMaterialConsist;
use app\modules\toquv\models\ToquvRawMaterialIp;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\ToquvRmOrder;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvInstructionItems;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvOrdersSearch;
use app\modules\toquv\models\ToquvInstructions;
/**
 * ToquvAksInstructionsController implements the CRUD actions for ToquvInstructions model.
 */
class ToquvAksInstructionsController extends BaseController
{
    public $type = ToquvRawMaterials::ACS;
    /**
     * Lists all ToquvInstructions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'instruction', $this->type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionModelOrders()
    {
        $searchModel = new ToquvOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'instruction-model', $this->type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @param null $orderId
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionView($id, $orderId = null)
    {
        $items   = ToquvInstructions::getInstructionViewData($orderId, $id);
        $rmItems = ToquvInstructions::getRawMaterials($id, $orderId, true);
        $insDoc = !empty($items[0])?$items[0]:null;
        $kaliteData =  ToquvInstructions::getKaliteRM($id);

        return $this->render('view',[
            'items'      => $items,
            'insDoc'     => $insDoc,
            'model'      => $this->findModel($id),
            'orderId'    => $orderId,
            'rmItems'    => $rmItems,
            'kaliteData' => $kaliteData
        ]);
    }

    /**
     * @param null $id
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate($id = null)
    {
        $order = ToquvOrders::findOne($id);
        if($order === null){
            return $this->redirect(['index']);
        }
        $modelInstruction = new ToquvInstructions();
        $items = ToquvInstructions::getOrderInfo($id);
        $rmItems = ToquvInstructions::getRawMaterials($id);
        $pusFine = ToquvPusFine::getPusFineList(2);
        $modelInstruction->cp['pus_fines'] = ArrayHelper::map($pusFine, 'id','name');
        $data = Yii::$app->request->post();
        $isSaved = false;
        if(Yii::$app->request->isPost && !empty($data)){
            $modelTI = new ToquvInstructions();
            $modelTI->type = ToquvRawMaterials::ACS;
            if($modelTI->load($data)){
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($modelTI->save()) {
                        $insId = $modelTI->id;
                        if (!empty($data['ItemsRM'])) {
                            foreach ($data['ItemsRM'] as $item) {
                                $isSaved = false;
                                if ($item['quantity'] > 0) {
                                    $modelTIR = new ToquvInstructionRm();
                                    $modelTIR->setAttributes([
                                        'toquv_instruction_id' => $modelTI->id,
                                        'toquv_rm_order_id' => $item['toquv_rm_order_id'],
                                        'toquv_pus_fine_id' => $item['toquv_pus_fine_id'],
                                        'type_weaving' => $item['type_weaving'],
                                        'thread_length' => $item['thread_length'],
                                        'finish_en' => $item['finish_en'],
                                        'finish_gramaj' => $item['finish_gramaj'],
                                        'quantity' => $item['quantity'],
                                        'moi_id' => $item['moi_id'],
                                    ]);
                                    if ($modelTIR->save()) {
                                        if (!empty($item['child'])) {
                                            foreach ($item['child'] as $ip) {
                                                $isSaved = false;
                                                if ($ip['quantity'] > 0) {
                                                    $modelTII = new ToquvInstructionItems();
                                                    $modelTII->setAttributes([
                                                        'entity_id' => $ip['entity_id'],
                                                        'quantity' => $ip['quantity'],
                                                        'fact' => $ip['fact'],
                                                        'entity_type' => ToquvInstructionItems::ENTITY_TYPE_IP,
                                                        'toquv_instruction_id' => $modelTI->id,
                                                        'rm_item_id' => $ip['rm_item_id'],
                                                        'add_info' => $ip['add_info'],
                                                        'thread_name' => $ip['thread_name'],
                                                        'lot' => $ip['lot'],
                                                        'musteri_id' => $ip['musteri_id'],
                                                        'is_own' => $ip['is_own'],
                                                        'percentage' => $ip['percentage'],
                                                        'toquv_ne' => $ip['toquv_ne'],
                                                        'toquv_thread' => $ip['toquv_thread'],
                                                        'toquv_ip_color' => $ip['toquv_ip_color'],
                                                        'toquv_instruction_rm_id' => $modelTIR->id
                                                    ]);
                                                    if ($modelTII->save()) {
                                                        $isSaved = true;
                                                    }else{
                                                        $isSaved = false;
                                                        Yii::error('Not saved ti.aks model tii '. $modelTII->getErrors(), 'save');
                                                        break 2;
                                                    }
                                                }
                                            }
                                        }
                                    }else{
                                        $isSaved = false;
                                        Yii::error('Not saved ti.aks model tir '. $modelTIR->getErrors(), 'save');
                                        break;
                                    }
                                }
                            }
                        }
                    }else{
                        $isSaved = false;
                        Yii::error('Not saved ti.aks model ti '. $modelTI->getErrors(), 'save');
                    }
                    if($isSaved && !empty($insId)){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success',"Ko'rstakich muvaffaqiyatli saqlandi!");
                        return $this->redirect(['view', 'id' => $insId, 'orderId' => $id]);
                    }else{
                        $transaction->rollBack();
                    }
                }catch (\Exception $e){
                    Yii::info('Not saved t.aks ins ' . $e, 'save');
                }
            }
        }
        return $this->render('create', [
            'order' => $order,
            'orderId' => $id,
            'model' => $modelInstruction,
            'items' => $items,
            'rmItems' => $rmItems
        ]);
    }
    public function actionUniversal()
    {
        $modelInstruction = new ToquvInstructions();
        $models = [new ToquvInstructionRm()];
        $pusFine = ToquvPusFine::getPusFineList(2);
        $modelInstruction->cp['pus_fines'] = ArrayHelper::map($pusFine, 'id','name');
        $data = Yii::$app->request->post();
        if(Yii::$app->request->isPost && !empty($data)){
            if($res = $modelInstruction->saveItems($data)){
                Yii::$app->session->setFlash('success',"Ko'rstakich muvaffaqiyatli saqlandi!");
                return $this->redirect(['view', 'id' => $res['insId'], 'orderId' => $res['ordId']
                ]);
            }
        }
        return $this->render('universal', [
            'model' => $modelInstruction,
            'models' => $models,
            'order' => new ToquvOrders(),
        ]);
    }
    public function actionUpdateUniversal($id,$orderId)
    {
        $modelInstruction = ToquvInstructions::findOne($id);
        $models = ($modelInstruction->toquvInstructionRms)?$modelInstruction->toquvInstructionRms:[new ToquvInstructionRm()];
        $items = ToquvInstructions::getOrderInfo($orderId);
        $pusFine = ToquvPusFine::getPusFineList(2);
        $modelInstruction->cp['pus_fines'] = ArrayHelper::map($pusFine, 'id','name');
        $data = Yii::$app->request->post();
        if(Yii::$app->request->isPost && !empty($data)){
            $modelInstruction = ToquvInstructions::findOne($id);
            if($modelInstruction){
                if($modelInstruction->toquvInstructionItems){
                    ToquvInstructionItems::deleteAll(['toquv_instruction_id'=>$modelInstruction->id]);
                }
                $modelInstruction->delete();
            }
            $orders = ToquvOrders::findOne($orderId);
            if($orders){
                if($orders->toquvRmOrders){
                    ToquvRmOrder::deleteAll(['toquv_orders_id'=>$orders->id]);
                }
                $orders->delete();
            }
            $instruction = new ToquvInstructions();
            if($res = $instruction->saveItems($data)){
                Yii::$app->session->setFlash('success',"Ko'rstakich muvaffaqiyatli saqlandi!");
                return $this->redirect(['view', 'id' => $res['insId'], 'orderId' => $res['ordId']
                ]);
            }
        }
        return $this->render('update-universal', [
            'model' => $modelInstruction,
            'models' => $models,
            'order' => new ToquvOrders(),
            'items' => $items,
            'samo' => $samo = Musteri::findOne(['token'=>'SAMO'])->name
        ]);
    }
    public function actionUpdateModelOrders($id,$orderId)
    {
        $modelInstruction = ToquvInstructions::findOne($id);
        $models = ($modelInstruction->toquvInstructionRms)?$modelInstruction->toquvInstructionRms:[new ToquvInstructionRm()];
        $items = ToquvInstructions::getOrderInfo($orderId);
        $pusFine = ToquvPusFine::find()->asArray()->all();
        $modelInstruction->cp['pus_fines'] = ArrayHelper::map($pusFine, 'id','name');
        $data = Yii::$app->request->post();
        if(Yii::$app->request->isPost && !empty($data)){
            $modelInstruction = ToquvInstructions::findOne($id);
            if($modelInstruction){
                if($modelInstruction->toquvInstructionItems){
                    ToquvInstructionItems::deleteAll(['toquv_instruction_id'=>$modelInstruction->id]);
                }
                $modelInstruction->delete();
            }
            $orders = ToquvOrders::findOne($orderId);
            if($orders){
                if($orders->toquvRmOrders){
                    ToquvRmOrder::deleteAll(['toquv_orders_id'=>$orders->id]);
                }
                $orders->delete();
            }
            $instruction = new ToquvInstructions();
            if($res = $instruction->saveItems($data)){
                Yii::$app->session->setFlash('success',"Ko'rstakich muvaffaqiyatli saqlandi!");
                return $this->redirect(['view', 'id' => $res['insId'], 'orderId' => $res['ordId']
                ]);
            }
        }
        return $this->render('update-model-orders', [
            'model' => $modelInstruction,
            'models' => $models,
            'model_orders_id' => $modelInstruction->model_orders_id,
        ]);
    }
    /*public function actionCreateModelOrders($id)
    {
        $modelInstruction = new ToquvInstructions();
        $model_orders = ToquvInstructions::getModelOrdersList($id);
        $models = ToquvInstructions::getModelOrders($id);
        $pusFine = ToquvPusFine::find()->asArray()->all();
        $modelInstruction->cp['pus_fines'] = ArrayHelper::map($pusFine, 'id','name');
        $data = Yii::$app->request->post();
        if(Yii::$app->request->isPost && !empty($data)){
            if($res = $modelInstruction->saveItems($data)){
                Yii::$app->session->setFlash('success',"Ko'rstakich muvaffaqiyatli saqlandi!");
                return $this->redirect(['view', 'id' => $res['insId'], 'orderId' => $res['ordId']
                ]);
            }
        }
        return $this->render('create-model-orders', [
            'model' => $modelInstruction,
            'models' => $models,
            'order' => new ToquvOrders(),
            'model_orders' => $model_orders,
            'model_orders_id' => $id
        ]);
    }*/
    public function actionRmItems($id,$kg,$count)
    {
        if (Yii::$app->request->isAjax) {
            if(!empty($id)&&$id!=0){
                $consist = ToquvRawMaterialConsist::find()->where(['fabric_type_id' => 3,'raw_material_id' => $id])->one();
                $service = ($consist)?0.35:0.25;
                $model = ToquvRawMaterialIp::find()->where(['toquv_raw_material_id'=>$id])->all();
                $samo = Musteri::findOne(['token'=>'SAMO'])->name;
                if(count($model)>0){
                    return $this->renderAjax('rm-items', [
                        'model' => $model,
                        'kg' => $kg,
                        'service' => $service,
                        'count' => $count,
                        'samo' => $samo
                    ]);
                }
            }else{
                return false;
            }
        }
    }
    /**
     * @param $id
     * @param $orderId
     * @return string|Response
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id, $orderId)
    {
        $order = ToquvOrders::findOne(['id' => $orderId]);
        if($order === null){
            return $this->redirect(['index']);
        }
        $modelInstruction = ToquvInstructions::findOne($id);
        $items = $items = ToquvInstructions::getInstructionViewData($orderId, $id,'update');
        $rmItems = ToquvInstructions::getRawMaterials($id, $orderId, true);
        $pusFine = ToquvPusFine::getPusFineList(2);
        $modelInstruction->cp['pus_fines'] = ArrayHelper::map($pusFine, 'id','name');
        $data = Yii::$app->request->post();
        $isSaved = false;
        if(Yii::$app->request->isPost && !empty($data)){
            $modelTI = $modelInstruction;
            if($modelTI->load($data)){
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($modelTI->save()) {
                        $insId = $modelTI->id;
                        $isDeletedChild = true;
                        if (!empty($modelTI->toquvInstructionItems)) {
                            if(ToquvInstructionItems::deleteAll(['toquv_instruction_id'=>$modelTI->id])){

                            }else{
                                $isDeletedChild = false;
                            }
                        }
                        if (!empty($modelTI->toquvInstructionRms)&&$isDeletedChild) {
                            if(ToquvInstructionRm::deleteAll(['toquv_instruction_id'=>$modelTI->id])){

                            }else{
                                $isDeletedChild = false;
                            }
                        }
                        if($isDeletedChild) {
                            if (!empty($data['ItemsRM'])) {
                                foreach ($data['ItemsRM'] as $item) {
                                    $isSaved = false;
                                    $modelTIR = new ToquvInstructionRm();
                                    $modelTIR->setAttributes([
                                        'toquv_instruction_id' => $modelTI->id,
                                        'toquv_rm_order_id' => $item['toquv_rm_order_id'],
                                        'toquv_pus_fine_id' => $item['toquv_pus_fine_id'],
                                        'type_weaving' => $item['type_weaving'],
                                        'thread_length' => $item['thread_length'],
                                        'finish_en' => $item['finish_en'],
                                        'finish_gramaj' => $item['finish_gramaj'],
                                        'quantity' => $item['quantity'],
                                        'moi_id' => $item['moi_id'],
                                    ]);
                                    if ($modelTIR->save()) {
                                        if (!empty($item['child'])) {
                                            foreach ($item['child'] as $ip) {
                                                $isSaved = false;
                                                $modelTII = new ToquvInstructionItems();
                                                $modelTII->setAttributes([
                                                    'entity_id' => $ip['entity_id'],
                                                    'quantity' => $ip['quantity'],
                                                    'fact' => $ip['fact'],
                                                    'entity_type' => ToquvInstructionItems::ENTITY_TYPE_IP,
                                                    'toquv_instruction_id' => $modelTI->id,
                                                    'rm_item_id' => $ip['rm_item_id'],
                                                    'add_info' => $ip['add_info'],
                                                    'thread_name' => $ip['thread_name'],
                                                    'lot' => $ip['lot'],
                                                    'musteri_id' => $ip['musteri_id'],
                                                    'is_own' => $ip['is_own'],
                                                    'percentage' => $ip['percentage'],
                                                    'toquv_ne' => $ip['toquv_ne'],
                                                    'toquv_thread' => $ip['toquv_thread'],
                                                    'toquv_ip_color' => $ip['toquv_ip_color'],
                                                    'toquv_instruction_rm_id' => $modelTIR->id
                                                ]);
                                                if ($modelTII->save()) {
                                                    $isSaved = true;
                                                } else {
                                                    $isSaved = false;
                                                    Yii::error('Not saved ti.aks model tii ' . $modelTII->getErrors(), 'save');
                                                    break 2;
                                                }
                                            }
                                        }
                                    } else {
                                        $isSaved = false;
                                        Yii::error('Not saved ti.aks model tir ' . $modelTIR->getErrors(), 'save');
                                        break;
                                    }
                                }
                            }
                        }else{
                            $isSaved = false;
                            Yii::error('Not deleted tir or tii '.Yii::$app->user->id, 'save');
                        }
                    }else{
                        $isSaved = false;
                        Yii::error('Not saved ti.aks model tir '. $modelTI->getErrors(), 'save');
                    }
                    if($isSaved && !empty($insId)){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success',"Ko'rstakich muvaffaqiyatli saqlandi!");
                        return $this->redirect(['view', 'id' => $insId, 'orderId' => $orderId]);
                    }else{
                        $transaction->rollBack();
                    }
                }catch (\Exception $e){
                    Yii::error('Not saved t.aks ins ' . $e, 'save');
                }
            }
        }
        return $this->render('update', [
            'orderId'   => $orderId,
            'model'     => $modelInstruction,
            'items'     => $items,
            'rmItems'   => $rmItems,
        ]);
    }

    /**
     * @param $id
     * @param $orderId
     * @return Response
     */
    public function actionSaveAndFinish($id,$orderId){
        $instruction = ToquvInstructions::findOne($id);
        $response = [];
        $response['status'] = 0;
        $response['message'] = 'error';
        $transaction = Yii::$app->db->beginTransaction();
        $saved = true;
        try {
            if($instruction !== null){
                if($instruction->status < $instruction::STATUS_INACTIVE){
                    $instruction->status = $instruction::STATUS_SAVED;
                    if($instruction->toquvInstructionRms){
                        foreach ($instruction->toquvInstructionRms as $item){
                            $item->status = $instruction::STATUS_SAVED;
                            if($item->save()){
                                $saved = true;
                                $mato = MatoInfo::findOne([
                                    'entity_id' => $item->toquvRmOrder->toquv_raw_materials_id,
                                    'entity_type' => ToquvDocuments::ENTITY_TYPE_ACS,
                                    'pus_fine_id' => $item->toquv_pus_fine_id,
                                    'musteri_id' => $item->toquvRmOrder->toquvOrders->musteri_id,
                                    'model_musteri_id' => $item->toquvRmOrder->toquvOrders->model_musteri_id,
                                    'thread_length' => $item->thread_length,
                                    'finish_en' => $item->finish_en,
                                    'finish_gramaj' => $item->finish_gramaj,
                                    'toquv_rm_order_id' => $item->toquv_rm_order_id,
                                    'model_musteri_id' => $item->toquvRmOrder->toquvOrders->model_musteri_id,
                                ]);
                                if (!$mato) {
                                    $mato = new MatoInfo([
                                        'entity_id' => $item->toquvRmOrder->toquv_raw_materials_id,
                                        'entity_type' => ToquvDocuments::ENTITY_TYPE_ACS,
                                        'pus_fine_id' => $item->toquv_pus_fine_id,
                                        'toquv_rm_order_id' => $item->toquv_rm_order_id,
                                        'musteri_id' => $item->toquvRmOrder->toquvOrders->musteri_id,
                                        'toquv_instruction_id' => $item->toquv_instruction_id,
                                        'toquv_instruction_rm_id' => $item->id,
                                        'model_musteri_id' => $item->toquvRmOrder->toquvOrders->model_musteri_id,
                                        'thread_length' => $item->thread_length,
                                        'finish_en' => $item->finish_en,
                                        'finish_gramaj' => $item->finish_gramaj,
                                    ]);
                                    $mato->save();
                                }
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                    if($instruction->save()&&$saved){
                        $saved = true;
                    }else{
                        $saved = false;
                    }
                }
            }
            if($saved) {
                $response['status'] = 1;
                $response['message'] = "OK";
                $transaction->commit();
            }else{
                Yii::$app->session->setFlash('error',Yii::t('app','Xatolik yuz berdi!'));
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            Yii::info('Not saved' . $e, 'save');
            $transaction->rollBack();
        }
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }
        if($instruction === null){
            Yii::$app->session->setFlash('error',Yii::t('app','Xatolik yuz berdi!'));
            return $this->redirect(['index']);
        }
        /*if($instruction->toquvInstructionRms){
            foreach ($instruction->toquvInstructionRms as $item){
                $item->updateCounters(['status' => 2]);
            }
        }
        $instruction->updateCounters(['status' => 2]);*/
        return $this->redirect(['view','id' => $id, 'orderId' => $orderId]);
    }

    /**
     * @return array
     */
    public function actionCloseInstructions(){

        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $response = ['status' => 0, 'message' => "Error"];
        if(!empty($data) && !empty($data['ids'])){
            $instructions = ToquvInstructionRm::findAll(explode(',', $data['ids']));
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($instructions as $item) {
                    $item->toquvInstruction->is_closed = 2;
                    $item->is_closed = 2;
                    $item->toquvInstruction->save();
                    $item->save();
                }
                $transaction->commit();
                $response = ['status' => 1, 'message' => 'OK'];
            } catch (Exception $e) {
                $response = ['status' => 0, 'message' => "Error"];
            }
        }
        return $response;
    }
    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!empty($model->toquvInstructionItems)){
            foreach ($model->toquvInstructionItems as $item){
                $item->delete();
            }
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionInstructionList(){
        $searchModel = new ToquvInstructionsSearch();
        $type = ToquvRawMaterials::ACS;
        $dataProvider = $searchModel->searchInstructionList(Yii::$app->request->queryParams,$type);
        return $this->render('instruction-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionAjax($id){
        $sql = "select m.name as mname,
                       ti.status,
                       ti.id,
                       tir.id as rmid, 
                       t.id as orderId, 
                       t.document_number,
                       ti.reg_date,
                       tpf.name as pus_fine, 
                       t.done_date,
                       tir.quantity,
                       tir.finish_en,
                       tir.finish_gramaj,
                       tir.thread_length,
                       trm.name as mato
                from toquv_orders t
                         left join toquv_instructions ti on t.id = ti.toquv_order_id
                         left join musteri m on t.musteri_id = m.id
                         left join toquv_instruction_rm tir on ti.id = tir.toquv_instruction_id
                         left join toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                         left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                         left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                where t.id = :id;";
        $items = Yii::$app->db->createCommand($sql)->bindValue('id', $id)->queryAll();
        return $this->renderAjax('each-instruction',['items' => $items]);
    }
    public function actionNewThread($id,$order,$key,$acs=null){
        $tro = ($acs)?" and tro.status = 3":"";
        $sql = "select 
                    tor.id,
                    tn.id as neid,
                    tt.id as ttid,
                    troi.id as troi_id,
                    m.id as mid,  
                    m.name as ca,
                    tn.name as nename,
                    tt.name as thrname,
                    trm.name as mato,
                    tro.quantity as qty,
                    troi.own_quantity as own_qty,
                    troi.their_quantity as their_qty,
                    troi.percentage percentage,
                    tro.id tro_id,
                    m2.name order_musteri,
                    tro.moi_id
                from toquv_orders tor
                     left join toquv_rm_order tro on tor.id = tro.toquv_orders_id
                     left join musteri m on tor.musteri_id = m.id
                     left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                     left join toquv_rm_order_items troi on tro.id = troi.toquv_rm_order_id
                     left join toquv_ne tn on troi.toquv_ne_id = tn.id
                     left join toquv_thread tt on troi.toquv_thread_id = tt.id
                     LEFT JOIN model_orders_items moi ON tro.moi_id = moi.id
                     LEFT JOIN model_orders mo ON moi.model_orders_id = mo.id
                     LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                where tro.id = %d and troi.id = %d %s
                ORDER BY tro.id ASC;";
        $sql = sprintf($sql,$order,$id,$tro);
        $item = Yii::$app->db->createCommand($sql)->queryOne();
        return $this->renderAjax('newThread',[
            'item' => $item,
            'key' => $key
        ]);
    }
    public function actionNewThreadUniversal($id,$kg,$count,$key)
    {
        if (Yii::$app->request->isAjax) {
            if(!empty($id)&&$id!=0){
                $model = ToquvRawMaterialIp::findOne($id);
                $samo = Musteri::findOne(['token'=>'SAMO'])->name;
                if(count($model)>0){
                    return $this->renderAjax('new-thread-universal', [
                        'item' => $model,
                        'kg' => $kg,
                        'count' => $count,
                        'samo' => $samo,
                        'key' => $key,
                    ]);
                }
            }else{
                return false;
            }
        }
        return false;
    }
    /**
     * @param null $id
     * @param string $action
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionInstructions($id = null, $action = 'create'){
        $order = ToquvOrders::findOne($id);
        if($order === null){
            return $this->redirect(['index']);
        }
        $modelInstruction = new ToquvInstructions();
        $items = ToquvInstructions::getOrderInfo($id);
        $data = Yii::$app->request->post();
        $isSaved = false;
        if(Yii::$app->request->isPost && !empty($data)){
            $modelTI = new ToquvInstructions();
            if($modelTI->load($data) && $modelTI->save()){
                $insId = $modelTI->id;
                if(!empty($data['Items'])){
                    foreach ($data['Items'] as $item){
                        $isSaved = false;
                        $modelTII = new ToquvInstructionItems();
                        $modelTII->setAttributes([
                            'entity_id'            => $item['entity_id'],
                            'quantity'             => $item['quantity'],
                            'fact'                 => $item['fact'],
                            'entity_type'            => ToquvInstructionItems::ENTITY_TYPE_IP,
                            'toquv_instruction_id' => $modelTI->id,
                            'add_info'             => $item['add_info'],
                            'thread_name'          => $item['thread_name']
                        ]);
                        if($modelTII->save()){
                            $isSaved = true;
                        }
                    }
                }
            }
            if($isSaved && !empty($insId)){

                return $this->redirect('view',[
                   'id' => $insId,
                   'orderId' => $id
                ]);
            }
        }
        return $this->render('instructions', [
            'orderId' => $id,
            'action' => $action,
            'model' => $modelInstruction,
            'items' => $items
        ]);
    }

    /**
     * @param $q
     * @param $ne
     * @param $thr
     * @param $isOwn
     * @param $mid
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionGetBelongToThread($q, $ne, $thr, $isOwn, $mid){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['results'] = [];
        if (!empty($q)) {
            $params = [];
            $params['query'] = $q;
            $params['is_own'] = $isOwn;
            $params['musteri'] = $mid;
            $params['ne'] = $ne;
            $params['thr'] = $thr;
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchEntityInstruction($params);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = "{$item['ipname']}-{$item['nename']} - {$item['thrname']} - {$item['clname']} ({$item['lot']})";
                    array_push($response['results'], [
                        'id' => $item['entity_id'],
                        'text' => $name,
                    ]);
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

    /**
     * Finds the ToquvInstructions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvInstructions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvInstructions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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
            $searchModel = new ToquvDocuments();
            $res = $searchModel->searchEntities($params);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = "{$item['ipname']}-{$item['nename']} - {$item['thrname']} - {$item['clname']} ({$item['lot']})";
                    array_push($response['results'], [
                        'id' => $item['entity_id'],
                        'text' => $name,
                        'summa' => $item['summa'],
                        'tib_id' => $item['id'],
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

    /**
     * @param null $id
     * @return array|null
     * @throws Exception
     */
    public function actionGetOrderInfo($id = null){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status']  = 0;
        $response['message'] = 'error';
        if($id){
            $data = ToquvInstructions::getOrderInfo($id);
            $result = '';
            $table = [];
            foreach ($data as $key=>$item){
                $table[$item['mato']]['ca'] = $item['ca'];
                $table[$item['mato']]['mato'] = $item['mato'];
                $table[$item['mato']]['qty'] = $item['qty'];
                $table[$item['mato']]['ip'][trim($item['nename']).trim($item['thrname'])] = "{$item['nename']} - {$item['thrname']} - {$item['own_qty']}kg";
            }
            $count = 1;
            foreach ($table as $key=>$item){
                $result .= "<tr>";
                $result .= "<td>{$count}</td>";
                $result .= "<td>{$item['ca']}</td>";
                $result .= "<td>{$item['mato']}</td>";
                $result .= "<td>{$item['qty']}</td>";
                $result .= "<td>";
                foreach ($item['ip'] as $sub){
                    $result.= "<div class='text-center'>{$sub}</div>";
                }
                $result .= "</td>";
                $result .= "</tr>";
                $count++;
            }
            $response['status'] = 1;
            $response['data'] = $result;
            return $response;
        }
        return null;
    }
}
