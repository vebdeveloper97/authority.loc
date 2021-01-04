<?php

namespace app\modules\toquv\controllers;

use app\modules\toquv\models\ToquvPusFine;
use Yii;
use app\modules\toquv\models\ToquvThread;
use app\modules\toquv\models\ToquvThreadSearch;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvThreadController implements the CRUD actions for ToquvThread model.
 */
class ToquvThreadController extends BaseController
{
    public static $active;

    /**
     * Lists all ToquvThread models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvThreadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'index' => 'index'
        ]);
    }

    /**
     * Displays a single ToquvThread model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
            'view' => "view"
        ]);
    }

    /**
     * Creates a new ToquvThread model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvThread();

        return $this->renderAjax('create', [
            'model' => $model,
            'create' => "create"
        ]);
    }

    /**
     * Updates an existing ToquvThread model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('/toquv/toquv-directory/index/');
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'update' => "update"
        ]);
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            $this->findModel($id)->delete();
            return ['status' => 'success' ,'message' => Yii::t('app','Successfully deleted')];
        }catch (Exception $e){
            return ['status' => 'error', 'message' => Yii::t('app','Could not deleted! This item is used anywhere')];
        }
    }

    /**
     * Finds the ToquvThread model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvThread the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvThread::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
