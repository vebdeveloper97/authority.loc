<?php

namespace app\modules\bichuv\controllers;

use app\modules\base\models\ModelsAcs;
use app\modules\bichuv\models\BichuvAcceptedMatoFromProduction;
use app\modules\bichuv\models\BichuvDetailTypes;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvGivenRollItems;
use app\modules\bichuv\models\BichuvGivenRollItemsAcs;
use app\modules\bichuv\models\BichuvGivenRolls;
use app\modules\bichuv\models\BichuvNastelDetailItems;
use app\modules\bichuv\models\BichuvNastelDetails;
use app\modules\bichuv\models\BichuvNastelItemsSearch;
use app\modules\bichuv\models\BichuvServiceItemBalance;
use app\modules\bichuv\models\BichuvSliceItemBalance;
use app\modules\bichuv\models\ModelRelProduction;
use app\modules\tikuv\models\ModelRelDoc;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\usluga\models\UslugaDoc;
use Yii;
use app\modules\bichuv\models\ClearNastelForm;
use app\modules\bichuv\models\ClearNastelFormSearch;
use app\modules\bichuv\controllers\BaseController;
use yii\db\Exception;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ClearNastelFormController implements the CRUD actions for ClearNastelForm model.
 */
class ClearNastelFormController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    /**
     * @param $orderId
     * @return array
     * @throws Exception
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
     * Lists all ClearNastelForm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClearNastelFormSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClearNastelForm model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = ClearNastelForm::findOne($id);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
            ]);
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing ClearNastelForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $nastel
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     */
    public function actionDelete($nastel)
    {
        $model = $this->findModel($nastel);
        $info['status'] = 'error';
        $info['message'] = Yii::t('app', "Hatolik yuz berdi");
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post()) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                $nastel_party = $model->nastel_party;
                $model_id = $model->id;
                $qabul_kesim = BichuvDoc::find()->joinWith(['bichuvSliceItems'])->where(['bichuv_slice_items.nastel_party'=>$nastel_party])->all();
                if(!$qabul_kesim){
                    $info['status'] = 'error';
                    $info['message'] = Yii::t('app', "Qabul kesim topilmadi");
                }else {
                    try {
                        $tikuv = TikuvDoc::find()->alias('td')->joinWith('tikuvDocItems tdi')->where(['td.status' => 3, 'nastel_party_no' => $nastel_party])->groupBy('td.id')->count();
                        $usluga = UslugaDoc::find()->alias('ud')->joinWith('uslugaDocItems udi')->where(['ud.status' => 3, 'udi.nastel_party' => $nastel_party])->groupBy('ud.id')->count();
                        $check = $tikuv + $usluga;
                        if ($check > 0) {
                            Yii::$app->session->setFlash('error', Yii::t('app', "Bu nastil boshqa bo'limga qabul bo'lgan"));
                            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
                        }
                        $tikuv = TikuvDoc::find()->alias('td')->joinWith('tikuvDocItems tdi')->where(['td.status' => 1, 'nastel_party_no' => $nastel_party])->groupBy('td.id')->all();
                        $usluga = UslugaDoc::find()->alias('ud')->joinWith('uslugaDocItems udi')->where(['ud.status' => 1, 'udi.nastel_party' => $nastel_party])->groupBy('ud.id')->all();
                        if ($tikuv) {
                            foreach ($tikuv as $item) {
                                if ($item->deleteOne()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    break;
                                }
                            }
                        } else {
                            $saved = true;
                        }
                        if ($saved) {
                            if ($usluga) {
                                foreach ($usluga as $item) {
                                    if ($item->delete()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            } else {
                                $saved = true;
                            }
                        }
                        if ($saved) {
                            if ($qabul_kesim) {
                                foreach ($qabul_kesim as $item) {
                                    if ($item->deleteOne()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            } else {
                                $info['status'] = 'error';
                                $info['message'] = Yii::t('app', "Qabul kesim topilmadi");
                                $saved = false;
                            }
                        }
                        $nastel_balance = BichuvSliceItemBalance::find()->where(['party_no' => $nastel_party])->all();
                        if ($saved) {
                            if ($nastel_balance) {
                                foreach ($nastel_balance as $item) {
                                    if ($item->delete()) {
                                        $saved = true;
                                    } else {
                                        $saved = false;
                                        break;
                                    }
                                }
                            }
                        }
                        $bichuv_mato_accepted = BichuvAcceptedMatoFromProduction::findOne(['bichuv_given_roll_id' => $model_id]);
                        if ($saved && $bichuv_mato_accepted) {
                            if ($bichuv_mato_accepted->delete()) {
                                $saved = true;
                            } else {
                                $saved = false;
                            }
                        }
                        if ($saved) {
                            $info['status'] = 'success';
                            $info['message'] = Yii::t('app', "Muvaffaqiyatli bajarildi");
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();
                        }
                    } catch (\Exception $e) {
                        Yii::info('Not saved' . $e, 'save');
                        $transaction->rollBack();
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
                            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
                        }
                        return $response;
                    }
                }
            }
        }
        Yii::$app->session->setFlash($info['status'], $info['message']);
        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }
    public function actionUpdate($id)
    {
        $acs = BichuvDetailTypes::getType('ACCESSORY');
        $model = ClearNastelForm::findOne($id);
        $modelId = $model->id;
        $matoList = BichuvGivenRollItems::find()->where(['not in', 'bichuv_detail_type_id', $acs])->andWhere(['bichuv_given_roll_id' => $modelId])->all();
        $modelNastel = new BichuvNastelDetails();
        $modelNastelItems = new BichuvNastelDetailItems();
         $models = !empty($matoList) ? $matoList : [new BichuvGivenRollItems()];
        if (empty($models)) {
            $models = [new BichuvGivenRollItems()];
        }
        $isPlan = false;
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
                    $modelId = $model->id;
                    $productModelId = null;
                    $nastelNo = $model->nastel_party;
                     if (!empty($data['BichuvGivenRollItems'])) {
                        foreach ($data['BichuvGivenRollItems'] as $key => $item) {
                            $saved = false;
                            $modelBGRI = BichuvGivenRollItems::findOne($item['id']);
                            if($modelBGRI) {
                                $modelBGRI->setAttributes([
                                    'required_count' => $item['required_count'],
                                ]);
                                if ($modelBGRI->save(false)) {
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
                            } else {
                                Yii::$app->session->setFlash('error', Yii::t('app', 'Item topilmadi'));
                                $saved = false;
                                break;
                            }
                        }
                    }
                    if ($saved) {
                        $transaction->commit();
                         return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            } catch (Exception $e) {
                Yii::info('Not saved' . $e, 'save');
            }
        }
        $acsList = BichuvGivenRollItemsAcs::find()->where(['bichuv_detail_type_id' => $acs, 'bichuv_given_roll_id' => $modelId])->all();
        $modelsAcs = !empty($acsList) ? $acsList : [new BichuvGivenRollItemsAcs()];
        $modelBD = new BichuvDoc();
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
        if (Yii::$app->request->isAjax)
        {
            return $this->renderAjax('update', [
                'model' => $model,
                'models' => $models,
                'modelBD' => $modelBD,
                'modelNastel' => $modelNastel,
                'modelNastelItems' => $modelNastelItems,
                'modelsAcs' => $modelsAcs,
            ]);
        }
        return $this->render('update', [
             'model' => $model,
            'models' => $models,
            'modelBD' => $modelBD,
            'modelNastel' => $modelNastel,
            'modelNastelItems' => $modelNastelItems,
            'modelsAcs' => $modelsAcs,
        ]);
    }
    public function actionNastelUpdate($nastel)
    {
        $model = $this->findModel($nastel);
        $isPlan = false;
        $check = ModelRelDoc::findOne(['nastel_no'=>$model->nastel_party]);
        if ($check) {
            Yii::$app->session->setFlash('error', Yii::t('app', "Model tasdiqlab bo'lingan!!!"));
            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
        }
        if (Yii::$app->request->isPost && !$isPlan) {
            $transaction = Yii::$app->db->beginTransaction();
            $data = Yii::$app->request->post();
            try {
                $saved = false;
                if ($model->load($data)&&$model->save()) {
                    if (!empty($model->modelRelProductions)) {
                        ModelRelProduction::deleteAll(['bichuv_given_roll_id' => $model->id]);
                    }
                    $modelId = $model->id;
                    $productModelId = null;
                    $nastelNo = $model->nastel_party;
                    if(!empty($data['ClearNastelForm']['model_list_id']) && !empty($data['ClearNastelForm']['model_var_id'])){
                        foreach ($data['ClearNastelForm']['model_var_id'] as $item) {
                            $orderItemId = null;
                            $price = 0;
                            $pb_id = null;
                            if(!empty($data['BichuvOrderData'])){
                                if(array_key_exists($item,$data['BichuvOrderData'])){
                                    $orderItemId = $data['BichuvOrderData'][$item]['order_item_id'];
                                    $price = $data['BichuvOrderData'][$item]['price'];
                                    $pb_id = $data['BichuvOrderData'][$item]['pb_id'];
                                }
                            }
                            if(!empty($data['BichuvOrderData'][$item]['part']) && $data['BichuvOrderData'][$item]['part'] > 0){
                                foreach ($data['BichuvOrderData'][$item]['part'] as $val){
                                    if(!empty($val)){
                                        $modelRelProd = new ModelRelProduction();
                                        $modelRelData = [
                                            'models_list_id' => $data['ClearNastelForm']['model_list_id'],
                                            'model_variation_id' => $item,
                                            'bichuv_given_roll_id' => $modelId,
                                            'order_id' => (int)$data['ClearNastelForm']['order_id'],
                                            'order_item_id' => $orderItemId,
                                            'price' => $price,
                                            'pb_id' => $pb_id,
                                            'model_var_part_id' => $val,
                                            'nastel_no' => $model->nastel_party,
                                            'type' => 2
                                        ];
                                        $modelRelProd->setAttributes($modelRelData);
                                        if($modelRelProd->save()){
                                            $saved = true;
                                        }else{
                                            if ($modelRelProd->hasErrors()) {
                                                $res = [
                                                    'status' => 'error',
                                                    'data' => $item,
                                                    'message' => $modelRelProd->getErrors(),
                                                ];
                                                Yii::info($res, 'save');
                                            }
                                            $saved = false;
                                            break 2;
                                        }
                                    }
                                }
                            }else{
                                $modelRelData = [
                                    'models_list_id' => $data['ClearNastelForm']['model_list_id'],
                                    'model_variation_id' => $item,
                                    'bichuv_given_roll_id' => $modelId,
                                    'order_id' => (int)$data['ClearNastelForm']['order_id'],
                                    'order_item_id' => $orderItemId,
                                    'price' => $price,
                                    'pb_id' => $pb_id,
                                    'type' => 1,
                                    'nastel_no' => $model->nastel_party
                                ];
                                $modelRelProd = new ModelRelProduction();
                                $modelRelProd->setAttributes($modelRelData);
                                if($modelRelProd->save()){
                                    $saved = true;
                                }else{
                                    if ($modelRelProd->hasErrors()) {
                                        $res = [
                                            'status' => 'error',
                                            'data' => $item,
                                            'message' => $modelRelProd->getErrors(),
                                        ];
                                        Yii::info($res, 'save');
                                    }
                                    $saved = false;
                                    break;
                                }
                                $modelRelProd->setAttributes($modelRelData);
                            }
                        }
                    }else{
                        \yii\helpers\VarDumper::dump($data,10,true);
                    }
                    if ($saved) {
                        Yii::$app->session->setFlash('success', Yii::t('app', "Saved Successfully"));
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelId]);
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
        return $this->render('nastel-update', [
            'model' => $model,
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
    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "clear-nastel-form_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => ClearNastelForm::find()->select([
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
     * Finds the ClearNastelForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClearNastelForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClearNastelForm::findOne(['nastel_party'=>$id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
