<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

namespace app\modules\toquv\controllers;

use app\modules\toquv\models\ToquvAksModel;
use app\modules\toquv\models\ToquvInstructions;
use app\modules\toquv\models\ToquvRawMaterials;
use Yii;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvOrdersSearch;
use yii\web\NotFoundHttpException;
use app\modules\toquv\models\ToquvRmOrder;
use app\modules\toquv\models\ToquvRawMaterialIp;
use app\modules\toquv\models\ToquvRawMaterialConsist;

/**
 * ToquvOrdersController implements the CRUD actions for ToquvOrders model.
 */
class ToquvOrdersAcsController extends BaseController
{
    /**
     * Lists all ToquvOrders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'order',2);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionModelOrders()
    {
        $searchModel = new ToquvOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'model_orders',2);
        return $this->render('model-orders', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    /**
     * Displays a single ToquvOrders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'models' => $model->toquvRmOrders,
        ]);
    }

    /**
     * Creates a new ToquvOrders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvOrders();
        $models = [new ToquvRmOrder];
        $model->reg_date = date('d.m.Y');
        $lastId = $model::find()->select('id')->orderBy(['id'=>SORT_DESC])->asArray()->one();
        $lastId = $lastId ? $lastId['id'] + 1 : 1;
        $model->document_number =  "TO".$lastId . "/" . date('m-Y');
        $model->priority = 1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $responsible = Yii::$app->request->post('ToquvOrders')['responsible'];
            if($responsible){
                $model->saveResponsible($responsible);
            }
            $rmOrder = Yii::$app->request->post('ToquvRmOrder');
            if($rmOrder){
                $model->saveItems($rmOrder);
            }
            $data = Yii::$app->request->post('ToquvRmOrderItems');
            if($data){
                $model->saveOrderItems($data);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models,
        ]);
    }
    
    /**
     * Updates an existing ToquvOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = ($model->toquvRmOrders)?$model->toquvRmOrders:[new ToquvRmOrder];
        $model->responsible = $model->responsibleMap;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (!empty($model->toquvRmOrders)) {
                foreach($model->toquvRmOrders as $key){
                    $key->delete();
                }
            }
            if (!empty($model->toquvOrdersResponsibles)) {
                foreach($model->toquvOrdersResponsibles as $key){
                    $key->delete();
                }
            }
            $responsible = Yii::$app->request->post('ToquvOrders')['responsible'];
            if($responsible){
                $model->saveResponsible($responsible);
            }
            $rmOrder = Yii::$app->request->post('ToquvRmOrder');
            if($rmOrder){
                $model->saveItems($rmOrder);
            }
            $data = Yii::$app->request->post('ToquvRmOrderItems');
            if($data){
                $model->saveOrderItems($data);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * @param null $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionInstructions($id = null){
        $instuction = new ToquvInstructions();
        $model = $this->findModel($id);
        return $this->render('instructions', [
            'instruction' => $instuction,
            'model' => $model
        ]);
    }

    public function actionAjax()
    {
        $model = new ToquvRmOrder;
        if (Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $id = ($data['id'])?$data['id']:'';
            $select = ($data['select'])?$data['select']:'';
            $data_type = $data['type'];
            switch ($data_type){
                case 'ACS':
                    $label = Yii::t('app','Aksessuar');
                    $placeholder = Yii::t('app','Aksessuar tanlang');
                    $type = ToquvRawMaterials::ACS;
                    break;
                default:
                    $label = Yii::t('app','Toquv Raw Materials ID');
                    $placeholder = Yii::t('app','Mato turini tanlang');
                    $type = ToquvRawMaterials::MATO;
            }
           return $this->renderAjax('ajax', [
               'model' => $model,
               'id' => $id,
               'select' => $select,
               'label' => $label,
               'placeholder' => $placeholder,
               'type' => $type
            ]);
        }
         return $this->redirect('index');
    }
    public function actionRmItems()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            if(!empty($data['id'])&&$data['id']!=0){
                $consist = ToquvRawMaterialConsist::find()->where(['fabric_type_id' => 3,'raw_material_id' => $data['id']])->one();
                $service = ($consist) ? 0.35 : 0.25;
                $model = ToquvRawMaterialIp::find()->where(['toquv_raw_material_id'=>$data['id']])->all();
                if(count($model)>0){
                    return $this->renderAjax('rm-items', [
                        'model' => $model,
                        'kg' => $data['kg'],
                        'service' => $service,
                    ]);
                }
            }else{
                return false;
            }
        }
    }

    /**
     * Deletes an existing ToquvOrders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->toquvRmOrders){
            foreach ($model->toquvRmOrders as $item){
                $item->delete();
            }
        }
        if($model->toquvRmOrders) {
            $model->delete();
        }

        return $this->redirect(['index']);
    }
    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSaveAndFinish($id){

        $model = $this->findModel($id);
        if($model->status < ToquvOrders::STATUS_INACTIVE){
            $model->responsible = 1;
            $model->status = ToquvOrders::STATUS_SAVED;
            $model->save();
        }
        return $this->redirect(['view','id' => $id]);
    }
    /**
     * Finds the ToquvOrders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvOrders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvOrders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
