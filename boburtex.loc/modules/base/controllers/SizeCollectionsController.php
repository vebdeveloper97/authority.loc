<?php

namespace app\modules\base\controllers;

use app\modules\base\models\SizeColRelSize;
use Yii;
use app\modules\base\models\SizeCollections;
use app\modules\base\models\SizeCollectionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SizeCollectionsController implements the CRUD actions for SizeCollections model.
 */
class SizeCollectionsController extends BaseController
{


    /**
     * Lists all SizeCollections models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SizeCollectionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SizeCollections model.
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
     * Creates a new SizeCollections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SizeCollections();
        $data = Yii::$app->request->post();

        if(Yii::$app->request->isAjax){
            if ($model->load($data)) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response['success'] = false;
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try{
                    if($model->save()){
                        $saved = true;
                    }

                    if(!empty($data['Sizes']) && $saved){
                        foreach ($data['Sizes'] as $item){
                            $modelSCRS = new SizeColRelSize();
                            $modelSCRS->setAttributes([
                                'size_id' => (int)$item,
                                'sc_id'  => $model->id
                            ]);
                            if($modelSCRS->save()) $saved = true;
                            else {$saved = false; break; }
                        }
                    }

                    if($saved){
                        $transaction->commit();
                        $response['success'] = true;
                        $response['title'] = $model->name;
                        $response['selected_id'] = $model->id;
                        $response['message'] = Yii::t('app', 'OK');
                    }
                    else{
                        $response['success'] = false;
                        $response['message'] = Yii::t('app', 'NO');
                    }
                    return $response;
                }
                catch(\Exception $e){
                    Yii::info('error message '.$e->getMessage(), 'save');
                    $response['success'] = false;
                    $response['message'] = Yii::t('app', 'Saqlanmadi!');
                    return $response;
                }
            }

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
        else{
            if ($model->load($data) && $model->save()) {
                if(!empty($data['Sizes'])){
                    foreach ($data['Sizes'] as $item){
                        $modelSCRS = new SizeColRelSize();
                        $modelSCRS->setAttributes([
                            'size_id' => (int)$item,
                            'sc_id'  => $model->id
                        ]);
                        $modelSCRS->save();
                    }
                }
                return $this->redirect(['index']);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SizeCollections model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->cp['rows'] = [];
        if(!empty($model->sizeColRelSizes)){
            foreach ($model->sizeColRelSizes as $key => $item){
                array_push($model->cp['rows'], $item->size_id);
            }
        }
        $data = Yii::$app->request->post();
        if ($model->load($data) && $model->save()) {
            if(!empty($model->sizeColRelSizes)){
                foreach ($model->sizeColRelSizes as $key => $item){
                    $item->delete();
                }
            }
            if(!empty($data['Sizes'])){
                foreach ($data['Sizes'] as $item){
                    $modelSCRS = new SizeColRelSize();
                    $modelSCRS->setAttributes([
                        'size_id' => (int)$item,
                        'sc_id'  => $model->id
                    ]);

                    $modelSCRS->save();
                }
            }
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SizeCollections model.
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

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "size-collections_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => SizeCollections::find()->select([
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
     * Finds the SizeCollections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SizeCollections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SizeCollections::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
