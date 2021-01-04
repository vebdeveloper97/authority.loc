<?php

namespace app\modules\toquv\controllers;

use app\modules\toquv\models\ToquvMusteriType;
use app\modules\toquv\models\ToquvSaldoSearch;
use Yii;
use app\modules\toquv\models\ToquvMusteri;
use app\modules\toquv\models\ToquvMusteriSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ToquvMusteriController implements the CRUD actions for ToquvMusteri model.
 */
class ToquvMusteriController extends BaseController
{
    /**
     * Lists all ToquvMusteri models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvMusteriSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Displays a single ToquvMusteri model.
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
     * Creates a new ToquvMusteri model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $model = new ToquvMusteri();

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            if($model->save()){
                $response['status'] = 0;
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
     * Updates an existing ToquvMusteri model.
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
            if($model->save()){
                $response['status'] = 0;
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
     * Deletes an existing ToquvMusteri model.
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
     * Finds the ToquvMusteri model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvMusteri the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvMusteri::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function actionCreateNewItem()
    {
        $this->enableCsrfValidation = false;
        $name = trim(Yii::$app->request->post('name'));
        $getModel = trim(Yii::$app->request->post('model'));
        $model = null;

        if($getModel == 'toquv-ne') {
            $model = new ToquvMusteriType();
            $model->name = $name;
        }


        if($model->save()){
            return $model->id;
        }else{
            return "fail";
        }
    }

    public function actionSaldo()
    {
        $this->layout = '@app/views/layouts/saldo';
        $this->view->title = 'Saldo';
        return $this->render('saldo');
    }
}
