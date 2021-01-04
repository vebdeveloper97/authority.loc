<?php

namespace app\modules\toquv\controllers;

use app\modules\toquv\models\ToquvRawMaterialConsist;
use app\modules\toquv\models\ToquvRawMaterialIp;
use app\modules\toquv\models\ToquvRawMaterialType;
use Yii;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\ToquvRawMaterialsSearch;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvRawMaterialsController implements the CRUD actions for ToquvRawMaterials model.
 */
class ToquvRawMaterialsController extends BaseController
{
    /**
     * Lists all ToquvRawMaterials models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvRawMaterialsSearch();
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
        if ($model->load(Yii::$app->request->post()) ) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            if($model->save()){
                $response['status'] = 0;
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

                $material = ToquvRawMaterials::getListMap(ToquvRawMaterials::MATO, $model->id);
                $response['success'] = true;
                $response['selected_id'] = $model->id;
                $response['title'] = $material[$model->id];

            }else{
                $result = [];
                foreach ($model->getErrors() as $attribute => $errors) {
                    $result[Html::getInputId($model, $attribute)] = $errors;
                }

                $response['validation'] = $result;
                $response['status'] = 1;
                $response['errors'] = $model->getErrors();
            }
            return $response;


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

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];

//            echo "<pre>";
//            var_dump($_POST['ToquvRawMaterials']['toquvRawMaterialIps']);
//            die();
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

            }else{
                $response['status'] = 1;
                $response['errors'] = $model->getErrors();
            }
            return $response;
        }

        return $this->renderAjax('update', [
            'model' => $model,
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
        if($this->findModel($id)->delete()){
            echo "success";
        }else{
            echo "fail";
        }

        exit();
    }
    public function actionCopy($id)
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $original = $this->findModel($id);
        $model = new ToquvRawMaterials();
        if ($model->load(Yii::$app->request->post())) {
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
