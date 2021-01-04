<?php

namespace app\modules\bichuv\controllers;

use app\modules\bichuv\models\BichuvGivenRolls;
use Yii;
use app\modules\bichuv\models\ModelRelProduction;
use app\modules\bichuv\models\ModelChangePriceSearch;
use app\modules\base\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ModelChangePriceController implements the CRUD actions for ModelRelProduction model.
 */
class ModelChangePriceController extends BaseController
{
    /**
     * Lists all ModelRelProduction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModelChangePriceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTayyorlov(){
        return $this->render('tayyorlov');
    }

    /**
     * Displays a single ModelRelProduction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ModelRelProduction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ModelRelProduction();
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if ($model->save()) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ModelRelProduction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if($model->is_accepted == 1){
                    if($model->type == 2){
                        $parts = ModelRelProduction::find()
                            ->leftJoin('bichuv_given_rolls','bichuv_given_rolls.id = model_rel_production.bichuv_given_roll_id')
                            ->andFilterWhere([
                                'order_id' => $model->order_id,
                                'order_item_id' => $model->order_item_id,
                                'models_list_id' => $model->models_list_id,
                                'model_variation_id' => $model->model_variation_id,
                                'bichuv_given_rolls.status' => 3
                                ])
                            ->andFilterWhere(['<>','model_rel_production.id', $model->id])
                            ->all();
                        if(!empty($parts)){
                            foreach ($parts as $part){
                                $part->is_accepted = 1;
                                $part->status = 3;
                                $part->save();
                            }
                        }
                    }
                    $model->status = 3;
                }else{
                    $model->status = 1;
                }
                if ($model->save()) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                return $response;
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
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
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "model-change-price_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => ModelRelProduction::find()->select([
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
     * @param $modelId
     * @param $orderId
     * @return array
     */
    public function actionGetModelVariations($modelId, $orderId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $items = BichuvGivenRolls::getOrderItemList($modelId,$orderId);
        $response['status'] = false;
        if (!empty($items)) {
            $response['status'] = true;
            $response['items'] = $items;
        }
        return $response;
    }

    /**
     * Finds the ModelRelProduction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelRelProduction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelRelProduction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
