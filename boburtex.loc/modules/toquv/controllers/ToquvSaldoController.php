<?php

namespace app\modules\toquv\controllers;

use Yii;
use app\modules\toquv\models\ToquvSaldo;
use app\modules\toquv\models\ToquvSaldoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvSaldoController implements the CRUD actions for ToquvSaldo model.
 */
class ToquvSaldoController extends BaseController
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
     * Lists all ToquvSaldo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvSaldoSearch();
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = date('d.m.Y');
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));

        if(!empty($params['ToquvSaldoSearch'])){
            if(!empty($params['ToquvSaldoSearch']) && !empty($params['ToquvSaldoSearch']['from_date'])){
                $data['from_date'] = $params['ToquvSaldoSearch']['from_date'];
            }
            if(!empty($params['ToquvSaldoSearch']) && !empty($params['ToquvSaldoSearch']['to_date'])){
                $data['to_date'] = $params['ToquvSaldoSearch']['to_date'];
            }
        } else {
            $params['ToquvSaldoSearch']['from_date'] = date('Y-m-d');
            $params['ToquvSaldoSearch']['to_date'] = date('Y-m-d', strtotime('tomorrow'));
        }

        $items = $searchModel->search($params);

        return $this->render('saldo2', [
            'model' => $searchModel,
            'data' => $data,
            'items' => $items
        ]);
    }

    /**
     * Displays a single ToquvSaldo model.
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
     * Creates a new ToquvSaldo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvSaldo();

        if ($model->load(Yii::$app->request->post())) {
            $model->operation == 1 ? $model->credit1 = $model->summa :  $model->debit1 = $model->summa;

            if($model->save()){
                return $this->redirect('index');
            }

        }
        $model->reg_date = date('d.m.Y');
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ToquvSaldo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            if($model->save()){
                $response['status'] = 0;
            }else{
                $response['status'] = 1;
                $response['errors'] = $model->getErrors();
            }

            return $response;
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ToquvSaldo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ToquvSaldo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvSaldo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvSaldo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
