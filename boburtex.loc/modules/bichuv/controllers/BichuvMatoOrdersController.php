<?php

namespace app\modules\bichuv\controllers;

use app\modules\base\models\ModelOrders;
use app\modules\bichuv\models\BichuvDocResponsible;
use app\modules\bichuv\models\BichuvMatoOrderItems;
use app\modules\tikuv\models\TikuvOutcomeProductsPack;
use app\modules\toquv\models\ToquvRawMaterials;
use Yii;
use app\modules\bichuv\models\BichuvMatoOrders;
use app\modules\bichuv\models\BichuvMatoOrdersSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BichuvMatoOrdersController implements the CRUD actions for BichuvMatoOrders model.
 */
class BichuvMatoOrdersController extends BaseController
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
     * Lists all BichuvMatoOrders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BichuvMatoOrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BichuvMatoOrders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        $id = Yii::$app->request->isAjax
            ? Yii::$app->request->post('expandRowKey') // expand row da model->id shunda keladi
            : Yii::$app->request->get('id');

        $model = $this->findModel($id);
        $models = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_MATO,'bichuv_mato_orders_id'=>$model->id])->all();
        $models_aks = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_ACS,'bichuv_mato_orders_id'=>$model->id])->all();
        $responsible = BichuvDocResponsible::findOne(['type'=>2,'bichuv_mato_orders_id'=>$id]);
        if(empty($responsible)){
            $responsible = BichuvDocResponsible::findOne(['bichuv_mato_orders_id'=>$id,'type'=>1]);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('view', [
                'model' => $model,
                'models' => $models,
                'models_aks' => $models_aks,
                'responsible' => $responsible,
            ]);
        }

        return $this->render('view', [
            'model' => $model,
            'models' => $models,
            'models_aks' => $models_aks,
            'responsible' => $responsible,
            'is_view' => true,
        ]);
    }

    /**
     * Creates a new BichuvMatoOrders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BichuvMatoOrders();
        $models = [new BichuvMatoOrderItems()];
        $model->reg_date = date('d.m.Y');
        $lastRec = $model::find()->select(['id'])->asArray()->orderBy(['id' => SORT_DESC])->one();
        $docNumber = $lastRec['id']+1;
        $model->doc_number = $docNumber;
        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $saved = false;
                if ($model->save()) {
                    $bmi = $data['BichuvMatoOrderItems'];
                    if(!empty($bmi)){
                        foreach ($bmi as $item) {
                            $bmoi = new BichuvMatoOrderItems([
                                'bichuv_mato_orders_id' => $model['id'],
                                'entity_id' => $item['entity_id'],
                                'entity_type' => $item['entity_type'],
                                'name' => $item['name'],
                                'quantity' => $item['quantity'],
                                'roll_count' => $item['roll_count'],
                                'count' => $item['count'],
                                'moi_id' => $model['model_orders_items_id'],
                                'mop_id' => $item['mop_id'],
                            ]);
                            if($bmoi->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                }
                if($saved){
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    $transaction->rollBack();
                }
            }catch (\Exception $e){
                Yii::info('Not saved BMO'. $e, 'save');
                $transaction->rollBack();
            }
        }
        return $this->render('create', [
            'model' => $model,
            'models' => $models,
        ]);
    }

    /**
     * Updates an existing BichuvMatoOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = $model->bichuvMatoOrderItems;
        if(empty($models)){
            $models =  [new BichuvMatoOrderItems()];
        }
        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                BichuvMatoOrderItems::deleteAll(['bichuv_mato_orders_id'=>$model['id']]);
                $saved = false;
                if ($model->save()) {
                    $bmi = $data['BichuvMatoOrderItems'];
                    if(!empty($bmi)){
                        foreach ($bmi as $item) {
                            $bmoi = new BichuvMatoOrderItems([
                                'bichuv_mato_orders_id' => $model['id'],
                                'entity_id' => $item['entity_id'],
                                'entity_type' => $item['entity_type'],
                                'name' => $item['name'],
                                'quantity' => $item['quantity'],
                                'roll_count' => $item['roll_count'],
                                'count' => $item['count'],
                                'moi_id' => $model['model_orders_items_id'],
                                'mop_id' => $item['mop_id'],
                            ]);
                            if($bmoi->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                }
                if($saved){
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    $transaction->rollBack();
                }
            }catch (\Exception $e){
                Yii::info('Not saved BMO'. $e, 'save');
                $transaction->rollBack();
            }
        }
        return $this->render('update', [
            'model' => $model,
            'models' => $models,

        ]);
    }

    /**
     * Deletes an existing BichuvMatoOrders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->status<$model::STATUS_SAVED) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                BichuvMatoOrderItems::deleteAll(['bichuv_mato_orders_id' => $model['id']]);
                if ($model->delete()) {
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }
        return $this->redirect(['index']);
    }
    public function actionSaveAndFinish($id)
    {
        $model = $this->findModel($id);
        if($model->status<$model::STATUS_INACTIVE){
            $model->updateCounters(['status'=>2]);
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }
    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "bichuv-mato-orders_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BichuvMatoOrders::find()->select([
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
    public function actionOrdersList()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id = $parents[0];
                $res = BichuvMatoOrders::getOrdersList($id,false,ModelOrders::STATUS_SAVED,'>');
                if(!empty($res)){
                    foreach ($res as $item) {
                        $out[] = [
                            'id' => $item['id'],
                            'name' => $item['doc_number'].' - '.number_format($item['sum'],0,'','').' ('.date('d.m.Y H:i',strtotime($item['reg_date'])).')'
                        ];
                    }
                }
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }
    public function actionOrdersItemsList()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id = $parents[0];
                $res = BichuvMatoOrders::getOrderItemsList($id);
                if(!empty($res)){
                    foreach ($res as $item) {
                        $out[] = [
                            'id' => $item['id'],
                            'name' => "SM-{$item['id']} - ({$item['artikul']} {$item['model']}) - ({$item['code']}} - (".number_format($item['summa'],0,"","").") - (".date('d.m.Y H:i',strtotime($item['load_date'])).")"
                        ];
                    }
                }
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }
    public function actionAjaxRequest()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $response = [];
        $response['status'] = 0;
        $response['message'] = Yii::t('app', "Ma'lumotlar topilmadi");
        $response['results'] = [];
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (!empty($data)) {
                $id = $data['id'];
                $res = BichuvMatoOrders::getOrderToquvList($id);
                if(!empty($res)){
                    foreach ($res as $item) {
                        $response['results']['mato'][] = [
                            'id' => $item['id'],
                            'moi_id' => $item['moi_id'],
                            'mop_id' => $item['mop_id'],
                            'name' => "{$item['mato']}  - (".number_format($item['quantity'],2,"","").") - ({$item['finish_en']} | {$item['finish_gramaj']}) - ({$item['color']}}",
                            'quantity' => $item['quantity'],
                            'count' => $item['count'],
                            'type' => ToquvRawMaterials::ENTITY_TYPE_MATO
                        ];
                    }
                }
                /*$res = BichuvMatoOrders::getOrderToquvAcsList($id);
                if(!empty($res)){
                    foreach ($res as $item) {
                        $response['results']['toquv_acs'][] = [
                            'id' => $item['id'],
                            'moi_id' => $item['moi_id'],
                            'mop_id' => $item['mop_id'],
                            'name' => "{$item['mato']}",
                            'quantity' => $item['quantity'],
                            'count' => $item['count'],
                            'type' => ToquvRawMaterials::ENTITY_TYPE_MATO
                        ];
                    }
                }*/
                $res = BichuvMatoOrders::getOrderAcsList($id);
                if(!empty($res)){
                    foreach ($res as $item) {
                        $response['results']['acs'][] = [
                            'id' => $item['id'],
                            'moi_id' => $item['moi_id'],
                            'name' => "{$item['acs']}  - (".number_format($item['count'],0,"","").")",
                            'quantity' => $item['quantity'],
                            'count' => $item['count'],
                            'summa' => $item['summa'],
                            'type' => ToquvRawMaterials::ENTITY_TYPE_ACS
                        ];
                    }
                }
                if(!empty($response['results']['mato'])||!empty($response['results']['acs'])){
                    $response['message'] = 'Success';
                    $response['status'] = 1;
                }
            }
        }
        return $response;
    }
    /**
     * Finds the BichuvMatoOrders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BichuvMatoOrders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BichuvMatoOrders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
