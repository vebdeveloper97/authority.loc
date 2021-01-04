<?php

namespace app\modules\tikuv\controllers;

use app\models\ColorPantone;
use app\models\Constants;
use app\models\Size;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\base\models\BarcodeCustomers;
use app\modules\base\models\Goods;
use app\modules\base\models\ModelsListSearch;
use app\modules\base\models\ModelsVariations;
use app\modules\bichuv\models\BichuvGivenRolls;
use app\modules\bichuv\models\BichuvServiceItemBalance;
use app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls;
use app\modules\tikuv\models\ChangeModelForm;
use app\modules\tikuv\models\DocSearch;
use app\modules\tikuv\models\ModelRelDoc;
use app\modules\tikuv\models\MovingSearchForm;
use app\modules\tikuv\models\TikuvDiffFromProduction;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\TikuvDocItems;
use app\modules\tikuv\models\TikuvGoodsDocPack;
use app\modules\tikuv\models\TikuvKonveyer;
use app\modules\tikuv\models\TikuvOutcomeProductsSearch;
use app\modules\tikuv\models\TikuvPackageItemBalance;
use app\modules\tikuv\models\TikuvSliceItemBalance;
use app\modules\tikuv\models\TOPPSearch;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\usluga\models\UslugaDoc;
use app\modules\usluga\models\UslugaDocItems;
use app\widgets\helpers\Telegram;
use moonland\phpexcel\Excel;
use Throwable;
use Yii;
use app\modules\tikuv\models\TikuvOutcomeProducts;
use app\modules\tikuv\models\TikuvOutcomeProductsPack;
use app\modules\tikuv\models\TikuvOutcomeProductsPackSearch;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TikuvOutcomeProductsPackController implements the CRUD actions for TikuvOutcomeProductsPack model.
 */
class TikuvOutcomeProductsPackController extends BaseController
{
    /**
     * Lists all TikuvOutcomeProductsPack models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TikuvOutcomeProductsPackSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$searchModel::TYPE_TIKUV);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionUsluga()
    {
        $searchModel = new TikuvOutcomeProductsPackSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$searchModel::TYPE_USLUGA);

        return $this->render('usluga', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetProductOut(){
        $model = new TikuvOutcomeProductsPack();
        $models = [new TikuvOutcomeProducts()];
        return $this->render('get_out',
            [
                'model' => $model,
                'models' => $models
            ]);
    }

    public function actionChangeSizeAndAgent(){

    }

    public function actionModelAcceptedIndex()
    {
        $searchModel = new ModelsListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('model-accepted-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single TikuvOutcomeProductsPack model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new TikuvOutcomeProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single TikuvOutcomeProductsPack model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUslugaView($id)
    {
        $searchModel = new TikuvOutcomeProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        return $this->render('usluga-view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewModels($id)
    {
        $model = TikuvDoc::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        if (Yii::$app->request->isPost) {

            $data = Yii::$app->request->post();
            if (!empty($data)) {
                if ($data['ChangeModelForm']) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $saved = false;
                    try {
                        ModelRelDoc::deleteAll(['tikuv_doc_id' => $id]);
                        foreach ($data['ChangeModelForm'] as $key => $subItem) {
                            $modelRelDoc = new ModelRelDoc();
                            $modelRelDoc->setAttributes([
                                'model_list_id' => $subItem['model_id'],
                                'model_var_id' => $subItem['model_var_id'],
                                'order_id' => $subItem['order_id'],
                                'order_item_id' => $subItem['order_item_id'],
                                'price' => $subItem['price'],
                                'pb_id' => $subItem['pb_id'],
                                'tikuv_doc_id' => $id,
                                'nastel_no' => $key,
                                'color_id' => $subItem['color_id']
                            ]);
                            if ($modelRelDoc->save()) {
                                $saved = true;
                            } else {
                                $saved = false;
                                break;
                            }
                            $items = TikuvDocItems::find()->where(['tikuv_doc_id' => $id,'nastel_party_no'=>$key])->with(['size'])->asArray()->all();
                            if(!empty($items)&&!empty($subItem['model_id'])&&!empty($subItem['color_id'])){
                                $pantone=ColorPantone::find()->where(['id'=>$subItem['color_id']])->asArray()->one();
                                if (!empty($pantone)) {
                                    foreach ($items as $item) {
                                        $check = Goods::findOne([
                                            'model_id' => $subItem['model_id'],
                                            'size_type' => $item['size']['size_type_id'],
                                            'size' => $item['size_id'],
                                            'color' => $subItem['color_id']
                                        ]);
                                        $all = Goods::find()->orderBy(['id' => SORT_DESC]);
                                        $count = $all->count();
                                        $barcode = ($count == 0) ? 100000000 : $all->one()->barcode + 1;
                                        if (empty($check)) {
                                            $name = "{$subItem['model_no']}*{$pantone['code']}*{$item['size']['name']}";
                                            $dataGoods = [
                                                'barcode' => $barcode,
                                                'is_inside' => Goods::TYPE_MODEL_INSIDE,
                                                'color' => $subItem['color_id'],
                                                'model_no' => $subItem['model_no'],
                                                'model_id' => $subItem['model_id'],
                                                'size_type' => $item['size']['size_type_id'],
                                                'size' => $item['size_id'],
                                                'name' => $name,
                                                'model_var' => $subItem['model_var_id'],
                                                'category' => null,
                                                'sub_category' => null,
                                                'model_type' => null,
                                                'season' => null
                                            ];
                                            $goods = new Goods($dataGoods);
                                            if ($goods->save()) {
                                                /*BichuvServiceItemBalance::updateAll(['model_id'=>$subItem['model_id'],'model_var'=>$subItem['model_var_id']],['and',['nastel_no'=>$key],['size_id'=>$item['size_id']]]);*/
                                                $saved = true;
                                            } else {
                                                $saved = false;
                                                break 2;
                                            }
                                        }
                                    }
                                }
                            }
                            else{
                                \yii\helpers\VarDumper::dump($subItem,10,true);die;
                            }
                        }
                        if($saved){
                            $model->change_note = $data['changeNote'];
                            $model->is_change_model = 2;
                            if(!$model->save()){
                                $saved = false;
                            }
                        }
                        if ($saved) {
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Xatolik yuz berdi!'));
                        }
                    } catch (\Exception $e) {
                        \yii\helpers\VarDumper::dump($e,10,true);die;
                    }
                }
                else{
                    \yii\helpers\VarDumper::dump($data,10,true);die;
                }
            }
        }

        $changeModel = new ChangeModelForm();
        $modelRelDocs = ModelRelDoc::find()->joinWith(['modelList', 'modelVar.colorPan', 'colorPantone'])->where(['tikuv_doc_id' => $id])->all();
        return $this->render("view-models", [
            'model' => $model,
            'changeModel' => $changeModel,
            'modelRelDocs' => $modelRelDocs
        ]);
    }

    public function actionCreateCombineNastel(){
        $model = [new TikuvDoc()];
        $modelItems = [[new TikuvDocItems()]];
        return $this->render('create-combine-nastel',[
            'model' => (empty($model)) ? [new TikuvDoc] : $model,
            'modelItems' => (empty($modelItems)) ? [[new TikuvDocItems]] : $modelItems,
        ]);
    }

    public function actionMakeCombineNastel(){
        return $this->render('make-combine-nastel');
    }

    public function actionCombineNastel(){
        $searchModel = new DocSearch();
        $dataProvider = $searchModel->searchCombine(Yii::$app->request->queryParams);

        return $this->render('combine-nastel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewCombineNastel($id){
        $model = TikuvDoc::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        $item = TikuvDocItems::findOne(['tikuv_doc_id'=>$id]);
        if($item){
            $item = $item->toArray();
        }
        $bgr = BichuvGivenRolls::findOne(['nastel_party'=>$item['nastel_party_no']]);
        if($bgr){
            $bgr = $bgr->toArray();
        }
        $konveyer = TikuvKonveyer::find()->joinWith('tikuvKonveyerBichuvGivenRolls')->where(['tikuv_konveyer_bichuv_given_rolls.bichuv_given_rolls_id'=>$bgr['id']])->asArray()->one();
        return $this->render('view-combine-nastel', [
            'model' => $model,
            'konveyer' => $konveyer
        ]);

    }

    /**
     * @param $id
     * @return array
     */
    public function actionGetOrderItems($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mv = TikuvDoc::getOrderItemModelList(null,$id,true);
        $response = [];
        $response['status'] = false;
        if (!empty($mv)) {
            $response['status'] = true;
            $response['items'] = $mv;
        }
        return $response;
    }
    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public function actionGetModelVariations($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mv = TikuvOutcomeProductsPack::getOrderItemModelVariation($id);
        $response = [];
        $response['status'] = false;
        if (!empty($mv)) {
            $response['status'] = true;
            $response['items'] = $mv;
        }
        return $response;
    }

    public function actionChangeModels()
    {

        $searchModel = new DocSearch();
        $docType = TikuvDoc::DOC_TYPE_ACCEPTED;
        $entityType = 1;
        $modelType = TikuvDoc::MODEL_TYPE_SLICE;

        $dataProvider = $searchModel->search_doc(Yii::$app->request->queryParams, $modelType, $docType, $entityType, true);
        return $this->render("change-models",
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Creates a new TikuvOutcomeProductsPack model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TikuvOutcomeProductsPack();
        $models = [new TikuvOutcomeProducts()];
        
        if(Yii::$app->request->isPost){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $saved = false;
                if ($model->load(Yii::$app->request->post())) {
                    $data = Yii::$app->request->post();
                    if ($model->save()) {
                        $modelId = $model->id;
                        if ($data['TikuvOutcomeProducts']) {
                            foreach ($data['TikuvOutcomeProducts'] as $k) {
                                $products = new TikuvOutcomeProducts();
                                $gbData = $model->getGoodsBarcodeData($k['goods_id'], $k['barcode']);
                                $products->setAttributes([
                                    'pack_id' => $modelId,
                                    'model_no' => $k['model_no'],
                                    'goods_barcode_id' => $gbData['id'],
                                    'color_code' => $k['color_code'],
                                    'barcode' => $k['barcode'],
                                    'is_main_barcode' => $gbData['barcode'],
                                    'quantity' => $k['quantity'],
                                    'sort_type_id' => $k['sort_type_id'],
                                    'size_type_id' => $k['size_type_id'],
                                    'size_id' => $k['size_id'],
                                    'unit_id' => 4,
                                    'goods_id' => $k['goods_id'],
                                    'reg_date' => date("Y-m-d H:i:s"),
                                ]);
                                if($products->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                        if ($data['TikuvOutcomeProductsNew']) {
                            foreach ($data['TikuvOutcomeProductsNew'] as $k) {
                                $products = new TikuvOutcomeProducts();
                                $gbData = $model->getGoodsBarcodeData($k['goods_id'], $k['barcode']);
                                $products->setAttributes([
                                    'pack_id' => $modelId,
                                    'model_no' => $k['model_no'],
                                    'goods_barcode_id' => $gbData['id'],
                                    'color_code' => $k['color_code'],
                                    'barcode' => $k['barcode'],
                                    'is_main_barcode' => $gbData['barcode'],
                                    'quantity' => $k['quantity'],
                                    'sort_type_id' => $k['sort_type_id'],
                                    'size_type_id' => $k['size_type_id'],
                                    'size_id' => $k['size_id'],
                                    'unit_id' => 4,
                                    'goods_id' => $k['goods_id'],
                                    'reg_date' => date("Y-m-d H:i:s"),
                                ]);
                            }
                        }
                        if($saved){
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $modelId]);
                        }else{
                            $transaction->rollBack();
                        }
                    }
                }
            }catch (\Exception $e){
                Yii::info('Not saved '.$e->getMessage(),'save');
            }    
        }
        return $this->render('create', [
            'model' => $model,
            'models' => $models,
        ]);
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
        $models = $model->tikuvOutcomeProducts ? $model->tikuvOutcomeProducts : [new TikuvOutcomeProducts()];
        if(Yii::$app->request->isPost){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $saved = false;
                if ($model->load(Yii::$app->request->post())) {
                    $data = Yii::$app->request->post();
                    if ($model->save()) {
                        if (!empty($models)) {
                            foreach ($models as $item) {
                                TikuvDiffFromProduction::deleteAll(['tikuv_op_id'=>$item->id]);
                                if($item->delete()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                        $modelId = $model->id;
                        if ($data['TikuvOutcomeProducts']) {
                            foreach ($data['TikuvOutcomeProducts'] as $k) {
                                $products = new TikuvOutcomeProducts();
                                $gbData = $model->getGoodsBarcodeData($k['goods_id'], $k['barcode']);
                                $products->setAttributes([
                                    'pack_id' => $modelId,
                                    'model_no' => $k['model_no'],
                                    'goods_barcode_id' => $gbData['id'],
                                    'color_code' => $k['color_code'],
                                    'barcode' => $k['barcode'],
                                    'is_main_barcode' => $gbData['barcode'],
                                    'quantity' => $k['quantity'],
                                    'sort_type_id' => $k['sort_type_id'],
                                    'size_type_id' => $k['size_type_id'],
                                    'size_id' => $k['size_id'],
                                    'unit_id' => 4,
                                    'goods_id' => $k['goods_id'],
                                    'reg_date' => date("Y-m-d H:i:s"),
                                ]);
                                if($products->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                        if ($data['TikuvOutcomeProductsNew']) {
                            foreach ($data['TikuvOutcomeProductsNew'] as $k) {
                                $products = new TikuvOutcomeProducts();
                                $gbData = $model->getGoodsBarcodeData($k['goods_id'], $k['barcode']);
                                $products->setAttributes([
                                    'pack_id' => $modelId,
                                    'model_no' => $k['model_no'],
                                    'goods_barcode_id' => $gbData['id'],
                                    'color_code' => $k['color_code'],
                                    'barcode' => $k['barcode'],
                                    'is_main_barcode' => $gbData['barcode'],
                                    'quantity' => $k['quantity'],
                                    'sort_type_id' => $k['sort_type_id'],
                                    'size_type_id' => $k['size_type_id'],
                                    'size_id' => $k['size_id'],
                                    'unit_id' => 4,
                                    'goods_id' => $k['goods_id'],
                                    'reg_date' => date("Y-m-d H:i:s"),
                                ]);
                                if($products->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                        if($saved){
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $modelId]);
                        }else{
                            $transaction->rollBack();
                        }
                    }
                }
            }catch (\Exception $e){
                Yii::info('Not saved '.$e->getMessage(),'save');
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'models' => $models
        ]);
    }
    public function actionUslugaForm($id=null)
    {
        $model = ($id)?$this->findModel($id):new TikuvOutcomeProductsPack();
        $models = $model->tikuvOutcomeProducts ? $model->tikuvOutcomeProducts : [new TikuvOutcomeProducts()];
        $model->type = $model::TYPE_FROM_MUSTERI;
        if(Yii::$app->request->isPost){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $saved = false;
                if ($model->load(Yii::$app->request->post())) {
                    $data = Yii::$app->request->post();
                    if ($model->save()) {
                        if (!empty($models)) {
                            foreach ($models as $item) {
                                if($item->delete()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                        $modelId = $model->id;
                        if ($data['TikuvOutcomeProducts']) {
                            foreach ($data['TikuvOutcomeProducts'] as $k) {
                                $products = new TikuvOutcomeProducts();
                                $gbData = $model->getGoodsBarcodeData($k['goods_id'], $k['barcode']);
                                $products->setAttributes([
                                    'pack_id' => $modelId,
                                    'model_no' => $k['model_no'],
                                    'goods_barcode_id' => $gbData['id'],
                                    'color_code' => $k['color_code'],
                                    'barcode' => $k['barcode'],
                                    'is_main_barcode' => $gbData['barcode'],
                                    'quantity' => $k['quantity'],
                                    'sort_type_id' => $k['sort_type_id'],
                                    'size_type_id' => $k['size_type_id'],
                                    'size_id' => $k['size_id'],
                                    'unit_id' => 4,
                                    'goods_id' => $k['goods_id'],
                                    'reg_date' => date("Y-m-d H:i:s"),
                                    'models_list_id' => $k['models_list_id'],
                                    'model_var_id' => $k['model_var_id'],
                                    'order_id' => $k['order_id'],
                                    'order_item_id' => $k['order_item_id'],
                                    'nastel_no' => $k['nastel_no'],
                                ]);
                                if($products->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                        if($saved){
                            $transaction->commit();
                            return $this->redirect(['usluga-view', 'id' => $modelId]);
                        }else{
                            $transaction->rollBack();
                        }
                    }
                }
            }catch (\Exception $e){
                $transaction->rollBack();
                Yii::info('Not saved '.$e->getMessage(),'save');
            }
        }

        return $this->render('usluga-form', [
            'model' => $model,
            'models' => $models
        ]);
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
        $type = $model->type;
        $model->delete();
        return ($type==TikuvOutcomeProductsPack::TYPE_TIKUV)?$this->redirect(['index']):$this->redirect(['usluga']);
    }

    public function actionAjax($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = TikuvOutcomeProducts::findOne($id);
            return $model->getCountAccepted($id);
        }
        return false;
    }

    public function actionExportExcel()
    {
        header('Content-Type: application/vnd.ms-excel');
        $filename = "tikuv-outcome-products-pack_" . date("d-m-Y-His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        Excel::export([
            'models' => TikuvOutcomeProductsPack::find()->select([
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

    public function actionSaveAndFinish($id)
    {
        $model = $this->findModel($id);
        if ($model->status < TikuvOutcomeProductsPack::STATUS_SAVED) {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $saved = false;
                $modelItems = $model->getTikuvOutcomeProducts()->joinWith('sortType')->asArray()->all();
                $nastelNo = $model->nastel_no;
                $musId = $model->musteri_id;
                $dept = $model->department_id;
                $toDept = $model->to_department;
                $modelListId = $model->model_list_id;
                $modelVarId = $model->model_var_id;
                $orderId = $model->order_id;
                $orderItemId = $model->order_item_id;
                $barcodeCustomerId = $model->barcode_customer_id;
                if(!empty($modelItems)){
                    foreach ($modelItems as $modelItem) {
                        $lastRecord = TikuvSliceItemBalance::find()->with(['size'])->where([
                            'nastel_no' => $nastelNo,
                            'size_id' => $modelItem['size_id']
                        ])->orderBy(['id'=>SORT_DESC])->asArray()->one();
                        if(!empty($lastRecord)){
                            if($modelItem['quantity'] > $lastRecord['inventory']){
                                $modelTikuvDiff = new TikuvDiffFromProduction();
                                $modelTikuvDiff->setAttributes([
                                    'tikuv_op_id' => $modelItem['id'],
                                    'size_id' => $modelItem['size_id'],
                                    'sort_id' => $modelItem['sort_type_id'],
                                    'quantity' => $modelItem['quantity'] - $lastRecord['inventory']
                                ]);
                                if($modelTikuvDiff->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }else{
                                $saved = true;
                            }
                            if(!empty($dept)&&$saved){
                                $toDept = $model->to_department;
                                $deptType = 'P';
                                if($modelItem['sortType']['code'] == Constants::$sortBrakCode){
                                    $brakDept = ToquvDepartments::find()->select('id')->where(['token' => Constants::$brakHoldDepartment])->asArray()->one();
                                    if(!empty($brakDept)){
                                        $toDept = $brakDept['id'];
                                        $deptType = 'BW';
                                    }else{
                                        continue;
                                    }
                                }
                                $lastRecordPackage = TikuvPackageItemBalance::find()->where([
                                    'goods_id' => $modelItem['goods_id'],
                                    'model_var_id' => $modelVarId,
                                    'model_list_id' => $modelListId,
                                    'order_id' => $orderId,
                                    'order_item_id' => $orderItemId,
                                    'nastel_no' => $nastelNo,
                                    'sort_type_id' => $modelItem['sort_type_id'],
                                    'department_id' => $toDept,
                                    'dept_type' => 'P',
                                    'barcode_customer_id' => $barcodeCustomerId
                                ])->orderBy(['id'=>SORT_DESC])->asArray()->one();

                                $inventoryPackage = $modelItem['quantity'];
                                if(!empty($lastRecordPackage)){
                                    $inventoryPackage += $lastRecordPackage['inventory'];
                                }
                                $modelTPIB = new TikuvPackageItemBalance();
                                $modelTPIB->setAttributes([
                                    'goods_id' =>  $modelItem['goods_id'],
                                    'count' => (int)$modelItem['quantity'],
                                    'inventory' => (int)$inventoryPackage,
                                    'nastel_no' => $nastelNo,
                                    'barcode_customer_id' => $barcodeCustomerId,
                                    'is_main_barcode' => $modelItem['is_main_barcode'],
                                    'brand_type' => $modelItem['type'],
                                    'doc_type' => 1,
                                    'dept_type' => $deptType,
                                    'to_musteri' => $musId,
                                    'department_id' => $toDept,
                                    'from_department' => $dept,
                                    'model_list_id' => $modelListId,
                                    'model_var_id' => $modelVarId,
                                    'sort_type_id' => $modelItem['sort_type_id'],
                                    'order_id' => $orderId,
                                    'order_item_id' => $orderItemId
                                ]);
                                if($modelTPIB->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }else{
                                \yii\helpers\VarDumper::dump($dept." topilmadi",10,true);die;
                            }
                            $modelTSIB = new TikuvSliceItemBalance();
                            $inventory = $lastRecord['inventory']-$modelItem['quantity'];
                            if($inventory < 0){
                                $inventory = 0;
                            }
                            $modelTSIB->setAttributes([
                                'size_id' => $modelItem['size_id'],
                                'nastel_no' => $nastelNo,
                                'count' => (-1)*$modelItem['quantity'],
                                'inventory' => $inventory,
                                'doc_id' => $lastRecord['doc_id'],
                                'doc_type' => 2,
                                'department_id' => $dept,
                                'from_department' => $dept,
                                'to_department' => $toDept,
                                'musteri_id' => $musId
                            ]);
                            if($modelTSIB->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                        else{
                            \yii\helpers\VarDumper::dump("{$nastelNo} topilmadi!",10,true);die;
                        }
                    }
                }else{
                    \yii\helpers\VarDumper::dump("modelItem topilmadi",10,true);die;
                }
                $modelBGR = TikuvKonveyerBichuvGivenRolls::find()
                    ->joinWith(['bichuvGivenRolls'])
                    ->where(['nastel_party' => $model->nastel_no])
                    ->one();
                if($modelBGR !== null&&$saved){
                    $modelBGR->status = 5;
                    if($modelBGR->save()){
                        $saved = true;
                    }else{
                        $saved = false;
                    }
                }
                $model->status = TikuvOutcomeProductsPack::STATUS_SAVED;
                if ($model->save()&&$saved) {
                    $saved = true;
                }else{
                    $saved = false;
                }
                if($saved){
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                }
            }catch (\Exception $e){
                Yii::info($e->getMessage(),'save');
                $transaction->rollBack();
            }
        }
        return $this->redirect(['view', 'id' => $id]);
    }
    public function actionSaveAndFinishFromMusteri($id)
    {
        $model = $this->findModel($id);
        if ($model->status < TikuvOutcomeProductsPack::STATUS_SAVED) {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $saved = false;
                $modelItems = $model->getTikuvOutcomeProducts()->joinWith('sortType')->asArray()->all();
                $musId = $model->musteri_id;
                $fromMus = $model->from_musteri;
                $dept = $model->department_id;
                $toDept = $model->to_department;
                $barcodeCustomerId = $model->barcode_customer_id;
                $type = UslugaDoc::TYPE_SLICE;
                if(!empty($modelItems)){
                    $usluga = new UslugaDoc([
                        'nastel_no' => $model->nastel_no,
                        'doc_number' => 'TFM-'.$model->id,
                        'from_musteri' => $fromMus,
                        'document_type' => UslugaDoc::DOC_TYPE_INCOMING_MUSTERI_WORK,
                        'to_department' => $model->to_department,
                        'accepted_date' => date('Y-m-d H:i:s'),
                        'reg_date' => date('Y-m-d H:i:s'),
                        'type' => UslugaDoc::TYPE_WORK,
                        'add_info' => $model->add_info,
                        'status' => UslugaDoc::STATUS_SAVED
                    ]);
                    $usluga->save(false);
                    foreach ($modelItems as $modelItem) {
                        $nastelNo = $modelItem['nastel_no'];
                        $modelListId = $modelItem['models_list_id'];
                        $modelVarId = $modelItem['model_var_id'];
                        $orderId = $modelItem['order_id'];
                        $orderItemId = $modelItem['order_item_id'];
                        $lastRecord = BichuvServiceItemBalance::find()->with(['size'])->where([
                            'nastel_no' => $nastelNo,
                            'size_id' => $modelItem['size_id'],
                            'musteri_id' => $fromMus,
                            /*'model_id' => $modelListId,
                            'model_var' => $modelVarId,*/
                            'type' => $type,
                        ])->orderBy(['id'=>SORT_DESC])->asArray()->one();
                        if(!empty($lastRecord)) {
                            /*if ($modelItem['quantity'] > $lastRecord['inventory']) {
                                $remain = $modelItem['quantity'] - $lastRecord['inventory'];
                                Yii::$app->session->setFlash('error', "Siz {$lastRecord['size']['name']} o'lchamdan {$remain} dona ortiqcha kiritib yubordingiz! :(");
                                return $this->redirect(['usluga-view', 'id' => $id]);
                            }*/
                            if (!empty($dept)) {
                                $deptType = 'P';
                                if($modelItem['sortType']['code'] == Constants::$sortBrakCode){
                                    $brakDept = ToquvDepartments::find()->select('id')->where(['token' => Constants::$brakHoldDepartment])->asArray()->one();
                                    if(!empty($brakDept)){
                                        $toDept = $brakDept['id'];
                                        $deptType = 'BW';
                                    }else{
                                        continue;
                                    }
                                }
                                $lastRecordPackage = TikuvPackageItemBalance::find()->where([
                                    'goods_id' => $modelItem['goods_id'],
                                    'model_var_id' => $modelVarId,
                                    'model_list_id' => $modelListId,
                                    'order_id' => $orderId,
                                    'order_item_id' => $orderItemId,
                                    'nastel_no' => $nastelNo,
                                    'sort_type_id' => $modelItem['sort_type_id'],
                                    'department_id' => $toDept,
                                    'dept_type' => $deptType,
                                    'barcode_customer_id' => $barcodeCustomerId
                                ])->orderBy(['id' => SORT_DESC])->asArray()->one();
                                $inventoryPackage = $modelItem['quantity'];
                                if (!empty($lastRecordPackage)) {
                                    $inventoryPackage += $lastRecordPackage['inventory'];
                                }
                                $modelTPIB = new TikuvPackageItemBalance();
                                $modelTPIB->setAttributes([
                                    'goods_id' => $modelItem['goods_id'],
                                    'count' => (int)$modelItem['quantity'],
                                    'inventory' => (int)$inventoryPackage,
                                    'nastel_no' => $nastelNo,
                                    'barcode_customer_id' => $barcodeCustomerId,
                                    'is_main_barcode' => $modelItem['is_main_barcode'],
                                    'brand_type' => $modelItem['type'],
                                    'doc_type' => 1,
                                    'dept_type' => $deptType,
                                    'to_musteri' => $musId,
                                    'department_id' => $toDept,
                                    'from_department' => $dept,
                                    'model_list_id' => $modelListId,
                                    'model_var_id' => $modelVarId,
                                    'sort_type_id' => $modelItem['sort_type_id'],
                                    'order_id' => $orderId,
                                    'order_item_id' => $orderItemId,
                                    'from_musteri' => $fromMus
                                ]);
                                if ($modelTPIB->save()) {
                                    $saved = true;
                                } else {
                                    if($modelTPIB->hasErrors()){
                                        new Telegram([
                                            'text' => ' #goods'.json_encode($modelTPIB->getErrors()),
                                            'controller' => 'TikuvOutcomeProductsPackController'
                                        ]);
                                    }
                                    $saved = false;
                                    break;
                                }
                            }
                            if($saved){
                                $modelTSIB = new BichuvServiceItemBalance();
                                $inventory = $lastRecord['inventory'] - $modelItem['quantity'];
                                if ($inventory < 0) {
                                    $inventory = 0;
                                    $diff = new TikuvDiffFromProduction([
                                        'size_id' => $modelItem['size_id'],
                                        'sort_id' => $modelItem['sort_type_id'],
                                        'quantity' => $modelItem['quantity'] - $lastRecord['inventory'],
                                        'nastel_no' => $nastelNo,
                                        'is_service' => 1,
                                        'tikuv_op_id' => $modelItem['id']
                                    ]);
                                    if($diff->save(false)){
                                        $saved = true;
                                    }else{
                                        $saved = false;
                                        break;
                                    }
                                }
                                $modelTSIB->setAttributes([
                                    'size_id' => $modelItem['size_id'],
                                    'nastel_no' => $nastelNo,
                                    'count' => (-1) * $modelItem['quantity'],
                                    'inventory' => $inventory,
                                    'doc_id' => $model['id'],
                                    'to_department' => $toDept,
                                    'musteri_id' => $fromMus,
                                    'doc_type' => UslugaDoc::DOC_TYPE_INCOMING_MUSTERI_WORK,
                                    'type' => $type,
                                    'sort_id' => 1,
                                    'model_id' => $lastRecord['model_id'],
                                    'model_var' => $lastRecord['model_var']
                                ]);
                                if ($modelTSIB->save()) {
                                    $saved = true;
                                } else {
                                    if($modelTSIB->hasErrors()){
                                        new Telegram([
                                            'text' => ' #goods'.json_encode($modelTSIB->getErrors()),
                                            'controller' => 'TikuvOutcomeProductsPackController'
                                        ]);
                                    }
                                    $saved = false;
                                    break;
                                }
                                $usluga_items = new UslugaDocItems([
                                    'usluga_doc_id' => $usluga->id,
                                    'nastel_party' => $nastelNo,
                                    'models_list_id' => $modelListId,
                                    'model_var_id' => $modelVarId,
                                    'moi_id' => $orderItemId,
                                    'type' => UslugaDoc::TYPE_WORK,
                                    'bsib_id' => $modelTSIB->id,
                                    'size_id' => $modelItem['size_id'],
                                    'sort_name_id' => $modelItem['sort_type_id'],
                                    'quantity' => $modelItem['quantity'],
                                    'price' => $modelItem['price'],
                                    'pb_id' => $modelItem['pb_id']
                                ]);
                                $usluga_items->save(false);
                            }
                        }else{
                            new Telegram([
                                'text' => ' #nastel_topilmadi'.json_encode($modelItem),
                                'controller' => 'TikuvOutcomeProductsPackController'
                            ]);
                            \yii\helpers\VarDumper::dump($nastelNo. ' topilmadi',10,true);die;
                        }
                    }
                }
                if($saved){
                    $model->status = TikuvOutcomeProductsPack::STATUS_SAVED;
                    if ($model->save()) {
                        $saved = true;
                    }else{
                        $saved = false;
                    }
                }
                if($saved){
                    Yii::$app->session->setFlash('success',Yii::t('app', 'Saved Successfully'));
                    $transaction->commit();
                }else{
                    Yii::$app->session->setFlash('error',Yii::t('app', 'Hatolik yuz berdi'));
                    $transaction->rollBack();
                }
            }catch (\Exception $e){
                Yii::info($e->getMessage(),'save');
            }
        }
        return $this->redirect(['usluga-view', 'id' => $id]);
    }
    public function actionSaveAndFinishUsluga($id)
    {
        $model = $this->findModel($id);
        if ($model->status < TikuvOutcomeProductsPack::STATUS_SAVED) {
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $saved = false;
                $modelItems = $model->getTikuvOutcomeProducts()->asArray()->all();
                $nastelNo = $model->nastel_no;
                $musId = $model->musteri_id;
                $fromMus = $model->from_musteri;
                $dept = $model->department_id;
                $toDept = $model->to_department;
                $modelListId = $model->model_list_id;
                $modelVarId = $model->model_var_id;
                $orderId = $model->order_id;
                $orderItemId = $model->order_item_id;
                $barcodeCustomerId = $model->barcode_customer_id;

                $type = UslugaDoc::TYPE_WORK;
                if(!empty($modelItems)){
                    foreach ($modelItems as $modelItem) {
                        $lastRecord = BichuvServiceItemBalance::find()->with(['size'])->where([
                            'nastel_no' => $nastelNo,
                            'size_id' => $modelItem['size_id'],
                            'department_id' => $dept,
                            'from_musteri' => $fromMus,
                            'model_id' => $modelListId,
                            'model_var' => $modelVarId,
                            'type' => $type,
                            'sort_id' => $modelItem['sort_type_id']
                        ])->orderBy(['id'=>SORT_DESC])->asArray()->one();
                        if(!empty($lastRecord)){
                            if($modelItem['quantity'] > $lastRecord['inventory']){
                                $remain = $modelItem['quantity'] - $lastRecord['inventory'];
                                Yii::$app->session->setFlash('error',"Siz {$lastRecord['size']['name']} o'lchamdan {$remain} dona ortiqcha kiritib yubordingiz! :(");
                                return $this->redirect(['usluga-view', 'id' => $id]);
                            }
                            if(!empty($dept)){
                                $deptType = 'P';
                                if($modelItem['sortType']['code'] == Constants::$sortBrakCode){
                                    $brakDept = ToquvDepartments::find()->select('id')->where(['token' => Constants::$brakHoldDepartment])->asArray()->one();
                                    if(!empty($brakDept)){
                                        $toDept = $brakDept['id'];
                                        $deptType = 'BW';
                                    }else{
                                        continue;
                                    }
                                }
                                $lastRecordPackage = TikuvPackageItemBalance::find()->where([
                                    'goods_id' => $modelItem['goods_id'],
                                    'model_var_id' => $modelVarId,
                                    'model_list_id' => $modelListId,
                                    'order_id' => $orderId,
                                    'order_item_id' => $orderItemId,
                                    'nastel_no' => $nastelNo,
                                    'sort_type_id' => $modelItem['sort_type_id'],
                                    'department_id' => $toDept,
                                    'dept_type' => $deptType,
                                    'barcode_customer_id' => $barcodeCustomerId
                                ])->orderBy(['id'=>SORT_DESC])->asArray()->one();
                                $inventoryPackage = $modelItem['quantity'];
                                if(!empty($lastRecordPackage)){
                                    $inventoryPackage += $lastRecordPackage['inventory'];
                                }
                                $modelTPIB = new TikuvPackageItemBalance();
                                $modelTPIB->setAttributes([
                                    'goods_id' =>  $modelItem['goods_id'],
                                    'count' => (int)$modelItem['quantity'],
                                    'inventory' => (int)$inventoryPackage,
                                    'nastel_no' => $nastelNo,
                                    'barcode_customer_id' => $barcodeCustomerId,
                                    'is_main_barcode' => $modelItem['is_main_barcode'],
                                    'brand_type' => $modelItem['type'],
                                    'doc_type' => 1,
                                    'dept_type' => $deptType,
                                    'to_musteri' => $musId,
                                    'department_id' => $toDept,
                                    'from_department' => $dept,
                                    'model_list_id' => $modelListId,
                                    'model_var_id' => $modelVarId,
                                    'sort_type_id' => $modelItem['sort_type_id'],
                                    'order_id' => $orderId,
                                    'order_item_id' => $orderItemId,
                                    'from_musteri' => $fromMus
                                ]);
                                if($modelTPIB->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                            $modelTSIB = new BichuvServiceItemBalance();
                            $inventory = $lastRecord['inventory']-$modelItem['quantity'];
                            if($inventory >=0 ) {
                                $modelTSIB->setAttributes([
                                    'size_id' => $modelItem['size_id'],
                                    'nastel_no' => $nastelNo,
                                    'count' => (-1) * $modelItem['quantity'],
                                    'inventory' => $inventory,
                                    'doc_id' => $model['id'],
                                    'department_id' => $dept,
                                    'to_department' => $toDept,
                                    'from_musteri' => $fromMus,
                                    'sort_id' => $modelItem['sort_type_id'],
                                    'doc_type' => UslugaDoc::DOC_TYPE_MOVING_WORK,
                                    'type' => $type,
                                    'model_id' => $modelListId,
                                    'model_var' => $modelVarId
                                ]);
                                if ($modelTSIB->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    break;
                                }
                            }else{
                                $saved = false;
                                Yii::$app->session->setFlash('error',"Sizda {$lastRecord['size']['name']} o'lchamdan ".abs($inventory)." dona yetishmayapti! :(");
                            }
                        }
                    }
                }
                if($saved){
                    $model->status = TikuvOutcomeProductsPack::STATUS_SAVED;
                    if ($model->save()) {
                        $saved = true;
                    }else{
                        $saved = false;
                    }
                }

                if($saved){
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                }
            }catch (\Exception $e){
                Yii::info($e->getMessage(),'save');
            }
        }
        return $this->redirect(['usluga-view', 'id' => $id]);
    }
    public function actionSizes($typeId)
    {
        $response = ['status' => false];
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = Size::find()->select(['id', 'name'])->where(['size_type_id' => $typeId])->asArray()->all();
            $response['status'] = true;
            $response['data'] = $model;
        }
        return $response;
    }

    public function actionBoyoqPartiya($q)
    {
        $response = ['status' => false];
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = true;
            $response['results'] = TikuvOutcomeProductsPack::getBoyoqPartiya($q);
        }
        return $response;
    }

    public function actionMusteri($id)
    {
        $response = ['status' => false];
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $response['status'] = true;
            $response['data'] = TikuvOutcomeProductsPack::getMusteriList($id);
        }
        return $response;
    }

    public function actionOrder($id)
    {
        $response = ['status' => false];
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = true;
            $response['data'] = TikuvOutcomeProductsPack::getOrderList($id);
        }
        return $response;
    }

    public function actionGetNastelNo($q,$dep){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $results = TikuvOutcomeProductsPack::getNastelNo($q,$dep);
        return $results;
    }
    public function actionOrderItems($nastel, $model_var,$model)
    {

        $sql1 = "select bc.name, bc.id from tikuv_doc_items tdi
                inner join model_rel_doc mrd on tdi.nastel_party_no = mrd.nastel_no
                left join goods g on g.model_id = mrd.model_list_id
                left join goods_barcode gb on g.id = gb.goods_id
                inner join barcode_customers bc on gb.bc_id = bc.id
                where tdi.nastel_party_no = '{$nastel}'
                  AND mrd.model_list_id = {$model}
                  AND g.model_id = {$model} AND g.color = mrd.color_id 
                 GROUP BY bc.id;";

        $results = Yii::$app->db->createCommand($sql1)->queryAll();

        $sql = "select g.barcode,
                       g.id       as good_id,
                       ml.article as model_no,
                       s.size_type_id,
                       s.id       as size_id,
                       tsib.inventory as quantity,
                       s.name     as size_name,
                       cp.code,
                       ml.name    as model,
                       ml.id      as model_id,
                       cp.name    as pantone
                from tikuv_slice_item_balance tsib
                         inner join tikuv_doc_items tdi on tsib.nastel_no = tdi.nastel_party_no
                         inner join model_rel_doc mrd on mrd.tikuv_doc_id = tdi.tikuv_doc_id
                         inner join goods g on mrd.model_list_id = g.model_id
                         inner join models_list ml on g.model_id = ml.id
                         left join models_variations mv on mrd.model_var_id = mv.id  
                         inner join color_pantone cp on mrd.color_id = cp.id
                         inner join size s on g.size = s.id
                where tsib.id IN (select MAX(tsib2.id)
                                  from tikuv_slice_item_balance tsib2
                                  where tsib2.nastel_no = '%s'
                                    AND tsib2.size_id = g.size
                                  GROUP BY tsib2.size_id)
                  AND g.color = cp.id AND mrd.nastel_no = '%s' AND mrd.model_var_id = %d AND g.status = 1
                GROUP BY s.id ORDER BY s.order;";
        $sql = sprintf($sql, $nastel, $nastel, $model_var);
        $items = Yii::$app->db->createCommand($sql)->queryAll();
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;

            $response = [];
            $response['status'] = false;
            if(!empty($items)){
                $response['status'] = true;
                $response['items'] = $items;
                $response['results'] = [];
                foreach ($results as $result) {
                    array_push($response['results'], [
                        'id' => $result['id'],
                        'text' => $result['name']
                    ]);
                }
                array_push($response['results'], [
                    'id' => 1,
                    'text' => 'SAMO'
                ]);
            }
            return $response;
        }
        return $this->render('order-items', ['items' => $result]);
    }
    public function actionUslugaItems()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $result['status'] = 0;
        $result['message'] = Yii::t('app', 'Bunday raqamdagi kesim topilmadi yoki avval kiritilgan :(');

        if (!empty($data) && !empty($data['nastel']) && (!empty($data['department']) || !empty($data['musteri']))) {
            $nastelCondition = "";

            if (!empty($data['nastelList'])) {
                $nastelNo = join(',', $data['nastelList']);
                $nastelCondition .= " AND bsib2.nastel_no NOT IN ({$nastelNo})";
            }
            $dept = '';
            if (!empty($data['department'])) {
                $dept = "AND bsib2.department_id = {$data['department']}";
            }
            $musteri = '';
            if (!empty($data['musteri'])) {
                $musteri = "AND bsib2.musteri_id = {$data['musteri']}";
            }
            if (is_array($data['nastel'])) {
                $nastel = '';
                foreach ($data['nastel'] as $key => $item) {
                    $nastel .= ($key != 0) ? ",'{$item}'" : "'{$item}'";
                }
            } else {
                $nastel = $data['nastel'];
            }
            $sql = "select g.barcode,
                       g.id       as good_id,
                       ml.article as model_no,
                       s.size_type_id,
                       s.id       as size_id,
                       s.name     as size_name,
                       cp.code,
                       ml.name    as model,
                       mv.name    as variation,
                       cp.name    as pantone,
                       bsib.inventory as quantity,
                       bsib.nastel_no, 
                       ml.name    as model,
                       mrd.model_list_id as model_id,
                       mrd.model_var_id as model_var_id,
                       mrd.order_id as order_id,
                       mrd.order_item_id as order_item_id,
                       mo.musteri_id as musteri_id,
                       mv.name    as variation,
                       cp.name    as pantone
                from bichuv_service_item_balance bsib
                         inner join model_rel_doc mrd on mrd.nastel_no = bsib.nastel_no
                         left join model_orders mo on mrd.order_id = mo.id
                         inner join goods g on mrd.model_list_id = g.model_id
                         left join models_list ml on g.model_id = ml.id
                         left join models_variations mv on mrd.model_var_id = mv.id
                         left join color_pantone cp on mrd.color_id = cp.id
                         left join size s on g.size = s.id
                where bsib.id IN (select MAX(bsib2.id) from bichuv_service_item_balance bsib2
                                      where bsib2.nastel_no IN (%s) AND bsib2.type = %d %s %s %s GROUP BY bsib2.size_id,bsib2.model_id,bsib2.model_var,bsib2.nastel_no)
                and bsib.inventory > 0 AND bsib.size_id = g.size AND cp.id = g.color AND g.status = 1
                    GROUP BY s.id,bsib.nastel_no,bsib.id,g.id,cp.id,mv.id ORDER BY s.order ASC,bsib.nastel_no ASC;";
            $type = UslugaDoc::TYPE_SLICE ?? 1;
            $sql = sprintf($sql, $nastel, $type, $dept, $musteri, $nastelCondition);
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                $response['status'] = false;
                $response['message'] = Yii::t('app', 'Bunday raqamdagi kesim topilmadi yoki avval kiritilgan :(');
                if (!empty($result)) {
                    $response['status'] = true;
                    $response['items'] = $result;
                }
                return $response;
            }
        }
    }

    public function actionBarcodeCustomers($q, $nastelNo, $modelVar, $model){

        Yii::$app->response->format = Response::FORMAT_JSON;
        $conditionQ = "";
        if(!empty($q)){
            $conditionQ = " AND bc.name like '%{$q}%' ";
        }
        $sql = "select bc.name, bc.id from tikuv_doc_items tdi
                inner join model_rel_doc mrd on tdi.nastel_party_no = mrd.nastel_no
                left join goods g on g.model_id = mrd.model_list_id
                left join goods_barcode gb on g.id = gb.goods_id
                inner join barcode_customers bc on gb.bc_id = bc.id
                where tdi.nastel_party_no = '%s'
                  AND mrd.model_list_id = %d
                  AND g.model_id = %d AND g.color = mrd.color_id %s
                 GROUP BY bc.id;";
//        $sql = "select bc.name, bc.id from barcode_customers bc ORDER BY bc.name;";
        $sql = sprintf($sql, $nastelNo, $model, $model, $conditionQ);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $out = [];
        $out['results'] = [];
        array_push($out['results'], [
            'id' => 1,
            'text' => 'SAMO'
        ]);
        foreach ($results as $result) {
            array_push($out['results'], [
               'id' => $result['id'],
               'text' => $result['name']
            ]);
        }
        return $out;
    }
    public function actionBarcodeCustomersMain($nastelNo, $modelVar, $model){

        Yii::$app->response->format = Response::FORMAT_JSON;

        $sql = "select bc.name, bc.id from tikuv_doc_items tdi
                inner join model_rel_doc mrd on tdi.nastel_party_no = mrd.nastel_no
                left join goods g on g.model_id = mrd.model_list_id
                left join goods_barcode gb on g.id = gb.goods_id
                inner join barcode_customers bc on gb.bc_id = bc.id
                where tdi.nastel_party_no = '{$nastelNo}'
                  AND mrd.model_list_id = {$model}
                  AND g.model_id = {$model} AND g.color = mrd.color_id 
                 GROUP BY bc.id;";

        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $out = [];
        $out['results'] = [];
        array_push($out['results'], [
            'id' => 1,
            'text' => 'SAMO'
        ]);
        foreach ($results as $result) {
            array_push($out['results'], [
                'id' => $result['id'],
                'text' => $result['name']
            ]);
        }
        return $out;
    }
    public function actionUslugaBarcodeCustomers(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $conditionQ = "";
        $data = Yii::$app->request->get();
        $q = $data['q'];
        if(!empty($q)){
            $conditionQ = "bc.name like '%{$q}%' ";
        }
        if (is_array($data['nastel'])) {
            $nastel = '';
            foreach ($data['nastel'] as $key => $item) {
                $nastel .= ($key != 0) ? ",'{$item}'" : "'{$item}'";
            }
            $nastelCondition = "mrd.nastel_no in ({$nastel})";
        } else {
            $nastel = $data['nastel'];
            $nastelCondition = "mrd.nastel_no = '{$nastel}'";
        }
        $sql = "select bc.name, bc.id from goods g 
                left join goods_barcode gb on g.id = gb.goods_id
                left join barcode_customers bc on gb.bc_id = bc.id
                where %s
                 GROUP BY bc.id;";
        $sql = sprintf($sql, $conditionQ);
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        $out = [];
        $out['results'] = [];
        array_push($out['results'], [
            'id' => 1,
            'text' => 'SAMO'
        ]);
        foreach ($results as $result) {
            array_push($out['results'], [
                'id' => $result['id'],
                'text' => $result['name']
            ]);
        }
        return $out;
    }
    /**
     * @return string
     */
    public function actionReport()
    {

        $searchModel = new TOPPSearch();
        $dataProvider = $searchModel->reportDataProvider(Yii::$app->request->queryParams);

        return $this->render('report', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);

    }

    public function actionModelsVariationsChangePantoneId()
    {
        // http://textile-yii/uz/tikuv/tikuv-outcome-products-pack/models-variations-change-pantone-id
        $ColorPantone = ColorPantone::find()
            ->select(['id', 'code'])
            ->where(['status' => 1])
            ->asArray()
            ->indexBy('code')
            ->all();
        $ModelsVariations = ModelsVariations::find()
            ->select(['mv.id', 'mv.name', 'cp.code', 'cp.status'])
            ->from('models_variations mv')
            ->leftJoin('color_pantone cp', '`mv`.`color_pantone_id` = `cp`.`id`')
            ->where(['cp.status' => 2])
            ->asArray()
            ->all();
        echo "<pre>";
        $ModelsVariationsIDs = [];
        $transaction = Yii::$app->db->beginTransaction();
        $flag = true;
        foreach ($ModelsVariations as $modelsVariation) {
            if (isset($ColorPantone[$modelsVariation['code']])) {
                $ModelsVariationsModel = ModelsVariations::findOne($modelsVariation['id']);
                $ModelsVariationsModel->color_pantone_id = $ColorPantone[$modelsVariation['code']]['id'];
                if(!$ModelsVariationsModel->save()){
                    $flag = false;
                }
                echo $modelsVariation['id'] ."\n";
                $ModelsVariationsIDs[] = [
                    'model_id' => $modelsVariation['id'],
                    'color_pantone_id' => $ColorPantone[$modelsVariation['code']]['id'],
                ];
            }
        }
        if ($flag === true) {
            $transaction->commit();
        } else {
            $transaction->rollBack();
        }
        echo "NATIJA: ";
        var_dump($flag);
        die;
        return;
    }

    /**
     * @param $q
     * @param $dept
     * @param $order
     * @param $orderItem
     * @param $index
     * @return array
     * @throws Exception
     */
    public function actionAjaxRequest($q, $dept, $order, $orderItem, $index)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $params = [
            'q' => $q,
            'dept' => $dept,
            'order' => $order,
            'orderItem' => $orderItem,
            'index' => $index
        ];
        $model = new TikuvGoodsDocPack();

        return $model->searchAjax($params);
    }

    /**
     * Finds the TikuvOutcomeProductsPack model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TikuvOutcomeProductsPack the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TikuvOutcomeProductsPack::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
