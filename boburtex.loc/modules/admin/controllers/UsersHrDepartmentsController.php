<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\admin\models\UsersHrDepartmentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UsersHrDepartmentsController implements the CRUD actions for UsersHrDepartments model.
 */
class UsersHrDepartmentsController extends Controller
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
     * Lists all UsersHrDepartments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersHrDepartmentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UsersHrDepartments model.
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
     * Creates a new UsersHrDepartments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UsersHrDepartments();

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            $isAllSaved = false;
            try {
                if(!empty($data['departments'])){
                    $dataLoop = [];
                    $departments = explode(',', $data['departments']);
                    foreach ($departments as $departmentId){
                        $isAllSaved = false;
                        $modelLoop = new UsersHrDepartments();
                        $dataLoop = $data;
                        $dataLoop['UsersHrDepartments']['hr_departments_id'] = $departmentId;
                        $dataLoop['UsersHrDepartments']['type'] = UsersHrDepartments::OWN_DEPARTMENT_TYPE;
                        if ($modelLoop->load($dataLoop) && $modelLoop->save()) {
                            $isAllSaved = true;
                            unset($modelLoop);
                        }
                    }
                }
                if(!empty($data['departments_2'])){
                    $dataLoop = [];
                    $departments = explode(',', $data['departments_2']);
                    foreach ($departments as $departmentId){
                        $isAllSaved = false;
                        $modelLoop = new UsersHrDepartments();
                        $dataLoop = $data;
                        $dataLoop['UsersHrDepartments']['hr_departments_id'] = $departmentId;
                        $dataLoop['UsersHrDepartments']['type'] = UsersHrDepartments::FOREIGN_DEPARTMENT_TYPE; // TODO hard code typlarga nom bera olmadim
                        if ($modelLoop->load($dataLoop) && $modelLoop->save()) {
                            $isAllSaved = true;
                            unset($modelLoop);
                        }
                    }
                }
                if($isAllSaved) {
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
                if ($isAllSaved) {
                    $response['status'] = 0;
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                    $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
                }
                return $response;
            }
            if ($isAllSaved) {
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
     * Updates an existing UsersHrDepartments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->cp['rows'] = [];
        $model->cp['rows2'] = [];

        $departments = UsersHrDepartments::find()->where(['user_id' => $model->user_id, 'type' => '0'])->all();
        if(!empty($departments)){
            foreach ($departments as $key => $item){
                array_push($model->cp['rows'], $item['hr_departments_id']);
            }
        }
        $departments2 = UsersHrDepartments::find()->where(['user_id' => $model->user_id, 'type' => '1'])->all();
        if(!empty($departments2)){
            foreach ($departments2 as $key => $item){
                array_push($model->cp['rows2'], $item['hr_departments_id']);
            }
        }

        if (Yii::$app->request->isPost) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = true;
                try {
                    $data = Yii::$app->request->post();
                    //Delete all old items
                    if($departments !== null){
                        foreach ($departments as $item){
                            if ($item->delete() === false) {
                                $saved = false;
                                break;
                            }
                        }
                    }
                    if($departments2 !== null){
                        foreach ($departments2 as $item){
                            if ($item->delete() === false) {
                                $saved = false;
                                break;
                            }
                        }
                    }

                    if(!empty($data['departments'])){
                        $dataLoop = [];
                        $departments = explode(',', $data['departments']);
                        foreach ($departments as $departmentId){
                            $modelLoop = new UsersHrDepartments();
                            $dataLoop = $data;
                            $dataLoop['UsersHrDepartments']['hr_departments_id'] = $departmentId;
                            $dataLoop['UsersHrDepartments']['type'] = UsersHrDepartments::OWN_DEPARTMENT_TYPE;
                            if ($saved = $saved && ($modelLoop->load($dataLoop) && $modelLoop->save())) {
                                unset($modelLoop);
                            }
                        }
                    }
                    if(!empty($data['departments_2'])){
                        $dataLoop = [];
                        $departments = explode(',', $data['departments_2']);
                        foreach ($departments as $departmentId){
                            $modelLoop = new UsersHrDepartments();
                            $dataLoop = $data;
                            $dataLoop['UsersHrDepartments']['hr_departments_id'] = $departmentId;
                            $dataLoop['UsersHrDepartments']['type'] = UsersHrDepartments::FOREIGN_DEPARTMENT_TYPE;
                            if ($saved = $saved && ($modelLoop->load($dataLoop) && $modelLoop->save())) {
                                unset($modelLoop);
                            }
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
     * Deletes an existing UsersHrDepartments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        $isDeleted = false;
        try {
            $deletedItemCount = UsersHrDepartments::deleteAll(['user_id' => $model->user_id]);
            if($deletedItemCount > 0){
                $isDeleted = true;
            }
            if($isDeleted){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        }catch (\Exception $e){
            Yii::info('Not saved' . $e, 'save');
            $transaction->rollBack();
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
        $filename = "users-hr-departments_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => UsersHrDepartments::find()->select([
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
     * Finds the UsersHrDepartments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UsersHrDepartments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UsersHrDepartments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
