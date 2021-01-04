<?php

namespace app\modules\admin\controllers;

use app\models\Users;
use app\modules\toquv\models\ToquvDepartments;
use Yii;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\admin\models\ToquvUserDepartmentSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserDepartmentController implements the CRUD actions for ToquvUserDepartment model.
 */
class UserDepartmentController extends Controller
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
     * Lists all ToquvUserDepartment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvUserDepartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ToquvUserDepartment model.
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
     * Creates a new ToquvUserDepartment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvUserDepartment();

        if(Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $isAllSaved = false;
            if(!empty($data['departments'])){
                $dataLoop = [];
                foreach ($data['departments'] as $departmentId){
                    $isAllSaved = false;
                    $modelLoop = new ToquvUserDepartment();
                    $dataLoop = $data;
                    $dataLoop['ToquvUserDepartment']['department_id'] = $departmentId;
                    $dataLoop['ToquvUserDepartment']['type'] = ToquvUserDepartment::OWN_DEPARTMENT_TYPE;
                    if ($modelLoop->load($dataLoop) && $modelLoop->save()) {
                        $isAllSaved = true;
                        unset($modelLoop);
                    }
                }
            }
            if(!empty($data['departments_2'])){
                $dataLoop = [];
                foreach ($data['departments_2'] as $departmentId){
                    $isAllSaved = false;
                    $modelLoop = new ToquvUserDepartment();
                    $dataLoop = $data;
                    $dataLoop['ToquvUserDepartment']['department_id'] = $departmentId;
                    $dataLoop['ToquvUserDepartment']['type'] = ToquvUserDepartment::FOREIGN_DEPARTMENT_TYPE; // TODO hard code typlarga nom bera olmadim
                    if ($modelLoop->load($dataLoop) && $modelLoop->save()) {
                        $isAllSaved = true;
                        unset($modelLoop);
                    }
                }
            }
            if ($isAllSaved) {
                return $this->redirect(['index']);
            }
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->cp['rows'] = [];
        $model->cp['rows2'] = [];

        $departments = ToquvUserDepartment::find()->where(['user_id' => $model->user_id, 'type' => '0'])->all();
        if(!empty($departments)){
            foreach ($departments as $key => $item){
                array_push($model->cp['rows'], $item['department_id']);
            }
        }
        $departments2 = ToquvUserDepartment::find()->where(['user_id' => $model->user_id, 'type' => '1'])->all();
        if(!empty($departments2)){
            foreach ($departments2 as $key => $item){
                array_push($model->cp['rows2'], $item['department_id']);
            }
        }

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $isAllSaved = false;
            //Delete all old items
            if($departments !== null){
                foreach ($departments as $item){
                    $item->delete();
                }
            }
            if($departments2 !== null){
                foreach ($departments2 as $item){
                    $item->delete();
                }
            }

            if(!empty($data['departments'])){
                $dataLoop = [];
                foreach ($data['departments'] as $departmentId){
                    $isAllSaved = false;
                    $modelLoop = new ToquvUserDepartment();
                    $dataLoop = $data;
                    $dataLoop['ToquvUserDepartment']['department_id'] = $departmentId;
                    $dataLoop['ToquvUserDepartment']['type'] = ToquvUserDepartment::OWN_DEPARTMENT_TYPE;
                    if ($modelLoop->load($dataLoop) && $modelLoop->save()) {
                        $isAllSaved = true;
                        unset($modelLoop);
                    }
                }
            }
            if(!empty($data['departments_2'])){
                $dataLoop = [];
                foreach ($data['departments_2'] as $departmentId){
                    $isAllSaved = false;
                    $modelLoop = new ToquvUserDepartment();
                    $dataLoop = $data;
                    $dataLoop['ToquvUserDepartment']['department_id'] = $departmentId;
                    $dataLoop['ToquvUserDepartment']['type'] = ToquvUserDepartment::FOREIGN_DEPARTMENT_TYPE;
                    if ($modelLoop->load($dataLoop) && $modelLoop->save()) {
                        $isAllSaved = true;
                        unset($modelLoop);
                    }
                }
            }
            if ($isAllSaved) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $userDeps = ToquvUserDepartment::find()->where(['user_id' => $model->user_id])->all();

        if($userDeps !== null){
            foreach ($userDeps as $item){
                $item->delete();
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ToquvUserDepartment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvUserDepartment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvUserDepartment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
