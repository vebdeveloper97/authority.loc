<?php

namespace app\modules\mechanical\controllers;

use app\modules\mechanical\models\SparePassportItems;
use Yii;
use app\modules\mechanical\models\SpareItemRelHrEmployee;
use app\modules\mechanical\models\search\SpareItemRelHrEmployeeSearch;
use app\modules\mechanical\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SpareItemRelHrEmployeeController implements the CRUD actions for SpareItemRelHrEmployee model.
 */
class SpareItemRelHrEmployeeController extends BaseController
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
     * Lists all SpareItemRelHrEmployee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpareItemRelHrEmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SpareItemRelHrEmployee model.
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

    public function actionCopy($id)
    {
        $model = $this->findModel($id);
        $models = (!empty($model->sparePassportItems)) ? $model->sparePassportItems : [new SparePassportItems()];
        $request = Yii::$app->request;
        if($request->isPost){
            $model = new SpareItemRelHrEmployee();
            if ($model->load(Yii::$app->request->post())) {
                $data = Yii::$app->request->post();
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try{
                    $existsModel = SpareItemRelHrEmployee::find()->where([
                        'inv_number' => $model['inv_number'],
                        'status' => $model::STATUS_ACTIVE,
                        'spare_item_id' => $model['spare_item_id']
                    ])->exists();
                    if($existsModel){
                        Yii::$app->session->setFlash('error',Yii::t('app','Xodim bu mashinaga avvaldan javobgar'));
                        return $this->redirect('create', ['model' => $model]);
                    }

                    if($model->save()){
                        if(!empty($data['SparePassportItems'])){
                            $saved = $model->saveSparePassportItems($model['id'], $data['SparePassportItems']);
                        }else{
                            $saved = true;
                        }
                    }
                    if($saved){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success',Yii::t('app','Saqlandi'));
                        return $this->redirect(['index']);
                    }else{
                        $transaction->rollBack();
                    }
                }catch(\Exception $e){
                    Yii::info('Not saved'.$e,'save');
                    $transaction->rollBack();
                }
            }
        }

        if ($request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                'models' => $models
            ]);
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models
        ]);

    }


    /**
     * Creates a new SpareItemRelHrEmployee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SpareItemRelHrEmployee();
        $models = [new SparePassportItems()];
        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
           try{
               $existsModel = SpareItemRelHrEmployee::find()->where([
                   'inv_number' => $model['inv_number'],
                   'status' => $model::STATUS_ACTIVE,
                   'spare_item_id' => $model['spare_item_id']
               ])->exists();
               if($existsModel){
                   Yii::$app->session->setFlash('error',Yii::t('app','Xodim bu mashinaga avvaldan javobgar'));
                   return $this->redirect('create', ['model' => $model]);
               }

               if($model->save()){
                   if(!empty($data['SparePassportItems'])){
                       $saved = $model->saveSparePassportItems($model['id'], $data['SparePassportItems']);
                   }else{
                       $saved = true;
                   }
               }
               if($saved){
                   $transaction->commit();
                   Yii::$app->session->setFlash('success',Yii::t('app','Saqlandi'));
                   return $this->redirect(['view', 'id' => $model->id]);
               }else{

                   $transaction->rollBack();
               }
           }catch(\Exception $e){
               Yii::info('Not saved'.$e,'save');
               $transaction->rollBack();
           }
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * Updates an existing SpareItemRelHrEmployee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = $model->sparePassportItems;
        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $existsModel = SpareItemRelHrEmployee::find()->where([
                    'inv_number' => $model['inv_number'],
                    'status' => $model::STATUS_ACTIVE,
                    'spare_item_id' => $model['spare_item_id']
                ])->exists();
                if($existsModel){
                    Yii::$app->session->setFlash('error',Yii::t('app','Xodim bu mashinaga avvaldan javobgar'));
                    return $this->redirect('create', ['model' => $model]);
                }

                if($model['hr_employee_id'] != $model->oldAttributes['hr_employee_id']){
                    $newModel = new SpareItemRelHrEmployee();
                    $newModel->attributes = $model->getAttributes();
                    if($newModel->save()){
                        if(!empty($data['SparePassportItems'])){
                            $saved = $model->saveSparePassportItems($newModel['id'], $data['SparePassportItems']);
                            $model->updateCounters(['status' => 2]);
                        }else{
                            $saved = true;
                        }
                    }
                }else{
                    SparePassportItems::deleteAll(['sirhe_id' => $id]);
                    if($model->save()){
                        if(!empty($data['SparePassportItems'])){
                            $saved = $model->saveSparePassportItems($model['id'], $data['SparePassportItems']);
                        }else{
                            $saved = true;
                        }
                    }
                }

                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success',Yii::t('app','Saqlandi'));
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{

                    $transaction->rollBack();
                }
            }catch(\Exception $e){
                Yii::info('Not saved'.$e,'save');
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * Deletes an existing SpareItemRelHrEmployee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        SparePassportItems::deleteAll(['sirhe_id' => $id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "spare-item-rel-hr-employee_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => SpareItemRelHrEmployee::find()->select([
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
     * Finds the SpareItemRelHrEmployee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SpareItemRelHrEmployee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SpareItemRelHrEmployee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
