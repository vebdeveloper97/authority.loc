<?php

namespace app\modules\toquv\controllers;

use Yii;
use app\modules\toquv\models\ToquvMakine;
use app\modules\toquv\models\ToquvMakineSearch;
use app\modules\toquv\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvMakineController implements the CRUD actions for ToquvMakine model.
 */
class ToquvMakineController extends BaseController
{
    /**
     * {@inheritdoc}
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
     * Lists all ToquvMakine models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvMakineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single ToquvMakine model.
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
     * Creates a new ToquvMakine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvMakine();

        if ($model->load(Yii::$app->request->post())) {
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
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ToquvMakine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
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
     * Deletes an existing ToquvMakine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id){
        if(Yii::$app->request->isAjax){
            if($this->findModel($id)->delete()){
                echo "success";
            }else{
                echo "fail";
            }
            exit();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Mashinalar holati
     * @return makine-status-list
     * @throws \yii\db\Exception
     */
    public function actionMakine()
    {
        $this->layout = 'makine';
        $years = ToquvMakine::getBySql("SELECT mac.id,mac.name FROM `toquv_makine`as mac WHERE 1");
        $month = ToquvMakine::getBySql("SELECT mac.id,mac.name FROM `toquv_makine`as mac WHERE 1");
        $day = ToquvMakine::getBySql("SELECT mac.id,mac.name FROM `toquv_makine`as mac WHERE 1");
        return $this->render('makine', [
            'row' => ToquvMakine::getMakine(),
            'years' => $years,
            'month' => $month,
            'day' => $day
        ]);
    }
    public function actionToquvMakineYillik()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        if($post) {
            return ToquvMakine::getToquvMakineYillik($post);
        }
        return false;
    }

    public function actionToquvMakineOylik(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        if($post) {
            return ToquvMakine::getToquvMakineOylik($post);
        }
        return false;
    }

    public function actionToquvMakineKunlik(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        if($post) {
            return ToquvMakine::getToquvMakineKunlik($post);
        }
        return false;
    }

    /**
     * Finds the ToquvMakine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvMakine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvMakine::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
