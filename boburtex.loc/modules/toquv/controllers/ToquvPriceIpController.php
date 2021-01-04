<?php

namespace app\modules\toquv\controllers;

use Yii;
use app\modules\toquv\models\ToquvPriceIp;
use app\modules\toquv\models\ToquvPriceIpItem;
use app\modules\toquv\models\ToquvPriceIpSearch;
use app\modules\toquv\models\ToquvPriceIpItemSearch;
use app\modules\toquv\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ToquvPriceIpController implements the CRUD actions for ToquvPriceIp model.
 */
class ToquvPriceIpController extends BaseController
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
     * Lists all ToquvPriceIp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvPriceIpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ToquvPriceIp model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new ToquvPriceIpItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new ToquvPriceIp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvPriceIp();
        $models = [new ToquvPriceIpItem()];
        $model->reg_date = date('d.m.Y');
        $lastId = $model::find()->select('id')->orderBy(['id'=>SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->doc_number =  "TPI".$lastId . "/" . date('Y');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $data = Yii::$app->request->post('ToquvPriceIpItem');
            if($data){
                $model->savePricing($data);
            }

            Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * Updates an existing ToquvPriceIp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = ($model->toquvPriceIpItems)?$model->toquvPriceIpItems:[new ToquvPriceIpItem()];
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $data = Yii::$app->request->post('ToquvPriceIpItem');
            if($data){
                $model->savePricing($data);
            }
            $remove = Yii::$app->request->post('remove');
            if($remove){
                $model->removePricing($remove);
            }

            Yii::$app->session->setFlash('success',Yii::t('app','Saved Successfully'));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * Deletes an existing ToquvPriceIp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!empty($model->toquvPriceIpItems)){
            foreach ($model->toquvPriceIpItems as $item){
                $item->delete();
            }
        }
        $model->delete();

        return $this->redirect(['index']);
    }
    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id){

        $model = $this->findModel($id);
        if($model->status !== ToquvPriceIp::STATUS_SAVED){
            $model->status = ToquvPriceIp::STATUS_SAVED;
            $model->save();
        }
        return $this->redirect(['view','id' => $id]);
    }
    /**
     * Finds the ToquvPriceIp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvPriceIp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvPriceIp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
