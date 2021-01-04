<?php

namespace app\modules\hr\controllers;

use app\modules\hr\models\HrAdditionTaskItems;
use Yii;
use app\modules\hr\models\HrAdditionTaskToEmployees;
use app\modules\hr\models\HrAdditionTaskToEmployeesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\hr\controllers\BaseController;

/**
 * HrAdditionTaskToEmployeesController implements the CRUD actions for HrAdditionTaskToEmployees model.
 */
class HrAdditionTaskToEmployeesController extends BaseController
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
     * Lists all HrAdditionTaskToEmployees models.
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $searchModel = new HrAdditionTaskToEmployeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HrAdditionTaskToEmployees model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $models = $model->hrAdditionTaskItems;
        return $this->render('view', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * Creates a new HrAdditionTaskToEmployees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HrAdditionTaskToEmployees();
        $models = [new HrAdditionTaskItems()];
        $model->hr_employee_id = Yii::$app->request->get('hr_employee_id');
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $hrAdditionTaskItems = Yii::$app->request->post()['HrAdditionTaskItems'];
                $model->reg_date =  date("Y-m-d H:i:s");
                if($model->save()){
                    foreach ($hrAdditionTaskItems as $additionTaskItem){
                        $newHrAdditionTaskItem = new HrAdditionTaskItems([
                           'hr_addition_task_id' => $model->id,
                            'task' => $additionTaskItem['task'],
                            'rate' => $additionTaskItem['rate'],
                        ]);
                        if($newHrAdditionTaskItem->save()){
                            $saved = true;
                        }else{
                            Yii::$app->session->setFlash('error', $newHrAdditionTaskItem->getErrors());
                            $transaction->rollBack();
                        }
                    }
                }
                if($saved){
                    Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                }
            }catch(\Exception $e){
                Yii::info('Not saved'.$e,'save');
                $transaction->rollBack();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('create', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * Updates an existing HrAdditionTaskToEmployees model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = (!empty($model->hrAdditionTaskItems)) ? $model->hrAdditionTaskItems : [new HrAdditionTaskItems()];

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                HrAdditionTaskItems::deleteAll(['hr_addition_task_id' => $model->id]);
                $hrAdditionTaskItems = Yii::$app->request->post()['HrAdditionTaskItems'];
                if($model->save()){
                    foreach ($hrAdditionTaskItems as $additionTaskItem){
                        $newHrAdditionTaskItem = new HrAdditionTaskItems([
                            'hr_addition_task_id' => $model->id,
                            'task' => $additionTaskItem['task'],
                            'rate' => $additionTaskItem['rate'],
                        ]);

                        if($newHrAdditionTaskItem->save()){
                            $saved = true;
                        }else{
                            Yii::$app->session->setFlash('error', $newHrAdditionTaskItem->getErrors());
                            $transaction->rollBack();
                        }
                    }
                }
                if($saved){
                    Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                }
            }catch(\Exception $e){
                Yii::info('Not saved'.$e,'save');
                $transaction->rollBack();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * Deletes an existing HrAdditionTaskToEmployees model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "hr-addition-task-to-employees_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => HrAdditionTaskToEmployees::find()->select([
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
     * Finds the HrAdditionTaskToEmployees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HrAdditionTaskToEmployees the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HrAdditionTaskToEmployees::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function actionHistory($id)
    {
        $model = self::findModel($id);
        $results = [];

        $models = HrAdditionTaskToEmployees::find()
            ->orderBy(['id' => SORT_DESC])
            ->andWhere(['hr_employee_id' => $model['hr_employee_id']])
            ->all();

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('history', [
                'results' => $results,
                'models' => $models
            ]);
        }

    }
}
