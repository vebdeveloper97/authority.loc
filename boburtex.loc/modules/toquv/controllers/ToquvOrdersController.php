<?php

namespace app\modules\toquv\controllers;

use app\models\Users;
use app\modules\base\models\ModelsVariationColors;
use app\modules\toquv\models\MatoInfo;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocItemsRelOrder;
use app\modules\toquv\models\ToquvDocumentItems;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvInstructions;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\ToquvRmOrderItems;
use app\modules\toquv\models\Unit;
use Exception;
use Throwable;
use Yii;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvOrdersSearch;
use yii\db\StaleObjectException;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use app\modules\toquv\models\ToquvRmOrder;
use app\modules\toquv\models\ToquvRawMaterialIp;
use app\modules\toquv\models\ToquvRawMaterialConsist;
use yii\web\Response;

/**
 * ToquvOrdersController implements the CRUD actions for ToquvOrders model.
 */
class ToquvOrdersController extends BaseController
{
    /**
     * Lists all ToquvOrders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionModelOrders()
    {
        $searchModel = new ToquvOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'model_orders');
        return $this->render('model-orders', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    /**
     * Displays a single ToquvOrders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'models' => $model->toquvRmOrders,
        ]);
    }

    /**
     * Creates a new ToquvOrders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvOrders();
        $models = [new ToquvRmOrder];
        $model->reg_date = date('d.m.Y');
        $lastId = $model::find()->select('id')->orderBy(['id'=>SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->document_number =  "TO".$lastId . "/" . date('m-Y');
        $model->priority = 1;
        if(Yii::$app->request->get('status')==='kirim_mato'){
            $model->status = $model::STATUS_INACTIVE;
        }
        if(Yii::$app->request->isPost) {
            if($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if ($model->save()) {
                        $responsible = Yii::$app->request->post('ToquvOrders')['responsible'];
                        if ($responsible) {
                            if($model->saveResponsible($responsible)){
                                $saved = true;
                            }else{
                                $saved = false;
                            }
                        }else{
                            $saved = true;
                        }
                        $rmOrder = Yii::$app->request->post('ToquvRmOrder');
                        if ($rmOrder&&$saved) {
                            if($model->saveItems($rmOrder)){
                                $saved = true;
                            }else{
                                $saved = false;
                            }
                        }
                        if($saved) {
                            $transaction->commit();
                            return (Yii::$app->request->get('status') !== 'kirim_mato') ? $this->redirect(['view', 'id' => $model->id]) : $this->redirect(['view-kirim-mato', 'id' => $model->id]);
                        }else{
                            $transaction->rollBack();
                        }
                    }
                } catch (Exception $e) {
                    Yii::info('Not saved Toquv Orders ' . $e, 'save');
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models,
        ]);
    }

    /**
     * Updates an existing ToquvOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = ($model->toquvRmOrders)?$model->toquvRmOrders:[new ToquvRmOrder];
        $model->responsible = $model->responsibleMap;
        if(Yii::$app->request->isPost) {
            if($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if ($model->save()) {
                        if (!empty($model->toquvRmOrders)) {
                            foreach($model->toquvRmOrders as $key){
                                $key->delete();
                            }
                        }
                        if (!empty($model->toquvOrdersResponsibles)) {
                            foreach($model->toquvOrdersResponsibles as $key){
                                $key->delete();
                            }
                        }
                        $responsible = Yii::$app->request->post('ToquvOrders')['responsible'];
                        if ($responsible) {
                            if($model->saveResponsible($responsible)){
                                $saved = true;
                            }else{
                                $saved = false;
                            }
                        }else{
                            $saved = true;
                        }
                        $rmOrder = Yii::$app->request->post('ToquvRmOrder');
                        if ($rmOrder&&$saved) {
                            if($model->saveItems($rmOrder)){
                                $saved = true;
                            }else{
                                $saved = false;
                            }
                        }
                        if($saved) {
                            $transaction->commit();
                            return (Yii::$app->request->get('status') !== 'kirim_mato') ? $this->redirect(['view', 'id' => $model->id]) : $this->redirect(['view-kirim-mato', 'id' => $model->id]);
                        }else{
                            $transaction->rollBack();
                        }
                    }
                } catch (Exception $e) {
                    Yii::info('Not saved Toquv Orders ' . $e, 'save');
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * @param null $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionInstructions($id = null){
        $instuction = new ToquvInstructions();
        $model = $this->findModel($id);
        return $this->render('instructions', [
            'instruction' => $instuction,
            'model' => $model
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionAjax()
    {
        $model = new ToquvRmOrder;
        if (Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $id = ($data['id'])?$data['id']:0;
            $type = $data['type'];
            switch ($type){
                case 'ACS':
                    $label = Yii::t('app','Aksessuar');
                    $placeholder = Yii::t('app','Aksessuar tanlang');
                    $type = ToquvRawMaterials::ACS;
                    break;
                default:
                    $label = Yii::t('app','Toquv Raw Materials ID');
                    $placeholder = Yii::t('app','Mato turini tanlang');
                    $type = ToquvRawMaterials::MATO;
            }
           return $this->renderAjax('ajax', [
               'model' => $model,
               'id' => $id,
               'label' => $label,
               'placeholder' => $placeholder,
               'type' => $type
            ]);
        }
         return $this->redirect('index');
    }
    public function actionRmItems()
    {
        if (Yii::$app->request->isAjax&&Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            if(!empty($data['id'])&&$data['id']!=0){
                $consist = ToquvRawMaterialConsist::find()->where(['fabric_type_id' => 3,'raw_material_id' => $data['id']])->one();
                $service = ($consist)?0.35:0.25;
                $model = ToquvRawMaterialIp::find()->where(['toquv_raw_material_id'=>$data['id']])->all();
                if(count($model)>0){
                    return $this->renderAjax('rm-items', [
                        'model' => $model,
                        'kg' => $data['kg'],
                        'service' => $service,
                    ]);
                }
            }else{
                return false;
            }
        }
    }

    /**
     * Deletes an existing ToquvOrders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws Throwable
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if($model->toquvRmOrders){
                foreach ($model->toquvRmOrders as $item){
                    ToquvRmOrderItems::deleteAll(['toquv_rm_order_id'=>$item->id]);
                    $item->delete();
                }
            }
            $isDeleted = false;
            if($model->delete()){
                $isDeleted = true;
            }
            if($isDeleted){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        }catch (Exception $e){
            Yii::info('Not saved' . $e, 'save');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->redirect(['index']);
    }

    public function actionOrderInfo($id)
    {
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('view/order-info', ['order_info' => ToquvRmOrder::getOrderInfo($id)]);
        }
        return $this->render('view/order-info', ['order_info' => ToquvRmOrder::getOrderInfo($id)]);
    }
    /**
     * @param $q
     * @return array
     */
    public function actionAjaxRequest($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['results'] = [];
        if (!empty($q)) {
            $searchModel = new ModelsVariationColors();
            $res = $searchModel->getColorList($q);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = $item['ccode'] . " - <b>"
                        . "<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>"
                        .$item['tname'] . "</span></span> ". $item['cname'] . "</b>";
                    array_push($response['results'], [
                        'id' => $item['id'],
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
    public function actionAjaxRequestBoyoq($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['results'] = [];
        if (!empty($q)) {
            $searchModel = new ModelsVariationColors();
            $res = $searchModel->getBoyoqColorList($q);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = "<b>". $item['color_id'] . "</b>
                            <span style='background:{$item['color']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>"
                        .$item['color'] . "</span></span> - <b>"
                        . $item['tname'] . "</b>" . " - <b>"
                        . $item['cname'] . "</b>".$item['pantone'];
                    array_push($response['results'], [
                        'id' => $item['id'],
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
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id){

        $model = $this->findModel($id);
        if($model->status < ToquvOrders::STATUS_INACTIVE){
            $model->responsible = 1;
            $model->status = ToquvOrders::STATUS_SAVED;
            $model->save();
        }
        return $this->redirect(['view','id' => $id]);
    }
    /**
     * Finds the ToquvOrders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvOrders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvOrders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function actionKirimMato()
    {
        $searchModel = new ToquvOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'kirim_mato');
        return $this->render('kirim-mato', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionViewKirimMato($id)
    {
        $model = $this->findModel($id);
        if($model->status!=$model::STATUS_INACTIVE&&$model->status!=$model::STATUS_KIRIM_MATO){
            return $this->redirect('kirim-mato');
        }
        return $this->render('view-kirim-mato', [
            'model' => $model,
            'models' => $model->toquvRmOrders,
        ]);
    }
    public function actionSaveAndFinishKirimMato($id){
        $toquv_orders = $this->findModel($id);
        if($toquv_orders->status === ToquvOrders::STATUS_INACTIVE){
            $data = Yii::$app->request->post();
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
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!empty($toquv_orders->toquvRmOrders)&&$model->save()) {
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
                            if($mato){
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
                                VarDumper::dump($mato->getErrors(), 10, true);
                                VarDumper::dump($item, 10, true);
                                die;
                            }
                        }else{
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
                            if ($modelDI->save()) {
                                $tdiro = ToquvDocItemsRelOrder::findOne([
                                    'toquv_document_items_id' => $modelDI->id,
                                    'toquv_orders_id' => $toquv_orders->id,
                                    'toquv_rm_order_id' => $item->id,
                                ]);
                                if (!$tdiro) {
                                    $TDIRO = new ToquvDocItemsRelOrder([
                                        'toquv_document_items_id' => $modelDI->id,
                                        'toquv_orders_id' => $toquv_orders->id,
                                        'toquv_rm_order_id' => $item->id,
                                    ]);
                                    $TDIRO->save();
                                }
                                $flagToquvItems = true;
                            }else{
                                $flagToquvItems = false;
                                break;
                            }
                        } else {
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Hatolik yuz berdi'));
                            $flagToquvItems = false;
                            break;
                        }
                    }
                    if($flagToquvItems){
                        $toquv_orders->status = ToquvOrders::STATUS_KIRIM_MATO;
                        $toquv_orders->save(false);
                    }
                    if ($flagToquvItems){
                        $transaction->commit();
                        return $this->redirect(['toquv-documents/view','id' => $model->id, 'slug'=>'kirim_mato']);
                    }else{
                        $transaction->rollBack();
                    }
                }
            }catch (Exception $e){
                Yii::info('Not saved' . $e, 'save');
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
        }
        return $this->redirect(['view-kirim-mato','id' => $id]);
    }
    public function actionCreateKirimMato()
    {
        $model = new ToquvOrders();
        $models = [new ToquvRmOrder];
        $model->reg_date = date('d.m.Y');
        $lastId = $model::find()->select('id')->orderBy(['id'=>SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->document_number =  "TO".$lastId . "/" . date('m-Y');
        $model->priority = 1;
        if(Yii::$app->request->get('status')==='kirim_mato'){
            $model->status = $model::STATUS_INACTIVE;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $responsible = Yii::$app->request->post('ToquvOrders')['responsible'];
            if($responsible){
                $model->saveResponsible($responsible);
            }
            $rmOrder = Yii::$app->request->post('ToquvRmOrder');
            if($rmOrder){
                $model->saveItems($rmOrder);
            }
//            $data = Yii::$app->request->post('ToquvRmOrderItems');
//            if($data){
//                $model->saveOrderItems($data);
//            }
            return (Yii::$app->request->get('status')!=='kirim_mato')?$this->redirect(['view', 'id' => $model->id]):$this->redirect(['view-kirim-mato', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models,
        ]);
    }

    /**
     * Updates an existing ToquvOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionUpdateKirimMato($id)
    {
        $model = $this->findModel($id);
        $models = ($model->toquvRmOrders)?$model->toquvRmOrders:[new ToquvRmOrder];
        $model->responsible = $model->responsibleMap;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (!empty($model->toquvRmOrders)) {
                foreach($model->toquvRmOrders as $key){
                    $key->delete();
                }
            }
            if (!empty($model->toquvOrdersResponsibles)) {
                foreach($model->toquvOrdersResponsibles as $key){
                    $key->delete();
                }
            }
            $responsible = Yii::$app->request->post('ToquvOrders')['responsible'];
            if($responsible){
                $model->saveResponsible($responsible);
            }
            $rmOrder = Yii::$app->request->post('ToquvRmOrder');
            if($rmOrder){
                $model->saveItems($rmOrder);
            }
//            $data = Yii::$app->request->post('ToquvRmOrderItems');
//            if($data){
//                $model->saveOrderItems($data);
//            }
            return (Yii::$app->request->get('status')!=='kirim_mato')?$this->redirect(['view', 'id' => $model->id]):$this->redirect(['view-kirim-mato', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models
        ]);
    }
    public function actionDeleteKirimMato($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if($model->toquvRmOrders){
                foreach ($model->toquvRmOrders as $item){
                    ToquvRmOrderItems::deleteAll(['toquv_rm_order_id'=>$item->id]);
                    $item->delete();
                }
            }
            $isDeleted = false;
            if($model->delete()){
                $isDeleted = true;
            }
            if($isDeleted){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        }catch (Exception $e){
            Yii::info('Not saved' . $e, 'save');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->redirect(['index']);
    }
}
