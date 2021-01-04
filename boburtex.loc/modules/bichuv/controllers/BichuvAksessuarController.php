<?php

namespace app\modules\bichuv\controllers;

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocResponsible;
use app\modules\bichuv\models\BichuvMatoDocSearch;
use app\modules\bichuv\models\BichuvMatoOrderItems;
use app\modules\toquv\models\ToquvRawMaterials;
use Yii;
use app\modules\bichuv\models\BichuvMatoOrders;
use app\modules\bichuv\models\BichuvAksessuarSearch;
use app\modules\bichuv\models\BichuvDocSearch;
use app\modules\bichuv\controllers\BaseController;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BichuvAksessuarController implements the CRUD actions for BichuvMatoOrders model.
 */
class BichuvAksessuarController extends BaseController
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
    public function actionAksessuar()
    {
        $searchModel = new BichuvAksessuarSearch();
         $id=0;
        $dataProvider = $searchModel->search2(Yii::$app->request->queryParams,$id );

        return $this->render('aksessuar', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
     public function actionViewAksessuar()
     {
         $id = Yii::$app->request->isAjax
             ? Yii::$app->request->post('expandRowKey') // expand row da model->id shunda keladi
             : Yii::$app->request->get('id');
//              echo "<pre>";
//               print_r(Yii::$app->request->post('expandRowKey'));
//               print_r($id);
         $searchModel = new BichuvAksessuarSearch();

         $dataProvider = $searchModel->search2(Yii::$app->request->queryParams,$id);

          if (Yii::$app->request->isAjax) {
             return $this->renderAjax('view-aksessuar', [
                 'searchModel' => $searchModel,
                 'dataProvider' => $dataProvider,
             ]);
         }
         return $this->render('view-aksessuar', [
             'searchModel' => $searchModel,
             'dataProvider' => $dataProvider,
         ]);
     }
     public function actionIndex()
    {
        $searchModel = new BichuvAksessuarSearch();
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
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $models = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_ACS,'bichuv_mato_orders_id'=>$model->id])->all();
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
     * Updates an existing BichuvMatoOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_ACS,'bichuv_mato_orders_id'=>$model['id']])->all();
        $count = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_ACS,'bichuv_mato_orders_id'=>$model['id'],'status'=>1])->count();
        $responsible = BichuvDocResponsible::findOne(['type'=>2,'bichuv_mato_orders_id'=>$id]);
        $response = [];
        if(empty($responsible)){
            $responsible = new BichuvDocResponsible([
                'bichuv_mato_orders_id' => $id,
                'type' => 2,
                'users_id' => Yii::$app->user->id,
                'add_info' => Yii::t('app', "Men tayyor bo'lmagan aksessuarlarni mato bichulguncha yetkazib berishni o'z zimmamga olaman")
            ]);
        }
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $items = $data['BichuvMatoOrderItems'];
            $saved = false;
            if($responsible->load(Yii::$app->request->post())){
                if($responsible->save()){
                    $saved = true;
                }else{
                    if($responsible->hasErrors()){
                        $response['errors'] = $responsible->getErrors();
                    }
                    $saved = false;
                }
            }else{
                if($responsible){
                    $responsible->delete();
                }
                $saved = true;
            }
            if(!empty($items)&&$saved){
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    foreach ($items as $item) {
                        $bmoi = BichuvMatoOrderItems::findOne($item['id']);
                        if($bmoi){
                            if($item['status']==1){
                                $bmoi->status = 3;
                            }else{
                                $bmoi->status = 1;
                            }
                            if($bmoi->save()){
                                $saved = true;
                            }else{
                                if($bmoi->hasErrors()){
                                    $response['errors'] = array_merge($response['errors'],$bmoi->getErrors());
                                }
                                $saved = false;
                                break;
                            }
                        }
                    }
                    if($saved) {
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                }
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($saved) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = array_merge($response['errors'],$model->getErrors());
                }
                return $response;
            }
            if ($saved) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'models' => $models,
                'responsible' => $responsible,
                'count' => $count,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models,
            'responsible' => $responsible,
            'count' => $count,
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
        $filename = "bichuv-aksessuar_".date("d-m-Y-His").".xls";
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
