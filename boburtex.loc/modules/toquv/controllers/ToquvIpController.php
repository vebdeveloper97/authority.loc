<?php

namespace app\modules\toquv\controllers;

use app\modules\toquv\models\ToquvIpColor;
use app\modules\toquv\models\ToquvIpTarkibi;
use app\modules\toquv\models\ToquvNe;
use app\modules\toquv\models\ToquvThread;
use Yii;
use app\modules\toquv\models\ToquvIp;
use app\modules\toquv\models\ToquvIpSearch;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ToquvIpController implements the CRUD actions for ToquvIp model.
 */
class ToquvIpController extends BaseController
{
    /**
     * Lists all ToquvIp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvIpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ToquvIp model.
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
     * Creates a new ToquvIp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');

        $model = new ToquvIp();

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];

            if($model->save()){
                $response['status'] = 0;
                $ip_tarkibi = $_POST['ToquvIp']['ip_tarkibi'];

                if(!empty($ip_tarkibi)){
                    foreach ($ip_tarkibi as $tarkib){
                        if( (int) $tarkib['quantity'] !== 0){
                            $new = new ToquvIpTarkibi();
                            $new->fabric_type_id = $tarkib['fabric_type_id'];
                            $new->quantity = $tarkib['quantity'];
                            $new->ip_id = $model->id;
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

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ToquvIp model.
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

        if ($model->load(Yii::$app->request->post()) ) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            if($model->save()){

                $response['status'] = 0;
                $ip_tarkibi = $_POST['ToquvIp']['ip_tarkibi'];
                if(!empty($ip_tarkibi[0]['quantity'])) {
                    ToquvIpTarkibi::deleteTarkib($model->id);
                    foreach ($ip_tarkibi as $tarkib) {
                        if ((int)$tarkib['quantity'] !== 0) {
                            $new = new ToquvIpTarkibi();
                            $new->fabric_type_id = $tarkib['fabric_type_id'];
                            $new->quantity = $tarkib['quantity'];
                            $new->ip_id = $model->id;
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
     * Deletes an existing ToquvIp model.
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

    /**
     * Finds the ToquvIp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvIp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvIp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionCreateToquvNe()
    {
        $this->enableCsrfValidation = false;
        $name = trim(Yii::$app->request->post('name'));
        $model = new ToquvNe();
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $model->name = $name;

        if($model->save()){
            return "success";
        }else{
            return "fail";
        }
    }

    public function actionCreateNewItem()
    {
        $this->enableCsrfValidation = false;
        $name = trim(Yii::$app->request->post('name'));
        $getModel = trim(Yii::$app->request->post('model'));
        $model = null;

        if($getModel == 'toquv-ne') {
            $model = new ToquvNe();
            $model->name = $name;
        }

        if($getModel == 'toquv-thread') {
            $model = new ToquvThread();
            $model->name = $name;
        }
        if($getModel == 'toquv-ip-color') {
            $model = new ToquvIpColor();
            $model->name = $name;
        }


        if($model->save()){
            return $model->id;
        }else{
            return "fail";
        }
    }

    public function actionDeleteItem()
    {
        $id = trim(Yii::$app->request->post('id'));

        $model = ToquvIpTarkibi::find()->where(['id' => $id])->one();

        if( $model->delete()){
            return "success";
        }else{
            return "fail";
        }
    }
    

    public function actionChangeStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('pk');
        $status = Yii::$app->request->post('value');
        $model = ToquvIp::find()->where(['id' => $id])->one();

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
