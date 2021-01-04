<?php

namespace app\modules\base\controllers;

use app\modules\base\models\BaseNormStandartItems;
use Yii;
use app\modules\base\models\BaseNormStandart;
use app\modules\base\models\BaseNormStandartSearch;
use app\modules\mechanical\controllers\BaseController;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BaseNormStandartController implements the CRUD actions for BaseNormStandart model.
 */
class BaseNormStandartController extends BaseController
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
     * Lists all BaseNormStandart models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BaseNormStandartSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BaseNormStandart model.
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
     * Creates a new BaseNormStandart model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BaseNormStandart();
        $models = [new BaseNormStandartItems()];
        $request = Yii::$app->request;
        if ($request->isPost){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $standartItems = $request->post('BaseNormStandartItems');
                if(!empty($standartItems)){
                    unset($models);
                    foreach ($standartItems as $key => $standartItem){
                        $models[$key] = new BaseNormStandartItems(['scenario' => BaseNormStandartItems::SCENARIO_CREATE]);
                    }
                }
                if ($model->load($request->post()) && Model::loadMultiple($models,$request->post())) {
                    if ($saved = $model->save()){
                        if (!empty($models)){
                            foreach ($models as $item) {
                                $item->norm_standart_id = $model->id;
                                if ($item->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                    }
                }
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app','Saved Successfully'));
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    Yii::$app->session->setFlash('error', Yii::t('app','Saqlashda xatolik!'));
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
     * Updates an existing BaseNormStandart model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = (!empty($model->baseNormStandartItems)) ? $model->baseNormStandartItems : [new BaseNormStandartItems()];
        $request = Yii::$app->request;
        if ($request->isPost){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $standartItems = $request->post('BaseNormStandartItems');
                if(!empty($standartItems)){
                    unset($models);
                    foreach ($standartItems as $key => $standartItem){
                        $models[$key] = new BaseNormStandartItems(['scenario' => BaseNormStandartItems::SCENARIO_CREATE]);
                    }
                }
                if ($model->load($request->post()) && Model::loadMultiple($models,$request->post())) {
                    if ($saved = $model->save()){
                        BaseNormStandartItems::deleteAll(['norm_standart_id' => $id]);
                        if (!empty($models)){
                            foreach ($models as $item) {
                                $item->norm_standart_id = $model->id;
                                if ($item->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                    }
                }
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app','Saved Successfully'));
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    Yii::$app->session->setFlash('error', Yii::t('app','Saqlashda xatolik!'));
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
     * Deletes an existing BaseNormStandart model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        BaseNormStandartItems::deleteAll(['norm_standart_id' => $id]);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app','O\'chirildi'));
        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "base-norm-standart_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BaseNormStandart::find()->select([
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
     * Finds the BaseNormStandart model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaseNormStandart the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BaseNormStandart::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
