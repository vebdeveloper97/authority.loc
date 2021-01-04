<?php

namespace app\modules\base\controllers;

use app\modules\base\models\ModelOrdersRelFsAttachments;
use Yii;
use app\modules\base\models\ModelOrdersFs;
use app\modules\base\models\ModelOrdersFsSearch;
use app\modules\base\controllers\BaseController;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ModelOrdersFsController implements the CRUD actions for ModelOrdersFs model.
 */
class ModelOrdersFsController extends BaseController
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
     * Lists all ModelOrdersFs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModelOrdersFsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ModelOrdersFs model.
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
     * Creates a new ModelOrdersFs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ModelOrdersFs();
        /** begin Modal bilan yuklash uchun ishlatiladi qaysi zakaz boyicha $id, qaysi itemsi $mId */
        $id = Yii::$app->request->get('id');
        $mId = Yii::$app->request->get('mId');
        if(!isset($id) || empty($id)){
            Yii::$app->session->setFlash('error', Yii::t('app', "Buyurtmaning id si mavjud emas!"));
            return $this->redirect(Yii::$app->request->referrer);
        }
        if(!isset($mId) || empty($mId)){
            Yii::$app->session->setFlash('error', Yii::t('app', "Buyurtmaning id si mavjud emas!"));
            return $this->redirect(Yii::$app->request->referrer);
        }
        $model->model_orders_id = $id;
        $model->model_orders_items_id = $mId;
        /** end Modal bilan yuklash uchun ishlatiladi qaysi zakaz boyicha $id, qaysi itemsi $mId */

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                if($model->save()){
                    if($model->attachments_id){
                        foreach ($model->attachments_id as $item) {
                            $modelAttachments = new ModelOrdersRelFsAttachments();
                            $modelAttachments->setAttributes([
                               'model_orders_fs_id' => $model->id,
                               'attachments_id' => $item,
                            ]);
                            if($modelAttachments->save())
                            {
                                $saved = true;
                                unset($modelAttachments);
                            }
                            else
                            {
                                $saved = false;
                                break;
                            }
                        }
                    }
                }
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                    return $this->redirect(Yii::$app->request->referrer);
                }
                else{
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
            catch (\Exception $e){
                Yii::info('Error messgae'.$e->getMessage(), 'save');
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ModelOrdersFs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ModelOrdersFs model.
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
        $filename = "model-orders-fs_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => ModelOrdersFs::find()->select([
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
     * Finds the ModelOrdersFs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelOrdersFs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModelOrdersFs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
