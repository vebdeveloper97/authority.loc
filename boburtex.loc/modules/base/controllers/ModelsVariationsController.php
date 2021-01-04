<?php

namespace app\modules\base\controllers;

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelOrdersItemsAcs;
use app\modules\base\models\ModelOrdersItemsMaterial;
use app\modules\base\models\ModelsAcs;
use app\modules\base\models\ModelsAcsVariations;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsNaqsh;
use app\modules\base\models\ModelsPechat;
use app\modules\base\models\ModelsRawMaterials;
use app\modules\base\models\ModelsVariationColors;
use app\modules\base\models\ModelVarBaski;
use app\modules\base\models\ModelVariationParts;
use app\modules\base\models\ModelVarPrints;
use app\modules\base\models\ModelVarRelAttach;
use app\modules\base\models\ModelVarStone;
use app\modules\bichuv\Bichuv;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\wms\models\WmsMatoInfo;
use Yii;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\ModelsVariationsSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\UploadForm;
use yii\widgets\ActiveForm;

/**
 * ModelsVariationsController implements the CRUD actions for ModelsVariations model.
 */
class ModelsVariationsController extends BaseController
{
    /**
     * Lists all ModelsVariations models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModelsVariationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionList($id)
    {
        $modelList = ModelsList::findOne($id);
        if($modelList){
            if(Yii::$app->request->isAjax){
                return $this->renderAjax('list', [
                    'modelList' => $modelList,
                    'variations' => $modelList->modelsVariations
                ]);
            }
            return $this->render('list', [
                'modelList' => $modelList,
                'variations' => $modelList->modelsVariations
            ]);
        }
        return false;
    }

    public function actionColors($id)
    {
        $model = ModelsVariations::findOne($id);
        if($model) {
            return $this->renderAjax('view/_colors', [
                'colors' => $model->modelsVariationColors,
            ]);
        }
        return false;
    }
    /**
     * Displays a single ModelsVariations model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $is = Yii::$app->request->get('isModel');
        $isModel = $is?$is:false;
        if(Yii::$app->request->isAjax) {
                $modelList = $model->modelList;
                /** Acc malumotlarini olish */
                $modelsAcsAll = ModelsAcs::find()->where(['model_list_id' => $model->model_list_id])->all();
                $acc = [];
                if($modelsAcsAll){
                    foreach ($modelsAcsAll as $item) {
                        if($item->bichuvAcs){
                            $acc[] = $item->bichuvAcs;
                        }
                    }
                }
                /** Acslarni olish uchun ishlatiladi */
                $acsData = ModelsAcsVariations::find()->where(['models_list_id' => $model->model_list_id, 'model_var_id' => $model->id])->all();
                if(!empty($acsData)){
                    foreach ($acsData as $acsDatum) {
                        if($acsDatum->bichuvAcs){
                            $model->bichuv_acs_id[] = $acsDatum->bichuvAcs;
                        }
                    }
                }
                return $this->renderAjax('_forma', [
                    'model' => $model,
                    'isModel' => $isModel,
                    'colors' => ($model->modelsVariationColors) ? $model->modelsVariationColors : [new ModelsVariationColors()],
                    'attachments' => ($model->modelVarRelAttaches) ? $model->modelVarRelAttaches : [new ModelVarRelAttach()],
                    /*'prints' => ($model->modelVarPrintsRels) ? $model->modelVarPrintsRels : [new ModelVarPrints()],*/
                    'modelList' => $modelList,
                    'naqsh' => new ModelVarStone(),
                    'pechat' => new ModelVarPrints(),
                    'acs' => $acc,
                    'oneAcs' => $model->bichuv_acs_id,
                ]);
        }
        else{
            /** Acc malumotlarini olish */
            $modelsAcsAll = ModelsAcs::find()->where(['model_list_id' => $model->model_list_id])->all();
            $acc = [];
            if($modelsAcsAll){
                foreach ($modelsAcsAll as $item) {
                    if($item->bichuvAcs){
                        $acc[] = $item->bichuvAcs;
                    }
                }
            }
            /** Acslarni olish uchun ishlatiladi */
            $acsData = ModelsAcsVariations::find()->where(['models_list_id' => $model->model_list_id, 'model_var_id' => $model->id])->all();
            if(!empty($acsData)){
                foreach ($acsData as $acsDatum) {
                    if($acsDatum->bichuvAcs){
                        $model->bichuv_acs_id[] = $acsDatum->bichuvAcs;
                    }
                }
            }
            return $this->render('update', [
                'model' => $model,
                'acs' => $acc,
                'isModel' => $isModel,
                'oneAcs' => $model->bichuv_acs_id,
                'colors' => ($model->modelsVariationColors)?$model->modelsVariationColors:[new ModelsVariationColors()],
                'attachments' => ($model->modelVarRelAttaches)?$model->modelVarRelAttaches:[new ModelVarRelAttach()],
            ]);
        }
    }

    /**
     * Creates a new ModelsVariations model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ModelsVariations();
        $pechat = new ModelVarPrints();
        $naqsh = new ModelVarStone();
        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $saved = true;

                    if ($model->save() && $saved){
                        /** BichuvAcs larini saqlash */
                        if(!empty($model->bichuv_acs_id)){
                            foreach ($model->bichuv_acs_id as $item) {
                                $Obj = new ModelsAcsVariations();
                                $Obj->setAttributes([
                                    'models_list_id' => $model->model_list_id,
                                    'model_var_id' => $model->id,
                                    'bichuv_acs_id' => $item,
                                ]);
                                if($Obj->save()){
                                    $saved = true;
                                    unset($Obj);
                                }
                                else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }

                        if($saved){
                            $transaction->commit();
                            $data = Yii::$app->request->post();
                            $model->deleteItems();
                            $model->saveItems($data);
                            Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
                            return [
                                'data' => [
                                    'success' => true,
                                    'model' => $model,
                                    'message' => Yii::t('app', 'Saved Successfully'),
                                ],
                                'code' => 0,
                            ];
                        }
                        else {
                            $transaction->rollBack();
                            return [
                                'data' => [
                                    'success' => false,
                                    'model' => null,
                                    'message' => $model->getErrors(),
                                ],
                                'code' => 1, // Some semantic codes that you know them for yourself
                            ];
                        }
                    }
                    else {
                        $transaction->rollBack();
                        return [
                            'data' => [
                                'success' => false,
                                'model' => null,
                                'message' => $model->getErrors(),
                            ],
                            'code' => 1, // Some semantic codes that you know them for yourself
                        ];
                    }
                }
                catch(\Exception $e){
                    Yii::info('error message '.$e->getMessage(), 'save');
                }
            }
            else {
                $id = Yii::$app->request->get('list');
                $modelList = ModelsList::findOne(['id' => $id]);
                /** ModelsAcs larini olish */
                $acs = $modelList->modelsAcs?$modelList->modelsAcs:false;
                $modelsAcs = [];
                if($acs){
                    foreach ($acs as $ac) {
                        $modelsAcs[] = BichuvAcs::findOne($ac['bichuv_acs_id']);
                    }
                }
                else{
                    $modelsAcs = [new ModelsAcs()];
                }

                if($modelList !== null){
                    return $this->renderAjax('_forma', [
                        'model' => $model,
                        'modelList' => $modelList,
                        'colors' => ($model->modelsVariationColors) ? $model->modelsVariationColors : [new ModelsVariationColors()],
                        'attachments' => ($model->modelVarRelAttaches) ? $model->modelVarRelAttaches : [new ModelVarRelAttach()],
                        'pechat' => $pechat,
                        'naqsh' => $naqsh,
                        'acs' => $modelsAcs,
                    ]);
                }
            }
        }
        else {
            if ($model->load(Yii::$app->request->post())) {
                $isWms = WmsMatoInfo::findOne([
                    'wms_desen_id' => $model->wms_desen_id,
                    'wms_color_id' => $model->wms_color_id,
                    'toquv_raw_materials_id' => $model->toquv_raw_material_id
                ]);
                if(!$isWms){
                    $wmsMatoInfo = new WmsMatoInfo();
                    $wmsMatoInfo->setAttributes([
                        'wms_desen_id' => $model->wms_desen_id,
                        'wms_color_id' => $model->wms_color_id,
                        'toquv_raw_materials_id' => $model->toquv_raw_material_id,
                        'type' => ToquvRawMaterials::MATO,
                    ]);
                    if($wmsMatoInfo->save(false)){
                        $saved = true;
                    }
                    else{
                        $saved = false;
                    }
                }
                $data = Yii::$app->request->post();
                if (!empty($data['ModelsVariationColors'])) {
                    $model->saveColor($data['ModelsVariationColors']);
                }
                if (!empty($data['ModelVarRelAttach'])) {
                    $model->saveAttachments($data['ModelVarRelAttach']);
                }
                if(!empty($model->bichuv_acs_id)){
                    foreach ($model->bichuv_acs_id as $item) {
                        $Obj = new ModelsAcsVariations();
                        $Obj->setAttributes([
                            'models_list_id' => $model->model_list_id,
                            'model_var_id' => $model->id,
                            'bichuv_acs_id' => $item,
                        ]);
                        if($Obj->save()){
                            $saved = true;
                            unset($Obj);
                        }
                        else{
                            $saved = false;
                            break;
                        }
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
            $id = Yii::$app->request->get('list');
            $modelList = ModelsList::findOne(['id' => $id]);
            /** ModelsAcs larini olish */
            $acs = $modelList->modelsAcs?$modelList->modelsAcs:false;
            $modelsAcs = [];
            if($acs){
                foreach ($acs as $ac) {
                    $modelsAcs[] = BichuvAcs::findOne($ac['bichuv_acs_id']);
                }
            }
            else{
                $modelsAcs = [new ModelsAcs()];
            }
            return $this->render('create', [
                'model' => $model,
                'colors' => [new ModelsVariationColors()],
                'attachments' => [new ModelVarRelAttach()],
                'prints' => [new ModelVarPrints()],
                'naqsh' => $naqsh,
                'modelList' => $modelList,
                'acs' => $modelsAcs
            ]);
        }
    }

    public function actionForma()
    {
        $model = new ModelsVariations();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $data = Yii::$app->request->post();
            if(!empty($data['ModelsVariationColors'])){
                $model->saveColor($data['ModelsVariationColors']);
            }
            if(!empty($data['ModelVarRelAttach'])){
                $model->saveAttachments($data['ModelVarRelAttach']);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $id = Yii::$app->request->get('list');
        $modelList = ModelsList::findOne(['id' => $id]);
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_forma', [
                'model' => $model,
                'modelList' => $modelList,
                'colors' => [new ModelsVariationColors()],
                'attachments' => [new ModelVarRelAttach()],
                /*'prints' => ($model->modelVarPrintsRels) ? $model->modelVarPrintsRels : [new ModelVarPrints()],*/
            ]);
        }
        return $this->render('_forma', [
            'model' => $model,
            'colors' => [new ModelsVariationColors()],
            'attachments' => [new ModelVarRelAttach()],
            /*'prints' => ($model->modelVarPrintsRels) ? $model->modelVarPrintsRels : [new ModelVarPrints()],*/
            'modelList' => $modelList,
        ]);
    }

    public function actionForm(){
        $model = new ModelsVariations();
        $id = Yii::$app->request->get('list');
        $modelList = ModelsList::findOne(['id' => $id]);
        return $this->render('_form', [
            'model' => $model,
            'colors' => [new ModelsVariationColors()],
            'attachments' => [new ModelVarRelAttach()],
            'modelList' => $modelList,
        ]);
    }
    public function actionFileUpload(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new UploadForm();
        if(Yii::$app->request->isPost){
            $image = UploadedFile::getInstancesByName('img');
            $model->file = $image[0];
            return $model->uploadAjax('model/variations');
        }
    }
    public function actionAttachmentUpload(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new UploadForm();
        $response = [];
        $response['status'] = 0;
        $response['message'] = Yii::t('app', 'Error');
        if(Yii::$app->request->isPost){
            $image = UploadedFile::getInstancesByName('img');
            $model->file = $image[0];
            if($img = $model->uploadAjax('model/variations')){
                $response['status'] = 1;
                $response['message'] = Yii::t('app', 'Saved Successfully');
                $response['id'] = $img;
            }
        }
        return $response;
    }
    /**
     * Updates an existing ModelsVariations model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if ($model->save()) {
                    $data = Yii::$app->request->post();
                    $model->deleteItems();
                    $model->saveItems($data);

                    if(!empty($model->bichuv_acs_id)){
                        foreach ($model->bichuv_acs_id as $item) {
                            $Obj = new ModelsAcsVariations();
                            $Obj->setAttributes([
                                'models_list_id' => $model->model_list_id,
                                'model_var_id' => $model->id,
                                'bichuv_acs_id' => $item,
                            ]);
                            if($Obj->save()){
                                $saved = true;
                                unset($Obj);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                    return [
                        'data' => [
                            'success' => true,
                            'model' => $model,
                            'message' => 'Model has been saved.',
                        ],
                        'code' => 0,
                    ];
                }
                else {
                    return [
                        'data' => [
                            'success' => false,
                            'model' => null,
                            'message' => $model->getErrors(),
                        ],
                        'code' => 1, // Some semantic codes that you know them for yourself
                    ];
                }
            }else{
                $modelList = $model->modelList;
                /** Acc malumotlarini olish */
                $modelsAcsAll = ModelsAcs::find()->where(['model_list_id' => $model->model_list_id])->all();
                $acc = [];
                if($modelsAcsAll){
                    foreach ($modelsAcsAll as $item) {
                        if($item->bichuvAcs){
                            $acc[] = $item->bichuvAcs;
                        }
                    }
                }
                /** Acslarni olish uchun ishlatiladi */
                $acsData = ModelsAcsVariations::find()->where(['models_list_id' => $model->model_list_id, 'model_var_id' => $model->id])->all();
                if(!empty($acsData)){
                    foreach ($acsData as $acsDatum) {
                        if($acsDatum->bichuvAcs){
                            $model->bichuv_acs_id[] = $acsDatum->bichuvAcs;
                        }
                    }
                }
                return $this->renderAjax('_forma', [
                    'model' => $model,
                    'colors' => ($model->modelsVariationColors) ? $model->modelsVariationColors : [new ModelsVariationColors()],
                    'attachments' => ($model->modelVarRelAttaches) ? $model->modelVarRelAttaches : [new ModelVarRelAttach()],
                    /*'prints' => ($model->modelVarPrintsRels) ? $model->modelVarPrintsRels : [new ModelVarPrints()],*/
                    'modelList' => $modelList,
                    'naqsh' => new ModelVarStone(),
                    'pechat' => new ModelVarPrints(),
                    'acs' => $acc,
                    'oneAcs' => $model->bichuv_acs_id,
                ]);
            }
        }
        else{
            if ($model->load(Yii::$app->request->post())) {
                $data = Yii::$app->request->post();
                $model->deleteItems();
                $model->saveItems($data);
                return (isset($_GET['list']))?$this->redirect(['models-list/update', 'id' => $_GET['list'],'active'=>'variation']):$this->redirect(['view', 'id' => $model->id]);
            }
            /** Acc malumotlarini olish */
            $modelsAcsAll = ModelsAcs::find()->where(['model_list_id' => $model->model_list_id])->all();
            $acc = [];
            if($modelsAcsAll){
                foreach ($modelsAcsAll as $item) {
                    if($item->bichuvAcs){
                        $acc[] = $item->bichuvAcs;
                    }
                }
            }
            /** Acslarni olish uchun ishlatiladi */
            $acsData = ModelsAcsVariations::find()->where(['models_list_id' => $model->model_list_id, 'model_var_id' => $model->id])->all();
            if(!empty($acsData)){
                foreach ($acsData as $acsDatum) {
                    if($acsDatum->bichuvAcs){
                        $model->bichuv_acs_id[] = $acsDatum->bichuvAcs;
                    }
                }
            }
            return $this->render('update', [
                'model' => $model,
                'acs' => $acc,
                'oneAcs' => $model->bichuv_acs_id,
                'colors' => ($model->modelsVariationColors)?$model->modelsVariationColors:[new ModelsVariationColors()],
                'attachments' => ($model->modelVarRelAttaches)?$model->modelVarRelAttaches:[new ModelVarRelAttach()],
            ]);
        }
    }
    public function actionSave($id=0)
    {
        $model = ($id!=0)?$this->findModel($id):new ModelsVariations();
        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $data = Yii::$app->request->post();
                if(!empty($data['ModelsVariationColors'])){
                    $checkStatus = false;
                    $check_model = ModelsVariations::find()->where([
                        'model_list_id' => $data['ModelsVariations']['model_list_id'],
                        'color_pantone_id' => (!empty($data['ModelsVariations']['color_pantone_id'])) ? $data['ModelsVariations']['color_pantone_id'] : null,
                        'toquv_raw_material_id' => (!empty($data['ModelsVariations']['toquv_raw_material_id'])) ? $data['ModelsVariations']['toquv_raw_material_id'] : null,
                        'boyoqhona_color_id' => (!empty($data['ModelsVariations']['boyoqhona_color_id'])) ? $data['ModelsVariations']['boyoqhona_color_id'] : null,
                    ])->orderBy(['id' => SORT_DESC])->one();
                    if ($check_model) {
                        $checkStatus = true;
                        if (!empty($data['ModelsVariationColors'])) {
                            foreach ($data['ModelsVariationColors'] as $check) {
                                $checkItem = ModelsVariations::find()->alias('mv')->joinWith('modelsVariationColors as mvc')->where([
                                    'model_list_id' => $data['ModelsVariations']['model_list_id'],
                                    'name' => $data['ModelsVariations']['name'],
                                    'mv.color_pantone_id' => (!empty($data['ModelsVariations']['color_pantone_id'])) ? $data['ModelsVariations']['color_pantone_id'] : null,
                                    'mv.toquv_raw_material_id' => (!empty($data['ModelsVariations']['toquv_raw_material_id'])) ? $data['ModelsVariations']['toquv_raw_material_id'] : null,
                                    'mv.boyoqhona_color_id' => (!empty($data['ModelsVariations']['boyoqhona_color_id'])) ? $data['ModelsVariations']['boyoqhona_color_id'] : null,
                                ])->andWhere([
                                    'mvc.base_detail_list_id' => (!empty($check['base_detail_list_id'])) ? $check['base_detail_list_id'] : null,
                                    'mvc.color_pantone_id' => (!empty($check['color_pantone_id'])) ? $check['color_pantone_id'] : null,
                                    'mvc.color_boyoqhona_id' => (!empty($check['color_boyoqhona_id'])) ? $check['color_boyoqhona_id'] : null,
                                    'mvc.toquv_raw_material_id' => (!empty($check['toquv_raw_material_id'])) ? $check['toquv_raw_material_id'] : null,
                                ])->groupBy('mv.id')->orderBy(['mv.id' => SORT_DESC]);
                                $count = $checkItem->count();
                                if ($count > 0) {
                                    if ($count < 2) {
                                        $check_model = $checkItem->one();
                                    }
                                    $checkStatus = true;
                                } else {
                                    $checkStatus = false;
                                    break;
                                }
                            }
                        }
                    }
                    if ($checkStatus) {
                        $response = [
                            'status' => 1,
                            'model' => $check_model,
                            'image' => $check_model->image,
                            'full_name' => $check_model->wmsColor->fullName,
                            'message' => Yii::t('app', 'Saved Successfully'),
                        ];
                        return $response;
                    }
                    $transaction = Yii::$app->db->beginTransaction();
                    $response = [];
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
                    try {
                        if ($model->save()) {
                            $model->code = "MV-" . $model->id;
                            $model->save(false, ['code']);

                            if ($model->saveItems($data)) {
                                $transaction->commit();
                                $response = [
                                    'status' => 1,
                                    'model' => $model,
                                    'image' => "/web/" . $model->image,
                                    'full_name' => $model->wmsColor->fullName,
                                    'message' => Yii::t('app', 'Saved Successfully'),
                                ];
                            } else {
                                $transaction->rollBack();
                            }
                        } else {
                            $response = [
                                'status' => 0,
                                'model' => null,
                                'messages' => $model->getErrors(),
                                'message' => Yii::t('app', 'Hatolik yuz berdi')
                            ];
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::info('Not saved ModelVariationsColors' . $e, 'save');
                    }
                }
                else{
                    $response = [
                        'status' => 0,
                        'model' => null,
                        'message' => Yii::t('app', 'Detallar uchun ranglar va matolar tanlash shart')
                    ];
                }
                return $response;
            } else {
                return $this->renderAjax('_forma', [
                    'model' => $model,
                    'colors' => ($model->modelsVariationColors) ? $model->modelsVariationColors : [new ModelsVariationColors()],
                    'attachments' => ($model->modelVarRelAttaches) ? $model->modelVarRelAttaches : [new ModelVarRelAttach()],
                    'modelList' => ($model->isNewRecord)?new ModelsList():$model->modelList,
                ]);
            }
        }
        return false;
    }
    public function actionValidate()
    {
        $model = new ModelsVariations();
        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        return false;
    }

    /**
     * Deletes an existing ModelsVariations model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($model->delete()) {
                return [
                    'data' => [
                        'success' => true,
                        'message' => 'Model has been deleted.',
                    ],
                    'code' => 0,
                ];
            } else {
                return [
                    'data' => [
                        'success' => false,
                        'model' => null,
                    ],
                    'code' => 1, // Some semantic codes that you know them for yourself
                ];
            }
        }
        if($model->delete()){
            Yii::$app->session->setFlash('success',Yii::t('app','Deleted Successfully'));
        }
        return $this->redirect(['index']);
    }


    /**
     * Finds the ModelsVariations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelsVariations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelsVariations::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
