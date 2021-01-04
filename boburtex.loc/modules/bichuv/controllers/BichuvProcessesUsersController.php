<?php

namespace app\modules\bichuv\controllers;

use Yii;
use app\modules\bichuv\models\BichuvProcessesUsers;
use app\modules\bichuv\models\BichuvProcessesUsersSearch;
use app\modules\bichuv\controllers\BaseController;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BichuvProcessesUsersController implements the CRUD actions for BichuvProcessesUsers model.
 */
class BichuvProcessesUsersController extends BaseController
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
     * Lists all BichuvProcessesUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BichuvProcessesUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BichuvProcessesUsers model.
     * @param integer $bichuv_processes_id
     * @param integer $users_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($bichuv_processes_id, $users_id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($bichuv_processes_id, $users_id),
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($bichuv_processes_id, $users_id),
        ]);
    }

    /**
     * Creates a new BichuvProcessesUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BichuvProcessesUsers();
        if ($model->load(Yii::$app->request->post())) {
            if(!empty($model->tables)){
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    foreach ($model->tables as $table) {
                        $new_model = new BichuvProcessesUsers([
                            'bichuv_processes_id' => $table,
                            'users_id' => $model->users_id,
                            'tables' => 1
                        ]);
                        if ($new_model->save()) {
                            $saved = true;
                        } else {
                            $saved = false;
                            break;
                        }
                    }
                    if($saved){
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                    }
                }catch (\Exception $e){
                    Yii::info("Not saved Bichuv Tables Users {$e->getMessage()}",'save');
                }
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if ($saved) {
                    $response['status'] = 0;
                } else {
                    $model->validate();
                    $response['message'] = 'Xato';
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($saved) {
                return $this->redirect(['view', 'bichuv_processes_id' => $model->bichuv_processes_id, 'users_id' => $model->users_id]);
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
     * Updates an existing BichuvProcessesUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $bichuv_processes_id
     * @param integer $users_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = BichuvProcessesUsers::find()->where(['users_id'=>$id])->one();
        if(!empty($model)) {
            $model->tables = $model->getUserProcessList($id, true);
            $model->tables = ArrayHelper::getColumn($model->tables, 'id');
        }else{
            $model = new BichuvProcessesUsers([
                'users_id' => $id
            ]);
        }
        if ($model->load(Yii::$app->request->post())) {
            if(!empty($model->tables)){
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    BichuvProcessesUsers::deleteAll(['users_id'=>$id]);
                    foreach ($model->tables as $table) {
                        $new_model = new BichuvProcessesUsers([
                            'bichuv_processes_id' => $table,
                            'users_id' => $id,
                            'tables' => 1
                        ]);
                        if ($new_model->save()) {
                            $saved = true;
                        } else {
                            $saved = false;
                            break;
                        }
                    }
                    if($saved){
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                    }
                }catch (\Exception $e){
                    Yii::info("Not saved Bichuv Tables Users {$e->getMessage()}",'save');
                }
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if ($saved) {
                    $response['status'] = 0;
                } else {
                    $model->validate();
                    $response['message'] = 'Xato';
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($saved) {
                return $this->redirect(['view', 'bichuv_processes_id' => $model->bichuv_processes_id, 'users_id' => $model->users_id]);
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
     * Deletes an existing BichuvProcessesUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $bichuv_processes_id
     * @param integer $users_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($bichuv_processes_id, $users_id)
    {
        if(Yii::$app->request->isAjax){
            if($this->findModel($id)->delete()){
                echo "success";
            }else{
                echo "fail";
            }
            exit();
        }
        $this->findModel($bichuv_processes_id, $users_id)->delete();
        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "bichuv-processes-users_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BichuvProcessesUsers::find()->select([
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
     * Finds the BichuvProcessesUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $bichuv_processes_id
     * @param integer $users_id
     * @return BichuvProcessesUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($bichuv_processes_id, $users_id)
    {
        if (($model = BichuvProcessesUsers::findOne(['bichuv_processes_id' => $bichuv_processes_id, 'users_id' => $users_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
