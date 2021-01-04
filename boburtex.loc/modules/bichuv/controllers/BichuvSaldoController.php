<?php

namespace app\modules\bichuv\controllers;

use Yii;
use app\modules\bichuv\models\BichuvSaldo;
use app\modules\bichuv\models\BichuvSaldoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BichuvSaldoController implements the CRUD actions for BichuvSaldo model.
 */
class BichuvSaldoController extends Controller
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
     * Lists all BichuvSaldo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BichuvSaldoSearch();
        $params = Yii::$app->request->queryParams;
        $data = [];
        $data['from_date'] = date('d.m.Y');
        $data['to_date'] = date('d.m.Y', strtotime('tomorrow'));

        if(!empty($params['BichuvSaldoSearch'])){
            if(!empty($params['BichuvSaldoSearch']) && !empty($params['BichuvSaldoSearch']['from_date'])){
                $data['from_date'] = $params['BichuvSaldoSearch']['from_date'];
            }
            if(!empty($params['BichuvSaldoSearch']) && !empty($params['BichuvSaldoSearch']['to_date'])){
                $data['to_date'] = $params['BichuvSaldoSearch']['to_date'];
            }
        } else {
            $params['BichuvSaldoSearch']['from_date'] = date('Y-m-d');
            $params['BichuvSaldoSearch']['to_date'] = date('Y-m-d', strtotime('tomorrow'));
        }

        $items = $searchModel->search($params);

        return $this->render('saldo2', [
            'model' => $searchModel,
            'data' => $data,
            'items' => $items
        ]);
    }

    /**
     * Displays a single BichuvSaldo model.
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
     * Creates a new BichuvSaldo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BichuvSaldo();

        if ($model->load(Yii::$app->request->post())) {
            $model->debit1 = 0;
            $model->credit1 = 0;
            $model->reg_date = date('Y-m-d H:i:s', strtotime($model->reg_date));
            $model->operation == 1 ? $model->credit1 = $model->summa : $model->debit1 = $model->summa;

            //echo "<pre>";var_dump($model); exit();
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
     * Updates an existing BichuvSaldo model.
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
     * Deletes an existing BichuvSaldo model.
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
     * Finds the BichuvSaldo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BichuvSaldo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BichuvSaldo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
