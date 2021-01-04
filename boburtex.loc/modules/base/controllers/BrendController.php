<?php

namespace app\modules\base\controllers;

use Yii;
use app\modules\base\models\Brend;
use app\modules\base\models\BrendSearch;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BrendController implements the CRUD actions for Brend model.
 */
class BrendController extends BaseController
{
    /**
     * Lists all Brend models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BrendSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Brend model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Brend model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Brend();
        if ($model->load(Yii::$app->request->post())) {
            if($file = Yii::$app->request->post('Brend')['file']){
                $model->image = $model->uploadBase64('brend', $file);
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if ($model->save()) {
                    $response['data']['success'] = 1;
                    $response['data']['title'] = $model->name;
                    $response['data']['selected_id'] = $model->id;
                } else {
                    $response['data']['success'] = 0;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Brend model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if($file = Yii::$app->request->post('Brend')['file']){
                $model->image = $model->uploadBase64('brend', $file);
            }
            if($remove = Yii::$app->request->post('remove')){
                $path = str_replace('/web/', '', $remove);
                if (file_exists($path)){
                    unlink($path);
                }
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if ($model->save()) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Brend model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->image){
            $path = str_replace('/web/', '', $model->image);
            if (file_exists($path)){
                unlink($path);
            }
        }
        if(Yii::$app->request->isAjax){

            if($model->delete()){
                echo "success";
            }else{
                echo "fail";
            }
            exit();
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "brend_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => Brend::find()->select([
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
     * Finds the Brend model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Brend the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Brend::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
