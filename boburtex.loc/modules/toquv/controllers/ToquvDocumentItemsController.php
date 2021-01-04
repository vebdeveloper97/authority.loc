<?php

namespace app\modules\toquv\controllers;

use Yii;
use app\modules\toquv\models\ToquvDocumentItems;
use app\modules\toquv\models\ToquvDocumentItemsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ToquvDocumentItemsController implements the CRUD actions for ToquvDocumentItems model.
 */
class ToquvDocumentItemsController extends BaseController
{
    /**
     * Lists all ToquvDocumentItems models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToquvDocumentItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ToquvDocumentItems model.
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
     * Creates a new ToquvDocumentItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ToquvDocumentItems();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ToquvDocumentItems model.
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
     * Deletes an existing ToquvDocumentItems model.
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
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionQuantityChange()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $id  = $data['pk'];
        $qty = $data['value'];
        $remain = $data['remain'];
        $oldValue = $data['oldValue'];
        $result = ['success'=>false, 'msg' => 'Unknown error'];
        if($remain < $qty){
            $result['msg'] = Yii::t('app','Kiritilayotgan miqdor {qty} qoldiqdan katta bola olmaydi', ['qty' => $remain]);
            return $result;
        }
        $sql = "UPDATE toquv_document_items SET quantity = %d WHERE id = %d";
        $sql = sprintf($sql, $qty, $id);
        $editQty = Yii::$app->db->createCommand($sql)->execute();
        if($editQty) {
            $result = ['success' => true, 'value' => $qty, 'old' => $oldValue];
        }
        return $result;
    }

    /**
     * Finds the ToquvDocumentItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToquvDocumentItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToquvDocumentItems::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
