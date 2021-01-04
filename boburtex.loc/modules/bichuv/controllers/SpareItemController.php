<?php

namespace app\modules\bichuv\controllers;

use app\modules\bichuv\models\BichuvAcsProperties;
use app\modules\bichuv\models\SpareItemProperty;
use app\modules\bichuv\models\SpareItemPropertyList;
use Mpdf\Tag\I;
use Yii;
use app\modules\bichuv\models\SpareItem;
use app\modules\bichuv\models\SpareItemSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SpareItemController implements the CRUD actions for SpareItem model.
 */
class SpareItemController extends Controller
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
     * Lists all SpareItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpareItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new SpareItem();
        $property = new SpareItemProperty();

        $request = Yii::$app->request;
        if($request->isPost && $model->load($request->post())){
            $data = $request->post();
            $dataProvider = $searchModel->search(null,$data);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'property' => $property,
        ]);
    }

    /**
     * Displays a single SpareItem model.
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
     * Creates a new SpareItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;

        $model = new SpareItem();
        $spareItemProperty = [new SpareItemProperty()];

        if($model->load($request->post())){
            $data = $request->post();
            if($model->getSave($data)){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                return $this->redirect(['index']);
            }
            else{
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'spareItemProperty' => $spareItemProperty,
        ]);
    }

    /**
     * Updates an existing SpareItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $spareItemProperty = SpareItemProperty::find()->where(['spare_item_id' => $model->id])->all();

        if(empty($spareItemProperty)){
            $spareItemProperty = [new SpareItemProperty()];
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $data = Yii::$app->request->post();
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if($model->save()){
                        SpareItemProperty::deleteAll([
                           'spare_item_id' => $model->id
                        ]);
                        if(!empty($data['SpareItemProperty'])){
                            foreach($data['SpareItemProperty'] as $item){
                                $itemProperty = new SpareItemProperty();
                                $itemProperty->spare_item_id = $model->id;
                                $itemProperty->spare_item_property_list_id = $item['spare_item_property_list_id'];
                                $itemProperty->value = $item['value'];
                                $itemProperty->status = SpareItemProperty::STATUS_ACTIVE;
                                if($itemProperty->save()){
                                    $saved = true;
                                    unset($itemProperty);
                                }
                                else{
                                    $saved = false;
                                }
                            }
                        }
                    }else{
                        $saved = false;
                    }
                    if($saved) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return $this->redirect(['index']);
                    }else{
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                } catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                }
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = [];
                    if ($saved) {
                        $response['status'] = 0;
                        $response['message'] = Yii::t('app', 'Saved Successfully');
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                        $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
                    }
                    return $response;
                }

            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'spareItemProperty' => $spareItemProperty,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'spareItemProperty' => $spareItemProperty,
        ]);
    }

    /**
     * Deletes an existing SpareItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);
            $isDeleted = false;
            if($model->delete()){
                $isDeleted = true;
            }
            if($isDeleted){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        }catch (\Exception $e){
            Yii::info('Not saved' . $e, 'save');
        }
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            if($isDeleted){
                $response['status'] = 0;
                $response['message'] = Yii::t('app','Deleted Successfully');
            }
            return $response;
        }
        if($isDeleted){
            Yii::$app->session->setFlash('success',Yii::t('app','Deleted Successfully'));
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Hatolik yuz berdi'));
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "spare-item_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => SpareItem::find()->select([
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
     * Finds the SpareItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SpareItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SpareItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
