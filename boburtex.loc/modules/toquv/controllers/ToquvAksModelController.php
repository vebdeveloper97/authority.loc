<?php

namespace app\modules\toquv\controllers;

use app\modules\base\models\ModelVarStoneRelAttach;
use app\modules\toquv\models\ToquvAksModelItem;
use Yii;
use app\modules\toquv\models\ToquvAksModel;
use app\modules\toquv\models\ToquvAksModelSearch;
use app\modules\toquv\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvAksModelController implements the CRUD actions for ToquvAksModel model.
 */
class ToquvAksModelController extends BaseController
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
     * Lists all ToquvAksModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvAksModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ToquvAksModel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $models = $model->toquvAksModelItems ?? [new ToquvAksModelItem()];
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
                'models' => $models,
            ]);
        }
        return $this->render('view', [
            'model' => $model,
            'models' => $models,
        ]);
    }

    /**
     * Creates a new ToquvAksModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvAksModel();
        $models = [new ToquvAksModelItem()];
        if ($model->load(Yii::$app->request->post())) {
            $saved = false;
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $saved = true;
                    $image = $data['image'];
                    $aks_item = $data['ToquvAksModelItem'];
                    $saved_image = true;
                    if(!empty($image)){
                        $model->image = $model->uploadBase64($image);
                        if($model->save()){
                            $saved = true;
                            $saved_image = true;
                        }else{
                            $saved = false;
                            $saved_image = false;
                        }
                    }
                    if(!empty($aks_item)&&$saved_image){
                        $saved = false;
                        foreach($aks_item as $key => $item){
                            $aks_model_item = new ToquvAksModelItem([
                                'toquv_aks_model_id' => $model->id,
                                'name' => $item['name'],
                                'toquv_ne_id' => $item['toquv_ne_id'],
                                'toquv_thread_id' => $item['toquv_thread_id'],
                                'toquv_ip_color_id' => $item['toquv_ip_color_id'],
                                'color_pantone_id' => $item['color_pantone_id'],
                                'height' => $item['height'],
                                'height_sm' => $item['height_sm'],
                                'percentage' => $item['percentage'],
                                'parent_percentage' => $item['parent_percentage'],
                                'ip_id' => $item['ip_id'],
                                'indeks' => $key
                            ]);
                            if($aks_model_item->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                }else{
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                $response = [];
                if ($saved) {
                    $response['status'] = 0;
                    $transaction->commit();
                    $response['model'] = $model;
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                } else {
                    $response['status'] = 1;
                    $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
                    $response['errors'] = $model->getErrors();
                    $transaction->rollBack();
                }
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $response;
                }
                if($saved){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }catch (\Exception $e){
                Yii::info('Not saved toquv aks model' . $e, 'save');
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
                'models' => $models,
            ]);
        }
        return $this->render('create', [
            'model' => $model,
            'models' => $models,
        ]);
    }

    /**
     * Updates an existing ToquvAksModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = $model->toquvAksModelItems ?? [new ToquvAksModelItem()];
        $old_image = $model->image;
        if ($model->load(Yii::$app->request->post())) {
            $saved = false;
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    ToquvAksModelItem::deleteAll(['toquv_aks_model_id'=>$model->id]);
                    $saved = true;
                    $image = $data['image'];
                    $aks_item = $data['ToquvAksModelItem'];
                    $saved_image = true;
                    if(!empty($image)){
                        $model->image = $model->uploadBase64($image);
                        if($model->save()){
                            $saved = true;
                            $saved_image = true;
                        }else{
                            $saved = false;
                            $saved_image = false;
                        }
                    }
                    if(!empty($aks_item)&&$saved_image){
                        $saved = false;
                        foreach($aks_item as $key => $item){
                            $aks_model_item = new ToquvAksModelItem([
                                'toquv_aks_model_id' => $model->id,
                                'name' => $item['name'],
                                'toquv_ne_id' => $item['toquv_ne_id'],
                                'toquv_thread_id' => $item['toquv_thread_id'],
                                'toquv_ip_color_id' => $item['toquv_ip_color_id'],
                                'color_pantone_id' => $item['color_pantone_id'],
                                'height' => $item['height'],
                                'height_sm' => $item['height_sm'],
                                'percentage' => $item['percentage'],
                                'parent_percentage' => $item['parent_percentage'],
                                'ip_id' => $item['ip_id'],
                                'indeks' => $key
                            ]);
                            if($aks_model_item->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                }else{
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                $response = [];
                if ($saved) {
                    if(!empty($image)&&file_exists($old_image)){
                        unlink($old_image);
                    }
                    $response['status'] = 0;
                    $transaction->commit();
                    $response['model'] = $model;
                    $response['message'] = Yii::t('app', 'Saved Successfully');
                } else {
                    $response['status'] = 1;
                    $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
                    $response['errors'] = $model->getErrors();
                    $transaction->rollBack();
                }
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $response;
                }
                if($saved){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }catch (\Exception $e){
                Yii::info('Not saved toquv aks model' . $e, 'save');
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'models' => $models,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models,
        ]);
    }
    public function actionAjaxRequest($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['results'] = [];
        if (!empty($q)) {
            $searchModel = new ToquvAksModel();
            $res = $searchModel->getColorList($q);
            if (!empty($res)) {
                foreach ($res as $item) {
                    $name = "<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>"
                        .$item['tname'] . "</span></span> ".$item['ccode'] . " - <b>"
                        . $item['cname'] . "</b>";
                    array_push($response['results'], [
                        'id' => $item['id'],
                        'text' => $name,
                    ]);
                }
            } else {
                $response['results'] = [
                    'id' => '',
                    'text' => '',
                ];
            }
        }
        return $response;
    }
    /**
     * Deletes an existing ToquvAksModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
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
        $filename = "toquv-aks-model_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => ToquvAksModel::find()->select([
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
     * Finds the ToquvAksModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvAksModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvAksModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
