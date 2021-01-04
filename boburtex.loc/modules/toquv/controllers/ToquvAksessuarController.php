<?php

namespace app\modules\toquv\controllers;

use app\models\UploadForm;
use app\modules\toquv\models\ToquvRawMaterialAttachments;
use app\modules\toquv\models\ToquvRawMaterialColor;
use app\modules\toquv\models\ToquvRawMaterialConsist;
use app\modules\toquv\models\ToquvRawMaterialIp;
use app\modules\toquv\models\ToquvRawMaterialsSearch;
use app\modules\toquv\models\ToquvRawMaterialType;
use Yii;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\ToquvAksessuarSearch;
use app\modules\toquv\controllers\BaseController;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ToquvAksessuarController implements the CRUD actions for ToquvRawMaterials model.
 */
class ToquvAksessuarController extends BaseController
{
    /**
     * Lists all ToquvRawMaterials models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvAksessuarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ToquvRawMaterials model.
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

    /**
     * Creates a new ToquvRawMaterials model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');

        $model = new ToquvRawMaterials();
        $data = Yii::$app->request->post();
        if ($model->load($data)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            try {
                $response = [];
                $save = false;
                $transaction = Yii::$app->db->beginTransaction();

                if($model->save()){

                    $save = true;
                    $response['status'] = 0;
                    $row_material_consist = $data['ToquvRawMaterials']['toquvRawMaterialConsists'];
                    $row_material_ip = $data['ToquvRawMaterials']['toquvRawMaterialIps'];

                    if(!empty($row_material_consist)){
                        foreach ($row_material_consist as $material_consist){
                            if( (int) $material_consist['percentage'] !== 0){
                                $new = new ToquvRawMaterialConsist();
                                $new->setAttributes([
                                    'fabric_type_id' => $material_consist['fabric_type_id'],
                                    'percentage' => (int) $material_consist['percentage'],
                                    'raw_material_id' => $model->id,
                                ]);
                                if (!$new->save()) {
                                    $save = false;
                                    $transaction->rollBack();
                                    break;
                                }
                            }

                        }
                    }

                    if (!$save) {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                        return $response;
                    }

                    if(!empty($row_material_ip)){
                        foreach ($row_material_ip as $material_ip){
                            if( (int) $material_ip['percentage'] !== 0){
                                $new = new ToquvRawMaterialIp();
                                $new->setAttributes([
                                    'ne_id' => $material_ip['ne_id'],
                                    'thread_id' => $material_ip['thread_id'],
                                    'percentage' => (int) $material_ip['percentage'],
                                    'toquv_raw_material_id' => $model->id,
                                ]);
                                if (!$new->save()) {
                                    $save = false;
                                    $transaction->rollBack();
                                    break;
                                }
                            }

                        }
                    }

                    if (!$save) {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                        return $response;
                    }

                    $image = $data['attachments'];
                    $i = 0;
                    if($image){
                        foreach($image as $item){
                            $rel = new ToquvRawMaterialAttachments([
                                'toquv_raw_materials_id' => $model->id,
                                'attachment_id' => $item,
                                'is_main' => ($i == 0) ? 1 : 0
                            ]);
                            $i++;
                            if(!$rel->save()){
                                $save = false;
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($save) {
                        $transaction->commit();
                        $response['success'] = true;
                        $response['title'] = $model->name.' '.$model->code;
                        $response['selected_id'] = $model->id;
                    }

                }
                else{
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            } catch (\Exception $e) {
                Yii::info('Not saved toquv aksesuar' . $e, 'save');
            }

        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ToquvRawMaterials model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $model = $this->findModel($id);
        $attachments = $model->toquvRawMaterialAttachments ? $model->toquvRawMaterialAttachments : [];
        $data = Yii::$app->request->post();
        if ($model->load($data)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];

            if($model->save()){
                $response['status'] = 0;
                $row_material_consist = $_POST['ToquvRawMaterials']['toquvRawMaterialConsists'];
                $row_material_ip = $_POST['ToquvRawMaterials']['toquvRawMaterialIps'];

                if(!empty($row_material_consist[0]['percentage'])){
                    ToquvRawMaterialConsist::deleteRawMaterialConsist($model->id);
                    foreach ($row_material_consist as $material_consist){
                        if( (int) $material_consist['percentage'] !== 0){
                            $new = new ToquvRawMaterialConsist();
                            $new->fabric_type_id = $material_consist['fabric_type_id'];
                            $new->percentage = (int) $material_consist['percentage'];
                            $new->raw_material_id = $model->id;
                            $new->save();
                        }

                    }
                }

                if(!empty($row_material_ip[0]['percentage'])){

                    ToquvRawMaterialIp::deleteRawMaterialIp($model->id);
                    foreach ($row_material_ip as $material_ip){
                        if( (int) $material_ip['percentage'] !== 0){
                            $new = new ToquvRawMaterialIp();
                            $new->ne_id = $material_ip['ne_id'];
                            $new->thread_id = $material_ip['thread_id'];
                            $new->percentage = (int) $material_ip['percentage'];
                            $new->toquv_raw_material_id = $model->id;
                            $new->save();
                        }

                    }
                }

                $image = $data['attachments'];
                $i = 0;
                if($image){
                    ToquvRawMaterialAttachments::deleteAll(['toquv_raw_materials_id'=>$model->id]);
                    foreach($image as $item){
                        $rel = new ToquvRawMaterialAttachments([
                            'toquv_raw_materials_id' => $model->id,
                            'attachment_id' => $item,
                            'is_main' => ($i == 0) ? 1 : 0
                        ]);
                        $i++;
                        if(!$rel->save()){
                            Yii::info('Not saved toquv aksesuar attachment: => ' . $image, 'save');
                            break;
                        }
                    }
                }

            }else{
                $response['status'] = 1;
                $response['errors'] = $model->getErrors();
            }
            return $response;
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'attachments' => $attachments,
        ]);
    }

    /**
     * Deletes an existing ToquvRawMaterials model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try {
            if(
                ToquvRawMaterialConsist::deleteAll(['raw_material_id'=>$model->id]) &&
                ToquvRawMaterialIp::deleteAll(['toquv_raw_material_id'=>$model->id]) &&
                ToquvRawMaterialAttachments::deleteAll(['toquv_raw_materials_id' => $model->id])
            ){
                $saved = true;
                if ($model->delete()) {
                    $saved = true;
                } else {
                    $saved = false;
                }
            }
            if ($saved) {
                $transaction->commit();
                echo "success";
            } else {
                $transaction->rollBack();
                echo "fail";
            }
        }catch (\Exception $e){
            Yii::error('Not deleted toquv aks ' . $e, 'save');
            echo 'fail';
        }
        exit();
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function actionCopy($id)
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');

        $original = $this->findModel($id);
        $original->code = '';
        $model = new ToquvRawMaterials();
        $data = Yii::$app->request->post();

        if ($model->load($data)) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];

            if($model->save()){
                $row_material_consist = $_POST['ToquvRawMaterials']['toquvRawMaterialConsists'];
                $row_material_ip = $_POST['ToquvRawMaterials']['toquvRawMaterialIps'];
                if(!empty($row_material_consist)){
                    foreach ($row_material_consist as $material_consist){
                        if( (int) $material_consist['percentage'] !== 0){
                            $new = new ToquvRawMaterialConsist();
                            $new->setAttributes([
                                'fabric_type_id' => $material_consist['fabric_type_id'],
                                'percentage' => (int) $material_consist['percentage'],
                                'raw_material_id' => $model->id,
                            ]);
                            $new->save();
                        }
                    }
                }

                if(!empty($row_material_ip)){
                    foreach ($row_material_ip as $material_ip){
                        if( (int) $material_ip['percentage'] !== 0){
                            $new = new ToquvRawMaterialIp();
                            $new->setAttributes([
                                'ne_id' => $material_ip['ne_id'],
                                'thread_id' => $material_ip['thread_id'],
                                'percentage' => (int) $material_ip['percentage'],
                                'toquv_raw_material_id' => $model->id,
                            ]);
                            $new->save();
                        }
                    }
                }

                $image = $data['attachments'];
                $i = 0;
                if($image){
                    foreach($image as $item){
                        $rel = new ToquvRawMaterialAttachments([
                            'toquv_raw_materials_id' => $model->id,
                            'attachment_id' => $item,
                            'is_main' => ($i == 0) ? 1 : 0
                        ]);
                        $i++;
                        if(!$rel->save()){
                            Yii::info('Not saved toquv aksesuar attachment: => ' . $image, 'save');
                            break;
                        }
                    }
                }
                $response['status'] = 0;
                return $response;
            }else{
                $response['status'] = 1;
                $response['errors'] = $model->getErrors();
                return $response;
            }
        }
        return $this->renderAjax('copy', [
            'model' => $original,
            'attachments' => [],
        ]);
    }
    /**
     * Finds the ToquvRawMaterials model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvRawMaterials the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvRawMaterials::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionCreateNewItem()
    {
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
            return $model->id;
        }else{
            return "fail";
        }
    }
    public function actionTypeItem()
    {
        $type = trim(Yii::$app->request->post('type'));
        $response = ['status'=>false];
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response['status'] = true;
            $response['data'] = ToquvRawMaterialType::find()->select(['id','name'])->where(['type'=>$type])->asArray()->all();
        }
        return $response;
    }
    public function actionExportExcel(){
        $searchModel = new ToquvAksessuarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        header('Content-Type: application/vnd.ms-excel');
        $filename = "toquv-aksessuar_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => $dataProvider->getModels(),
            'columns' => [
                'id',
                'code',
                'name',
                //'name_ru',
                [
                    'attribute' => 'type',
                    'value' => function($model){
                        return ($model->type)?$model->getTypeList($model->type):'';
                    },
                    'filter' => ToquvRawMaterials::getTypeList()
                ],
                [
                    'attribute' => 'rawMaterialName',
                    'format' => 'raw',
                    'filter' => ToquvRawMaterials::getMaterialTypeSearch(ToquvRawMaterials::ACS)
                ],
                [
                    'attribute' => 'rawMaterialConsist',
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'ip',
                    'value' => function($model){
                        return $model->getRawmaterialIp(',',' ');
                    },
                    'format' => 'raw',
                ],
            ],
            'headers' => [
                'id' => 'Id',
            ],
            'autoSize' => true,
        ]);
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
            if($img = $model->uploadAjax('toquv/aksesuar')){
                $response['status'] = 1;
                $response['message'] = Yii::t('app', 'Saved Successfully');
                $response['id'] = $img;
            }
        }
        return $response;
    }

    public function actionChangeStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('pk');
        $status = Yii::$app->request->post('value');
        $model = ToquvRawMaterials::find()->where(['id' => $id])->one();

        $model->status = (int)$status;
        $result = ['success'=>false];
        if($model->save()) {
            $text = $model->getStatusList($model->status);
            $btnClass = $model->status == 1 ? 'btn btn-xs btn-success' : 'btn btn-xs btn-danger';
            $button = Html::button($text, ['class' => $btnClass]);
            $result = ['success' => true, 'btn' => $button, 'id' => $model->id];
        }
        return $result;
    }

}
