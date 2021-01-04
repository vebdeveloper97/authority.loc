<?php

namespace app\modules\bichuv\controllers;

use app\modules\bichuv\models\BichuvTables;
use Yii;
use app\modules\bichuv\models\BichuvTablesUsers;
use app\modules\bichuv\models\BichuvTablesUsersSearch;
use app\modules\bichuv\controllers\BaseController;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BichuvTablesUsersController implements the CRUD actions for BichuvTablesUsers model.
 */
class BichuvTablesUsersController extends BaseController
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
     * Lists all BichuvTablesUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BichuvTablesUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BichuvTablesUsers model.
     * @param integer $bichuv_tables_id
     * @param integer $users_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($bichuv_tables_id, $users_id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($bichuv_tables_id, $users_id),
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($bichuv_tables_id, $users_id),
        ]);
    }

    /**
     * Creates a new BichuvTablesUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BichuvTablesUsers();
        if ($model->load(Yii::$app->request->post())) {

            if(!empty($model->tables)){
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    \yii\helpers\VarDumper::dump($model,10,true); die;
                    foreach ($model->tables as $table) {
                        $new_model = new BichuvTablesUsers();
                        $new_model->setAttributes([
                            'bichuv_tables_id' => $table,
                            'hr_employee_id' => $model->hr_employee_id,
                        ]);
                        if(!$new_model->save()){
                            \yii\helpers\VarDumper::dump($new_model->getErrors(),10,true); die;
                        }
                        \yii\helpers\VarDumper::dump($new_model->save(),10,true); die;
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
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($saved) {
                return $this->redirect(['view', 'bichuv_tables_id' => $model->bichuv_tables_id, 'users_id' => $model->users_id]);
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
     * Updates an existing BichuvTablesUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $bichuv_tables_id
     * @param integer $users_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = BichuvTablesUsers::find()->where(['users_id'=>$id])->one();
        if(!empty($model)) {
            $model->tables = $model->getUserTableList($id, true);
            $model->tables = ArrayHelper::getColumn($model->tables, 'id');
        }else{
            $model = new BichuvTablesUsers();
        }
        if ($model->load(Yii::$app->request->post())) {
            if(!empty($model->tables)){
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    BichuvTablesUsers::deleteAll(['users_id'=>$id]);
                    foreach ($model->tables as $table) {
                        $new_model = new BichuvTablesUsers([
                            'bichuv_tables_id' => $table,
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
                return $this->redirect(['view', 'bichuv_tables_id' => $model->bichuv_tables_id, 'users_id' => $model->users_id]);
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
     * Deletes an existing BichuvTablesUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $bichuv_tables_id
     * @param integer $users_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($bichuv_tables_id, $users_id)
    {
        if(Yii::$app->request->isAjax){
            if($this->findModel($id)->delete()){
                echo "success";
            }else{
                echo "fail";
            }
            exit();
        }
        $this->findModel($bichuv_tables_id, $users_id)->delete();
        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "bichuv-tables-users_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BichuvTablesUsers::find()->select([
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
     * Finds the BichuvTablesUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $bichuv_tables_id
     * @param integer $users_id
     * @return BichuvTablesUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($bichuv_tables_id, $users_id)
    {
        if (($model = BichuvTablesUsers::findOne(['bichuv_tables_id' => $bichuv_tables_id, 'users_id' => $users_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
