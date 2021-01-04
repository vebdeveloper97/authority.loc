<?php

namespace app\modules\bichuv\controllers;

use app\models\UserRfidKey;
use app\models\UserRoles;
use app\models\UsersInfo;
use kartik\form\ActiveForm;
use Yii;
use app\models\Users;
use app\modules\bichuv\models\BichuvUsersSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BichuvUsersController implements the CRUD actions for Users model.
 */
class BichuvUsersController extends BaseController
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
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BichuvUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $info = (UsersInfo::findOne(['users_id'=>$id]))?UsersInfo::findOne(['users_id'=>$id]):new UsersInfo();
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
                'info' => $info
            ]);
        }
        return $this->render('view', [
            'model' => $model,
            'info' => $info
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $info = new UsersInfo(['scenario' => UsersInfo::SCENARIO_CREATE]);
        $model->session_id = Yii::$app->getSecurity()->generateRandomString(32);
        $model->created_user = Yii::$app->user->identity->id;
        $model->user_role = 8;
        $model->add_info = 'Bichuv';
        $model->lavozimi = 'Bichuvchi';
        $info->smena = "A";
        if ($model->load(Yii::$app->request->post())&&$info->load(Yii::$app->request->post())) {
            if(empty($model->password)){
                $model->password = '1';
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                $model->uid = Users::find()->orderBy(['id'=>SORT_DESC])->one()['id']+1;
                $info->validate();
                if ($model->validate()&&$info->validate()) {
                    $model->save();
                    $info->users_id = $model->id;
                    $info->fio = $model->user_fio;
                    $info->lavozim = $model->lavozimi;
                    if($info->save()) {
                        $response['status'] = 0;
                    }else{
                        $response['status'] = 1;
                        $response['errors'] = $info->getErrors();
                    }
                } else {
                    $response['status'] = 1;
                    $response['errors'] = array_merge($model->getErrors(),$info->getErrors());
                }
                return $response;
            }
            if ($model->save()) {
                $info->users_id = $model->id;
                $info->fio = $model->user_fio;
                $info->save();
                if($info->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
                'info' => $info
            ]);
        }
        return $this->render('create', [
            'model' => $model,
            'info' => $info
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $info = (UsersInfo::findOne(['users_id'=>$id]))?UsersInfo::findOne(['users_id'=>$id]):new UsersInfo();
//        $model->scenario = Users::SCENARIO_UPDATE;
        if ($model->load(Yii::$app->request->post()) && $info->load(Yii::$app->request->post())) {
            $info->users_id = $id;
            $info->fio = $model->user_fio;
            $info->lavozim = $model->lavozimi;
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                $info->validate();
                if ($model->save()&&$info->save()) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = array_merge($model->getErrors(),$info->getErrors());
                }
                return $response;
            }
            if ($model->save()&&$info->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'info' => $info,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'info' => $info,
        ]);
    }
    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Users::SCENARIO_DELETE;
        $model->deleted_time = date('Y-m-d H:i:s');
        $response = [];
        $response['status'] = 1;
        $response['message'] = "Avvalroq o'chirilgan";
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if($model->status!=2) {
                    $model->status = 2;
                    if ($model->save()) {
                        $response['status'] = 0;
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                    }
                }else{
                    $response['status'] = 1;
                    $response['message'] = "Avvalroq o'chirilgan";
                }
                return $response;
            }
            if($model->status!=2) {
                $model->status = 2;
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }else{
                Yii::$app->session->setFlash('error', "Avvalroq o'chirilgan");
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('delete', [
                'model' => $model,
            ]);
        }

        return $this->render('delete', [
            'model' => $model,
        ]);
    }
    /*public function actionDelete($id)
    {
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
    }*/

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "bichuv-users_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => Users::find()->select([
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
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
