<?php

namespace app\modules\mobile\controllers;

use app\modules\hr\models\HrEmployee;
use app\modules\mobile\models\MobileTablesRelHrEmployee;
use Yii;
use app\modules\mobile\models\MobileTables;
use app\modules\mobile\models\MobileTablesSearch;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * MobileTablesController implements the CRUD actions for MobileTables model.
 */
class MobileTablesController extends Controller
{
    public $layout = '@app/views/layouts/wbm';

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
     * Lists all MobileTables models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MobileTablesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MobileTables model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $responsiblePersons = null;
        if (isset($model->id)) {
            $query = MobileTablesRelHrEmployee::find()
                ->joinWith(['hrEmployee' => function($q) {
                    $q->select(['id', 'fish']);
                }])
                ->andWhere(['mobile_tables_id' => $model->id])
                ->addOrderBy(['mobile_tables_id' => SORT_ASC, 'start_date' => SORT_DESC]);

            $responsiblePersons = $query->all();
        }



        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
                'responsiblePersons' => $responsiblePersons,
            ]);
        }
        return $this->render('view', [
            'model' => $model,
            'responsiblePersons' => $responsiblePersons,
        ]);
    }

    /**
     * Creates a new MobileTables model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new MobileTables();
        $responsiblePersonRel = [new MobileTablesRelHrEmployee(['scenario' => MobileTablesRelHrEmployee::SCENARIO_CREATE])];
        if ($request->isPost) {

            /** begin load multiple input */
            $responsiblePersonRelData = $request->post('MobileTablesRelHrEmployee', []);
            foreach (array_keys($responsiblePersonRelData) as $index) {
                $responsiblePersonRel[$index] = new MobileTablesRelHrEmployee(['scenario' => MobileTablesRelHrEmployee::SCENARIO_CREATE]);
            }
            /** end load multiple input */

            if (
                $model->load($request->post())
                && Model::loadMultiple($responsiblePersonRel, $request->post())
            ) {

                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if($model->save()){
                        $saved = true;
                    }else{
                        $saved = false;
                    }

                    /** begin save responsible person */
                    if ($saved) {
                        foreach ($responsiblePersonRel as $responsiblePersonItem) {
                            $responsiblePersonItem->setAttributes([
                                'mobile_tables_id' => $model->id,
                            ]);
                            $saved = $responsiblePersonItem->save();

                            if (!$saved) {
                                break;
                            }
                        }
                    }
                    /** end save responsible person */

                    if($saved) {
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                    }
                }
                catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $saved = false;
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
                'responsiblePersonRel' => $responsiblePersonRel
            ]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MobileTables model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $responsiblePersonRel = $model->mobileTablesRelHrEmployees;

        if ($request->isPost) {

            /** begin create empty items multiple input */
            $responsiblePersonRelData = $request->post('MobileTablesRelHrEmployee', []);
            foreach (array_keys($responsiblePersonRelData) as $index) {
                $responsiblePersonRel[$index] = new MobileTablesRelHrEmployee();
            }
            /** end create empty items multiple input */

            if (
                $model->load($request->post())
                && Model::loadMultiple($responsiblePersonRel, $request->post())
            ) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;

                try {
                    if($model->save()){
                        $saved = true;
                    }else{
                        $saved = false;
                    }

                    /** begin save responsible person */
                    if ($saved) {
                        foreach ($responsiblePersonRel as $responsiblePersonItem) {
                            if ( // oldingi item ni update qilish
                                $responsiblePersonItem->id
                                && ($oldItem = MobileTablesRelHrEmployee::findOne([
                                    'id' => $responsiblePersonItem->id,
                                    'mobile_tables_id' => $model->id,
                                    'status' => MobileTablesRelHrEmployee::STATUS_ACTIVE
                                ]))
                            ) {
                                Yii::error('old', 'flag');
                                $oldItem->setAttributes($responsiblePersonItem->getAttributes(['start_date', 'end_date']));
                                $saved = $oldItem->save();
                            }
                            else if ($responsiblePersonItem->id) {
                                // TODO: agar olding itemni yangilashga urinsa (status -> 3), logika yozish kerak
                                $saved = true;
                            }
                            else { // yangi mas'ul shaxs qo'shilyabdi
                                Yii::error('new', 'flag');
                                $responsiblePersonItem->setAttributes([
                                    'mobile_tables_id' => $model->id,
                                ]);
                                $saved = $responsiblePersonItem->save();
                            }

                            if (!$saved) {
                                break;
                            }
                        }
                    }
                    /** end save responsible person */

                    if($saved) {
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                    }
                }
                catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $saved = false;
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
                'responsiblePersonRel' => $responsiblePersonRel
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'responsiblePersonRel' => $responsiblePersonRel
        ]);
    }

    /**
     * Deletes an existing MobileTables model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);
            $isDeleted = false;
            if($model->delete()){
                $isDeleted = true;
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
        $filename = "mobile-tables_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => MobileTables::find()->select([
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
     * Finds the MobileTables model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MobileTables the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MobileTables::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
