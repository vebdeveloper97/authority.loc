<?php

namespace app\modules\base\controllers;

use app\models\UploadForms;
use app\modules\base\models\Attachments;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelOrdersItemsMaterial;
use app\modules\base\models\ModelOrdersVariations;
use app\modules\base\models\ModelRelAttach;
use app\modules\base\models\ModelsAcs;
use app\modules\base\models\ModelsNaqsh;
use app\modules\base\models\ModelsPechat;
use app\modules\base\models\ModelsRawMaterials;
use app\modules\base\models\ModelsToquvAcs;
use app\modules\base\models\ModelsVariationColors;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\ModelTypes;
use app\modules\base\models\ModelVarPrints;
use app\modules\base\models\ModelVarStone;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\toquv\models\ToquvRawMaterialColor;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\ToquvRawMaterialType;
use app\modules\wms\models\WmsMatoInfo;
use Exception;
use moonland\phpexcel\Excel;
use Yii;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsListSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ModelsListController implements the CRUD actions for ModelsList model.
 */
class ModelsListController extends BaseController
{

    public function beforeAction($action)
    {
        if($action->id == 'file-upload'){
            $this->enableCsrfValidation = false;
        }
        if(parent::beforeAction($action)){
            return true;
        }
    }

    /**
     * Lists all ModelsList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModelsListSearch();
        $userId = Yii::$app->user->id;
        if($userId!=1) {
            $searchModel->created_by = Yii::$app->user->id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ModelsList model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $isModel = true;
        $modelVars = ModelsVariations::find()->where(['model_list_id' => $model->id])->all()?ModelsVariations::find()->where(['model_list_id' => $model->id])->all():false;
        $pechat = [];
        $naqsh = [];
        if($modelVars){
            foreach ($modelVars as $k => $modelVar) {
                if($modelVar->modelVarStones){
                    foreach ($modelVar->modelVarStones as $key => $modelVarStone) {
                        $naqsh[$k] = $modelVarStone;
                        if($modelVarStone['image']){
                            $naqsh[$k]['image'] = [$modelVarStone['image']];
                        }
                    }
                }
                if($modelVar->modelVarPrints){
                    foreach ($modelVar->modelVarPrints as $key => $modelVarPrint) {
                        $pechat[$k] = $modelVarPrint;
                        if($modelVarPrint['image']){
                            $pechat[$k]['image'] = [$modelVarPrint['image']];
                        }
                    }
                }
            }

        }
        $list = Yii::$app->request->get('list');

        if(Yii::$app->request->isAjax){
            if(isset($list))
                return $this->renderAjax('view', [
                    'model' => $model,
                    'isModel' => $isModel,
                    'list' => $list,
                    'pechat' => $pechat?$pechat:[new ModelVarPrints()],
                    'naqsh' => $naqsh?$naqsh:[new ModelVarStone()],
                ]);
            return $this->renderAjax('view', [
                'model' => $model,
                'isModel' => $isModel,
                'pechat' => $pechat?$pechat:[new ModelVarPrints()],
                'naqsh' => $naqsh?$naqsh:[new ModelVarStone()],
            ]);
        }
        if(isset($list))
            return $this->render('view', [
                'model' => $model,
                'isModel' => $isModel,
                'list' => $list,
                'pechat' => $pechat?$pechat:[new ModelVarPrints()],
                'naqsh' => $naqsh?$naqsh:[new ModelVarStone()],
            ]);
        return $this->render('view', [
            'model' => $model,
            'isModel' => $isModel,
            'pechat' => $pechat?$pechat:[new ModelVarPrints()],
            'naqsh' => $naqsh?$naqsh:[new ModelVarStone()],
        ]);
    }

    /**
     * Creates a new ModelsList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $app = Yii::$app;
        $id = $app->request->get('id');
        $list = $app->request->get('list');

        $model = new ModelsList();
        $rawMaterials = [new ModelsRawMaterials()];
        $toquvRawMaterials = [new ModelsToquvAcs()];

        if(isset($id) && !empty($id)){
            $model->base_pattern_id = $model->getPatternsId($id);
            $rawMaterials = [new ModelsRawMaterials()];
        }
        $model->cp['upload'] = new UploadForms();
        if ($model->load(Yii::$app->request->post())) {
            if(isset($list)){
                $modelOrdersItems = ModelOrdersItems::findOne(['id' => $list]);
                $modelOrdersItemsMaterials = $modelOrdersItems->getModelOrdersItemsMaterial()->where(['model_orders_items_material.status' => 1])->all()?$modelOrdersItems->getModelOrdersItemsMaterial()->where(['model_orders_items_material.status' => 1])->all():[new ModelsRawMaterials()];
                $acs = $modelOrdersItems->getModelOrdersItemsAcs()->where(['status' => 1])->all()?$modelOrdersItems->getModelOrdersItemsAcs()->where(['status' => 1])->all():[new ModelsAcs()];
                $toquvRawMaterials1 = $modelOrdersItems->getModelOrdersItemsToquvAcs()->where(['status' => 1])->all()?$modelOrdersItems->getModelOrdersItemsToquvAcs()->where(['status' => 1])->all():[new ModelsToquvAcs()];
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try{
                    $models = ModelOrdersItems::find()->where(['id' => $list])->all();
                    if($model->save()){
                        $saved = true;
                        foreach ($models as $item) {
                            $item->models_list_id = $model->id;
                            if($item->save() && $saved){
                                $saved = true;
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                    }

                    if($modelOrdersItemsMaterials){
                        foreach ($modelOrdersItemsMaterials as $rawMaterial){
                            $wmsMatoInfo = WmsMatoInfo::findOne([
                                'id' => $rawMaterial['mato_id'],
                            ]);
                            if($wmsMatoInfo){
                                $modelsMaterials = new ModelsRawMaterials();
                                $modelsMaterials->model_list_id = $model->id;
                                $modelsMaterials->rm_id = $wmsMatoInfo->toquv_raw_materials_id;

                                if($modelsMaterials->save(false)){
                                    $saved = true;
                                    unset($modelsMaterials);
                                }
                                else{
                                    $saved = false;
                                    break;
                                }
                            }
                            else{
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error',Yii::t('app', 'WmsMatoInfo da Material topilmadi!'));
                                return $this->refresh();
                            }
                        }
                    }

                    if($acs){
                        foreach($acs as $item){
                            $bichuvacs = new ModelsAcs();
                            $bichuvacs->model_list_id = $model->id;
                            $bichuvacs->bichuv_acs_id = $item['bichuv_acs_id'];
                            $bichuvacs->qty = $item['qty'];
                            $bichuvacs->add_info = $item['add_info'];
                            if($bichuvacs->save() && $saved){
                                $saved = true;
                                unset($bichuvacs);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                    }

                    if($toquvRawMaterials1){
                        foreach ($toquvRawMaterials1 as $toquvRawMaterial){
                            $wmsMatoInfo = WmsMatoInfo::findOne([
                                'id' => $toquvRawMaterial['wms_mato_info_id'],
                                'type' => ToquvRawMaterials::ACS
                            ]);

                            if($wmsMatoInfo){
                                $toquvAcs = new ModelsToquvAcs();
                                $toquvAcs->models_list_id = $model->id;
                                $toquvAcs->wms_mato_info_id = $wmsMatoInfo->id;
                                $toquvAcs->qty = $toquvRawMaterial['count'];
                                if($toquvAcs->save()){
                                    $saved = true;
                                    unset($toquvAcs);
                                }
                                else{
                                    $saved = false;
                                    break;
                                }
                            }
                            else{
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error',Yii::t('app', 'WmsMatoInfo da Toquv Aksessuar topilmadi!'));
                            }
                        }
                    }
                    $data = Yii::$app->request->post();
                    if(!empty($data['ModelsList']['images'])) {
                        $model->saveAttachments($data['ModelsList']['images']);
                    }
                    if($saved){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
                        return $this->redirect(['model-orders/check-order', 'id' => $modelOrdersItems->model_orders_id]);
                    }
                    else{
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error',Yii::t('app','Models List yaratilmadi!'));
                        return $this->redirect(['model-orders/check-order', 'id' => $modelOrdersItems->model_orders_id]);
                    }
                }
                catch(\Exception $e){
                    Yii::info('error message '.$e->getMessage(),'save');
                }

            }
            else{
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if($model->save()){
                        $saved = true;
                    }
                    if($saved){
                        $data = Yii::$app->request->post();
                        /*if(!empty($data['ModelsList']['images'])) {
                            $model->saveAttachments($data['ModelsList']['images']);
                        }*/
                        if($data['ModelsList']['model_images']){
                            $attributeData['model_id'] =  $model->id;
                            $attributeData['model_images'] = $data['ModelsList']['model_images'];
                            $saved = $saved && $model->saveModelAttachments($attributeData);
                        }
                        if($saved){
                            $transaction->commit();
                            Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
                            return $this->redirect(['update', 'id' => $model->id, 'active' => 'material']);
                        }
                        else{
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', Yii::t('app','Error'));
                        }
                    }
                }
                catch (\Exception $e) {
                    if(!empty($e->errorInfo)){
                        foreach ($e->errorInfo as $error) {
                            Yii::$app->session->setFlash('error',Yii::t('app',$error));
                        }
                    }
                    Yii::info($e, 'save');
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'rawMaterials' => $rawMaterials,
            'acs' => [new ModelsAcs()],
            'variations' => [new ModelsVariations()],
            'list' => $list,
            'toquvRawMaterials' => $toquvRawMaterials
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelRelAttachment = $model->modelRelAttaches;
        if (!empty($modelRelAttachment)){
            $img = [];
            foreach ($modelRelAttachment as $key => $attachment){
                $img[] = $attachment->attachment->path;
            }
            $model['model_images'] = $img;
        }
        $list = Yii::$app->request->get('list');
        $rawMaterials = $model->modelsRawMaterials ? $model->modelsRawMaterials : [new ModelsRawMaterials()];
        $acs = $model->modelsAcs ? $model->modelsAcs : [new ModelsAcs()];
        $toquvRawMaterials = $model->modelsToquvAcs ? $model->modelsToquvAcs : [new ModelsToquvAcs()];

        if(!empty($toquvRawMaterials)){
            // mato info dagi ma'lumotlarni olish
            foreach ($toquvRawMaterials as $documentItem) {
                $AcsInfo = WmsMatoInfo::findOne(['id' => $documentItem->wms_mato_info_id]);
                if ($AcsInfo) {
                    $documentItem->toquv_acs_id = $AcsInfo->toquv_raw_materials_id;
                    $documentItem->pus_fine_id = $AcsInfo->pus_fine_id;
                    $documentItem->en = $AcsInfo->en;
                    $documentItem->gramaj = $AcsInfo->gramaj;
                    $documentItem->wms_color_id = $AcsInfo->wms_color_id;
                    $documentItem->wms_desen_id = $AcsInfo->wms_desen_id;
                }
            }
        }

        $variations = ($model->modelsVariations)?$model->modelsVariations:[new ModelsVariations()];

        $model->cp['upload'] = new UploadForms();
        /** Malumotlarni saqlash */
        if(Yii::$app->request->isPost) {
            $saved = false;
            if ($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $data = Yii::$app->request->post();
                    if ($model->save()) {
                        $saved = true;
                        /** Model attachment delete**/
                        if ($model->modelRelAttaches){
                            ModelRelAttach::deleteAll(['model_list_id' => $model->id]);
                        }

                        if (($model->modelsRawMaterials)) {
                            foreach (($model->modelsRawMaterials) as $item) {
                                $item->delete();
                            }
                        }
                        if (($model->modelsAcs)) {
                            foreach (($model->modelsAcs) as $item) {
                                $item->delete();
                            }
                        }
                        if (($model->modelsToquvAcs)) {
                            foreach (($model->modelsToquvAcs) as $item) {
                                $item->delete();
                            }
                        }
                        if (($model->pechats)){
                            foreach ($model->pechats as $item){
                                $item->delete();
                            }
                        }
                        if (($model->naqshs)){
                            foreach ($model->naqshs as $item){
                                $item->delete();
                            }
                        }

                        if (!empty($data['ModelsRawMaterials']) && $saved) {
                            $saved = $model->saveMaterials($data['ModelsRawMaterials']);
                        }
                        if (!empty($data['ModelsToquvAcs']) && $saved) {
                            $saved = $model->saveToquvAcs($data['ModelsToquvAcs']);
                        }
                        if (!empty($data['ModelsAcs']) && $saved) {
                            $saved = $model->saveAcs($data['ModelsAcs']);
                        }
                        if ($data['ModelsPechat'] != null && $saved){
                            $saved = $model->savePechats($data['ModelsPechat']);
                        }
                        if ($data['ModelsNaqsh'] != null && $saved){
                            $saved = $model->saveNaqshs($data['ModelsNaqsh']);
                        }
                        if (!empty($data['ModelsList']['remove']) && $saved) {
                            $model->removeAttachments($data['ModelsList']['remove']);
                        }
                        if (!empty($data['ModelsList']['sketch']['remove']) && $saved) {
                            $model->removeItems('sketch', $data['ModelsList']['sketch']['remove']);
                        }
                        if (!empty($data['ModelsList']['measurement']['remove']) && $saved) {
                            $model->removeItems('measurement', $data['ModelsList']['measurement']['remove']);
                        }
                        if($data['ModelsList']['model_images']){
                            $attributeData['model_id'] =  $model->id;
                            $attributeData['model_images'] = $data['ModelsList']['model_images'];
                            $saved = $model->saveModelAttachments($attributeData);
                        }
                    }
                    if ($saved) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Updates Data'));
                        if(isset($list))
                            return $this->redirect(['view', 'id' => $model->id, 'list' => $list]);
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
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
            'rawMaterials' => $rawMaterials,
            'acs' => $acs,
            'toquvRawMaterials' => $toquvRawMaterials,
            /*'pechat' => $pechat,
            'naqsh' => $naqsh,*/
            'variations' => $variations,
            /*'pechatImages' => $pechatImages,
            'naqshImages' => $naqshImages,*/
        ]);
    }

    /**
     * @param null $id
     * @return array
     */
    public function actionGetModelTypes($id){
        $this->layout = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        $response['message'] = 'error';
        if(Yii::$app->request->isAjax){
            $query = ModelTypes::find()
                ->andWhere(['parent' => $id])
                ->asArray()
                ->all();
            if(!empty($query)){
                $response['status'] = 1;
                $response['message'] = 'success';
                $response['data'] = ArrayHelper::map($query,'id','name');
            }
        }
        return $response;
    }
    public function actionFileUpload($id=null){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new UploadForms();
        if(Yii::$app->request->isPost){
            if($model->images = UploadedFile::getInstances($model, 'images')){
                return $model->upload('model');
            }
            if ($sketch = UploadedFile::getInstances($model, 'sketch')){
                $modelList = ModelsList::findOne($id);
                if($modelList) {
                    return $modelList->uploadSketch($sketch, 'sketch');
                }else{
                    return 'error';
                }
            }
            if ($files = UploadedFile::getInstances($model, 'files')){
                $modelList = ModelsList::findOne($id);
                if($modelList) {
                    return $modelList->uploadMeasurement($files, 'measurement');
                }else{
                    return 'error';
                }
            }
            if ($files = UploadedFile::getInstances($model, 'comment_attachments')){
                $modelList = ModelsList::findOne($id);
                if($modelList) {
                    return $modelList->uploadCommentAttachment($files, 'comment_attachments');
                }else{
                    return 'error';
                }
            }
        }
        return false;
    }

    /**
     * Deletes an existing ModelsList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = $model::STATUS_INACTIVE;
        $model->save();
        return $this->redirect(['index']);
    }


    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = $model::STATUS_SAVED;
        $model->save();
        return $this->redirect(['index']);
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
            $colorType = Yii::$app->request->get('colorType','pantone');
            if($colorType == 'pantone'){
                $res = $searchModel->getColorList($q);
                if (!empty($res)) {
                    foreach ($res as $item) {
                        $name = "<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>CP</span></span> ".$item['ccode'] . " - <b>"
                            . $item['cname'] . "</b>";
                        $boyoq_name = (!empty($item['color_boyoq_id']))?"<span style='background:{$item['color_boyoq']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>CB</span></span> - <b>"
                            . $item['color_boyoq_name'] . "</b>":"";
                        array_push($response['results'], [
                            'id' => $item['id'],
                            'text' => $name,
                            'boyoq_id' => $item['color_boyoq_id'],
                            'boyoq_text' => $boyoq_name,
                        ]);
                    }
                }
            }else{
                $res = $searchModel->getColorBoyoqhonaList($q);
                if (!empty($res)) {
                    foreach ($res as $item) {
                        $name = "<span style='background:".$item['color']."; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>bbb</span></span> ".$item['name']." <b>".$item['color_id']."</b> ".$item['tone'];
                        $pantone_name = (!empty($item['ccode']))?"<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>CP</span></span> ".$item['ccode'] . " - <b>"
                            . $item['cname'] . "</b>":"";
                        array_push($response['results'], [
                            'id' => $item['id'],
                            'text' => $name,
                            'pantone_id' => $item['pantone_id'],
                            'pantone_text' => $pantone_name,
                        ]);
                    }
                }
            }
        } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                ];
        }
        return $response;
    }
    public function actionExportExcel(){
        $searchModel = new ModelsListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,1);
        header('Content-Type: application/vnd.ms-excel');
        $filename = "model_list_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => $dataProvider->getModels(),
            'columns' => [
                'id',
                [
                    'attribute' => 'name',
                    'value' => function($model){
                        return $model->name;
                    },
                    'format' => 'html'
                ],
                'long_name',
                'article',
                [
                    'attribute' => 'rawMaterials',
                    'contentOptions' => ['style' => 'width:15%;'],
                    'value' => function($model){
                        $rms = $model->modelsRawMaterials;
                        if (!empty($rms)) {
                            $return = '';
                            foreach ($rms as $k => $rm) {
                                $return .= $rm->rm->rawMaterialConsist . ", " ;
                            }
                            return $return;
                        }
                        return '';
                    },
                    'format' => 'html',
                ],
                [
                    'attribute' => 'brend_id',
                    'contentOptions' => ['style' => 'width:15%;'],
                    'value' => function($model){
                        return $model->brend->name;
                    },
                ],
                [
                    'attribute' => 'view_id',
                    'value' => function($model){
                        return $model->view->name;
                    }
                ],
                [
                    'attribute' => 'type_id',
                    'value' => function($model){
                        return $model->type->name;
                    }
                ],
                [
                    'attribute' => 'model_season',
                    'value' => function($model){
                        return $model->modelSeason->name;
                    }
                ],
                [
                    'attribute' => 'created_by',
                    'contentOptions' => ['style' => 'width:15%;'],
                    'value' => function($model){
                        return $model->author->user_fio;
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function($model){
                        return date('d.m.Y H:i',$model->created_at);
                    }
                ],
            ],
            'headers' => [
                'id' => 'Id',
            ],
            'autoSize' => true,
        ]);
    }

    public function actionStatistics()
    {
        $model = new ModelsList();
        $dataProvider = $model->getModelAthorsStatistics(Yii::$app->request->queryParams);

        return $this->render('statistics', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreatePechat()
    {
        $app = Yii::$app;
        if($app->request->isAjax){
            $app->response->format = Response::FORMAT_JSON;
            $response['width'] = Yii::$app->request->get('title');
            $response['height'] = Yii::$app->request->get('content');
            $response['base_details'] = Yii::$app->request->get('base_details');
            $response['attachment'] = Yii::$app->request->get('attachments');
            $response['id'] = Yii::$app->request->get('id');
            $response['name'] = Yii::$app->request->get('name');
            $transaction = $app->db->beginTransaction();
            $saved = true;
            try{
                if($saved){
                    $model = new  ModelVarPrints();
                    $model->setAttributes([
                        'models_list_id' => $response['id'],
                        'image' => $response['attachment'],
                        'name' => $response['name'],
                        'width' => $response['width'],
                        'height' => $response['height'],
                        'base_details_list_id' => $response['base_details'],
                        'status' => ModelsList::STATUS_ACTIVE
                    ]);
                    $model->setScenario(ModelVarPrints::MODELSLIST_CODE);
                    if($model->save() && $saved){
                        $saved = true;
                    }
                    else{
                        $saved = false;
                    }
                }
                if($saved){
                    $transaction->commit();
                    $response['status'] = 1;
                    $response['data'] = ModelVarPrints::findOne($model->id);
                    return $response;
                }
                else{
                    $transaction->rollBack();
                    $response['status'] = 0;
                    return $response;
                }
            }
            catch(\Exception $e){
                Yii::info('Error Message '.$e->getMessage(), 'save');
            }
        }
    }

    public function actionCreateNewItem()
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            $this->enableCsrfValidation = false;
            $name = trim(Yii::$app->request->post('name'));
            $type = trim(Yii::$app->request->post('type'));
            $getModel = trim(Yii::$app->request->post('model'));
            $model = null;

            if($getModel == 'toquv-raw-material-type') {
                $model = new ToquvRawMaterialType();
                $model->type = $type;
                $model->name = $name;
            }

            if($getModel == 'toquv-raw-material-color') {
                $model = new ToquvRawMaterialColor();
                $model->name = $name;
            }

            if($model->save()){
                $response['success'] = true;
                $response['selected_id'] = $model->id;
                $response['title'] = $model->name;
                return $model->id;
            }else{
                return 'fiel';
            }
            return $response;
        }
        else{
            return $this->redirect($request->referrer);
        }

    }

    public function actionCreateNaqsh()
    {
        $app = Yii::$app;
        if($app->request->isAjax){
            $app->response->format = Response::FORMAT_JSON;
            $response['width'] = Yii::$app->request->get('title');
            $response['name'] = Yii::$app->request->get('name');
            $response['height'] = Yii::$app->request->get('content');
            $response['base_details'] = Yii::$app->request->get('base_details');
            $response['attachment'] = Yii::$app->request->get('attachments');
            $response['id'] = Yii::$app->request->get('id');
            $transaction = $app->db->beginTransaction();
            $saved = true;
            try{

                if($saved){
                    $model = new  ModelVarStone();
                    $model->setAttributes([
                        'models_list_id' => $response['id'],
                        'image' => $response['attachment'],
                        'width' => $response['width'],
                        'height' => $response['height'],
                        'name' => $response['name'],
                        'base_details_list_id' => $response['base_details'],
                        'status' => ModelVarStone::STATUS_ACTIVE
                    ]);
                    if($model->save() && $saved){
                        $saved = true;
                    }
                    else{
                        $saved = false;
                    }
                }

                if($saved){
                    $transaction->commit();
                    $response['status'] = 1;
                    $response['data'] = ModelVarStone::findOne($model->id);
                    return $response;
                }
                else{
                    $transaction->rollBack();
                    $response['status'] = 0;
                    return $response;
                }
            }
            catch(\Exception $e){
                Yii::info('Error Message '.$e->getMessage(), 'save');
            }
        }
    }

    /**
     * Finds the ModelsList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelsList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelsList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
