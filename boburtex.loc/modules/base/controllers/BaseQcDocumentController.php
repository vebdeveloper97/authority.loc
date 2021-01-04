<?php

namespace app\modules\base\controllers;

use app\modules\base\models\BaseQcAttachment;
use app\modules\base\models\BaseQcDocumentItems;
use Yii;
use app\modules\base\models\BaseQcDocument;
use app\modules\base\models\BaseQcDocumentSearch;
use app\modules\base\controllers\BaseController;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BaseQcDocumentController implements the CRUD actions for BaseQcDocument model.
 */
class BaseQcDocumentController extends BaseController
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
     * Lists all BaseQcDocument models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BaseQcDocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BaseQcDocument model.
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
     * Creates a new BaseQcDocument model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BaseQcDocument();
        $models = [new BaseQcDocumentItems()];
        $attachment = new BaseQcAttachment();
        $request = Yii::$app->request;
        if ($request->isPost){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                if (!empty($request->post('BaseQcDocumentItems'))){
                    unset($models);
                    foreach ($request->post('BaseQcDocumentItems') as $key => $item){
                        $models[$key] = new BaseQcDocumentItems(['scenario' => BaseQcDocumentItems::SCENARIO_CREATE]);
                    }
                }
                if ($model->load($request->post())
                    && Model::loadMultiple($models,$request->post())
                    && $attachment->load($request->post())) {
                    if ($saved = $model->save()){
                        foreach ($models as $item) {
                            $item->qc_document_id = $model->id;
                            if ($item->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                        if ($saved && !empty($attachment)){
                            foreach ($attachment['path'] as $attach){
                                $newAttachment = new BaseQcAttachment([
                                    'qc_document_id' =>$model->id,
                                    'path' => $attach,
                                    'name' => substr($attach, strrpos($attach,"/") + 1)
                                ]);
                                if ($newAttachment->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                    }
                }
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success',Yii::t('app', "Saved Successfully"));
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    Yii::$app->session->setFlash('error',Yii::t('app', "Saqlashda xatolik!"));
                    $transaction->rollBack();
                }
            }catch(\Exception $e){
                Yii::info('Not saved'.$e,'save');
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models,
            'attachment' => $attachment
        ]);
    }

    /**
     * Updates an existing BaseQcDocument model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = (!empty($model->baseQcDocumentItems)) ? $model->baseQcDocumentItems : [new BaseQcDocumentItems()];
        $attachment = new BaseQcAttachment();
        if (!empty($model->baseQcAttachments)){
            $img = [];
            foreach ($model->baseQcAttachments as $key => $item){
                $img[] = $item['path'];
            }
            $attachment['path'] = $img;
        }
        $request = Yii::$app->request;
        if ($request->isPost){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                if (!empty($request->post('BaseQcDocumentItems'))){
                    unset($models);
                    foreach ($request->post('BaseQcDocumentItems') as $key => $item){
                        $models[$key] = new BaseQcDocumentItems(['scenario' => BaseQcDocumentItems::SCENARIO_CREATE]);
                    }
                }
                if ($model->load($request->post())
                    && Model::loadMultiple($models,$request->post())
                    && $attachment->load($request->post())) {
                    if ($saved = $model->save()){
                        BaseQcDocumentItems::deleteAll(['qc_document_id' => $id]);
                        BaseQcAttachment::deleteAll(['qc_document_id' => $id]);
                        foreach ($models as $item) {
                            $item->qc_document_id = $model->id;
                            if ($item->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                        if ($saved && !empty($attachment)){
                            foreach ($attachment['path'] as $attach){
                                $newAttachment = new BaseQcAttachment([
                                    'qc_document_id' =>$model->id,
                                    'path' => $attach,
                                    'name' => substr($attach, strrpos($attach,"/") + 1)
                                ]);
                                if ($newAttachment->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break;
                                }
                            }
                        }
                    }
                }
                if($saved){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success',Yii::t('app', "Saved Successfully"));
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    Yii::$app->session->setFlash('error',Yii::t('app', "Saqlashda xatolik!"));
                    $transaction->rollBack();
                }
            }catch(\Exception $e){
                Yii::info('Not saved'.$e,'save');
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models,
            'attachment' => $attachment
        ]);
    }

    /**
     * Deletes an existing BaseQcDocument model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        BaseQcDocumentItems::deleteAll(['qc_document_id' => $id]);
        BaseQcAttachment::deleteAll(['qc_document_id' => $id]);
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "base-qc-document_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => BaseQcDocument::find()->select([
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
     * Finds the BaseQcDocument model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaseQcDocument the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BaseQcDocument::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
