<?php

namespace app\modules\toquv\controllers;

use app\modules\toquv\models\ToquvPusFineSearch;
use app\modules\toquv\models\ToquvThreadSearch;
use Yii;
use app\modules\toquv\models\ToquvNe;
use app\modules\toquv\models\ToquvNeSearch;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvNeController implements the CRUD actions for ToquvNe model.
 */
class ToquvNeController extends BaseController
{

    public static $active;

    /**
     * Lists all ToquvNe models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this::$active = 'index';
        $searchModel = new ToquvNeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'index' =>'index'
        ]);
    }

    /**
     * Displays a single ToquvNe model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this::$active = 'view';
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
            'view' => "view"
        ]);
    }

    /**
     * Creates a new ToquvNe model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvNe();

        return $this->renderAjax('create', [
            'model' => $model,
            'create' => "create"
        ]);
    }



    /**
     * Updates an existing ToquvNe model.
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
     * Finds the ToquvNe model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvNe the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvNe::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
