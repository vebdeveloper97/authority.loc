<?php

namespace app\modules\hr\controllers;

use app\models\Users;
use app\modules\hr\models\HrEmployee;
use Yii;
use app\modules\hr\models\HrEmployeeUsers;
use app\modules\hr\models\HrEmployeeUsersSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HrEmployeeUsersController implements the CRUD actions for HrEmployeeUsers model.
 */
class HrEmployeeUsersController extends Controller
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
     * Lists all HrEmployeeUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HrEmployeeUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HrEmployeeUsers model.
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
     * Creates a new HrEmployeeUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HrEmployeeUsers();
        $users = new Users();
        $employee = new HrEmployee();

        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            // malumotlarni saqlash
            $model->getSave($data['HrEmployeeUsers']);
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
            'employee' => $employee
        ]);
    }

    public function actionViewAll($id)
    {
        if(empty($id))
            throw new ForbiddenHttpException(Yii::t('app', 'Parametr mavjud emas!'));

        $model = "SELECT hr_employee_users.status, hr_employee.fish, users.username FROM hr_employee_users 
            LEFT JOIN hr_employee ON hr_employee_users.hr_employee_id = hr_employee.id 
            LEFT JOIN users ON hr_employee_users.users_id = users.id 
            WHERE hr_employee_users.hr_employee_id = $id AND hr_employee_users.status = 1";

        return $this->render('view_all', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing HrEmployeeUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new HrEmployeeUsers();
        $models = HrEmployeeUsers::find()
            ->where(['hr_employee_id' => $id])
            ->all();

        $model->cp['rows'] = [];

        if(!empty($models)){
            foreach($models as $key => $item){
                array_push($model->cp['rows'], $item['users_id']);
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->getStatusActive($model->cp['rows']);
            $model->getStatusInActive($model['users_id'], $model['hr_employee_id']);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models,
        ]);
    }

    /**
     * Deletes an existing HrEmployeeUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        /*$users = HrEmployeeUsers::findAll([
            'hr_employee_id' => $id
        ]);
        $array = [];
        $i = 0;
        foreach ($users as $item){
            $array[$i] = $item['users_id'];
            $i++;
        }

        Users::updateAll(
            ['status' => 1],
            ['and', ['in', 'id', $array]]);*/

        HrEmployeeUsers::deleteAll([
            'hr_employee_id' => $id
        ]);

        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "hr-employee-users_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => HrEmployeeUsers::find()->select([
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
     * Finds the HrEmployeeUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HrEmployeeUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HrEmployeeUsers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
