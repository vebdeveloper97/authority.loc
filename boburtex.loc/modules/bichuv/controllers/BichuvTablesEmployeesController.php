<?php

namespace app\modules\bichuv\controllers;

use Yii;
use app\modules\bichuv\models\BichuvTablesEmployees;
use app\modules\bichuv\models\BichuvTablesEmployeesSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BichuvTablesEmployeesController implements the CRUD actions for BichuvTablesEmployees model.
 */
class BichuvTablesEmployeesController extends BaseController
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
     * Lists all BichuvTablesEmployees models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BichuvTablesEmployeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BichuvTablesEmployees model.
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
     * Creates a new BichuvTablesEmployees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BichuvTablesEmployees();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {

                    $saved = $model->allModelSave($model);

                    if($saved) {
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                }
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = [];
                    if ($saved) {
                        $response['status'] = 0;
                        $response['message'] = Yii::t('app', 'Saved Successfully');
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                        $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
                    }
                    return $response;
                }
                if ($saved) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
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
     * Updates an existing BichuvTablesEmployees model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_UPDATE;
        $oldModel = $model->oldAttributes;
        $allOldModels = BichuvTablesEmployees::find()->where(['hr_employee_id' => $oldModel['hr_employee_id'], 'status' => $model::STATUS_ACTIVE])->all();
        $allOldTablesByEmployee = ArrayHelper::getColumn($allOldModels,'bichuv_table_id');
        $model->bichuv_table_id = $allOldTablesByEmployee;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {

                    $allNewTablesByEmployee = $model['bichuv_table_id'];
                    if($model['hr_employee_id'] != $oldModel['hr_employee_id']){

                        if (!empty($allOldModels)){
                            $saved = $model->modelStatusChange($allOldModels,$model['add_info']);
                        }

                        $saved = $model->allModelSave($model);
                    }else{

                        $modelStatusChange = array_diff($allOldTablesByEmployee, $allNewTablesByEmployee);
                        $newModelCreate = array_diff($allNewTablesByEmployee, $allOldTablesByEmployee);
                        if (!empty($modelStatusChange)){

                            $allStatusChangeModel = BichuvTablesEmployees::find()
                                ->where(['in', 'bichuv_table_id', $modelStatusChange])
                                ->andWhere([
                                    'hr_employee_id' => $model['hr_employee_id'],
                                    'status' => $model::STATUS_ACTIVE
                                ])->all();

                            $saved = $model->modelStatusChange($allStatusChangeModel,$model['add_info']);

                        }
                        if (!empty($newModelCreate)){
                            $saved = $model->allModelSave($model,$allNewTablesByEmployee);
                        }
                    }

                    if($saved) {
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                }
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = [];
                    if ($saved) {
                        $response['status'] = 0;
                        $response['message'] = Yii::t('app', 'Saved Successfully');
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                        $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
                    }
                    return $response;
                }
                if ($saved) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
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
     * Deletes an existing BichuvTablesEmployees model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     */
    public function actionDelete($id)
    {

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);
            $isDeleted = false;
            $modelTables = BichuvTablesEmployees::find()->where([
                'hr_employee_id' => $model->hr_employee_id,
                'status' => $model::STATUS_ACTIVE
                ])->all();
            if(!empty($modelTables)){
                foreach ($modelTables as $model){
                    $model->status = $model::STATUS_SAVED;
                    if($model->save()){
                        $isDeleted = true;
                    }else{
                        $isDeleted = false;
                        break;
                    }
                }
            }

            if($isDeleted){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        }catch (\Exception $e){
            Yii::info('Not saved' . $e, 'save');
        }
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            if($isDeleted){
                $response['status'] = 0;
                $response['message'] = Yii::t('app','Deleted Successfully');
            }
            return $response;
        }
        if($isDeleted){
            Yii::$app->session->setFlash('success',Yii::t('app','Deleted Successfully'));
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Hatolik yuz berdi'));
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "bichuv-tables-employees_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BichuvTablesEmployees::find()->select([
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
     * Finds the BichuvTablesEmployees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BichuvTablesEmployees the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BichuvTablesEmployees::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
